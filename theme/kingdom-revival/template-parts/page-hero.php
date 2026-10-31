<?php
/**
 * Page hero used by templates the design did not include (generic pages, archives, search, 404).
 * Same markup and classes as the design's page heroes.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_args = wp_parse_args(
	$args,
	array(
		'eyebrow' => '',
		'title'   => '',
		'lead'    => '',
		'crumb'   => '',
		'image'   => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-38-scaled.jpg',
	)
);
?>
	<?php krm_hero_preload( $krm_args['image'] ); ?>
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( $krm_args['image'] ) ); ?>')<?php krm_bg_small( $krm_args['image'] ); ?>">
		<div class="wrap">
		<?php if ( krm_breadcrumbs() ) : ?>
		<?php krm_breadcrumb_html(); ?>
		<?php else : ?>
		<ol class="breadcrumb"><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li><li aria-current="page"><?php echo esc_html( $krm_args['crumb'] ? $krm_args['crumb'] : $krm_args['title'] ); ?></li></ol>
		<?php endif; ?>
		<?php if ( $krm_args['eyebrow'] ) : ?>
		<p class="eyebrow"><?php echo esc_html( $krm_args['eyebrow'] ); ?></p>
		<?php endif; ?>
		<h1><?php echo esc_html( $krm_args['title'] ); ?></h1>
		<?php if ( $krm_args['lead'] ) : ?>
		<p class="lead"><?php echo esc_html( $krm_args['lead'] ); ?></p>
		<?php endif; ?>
		</div>
	</section>
