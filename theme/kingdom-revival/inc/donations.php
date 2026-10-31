<?php
/**
 * Online giving: PayPal's hosted checkout, verified payment notifications (IPN), saved donations and receipts.
 *
 * Donors pay on PayPal's own pages (paypal.com), by PayPal balance or card; no card data ever reaches WordPress.
 * Each checkout link (built in assets/js/donate.js) carries a notify_url pointing at this file's handler. PayPal
 * posts every payment, refund and subscription change there; the handler asks PayPal to confirm the message
 * (cmd=_notify-validate) before anything is saved, so a forged notification records nothing. No API keys are needed.
 *
 * Verified gifts are saved as Donations (Kingdom Revival → Donations); the donor is emailed a thank-you receipt and
 * the ministry a notification. Donors return to the Donate page with ?thanks=1, which shows the thank-you view.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is PayPal's hosted checkout (one-time + monthly, with notifications) set up? Otherwise the PayPal.me link is used.
 */
function krm_paypal_checkout() {
	return (bool) is_email( krm_opt( 'paypal_email' ) );
}

/**
 * PayPal's site for the chosen mode: the live site or the sandbox (test) site.
 */
function krm_paypal_host() {
	return 'sandbox' === krm_opt( 'paypal_mode' ) ? 'https://www.sandbox.paypal.com' : 'https://www.paypal.com';
}

/**
 * Address PayPal posts payment notifications to.
 */
function krm_paypal_notify_url() {
	return admin_url( 'admin-post.php?action=krm_paypal_ipn' );
}

/**
 * Donation statuses: PayPal payment_status => label.
 */
function krm_donation_statuses() {
	return array(
		'Completed' => __( 'Completed', 'kingdom-revival' ),
		'Pending'   => __( 'Pending', 'kingdom-revival' ),
		'Refunded'  => __( 'Refunded', 'kingdom-revival' ),
		'Reversed'  => __( 'Reversed', 'kingdom-revival' ),
	);
}

/**
 * The gift a donor returned from PayPal with (Donate page ?thanks=1), or null.
 *
 * Amount, frequency and fund come from the donor's own form choices carried in the return link. They are only
 * displayed back to the donor and are limited to a number and the form's own options.
 */
