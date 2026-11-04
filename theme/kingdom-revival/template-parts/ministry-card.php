<?php
/**
 * One ministry giving card: the home page design's "causes" card (photo with a navy label, title, text, raised/goal
 * progress bar, red "Donate Now") plus a "Learn more →" link to the ministry page.
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
$krm_pct    = krm_raised_pct( $krm_m );
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
		<?php if ( krm_show_progress( $krm_m['goal'] ) ) : ?>
		<div class="cause-card-progress">
			<div class="cause-card-amounts"><span><?php krm_t( 'ministries.raised_label', 'site' ); ?> <strong><?php echo esc_html( krm_card_amount( $krm_m['raised'] ) ); ?></strong></span><span><?php krm_t( 'ministries.goal_label', 'site' ); ?> <?php echo esc_html( krm_card_amount( $krm_m['goal'] ) ); ?></span></div>
			<div class="cause-card-bar" role="progressbar" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: ministry name */ __( '%s: share of the goal raised', 'kingdom-revival' ), $krm_title ) ); ?>" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo esc_attr( $krm_pct ); ?>"><span style="width: <?php echo esc_attr( $krm_pct ); ?>%"></span></div>
		</div>
		<?php endif; ?>
		<div class="cause-card-actions">
			<a class="cause-card-donate" href="<?php echo esc_url( $krm_donate ); ?>"><?php krm_t( 'ministries.donate_label', 'site' ); ?><span class="visually-hidden"> <?php echo esc_html( sprintf( /* translators: %s: ministry name */ __( 'to %s', 'kingdom-revival' ), $krm_title ) ); ?></span></a>
			<a class="cause-card-more" href="<?php echo esc_url( $krm_m['url'] ); ?>"><?php krm_t( 'ministries.card_button', 'site' ); ?><span class="visually-hidden"> <?php echo esc_html( sprintf( /* translators: %s: ministry name */ __( 'about %s', 'kingdom-revival' ), $krm_title ) ); ?></span></a>
		</div>
	</div>
</article>
