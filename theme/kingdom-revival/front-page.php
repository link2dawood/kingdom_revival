<?php
/**
 * Home page — server-rendered from the approved home page design (no React). Each section is a part in
 * template-parts/home/.
 *
 * The donation amount picker and the floating donation panel are driven by assets/js/front.js. Sections that left the
 * home page in 1.23.0 (clean water, giving cards, current projects, freedom, flagship cards, ministry in action,
 * Pray/Give/Go, prayer request, founder) keep their template parts: freedom, cta,
 * gift and prayer are still used on other pages.
 *
 * The header and footer are the global ones (header.php, footer.php). The wrapper below carries the base type and
 * colour the home page design set on its sections.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="krm-home" style="font-family: var(--font-body); color: var(--color-text); background: var(--color-bg); font-size: 16px; line-height: 1.6">
<main id="main">
<?php
// Theme 1.23.0: the client's section order (1. header and 16. footer are header.php / footer.php); 1.23.2 restored
// the mission slider (4), the ministry explorer (11) and the gift calculator (13).
get_template_part( 'template-parts/home/hero' );            // 2. Hero, donation card and the impact numbers bar.
get_template_part( 'template-parts/home/stats' );
get_template_part( 'template-parts/home/about' );           // 3. Who We Are.
get_template_part( 'template-parts/home/mission' );         // 4. Taking the Gospel to the villages of Pakistan (photo slider).
get_template_part( 'template-parts/home/wwd' );             // 5. What We Do.
get_template_part( 'template-parts/home/needs' );           // 6. Why Pakistan Needs Hope.
get_template_part( 'template-parts/home/impact' );          // 7. Our Impact, with the Pakistan ministry map.
get_template_part( 'template-parts/home/featured' );        // 8. Featured Projects.
get_template_part( 'template-parts/home/stories' );         // 9. Stories from the Field.
get_template_part( 'template-parts/home/journey' );         // 10. Our Journey.
get_template_part( 'template-parts/home/explorer', null, array( 'ministries' => krm_ministries( 'home' ) ) ); // 11. Our Ministries 01–12.
get_template_part( 'template-parts/home/partner' );         // 12. Partner With Us.
get_template_part( 'template-parts/home/gift-calculator' ); // 13. Your gift at work (donation slider).
get_template_part( 'template-parts/home/news' );            // 14. Latest News & Updates.
get_template_part( 'template-parts/home/final-cta' );       // 15. Final call to action.
get_template_part( 'template-parts/home/whatsapp' );
get_template_part( 'template-parts/home/floating-donate' );
?>
</main>
</div>

<?php
get_footer();
