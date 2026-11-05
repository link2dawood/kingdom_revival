<?php
/**
 * Ministry page 3: impact counters in the home stats bar's style (white card, round icons, navy panel). The values
 * are the ministry's "Impact stats" (also on Our Impact); numbers count up when the strip comes into view
 * (components.js). Visitors see only real numbers (none: the strip is not printed); administrators see the
 * placeholders.
 *
 * @param array $args { ministry }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m     = $args['ministry'];
$krm_stats = krm_ministry_counters( $krm_m );
if ( ! $krm_stats ) {
	return; // Visitors: no real numbers yet.
}
$krm_icons = krm_home_stat_icons();
$krm_real  = array_filter(
	$krm_stats,
	function ( $s ) {
		return ! krm_is_placeholder( $s[0] );
	}
);
?>
<div class="ministry-block">
<?php krm_admin_only_note( ! $krm_real ); ?>
<section class="ministry-stats" aria-label="<?php echo krm_site_ministry_text( 'ministry.stats_label', $krm_m['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped there. ?>">
	<ul class="ministry-stats-list">
		<?php foreach ( $krm_stats as $krm_i => $krm_stat ) : ?>
		<li>
			<span class="ministry-stat-icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2" style="stroke: var(--color-heading)"><path d="<?php echo esc_attr( $krm_icons[ $krm_i % count( $krm_icons ) ] ); ?>"></path></svg></span>
			<span class="ministry-stat-text"><strong<?php echo preg_match( '/^[^\[\d]*\d/', (string) $krm_stat[0] ) ? ' data-krm-count' : ''; ?>><?php echo esc_html( $krm_stat[0] ); ?></strong><span><?php echo esc_html( $krm_stat[1] ); ?></span></span>
		</li>
		<?php endforeach; ?>
	</ul>
	<div class="ministry-stats-note">
		<strong><?php krm_t( 'ministry.stats_note', 'site' ); ?></strong>
		<span><span class="ministry-stats-dots" aria-hidden="true"><span></span><span></span><span></span></span><?php krm_t( 'ministry.stats_note_sub', 'site' ); ?></span>
	</div>
</section>
</div>
