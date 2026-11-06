<?php
/**
 * Phase 24: one handler for the Partner With Us and Donate forms.
 *
 * form_type: prayer_partner, financial_partner, church_partner, volunteer (the four Partner With Us sign-ups),
 * prayer_request (Prayer Request page and the home page's prayer form) and donation (Donate → donor details, a
 * "donation intent": never a payment confirmation and never card or bank-login data).
 *
 * Routes, all into krm_form_submit():
 *  - JavaScript: admin-ajax.php?action=krm_form_submit (POST, JSON answer); assets/js/forms.js first fetches a fresh
 *    nonce + start token from admin-ajax.php?action=krm_form_nonce (GET, never cached), so cached pages keep working.
 *  - No JavaScript: the form posts to admin-post.php?action=krm_form_submit and is redirected back to the page
 *    (?krm_sent=<key>#<form id>), which shows the same success or error message (krm_form_state()).
 *
 * Checks: nonce (JS: always; no-JS: the page's nonce, or for logged-out visitors on a cached page the signed page
 * token), hidden honeypot, at least 3 seconds between the start token and the post, at most 5 submissions per IP per
 * 10 minutes, server-side validation of every field. Every accepted submission is saved first (Form Submissions,
 * admin-only), then emailed (inc/mail.php); the email status is stored on the entry.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/** Minimum seconds between getting the start token and sending. */
const KRM_FORM_MIN_SECONDS = 3;
/** Submissions allowed per IP in KRM_FORM_RATE_WINDOW seconds. */
const KRM_FORM_RATE_MAX = 5;
const KRM_FORM_RATE_WINDOW = 600;

/**
 * The form types: label (admin, email heading), subject prefix.
 */
function krm_form_types() {
	return array(
		'prayer_partner'    => array( 'label' => __( 'Prayer Partner', 'kingdom-revival' ), 'subject' => 'New Prayer Partner' ), // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing
		'financial_partner' => array( 'label' => __( 'Financial Partner', 'kingdom-revival' ), 'subject' => 'New Financial Partner' ), // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing
		'church_partner'    => array( 'label' => __( 'Church & Ministry Partner', 'kingdom-revival' ), 'subject' => 'New Church & Ministry Partner' ), // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing
		'volunteer'         => array( 'label' => __( 'Volunteer', 'kingdom-revival' ), 'subject' => 'New Volunteer' ), // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing
		'prayer_request'    => array( 'label' => __( 'Prayer Request', 'kingdom-revival' ), 'subject' => 'Prayer Request' ), // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing
		'donation'          => array( 'label' => __( 'Donation Intent', 'kingdom-revival' ), 'subject' => 'Donation Intent' ), // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing
	);
}

/** Prayer request types offered by the form. */
function krm_prayer_types() {
	return array( 'Healing', 'Family', 'Salvation', 'Other' );
}

/** Payment methods of the Donate page tabs (data-method). */
function krm_donation_methods() {
	return array( 'PayPal', 'Bank transfer', 'Stripe', 'Local - Western Union', 'Local - MoneyGram' );
}

/** Funds offered by the Donate page (the select's values). */
function krm_donation_funds() {
	return array_merge( array( 'Where it is needed most' ), wp_list_pluck( krm_ministries(), 'title' ) );
}

/**
 * Fields per type: key => [ label, required, kind ]. Kinds: text, contact (email or WhatsApp number), textarea,
 * consent (checkbox "Yes"), amount (US dollars), choice (one of krm_form_choices()).
 *
 * @param string $type Form type.
 */
