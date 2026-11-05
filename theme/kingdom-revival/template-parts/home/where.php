<?php
/**
 * Home: "Where we work" — places list and map with pins.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_map = krm_c( 'where.map' );
?>
<!-- Where we work -->
<section id="where" style="background: var(--color-bg-soft)">
<div style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px); display: flex; flex-wrap: wrap; gap: 40px 64px; align-items: center">
<div style="flex: 1 1 380px; min-width: 0">
<p style="margin: 0 0 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--color-secondary-text)"><?php krm_t( 'where.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: clamp(32px, 3.8vw, 46px); font-weight: 800; line-height: 1.12; color: var(--color-heading)"><?php krm_h( 'where.heading' ); ?></h2>
<div style="display: flex; flex-direction: column; margin-top: 28px">
<?php foreach ( krm_visible( krm_c( 'where.places' ), 'name' ) as $krm_place ) : // Visitors: no [District] rows; counts only once set. ?>
<div style="display: flex; justify-content: space-between; gap: 16px; padding: 14px 0; border-bottom: 1px solid var(--color-border-2)">
<span style="display: flex; gap: 10px; align-items: center; font-weight: 700"><span aria-hidden="true" style="width: 10px; height: 10px; border-radius: 50%; background: var(--color-secondary)"></span><?php echo krm_text( $krm_place['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<?php if ( '' !== trim( (string) $krm_place['count'] ) ) : ?>
<span style="color: var(--color-text-muted)"><?php echo krm_text( $krm_place['count'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
</div>
<div style="flex: 1.2 1 440px; min-width: 0; height: 440px; border-radius: 14px; overflow: hidden; background: var(--color-placeholder); position: relative">
<?php
$krm_pins = krm_visible( krm_c( 'where.pins' ), 'label' );
if ( ! $krm_map ) :
	// Built-in map (assets/images/where-map.svg: central Punjab districts from geoBoundaries, public domain), inline so
	// the pins are drawn inside it and stay on their towns at every width (the SVG fills the box: slice). Pins: % of
	// the map's width and height, drawn as red map pointers whose tip is on the town; the first (headquarters) is larger.
	$krm_svg = (string) file_get_contents( KRM_DIR . '/assets/images/where-map.svg' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- theme file.
	$krm_svg = str_replace( '{TITLE}', esc_html( wp_strip_all_tags( krm_fill( krm_c( 'where.map_alt' ) ) ) ), $krm_svg );
	$krm_svg = str_replace( '<svg ', '<svg style="position: absolute; inset: 0; width: 100%; height: 100%; display: block" ', $krm_svg );
	$krm_marks = '';
	foreach ( $krm_pins as $krm_i => $krm_pin ) {
		$krm_x      = round( (float) $krm_pin['left'] * 12, 1 );
		$krm_y      = round( (float) $krm_pin['top'] * 8.2, 1 );
		$krm_k      = $krm_i ? 1.4 : 1.7; // The headquarters pin is a little larger.
		$krm_marks .= sprintf(
			'<g transform="translate(%1$s %2$s) scale(%3$s)"><ellipse cx="0" cy="0" rx="11" ry="4" fill-opacity=".25" style="fill: var(--color-navy-fixed)"/><path d="M0 0C-4-12-19-24-19-39A19 19 0 1 1 19-39C19-24 4-12 0 0Z" stroke-width="3" style="fill: var(--color-red-fixed); stroke: var(--color-white)"/><circle cx="0" cy="-39" r="7" style="fill: var(--color-white)"/></g><text x="%1$s" y="%4$s" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="24" font-weight="800" stroke-width="6" stroke-linejoin="round" paint-order="stroke" style="fill: var(--color-navy-fixed); stroke: var(--color-white)">%5$s</text>',
			esc_attr( $krm_x ),
			esc_attr( $krm_y ),
			esc_attr( $krm_k ),
			esc_attr( round( $krm_y - 64 * $krm_k, 1 ) ),
			esc_html( wp_strip_all_tags( krm_fill( $krm_pin['label'] ) ) )
		);
	}
	echo str_replace( '</svg>', $krm_marks . '</svg>', $krm_svg ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme SVG; pin values escaped above.
else :
	?>
<img loading="lazy" decoding="async" <?php krm_img_src( $krm_map, '(max-width: 960px) 100vw, 640px' ); ?> alt="<?php echo krm_alt( $krm_map, krm_c( 'where.map_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover">
<?php
foreach ( $krm_pins as $krm_i => $krm_pin ) :
	$krm_left = (float) $krm_pin['left'];
	$krm_top  = (float) $krm_pin['top'];
	$krm_size = $krm_i ? 30 : 38;
	?>
<svg aria-hidden="true" width="<?php echo (int) $krm_size; ?>" height="<?php echo (int) round( $krm_size * 1.3 ); ?>" viewBox="-21 -60 42 64" style="position: absolute; left: <?php echo esc_attr( $krm_left ); ?>%; top: <?php echo esc_attr( $krm_top ); ?>%; transform: translate(-50%, -94%); overflow: visible"><ellipse cx="0" cy="0" rx="11" ry="4" fill-opacity=".25" style="fill: var(--color-navy-fixed)"/><path d="M0 0C-4-12-19-24-19-39A19 19 0 1 1 19-39C19-24 4-12 0 0Z" stroke-width="3" style="fill: var(--color-red-fixed); stroke: var(--color-white)"/><circle cx="0" cy="-39" r="7" style="fill: var(--color-white)"/></svg>
<span style="position: absolute; left: <?php echo esc_attr( $krm_left ); ?>%; top: calc(<?php echo esc_attr( $krm_top ); ?>% - <?php echo (int) round( $krm_size * 1.3 ) + 22; ?>px); transform: translateX(-50%); white-space: nowrap; font-size: 13px; font-weight: 800; color: var(--color-heading); text-shadow: 0 0 3px var(--color-surface), 0 0 3px var(--color-surface)"><?php echo krm_text( $krm_pin['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<?php endforeach; ?>
<?php endif; ?>
</div>
</div>
</section>
