<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase -- WordPress template hierarchy name (taxonomy-{taxonomy}.php).
/**
 * Sermons in one series (/sermons/series/{slug}/).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="main">
	<?php get_template_part( 'template-parts/type-archive', null, array( 'type' => 'sermon' ) ); ?>
	</main>
<?php
get_footer();