function krm_form24_fields( $type ) {
	$partner = array(
		'name'    => array( 'Name', true, 'text' ),
		'contact' => array( 'Email or WhatsApp', true, 'contact' ),
		'country' => array( 'Country', true, 'text' ),
	);
	$message = array( 'message' => array( 'Message', false, 'textarea' ) );
	switch ( $type ) {
		case 'prayer_partner':
		case 'volunteer':
			return $partner + $message;
		case 'financial_partner':
			return $partner + array( 'project' => array( 'Project', false, 'text' ) ) + $message;
		case 'church_partner':
			return $partner + array( 'church' => array( 'Church / organisation', false, 'text' ) ) + $message;
		case 'prayer_request':
			return array(
				'name'    => array( 'Name', true, 'text' ),
				'contact' => array( 'Email or WhatsApp', true, 'contact' ),
				'country' => array( 'Country', false, 'text' ),
				'type'    => array( 'Request type', false, 'choice' ),
				'request' => array( 'Prayer request', true, 'textarea' ),
				'private' => array( 'Keep request private', false, 'consent' ),
			);
		case 'donation':
			return array(
				'name'      => array( 'Name', true, 'text' ),
				'contact'   => array( 'Email or WhatsApp', true, 'contact' ),
				'country'   => array( 'Country', false, 'text' ),
				'amount'    => array( 'Amount', true, 'amount' ),
				'frequency' => array( 'Frequency', true, 'choice' ),
				'fund'      => array( 'Ministry / project', true, 'choice' ),
				'method'    => array( 'Payment method chosen', false, 'choice' ),
				'message'   => array( 'Message', false, 'textarea' ),
			);
	}
	return array();
}

/**
 * Allowed values of a choice field.
 *
 * @param string $type Form type.
 * @param string $key  Field.
 */
function krm_form_choices( $type, $key ) {
	if ( 'type' === $key ) {
		return krm_prayer_types();
	}
	if ( 'frequency' === $key ) {
		return array( 'One-time', 'Monthly' );
	}
	if ( 'fund' === $key ) {
		return krm_donation_funds();
	}
	if ( 'method' === $key ) {
		return krm_donation_methods();
	}
	return array();
}

/**
 * Is this an email address or a phone / WhatsApp number (7–15 digits, optional +, spaces, dashes, brackets)?
 *
 * @param string $value Value.
 * @return string 'email', 'phone' or ''.
 */
function krm_contact_kind( $value ) {
	$value = trim( (string) $value );
	if ( false !== strpos( $value, '@' ) ) {
		return is_email( $value ) ? 'email' : '';
	}
	$digits = preg_replace( '/\D/', '', $value );
	return preg_match( '/^\+?[\d\s().-]{7,25}$/', $value ) && strlen( $digits ) >= 7 && strlen( $digits ) <= 15 ? 'phone' : '';
}

/**
 * wa.me link for a phone number. Pakistani numbers written 03xx… become 923xx…; "00" becomes the international form.
 *
 * @param string $phone Number.
 */
function krm_whatsapp_link( $phone ) {
	$digits = preg_replace( '/\D/', '', (string) $phone );
	if ( 0 === strpos( $digits, '00' ) ) {
		$digits = substr( $digits, 2 );
	} elseif ( 11 === strlen( $digits ) && 0 === strpos( $digits, '03' ) ) {
		$digits = '92' . substr( $digits, 1 );
	}
	return 'https://wa.me/' . $digits;
}

/**
 * Remove anything that looks like a payment card number (13–19 digits passing the Luhn check) and CVV codes from free
 * text, so they are never emailed or stored even if a donor types them in.
 *
 * @param string $text Text.
 */
function krm_redact_payment_data( $text ) {
	$text = preg_replace_callback(
		'/(?<!\d)(?:\d[ -]?){12,18}\d(?!\d)/',
		function ( $m ) {
			$digits = preg_replace( '/\D/', '', $m[0] );
			$sum    = 0;
			$len    = strlen( $digits );
			for ( $i = 0; $i < $len; $i++ ) {
				$d = (int) $digits[ $len - 1 - $i ];
				if ( $i % 2 ) {
					$d *= 2;
					if ( $d > 9 ) {
						$d -= 9;
					}
				}
				$sum += $d;
			}
			return 0 === $sum % 10 ? '[card number removed]' : $m[0];
		},
		(string) $text
	);
	return preg_replace( '/\b(cvv2?|cvc2?|csc|security code)\b\W{0,3}\d{3,4}\b/i', '$1 [removed]', $text );
}

/**
 * The visitor's IP for rate limiting (REMOTE_ADDR: forwarded headers can be forged).
 */
function krm_form_ip() {
	return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
}

/**
 * Validate and sanitize the posted fields of a form type.
 *
 * @param string $type Form type.
 * @param array  $post Unslashed input.
 * @return array{0: array, 1: array} Clean data, and field errors (key => message).
 */
