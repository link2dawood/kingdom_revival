<?php
/**
 * Outgoing email (Phase 24): SMTP sending, the recipient/sender constants, the branded HTML + plain-text email for
 * form submissions, and a capture mode for local testing.
 *
 * Constants (put them in wp-config.php, never in the theme or git; all optional):
 *   KRM_FORMS_TO_EMAIL   Where form submissions go. Default Info@kingdomrevivalministries.com. Comma-separated for several.
 *   KRM_FORMS_FROM_EMAIL The From address of form emails. Default no-reply@kingdomrevivalministries.com.
 *   KRM_SMTP_HOST        SMTP server, e.g. mail.kingdomrevivalministries.com or smtp-relay.brevo.com. Without it
 *                        WordPress sends with PHP mail() as before.
 *   KRM_SMTP_PORT        587 (STARTTLS, default) or 465 (SSL).
 *   KRM_SMTP_SECURE      'tls' (default for 587), 'ssl' (default for 465) or '' (none; not recommended).
 *   KRM_SMTP_USER        SMTP login (usually the full mailbox address).
 *   KRM_SMTP_PASS        SMTP password or API/SMTP key.
 *   KRM_SMTP_FROM        Force this From address on every email the site sends (needed when the SMTP account may only
 *                        send as itself, e.g. a hosting mailbox). Default: leave each email's From as it is.
 *   KRM_MAIL_CAPTURE     Local/dev only: true (or a folder path) writes every email to wp-content/krm-mail/ as .eml + .html
 *                        instead of sending it. Ignored when the site's environment type is "production". If the
 *                        folder holds a file named FAIL, every send fails (to test the "email failed" path).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'KRM_FORMS_TO_EMAIL' ) ) {
	define( 'KRM_FORMS_TO_EMAIL', 'Info@kingdomrevivalministries.com' );
}
if ( ! defined( 'KRM_FORMS_FROM_EMAIL' ) ) {
	define( 'KRM_FORMS_FROM_EMAIL', 'no-reply@kingdomrevivalministries.com' );
}

/**
 * Recipients of form submissions (KRM_FORMS_TO_EMAIL), as a list of valid addresses.
 *
 * @return string[]
 */
function krm_forms_to() {
	$to = array_filter( array_map( 'trim', explode( ',', (string) KRM_FORMS_TO_EMAIL ) ), 'is_email' );
	return $to ? array_values( $to ) : array( get_option( 'admin_email' ) );
}

/* ---------- SMTP ---------- */

