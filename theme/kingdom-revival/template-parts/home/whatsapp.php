<?php
/**
 * Home: floating WhatsApp chat button. It sits above the floating "Donate" button (floating-donate.php), which keeps
 * the bottom-right corner.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

if ( '' === trim( (string) krm_c( 'whatsapp.label' ) ) || '' === preg_replace( '/[^0-9]/', '', krm_opt( 'whatsapp' ) ) ) {
	return;
}
?>
<a class="krm-wa-float" href="<?php echo esc_url( krm_whatsapp_url() ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( krm_fill( krm_c( 'whatsapp.label' ) ) ); ?>" style="position: fixed; right: 20px; bottom: 86px; z-index: 50; width: 58px; height: 58px; border-radius: 50%; background: #0F8A4A; color: var(--color-white); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 24px rgba(0,0,0,0.22)"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.1-5.4A8.4 8.4 0 1 1 21 11.5z"></path></svg></a>
