<?php
/**
 * Ministry page 8: three other ministries of the same group (krm_related_ministries()) as What We Do giving cards.
 *
 * @param array $args { ministry }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_list = krm_related_ministries( $args['ministry'] );
if ( ! $krm_list ) {
	return;
}
?>
<section class="section section-soft ministry-others" aria-labelledby="others-heading">
<div class="wrap">
	<div class="ministry-head">
		<div>
			<p class="ministry-kicker"><?php krm_t( 'ministry.other_eyebrow', 'site' ); ?></p>
			<h2 id="others-heading"><?php krm_t( 'ministry.other_heading', 'site' ); ?></h2>
		</div>
		<p><a href="<?php echo esc_url( krm_ministries_url() ); ?>"><?php krm_t( 'ministry.other_more', 'site' ); ?></a></p>
	</div>
	<ul class="ministry-grid">
		<?php foreach ( $krm_list as $krm_i => $krm_item ) : ?>
		<li>
			<?php
			get_template_part(
				'template-parts/ministry-card',
				null,
				array(
					'ministry' => $krm_item,
					'index'    => $krm_i,
				)
			);
			?>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
</section>
