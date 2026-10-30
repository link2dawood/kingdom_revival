<?php
/**
 * /ministries/ — the design's "Choose a ministry to explore" state of all-ministries.html.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="main">
	<div class="wrap section" id="not-found">
		<div class="not-found">
		<p class="eyebrow">Ministry details</p>
		<h1>Choose a ministry to explore</h1>
		<p>Pick a ministry from the menu above, or browse them all on the home page.</p>
		<a class="button button-primary" href="<?php echo esc_url( home_url( '/#all-ministries' ) ); ?>">Browse ministries</a>
		</div>
	</div>
	</main>
<?php
get_footer();
