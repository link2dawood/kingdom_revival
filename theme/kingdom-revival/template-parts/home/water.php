<?php
/**
 * Home: "Clean water for brick kiln families" — compact text-and-photo section after "Bringing Christ" (mission.php).
 * Text: Pages → Home → Page content → Clean water. The number is the clean water hand pumps impact number
 * (krm_opt( 'stat_6_value' ), the same one as the stats bar). Styles: .krm-water in assets/css/front.css.
 *
 * Photos (theme 1.21.3): up to three — one tall on the left, two stacked on the right (fewer fill the box) — by default
 * the client's Clean Water Project photos (assets/images/content/clean-water-1…3.jpg). They open in the photo viewer.
 * With all three empty, visitors see a plain tinted box with a water-drop icon; administrators also see a note.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_imgs = array();
foreach ( array( 'image', 'image2', 'image3' ) as $krm_key ) {
	$krm_ref = krm_c( 'water.' . $krm_key );
	if ( $krm_ref ) {
		$krm_imgs[] = array( $krm_ref, krm_c( 'water.' . $krm_key . '_alt' ) );
	}
}
$krm_n = count( $krm_imgs );
$krm_count = trim( (string) krm_public( krm_opt( 'stat_6_value' ) ) );
$krm_quote = trim( (string) krm_c( 'water.quote' ) );
$krm_b1    = trim( (string) krm_c( 'water.button1_label' ) );
$krm_b2    = trim( (string) krm_c( 'water.button2_label' ) );
?>
<!-- Clean water: hand pumps at brick kilns -->
<section id="clean-water" class="krm-water" aria-labelledby="krm-water-title">
<div class="krm-water__inner">
<div class="krm-water__text" data-reveal>
<p class="krm-water__eyebrow"><?php krm_t( 'water.eyebrow' ); ?></p>
<h2 id="krm-water-title" class="krm-water__title"><?php krm_h( 'water.heading' ); ?></h2>
<p class="krm-water__lead"><?php krm_t( 'water.text' ); ?></p>
<?php if ( '' !== $krm_count ) : ?>
<p class="krm-water__stat"><strong><?php echo esc_html( $krm_count ); ?>+</strong> <span><?php krm_t( 'water.stat_label' ); ?></span></p>
<?php endif; ?>
<?php if ( '' !== $krm_quote ) : ?>
<blockquote class="krm-about__verse krm-water__verse">
<p><?php krm_t( 'water.quote' ); ?></p>
<cite><?php krm_t( 'water.cite' ); ?></cite>
</blockquote>
<?php endif; ?>
<?php if ( '' !== $krm_b1 || '' !== $krm_b2 ) : ?>
<div class="krm-water__actions">
<?php if ( '' !== $krm_b1 ) : ?>
<a class="button button-primary" href="<?php krm_u( 'water.button1_url' ); ?>"><?php krm_t( 'water.button1_label' ); ?></a>
<?php endif; ?>
<?php if ( '' !== $krm_b2 ) : ?>
<a class="button button-secondary" href="<?php krm_u( 'water.button2_url' ); ?>"><?php krm_t( 'water.button2_label' ); ?></a>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
<figure class="krm-water__media<?php echo $krm_n ? ' has-' . (int) $krm_n : ' is-empty'; ?>" data-reveal>
<?php if ( $krm_n ) : ?>
	<?php foreach ( $krm_imgs as $krm_i => $krm_im ) : ?>
<img loading="lazy" decoding="async" <?php krm_img_src( $krm_im[0], 0 === $krm_i ? '(max-width: 900px) 55vw, 320px' : '(max-width: 900px) 45vw, 260px' ); ?> alt="<?php echo krm_alt( $krm_im[0], $krm_im[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" data-zoom="water">
	<?php endforeach; ?>
<?php else : ?>
<svg aria-hidden="true" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke-width="1.6" style="stroke: var(--color-heading)"><path d="M12 2.7l5.7 5.7a8 8 0 1 1-11.3 0z"></path></svg>
	<?php if ( krm_show_placeholders() ) : ?>
<figcaption class="krm-admin-only"><?php esc_html_e( 'Photo slot: add hand pump photos in Pages → Home → Page content → Clean water. Visitors see this tinted box until then.', 'kingdom-revival' ); ?></figcaption>
	<?php endif; ?>
<?php endif; ?>
</figure>
</div>
</section>