function krm_form_validate( $type, array $post ) {
	$data   = array();
	$errors = array();
	foreach ( krm_form24_fields( $type ) as $key => $field ) {
		list( , $required, $kind ) = $field;
		$raw = isset( $post[ $key ] ) && is_string( $post[ $key ] ) ? $post[ $key ] : '';
		switch ( $kind ) {
			case 'textarea':
				$value = mb_substr( sanitize_textarea_field( $raw ), 0, 5000 );
				break;
			case 'consent':
				$value = 'Yes' === $raw ? 'Yes' : '';
				break;
			case 'amount':
				// "$50" radios, or "Other" with the amount in "custom".
				if ( 'Other' === $raw ) {
					$raw = isset( $post['custom'] ) && is_string( $post['custom'] ) ? $post['custom'] : '';
				}
				$number = (float) preg_replace( '/[^0-9.]/', '', $raw );
				$value  = $number > 0 && $number <= 1000000 ? '$' . rtrim( rtrim( number_format( $number, 2, '.', '' ), '0' ), '.' ) : '';
				if ( '' === $value && ! isset( $errors[ $key ] ) ) {
					$errors[ $key ] = __( 'Please choose or enter an amount.', 'kingdom-revival' );
				}
				break;
			case 'choice':
				$value = sanitize_text_field( $raw );
				if ( '' !== $value && ! in_array( $value, krm_form_choices( $type, $key ), true ) ) {
					$errors[ $key ] = __( 'Please choose one of the options.', 'kingdom-revival' );
					$value          = '';
				}
				break;
			default: // text, contact: one line, no tags, no line breaks.
				$value = mb_substr( krm_mail_line( sanitize_text_field( $raw ) ), 0, 'name' === $key ? 100 : 200 );
		}
		if ( 'contact' === $kind && '' !== $value && '' === krm_contact_kind( $value ) ) {
			$errors[ $key ] = __( 'Please enter a valid email address or WhatsApp number.', 'kingdom-revival' );
		}
		if ( $required && '' === trim( $value ) && ! isset( $errors[ $key ] ) ) {
			$errors[ $key ] = __( 'Please fill in this field.', 'kingdom-revival' );
		}
		if ( 'donation' === $type && in_array( $kind, array( 'text', 'textarea' ), true ) ) {
			$value = krm_redact_payment_data( $value );
		}
		$data[ $key ] = $value;
	}
	return array( $data, $errors );
}

/**
 * Process one submission. Shared by the JavaScript (admin-ajax) and no-JavaScript (admin-post) routes.
 *
 * @param array $post Unslashed input.
 * @param bool  $ajax Sent by forms.js (true) or a plain form post (false).
 * @return array { ok: bool, status: int, code: string, message: string, fields: array, name: string, values: array }
 */
