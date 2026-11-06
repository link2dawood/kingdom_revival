<?php
/**
 * Contact, partner, newsletter, prayer request and testimony forms: validation, spam protection, saved submissions and email notifications.
 *
 * Spam protection (no third-party service, works with page caching):
 *  - hidden honeypot field,
 *  - minimum time on the form (measured in the browser),
 *  - per-visitor rate limit,
 *  - link-stuffing check,
 *  - nonce check for logged-in users (logged-out pages may be cached, so a nonce would expire there).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field definitions per form: key => [ label, required, type ].
 *
 * @param string $form contact|partner|newsletter|prayer|signup.
 */
function krm_form_fields( $form ) {
	$fields = array(
		'contact'    => array(
			'name'    => array( 'Name', true, 'text' ),
			'email'   => array( 'Email', true, 'email' ),
			'phone'   => array( 'Phone', false, 'text' ),
			'topic'   => array( 'Topic', false, 'text' ),
			'message' => array( 'Message', true, 'textarea' ),
		),
		'partner'    => array(
			'type'     => array( 'Partnership', true, 'text' ),
			'amount'   => array( 'Monthly amount', false, 'text' ),
			'ministry' => array( 'Ministry to sponsor', false, 'text' ),
			'church'   => array( 'Church / group', false, 'text' ),
			'name'     => array( 'Name', true, 'text' ),
			'email'    => array( 'Email', true, 'email' ),
			'phone'    => array( 'Phone', false, 'text' ),
			'country'  => array( 'Country', true, 'text' ),
			'updates'  => array( 'Prayer updates', false, 'text' ),
			'message'  => array( 'Message', false, 'textarea' ),
		),
		'newsletter' => array(
			'name'  => array( 'First name', false, 'text' ),
			'email' => array( 'Email', true, 'email' ),
		),
		'prayer'     => array(
			'name'    => array( 'Name', true, 'text' ),
			'contact' => array( 'Email or WhatsApp', true, 'text' ),
			'country' => array( 'Country', false, 'text' ),
			'type'    => array( 'Request type', false, 'text' ),
			'request' => array( 'Prayer request', true, 'textarea' ),
			'private' => array( 'Keep request private', false, 'text' ),
		),
		// Partner With Us: one form per partner type (Prayer Partners, Financial Partner, Church & Ministry
		// Partners, Volunteer With Us); "church" is asked of church partners only, "project" comes from
		// Donate → Upcoming Projects → "Pledge / Get notified".
		'signup'     => array(
			'type'    => array( 'Partner type', true, 'text' ),
			'name'    => array( 'Name', true, 'text' ),
			'contact' => array( 'Email or WhatsApp', true, 'text' ),
			'country' => array( 'Country', true, 'text' ),
			'church'  => array( 'Church / organisation', false, 'text' ),
			'project' => array( 'Project', false, 'text' ),
			'message' => array( 'Message', false, 'textarea' ),
		),
		// Testimonies → "Share your testimony" (Phase 21): saved as a pending Testimonial, never published by itself.
		// The optional photo arrives as the file "photo" (krm_testimony_photo()).
		'testimony'  => array(
			'name'     => array( 'Name', true, 'text' ),
			'place'    => array( 'Village / city', false, 'text' ),
			'ministry' => array( 'Ministry', false, 'text' ),
			'story'    => array( 'Story', true, 'textarea' ),
			'consent'  => array( 'May be published', true, 'consent' ),
		),
	);
	// Phase 24 types (inc/form-handler.php): for the Form Submissions screens and the CSV export.
	return isset( $fields[ $form ] ) ? $fields[ $form ] : krm_form24_fields( $form );
}

/**
 * Submission type labels, keyed by form.
 */
function krm_form_labels() {
	return array(
		'contact'    => __( 'Contact message', 'kingdom-revival' ),
		'partner'    => __( 'Partner sign-up', 'kingdom-revival' ),
		'newsletter' => __( 'Newsletter sign-up', 'kingdom-revival' ),
		'prayer'     => __( 'Prayer request', 'kingdom-revival' ),
		'signup'     => __( 'Partner With Us sign-up', 'kingdom-revival' ),
		'testimony'  => __( 'Testimony', 'kingdom-revival' ),
	) + wp_list_pluck( krm_form_types(), 'label' );
}

