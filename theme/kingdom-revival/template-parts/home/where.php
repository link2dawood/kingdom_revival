<?php
/**
 * Home: "Where we serve" — text, the served places and a map of Pakistan over a darkened photo.
 *
 * The map is assets/images/pakistan-map.svg (provinces from geoBoundaries, public domain, built at dev time by
 * tools/map/build_pakistan_map.py), printed inline and hidden from screen readers: the places are listed as text.
 * Punjab is highlighted; a place in the list gets a marker when it is one of the towns below (their % positions on
 * the map come from the build script). Labels sit at the end of short leader lines pointing away from the cluster,
 * so they never overlap, even at 360px. Around each town, one dot per village and one square per kiln from its count text. Styles: assets/css/front.css (.krm-where).
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
// Dots: one per village and one (square) per brick kiln, read from each place's "40 villages · 8 kilns" text,
// scattered around its town with a fixed seed so the pattern is the same on every load.
$krm_dots = '';
foreach ( $krm_places as $krm_place ) {
	$krm_name = strtolower( trim( wp_strip_all_tags( krm_fill( $krm_place['name'] ) ) ) );
	if ( ! isset( $krm_towns[ $krm_name ] ) ) {
		continue;
	}
	$krm_count = (string) $krm_place['count'];
	$krm_v     = preg_match( '/(\d+)\s*villages?/i', $krm_count, $krm_m ) ? min( 300, (int) $krm_m[1] ) : 0;
	$krm_k     = preg_match( '/(\d+)\s*kilns?/i', $krm_count, $krm_m ) ? min( 100, (int) $krm_m[1] ) : 0;
	$krm_seed  = crc32( $krm_name );
	$krm_rand  = static function () use ( &$krm_seed ) {
		$krm_seed = ( $krm_seed * 1103515245 + 12345 ) & 0x7fffffff;
		return ( $krm_seed + 1 ) / 0x80000001;
	};
	$krm_cx    = $krm_towns[ $krm_name ][0] * 10;
	$krm_cy    = $krm_towns[ $krm_name ][1] * 9.11;
	$krm_sigma = 6 + 4.2 * sqrt( $krm_v + $krm_k );
	for ( $krm_i = 0; $krm_i < $krm_v + $krm_k; $krm_i++ ) {
		$krm_r  = min( 2.4, sqrt( -2 * log( $krm_rand() ) ) ) * $krm_sigma; // Gaussian spread, clipped.
		$krm_a  = 2 * M_PI * $krm_rand();
		$krm_x  = round( $krm_cx + cos( $krm_a ) * $krm_r, 1 );
		$krm_y  = round( $krm_cy + sin( $krm_a ) * $krm_r, 1 );
		$krm_d  = ' style="--d:' . (int) ( $krm_rand() * 1200 ) . 'ms"';
		$krm_dots .= $krm_i < $krm_v
			? '<circle class="krm-where__v" cx="' . $krm_x . '" cy="' . $krm_y . '" r="5"' . $krm_d . '/>'
			: '<rect class="krm-where__k" x="' . ( $krm_x - 4.5 ) . '" y="' . ( $krm_y - 4.5 ) . '" width="9" height="9"' . $krm_d . '/>';
	}
}
if ( $krm_dots ) {
	// Kept inside Punjab: clipped to the highlighted province's outline from the map.
	$krm_clip = preg_match( '/data-province="Punjab" d="([^"]+)"/', $krm_svg, $krm_m ) ? $krm_m[1] : '';
	$krm_dots = $krm_clip ? '<clipPath id="krm-where-clip"><path d="' . esc_attr( $krm_clip ) . '"/></clipPath><g clip-path="url(#krm-where-clip)">' . $krm_dots . '</g>' : '<g>' . $krm_dots . '</g>';
	echo '<svg class="krm-where__dots" viewBox="0 0 1000 911" focusable="false">' . $krm_dots . '</svg>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- numbers only.
}
foreach ( $krm_places as $krm_place ) :
	$krm_name = strtolower( trim( wp_strip_all_tags( krm_fill( $krm_place['name'] ) ) ) );
	if ( ! isset( $krm_towns[ $krm_name ] ) ) {
		continue;
	}
	list( $krm_x, $krm_y, $krm_dir, $krm_hq ) = $krm_towns[ $krm_name ];
	?>
<span class="krm-where__town krm-where__town--<?php echo esc_attr( $krm_dir . ( $krm_hq ? ' krm-where__town--hq' : '' ) ); ?>" style="left: <?php echo esc_attr( $krm_x ); ?>%; top: <?php echo esc_attr( $krm_y ); ?>%"><span class="krm-where__dot"></span><span class="krm-where__leader"></span><span class="krm-where__label"><?php echo krm_text( $krm_place['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php if ( $krm_hq ) : ?> <small>HQ</small><?php endif; ?></span></span>
<?php endforeach; ?>
<?php if ( $krm_dots ) : ?>
<p class="krm-where__legend"><span><i class="krm-where__lv"></i><?php esc_html_e( 'Village', 'kingdom-revival' ); ?></span><span><i class="krm-where__lk"></i><?php esc_html_e( 'Brick kiln', 'kingdom-revival' ); ?></span></p>
<?php endif; ?>
</div>
</div>
</section>