function krm_gift_return() {
	if ( empty( $_GET['thanks'] ) || 'donate' !== krm_design_template() ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return null;
	}
	$amount = isset( $_GET['amount'] ) ? (float) preg_replace( '/[^0-9.]/', '', sanitize_text_field( wp_unslash( $_GET['amount'] ) ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$fund   = isset( $_GET['for'] ) ? sanitize_text_field( wp_unslash( $_GET['for'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	return array(
		'amount'  => $amount > 0 && $amount < 1000000 ? $amount : 0,
		'monthly' => isset( $_GET['frequency'] ) && 'Monthly' === $_GET['frequency'], // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		'fund'    => krm_valid_fund( $fund ) ? $fund : '',
	);
}

/**
 * Is this one of the gift form's options ("Where it is needed most" or a published ministry)?
 *
 * @param string $fund Fund name.
 */
function krm_valid_fund( $fund ) {
	if ( 'Where it is needed most' === $fund ) {
		return true;
	}
	foreach ( krm_ministries() as $ministry ) {
		if ( $ministry['title'] === $fund ) {
			return true;
		}
	}
	return false;
}

/**
 * "$50" or "$12.50".
 *
 * @param float  $amount   Amount.
 * @param string $currency Currency code.
 */
function krm_money( $amount, $currency = 'USD' ) {
	$amount = (float) $amount;
	$text   = floor( $amount ) === $amount ? number_format( $amount ) : number_format( $amount, 2 );
	return 'USD' === $currency ? '$' . $text : $text . ' ' . $currency;
}

/* The thank-you view is not a page of its own; keep it out of search results (the canonical is the Donate page). */
add_filter(
	'wp_robots',
	function ( $robots ) {
		if ( krm_gift_return() ) {
			$robots['noindex'] = true;
			$robots['follow']  = true;
		}
		return $robots;
	}
);
add_filter(
	'wpseo_robots_array',
	function ( $robots ) {
		if ( krm_gift_return() ) {
			$robots['index'] = 'noindex';
		}
		return $robots;
	}
);
add_filter(
	'rank_math/frontend/robots',
	function ( $robots ) {
		if ( krm_gift_return() ) {
			$robots['index'] = 'noindex';
		}
		return $robots;
	}
);

/* ---------- Saved donations (private) ---------- */

add_action(
	'init',
	function () {
		register_post_type(
			'krm_donation',
			array(
				'labels'          => array(
					'name'          => __( 'Donations', 'kingdom-revival' ),
					'singular_name' => __( 'Donation', 'kingdom-revival' ),
					'all_items'     => __( 'Donations', 'kingdom-revival' ),
					'edit_item'     => __( 'Donation', 'kingdom-revival' ),
					'search_items'  => __( 'Search donations', 'kingdom-revival' ),
					'not_found'     => __( 'No donations yet. Gifts made through PayPal’s checkout appear here once PayPal confirms them.', 'kingdom-revival' ),
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => 'krm-settings',
				'show_in_rest'    => false,
				'supports'        => array( 'title' ),
				'capability_type' => 'post',
				// Donors' details: Editors and Administrators only (not Authors or Contributors).
				'capabilities'    => array(
					'create_posts' => 'do_not_allow',
					'edit_posts'   => 'edit_others_posts',
				),
				'map_meta_cap'    => true,
				'rewrite'         => false,
				'query_var'       => false,
			)
		);
	}
);

/**
 * The saved donation with a PayPal transaction ID, or 0.
 *
 * @param string $txn_id PayPal transaction ID.
 */
function krm_find_donation( $txn_id ) {
	if ( '' === $txn_id ) {
		return 0;
	}
	$found = get_posts(
		array(
			'post_type'      => 'krm_donation',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_krm_txn_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $txn_id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'no_found_rows'  => true,
		)
	);
	return $found ? (int) $found[0] : 0;
}

/* ---------- PayPal payment notifications (IPN) ---------- */

/**
 * Receive a PayPal notification: confirm it with PayPal, then record it.
 */
function krm_paypal_ipn() {
	$raw = file_get_contents( 'php://input' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! $raw || ! krm_paypal_checkout() ) {
		status_header( 400 );
		exit;
	}

	// Ask PayPal whether it sent this exact message. The live and sandbox sites each confirm only their own.
	$verify_host = 'sandbox' === krm_opt( 'paypal_mode' ) ? 'https://ipnpb.sandbox.paypal.com' : 'https://ipnpb.paypal.com';
	$response    = wp_remote_post(
		$verify_host . '/cgi-bin/webscr',
		array(
			'body'        => 'cmd=_notify-validate&' . $raw,
			'timeout'     => 30,
			'httpversion' => '1.1',
			'headers'     => array(
				'Content-Type' => 'application/x-www-form-urlencoded',
				'User-Agent'   => 'Kingdom-Revival-Ministries-IPN',
			),
		)
	);
	if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
		// PayPal could not be reached: answer with an error so PayPal sends the notification again later.
		status_header( 503 );
		exit;
	}
	if ( 'VERIFIED' !== trim( wp_remote_retrieve_body( $response ) ) ) {
		status_header( 200 );
		exit;
	}

	$ipn = array();
	parse_str( $raw, $ipn );
	$ipn = array_map(
		function ( $value ) {
			return is_string( $value ) ? sanitize_text_field( $value ) : '';
		},
		$ipn
	);
	$get = function ( $key ) use ( $ipn ) {
		return isset( $ipn[ $key ] ) ? $ipn[ $key ] : '';
	};

	// Only gifts to this ministry's own PayPal account count.
	$receiver = strtolower( '' !== $get( 'receiver_email' ) ? $get( 'receiver_email' ) : $get( 'business' ) );
	if ( strtolower( krm_opt( 'paypal_email' ) ) !== $receiver ) {
		status_header( 200 );
		exit;
	}

	krm_record_paypal_notification( $ipn );
	status_header( 200 );
	exit;
}
add_action( 'admin_post_nopriv_krm_paypal_ipn', 'krm_paypal_ipn' );
add_action( 'admin_post_krm_paypal_ipn', 'krm_paypal_ipn' );

/**
 * Save or update a donation from a verified PayPal notification.
 *
 * @param array $ipn Sanitized notification fields.
 */
function krm_record_paypal_notification( $ipn ) {
	$get    = function ( $key ) use ( $ipn ) {
		return isset( $ipn[ $key ] ) ? $ipn[ $key ] : '';
	};
	$type   = $get( 'txn_type' );
	$status = $get( 'payment_status' );

	// A monthly gift was cancelled or ended: mark its donations.
	if ( in_array( $type, array( 'subscr_cancel', 'subscr_eot' ), true ) && '' !== $get( 'subscr_id' ) ) {
		$ids = get_posts(
			array(
				'post_type'      => 'krm_donation',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_key'       => '_krm_subscr_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $get( 'subscr_id' ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);
		foreach ( $ids as $id ) {
			update_post_meta( $id, '_krm_subscription_ended', current_time( 'mysql' ) );
		}
		if ( 'subscr_cancel' === $type ) {
			$name = trim( $get( 'first_name' ) . ' ' . $get( 'last_name' ) );
			/* translators: %s: donor name or email */
			krm_notify_ministry( sprintf( __( 'Monthly gift cancelled — %s', 'kingdom-revival' ), '' !== $name ? $name : $get( 'payer_email' ) ), sprintf( /* translators: %s: PayPal subscription ID */ __( 'PayPal subscription %s was cancelled. No further monthly payments will be taken.', 'kingdom-revival' ), $get( 'subscr_id' ) ) );
		}
		return;
	}

	// A refund or reversal arrives as its own notification pointing at the original payment.
	if ( in_array( $status, array( 'Refunded', 'Reversed' ), true ) ) {
		$id = krm_find_donation( $get( 'parent_txn_id' ) );
		if ( $id ) {
			update_post_meta( $id, '_krm_status', $status );
			krm_notify_ministry(
				/* translators: 1: Refunded/Reversed, 2: donation title */
				sprintf( __( 'Donation %1$s — %2$s', 'kingdom-revival' ), strtolower( $status ), get_post_field( 'post_title', $id ) ),
				admin_url( 'post.php?post=' . $id . '&action=edit' )
			);
		}
		return;
	}

	if ( ! in_array( $type, array( 'web_accept', 'subscr_payment' ), true ) || ! in_array( $status, array( 'Completed', 'Pending' ), true ) || '' === $get( 'txn_id' ) ) {
		return;
	}

	$id = krm_find_donation( $get( 'txn_id' ) );
	if ( ! $id ) {
		$fund     = krm_valid_fund( $get( 'custom' ) ) ? $get( 'custom' ) : 'Where it is needed most';
		$monthly  = 'subscr_payment' === $type;
		$name     = trim( $get( 'first_name' ) . ' ' . $get( 'last_name' ) );
		$currency = '' !== $get( 'mc_currency' ) ? $get( 'mc_currency' ) : 'USD';
		$data     = array(
			'name'      => $name,
			'email'     => sanitize_email( $get( 'payer_email' ) ),
			'amount'    => (float) $get( 'mc_gross' ),
			'fee'       => (float) $get( 'mc_fee' ),
			'currency'  => $currency,
			'frequency' => $monthly ? 'Monthly' : 'One-time',
			'fund'      => $fund,
			'country'   => $get( 'residence_country' ),
			'txn_id'    => $get( 'txn_id' ),
			'subscr_id' => $get( 'subscr_id' ),
			'test'      => '1' === $get( 'test_ipn' ),
		);
		$id       = wp_insert_post(
			array(
				'post_type'   => 'krm_donation',
				'post_status' => 'private',
				'post_title'  => krm_money( $data['amount'], $currency ) . ( $monthly ? ' / month' : '' ) . ' — ' . ( '' !== $name ? $name : $data['email'] ),
			)
		);
		if ( ! $id || is_wp_error( $id ) ) {
			return;
		}
		update_post_meta( $id, '_krm_donation', $data );
		update_post_meta( $id, '_krm_txn_id', $data['txn_id'] );
		if ( '' !== $data['subscr_id'] ) {
			update_post_meta( $id, '_krm_subscr_id', $data['subscr_id'] );
		}
		krm_notify_ministry(
			/* translators: %s: donation title */
			sprintf( __( 'New donation: %s', 'kingdom-revival' ), get_post_field( 'post_title', $id ) ) . ( $data['test'] ? ' (sandbox test)' : '' ),
			krm_donation_lines( $data ) . "\n\n" . sprintf( /* translators: %s: admin URL */ __( 'View all donations: %s', 'kingdom-revival' ), admin_url( 'edit.php?post_type=krm_donation' ) ),
			$data['email']
		);
	}
	update_post_meta( $id, '_krm_status', $status );

	// The donor's receipt goes out once the money has actually arrived (an eCheck stays Pending for a few days).
	if ( 'Completed' === $status && ! get_post_meta( $id, '_krm_receipt_sent', true ) ) {
		krm_send_receipt( $id );
	}
}

/**
 * Plain-text summary of a donation.
 *
 * @param array $data Donation data.
 */
function krm_donation_lines( $data ) {
	return implode(
		"\n",
		array(
			__( 'Donor', 'kingdom-revival' ) . ': ' . ( '' !== $data['name'] ? $data['name'] : '—' ),
			__( 'Email', 'kingdom-revival' ) . ': ' . $data['email'],
			__( 'Amount', 'kingdom-revival' ) . ': ' . krm_money( $data['amount'], $data['currency'] ),
			__( 'Frequency', 'kingdom-revival' ) . ': ' . $data['frequency'],
			__( 'For', 'kingdom-revival' ) . ': ' . $data['fund'],
			__( 'Country', 'kingdom-revival' ) . ': ' . ( '' !== $data['country'] ? $data['country'] : '—' ),
			__( 'PayPal transaction', 'kingdom-revival' ) . ': ' . $data['txn_id'],
		)
	);
}

/**
 * Email the ministry (the form notification addresses in Settings).
 *
 * @param string $subject  Subject, without the [Website] prefix.
 * @param string $body     Plain-text body.
 * @param string $reply_to Optional donor email for Reply-To.
 */
function krm_notify_ministry( $subject, $body, $reply_to = '' ) {
	$to = array_filter( array_map( 'trim', explode( ',', krm_opt( 'form_recipient' ) ) ) );
	if ( ! $to ) {
		$to = array( get_option( 'admin_email' ) );
	}
	$headers = is_email( $reply_to ) ? array( 'Reply-To: <' . $reply_to . '>' ) : array();
	/* translators: %s: subject */
	wp_mail( $to, sprintf( __( '[Website] %s', 'kingdom-revival' ), $subject ), $body, $headers );
}

/**
 * Email the donor the thank-you receipt from Settings → Online giving.
 *
 * @param int $id Donation ID.
 */
function krm_send_receipt( $id ) {
	$data = (array) get_post_meta( $id, '_krm_donation', true );
	if ( 'off' === krm_opt( 'donation_receipts' ) || empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
		return;
	}
	$post  = get_post( $id );
	$parts = explode( ' ', trim( $data['name'] ) );
	$vars  = array(
		'{name}'        => '' !== $parts[0] ? $parts[0] : __( 'friend', 'kingdom-revival' ),
		'{full_name}'   => '' !== $data['name'] ? $data['name'] : $data['email'],
		'{amount}'      => krm_money( $data['amount'], $data['currency'] ),
		'{frequency}'   => 'Monthly' === $data['frequency'] ? __( 'monthly', 'kingdom-revival' ) : __( 'one-time', 'kingdom-revival' ),
		'{fund}'        => 'Where it is needed most' === $data['fund'] ? __( 'the mission, where it is needed most', 'kingdom-revival' ) : $data['fund'],
		'{date}'        => get_the_date( get_option( 'date_format' ), $post ),
		'{transaction}' => $data['txn_id'],
	);
	$subject = krm_fill( strtr( krm_opt( 'receipt_subject' ), $vars ) );
	$body    = krm_fill( strtr( krm_opt( 'receipt_message' ), $vars ) );
	$headers = is_email( krm_opt( 'email' ) ) ? array( 'Reply-To: <' . krm_opt( 'email' ) . '>' ) : array();
	if ( wp_mail( $data['email'], $subject, $body, $headers ) ) {
		update_post_meta( $id, '_krm_receipt_sent', current_time( 'mysql' ) );
	}
}

/* ---------- Donations screens ---------- */

add_filter(
	'manage_krm_donation_posts_columns',
	function () {
		return array(
			'cb'          => '<input type="checkbox">',
			'title'       => __( 'Donation', 'kingdom-revival' ),
			'krm_email'   => __( 'Email', 'kingdom-revival' ),
			'krm_fund'    => __( 'For', 'kingdom-revival' ),
			'krm_status'  => __( 'Status', 'kingdom-revival' ),
			'krm_receipt' => __( 'Receipt sent', 'kingdom-revival' ),
			'date'        => __( 'Date', 'kingdom-revival' ),
		);
	}
);
add_action(
	'manage_krm_donation_posts_custom_column',
	function ( $col, $post_id ) {
		$data = (array) get_post_meta( $post_id, '_krm_donation', true );
		if ( 'krm_email' === $col && ! empty( $data['email'] ) ) {
			echo '<a href="' . esc_url( 'mailto:' . $data['email'] ) . '">' . esc_html( $data['email'] ) . '</a>';
		} elseif ( 'krm_fund' === $col && ! empty( $data['fund'] ) ) {
			echo esc_html( $data['fund'] );
		} elseif ( 'krm_status' === $col ) {
			$statuses = krm_donation_statuses();
			$status   = get_post_meta( $post_id, '_krm_status', true );
			echo esc_html( isset( $statuses[ $status ] ) ? $statuses[ $status ] : $status );
			if ( get_post_meta( $post_id, '_krm_subscription_ended', true ) ) {
				echo ' — ' . esc_html__( 'monthly gift ended', 'kingdom-revival' );
			}
			if ( ! empty( $data['test'] ) ) {
				echo ' — <strong>' . esc_html__( 'sandbox test', 'kingdom-revival' ) . '</strong>';
			}
		} elseif ( 'krm_receipt' === $col ) {
			echo get_post_meta( $post_id, '_krm_receipt_sent', true ) ? esc_html__( 'Yes', 'kingdom-revival' ) : esc_html__( 'No', 'kingdom-revival' );
		}
	},
	10,
	2
);

add_action(
	'pre_get_posts',
	function ( $q ) {
		if ( is_admin() && $q->is_main_query() && 'krm_donation' === $q->get( 'post_type' ) && ! $q->get( 'post_status' ) ) {
			$q->set( 'post_status', 'private' );
		}
	}
);

add_action(
	'restrict_manage_posts',
	function ( $post_type ) {
		if ( 'krm_donation' === $post_type ) {
			$export = wp_nonce_url( admin_url( 'admin-post.php?action=krm_export_donations' ), 'krm_export_donations' );
			echo ' <a class="button" href="' . esc_url( $export ) . '">' . esc_html__( 'Export CSV', 'kingdom-revival' ) . '</a>';
		}
	}
);

/* Read-only view of a donation. */
add_action(
	'add_meta_boxes_krm_donation',
	function () {
		remove_meta_box( 'submitdiv', 'krm_donation', 'side' );
		add_meta_box(
			'krm-donation',
			__( 'Details', 'kingdom-revival' ),
			function ( $post ) {
				$data     = (array) get_post_meta( $post->ID, '_krm_donation', true );
				$statuses = krm_donation_statuses();
				$status   = get_post_meta( $post->ID, '_krm_status', true );
				$rows     = array(
					__( 'Donor', 'kingdom-revival' )              => isset( $data['name'] ) ? $data['name'] : '',
					__( 'Email', 'kingdom-revival' )              => isset( $data['email'] ) ? $data['email'] : '',
					__( 'Amount', 'kingdom-revival' )             => isset( $data['amount'] ) ? krm_money( $data['amount'], $data['currency'] ) : '',
					__( 'PayPal fee', 'kingdom-revival' )         => ! empty( $data['fee'] ) ? krm_money( $data['fee'], $data['currency'] ) : '',
					__( 'Frequency', 'kingdom-revival' )          => isset( $data['frequency'] ) ? $data['frequency'] : '',
					__( 'For', 'kingdom-revival' )                => isset( $data['fund'] ) ? $data['fund'] : '',
					__( 'Country', 'kingdom-revival' )            => isset( $data['country'] ) ? $data['country'] : '',
					__( 'Status', 'kingdom-revival' )             => isset( $statuses[ $status ] ) ? $statuses[ $status ] : $status,
					__( 'PayPal transaction', 'kingdom-revival' ) => isset( $data['txn_id'] ) ? $data['txn_id'] : '',
					__( 'PayPal subscription', 'kingdom-revival' ) => isset( $data['subscr_id'] ) ? $data['subscr_id'] : '',
					__( 'Monthly gift ended', 'kingdom-revival' ) => (string) get_post_meta( $post->ID, '_krm_subscription_ended', true ),
					__( 'Receipt emailed', 'kingdom-revival' )    => (string) get_post_meta( $post->ID, '_krm_receipt_sent', true ),
					__( 'Received', 'kingdom-revival' )           => get_the_date( 'F j, Y g:i a', $post ),
				);
				echo '<table class="widefat striped"><tbody>';
				foreach ( $rows as $label => $value ) {
					echo '<tr><th style="width:200px">' . esc_html( $label ) . '</th><td>' . esc_html( '' !== (string) $value ? $value : '—' ) . '</td></tr>';
				}
				echo '</tbody></table>';
				if ( ! empty( $data['test'] ) ) {
					echo '<p><strong>' . esc_html__( 'This was a PayPal sandbox (test) payment; no money moved.', 'kingdom-revival' ) . '</strong></p>';
				}
				if ( ! empty( $data['email'] ) ) {
					echo '<p><a class="button button-primary" href="' . esc_url( 'mailto:' . $data['email'] ) . '">' . esc_html__( 'Email the donor', 'kingdom-revival' ) . '</a></p>';
				}
			},
			'krm_donation',
			'normal',
			'high'
		);
	}
);

/* CSV export. */
add_action(
	'admin_post_krm_export_donations',
	function () {
		if ( ! current_user_can( 'edit_others_posts' ) || ! check_admin_referer( 'krm_export_donations' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'kingdom-revival' ) );
		}
		$posts = get_posts(
			array(
				'post_type'      => 'krm_donation',
				'post_status'    => 'private',
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		$keys  = array( 'name', 'email', 'amount', 'fee', 'currency', 'frequency', 'fund', 'country', 'txn_id', 'subscr_id' );
		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=krm-donations-' . gmdate( 'Y-m-d' ) . '.csv' );
		$out = fopen( 'php://output', 'w' );
		fputcsv( $out, array_merge( array( 'date', 'status' ), $keys, array( 'receipt_sent', 'test' ) ) );
		foreach ( $posts as $p ) {
			$data = (array) get_post_meta( $p->ID, '_krm_donation', true );
			$row  = array( get_the_date( 'Y-m-d H:i', $p ), get_post_meta( $p->ID, '_krm_status', true ) );
			foreach ( $keys as $k ) {
				// Prefix values that spreadsheets would run as formulas.
				$v     = isset( $data[ $k ] ) ? (string) $data[ $k ] : '';
				$row[] = preg_match( '/^[=+\-@\t\r]/', $v ) ? "'" . $v : $v;
			}
			$row[] = get_post_meta( $p->ID, '_krm_receipt_sent', true );
			$row[] = empty( $data['test'] ) ? '' : 'sandbox';
			fputcsv( $out, $row );
		}
		fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		exit;
	}
);