function krm_form_submit( array $post, $ajax ) {
	$type   = isset( $post['form_type'] ) && is_string( $post['form_type'] ) ? sanitize_key( $post['form_type'] ) : '';
	$result = array(
		'ok'      => false,
		'status'  => 400,
		'code'    => 'invalid',
		'message' => __( 'Please reload the page and try again.', 'kingdom-revival' ),
		'fields'  => array(),
		'name'    => '',
		'values'  => array(),
	);
	$types  = krm_form_types();
	if ( ! isset( $types[ $type ] ) ) {
		return $result;
	}
	$fake_ok = array_merge( $result, array( 'ok' => true, 'status' => 200, 'code' => 'sent', 'message' => '' ) ); // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing

	// Nonce. Logged-out visitors without JavaScript on a cached page carry an expired nonce; their signed page token
	// (inc/security.php) proves the post comes from a page of this site instead.
	$nonce = isset( $post['_krm_nonce'] ) && is_string( $post['_krm_nonce'] ) ? $post['_krm_nonce'] : '';
	$token = isset( $post['token'] ) && is_string( $post['token'] ) ? sanitize_text_field( $post['token'] ) : '';
	$age   = krm_form_token_age( $token );
	if ( ! wp_verify_nonce( $nonce, 'krm_form_submit' ) && ( $ajax || is_user_logged_in() || $age < 0 ) ) {
		$result['status'] = 403;
		$result['code']   = 'expired';
		return $result;
	}
	if ( $age < 0 ) {
		return $result;
	}
	// Honeypot filled, or sent faster than a person can: a bot. Answer "sent" so it learns nothing; nothing is kept.
	if ( ! empty( $post['website'] ) || $age < KRM_FORM_MIN_SECONDS ) {
		return $fake_ok;
	}

	// Rate limit per IP across all these forms.
	$rl_key = 'krm_frl_' . md5( krm_form_ip() );
	$rl     = get_transient( $rl_key );
	$rl     = is_array( $rl ) && isset( $rl['n'], $rl['t'] ) && time() - $rl['t'] < KRM_FORM_RATE_WINDOW ? $rl : array( 'n' => 0, 't' => time() ); // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing
	if ( $rl['n'] >= KRM_FORM_RATE_MAX ) {
		$result['status']  = 429;
		$result['code']    = 'rate';
		$result['message'] = sprintf( /* translators: %s: WhatsApp / phone number */ __( 'You have sent several messages in a short time. Please wait a few minutes and try again, or WhatsApp us at %s.', 'kingdom-revival' ), krm_opt( 'phone' ) );
		return $result;
	}

	list( $data, $errors ) = krm_form_validate( $type, $post );
	$result['name']        = $data['name'];
	$result['values']      = $data;
	if ( $errors ) {
		$result['status']  = 422;
		$result['code']    = 'fields';
		$result['fields']  = $errors;
		$result['message'] = __( 'Please check the highlighted fields.', 'kingdom-revival' );
		return $result;
	}
	$rl['n']++;
	set_transient( $rl_key, $rl, KRM_FORM_RATE_WINDOW );

	// The page it came from: the posted address when it is on this site, else the referrer.
	$page = isset( $post['page'] ) && is_string( $post['page'] ) ? esc_url_raw( $post['page'] ) : '';
	if ( '' === $page || wp_parse_url( $page, PHP_URL_HOST ) !== wp_parse_url( home_url(), PHP_URL_HOST ) ) {
		$page = wp_get_referer() ? esc_url_raw( wp_get_referer() ) : home_url( '/' );
	}
	$page = remove_query_arg( 'krm_sent', $page );

	// Messages stuffed with links: kept for review (as possible spam) but not emailed.
	$is_spam = preg_match_all( '#https?://#i', implode( ' ', $data ) ) > 3;

	$label   = $types[ $type ]['label'];
	$private = 'prayer_request' === $type && 'Yes' === $data['private'];
	$post_id = wp_insert_post(
		array(
			'post_type'   => 'krm_submission',
			'post_status' => $is_spam ? 'draft' : 'private',
			'post_title'  => ( $private ? 'PRIVATE ' : '' ) . $label . ' — ' . $data['name'],
		),
		true
	);
	$saved   = $post_id && ! is_wp_error( $post_id );
	if ( $saved ) {
		update_post_meta( $post_id, '_krm_form', $type );
		update_post_meta( $post_id, '_krm_fields', $data );
		update_post_meta( $post_id, '_krm_spam', $is_spam ? 1 : 0 );
		update_post_meta( $post_id, '_krm_page', $page );
		update_post_meta( $post_id, '_krm_mail_status', $is_spam ? 'held' : 'pending' );
	}

	$sent = false;
	$w3   = null;
	if ( ! $is_spam && krm_w3_key() ) {
		// Web3Forms: the visitor's browser sends the email (forms.js), then reports the result (krm_form_mailed).
		// Without JavaScript the server forwards it itself.
		$w3 = krm_w3_payload( krm_form_mail( $type, $data, $page, $saved ? (int) $post_id : 0 ) );
		if ( $saved ) {
			update_post_meta( $post_id, '_krm_mail_via', 'web3forms' );
		}
		if ( ! $ajax ) {
			list( $sent, $error ) = krm_w3_send_server( $w3 );
			if ( $saved ) {
				update_post_meta( $post_id, '_krm_mail_status', $sent ? 'sent' : 'failed' );
				update_post_meta( $post_id, '_krm_emailed', $sent ? 1 : 0 );
				if ( ! $sent ) {
					update_post_meta( $post_id, '_krm_mail_error', mb_substr( $error, 0, 500 ) );
				}
			}
			$w3 = null;
		} else {
			$sent = true; // Sent by the browser; a failure there is reported back and shown on the entry.
		}
	} elseif ( ! $is_spam ) {
		list( $sent, $error ) = krm_send_form_mail( krm_form_mail( $type, $data, $page, $saved ? (int) $post_id : 0 ) );
		if ( $saved ) {
			update_post_meta( $post_id, '_krm_mail_status', $sent ? 'sent' : 'failed' );
			update_post_meta( $post_id, '_krm_emailed', $sent ? 1 : 0 );
			if ( ! $sent ) {
				update_post_meta( $post_id, '_krm_mail_error', mb_substr( $error, 0, 500 ) );
			}
		}
	}

	// Saved or emailed: it reached the team. Neither: tell the visitor it failed (they keep what they typed).
	if ( ! $saved && ! $sent && ! $is_spam ) {
		$result['status']  = 500;
		$result['code']    = 'failed';
		$result['message'] = '';
		return $result;
	}
	$done = array_merge( $result, array( 'ok' => true, 'status' => 200, 'code' => 'sent', 'message' => '' ) ); // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing
	if ( $w3 ) {
		$done['w3'] = krm_w3_envelope( $w3, $saved ? (int) $post_id : 0 );
	}
	return $done;
}

