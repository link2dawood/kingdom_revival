<?php
/**
 * Home: "Clean water for brick kiln families" — compact text-and-photo section after "Bringing Christ" (mission.php).
 * Text: Pages → Home → Page content → Clean water. The number is the clean water hand pumps impact number
 * (krm_opt( 'stat_6_value' ), the same one as the stats bar). Styles: .krm-water in assets/css/front.css.
 *
 * Photo: no hand pump photo exists yet, so the Photo field is empty by default. Visitors then see a plain tinted box
 * with a water-drop icon; administrators also see a note saying the slot is waiting for a photo.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_img   = krm_c( 'water.image' );
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
<figure class="krm-water__media<?php echo $krm_img ? '' : ' is-empty'; ?>" data-reveal>
<?php if ( $krm_img ) : ?>
<img loading="lazy" decoding="async" <?php krm_img_src( $krm_img, '(max-width: 900px) calc(100vw - 32px), 560px' ); ?> alt="<?php echo krm_alt( $krm_img, krm_c( 'water.image_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
<?php else : ?>
<svg aria-hidden="true" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke-width="1.6" style="stroke: var(--color-heading)"><path d="M12 2.7l5.7 5.7a8 8 0 1 1-11.3 0z"></path></svg>
	<?php if ( krm_show_placeholders() ) : ?>
<figcaption class="krm-admin-only"><?php esc_html_e( 'Photo slot: add a hand pump photo in Pages → Home → Page content → Clean water. Visitors see this tinted box until then.', 'kingdom-revival' ); ?></figcaption>
	<?php endif; ?>
<?php endif; ?>
</figure>
</div>
</section>
