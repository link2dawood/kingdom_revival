<?php
/**
 * A ministry group card (Phase 21): the group's photo under a navy overlay, the number of live ministries, the group
 * name (the link to the group page; the whole card is clickable), its intro when it has one, and "Explore →".
 * Used on /what-we-do/ (two side by side) and on each group page (the card to the other group).
 *
 * @param array $args { group: an item of krm_groups(); heading: 'h2'|'h3' (default 'h3'); eyebrow: small line above
 *                      the name (optional). }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_g = isset( $args['group'] ) ? $args['group'] : null;
if ( ! $krm_g ) {
	return;
}
$krm_hl    = isset( $args['heading'] ) && 'h2' === $args['heading'] ? 'h2' : 'h3';
$krm_n     = count( $krm_g['ministries'] );
$krm_count = str_replace( '{n}', number_format_i18n( $krm_n ), krm_fill( krm_c( 1 === $krm_n ? 'ministries.count_one' : 'ministries.count_many', 'site' ) ) );
$krm_intro = trim( (string) krm_public( $krm_g['intro'] ) );
?>
<article class="wwd-group-card">
	<?php if ( $krm_g['image'] ) : ?>
	<img class="wwd-group-card-img" <?php krm_img_src( $krm_g['image'], '(max-width: 760px) calc(100vw - 32px), 600px' ); ?> alt="" loading="lazy" decoding="async">
	<?php endif; ?>
	<div class="wwd-group-card-body">
		<?php if ( ! empty( $args['eyebrow'] ) ) : ?>
		<p class="wwd-group-card-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p>
		<?php endif; ?>
		<p class="wwd-group-card-count"><?php echo esc_html( $krm_count ); ?></p>
		<<?php echo $krm_hl; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- whitelisted above. ?> class="wwd-group-card-title"><a href="<?php echo esc_url( $krm_g['url'] ); ?>"><?php echo esc_html( $krm_g['name'] ); ?></a></<?php echo $krm_hl; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( '' !== $krm_intro ) : ?>
		<p class="wwd-group-card-text<?php echo krm_is_placeholder( $krm_g['intro'] ) ? ' krm-admin-only' : ''; ?>"><?php echo esc_html( $krm_intro ); ?></p>
		<?php endif; ?>
		<span class="wwd-group-card-cta" aria-hidden="true"><?php krm_t( 'ministries.explore', 'site' ); ?></span>
	</div>
</article>
