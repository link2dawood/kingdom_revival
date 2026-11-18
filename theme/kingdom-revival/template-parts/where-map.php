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
 * Lahore and Karachi (theme 1.23.0) are cities with a few ministry sites: three small markers around the city (same dot and
 * pulse as the towns) and a soft shaded area (Lahore district, clipped to Punjab; Karachi metro), styled like Punjab.
 * Every town shows its name and note in a tooltip when pointed at or tapped (effects.js toggles .is-open on tap).
 *
 * Args:
 * - places: list of array( 'name' => plain text, 'villages' => int, 'kilns' => int, 'note' => optional text for the
 *   tooltip, default "40 villages · 8 kilns" from the numbers ). A place gets a marker only when it is one of the towns
 *   below (their % positions come from tools/map/build_pakistan_map.py); "(HQ)" in a name is ignored.
 * - label:  accessible name for the map (role="img"); empty = hidden from screen readers (the places are listed as text).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

// Towns: [ % from the left, % from the top, label direction (n/e/s/w/ne/se), headquarters, label ].
// Faisalabad's label points south-east (theme 1.23.0) so that it clears Lahore, 7% to its east.
$krm_towns  = array(
	'faisalabad'     => array( 72.10, 41.93, 'se', true, 'Faisalabad' ),
	'jhang'          => array( 67.34, 43.28, 'w', false, 'Jhang' ),
	'chiniot'        => array( 71.19, 39.92, 'n', false, 'Chiniot' ),
	'toba tek singh' => array( 68.30, 45.49, 's', false, 'Toba Tek Singh' ),
	'lahore'         => array( 79.23, 41.41, 'e', false, 'Lahore' ),   // 31.5204 N, 74.3587 E.
	'karachi'        => array( 36.36, 90.73, 'e', false, 'Karachi' ),  // 24.8607 N, 67.0011 E.
);
// Cities: the shaded area (map units, viewBox 1000 × 911: centre x, centre y, radius x, radius y, clipped to Punjab) and
// the ministry sites around the city (offsets in map units; 1 unit ≈ 1.65 km). Lahore district ≈ 31.25–31.70 N,
// 74.0–74.6 E; Karachi metro ≈ 24.75–25.15 N, 66.85–67.45 E (shifted inland from the coast).
$krm_cities = array(
	'lahore'  => array( array( 788.9, 380.6, 18, 16, true ), array( array( -11, -7 ), array( 6, -10 ), array( -2, 11 ) ) ),
	'karachi' => array( array( 370.5, 818.5, 22, 17, false ), array( array( -8, -9 ), array( 11, -6 ), array( 13, 7 ) ) ),
);
$krm_places = array();
foreach ( (array) ( $args['places'] ?? array() ) as $krm_place ) {
	$krm_key = strtolower( trim( preg_replace( '/\(\s*hq\s*\)/i', '', (string) $krm_place['name'] ) ) );
	if ( isset( $krm_towns[ $krm_key ] ) ) {
		$krm_v    = (int) ( $krm_place['villages'] ?? 0 );
		$krm_k    = (int) ( $krm_place['kilns'] ?? 0 );
		$krm_note = trim( (string) ( $krm_place['note'] ?? '' ) );
		if ( '' === $krm_note ) {
			/* translators: %d: number of villages / brick kilns. */
			$krm_note = implode( ' · ', array_filter( array( $krm_v ? sprintf( _n( '%d village', '%d villages', $krm_v, 'kingdom-revival' ), $krm_v ) : '', $krm_k ? sprintf( _n( '%d kiln', '%d kilns', $krm_k, 'kingdom-revival' ), $krm_k ) : '' ) ) );
		}
		$krm_places[ $krm_key ] = array( $krm_v, $krm_k, trim( preg_replace( '/\(\s*hq\s*\)/i', '', (string) $krm_place['name'] ) ), $krm_note );
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
// Shaded areas of the cities in the list (Lahore's kept inside Punjab, like the dots).
$krm_areas = '';
foreach ( $krm_cities as $krm_key => $krm_city ) {
	if ( isset( $krm_places[ $krm_key ] ) ) {
		list( $krm_ax, $krm_ay, $krm_rx, $krm_ry, $krm_in ) = $krm_city[0];
		$krm_areas .= '<ellipse class="krm-where__area" cx="' . $krm_ax . '" cy="' . $krm_ay . '" rx="' . $krm_rx . '" ry="' . $krm_ry . '"' . ( $krm_in ? ' clip-path="url(#krm-where-clip)"' : '' ) . '/>';
	}
}
if ( $krm_dots || $krm_areas ) {
	// Kept inside Punjab: clipped to the highlighted province's outline from the map.
	$krm_clip = preg_match( '/data-province="Punjab" d="([^"]+)"/', $krm_svg, $krm_m ) ? $krm_m[1] : '';
	$krm_defs = $krm_clip ? '<clipPath id="krm-where-clip"><path d="' . esc_attr( $krm_clip ) . '"/></clipPath>' : '';
	if ( ! $krm_clip ) {
		$krm_areas = str_replace( ' clip-path="url(#krm-where-clip)"', '', $krm_areas );
	}
	$krm_dots = $krm_dots ? ( $krm_clip ? '<g clip-path="url(#krm-where-clip)">' . $krm_dots . '</g>' : '<g>' . $krm_dots . '</g>' ) : '';
	echo '<svg class="krm-where__dots" viewBox="0 0 1000 911" focusable="false" aria-hidden="true">' . $krm_defs . ( $krm_areas ? '<g class="krm-where__areas">' . $krm_areas . '</g>' : '' ) . $krm_dots . '</svg>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- numbers only.
}
foreach ( $krm_places as $krm_key => $krm_p ) :
	list( $krm_x, $krm_y, $krm_dir, $krm_hq ) = $krm_towns[ $krm_key ];
	$krm_class = 'krm-where__town krm-where__town--' . $krm_dir . ( $krm_hq ? ' krm-where__town--hq' : '' ) . ( isset( $krm_cities[ $krm_key ] ) ? ' krm-where__town--city' : '' );
	$krm_class .= $krm_x > 62 ? ' krm-where__town--tip-r' : ( $krm_x < 38 ? ' krm-where__town--tip-l' : '' ); // Tooltips stay inside the map.
	?>
<span class="<?php echo esc_attr( $krm_class ); ?>" style="left: <?php echo esc_attr( $krm_x ); ?>%; top: <?php echo esc_attr( $krm_y ); ?>%" aria-hidden="true">
	<?php if ( isset( $krm_cities[ $krm_key ] ) ) : ?>
		<?php foreach ( $krm_cities[ $krm_key ][1] as $krm_s ) : ?>
<span class="krm-where__site" style="--dx: <?php echo (int) $krm_s[0]; ?>; --dy: <?php echo (int) $krm_s[1]; ?>"><span class="krm-where__dot"></span></span>
		<?php endforeach; ?>
	<?php endif; ?>
<span class="krm-where__dot"></span><span class="krm-where__leader"></span><span class="krm-where__label"><?php echo esc_html( $krm_p[2] ); ?><?php if ( $krm_hq ) : ?> <small>HQ</small><?php endif; ?></span>
<span class="krm-where__tip"><strong><?php echo esc_html( $krm_p[2] ); ?></strong><?php if ( '' !== $krm_p[3] ) : ?> – <?php echo esc_html( $krm_p[3] ); ?><?php endif; ?></span>
</span>
<?php endforeach; ?>
<?php if ( $krm_dots || $krm_areas ) : ?>
<p class="krm-where__legend" aria-hidden="true"><?php if ( $krm_dots ) : ?><span><i class="krm-where__lv"></i><?php esc_html_e( 'Village', 'kingdom-revival' ); ?></span><span><i class="krm-where__lk"></i><?php esc_html_e( 'Brick kiln', 'kingdom-revival' ); ?></span><?php endif; ?><?php if ( $krm_areas ) : ?><span><i class="krm-where__la"></i><?php esc_html_e( 'Ministry area', 'kingdom-revival' ); ?></span><?php endif; ?></p>
<?php endif; ?>
</div>
