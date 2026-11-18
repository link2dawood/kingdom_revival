<?php
/**
 * Local-testing mailer (KRM_MAIL_CAPTURE, inc/mail.php): builds the email exactly as PHPMailer would send it, then
 * writes it to a folder (.eml = full message, .html = the HTML part, .txt = the plain-text part) instead of sending.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * PHPMailer that saves instead of sending.
 */
class KRM_Capture_Mailer extends PHPMailer\PHPMailer\PHPMailer {

	/**
	 * Folder to write to.
	 *
	 * @var string
	 */
	public $capture_dir = '';

	/**
	 * Write the built message to the capture folder.
	 *
	 * @throws PHPMailer\PHPMailer\Exception When the folder holds a FAIL file (test of the failure path) or can't be written.
	 * @return bool
	 */
	public function postSend() {
		$dir = $this->capture_dir;
		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
			file_put_contents( $dir . '/index.php', "<?php\n// Silence.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
			file_put_contents( $dir . '/.htaccess', "Require all denied\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		}
		if ( file_exists( $dir . '/FAIL' ) ) {
			throw new PHPMailer\PHPMailer\Exception( 'Capture: simulated sending failure (remove ' . basename( $dir ) . '/FAIL).' );
		}
		$slug = sanitize_title( substr( $this->Subject, 0, 60 ) ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$base = $dir . '/' . gmdate( 'Ymd-His' ) . '-' . substr( md5( uniqid( '', true ) ), 0, 6 ) . '-' . $slug;
		$ok   = file_put_contents( $base . '.eml', $this->getSentMIMEMessage() ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		if ( false === $ok ) {
			throw new PHPMailer\PHPMailer\Exception( 'Capture: could not write to ' . $dir );
		}
		$head = 'To: ' . implode( ', ', array_map( function ( $a ) { return $a[0]; }, $this->getToAddresses() ) ) . "\n" // phpcs:ignore Generic.Formatting.MultipleStatementAlignment
			. 'From: ' . $this->FromName . ' <' . $this->From . ">\n" // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			. 'Reply-To: ' . implode( ', ', array_map( function ( $a ) { return trim( $a[1] . ' <' . $a[0] . '>' ); }, $this->getReplyToAddresses() ) ) . "\n" // phpcs:ignore Generic.Formatting.MultipleStatementAlignment
			. 'Subject: ' . $this->Subject . "\n\n"; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( '' !== $this->AltBody || 'text/html' === $this->ContentType ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			file_put_contents( $base . '.html', $this->Body ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents,WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			file_put_contents( $base . '.txt', $head . $this->AltBody ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents,WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		} else {
			file_put_contents( $base . '.txt', $head . $this->Body ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents,WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		}
		return true;
	}
}
