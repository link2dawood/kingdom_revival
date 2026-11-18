<?php
/**
 * /what-we-do/{group}/ — one ministry group (Phase 21): inner hero with the group name; the group's intro (a
 * [placeholder] shows to administrators only); its live ministries as giving cards (template-parts/ministry-card.php,
 * client order, linking to /what-we-do/{group}/{ministry}/); the home page's "Your gift at work" cards; a card to the
 * other group. The closing banner is added by archive-ministry.php.
 *
 * @param array $args { group: an item of krm_groups() }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_g     = $args['group'];
$krm_hero  = $krm_g['image'] ? $krm_g['image'] : krm_c( 'ministries.image', 'site' );
$krm_intro = trim( (string) krm_public( $krm_g['intro'] ) );
$krm_other = array_filter(
	krm_groups(),
	function ( $g ) use ( $krm_g ) {
		return $g['slug'] !== $krm_g['slug'];
	}
);
?>
<?php krm_hero_preload( $krm_hero ); ?>
<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( $krm_hero ) ); ?>')<?php krm_bg_small( $krm_hero ); ?>">
	<div class="wrap">
	<?php krm_breadcrumb_html(); ?>
	<p class="eyebrow"><?php krm_t( 'ministries.group_eyebrow', 'site' ); ?></p>
	<h1><?php echo esc_html( $krm_g['name'] ); ?></h1>
	</div>
</section>

<section class="krm-band krm-band-soft wwd-group" aria-labelledby="wwd-group-heading">
	<div class="krm-container">
	<h2 class="visually-hidden" id="wwd-group-heading"><?php echo esc_html( $krm_g['name'] ); ?></h2>
	<?php if ( '' !== $krm_intro ) : ?>
	<p class="wwd-intro<?php echo krm_is_placeholder( $krm_g['intro'] ) ? ' krm-admin-only' : ''; ?>"><?php echo esc_html( $krm_intro ); ?></p>
	<?php endif; ?>
	<?php if ( $krm_g['ministries'] ) : ?>
	<ul class="ministry-grid">
		<?php foreach ( $krm_g['ministries'] as $krm_i => $krm_m ) : ?>
		<li>
			<?php
			get_template_part(
				'template-parts/ministry-card',
				null,
				array(
					'ministry' => $krm_m,
					'index'    => $krm_i,
				)
			);
			?>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php else : ?>
	<p class="empty-gallery"><?php krm_t( 'ministries.empty', 'site' ); ?></p>
	<?php endif; ?>
	</div>
</section>

<div class="krm-design">
	<?php get_template_part( 'template-parts/home/gift', null, array( 'screen' => 'home' ) ); ?>
</div>

<?php if ( $krm_other ) : ?>
<section class="section wwd-other" aria-label="<?php echo esc_attr( krm_fill( krm_c( 'ministries.other_eyebrow', 'site' ) ) ); ?>">
	<div class="wrap">
	<ul class="wwd-group-grid is-single">
		<?php foreach ( $krm_other as $krm_o ) : ?>
		<li>
			<?php
			get_template_part(
				'template-parts/what-we-do/group-card',
				null,
				array(
					'group'   => $krm_o,
					'heading' => 'h2',
					'eyebrow' => krm_fill( krm_c( 'ministries.other_eyebrow', 'site' ) ),
				)
			);
			?>
		</li>
		<?php endforeach; ?>
	</ul>
	</div>
</section>
<?php endif; ?>