/**
 * The email for a submission (see krm_send_form_mail()).
 *
 * @param string $type    Form type.
 * @param array  $data    Clean data.
 * @param string $page    Page URL.
 * @param int    $post_id Saved submission (0 when it could not be saved).
 */
function krm_form_mail( $type, array $data, $page, $post_id ) {
	$types   = krm_form_types();
	$fields  = krm_form24_fields( $type );
	$name    = krm_mail_line( $data['name'] );
	$kind    = krm_contact_kind( $data['contact'] );
	$rows    = array( 'Form' => $types[ $type ]['label'] );
	$notice  = '';
	$subject = '[Website] ' . $types[ $type ]['subject'] . ' — ' . $name;
	foreach ( $fields as $key => $field ) {
		$rows[ $field[0] ] = 'consent' === $field[2] ? ( 'Yes' === $data[ $key ] ? 'Yes' : 'No' ) : $data[ $key ];
	}
	if ( 'prayer_request' === $type && 'Yes' === $data['private'] ) {
		$subject = '[Website] PRIVATE Prayer Request — ' . $name;
		$notice  = 'PRIVATE: the visitor asked that only the ministry’s designated prayer team sees this request.';
	}
	if ( 'donation' === $type ) {
		$amount  = $data['amount'] . ( 'Monthly' === $data['frequency'] ? '/month' : '' );
		$subject = '[Website] Donation Intent — ' . $amount . ' for ' . krm_mail_line( $data['fund'] ) . ' — ' . $name;
		$notice  = 'Donation intent only: this is NOT a payment confirmation. Check PayPal / the bank / the transfer receipt before thanking the donor for a received gift.';
		if ( '' === $data['method'] ) {
			$rows['Payment method chosen'] = 'Not chosen';
		}
	}
	return array(
		'subject'    => $subject,
		'heading'    => $types[ $type ]['label'] . ( 'donation' === $type ? '' : ' — ' . $name ),
		'notice'     => $notice,
		'rows'       => $rows,
		'page'       => $page,
		'reply_to'   => 'email' === $kind ? $data['contact'] : '',
		'reply_name' => $name,
		'whatsapp'   => 'phone' === $kind ? krm_whatsapp_link( $data['contact'] ) : '',
		'admin'      => $post_id ? admin_url( 'post.php?action=edit&post=' . $post_id ) : '',
	);
}

/* ---------- Routes ---------- */

/** Fresh nonce + start token (never cached: admin-ajax sends no-cache headers). */
function krm_form_nonce_route() {
	nocache_headers();
	wp_send_json_success(
		array(
			'nonce' => wp_create_nonce( 'krm_form_submit' ),
			'token' => krm_form_token(),
		)
	);
}
add_action( 'wp_ajax_krm_form_nonce', 'krm_form_nonce_route' );
add_action( 'wp_ajax_nopriv_krm_form_nonce', 'krm_form_nonce_route' );

