<?php
/**
 * Home page — server-rendered from the approved home page design (no React). Each section is a part in
 * template-parts/home/.
 *
 * The ministry explorer, the donation amount picker and the floating donation panel are driven by
 * assets/js/front.js and start in the same state the design rendered.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header( 'home' );
?>
<main id="main">
<?php
get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/stats' );
get_template_part( 'template-parts/home/about' );
get_template_part( 'template-parts/home/explorer', null, array( 'ministries' => krm_ministries( 'home' ) ) );
get_template_part( 'template-parts/home/causes' );
get_template_part( 'template-parts/home/field-stories' );
get_template_part( 'template-parts/home/get-involved' );
get_template_part( 'template-parts/home/founder' );
get_template_part( 'template-parts/home/cta' );
get_template_part( 'template-parts/home/floating-donate' );
?>
</main>

<?php
get_footer( 'home' );
