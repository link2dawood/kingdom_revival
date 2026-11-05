<?php
/**
 * One ministry giving card: the home page design's "causes" card (photo with a navy label, title, text and red
 * "Donate Now" button) plus a "Learn more →" link to the ministry page.
 *
 * Styles: .cause-card in theme-global.css.
 *
 * @param array $args {
 *     ministry: one item from krm_ministries().
 *     index:    position in the list (alternates the design's two placeholder tones).
 *     heading:  heading level of the title (default 'h3').
 * }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m = isset( $args['ministry'] ) ? $args['ministry'] : null;
if ( ! $krm_m ) {
	return;
}
$krm_h      = isset( $args['heading'] ) && in_array( $args['heading'], array( 'h2', 'h3', 'h4' ), true ) ? $args['heading'] : 'h3';
$krm_donate = krm_ministry_donate_url( $krm_m['slug'] );
$krm_title  = $krm_m['title'];
$krm_alt    = $krm_m['image'] ? trim( (string) krm_ref_alt( $krm_m['image'] ) ) : '';
$krm_alt    = '' !== $krm_alt ? $krm_alt : $krm_title; // The photo's own description, else the ministry's name.
?>
<article class="cause-card<?php echo ( isset( $args['index'] ) && $args['index'] % 2 ) ? ' is-alt' : ''; ?>" data-group="<?php echo esc_attr( $krm_m['group_slug'] ); ?>">
	<div class="cause-card-media">
		<?php if ( $krm_m['image'] ) : ?>
		<img <?php krm_img_src( $krm_m['image'], '(max-width: 680px) calc(100vw - 32px), (max-width: 1040px) 50vw, 400px', true ); ?> alt="<?php echo esc_attr( $krm_alt ); ?>" width="400" height="210" loading="lazy" decoding="async">
		<?php endif; ?>
		<?php if ( '' !== $krm_m['tag'] ) : ?>
		<span class="cause-card-tag"><?php echo esc_html( $krm_m['tag'] ); ?></span>
		<?php endif; ?>
	</div>
	<div class="cause-card-body">
		<<?php echo $krm_h; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- whitelisted above. ?> class="cause-card-title"><a href="<?php echo esc_url( $krm_m['url'] ); ?>"><?php echo esc_html( $krm_title ); ?></a></<?php echo $krm_h; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( ! krm_is_placeholder( krm_public( $krm_m['summary'] ) ) ) : ?>
		<p class="cause-card-text"><?php echo esc_html( $krm_m['summary'] ); ?></p>
		<?php endif; ?>
		<div class="cause-card-actions">
			<a class="cause-card-donate" href="<?php echo esc_url( $krm_donate ); ?>"><?php krm_t( 'ministries.donate_label', 'site' ); ?><span class="visually-hidden"> <?php echo esc_html( sprintf( /* translators: %s: ministry name */ __( 'to %s', 'kingdom-revival' ), $krm_title ) ); ?></span></a>
			<a class="cause-card-more" href="<?php echo esc_url( $krm_m['url'] ); ?>"><?php krm_t( 'ministries.card_button', 'site' ); ?><span class="visually-hidden"> <?php echo esc_html( sprintf( /* translators: %s: ministry name */ __( 'about %s', 'kingdom-revival' ), $krm_title ) ); ?></span></a>
		</div>
	</div>
</article>
