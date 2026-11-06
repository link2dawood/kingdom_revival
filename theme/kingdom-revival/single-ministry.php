<?php
/**
 * Single ministry (/what-we-do/{slug}/) — one template for every ministry, filled from krm_ministries() (the ministry
 * post, falling back to inc/data/ministries.php).
 *
 * Sections (template-parts/ministry/*):
 *  1. Inner hero: first photo, breadcrumb, group eyebrow, H1, the Excerpt.
 *  2. Intro: overlapping photo pair (the home About section), "What we do", the ministry's points and focus text,
 *     then any extra sections (e.g. Sunday School inside Children & Orphan Care).
 *  2b. The ministry in action: its latest story (opening paragraphs, link) and three photos with their descriptions.
 *  3. Impact counters (the home stats bar).  4. How your gift helps (the home "Your gift at work" cards).
 *  5. Photos with the lightbox (up to 9).    6. One testimony (the home "Stories from the field" card).
 *  7. Side donation card (the home hero card), sticky beside 2–6, giving to this ministry.
 *  Special layouts: the freedom steps and before/after slider ('layout' => 'freedom'), churches planted and the
 *  camps / projects timeline (each shown only when it has rows).
 *  8. Other ministries of the same group.    9. CTA banner (the home page's).
 *  A "coming soon" ministry (Phase 22, krm_ministry_soon()) shows only 1 (with Donate / Give and Contact us) and 8.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$krm_m      = krm_current_ministry();
	$krm_photos = krm_ministry_photos( $krm_m );
	$krm_args   = array(
		'ministry' => $krm_m,
		'photos'   => $krm_photos,
	);
	?>
	<main id="main">
	<div id="ministry-content">
		<?php get_template_part( 'template-parts/ministry/hero', null, $krm_args ); ?>

		<?php if ( ! empty( $krm_m['soon'] ) ) : ?>
			<?php get_template_part( 'template-parts/ministry/others', null, $krm_args ); ?>
		<?php else : ?>
		<div class="wrap ministry-layout">
			<div class="ministry-main">
				<?php
				foreach ( array( 'intro', 'impact', 'stats', 'gifts', 'gallery', 'story' ) as $krm_part ) {
					get_template_part( 'template-parts/ministry/' . $krm_part, null, $krm_args );
				}
				?>
			</div>
			<aside class="ministry-aside" aria-labelledby="support-heading">
				<?php get_template_part( 'template-parts/ministry/give-card', null, $krm_args ); ?>
			</aside>
		</div>

		<?php if ( 'freedom' === $krm_m['layout'] ) : ?>
		<div class="krm-design ministry-freedom">
			<?php get_template_part( 'template-parts/home/freedom', null, array( 'screen' => 'home' ) ); ?>
		</div>
		<?php endif; ?>

		<?php
		get_template_part( 'template-parts/ministry/churches', null, $krm_args );
		get_template_part( 'template-parts/ministry/camps', null, $krm_args );
		get_template_part( 'template-parts/ministry/others', null, $krm_args );
		?>

		<div class="krm-design">
			<?php get_template_part( 'template-parts/home/cta', null, array( 'screen' => 'home' ) ); ?>
		</div>
		<?php endif; ?>
	</div>
	</main>
	<?php
endwhile;

get_footer();
