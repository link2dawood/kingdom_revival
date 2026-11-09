<?php
/**
 * Home: "Where we serve" — text, the served places and a map of Pakistan over a darkened photo.
 *
 * The map is assets/images/pakistan-map.svg (provinces from geoBoundaries, public domain, built at dev time by
 * tools/map/build_pakistan_map.py), printed inline and hidden from screen readers: the places are listed as text.
 * Punjab is highlighted; a place in the list gets a marker when it is one of the towns below (their % positions on
 * the map come from the build script). Labels sit at the end of short leader lines pointing away from the cluster,
 * so they never overlap, even at 360px. Styles: assets/css/front.css (.krm-where).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

// Towns named on the site: [ % from the left, % from the top, label direction (n/e/s/w), headquarters ].
$krm_towns = array(
	'faisalabad'     => array( 72.10, 41.93, 'e', true ),
	'jhang'          => array( 67.34, 43.28, 'w', false ),
	'chiniot'        => array( 71.19, 39.92, 'n', false ),
	'toba tek singh' => array( 68.30, 45.49, 's', false ),
);
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

$krm_svg = (string) file_get_contents( KRM_DIR . '/assets/images/pakistan-map.svg' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- theme file.
$krm_svg = preg_replace( '/<!--.*?-->/s', '', $krm_svg );
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
<div class="krm-where__map" aria-hidden="true">
<?php
echo $krm_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme SVG.
foreach ( $krm_places as $krm_place ) :
	$krm_name = strtolower( trim( wp_strip_all_tags( krm_fill( $krm_place['name'] ) ) ) );
	if ( ! isset( $krm_towns[ $krm_name ] ) ) {
		continue;
	}
	list( $krm_x, $krm_y, $krm_dir, $krm_hq ) = $krm_towns[ $krm_name ];
	?>
<span class="krm-where__town krm-where__town--<?php echo esc_attr( $krm_dir . ( $krm_hq ? ' krm-where__town--hq' : '' ) ); ?>" style="left: <?php echo esc_attr( $krm_x ); ?>%; top: <?php echo esc_attr( $krm_y ); ?>%"><span class="krm-where__dot"></span><span class="krm-where__leader"></span><span class="krm-where__label"><?php echo krm_text( $krm_place['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php if ( $krm_hq ) : ?> <small>HQ</small><?php endif; ?></span></span>
<?php endforeach; ?>
</div>
</div>
</section>
