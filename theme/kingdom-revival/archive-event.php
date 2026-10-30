<?php
/**
 * Events list (/events/).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="main">
	<?php get_template_part( 'template-parts/type-archive', null, array( 'type' => 'event' ) ); ?>
	</main>
<?php
get_footer();
