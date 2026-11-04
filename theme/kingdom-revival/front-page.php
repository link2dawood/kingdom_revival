<?php
/**
 * Home page — server-rendered from the approved home page design (no React). Each section is a part in
 * template-parts/home/.
 *
 * The ministry explorer, the donation amount picker and the floating donation panel are driven by
 * assets/js/front.js and start in the same state the design rendered.
 *
 * The header and footer are the global ones (header.php, footer.php). The wrapper below carries the base type and
 * colour the home page design set on its sections.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="krm-home" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: var(--color-text); background: var(--color-white); font-size: 16px; line-height: 1.6">
<main id="main">
<?php
get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/stats' );
get_template_part( 'template-parts/home/gift' );
get_template_part( 'template-parts/home/about' );
get_template_part( 'template-parts/home/explorer', null, array( 'ministries' => krm_ministries( 'home' ) ) );
get_template_part( 'template-parts/home/causes' );
get_template_part( 'template-parts/home/projects' );
get_template_part( 'template-parts/home/freedom' );
get_template_part( 'template-parts/home/flagship' );
get_template_part( 'template-parts/home/where' );
get_template_part( 'template-parts/home/field-stories' );
get_template_part( 'template-parts/home/stories' );
get_template_part( 'template-parts/home/get-involved' );
get_template_part( 'template-parts/home/prayer' );
get_template_part( 'template-parts/home/founder' );
get_template_part( 'template-parts/home/beliefs' );
get_template_part( 'template-parts/home/news' );
get_template_part( 'template-parts/home/cta' );
get_template_part( 'template-parts/home/whatsapp' );
get_template_part( 'template-parts/home/floating-donate' );
?>
</main>
</div>

<?php
get_footer();
