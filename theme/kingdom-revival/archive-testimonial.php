<?php
/**
 * Testimonials (/testimonials/).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="main">
	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'title' => krm_fill( krm_c( 'types.testimonials_heading', 'site' ) ),
			'lead'  => krm_fill( krm_c( 'types.testimonials_intro', 'site' ) ),
			'image' => krm_c( 'types.image', 'site' ),
		)
	);
	?>
	<section class="section" style="padding-top: clamp(48px, 6vw, 72px)">
		<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<?php
			global $wp_query;
			krm_testimonial_cards( $wp_query->posts );
			krm_pagination();
			?>
		<?php else : ?>
		<div class="empty"><strong><?php krm_t( 'types.testimonials_empty', 'site' ); ?></strong></div>
		<?php endif; ?>
		</div>
	</section>
	</main>
<?php
get_footer();
