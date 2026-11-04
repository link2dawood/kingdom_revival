<?php
/**
 * Ministry page, special layout: camps / projects completed (the ministry's "Camps / projects completed" field: date |
 * location | people served), as the About timeline. Visitors: not printed at all while the ministry has none; administrators see a placeholder row.
 *
 * @param array $args { ministry }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_rows = krm_filled_rows( $args['ministry']['camps'] );
$krm_none = ! $krm_rows;
if ( $krm_none ) {
	if ( ! krm_show_placeholders() || ! in_array( $args['ministry']['slug'], array( 'medical-health', 'clean-water' ), true ) ) {
		return; // Visitors: none yet. Administrators see the placeholder on the ministries that use this list.
	}
	$krm_rows = array( array( '[Date]', '[Location]', '[People served]' ) );
}
?>
<section class="wrap ministry-special" aria-labelledby="camps-heading">
	<?php krm_admin_only_note( $krm_none ); ?>
	<div class="ministry-head"><h2 id="camps-heading"><?php krm_t( 'ministry.camps_heading', 'site' ); ?></h2></div>
	<ol class="timeline ministry-timeline">
		<?php foreach ( $krm_rows as $krm_row ) : ?>
		<li><strong><?php echo esc_html( $krm_row[0] ); ?></strong><h3><?php echo esc_html( $krm_row[1] ); ?></h3><?php if ( '' !== trim( (string) $krm_row[2] ) ) : ?><p><?php krm_t( 'ministry.camps_people', 'site' ); ?> <?php echo esc_html( $krm_row[2] ); ?></p><?php endif; ?></li>
		<?php endforeach; ?>
	</ol>
</section>