/* ---------- Saved submissions (private) ---------- */

add_action(
	'init',
	function () {
		register_post_type(
			'krm_submission',
			array(
				'labels'          => array(
					'name'          => __( 'Form Submissions', 'kingdom-revival' ),
					'singular_name' => __( 'Form Submission', 'kingdom-revival' ),
					'all_items'     => __( 'Form Submissions', 'kingdom-revival' ),
					'edit_item'     => __( 'Form Submission', 'kingdom-revival' ),
					'search_items'  => __( 'Search submissions', 'kingdom-revival' ),
					'not_found'     => __( 'No submissions yet.', 'kingdom-revival' ),
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => 'krm-settings',
				'show_in_rest'    => false,
				'supports'        => array( 'title' ),
				'capability_type' => 'post',
				// Visitors' personal details (prayer requests, donor details): Administrators only (Phase 24).
				'capabilities'    => array(
					'create_posts'           => 'do_not_allow',
					'edit_posts'             => 'manage_options',
					'edit_others_posts'      => 'manage_options',
					'edit_private_posts'     => 'manage_options',
					'edit_published_posts'   => 'manage_options',
					'read_private_posts'     => 'manage_options',
					'publish_posts'          => 'manage_options',
					'delete_posts'           => 'manage_options',
					'delete_others_posts'    => 'manage_options',
					'delete_private_posts'   => 'manage_options',
					'delete_published_posts' => 'manage_options',
				),
				'map_meta_cap'    => true,
				'rewrite'         => false,
				'query_var'       => false,
			)
		);
	}
);

/**
 * Handle a form post (admin-ajax: krm_form).
 */
function krm_handle_form() {
	$form   = isset( $_POST['form'] ) ? sanitize_key( wp_unslash( $_POST['form'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- see spam/nonce checks below.
	$fields = krm_form_fields( $form );
	// Phase 24 types only go through krm_form_submit() (inc/form-handler.php).
	if ( ! $fields || isset( krm_form_types()[ $form ] ) ) {
		wp_send_json_error( array( 'message' => 'Unknown form.' ), 400 );
	}

	// Logged-in visitors get uncached pages, so their requests must carry a valid nonce.
	if ( is_user_logged_in() && ! check_ajax_referer( 'krm_form', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => __( 'Please reload the page and try again.', 'kingdom-revival' ) ), 403 );
	}

	// Posts must carry the signed token of a page from this site (bots posting straight to admin-ajax don't).
	$token = isset( $_POST['token'] ) ? sanitize_text_field( wp_unslash( $_POST['token'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	if ( krm_form_token_age( $token ) < 0 ) {
		wp_send_json_error( array( 'message' => __( 'Please reload the page and try again.', 'kingdom-revival' ) ), 400 );
	}

	// Honeypot: real visitors never see or fill this field. Pretend success so bots learn nothing.
	if ( ! empty( $_POST['website'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		wp_send_json_success();
	}
	// Filled faster than a person could: treat as a bot.
	$elapsed = isset( $_POST['elapsed'] ) ? absint( $_POST['elapsed'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	if ( $elapsed < 2500 ) {
		wp_send_json_success();
	}

	$ip_key = 'krm_rl_' . md5( $form . '|' . ( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '' ) );
	$count  = (int) get_transient( $ip_key );
	if ( $count >= 5 ) {
		wp_send_json_error( array( 'message' => __( 'Too many messages in a short time. Please try again in a few minutes.', 'kingdom-revival' ) ), 429 );
	}

	$data   = array();
	$errors = array();
	foreach ( $fields as $key => $field ) {
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$raw = is_string( $raw ) ? $raw : '';
		if ( 'email' === $field[2] ) {
			$value = sanitize_email( $raw );
			if ( ( $field[1] || '' !== trim( $raw ) ) && ! is_email( $value ) ) {
				$errors[ $key ] = __( 'Please enter a valid email address.', 'kingdom-revival' );
			}
		} elseif ( 'textarea' === $field[2] ) {
			$value = sanitize_textarea_field( $raw );
		} elseif ( 'consent' === $field[2] ) {
			$value = 'Yes' === $raw ? 'Yes' : '';
		} else {
			$value = sanitize_text_field( $raw );
		}
		$value = mb_substr( $value, 0, 'textarea' === $field[2] ? 5000 : 200 );
		if ( $field[1] && '' === trim( $value ) && ! isset( $errors[ $key ] ) ) {
			$errors[ $key ] = __( 'This field is required.', 'kingdom-revival' );
		}
		$data[ $key ] = $value;
	}
	if ( 'testimony' === $form ) {
		$slugs = wp_list_pluck( krm_ministries(), 'slug' );
		if ( ! in_array( $data['ministry'], $slugs, true ) ) {
			$data['ministry'] = '';
		}
		$photo = krm_testimony_photo_check();
		if ( is_wp_error( $photo ) ) {
			$errors['photo'] = $photo->get_error_message();
		}
	}
	if ( $errors ) {
		wp_send_json_error(
			array(
				'fields'  => $errors,
				'message' => __( 'Please check the highlighted fields.', 'kingdom-revival' ),
			),
			422
		);
	}

	set_transient( $ip_key, $count + 1, 10 * MINUTE_IN_SECONDS );

	// Messages stuffed with links are kept for review but not emailed.
	$is_spam = preg_match_all( '#https?://#i', implode( ' ', $data ) ) > 3;

	if ( 'testimony' === $form ) {
		krm_save_testimony( $data, $is_spam );
		wp_send_json_success();
	}

	$labels = krm_form_labels();
	$title  = $labels[ $form ] . ' — ' . ( '' !== $data['name'] ? $data['name'] : $data['email'] );
	if ( 'contact' === $form && '' !== $data['topic'] ) {
		$title = $data['topic'] . ' — ' . $data['name'];
	}
	if ( 'partner' === $form || 'signup' === $form ) {
		$title = $data['type'] . ' — ' . $data['name'];
	}
	$post_id = wp_insert_post(
		array(
			'post_type'   => 'krm_submission',
			'post_status' => $is_spam ? 'draft' : 'private',
			'post_title'  => $title,
		)
	);
	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_krm_form', $form );
		update_post_meta( $post_id, '_krm_fields', $data );
		update_post_meta( $post_id, '_krm_spam', $is_spam ? 1 : 0 );
		update_post_meta( $post_id, '_krm_page', isset( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '' );
	}

	if ( ! $is_spam ) {
		krm_notify( $form, $data, $title, $post_id );
		if ( 'newsletter' === $form ) {
			krm_forward_newsletter( $data );
		}
	}

	wp_send_json_success();
}
add_action( 'wp_ajax_krm_form', 'krm_handle_form' );
add_action( 'wp_ajax_nopriv_krm_form', 'krm_handle_form' );

/**
 * Email the ministry about a new submission.
 *
 * @param string $form    Form key.
 * @param array  $data    Sanitized fields.
 * @param string $title   Submission title.
 * @param int    $post_id Saved submission ID (0 if not saved).
 */
function krm_notify( $form, $data, $title, $post_id ) {
	$to = array_filter( array_map( 'trim', explode( ',', krm_opt( 'form_recipient' ) ) ) );
	if ( ! $to ) {
		$to = array( get_option( 'admin_email' ) );
	}
	$fields = krm_form_fields( $form );
	$lines  = array();
	foreach ( $data as $key => $value ) {
		$lines[] = $fields[ $key ][0] . ': ' . ( '' !== $value ? $value : '—' );
	}
	$body = $title . "\n\n" . implode( "\n", $lines );
	if ( 'testimony' === $form ) {
		$body .= "\n\n" . sprintf( /* translators: %s: admin URL */ __( 'Review it (it is not published until you press Publish): %s', 'kingdom-revival' ), admin_url( 'post.php?action=edit&post=' . (int) $post_id ) );
	} else {
		$body .= "\n\n" . sprintf( /* translators: %s: admin URL */ __( 'View all submissions: %s', 'kingdom-revival' ), admin_url( 'edit.php?post_type=krm_submission' ) );
	}

	$headers = array();
	// The prayer form has one "Email or WhatsApp" field; reply to it when it is an email address.
	$reply = isset( $data['email'] ) ? $data['email'] : ( isset( $data['contact'] ) ? $data['contact'] : '' );
	if ( is_email( $reply ) ) {
		$name      = isset( $data['name'] ) && '' !== $data['name'] ? str_replace( array( "\r", "\n", '"' ), '', $data['name'] ) : '';
		$headers[] = 'Reply-To: ' . ( $name ? '"' . $name . '" ' : '' ) . '<' . $reply . '>';
	}
	/* translators: %s: submission title */
	$subject = sprintf( __( '[Website] %s', 'kingdom-revival' ), $title );
	$sent    = wp_mail( $to, $subject, $body, $headers );
	if ( $post_id ) {
		update_post_meta( $post_id, '_krm_emailed', $sent ? 1 : 0 );
	}
}

/**
 * Optionally pass a newsletter sign-up on to an email service.
 *
 * @param array $data Sanitized fields.
 */
function krm_forward_newsletter( $data ) {
	$endpoint = krm_opt( 'newsletter_endpoint' );
	if ( ! $endpoint ) {
		return;
	}
	$body = array(
		'email' => $data['email'],
		'EMAIL' => $data['email'],
	);
	if ( '' !== $data['name'] ) {
		$body['name']  = $data['name'];
		$body['FNAME'] = $data['name'];
	}
	wp_safe_remote_post(
		$endpoint,
		array(
			'body'     => $body,
			'timeout'  => 8,
			'blocking' => false,
			'headers'  => array( 'Accept' => 'application/json' ),
		)
	);
}

/* ---------- Submissions screens ---------- */

add_filter(
	'manage_krm_submission_posts_columns',
	function () {
		return array(
			'cb'        => '<input type="checkbox">',
			'title'     => __( 'Submission', 'kingdom-revival' ),
			'krm_form'  => __( 'Form', 'kingdom-revival' ),
			'krm_email' => __( 'Email', 'kingdom-revival' ),
			'krm_mail'  => __( 'Email status', 'kingdom-revival' ),
			'date'      => __( 'Date', 'kingdom-revival' ),
		);
	}
);
add_action(
	'manage_krm_submission_posts_custom_column',
	function ( $col, $post_id ) {
		$data   = (array) get_post_meta( $post_id, '_krm_fields', true );
		$labels = krm_form_labels();
		$form   = get_post_meta( $post_id, '_krm_form', true );
		if ( 'krm_form' === $col ) {
			echo esc_html( isset( $labels[ $form ] ) ? $labels[ $form ] : $form );
			if ( get_post_meta( $post_id, '_krm_spam', true ) ) {
				echo ' — <strong>' . esc_html__( 'possible spam', 'kingdom-revival' ) . '</strong>';
			}
		} elseif ( 'krm_email' === $col ) {
			$reply = ! empty( $data['email'] ) ? $data['email'] : ( ! empty( $data['contact'] ) ? $data['contact'] : '' );
			if ( is_email( $reply ) ) {
				echo '<a href="' . esc_url( 'mailto:' . $reply ) . '">' . esc_html( $reply ) . '</a>';
			} elseif ( '' !== $reply ) {
				echo '<a href="' . esc_url( krm_whatsapp_link( $reply ) ) . '" target="_blank" rel="noopener">' . esc_html( $reply ) . '</a>';
			}
		} elseif ( 'krm_mail' === $col ) {
			echo krm_mail_status_html( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the function.
		}
	},
	10,
	2
);

/* Filter the list by form. */
add_action(
	'restrict_manage_posts',
	function ( $post_type ) {
		if ( 'krm_submission' !== $post_type ) {
			return;
		}
		$current = isset( $_GET['krm_form'] ) ? sanitize_key( wp_unslash( $_GET['krm_form'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo '<select name="krm_form"><option value="">' . esc_html__( 'All forms', 'kingdom-revival' ) . '</option>';
		foreach ( krm_form_labels() as $key => $label ) {
			printf( '<option value="%s"%s>%s</option>', esc_attr( $key ), selected( $current, $key, false ), esc_html( $label ) );
		}
		echo '</select>';
		$export = wp_nonce_url( admin_url( 'admin-post.php?action=krm_export&krm_form=' . $current ), 'krm_export' );
		echo ' <a class="button" href="' . esc_url( $export ) . '">' . esc_html__( 'Export CSV', 'kingdom-revival' ) . '</a>';
	}
);
add_action(
	'pre_get_posts',
	function ( $q ) {
		if ( ! is_admin() || ! $q->is_main_query() || 'krm_submission' !== $q->get( 'post_type' ) ) {
			return;
		}
		if ( ! $q->get( 'post_status' ) ) {
			$q->set( 'post_status', array( 'private', 'draft' ) );
		}
		if ( ! empty( $_GET['krm_form'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$q->set( 'meta_key', '_krm_form' ); // phpcs:ignore WordPress.DB.SlowDBQuery
			$q->set( 'meta_value', sanitize_key( wp_unslash( $_GET['krm_form'] ) ) ); // phpcs:ignore WordPress.DB.SlowDBQuery,WordPress.Security.NonceVerification.Recommended
		}
	}
);

/* Read-only view of a submission. */
add_action(
	'add_meta_boxes_krm_submission',
	function () {
		remove_meta_box( 'submitdiv', 'krm_submission', 'side' );
		add_meta_box(
			'krm-submission',
			__( 'Details', 'kingdom-revival' ),
			function ( $post ) {
				$form   = get_post_meta( $post->ID, '_krm_form', true );
				$fields = krm_form_fields( $form );
				$data   = (array) get_post_meta( $post->ID, '_krm_fields', true );
				echo '<table class="widefat striped"><tbody>';
				foreach ( $data as $key => $value ) {
					$label = isset( $fields[ $key ] ) ? $fields[ $key ][0] : $key;
					echo '<tr><th style="width:200px">' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( '' !== (string) $value ? $value : '—' ) ) . '</td></tr>';
				}
				echo '<tr><th>' . esc_html__( 'Received', 'kingdom-revival' ) . '</th><td>' . esc_html( get_the_date( 'F j, Y g:i a', $post ) ) . ' (' . esc_html( wp_date( 'j M Y, g:i a', get_post_timestamp( $post ), new DateTimeZone( 'Asia/Karachi' ) ) ) . ' PKT)</td></tr>';
				echo '<tr><th>' . esc_html__( 'Email status', 'kingdom-revival' ) . '</th><td>' . krm_mail_status_html( $post->ID, true ) . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the function.
				$page = get_post_meta( $post->ID, '_krm_page', true );
				if ( $page ) {
					echo '<tr><th>' . esc_html__( 'Sent from', 'kingdom-revival' ) . '</th><td><a href="' . esc_url( $page ) . '">' . esc_html( $page ) . '</a></td></tr>';
				}
				echo '</tbody></table>';
				$reply = ! empty( $data['email'] ) ? $data['email'] : ( ! empty( $data['contact'] ) ? $data['contact'] : '' );
				echo '<p>';
				if ( is_email( $reply ) ) {
					echo '<a class="button button-primary" href="' . esc_url( 'mailto:' . $reply ) . '">' . esc_html__( 'Reply by email', 'kingdom-revival' ) . '</a> ';
				} elseif ( 'phone' === krm_contact_kind( $reply ) ) {
					echo '<a class="button button-primary" href="' . esc_url( krm_whatsapp_link( $reply ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'Message on WhatsApp', 'kingdom-revival' ) . '</a> ';
				}
				echo '<a class="button" href="' . esc_url( get_delete_post_link( $post->ID ) ) . '">' . esc_html__( 'Move to Trash', 'kingdom-revival' ) . '</a></p>';
			},
			'krm_submission',
			'normal',
			'high'
		);
	}
);

/* CSV export (all forms or one). */
add_action(
	'admin_post_krm_export',
	function () {
		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'krm_export' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'kingdom-revival' ) );
		}
		$form  = isset( $_GET['krm_form'] ) ? sanitize_key( wp_unslash( $_GET['krm_form'] ) ) : '';
		$query = array(
			'post_type'      => 'krm_submission',
			'post_status'    => array( 'private', 'draft' ),
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		if ( $form ) {
			$query['meta_key']   = '_krm_form'; // phpcs:ignore WordPress.DB.SlowDBQuery
			$query['meta_value'] = $form; // phpcs:ignore WordPress.DB.SlowDBQuery
		}
		$posts = get_posts( $query );
		$keys  = array();
		foreach ( array_merge( array( 'contact', 'partner', 'newsletter' ), array_keys( krm_form_types() ) ) as $f ) {
			if ( ! $form || $form === $f ) {
				$keys = array_unique( array_merge( $keys, array_keys( krm_form_fields( $f ) ) ) );
			}
		}
		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=krm-submissions-' . ( $form ? $form . '-' : '' ) . gmdate( 'Y-m-d' ) . '.csv' );
		$out = fopen( 'php://output', 'w' );
		fputcsv( $out, array_merge( array( 'date', 'form' ), $keys ) );
		foreach ( $posts as $p ) {
			$data = (array) get_post_meta( $p->ID, '_krm_fields', true );
			$row  = array( get_the_date( 'Y-m-d H:i', $p ), get_post_meta( $p->ID, '_krm_form', true ) );
			foreach ( $keys as $k ) {
				// Prefix values that spreadsheets would run as formulas.
				$v     = isset( $data[ $k ] ) ? (string) $data[ $k ] : '';
				$row[] = preg_match( '/^[=+\-@\t\r]/', $v ) ? "'" . $v : $v;
			}
			fputcsv( $out, $row );
		}
		fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		exit;
	}
);

/**
 * Email status of a submission for the admin screens: Sent, Failed (with the reason on the entry), Not sent (possible
 * spam). Entries from before Phase 24 only recorded whether the email went out.
 *
 * @param int  $post_id Submission.
 * @param bool $detail  Include the failure reason.
 */
function krm_mail_status_html( $post_id, $detail = false ) {
	$status = get_post_meta( $post_id, '_krm_mail_status', true );
	if ( '' === $status ) {
		$status = get_post_meta( $post_id, '_krm_emailed', true ) ? 'sent' : ( get_post_meta( $post_id, '_krm_spam', true ) ? 'held' : 'failed' );
	}
	// Admin screen only (the front end's colours are tokens).
	$labels = array(
		'sent'    => array( __( 'Sent', 'kingdom-revival' ), '#1E5B3A' ),
		'failed'  => array( __( 'Failed', 'kingdom-revival' ), '#B3261E' ),
		'held'    => array( __( 'Not sent (possible spam)', 'kingdom-revival' ), '#7A5A00' ),
		'pending' => array( __( 'Not sent', 'kingdom-revival' ), '#7A5A00' ),
	);
	$label  = isset( $labels[ $status ] ) ? $labels[ $status ] : $labels['pending'];
	$html   = '<strong style="color:' . esc_attr( $label[1] ) . '">' . esc_html( $label[0] ) . '</strong>';
	$error  = get_post_meta( $post_id, '_krm_mail_error', true );
	if ( $detail && 'failed' === $status && $error ) {
		$html .= ' — ' . esc_html( $error ) . '<br><small>' . esc_html__( 'The submission is saved here; reply to the visitor directly, and check the SMTP settings in wp-config.php (theme readme, "Form emails").', 'kingdom-revival' ) . '</small>';
	}
	return $html;
}

/* ---------- Testimonies → "Share your testimony" (Phase 21) ---------- */

/**
 * The testimony's optional photo ($_FILES['photo']): null when none was sent, true when it is an acceptable image
 * (JPG, PNG or WebP, up to 5 MB), else a WP_Error with the message for the visitor.
 *
 * @return true|null|WP_Error
 */
function krm_testimony_photo_check() {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- checked in krm_handle_form().
	if ( empty( $_FILES['photo'] ) || ! isset( $_FILES['photo']['error'] ) || UPLOAD_ERR_NO_FILE === (int) $_FILES['photo']['error'] ) {
		return null;
	}
	$file = $_FILES['photo']; // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput -- validated below, then handled by wp_handle_upload().
	if ( UPLOAD_ERR_OK !== (int) $file['error'] || empty( $file['tmp_name'] ) || ! is_uploaded_file( $file['tmp_name'] ) ) {
		return new WP_Error( 'krm_photo', __( 'The photo could not be uploaded. Please try another one, or send your story without it.', 'kingdom-revival' ) );
	}
	if ( (int) $file['size'] > 5 * MB_IN_BYTES ) {
		return new WP_Error( 'krm_photo', __( 'This photo is too large. Please choose one under 5 MB.', 'kingdom-revival' ) );
	}
	$types = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'png'          => 'image/png',
		'webp'         => 'image/webp',
	);
	$check = wp_check_filetype_and_ext( $file['tmp_name'], sanitize_file_name( (string) $file['name'] ), $types );
	if ( empty( $check['type'] ) || ! in_array( $check['type'], $types, true ) || ! @getimagesize( $file['tmp_name'] ) ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- not an image: false.
		return new WP_Error( 'krm_photo', __( 'Please choose a JPG, PNG or WebP photo.', 'kingdom-revival' ) );
	}
	return true;
}

/**
 * Save a testimony as a pending Testimonial (a draft when it looks like spam), attach its photo as the featured
 * image, and email the ministry (not for spam).
 *
 * @param array $data    Sanitized fields.
 * @param bool  $is_spam Link-stuffed.
 */
function krm_save_testimony( $data, $is_spam ) {
	$post_id = wp_insert_post(
		array(
			'post_type'    => 'testimonial',
			'post_status'  => $is_spam ? 'draft' : 'pending',
			'post_title'   => $data['name'],
			'post_content' => $data['story'],
			'post_author'  => 0,
			'meta_input'   => array(
				'_krm_role'       => $data['place'],
				'_krm_ministry'   => $data['ministry'],
				'_krm_consent'    => current_time( 'mysql' ),
				'_krm_from_form'  => 1,
				'_krm_spam'       => $is_spam ? 1 : 0,
				'_krm_page'       => isset( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '',
			),
		),
		true
	);
	if ( is_wp_error( $post_id ) || ! $post_id ) {
		wp_send_json_error( array( 'message' => __( 'Sorry, your testimony could not be saved.', 'kingdom-revival' ) ), 500 );
	}
	if ( true === krm_testimony_photo_check() ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attachment = media_handle_upload(
			'photo',
			$post_id,
			array( 'post_title' => sprintf( /* translators: %s: name */ __( 'Testimony photo — %s', 'kingdom-revival' ), $data['name'] ) ),
			array(
				'test_form' => false,
				'mimes'     => array(
					'jpg|jpeg|jpe' => 'image/jpeg',
					'png'          => 'image/png',
					'webp'         => 'image/webp',
				),
			)
		);
		if ( ! is_wp_error( $attachment ) ) {
			set_post_thumbnail( $post_id, $attachment );
			$data['photo'] = __( 'attached (featured image)', 'kingdom-revival' );
		}
	}
	if ( ! $is_spam ) {
		$labels = krm_form_labels();
		krm_notify( 'testimony', array_intersect_key( $data, krm_form_fields( 'testimony' ) ), $labels['testimony'] . ' — ' . $data['name'], $post_id );
	}
}
