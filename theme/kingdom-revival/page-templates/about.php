<?php
/**
 * Template Name: About
 * About page.
 *
 * Markup from about.html. The text, images and buttons are edited in the page's "Page content" box
 * (inc/content/about.php); the impact numbers come from Kingdom Revival → Settings.
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
		<div class="hero-actions">
			<a class="button button-primary" href="<?php krm_u( 'hero.button1_url' ); ?>"><?php krm_t( 'hero.button1_label' ); ?></a>
			<a class="button button-light" href="<?php krm_u( 'hero.button2_url' ); ?>"><?php krm_t( 'hero.button2_label' ); ?></a>
		</div>
		</div>
	</section>

	<section class="section">
		<div class="wrap">
		<div class="story">
			<div class="collage" data-gallery>
			<?php foreach ( array( 1, 2, 3 ) as $krm_n ) : ?>
			<img <?php krm_img_src( krm_c( "story.image{$krm_n}" ), '(max-width: 820px) 52vw, 300px' ); ?> alt="<?php echo krm_alt( krm_c( "story.image{$krm_n}" ), krm_c( "story.image{$krm_n}_alt" ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in krm_alt(). ?>" loading="lazy" decoding="async">
			<?php endforeach; ?>
			<div class="collage-badge"><strong><?php krm_t( 'story.badge_number' ); ?></strong><span><?php krm_t( 'story.badge_text' ); ?></span></div>
			</div>
			<div class="story-copy">
			<p class="eyebrow"><?php krm_t( 'story.eyebrow' ); ?></p>
			<h2><?php krm_h( 'story.heading' ); ?></h2>
			<?php echo krm_paras( krm_c( 'story.text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in krm_paras(). ?>
			<div class="hero-actions">
				<a class="button button-primary" href="<?php krm_u( 'story.button_url' ); ?>"><?php krm_t( 'story.button_label' ); ?></a>
			</div>
			</div>
		</div>
		<div class="mv-grid">
			<article class="mv-card">
			<p class="eyebrow"><?php krm_t( 'mission.mission_eyebrow' ); ?></p>
			<h3><?php krm_h( 'mission.mission_heading' ); ?></h3>
			<p><?php krm_t( 'mission.mission_text' ); ?></p>
			</article>
			<article class="mv-card vision">
			<p class="eyebrow"><?php krm_t( 'mission.vision_eyebrow' ); ?></p>
			<h3><?php krm_h( 'mission.vision_heading' ); ?></h3>
			<p><?php krm_t( 'mission.vision_text' ); ?></p>
			</article>
		</div>
		</div>
	</section>

	<section class="section section-soft">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow"><?php krm_t( 'work.eyebrow' ); ?></p>
			<h2><?php krm_h( 'work.heading' ); ?></h2>
			<p><?php krm_t( 'work.intro' ); ?></p>
		</div>
		<div class="work-grid">
			<?php
			$krm_icons = array( 'globe24', 'church24', 'people24', 'heart24' );
			foreach ( (array) krm_c( 'work.cards' ) as $krm_i => $krm_card ) :
				?>
			<article class="work-card">
			<span class="work-icon"><?php echo krm_icon( $krm_icons[ $krm_i % 4 ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme SVG file. ?></span>
			<h3><?php echo krm_text( $krm_card['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
			<p><?php echo krm_text( $krm_card['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			</article>
			<?php endforeach; ?>
		</div>
		</div>
	</section>

	<section class="stats-wrap" aria-label="Our impact">
		<div class="wrap">
		<div class="stats">
			<?php foreach ( krm_stats() as $krm_stat ) : ?>
			<div class="stat"><strong><?php echo esc_html( $krm_stat['value'] ); ?>+</strong><span><?php echo esc_html( $krm_stat['label'] ); ?></span></div>
			<?php endforeach; ?>
		</div>
		</div>
	</section>

	<section class="section" id="founder">
		<div class="wrap founder">
		<div class="founder-photo">
			<img <?php krm_img_src( krm_c( 'founder.image' ), '(max-width: 820px) 100vw, 510px' ); ?> alt="<?php echo krm_alt( krm_c( 'founder.image' ), krm_c( 'founder.image_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" data-zoom="founder" loading="lazy" decoding="async">
		</div>
		<div>
			<p class="eyebrow"><?php krm_t( 'founder.eyebrow' ); ?></p>
			<h2><?php krm_h( 'founder.heading' ); ?></h2>
			<p><?php krm_t( 'founder.text' ); ?></p>
			<blockquote><?php krm_t( 'founder.quote' ); ?><cite><?php krm_t( 'founder.cite' ); ?></cite></blockquote>
			<div class="hero-actions">
			<a class="button button-secondary" href="<?php krm_u( 'founder.button_url' ); ?>"><?php krm_t( 'founder.button_label' ); ?></a>
			</div>
		</div>
		</div>
	</section>

	<section class="section section-soft">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow"><?php krm_t( 'ways.eyebrow' ); ?></p>
			<h2><?php krm_h( 'ways.heading' ); ?></h2>
		</div>
		<div class="ways">
			<?php
			$krm_icons = array( 'pray24', 'heart24', 'globe24' );
			foreach ( (array) krm_c( 'ways.items' ) as $krm_i => $krm_way ) :
				?>
			<article class="way"><span class="way-num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $krm_i + 1 ) ); ?></span><span class="work-icon"><?php echo krm_icon( $krm_icons[ $krm_i % 3 ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><h3><?php echo krm_text( $krm_way['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><p><?php echo krm_text( $krm_way['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<?php
				if ( '' !== $krm_way['link_label'] ) :
					?>
				<a href="<?php echo krm_url( $krm_way['link_url'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php echo krm_text( $krm_way['link_label'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo krm_icon( 'arrow16' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a><?php endif; ?></article>
			<?php endforeach; ?>
		</div>
		</div>
	</section>

	<section class="section">
		<div class="wrap">
		<div class="section-head">
			<p class="eyebrow"><?php krm_t( 'gallery.eyebrow' ); ?></p>
			<h2><?php krm_h( 'gallery.heading' ); ?></h2>
		</div>
		<p class="gallery-tip"><?php echo krm_icon( 'zoom' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php krm_t( 'gallery.tip' ); ?></p>
		<div class="photo-grid" data-gallery>
			<?php foreach ( array_values( (array) krm_c( 'gallery.photos' ) ) as $krm_i => $krm_photo ) : ?>
			<img <?php krm_img_src( $krm_photo['image'], in_array( $krm_i, array( 0, 5 ), true ) ? '(max-width: 820px) 100vw, 600px' : '(max-width: 820px) 50vw, 300px' ); ?> alt="<?php echo krm_alt( $krm_photo['image'], $krm_photo['image_alt'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" loading="lazy" decoding="async">
			<?php endforeach; ?>
		</div>
		</div>
	</section>

	<section class="section" style="padding-top: 0">
		<div class="wrap">
		<?php get_template_part( 'template-parts/give-band', null, krm_give_band_args( 'give', '', array( 'form' => array( 'short' => true ) ) ) ); ?>
		</div>
	</section>
	</main>
<?php
get_footer();
