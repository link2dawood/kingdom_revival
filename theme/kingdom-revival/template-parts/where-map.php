<?php
/**
 * Map of Pakistan with Punjab highlighted, a marker and label for each known town, and around each town one dot per
 * village and one square per brick kiln. Shared by the home "Where we serve" section (template-parts/home/where.php)
 * and the Our Impact "Serving Punjab, village by village" section (page-templates/impact.php, since theme 1.21.3).
 *
 * The map is assets/images/pakistan-map.svg (provinces from geoBoundaries, public domain, built at dev time by
 * tools/map/build_pakistan_map.py), printed inline. Labels sit at the end of short leader lines pointing away from the
 * cluster, so they never overlap, even at 360px. Styles: .krm-where__map… in assets/css/theme-global.css; the dots pop
 * in on scroll (assets/js/effects.js).
 *
 * Args:
 * - places: list of array( 'name' => plain text, 'villages' => int, 'kilns' => int ). A place gets a marker only when
 *   it is one of the towns below (their % positions come from the build script); "(HQ)" in a name is ignored.
 * - label:  accessible name for the map (role="img"); empty = hidden from screen readers (the places are listed as text).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

// Towns: [ % from the left, % from the top, label direction (n/e/s/w), headquarters, label ].
$krm_towns  = array(
	'faisalabad'     => array( 72.10, 41.93, 'e', true, 'Faisalabad' ),
	'jhang'          => array( 67.34, 43.28, 'w', false, 'Jhang' ),
	'chiniot'        => array( 71.19, 39.92, 'n', false, 'Chiniot' ),
	'toba tek singh' => array( 68.30, 45.49, 's', false, 'Toba Tek Singh' ),
);
$krm_places = array();
foreach ( (array) ( $args['places'] ?? array() ) as $krm_place ) {
	$krm_key = strtolower( trim( preg_replace( '/\(\s*hq\s*\)/i', '', (string) $krm_place['name'] ) ) );
	if ( isset( $krm_towns[ $krm_key ] ) ) {
		$krm_places[ $krm_key ] = array( (int) ( $krm_place['villages'] ?? 0 ), (int) ( $krm_place['kilns'] ?? 0 ), trim( preg_replace( '/\(\s*hq\s*\)/i', '', (string) $krm_place['name'] ) ) );
	}
}
$krm_label = (string) ( $args['label'] ?? '' );

$krm_svg = (string) file_get_contents( KRM_DIR . '/assets/images/pakistan-map.svg' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- theme file.
$krm_svg = preg_replace( '/<!--.*?-->/s', '', $krm_svg );
if ( '' !== $krm_label ) {
	$krm_svg = preg_replace( '/<svg\b/', '<svg aria-hidden="true"', $krm_svg, 1 );
}
?>
<div class="krm-where__map"<?php echo '' !== $krm_label ? ' role="img" aria-label="' . esc_attr( $krm_label ) . '"' : ' aria-hidden="true"'; ?>>
<?php
echo $krm_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme SVG.
// Dots scattered around each town with a fixed seed, so the pattern is the same on every load.
$krm_dots = '';
foreach ( $krm_places as $krm_key => $krm_p ) {
	$krm_v    = min( 300, max( 0, $krm_p[0] ) );
	$krm_k    = min( 100, max( 0, $krm_p[1] ) );
	$krm_seed = crc32( $krm_key );
	$krm_rand = static function () use ( &$krm_seed ) {
		$krm_seed = ( $krm_seed * 1103515245 + 12345 ) & 0x7fffffff;
		return ( $krm_seed + 1 ) / 0x80000001;
	};
	$krm_cx    = $krm_towns[ $krm_key ][0] * 10;
	$krm_cy    = $krm_towns[ $krm_key ][1] * 9.11;
	$krm_sigma = 6 + 4.2 * sqrt( $krm_v + $krm_k );
	for ( $krm_i = 0; $krm_i < $krm_v + $krm_k; $krm_i++ ) {
		$krm_r     = min( 2.4, sqrt( -2 * log( $krm_rand() ) ) ) * $krm_sigma; // Gaussian spread, clipped.
		$krm_a     = 2 * M_PI * $krm_rand();
		$krm_x     = round( $krm_cx + cos( $krm_a ) * $krm_r, 1 );
		$krm_y     = round( $krm_cy + sin( $krm_a ) * $krm_r, 1 );
		$krm_d     = ' style="--d:' . (int) ( $krm_rand() * 1200 ) . 'ms"';
		$krm_dots .= $krm_i < $krm_v
			? '<circle class="krm-where__v" cx="' . $krm_x . '" cy="' . $krm_y . '" r="5"' . $krm_d . '/>'
			: '<rect class="krm-where__k" x="' . ( $krm_x - 4.5 ) . '" y="' . ( $krm_y - 4.5 ) . '" width="9" height="9"' . $krm_d . '/>';
	}
}
if ( $krm_dots ) {
	// Kept inside Punjab: clipped to the highlighted province's outline from the map.
	$krm_clip = preg_match( '/data-province="Punjab" d="([^"]+)"/', $krm_svg, $krm_m ) ? $krm_m[1] : '';
	$krm_dots = $krm_clip ? '<clipPath id="krm-where-clip"><path d="' . esc_attr( $krm_clip ) . '"/></clipPath><g clip-path="url(#krm-where-clip)">' . $krm_dots . '</g>' : '<g>' . $krm_dots . '</g>';
	echo '<svg class="krm-where__dots" viewBox="0 0 1000 911" focusable="false" aria-hidden="true">' . $krm_dots . '</svg>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- numbers only.
}
foreach ( $krm_places as $krm_key => $krm_p ) :
	list( $krm_x, $krm_y, $krm_dir, $krm_hq ) = $krm_towns[ $krm_key ];
	?>
<span class="krm-where__town krm-where__town--<?php echo esc_attr( $krm_dir . ( $krm_hq ? ' krm-where__town--hq' : '' ) ); ?>" style="left: <?php echo esc_attr( $krm_x ); ?>%; top: <?php echo esc_attr( $krm_y ); ?>%" aria-hidden="true"><span class="krm-where__dot"></span><span class="krm-where__leader"></span><span class="krm-where__label"><?php echo esc_html( $krm_p[2] ); ?><?php if ( $krm_hq ) : ?> <small>HQ</small><?php endif; ?></span></span>
<?php endforeach; ?>
<?php if ( $krm_dots ) : ?>
<p class="krm-where__legend" aria-hidden="true"><span><i class="krm-where__lv"></i><?php esc_html_e( 'Village', 'kingdom-revival' ); ?></span><span><i class="krm-where__lk"></i><?php esc_html_e( 'Brick kiln', 'kingdom-revival' ); ?></span></p>
<?php endif; ?>
</div>
