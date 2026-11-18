<?php
/**
 * Home: Scripture marquee under the hero (theme 1.24.0 as a rotator, a one-line marquee since 1.25.3). Every KJV verse
 * with its ministry tag and reference scrolls right to left in one slim navy strip (CSS animation, speed set in
 * assets/js/front.js); the list is printed twice (the copy aria-hidden) so the loop is seamless. Hover, keyboard focus
 * and the pause button stop it; with reduced motion it does not move and the row scrolls by hand instead.
 * Text: Pages → Home → Page content → 2. Scripture rotator.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_verses = array_values(
	array_filter(
		(array) krm_c( 'scripture.verses' ),
		function ( $v ) {
			return is_array( $v ) && '' !== trim( (string) ( $v['verse'] ?? '' ) );
		}
	)
);
if ( ! $krm_verses ) {
	return;
}

// Tag icons, chosen by the verse's "icon" word (24×24, stroked with currentColor).
$krm_icons = array(
	'cross'     => '<path d="M12 3v18"></path><path d="M6 9h12"></path>',
	'megaphone' => '<path d="M3 11v2a1 1 0 0 0 1 1h2l5 4V6L6 10H4a1 1 0 0 0-1 1z"></path><path d="M15.5 8.5a5 5 0 0 1 0 7"></path><path d="M18.5 5.5a9 9 0 0 1 0 13"></path>',
	'chain'     => '<path d="M9 17H7A5 5 0 0 1 7 7h2"></path><path d="M15 7h2a5 5 0 0 1 4 8"></path><path d="M8 12h3"></path><path d="M2 2l20 20"></path>',
	'drop'      => '<path d="M12 2.7l5.7 5.7a8 8 0 1 1-11.3 0z"></path>',
	'heart'     => '<path d="M19 14c1.5-1.5 3-3.2 3-5.5A5.5 5.5 0 0 0 12 5.5 5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4 3 5.5l7 7z"></path>',
	'hands'     => '<path d="M11 14h2a2 2 0 0 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16"></path><path d="M7 20l1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"></path><path d="M2 15l6 6"></path><path d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12z"></path>',
);
$krm_items = '';
foreach ( $krm_verses as $krm_v ) {
	$krm_icon   = strtolower( trim( (string) ( $krm_v['icon'] ?? '' ) ) );
	$krm_tag    = trim( (string) ( $krm_v['tag'] ?? '' ) );
	$krm_ref    = trim( (string) ( $krm_v['reference'] ?? '' ) );
	$krm_items .= '<li class="krm-verses__item">';
	if ( '' !== $krm_tag ) {
		$krm_items .= '<span class="krm-verses__tag">' . ( isset( $krm_icons[ $krm_icon ] ) ? '<svg aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $krm_icons[ $krm_icon ] . '</svg>' : '' ) . esc_html( $krm_tag ) . '</span>';
	}
	$krm_items .= '<q class="krm-verses__text">' . esc_html( krm_fill( trim( (string) $krm_v['verse'] ) ) ) . '</q>';
	if ( '' !== $krm_ref ) {
		$krm_items .= '<cite class="krm-verses__ref">' . esc_html( $krm_ref ) . '</cite>';
	}
	$krm_items .= '<span class="krm-verses__sep" aria-hidden="true"></span></li>';
}
?>
<!-- Scripture marquee -->
<section class="krm-verses" data-krm-verses aria-label="<?php esc_attr_e( 'Scripture', 'kingdom-revival' ); ?>">
<div class="krm-verses__viewport">
<div class="krm-verses__track">
<ul class="krm-verses__list" role="list"><?php echo $krm_items; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></ul>
<ul class="krm-verses__list" role="list" aria-hidden="true"><?php echo $krm_items; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above; the copy makes the loop seamless. ?></ul>
</div>
</div>
<button type="button" class="krm-verses__pause" data-krm-verses-pause aria-label="<?php esc_attr_e( 'Pause the verses', 'kingdom-revival' ); ?>" data-label-pause="<?php esc_attr_e( 'Pause the verses', 'kingdom-revival' ); ?>" data-label-play="<?php esc_attr_e( 'Play the verses', 'kingdom-revival' ); ?>"><svg class="krm-verses__i-pause" width="10" height="10" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 4h4v16H6zM14 4h4v16h-4z"></path></svg><svg class="krm-verses__i-play" width="10" height="10" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 4l13 8-13 8z"></path></svg></button>
</section>
