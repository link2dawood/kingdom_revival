<?php
/**
 * Home: "Where we serve", the second half of 6. Our Impact (template-parts/home/impact.php prints it inside its section):
 * text, the served places and the Pakistan ministry map. The Our Impact page shows the same block (theme 1.23.1).
 *
 * @param array $args { screen: content screen to read ('home' on other pages); heading: h2 | h3 (default h3). }
 *
 * The map is template-parts/where-map.php (shared with Our Impact), hidden from screen readers here: the places are
 * listed as text. Around each known town, one dot per village and one square per kiln from its count text; Lahore and
 * Karachi (theme 1.23.0) get three ministry-site dots and a shaded area, with the text as their note.
 * Styles: theme-global.css (.krm-where, .krm-where__map; shared with the Our Impact page).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_screen = isset( $args['screen'] ) ? (string) $args['screen'] : '';
$krm_hd     = isset( $args['heading'] ) && 'h2' === $args['heading'] ? 'h2' : 'h3';
$krm_places = krm_visible( krm_c( 'where.places', $krm_screen ), 'name' ); // Visitors: no [District] rows; counts only once set.
?>
<div id="where" class="krm-where__inner">
<div class="krm-where__text" data-reveal>
<p class="krm-where__eyebrow"><?php krm_t( 'where.eyebrow', $krm_screen ); ?></p>
<<?php echo $krm_hd; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- h2 or h3. ?> id="where-h" class="krm-where__title krm-where__title--sub"><?php krm_h( 'where.heading', $krm_screen ); ?></<?php echo $krm_hd; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
<p class="krm-where__lead"><?php krm_t( 'where.text', $krm_screen ); ?></p>
<?php if ( $krm_places ) : ?>
<p class="krm-where__region"><?php krm_t( 'where.region', $krm_screen ); ?></p>
<ul class="krm-where__places">
	<?php foreach ( $krm_places as $krm_place ) : ?>
<li><span class="krm-where__place"><span class="krm-where__bullet" aria-hidden="true"></span><?php echo krm_text( $krm_place['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php if ( '' !== trim( (string) $krm_place['count'] ) ) : ?><span class="krm-where__count"><?php echo krm_text( $krm_place['count'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php endif; ?></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
</div>
<?php
// The map (shared with Our Impact): one dot per village and one square per kiln from each place's "40 villages · 8 kilns"
// text; the text itself is the town's note (shown when it is pointed at or tapped).
$krm_map_places = array();
foreach ( $krm_places as $krm_place ) {
	$krm_count        = (string) $krm_place['count'];
	$krm_map_places[] = array(
		'name'     => wp_strip_all_tags( krm_fill( $krm_place['name'] ) ),
		'villages' => preg_match( '/(\d+)\s*villages?/i', $krm_count, $krm_m ) ? (int) $krm_m[1] : 0,
		'kilns'    => preg_match( '/(\d+)\s*kilns?/i', $krm_count, $krm_m ) ? (int) $krm_m[1] : 0,
		'note'     => wp_strip_all_tags( krm_fill( $krm_count ) ),
	);
}
get_template_part( 'template-parts/where-map', null, array( 'places' => $krm_map_places ) );
?>
</div>
