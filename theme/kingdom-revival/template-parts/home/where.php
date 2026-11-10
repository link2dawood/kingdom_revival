<?php
/**
 * Home: "Where we serve" — text, the served places and a map of Pakistan over a darkened photo.
 *
 * The map is template-parts/where-map.php (shared with Our Impact), hidden from screen readers here: the places are
 * listed as text. Around each known town, one dot per village and one square per kiln from its count text.
 * Styles: assets/css/front.css (.krm-where section) and theme-global.css (.krm-where__map).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_places = krm_visible( krm_c( 'where.places' ), 'name' ); // Visitors: no [District] rows; counts only once set.

// The highlight: the "Communities reached" impact number (else the first one set).
$krm_stat = null;
foreach ( krm_visible_stats( 'home' ) as $krm_s ) {
	if ( null === $krm_stat || 0 === strcasecmp( trim( $krm_s['label'] ), 'Communities reached' ) ) {
		$krm_stat = $krm_s;
	}
	if ( 0 === strcasecmp( trim( $krm_s['label'] ), 'Communities reached' ) ) {
		break;
	}
}

?>
<!-- Where we serve -->
<section id="where" class="krm-where" aria-labelledby="where-h"<?php echo krm_photo_band_attrs( krm_c( 'where.band_photo' ), 'where' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
<div class="krm-where__inner">
<div class="krm-where__text">
<p class="krm-where__eyebrow"><?php krm_t( 'where.eyebrow' ); ?></p>
<h2 id="where-h" class="krm-where__title"><?php krm_h( 'where.heading' ); ?></h2>
<p class="krm-where__lead"><?php krm_t( 'where.text' ); ?></p>
<?php if ( $krm_stat ) : ?>
<p class="krm-where__stat"><strong data-krm-count><?php echo esc_html( $krm_stat['value'] ); ?>+</strong> <span><?php echo esc_html( $krm_stat['label'] ); ?></span></p>
<?php endif; ?>
<?php if ( $krm_places ) : ?>
<h3 class="krm-where__region"><?php krm_t( 'where.region' ); ?></h3>
<ul class="krm-where__places">
<?php foreach ( $krm_places as $krm_place ) : ?>
<li><span class="krm-where__place"><span class="krm-where__bullet" aria-hidden="true"></span><?php echo krm_text( $krm_place['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php if ( '' !== trim( (string) $krm_place['count'] ) ) : ?><span class="krm-where__count"><?php echo krm_text( $krm_place['count'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php endif; ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</div>
<?php
// The map (shared with Our Impact): one dot per village and one square per kiln from each place's "40 villages · 8 kilns" text.
$krm_map_places = array();
foreach ( $krm_places as $krm_place ) {
	$krm_count        = (string) $krm_place['count'];
	$krm_map_places[] = array(
		'name'     => wp_strip_all_tags( krm_fill( $krm_place['name'] ) ),
		'villages' => preg_match( '/(\d+)\s*villages?/i', $krm_count, $krm_m ) ? (int) $krm_m[1] : 0,
		'kilns'    => preg_match( '/(\d+)\s*kilns?/i', $krm_count, $krm_m ) ? (int) $krm_m[1] : 0,
	);
}
get_template_part( 'template-parts/where-map', null, array( 'places' => $krm_map_places ) );
?>
</div>
</div>
</section>
