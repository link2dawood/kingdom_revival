<?php
/**
 * A single event (/events/{slug}/).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/type-single' );
endwhile;

get_footer();
