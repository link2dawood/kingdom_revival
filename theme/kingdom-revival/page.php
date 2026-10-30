<?php
/**
 * Generic page (e.g. a privacy policy) in the design's page style.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<main id="main">
	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'title' => get_the_title(),
			'lead'  => has_excerpt() ? get_the_excerpt() : '',
			'image' => has_post_thumbnail() ? get_post_thumbnail_id() : 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-38-scaled.jpg',
		)
	);
	?>
	<section class="section">
		<div class="wrap">
		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
		</div>
	</section>
	</main>
	<?php
endwhile;

get_footer();
