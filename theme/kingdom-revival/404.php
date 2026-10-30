<?php
/**
 * Not found — uses the design's "not found" block.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="main">
	<section class="wrap not-found">
		<p class="eyebrow">Page not found</p>
		<h1>We couldn’t find that page</h1>
		<p>It may have moved. Explore our ministries or read the latest stories from the field.</p>
		<div class="hero-actions" style="justify-content: center">
		<a class="button button-primary" href="<?php echo esc_url( home_url( '/#all-ministries' ) ); ?>">Browse ministries</a>
		<a class="button button-secondary" href="<?php echo esc_url( krm_blog_url() ); ?>">See all stories</a>
		</div>
	</section>
	</main>
<?php
get_footer();
