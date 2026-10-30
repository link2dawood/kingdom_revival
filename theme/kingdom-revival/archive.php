<?php
/**
 * Story archives (categories, tags, dates) in the News & Blogs style.
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
			'eyebrow' => 'News & Blogs',
			'title'   => wp_strip_all_tags( get_the_archive_title() ),
			'lead'    => wp_strip_all_tags( get_the_archive_description() ),
		)
	);
	get_template_part( 'template-parts/post-list' );
	?>
	</main>
<?php
get_footer();