/** JavaScript route: JSON answer. */
function krm_form_ajax_route() {
	$result = krm_form_submit( wp_unslash( $_POST ), true ); // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- verified and sanitized in krm_form_submit().
	$body   = array(
		'code'    => $result['code'],
		'message' => $result['message'],
		'fields'  => $result['fields'],
		'name'    => $result['name'],
	);
	if ( ! empty( $result['w3'] ) ) {
		$body['w3'] = $result['w3'];
	}
	if ( $result['ok'] ) {
		wp_send_json_success( $body );
	}
	wp_send_json_error( $body, $result['status'] );
}
add_action( 'wp_ajax_krm_form_submit', 'krm_form_ajax_route' );
add_action( 'wp_ajax_nopriv_krm_form_submit', 'krm_form_ajax_route' );

/** No-JavaScript route: keep the outcome for the page (10 minutes) and go back to it. */
function krm_form_post_route() {
	$post   = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- verified and sanitized in krm_form_submit().
	$result = krm_form_submit( $post, false );
	$id     = isset( $post['form_id'] ) && is_string( $post['form_id'] ) ? sanitize_html_class( $post['form_id'] ) : '';
	$key    = wp_generate_password( 20, false );
	set_transient(
		'krm_ff_' . $key,
		array(
			'form_id' => $id,
			'ok'      => $result['ok'],
			'code'    => $result['code'],
			'message' => $result['message'],
			'fields'  => $result['fields'],
			'name'    => $result['name'],
			'values'  => $result['ok'] ? array() : $result['values'],
		),
		10 * MINUTE_IN_SECONDS
	);
	$back = isset( $post['page'] ) && is_string( $post['page'] ) ? $post['page'] : '';
	$back = wp_validate_redirect( $back, wp_get_referer() ? wp_get_referer() : home_url( '/' ) );
	$back = add_query_arg( 'krm_sent', $key, remove_query_arg( 'krm_sent', $back ) );
	wp_safe_redirect( $back . ( $id ? '#' . $id : '' ), 303 );
	exit;
}
add_action( 'admin_post_krm_form_submit', 'krm_form_post_route' );
add_action( 'admin_post_nopriv_krm_form_submit', 'krm_form_post_route' );

