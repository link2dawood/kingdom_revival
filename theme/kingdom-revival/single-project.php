<?php
/**
 * Project detail page (/projects/{slug}/) — one template for every project, filled from one data object
 * (krm_current_project_page(), inc/data/project-pages.php). Styles: assets/css/pages/project.css.
 *
 *  1. Hero: breadcrumb, ministry eyebrow, H1 (with .hl), intro, Give (this ministry preselected on Donate) and
 *     Contact buttons, the project card's photo.
 *  2. The need: text beside a photo (photo on the opposite side to the hero's; stacked on mobile).
 *  3. How it works: three numbered cards.
 *  4. Photo gallery (viewer.js), only photos not already on the page.
 *  5. Scripture (the home mission verse style).
 *  6. Closing CTA: heading, Give, "See all projects".
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_pp = krm_current_project_page();

// The matching project card (its photo is the hero photo, so card and page always show the same picture).
$krm_card = null;
foreach ( krm_projects() as $krm_proj ) {
	if ( $krm_proj['page'] === $krm_pp['key'] ) {
		$krm_card = $krm_proj;
		break;
	}
}
$krm_hero     = ( $krm_card && $krm_card['image'] ) ? $krm_card['image'] : $krm_pp['image'];
$krm_give_url = krm_ministry_donate_url( $krm_pp['ministry'] );
$krm_all_url  = krm_donate_url() . '#projects';
$krm_plain    = wp_strip_all_tags( $krm_pp['title'] );

// No photo twice on the page: drop gallery photos that are the hero or the need photo.
$krm_seen    = array( krm_project_ref_key( $krm_hero ), krm_project_ref_key( $krm_pp['need_image'][0] ) );
$krm_gallery = array();
foreach ( $krm_pp['gallery'] as $krm_photo ) {
	$krm_k = krm_project_ref_key( $krm_photo[0] );
	if ( ! in_array( $krm_k, $krm_seen, true ) ) {
		$krm_seen[]    = $krm_k;
		$krm_gallery[] = $krm_photo;
	}
}

get_header();
?>
<main id="main" class="project-page">

<section class="page-hero project-hero" aria-labelledby="project-title">
<div class="wrap project-hero-grid">
	<div class="project-hero-text">
		<?php krm_breadcrumb_html( 'crumb-title' ); ?>
		<p class="eyebrow"><?php echo esc_html( $krm_pp['eyebrow'] ); ?></p>
		<h1 id="project-title"><?php echo krm_inline( $krm_pp['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- krm_inline() escapes. ?></h1>
		<p class="lead"><?php echo esc_html( $krm_pp['intro'] ); ?></p>
		<div class="hero-actions">
			<a class="button button-primary" href="<?php echo esc_url( $krm_give_url ); ?>"><?php echo krm_icon( 'heart18' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php krm_t( 'projects.give_label', 'donate' ); ?></a>
			<a class="button button-light" href="<?php echo esc_url( krm_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Contact us', 'kingdom-revival' ); ?></a>
		</div>
	</div>
	<figure class="project-hero-media">
		<img <?php krm_img_src( $krm_hero, '(max-width: 900px) calc(100vw - 32px), 520px', true ); ?> alt="<?php echo esc_attr( $krm_pp['image_alt'] ); ?>" width="520" height="390" fetchpriority="high" decoding="async">
	</figure>
</div>
</section>

<section class="section project-need" aria-labelledby="project-need-h">
<div class="wrap project-split">
	<div class="project-split-text">
		<p class="eyebrow"><?php echo esc_html( $krm_pp['eyebrow'] ); ?></p>
		<h2 id="project-need-h"><?php esc_html_e( 'The need', 'kingdom-revival' ); ?></h2>
		<p><?php echo esc_html( $krm_pp['need'] ); ?></p>
	</div>
	<figure class="project-split-media">
		<img <?php krm_img_src( $krm_pp['need_image'][0], '(max-width: 900px) calc(100vw - 32px), 560px', true ); ?> alt="<?php echo esc_attr( $krm_pp['need_image'][1] ); ?>" width="560" height="420" loading="lazy" decoding="async">
	</figure>
</div>
</section>

<section class="section section-soft project-how" aria-labelledby="project-how-h">
<div class="wrap">
	<div class="section-head">
		<h2 id="project-how-h"><?php esc_html_e( 'How it works', 'kingdom-revival' ); ?></h2>
	</div>
	<ol class="project-steps">
		<?php foreach ( $krm_pp['steps'] as $krm_i => $krm_step ) : ?>
		<li class="project-step">
			<span class="project-step-num" aria-hidden="true"><?php echo (int) $krm_i + 1; ?></span>
			<h3><?php echo esc_html( $krm_step[0] ); ?></h3>
			<p><?php echo esc_html( $krm_step[1] ); ?></p>
		</li>
		<?php endforeach; ?>
	</ol>
</div>
</section>

<?php if ( $krm_gallery ) : ?>
<section class="section project-photos" aria-labelledby="project-photos-h">
<div class="wrap">
	<div class="section-head">
		<h2 id="project-photos-h"><?php esc_html_e( 'Photo gallery', 'kingdom-revival' ); ?></h2>
	</div>
	<div class="gallery krm-just" data-gallery>
		<?php foreach ( $krm_gallery as $krm_photo ) : ?>
		<figure class="photo"<?php echo krm_ratio_style( $krm_photo[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><img <?php krm_img_src( $krm_photo[0], '(max-width: 640px) 70vw, (max-width: 1100px) 45vw, 520px' ); ?> alt="<?php echo esc_attr( $krm_photo[1] ); ?>" loading="lazy" decoding="async"></figure>
		<?php endforeach; ?>
	</div>
</div>
</section>
<?php endif; ?>

<section class="project-verse" aria-label="<?php esc_attr_e( 'Scripture', 'kingdom-revival' ); ?>">
<div class="wrap">
	<blockquote class="krm-mission__verse">
		<p>“<?php echo esc_html( $krm_pp['verse'] ); ?>”</p>
		<cite><?php echo esc_html( $krm_pp['verse_cite'] ); ?></cite>
	</blockquote>
</div>
</section>

<section class="cta-banner" aria-labelledby="project-cta-h"><div><div class="cta-banner-text"><h2 id="project-cta-h"><?php echo esc_html( $krm_pp['cta'] ); ?></h2></div><div class="cta-banner-actions"><a class="button button-primary" href="<?php echo esc_url( $krm_give_url ); ?>"><?php krm_t( 'projects.give_label', 'donate' ); ?><span class="visually-hidden">: <?php echo esc_html( $krm_plain ); ?></span></a><a class="button button-light" href="<?php echo esc_url( $krm_all_url ); ?>"><?php esc_html_e( 'See all projects', 'kingdom-revival' ); ?></a></div></div></section>

</main>
<?php
get_footer();
