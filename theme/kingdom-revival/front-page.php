<?php
/**
 * Home page — server-rendered from the approved home page design (no React). Each section is a part in
 * template-parts/home/.
 *
 * The donation amount picker and the floating donation panel are driven by assets/js/front.js. Sections that left the
 * home page in 1.23.0 (clean water, giving cards, current projects, freedom, flagship cards, ministry in action,
 * prayer request, founder) keep their template parts: freedom, cta,
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
// Theme 1.23.0: the client's section order (1. header and 18. footer are header.php / footer.php; 7. and 10. added in 1.25.0); 1.23.2 restored
// the mission slider (4), the ministry explorer (13) and the gift calculator (15).
get_template_part( 'template-parts/home/hero' );            // 2. Hero and donation card.
get_template_part( 'template-parts/home/scripture' );       // 2. Scripture rotator under the hero (1.24.0; replaced the impact numbers bar).
get_template_part( 'template-parts/home/about' );           // 3. Who We Are.
get_template_part( 'template-parts/home/mission' );         // 4. Taking the Gospel to the villages of Pakistan (photo slider).
get_template_part( 'template-parts/home/wwd' );             // 5. What We Do.
get_template_part( 'template-parts/home/needs' );           // 6. Why Pakistan Needs Hope.
get_template_part( 'template-parts/home/word' );            // 7. God's Word in their hands (Urdu Bibles photo wall, 1.25.0; after Why Pakistan Needs Hope since 1.25.1).
get_template_part( 'template-parts/home/baptism' );         // 7b. New life in Christ: baptism verse banner (1.26.0), the second half of the Urdu Bibles story.
get_template_part( 'template-parts/home/impact' );          // 8. Our Impact, with the Pakistan ministry map.
get_template_part( 'template-parts/home/featured' );        // 9. Featured Projects.
get_template_part( 'template-parts/home/kiln-freedom' );    // 10. Brick Kiln Family Freedom (before/after slider and steps, 1.25.0).
get_template_part( 'template-parts/home/stories' );         // 11. Stories from the Field.
get_template_part( 'template-parts/home/journey' );         // 12. Our Journey.
get_template_part( 'template-parts/home/explorer', null, array( 'ministries' => krm_ministries( 'home' ) ) ); // 13. Our Ministries 01–12.
get_template_part( 'template-parts/home/partner' );         // 14. Partner With Us.
get_template_part( 'template-parts/home/gift-calculator' ); // 15. Your gift at work (donation slider).
get_template_part( 'template-parts/home/news' );            // 16. Latest News & Updates.
get_template_part( 'template-parts/home/get-involved' );    // 16b. Pray / Give / Go (restored in 1.26.0, last section before the final call to action).
get_template_part( 'template-parts/home/final-cta' );       // 17. Final call to action.
get_template_part( 'template-parts/home/whatsapp' );
get_template_part( 'template-parts/home/floating-donate' );
?>
</main>
</div>

<?php
get_footer();
