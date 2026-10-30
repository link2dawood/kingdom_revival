<?php
/**
 * Search results in the News & Blogs style.
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
			'eyebrow' => 'Search',
			/* translators: %s: search terms */
			'title'   => sprintf( __( 'Results for “%s”', 'kingdom-revival' ), get_search_query() ),
			'crumb'   => __( 'Search', 'kingdom-revival' ),
		)
	);
	get_template_part( 'template-parts/post-list' );
	?>
	</main>
<?php
get_footer();
