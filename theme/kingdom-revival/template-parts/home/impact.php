<?php
/**
 * Home: 6. Our Impact — heading, key achievements, animated counters, then "Where we serve": the places and the Pakistan
 * ministry map (template-parts/home/where.php). One dark band over a darkened photo (the map's photo band "where").
 * Text: Pages → Home → Page content → 6. Our Impact, and 6. Our Impact: the map. Styles: .krm-impact and .krm-where in
 * assets/css/front.css; the map itself in theme-global.css. Counters count up on scroll (assets/js/components.js).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

// Counters: a number not set yet (empty or not a number) is left out for visitors.
$krm_counters = array();
foreach ( (array) krm_c( 'impact.counters' ) as $krm_n ) {
	$krm_value = is_array( $krm_n ) ? preg_replace( '/[^0-9]/', '', (string) ( $krm_n['value'] ?? '' ) ) : '';
	$krm_label = is_array( $krm_n ) ? trim( (string) ( $krm_n['label'] ?? '' ) ) : '';
	if ( '' !== $krm_value && '' !== $krm_label ) {
		$krm_counters[] = array( number_format_i18n( (int) $krm_value ) . '+', krm_plural_label( krm_fill( $krm_label ), $krm_value ) );
	}
}
$krm_achievements = array_values( array_filter( array_map( 'trim', preg_split( '/\R/', (string) krm_c( 'impact.achievements' ) ) ) ) );
?>
<!-- Our Impact -->
<section id="impact" class="krm-where krm-impact" aria-labelledby="krm-impact-title"<?php echo krm_photo_band_attrs( krm_c( 'where.band_photo' ), 'where' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
<div class="krm-impact__inner">
<div class="krm-impact__top">
<div class="krm-impact__head" data-reveal>
<p class="krm-where__eyebrow"><?php krm_t( 'impact.eyebrow' ); ?></p>
<h2 id="krm-impact-title" class="krm-where__title"><?php krm_h( 'impact.heading' ); ?></h2>
<?php if ( '' !== trim( (string) krm_c( 'impact.intro' ) ) ) : ?>
<p class="krm-where__lead"><?php krm_t( 'impact.intro' ); ?></p>
<?php endif; ?>
<?php if ( '' !== trim( (string) krm_c( 'impact.link_label' ) ) ) : ?>
<p class="krm-impact__link"><a href="<?php krm_u( 'impact.link_url' ); ?>"><?php krm_t( 'impact.link_label' ); ?></a></p>
<?php endif; ?>
</div>
<?php if ( $krm_achievements ) : ?>
<div class="krm-impact__achieve" data-reveal>
<h3><?php krm_t( 'impact.achievements_title' ); ?></h3>
<ul>
	<?php foreach ( $krm_achievements as $krm_a ) : ?>
<li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"></path></svg><span><?php echo krm_text( $krm_a ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></li>
	<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
</div>
<?php if ( $krm_counters ) : ?>
<ul class="krm-impact__counters" data-reveal-stagger>
	<?php foreach ( $krm_counters as $krm_n ) : ?>
<li><strong data-krm-count><?php echo esc_html( $krm_n[0] ); ?></strong><span><?php echo esc_html( $krm_n[1] ); ?></span></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
</div>
<?php get_template_part( 'template-parts/home/where' ); ?>
</section>
