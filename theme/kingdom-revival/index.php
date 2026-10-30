<?php
/**
 * Fallback template.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="main">
	<?php
	get_template_part( 'template-parts/page-hero', null, array( 'title' => wp_strip_all_tags( get_the_archive_title() ) ) );
	get_template_part( 'template-parts/post-list' );
	?>
	</main>
<?php
get_footer();
