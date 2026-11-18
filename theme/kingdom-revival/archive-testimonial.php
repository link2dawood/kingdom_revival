<?php
/**
 * Testimonials (/testimonials/).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
global $wp_query;
$krm_testimonials = krm_public_testimonials( $wp_query->posts );
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
		<?php if ( $krm_testimonials ) : ?>
			<?php
			krm_testimonial_cards( $krm_testimonials );
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
