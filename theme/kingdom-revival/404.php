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
		<p class="eyebrow"><?php krm_t( 'notfound.eyebrow', 'site' ); ?></p>
		<h1><?php krm_t( 'notfound.heading', 'site' ); ?></h1>
		<p><?php krm_t( 'notfound.text', 'site' ); ?></p>
		<div class="hero-actions" style="justify-content: center">
		<a class="button button-primary" href="<?php krm_u( 'notfound.button1_url', 'site' ); ?>"><?php krm_t( 'notfound.button1_label', 'site' ); ?></a>
		<a class="button button-secondary" href="<?php krm_u( 'notfound.button2_url', 'site' ); ?>"><?php krm_t( 'notfound.button2_label', 'site' ); ?></a>
		</div>
	</section>
	</main>
<?php
get_footer();
