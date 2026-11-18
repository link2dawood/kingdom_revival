<?php
/**
 * Ministry page, special layout: churches and house churches planted (the ministry's "Churches & house churches
 * planted" field: name | place | planted). Visitors: not printed at all while the ministry has none; administrators see a placeholder row.
 *
 * @param array $args { ministry }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_rows = krm_filled_rows( $args['ministry']['churches'] );
$krm_none = ! $krm_rows;
if ( $krm_none ) {
	if ( ! krm_show_placeholders() || ! in_array( $args['ministry']['slug'], array( 'church-planting' ), true ) ) {
		return; // Visitors: none yet. Administrators see the placeholder on the ministries that use this list.
	}
	$krm_rows = array( array( '[Church or house church]', '[Village or district]', '[Year]' ) );
}
?>
<section class="wrap ministry-special" id="churches" aria-labelledby="churches-heading">
	<?php krm_admin_only_note( $krm_none ); ?>
	<div class="ministry-head"><h2 id="churches-heading"><?php krm_t( 'ministry.churches_heading', 'site' ); ?></h2></div>
	<ul class="church-list">
		<?php foreach ( $krm_rows as $krm_row ) : ?>
		<li>
			<span class="church-pin" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z"></path><circle cx="12" cy="10" r="2.5"></circle></svg></span>
			<span><strong><?php echo esc_html( $krm_row[0] ); ?></strong>
			<?php if ( '' !== trim( (string) $krm_row[1] ) ) : ?><small><?php echo esc_html( $krm_row[1] ); ?></small><?php endif; ?>
			<?php if ( '' !== trim( (string) $krm_row[2] ) ) : ?><small><?php krm_t( 'ministry.churches_planted', 'site' ); ?> <?php echo esc_html( $krm_row[2] ); ?></small><?php endif; ?></span>
		</li>
		<?php endforeach; ?>
	</ul>
</section>
