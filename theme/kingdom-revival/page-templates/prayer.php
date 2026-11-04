<?php
/**
 * Template Name: Prayer Request
 * Prayer Request (/partner-with-us/prayer-request/): the inner-page hero and the home page's prayer request form
 * (template-parts/home/prayer.php, text from Pages → Home → Page content → Prayer request).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="main">
	<?php krm_hero_preload( krm_c( 'hero.image' ) ); ?>
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( krm_c( 'hero.image' ) ) ); ?>')<?php krm_bg_small( krm_c( 'hero.image' ) ); ?>">
		<div class="wrap">
		<?php krm_breadcrumb_html(); ?>
		<p class="eyebrow"><?php krm_t( 'hero.eyebrow' ); ?></p>
		<h1><?php krm_h( 'hero.title' ); ?></h1>
		<p class="lead"><?php krm_t( 'hero.lead' ); ?></p>
		</div>
	</section>
	<div class="krm-design" style="padding-top: clamp(56px, 7vw, 88px)">
		<?php
		get_template_part(
			'template-parts/home/prayer',
			null,
			array(
				'screen' => 'home',
				'id'     => 'request',
			)
		);
		?>
	</div>
	</main>
<?php
get_footer();
