<?php
/**
 * Home: 12. Final call to action — a full-width navy band over a photo: "Together, We Can Bring Hope" and three buttons
 * (Pray, Partner, Donate). Text: Pages → Home → Page content → 12. Final call to action.
 * (The Matthew 28:19 banner, template-parts/home/cta.php, is still used on the ministry pages.)
 * Styles: .krm-final in assets/css/front.css.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_buttons = array();
foreach ( array( 1, 2, 3 ) as $krm_n ) {
	if ( '' !== trim( (string) krm_c( "final.button{$krm_n}_label" ) ) ) {
		$krm_buttons[ $krm_n ] = array( krm_c( "final.button{$krm_n}_label" ), krm_c( "final.button{$krm_n}_url" ) );
	}
}
// Icons: praying hands, handshake, heart.
$krm_icons = array(
	1 => '<path d="M12 21l-3.5-3.5a3 3 0 0 1-.9-2.1V8.5a1.5 1.5 0 0 1 3 0V12"></path><path d="M12 21l3.5-3.5a3 3 0 0 0 .9-2.1V8.5a1.5 1.5 0 0 0-3 0V12"></path><path d="M12 12V4"></path>',
	2 => '<path d="M11 17l2 2a1 1 0 1 0 3-3"></path><path d="M14 14l2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"></path><path d="M21 3l1 11h-2"></path><path d="M3 3L2 14l6.5 6.5a1 1 0 1 0 3-3"></path><path d="M3 4h8"></path>',
	3 => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path>',
);
?>
<!-- Final call to action -->
<section id="bring-hope" class="krm-final" aria-labelledby="krm-final-title"<?php echo krm_photo_band_attrs( krm_c( 'final.band_photo' ), 'cta' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
<div class="krm-final__inner" data-reveal>
<h2 id="krm-final-title" class="krm-final__title"><?php krm_h( 'final.heading' ); ?></h2>
<?php if ( '' !== trim( (string) krm_c( 'final.text' ) ) ) : ?>
<p class="krm-final__text"><?php krm_t( 'final.text' ); ?></p>
<?php endif; ?>
<?php if ( $krm_buttons ) : ?>
<div class="krm-final__actions">
	<?php foreach ( $krm_buttons as $krm_n => $krm_b ) : ?>
<a class="krm-final__btn<?php echo 3 === $krm_n ? ' is-red' : ''; ?>" href="<?php echo esc_url( krm_resolve_url( $krm_b[1] ) ); ?>"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?php echo $krm_icons[ $krm_n ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup above. ?></svg><?php echo krm_text( $krm_b[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	<?php endforeach; ?>
</div>
<?php endif; ?>
</div>
</section>