/* Pages showing a no-JS outcome are personal: never cache them. */
add_action(
	'template_redirect',
	function () {
		if ( isset( $_GET['krm_sent'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			nocache_headers();
			if ( ! defined( 'DONOTCACHEPAGE' ) ) {
				define( 'DONOTCACHEPAGE', true );
			}
		}
	}
);

/* ---------- Template helpers ---------- */

/**
 * The no-JS outcome for a form on this page view (after the redirect), or null.
 *
 * @param string $form_id Form id.
 */
function krm_form_state( $form_id ) {
	static $state = false;
	if ( false === $state ) {
		$state = null;
		$key   = isset( $_GET['krm_sent'] ) ? sanitize_key( wp_unslash( $_GET['krm_sent'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( '' !== $key ) {
			$found = get_transient( 'krm_ff_' . $key );
			$state = is_array( $found ) ? $found : null;
		}
	}
	return $state && $state['form_id'] === $form_id ? $state : null;
}

/**
 * A value to put back in a field after a failed no-JS post (escaped for an attribute or textarea).
 *
 * @param string $form_id Form id.
 * @param string $key     Field.
 */
function krm_fv( $form_id, $key ) {
	$state = krm_form_state( $form_id );
	return $state && isset( $state['values'][ $key ] ) ? (string) $state['values'][ $key ] : '';
}

/**
 * Print the hidden fields every Phase 24 form carries, and load assets/js/forms.js.
 *
 * @param string $type    Form type.
 * @param string $form_id Form id.
 */
function krm_form_hidden( $type, $form_id ) {
	wp_enqueue_script( 'krm-forms' );
	$page = is_singular() ? get_permalink() : home_url( '/' );
	?>
	<input type="hidden" name="action" value="krm_form_submit">
	<input type="hidden" name="form_type" value="<?php echo esc_attr( $type ); ?>">
	<input type="hidden" name="form_id" value="<?php echo esc_attr( $form_id ); ?>">
	<input type="hidden" name="_krm_nonce" value="<?php echo esc_attr( wp_create_nonce( 'krm_form_submit' ) ); ?>">
	<input type="hidden" name="token" value="<?php echo esc_attr( krm_form_token() ); ?>">
	<input type="hidden" name="page" value="<?php echo esc_url( $page ); ?>">
	<p class="krm-hp" aria-hidden="true" style="position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden"><label>Leave this field empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
	<?php
}

/**
 * Attributes of a Phase 24 form element (action, method, data attributes).
 *
 * @param string $type    Form type.
 * @param string $form_id Form id.
 */
function krm_form_attrs( $type, $form_id ) {
	$state = krm_form_state( $form_id );
	printf(
		'id="%1$s" action="%2$s" method="post" data-krm-send="%3$s" novalidate%4$s',
		esc_attr( $form_id ),
		esc_url( admin_url( 'admin-post.php' ) ),
		esc_attr( $type ),
		$state && $state['ok'] ? ' data-krm-done' : '' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed string.
	);
}

/**
 * The form's message area: a screen-reader live region (polite) and the visible success / error box, filled from the
 * no-JS outcome when there is one. Texts: {name} becomes the visitor's first name.
 *
 * @param string $form_id Form id.
 * @param string $success Success text (already filled with krm_fill()).
 * @param string $error   Error text.
 * @param string $style   Optional inline style of the visible box (grid placement).
 */
function krm_form_messages( $form_id, $success, $error, $style = '' ) {
	$state = krm_form_state( $form_id );
	$first = $state ? preg_split( '/\s+/', trim( $state['name'] ) )[0] : '';
	$ok    = $state && $state['ok'];
	$text  = '';
	if ( $state ) {
		if ( $ok ) {
			$text = '' !== $first ? str_replace( '{name}', $first, $success ) : preg_replace( '/,?\s*\{name\}/', '', $success );
		} elseif ( 'fields' === $state['code'] || 'rate' === $state['code'] || 'expired' === $state['code'] || 'invalid' === $state['code'] ) {
			$text = $state['message'];
		} else {
			$text = $error;
		}
	}
	?>
	<p class="visually-hidden" data-krm-live aria-live="polite"><?php echo $state ? esc_html( $text ) : ''; ?></p>
	<div class="krm-form-msg<?php echo $ok ? ' is-success' : ( $state ? ' is-error' : '' ); ?>" data-krm-msg data-success="<?php echo esc_attr( $success ); ?>" data-error="<?php echo esc_attr( $error ); ?>" tabindex="-1"<?php echo $state ? '' : ' hidden'; ?><?php echo $style ? ' style="' . esc_attr( $style ) . '"' : ''; ?>>
		<span class="krm-form-msg__icon" aria-hidden="true"><?php if ( $ok ) : ?><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg><?php elseif ( $state ) : ?><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v5M12 16h.01"></path></svg><?php endif; ?></span>
		<p class="krm-form-msg__text"><?php echo esc_html( $text ); ?></p>
	</div>
	<?php
}

/**
 * aria-invalid / aria-describedby and the error note of a field after a failed no-JS post.
 *
 * @param string $form_id Form id.
 * @param string $key     Field.
 * @return array{0: string, 1: string} Attributes for the input, and the note's HTML (both escaped).
 */
function krm_field_error( $form_id, $key ) {
	$state = krm_form_state( $form_id );
	if ( ! $state || empty( $state['fields'][ $key ] ) ) {
		return array( '', '' );
	}
	$id = 'krm-err-' . $form_id . '-' . $key;
	return array(
		' aria-invalid="true" aria-describedby="' . esc_attr( $id ) . '"',
		'<span class="krm-field-error" id="' . esc_attr( $id ) . '">' . esc_html( $state['fields'][ $key ] ) . '</span>',
	);
}

/* ---------- Web3Forms ---------- */

/**
 * The Web3Forms access key (Kingdom Revival → Settings), or '' when form emails are sent by WordPress.
 */
function krm_w3_key() {
	$key = trim( (string) krm_opt( 'web3forms_key' ) );
	return preg_match( '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $key ) ? $key : '';
}

/**
 * Web3Forms request body for an email built for krm_send_form_mail() (subject, rows, reply_to, whatsapp, page, admin).
 * Web3Forms emails every other key as "label: value"; access_key, subject, from_name and replyto are its own fields.
 *
 * @param array $mail Email parts.
 */
function krm_w3_payload( array $mail ) {
	$body = array(
		'access_key' => krm_w3_key(),
		'subject'    => krm_mail_line( $mail['subject'] ),
		'from_name'  => 'Kingdom Revival Website',
	);
	if ( ! empty( $mail['reply_to'] ) && is_email( $mail['reply_to'] ) ) {
		$body['replyto'] = $mail['reply_to'];
	}
	if ( ! empty( $mail['notice'] ) ) {
		$body['Note'] = $mail['notice'];
	}
	foreach ( $mail['rows'] as $label => $value ) {
		$body[ $label ] = '' === trim( (string) $value ) ? '—' : (string) $value;
	}
	if ( ! empty( $mail['whatsapp'] ) ) {
		$body['WhatsApp'] = $mail['whatsapp'];
	}
	$body['Sent from'] = $mail['page'];
	$body['Received']  = wp_date( 'l, j F Y, g:i a', time(), new DateTimeZone( 'Asia/Karachi' ) ) . ' PKT';
	if ( ! empty( $mail['admin'] ) ) {
		$body['Saved in WordPress'] = $mail['admin'];
	}
	return $body;
}

/**
 * What the browser gets: the Web3Forms body, plus the entry and a signed receipt to report the result with.
 *
 * @param array $payload Web3Forms body.
 * @param int   $post_id Saved submission (0: none).
 */
function krm_w3_envelope( array $payload, $post_id ) {
	return array(
		'payload' => $payload,
		'entry'   => $post_id,
		'receipt' => $post_id ? hash_hmac( 'sha256', 'krm_w3|' . $post_id, wp_salt( 'nonce' ) ) : '',
	);
}

/**
 * Send a Web3Forms body from the server (no-JavaScript posts). Web3Forms' free plan may refuse server-side requests;
 * the result is recorded on the entry either way.
 *
 * @param array $payload Web3Forms body.
 * @return array{0: bool, 1: string}
 */
function krm_w3_send_server( array $payload ) {
	$response = wp_remote_post(
		'https://api.web3forms.com/submit',
		array(
			'timeout' => 15,
			'headers' => array(
				'Content-Type' => 'application/json',
				'Accept'       => 'application/json',
			),
			'body'    => wp_json_encode( $payload ),
		)
	);
	if ( is_wp_error( $response ) ) {
		return array( false, 'Web3Forms: ' . $response->get_error_message() );
	}
	$json = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( is_array( $json ) && ! empty( $json['success'] ) ) {
		return array( true, '' );
	}
	return array( false, 'Web3Forms: HTTP ' . wp_remote_retrieve_response_code( $response ) . ( is_array( $json ) && ! empty( $json['message'] ) ? ' — ' . sanitize_text_field( $json['message'] ) : '' ) );
}

/**
 * The browser reports whether Web3Forms accepted the email (POST entry, receipt, ok, error). Only an entry still
 * waiting for its result can be updated, and only with the receipt signed for it.
 */
function krm_form_mailed_route() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- the signed receipt authorises this one update.
	$id      = isset( $_POST['entry'] ) ? absint( $_POST['entry'] ) : 0;
	$receipt = isset( $_POST['receipt'] ) ? sanitize_text_field( wp_unslash( $_POST['receipt'] ) ) : '';
	$ok      = isset( $_POST['ok'] ) && '1' === $_POST['ok'];
	$error   = isset( $_POST['error'] ) ? sanitize_text_field( wp_unslash( $_POST['error'] ) ) : '';
	// phpcs:enable
	if ( ! $id || ! hash_equals( hash_hmac( 'sha256', 'krm_w3|' . $id, wp_salt( 'nonce' ) ), $receipt ) || ! in_array( get_post_type( $id ), array( 'krm_submission', 'testimonial' ), true ) ) {
		wp_send_json_error( null, 400 );
	}
	if ( 'pending' === get_post_meta( $id, '_krm_mail_status', true ) ) {
		update_post_meta( $id, '_krm_mail_status', $ok ? 'sent' : 'failed' );
		update_post_meta( $id, '_krm_emailed', $ok ? 1 : 0 );
		if ( ! $ok ) {
			update_post_meta( $id, '_krm_mail_error', 'Web3Forms: ' . mb_substr( '' !== $error ? $error : 'no answer', 0, 300 ) );
		}
	}
	wp_send_json_success();
}
add_action( 'wp_ajax_krm_form_mailed', 'krm_form_mailed_route' );
add_action( 'wp_ajax_nopriv_krm_form_mailed', 'krm_form_mailed_route' );
