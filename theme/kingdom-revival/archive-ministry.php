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
		<p class="eyebrow"><?php krm_t( 'overview.eyebrow', 'site' ); ?></p>
		<h1><?php krm_t( 'overview.heading', 'site' ); ?></h1>
		<p><?php krm_t( 'overview.text', 'site' ); ?></p>
		<a class="button button-primary" href="<?php krm_u( 'overview.button_url', 'site' ); ?>"><?php krm_t( 'overview.button_label', 'site' ); ?></a>
		</div>
	</div>
	</main>
<?php
get_footer();