add_action(
	'phpmailer_init',
	function ( $phpmailer ) {
		if ( defined( 'KRM_SMTP_HOST' ) && KRM_SMTP_HOST ) {
			$port = defined( 'KRM_SMTP_PORT' ) && KRM_SMTP_PORT ? (int) KRM_SMTP_PORT : 587;
			$phpmailer->isSMTP();
			$phpmailer->Host        = (string) KRM_SMTP_HOST; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- PHPMailer API.
			$phpmailer->Port        = $port; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$phpmailer->SMTPSecure  = defined( 'KRM_SMTP_SECURE' ) ? (string) KRM_SMTP_SECURE : ( 465 === $port ? 'ssl' : 'tls' ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$phpmailer->SMTPAutoTLS = '' !== $phpmailer->SMTPSecure; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$phpmailer->Timeout     = 15; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			if ( defined( 'KRM_SMTP_USER' ) && KRM_SMTP_USER ) {
				$phpmailer->SMTPAuth = true; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$phpmailer->Username = (string) KRM_SMTP_USER; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$phpmailer->Password = defined( 'KRM_SMTP_PASS' ) ? (string) KRM_SMTP_PASS : ''; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			}
		}
		if ( defined( 'KRM_SMTP_FROM' ) && is_email( KRM_SMTP_FROM ) ) {
			$phpmailer->setFrom( KRM_SMTP_FROM, $phpmailer->FromName, false ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		}
		// Over SMTP, bounces go back to the sending address (envelope sender = From), which SPF checks.
		if ( defined( 'KRM_SMTP_HOST' ) && KRM_SMTP_HOST ) {
			$phpmailer->Sender = $phpmailer->From; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		}
		// The plain-text part of the next form email (krm_send_form_mail()).
		if ( '' !== $GLOBALS['krm_mail_alt'] ) {
			$phpmailer->AltBody = $GLOBALS['krm_mail_alt']; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		}
	},
	20
);
$GLOBALS['krm_mail_alt'] = '';

/* ---------- Capture (local testing) ---------- */

/**
 * The folder captured emails are written to, or '' when capture is off.
 */
function krm_mail_capture_dir() {
	if ( ! defined( 'KRM_MAIL_CAPTURE' ) || ! KRM_MAIL_CAPTURE || 'production' === wp_get_environment_type() ) {
		return '';
	}
	return is_string( KRM_MAIL_CAPTURE ) ? untrailingslashit( KRM_MAIL_CAPTURE ) : WP_CONTENT_DIR . '/krm-mail';
}

add_filter(
	'pre_wp_mail',
	function ( $short ) {
		$dir = krm_mail_capture_dir();
		if ( null !== $short || '' === $dir ) {
			return $short;
		}
		global $phpmailer;
		if ( ! ( $phpmailer instanceof KRM_Capture_Mailer ) ) {
			require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
			require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
			require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
			require_once KRM_DIR . '/inc/class-krm-capture-mailer.php';
			$phpmailer              = new KRM_Capture_Mailer( true ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- wp_mail() reuses this instance.
			$phpmailer::$validator  = static function ( $email ) {
				return (bool) is_email( $email );
			};
			$phpmailer->capture_dir = $dir;
		}
		return null;
	},
	1
);

/* ---------- Form email ---------- */

/**
 * Brand colours for email. Email clients ignore CSS custom properties, so these are the light theme's token values
 * (--color-primary, --color-secondary, --color-bg-soft, --color-text-muted, --color-success) written out.
 */
function krm_mail_colors() {
	return array(
		'navy'  => '#0C1C4F',
		'red'   => '#D4070B',
		'soft'  => '#F3F5FA',
		'line'  => '#DDE2EE',
		'text'  => '#1F2937',
		'muted' => '#5B6475',
		'green' => '#1E5B3A',
		'white' => '#FFFFFF',
	);
}

/**
 * Remove line breaks (header injection) and trim.
 *
 * @param string $value Value.
 */
function krm_mail_line( $value ) {
	return trim( preg_replace( '/[\r\n\t\0]+/', ' ', (string) $value ) );
}

/**
 * Send a form email: branded HTML with a plain-text alternative.
 *
 * @param array $mail {
 *     subject:  subject (line breaks removed).
 *     heading:  heading in the email header, e.g. "New volunteer".
 *     notice:   optional highlighted note above the fields (e.g. PRIVATE, donation intent).
 *     rows:     [ label => value ] in order.
 *     page:     URL of the page the form was sent from.
 *     reply_to: visitor's email (optional).
 *     reply_name: visitor's name.
 *     whatsapp: wa.me link (optional).
 *     admin:    link to the saved submission (optional).
 * }
 * @return array{0: bool, 1: string} Sent?, and the error message when not (for the admin screen only).
 */
function krm_send_form_mail( array $mail ) {
	$c       = krm_mail_colors();
	$subject = krm_mail_line( $mail['subject'] );
	$when    = wp_date( 'l, j F Y, g:i a', time(), new DateTimeZone( 'Asia/Karachi' ) ) . ' PKT';
	$site    = 'Kingdom Revival Ministries';

	/* Plain text */
	$text = $site . "\n" . $mail['heading'] . "\n" . str_repeat( '=', 40 ) . "\n\n";
	if ( ! empty( $mail['notice'] ) ) {
		$text .= strtoupper( wp_strip_all_tags( $mail['notice'] ) ) . "\n\n";
	}
	foreach ( $mail['rows'] as $label => $value ) {
		$value = '' === trim( (string) $value ) ? '—' : (string) $value;
		$text .= $label . ': ' . ( false !== strpos( $value, "\n" ) ? "\n" . $value : $value ) . "\n";
	}
	$text .= "\n";
	if ( ! empty( $mail['whatsapp'] ) ) {
		$text .= 'WhatsApp: ' . $mail['whatsapp'] . "\n";
	}
	if ( ! empty( $mail['reply_to'] ) ) {
		$text .= 'Reply: press Reply to answer ' . $mail['reply_to'] . "\n";
	}
	$text .= 'Sent from: ' . $mail['page'] . "\n" . 'Received: ' . $when . "\n";
	if ( ! empty( $mail['admin'] ) ) {
		$text .= 'Saved in WordPress: ' . $mail['admin'] . "\n";
	}

	/* HTML */
	$rows = '';
	foreach ( $mail['rows'] as $label => $value ) {
		$value = '' === trim( (string) $value ) ? '—' : (string) $value;
		$rows .= '<tr><th align="left" valign="top" style="padding:10px 14px;width:34%;border-bottom:1px solid ' . $c['line'] . ';font:700 13px/1.5 Arial,Helvetica,sans-serif;color:' . $c['muted'] . '">' . esc_html( $label ) . '</th>'
			. '<td valign="top" style="padding:10px 14px;border-bottom:1px solid ' . $c['line'] . ';font:400 15px/1.55 Arial,Helvetica,sans-serif;color:' . $c['text'] . '">' . nl2br( esc_html( $value ) ) . '</td></tr>';
	}
	$buttons = '';
	if ( ! empty( $mail['reply_to'] ) ) {
		$buttons .= '<a href="' . esc_url( 'mailto:' . $mail['reply_to'] ) . '" style="display:inline-block;margin:0 8px 8px 0;padding:11px 20px;border-radius:6px;background:' . $c['navy'] . ';color:' . $c['white'] . ';font:700 14px Arial,Helvetica,sans-serif;text-decoration:none">Reply by email</a>';
	}
	if ( ! empty( $mail['whatsapp'] ) ) {
		$buttons .= '<a href="' . esc_url( $mail['whatsapp'] ) . '" style="display:inline-block;margin:0 8px 8px 0;padding:11px 20px;border-radius:6px;background:' . $c['green'] . ';color:' . $c['white'] . ';font:700 14px Arial,Helvetica,sans-serif;text-decoration:none">Message on WhatsApp</a>';
	}
	$notice = '';
	if ( ! empty( $mail['notice'] ) ) {
		$notice = '<p style="margin:0 0 18px;padding:12px 14px;border-left:4px solid ' . $c['red'] . ';background:' . $c['soft'] . ';font:700 14px/1.5 Arial,Helvetica,sans-serif;color:' . $c['text'] . '">' . esc_html( $mail['notice'] ) . '</p>';
	}
	$meta = 'Sent from <a href="' . esc_url( $mail['page'] ) . '" style="color:' . $c['navy'] . '">' . esc_html( $mail['page'] ) . '</a><br>Received ' . esc_html( $when );
	if ( ! empty( $mail['admin'] ) ) {
		$meta .= '<br><a href="' . esc_url( $mail['admin'] ) . '" style="color:' . $c['navy'] . '">View it in WordPress (Form Submissions)</a>';
	}
	$html = '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>' . esc_html( $subject ) . '</title></head>'
		. '<body style="margin:0;padding:0;background:' . $c['soft'] . '">'
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:' . $c['soft'] . ';padding:24px 12px"><tr><td align="center">'
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;background:' . $c['white'] . ';border-radius:10px;overflow:hidden">'
		. '<tr><td style="background:' . $c['navy'] . ';padding:22px 26px;border-bottom:4px solid ' . $c['red'] . '">'
		. '<div style="font:800 13px/1.2 Arial,Helvetica,sans-serif;letter-spacing:.18em;text-transform:uppercase;color:' . $c['white'] . '">' . esc_html( $site ) . '</div>'
		. '<div style="margin-top:8px;font:800 22px/1.25 Arial,Helvetica,sans-serif;color:' . $c['white'] . '">' . esc_html( $mail['heading'] ) . '</div></td></tr>'
		. '<tr><td style="padding:24px 26px">' . $notice
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-top:1px solid ' . $c['line'] . '">' . $rows . '</table>'
		. ( $buttons ? '<div style="margin-top:22px">' . $buttons . '</div>' : '' )
		. '<p style="margin:18px 0 0;font:400 13px/1.6 Arial,Helvetica,sans-serif;color:' . $c['muted'] . '">' . $meta . '</p>'
		. '</td></tr></table>'
		. '<p style="margin:14px 0 0;font:400 12px/1.5 Arial,Helvetica,sans-serif;color:' . $c['muted'] . '">This message was sent by the form on ' . esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) ) . '.</p>'
		. '</td></tr></table></body></html>';

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: Kingdom Revival Website <' . KRM_FORMS_FROM_EMAIL . '>',
	);
	if ( ! empty( $mail['reply_to'] ) && is_email( $mail['reply_to'] ) ) {
		// wp_mail() splits Reply-To on commas and keeps quotes, so the name carries neither (PHPMailer quotes it).
		$name      = trim( str_replace( array( '"', '<', '>', '\\', ',', ';' ), '', krm_mail_line( isset( $mail['reply_name'] ) ? $mail['reply_name'] : '' ) ) );
		$headers[] = 'Reply-To: ' . ( '' !== $name ? $name . ' ' : '' ) . '<' . $mail['reply_to'] . '>';
	}

	$error  = '';
	$failed = function ( $wp_error ) use ( &$error ) {
		$error = $wp_error instanceof WP_Error ? $wp_error->get_error_message() : 'unknown error';
	};
	add_action( 'wp_mail_failed', $failed );
	$GLOBALS['krm_mail_alt'] = $text;
	$sent                    = wp_mail( krm_forms_to(), $subject, $html, $headers );
	$GLOBALS['krm_mail_alt'] = '';
	remove_action( 'wp_mail_failed', $failed );
	return array( (bool) $sent, $sent ? '' : ( '' !== $error ? $error : 'wp_mail() returned false' ) );
}
