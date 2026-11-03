<?php
/**
 * Single story — markup from blog-post.html, rendered on the server.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$krm_id       = get_the_ID();
	$krm_title    = get_the_title();
	$krm_url      = get_permalink();
	$krm_cover    = krm_post_cover( $krm_id );
	$krm_category = krm_post_category( $krm_id );
	$krm_ministry = krm_post_ministry( $krm_id );
	$krm_share    = rawurlencode( $krm_url );
	$krm_text     = rawurlencode( wp_strip_all_tags( $krm_title ) );
	?>
	<main id="main">
	<div id="post-page">
		<?php krm_hero_preload( $krm_cover ); ?>
		<section class="post-hero" id="post-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( $krm_cover ) ); ?>')<?php krm_bg_small( $krm_cover ); ?>">
		<div class="wrap">
			<?php krm_breadcrumb_html( 'crumb-title' ); ?>
			<span class="tag" id="post-tag"><?php echo esc_html( $krm_category ); ?></span>
			<h1 id="post-title"><?php echo esc_html( $krm_title ); ?></h1>
			<div class="post-meta" id="post-meta"><span><?php echo krm_icon( 'cal' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></time></span><span><?php echo krm_icon( 'clock' ) . esc_html( krm_read_time( $krm_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> min read</span><span>Ministry: <a href="<?php echo esc_url( $krm_ministry['url'] ); ?>"><?php echo esc_html( $krm_ministry['title'] ); ?></a></span></div>
		</div>
		</section>

		<div class="wrap post-layout">
		<article class="article" id="article">
			<div id="article-body" data-gallery><?php the_content(); ?></div>
			<div class="article-footer">
			<div class="share">
				<strong><?php krm_t( 'story.share', 'site' ); ?></strong>
				<a id="share-whatsapp" href="<?php echo esc_url( 'https://wa.me/?text=' . $krm_text . '%20' . $krm_share ); ?>" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.1-5.4A8.4 8.4 0 1 1 21 11.5z"></path></svg></a>
				<a id="share-facebook" href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $krm_share ); ?>" target="_blank" rel="noopener" aria-label="Share on Facebook"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
				<a id="share-x" href="<?php echo esc_url( 'https://twitter.com/intent/tweet?text=' . $krm_text . '&url=' . $krm_share ); ?>" target="_blank" rel="noopener" aria-label="Share on X"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4l16 16M20 4L4 20"></path></svg></a>
				<button type="button" id="share-copy" aria-label="Copy link" data-url="<?php echo esc_url( $krm_url ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7"></path><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7"></path></svg></button>
				<span class="copied" id="copied" role="status" hidden>Link copied!</span>
			</div>
			<a class="button button-secondary" href="<?php echo esc_url( krm_blog_url() ); ?>"><?php krm_t( 'story.back', 'site' ); ?></a>
			</div>
			<div class="author">
			<?php krm_logo_img( 60, '60px', '', ' loading="lazy"' ); ?>
			<div><strong><?php krm_t( 'story.author_name', 'site' ); ?></strong><span><?php krm_t( 'story.author_text', 'site' ); ?></span></div>
			</div>
			<nav class="post-nav" id="post-nav" aria-label="More stories">
			<?php
			$krm_older = get_previous_post();
			$krm_newer = get_next_post();
			if ( $krm_older ) :
				?>
<a href="<?php echo esc_url( get_permalink( $krm_older ) ); ?>"><small><?php krm_t( 'story.previous', 'site' ); ?></small><strong><?php echo esc_html( get_the_title( $krm_older ) ); ?></strong></a>
				<?php
else :
	?>
				<span></span><?php endif; ?>
				<?php
				if ( $krm_newer ) :
					?>
				<a class="next" href="<?php echo esc_url( get_permalink( $krm_newer ) ); ?>"><small><?php krm_t( 'story.next', 'site' ); ?></small><strong><?php echo esc_html( get_the_title( $krm_newer ) ); ?></strong></a><?php endif; ?>
			</nav>
		</article>

		<aside class="sidebar" aria-label="Support this ministry">
			<form class="give-card" id="give-side" data-short="true" data-give="<?php echo esc_attr( $krm_ministry['title'] ); ?>" data-heading="<?php echo esc_attr( 'Support ' . $krm_ministry['title'] ); ?>" data-intro="<?php echo esc_attr( krm_fill( krm_c( 'story.side_give_intro', 'site' ) ) ); ?>"></form>
			<a class="ministry-card" id="ministry-card" href="<?php echo esc_url( $krm_ministry['url'] ); ?>">
			<img id="ministry-card-img" <?php krm_img_src( $krm_cover, '120px' ); ?> alt="" loading="lazy">
			<span><small><?php krm_t( 'story.side_ministry', 'site' ); ?></small><strong id="ministry-card-name"><?php echo esc_html( $krm_ministry['title'] ); ?></strong><em><?php krm_t( 'story.side_more', 'site' ); ?></em></span>
			</a>
			<a class="ministry-card" href="<?php krm_u( 'story.side_pray_url', 'site' ); ?>">
			<img src="<?php echo esc_url( krm_logo_url() ); ?>" alt="" style="object-fit: contain; background: var(--soft); padding: 6px" loading="lazy">
			<span><small><?php krm_t( 'story.side_pray_label', 'site' ); ?></small><strong><?php krm_t( 'story.side_pray_title', 'site' ); ?></strong><em><?php krm_t( 'story.side_pray_link', 'site' ); ?></em></span>
			</a>
		</aside>
		</div>

		<div class="wrap" style="padding-bottom: clamp(56px, 7vw, 88px)">
		<?php
		get_template_part(
			'template-parts/give-band',
			null,
			krm_site_give_band_args(
				'story_give',
				$krm_ministry['title'],
				array(
					'heading_id' => 'give-main-heading',
					'form'       => array(
						'id'   => 'give-main',
						'give' => $krm_ministry['title'],
					),
				)
			)
		);
		?>
		</div>

		<?php $krm_related = krm_related_posts( $krm_id ); ?>
		<?php if ( $krm_related ) : ?>
		<section class="related">
		<div class="wrap">
			<div class="related-head">
			<div><p class="eyebrow"><?php krm_t( 'story.related_eyebrow', 'site' ); ?></p><h2><?php krm_t( 'story.related_heading', 'site' ); ?></h2></div>
			<a class="button button-secondary" href="<?php echo esc_url( krm_blog_url() ); ?>"><?php krm_t( 'story.related_button', 'site' ); ?></a>
			</div>
			<div class="post-grid" id="related-grid">
			<?php
			foreach ( $krm_related as $krm_post ) {
				krm_post_card( $krm_post ); }
			?>
			</div>
		</div>
		</section>
		<?php endif; ?>
	</div>
	</main>
	<?php
endwhile;

get_footer();
