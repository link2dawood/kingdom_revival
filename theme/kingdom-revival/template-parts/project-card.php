<?php
/**
 * One project card: the giving card (.cause-card) for a project from krm_projects().
 * Current: photo, ministry, title, text, "Give to this project" (no raised/goal bar) (Donate, ministry preselected),
 * plus an outline "Learn more" to its detail page (/projects/{slug}/) when it has one.
 * Upcoming: photo with an "Upcoming" label, target amount, planned date, "Pledge / Get notified" (the Financial
 * Partner pledge form on Partner With Us, with the project filled in).
 *
 * @param array $args { project: one project; index: position (placeholder tone); heading: h2|h3|h4 (default h3). }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_p = isset( $args['project'] ) ? $args['project'] : null;
if ( ! $krm_p ) {
	return;
}
$krm_h        = isset( $args['heading'] ) && in_array( $args['heading'], array( 'h2', 'h3', 'h4' ), true ) ? $args['heading'] : 'h3';
$krm_upcoming = 'upcoming' === $krm_p['status'];
$krm_title    = wp_strip_all_tags( krm_fill( $krm_p['title'] ) ); // Plain text (alt, screen-reader labels).
$krm_heading  = krm_hl_on_light( krm_inline( $krm_p['title'] ) ); // Visible title: <span class="hl"> allowed.
if ( $krm_upcoming ) {
	$krm_link = add_query_arg( 'project', rawurlencode( wp_strip_all_tags( $krm_title ) ), krm_page_url( 'partner' ) ) . '#financial-partner';
} else {
	$krm_link = $krm_p['ministry_title'] ? krm_ministry_donate_url( $krm_p['ministry'] ) : krm_donate_url() . '#give';
}
$krm_alt = $krm_p['image'] ? trim( (string) krm_ref_alt( $krm_p['image'] ) ) : '';
?>
<article class="cause-card<?php echo ( isset( $args['index'] ) && $args['index'] % 2 ) ? ' is-alt' : ''; ?>">
	<div class="cause-card-media">
		<?php if ( $krm_p['image'] ) : ?>
		<img <?php krm_img_src( $krm_p['image'], '(max-width: 680px) calc(100vw - 32px), (max-width: 1040px) 50vw, 400px', true ); ?> alt="<?php echo esc_attr( '' !== $krm_alt ? $krm_alt : wp_strip_all_tags( $krm_title ) ); ?>" width="400" height="210" loading="lazy" decoding="async">
		<?php endif; ?>
		<span class="cause-card-tag<?php echo $krm_upcoming ? ' is-upcoming' : ''; ?>"><?php echo esc_html( $krm_upcoming ? krm_fill( krm_c( 'projects.upcoming_tag', 'donate' ) ) : krm_fill( krm_c( 'projects.current_tag', 'donate' ) ) ); ?></span>
	</div>
	<div class="cause-card-body">
		<?php if ( $krm_p['ministry_title'] ) : ?>
		<p class="cause-card-meta"><a href="<?php echo esc_url( $krm_p['ministry_url'] ); ?>"><?php echo esc_html( $krm_p['ministry_title'] ); ?></a></p>
		<?php endif; ?>
		<<?php echo $krm_h; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- whitelisted above. ?> class="cause-card-title"><?php echo $krm_heading; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- krm_inline() escapes. ?></<?php echo $krm_h; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( '' !== trim( (string) $krm_p['text'] ) ) : ?>
		<p class="cause-card-text"><?php echo esc_html( krm_fill( $krm_p['text'] ) ); ?></p>
		<?php endif; ?>
		<?php
		if ( $krm_upcoming ) :
			$krm_goal_on = krm_show_progress( $krm_p['goal'] );
			$krm_date    = (string) krm_public( trim( (string) $krm_p['date'] ) );
			if ( $krm_goal_on || '' !== $krm_date ) :
				?>
		<p class="cause-card-goal"><?php if ( $krm_goal_on ) : ?><?php krm_t( 'projects.target_label', 'donate' ); ?> <strong><?php echo esc_html( krm_card_amount( $krm_p['goal'] ) ); ?></strong><?php endif; ?><?php if ( $krm_goal_on && '' !== $krm_date ) : ?> · <?php endif; ?><?php if ( '' !== $krm_date ) : ?><?php krm_t( 'projects.date_label', 'donate' ); ?> <strong><?php echo esc_html( krm_fill( $krm_date ) ); ?></strong><?php endif; ?></p>
				<?php
			endif;
		endif;
		?>
		<div class="cause-card-actions">
			<?php if ( $krm_upcoming ) : ?>
			<a class="cause-card-more is-button" href="<?php echo esc_url( $krm_link ); ?>"><?php krm_t( 'projects.pledge_label', 'donate' ); ?><span class="visually-hidden">: <?php echo esc_html( $krm_title ); ?></span></a>
			<?php else : ?>
			<a class="cause-card-donate" href="<?php echo esc_url( $krm_link ); ?>"><?php krm_t( 'projects.give_label', 'donate' ); ?><span class="visually-hidden">: <?php echo esc_html( $krm_title ); ?></span></a>
				<?php if ( ! empty( $krm_p['page_url'] ) ) : ?>
			<a class="cause-card-more is-button" href="<?php echo esc_url( $krm_p['page_url'] ); ?>"><?php krm_t( 'projects.more_label', 'donate' ); ?><span class="visually-hidden">: <?php echo esc_html( $krm_title ); ?></span></a>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</article>
