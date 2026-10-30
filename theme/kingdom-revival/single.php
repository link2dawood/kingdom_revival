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
		<section class="post-hero" id="post-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( $krm_cover ) ); ?>')">
		<div class="wrap">
			<ol class="breadcrumb"><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li><li><a href="<?php echo esc_url( krm_blog_url() ); ?>">News &amp; Blogs</a></li><li aria-current="page" id="crumb-title"><?php echo esc_html( $krm_title ); ?></li></ol>
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
				<strong>Share this story</strong>
				<a id="share-whatsapp" href="<?php echo esc_url( 'https://wa.me/?text=' . $krm_text . '%20' . $krm_share ); ?>" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.1-5.4A8.4 8.4 0 1 1 21 11.5z"></path></svg></a>
				<a id="share-facebook" href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $krm_share ); ?>" target="_blank" rel="noopener" aria-label="Share on Facebook"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
				<a id="share-x" href="<?php echo esc_url( 'https://twitter.com/intent/tweet?text=' . $krm_text . '&url=' . $krm_share ); ?>" target="_blank" rel="noopener" aria-label="Share on X"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4l16 16M20 4L4 20"></path></svg></a>
				<button type="button" id="share-copy" aria-label="Copy link" data-url="<?php echo esc_url( $krm_url ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7"></path><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7"></path></svg></button>
				<span class="copied" id="copied" role="status" hidden>Link copied!</span>
			</div>
			<a class="button button-secondary" href="<?php echo esc_url( krm_blog_url() ); ?>">← All stories</a>
			</div>
			<div class="author">
			<img src="<?php echo esc_url( krm_logo_url() ); ?>" alt="" width="60" height="60" loading="lazy">
			<div><strong>Kingdom Revival Ministries Team</strong><span>Sharing stories from the villages, kilns and churches we serve in Pakistan.</span></div>
			</div>
			<nav class="post-nav" id="post-nav" aria-label="More stories">
			<?php
			$krm_older = get_previous_post();
			$krm_newer = get_next_post();
			if ( $krm_older ) :
				?>
<a href="<?php echo esc_url( get_permalink( $krm_older ) ); ?>"><small>← Previous story</small><strong><?php echo esc_html( get_the_title( $krm_older ) ); ?></strong></a>
				<?php
else :
	?>
				<span></span><?php endif; ?>
				<?php
				if ( $krm_newer ) :
					?>
				<a class="next" href="<?php echo esc_url( get_permalink( $krm_newer ) ); ?>"><small>Next story →</small><strong><?php echo esc_html( get_the_title( $krm_newer ) ); ?></strong></a><?php endif; ?>
			</nav>
		</article>

		<aside class="sidebar" aria-label="Support this ministry">
			<form class="give-card" id="give-side" data-short="true" data-give="<?php echo esc_attr( $krm_ministry['title'] ); ?>" data-heading="<?php echo esc_attr( 'Support ' . $krm_ministry['title'] ); ?>" data-intro="Your gift helps this story continue."></form>
			<a class="ministry-card" id="ministry-card" href="<?php echo esc_url( $krm_ministry['url'] ); ?>">
			<img id="ministry-card-img" <?php krm_img_src( $krm_cover, '120px' ); ?> alt="" loading="lazy">
			<span><small>About this ministry</small><strong id="ministry-card-name"><?php echo esc_html( $krm_ministry['title'] ); ?></strong><em>Learn more →</em></span>
			</a>
			<a class="ministry-card" href="<?php echo esc_url( krm_page_url( 'contact' ) ); ?>">
			<img src="<?php echo esc_url( krm_logo_url() ); ?>" alt="" style="object-fit: contain; background: var(--soft); padding: 6px" loading="lazy">
			<span><small>Pray with us</small><strong>Send a prayer request</strong><em>Contact us →</em></span>
			</a>
		</aside>
		</div>

		<div class="wrap" style="padding-bottom: clamp(56px, 7vw, 88px)">
		<?php
		get_template_part(
			'template-parts/give-band',
			null,
			array(
				'heading_id' => 'give-main-heading',
				'image'      => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-19.jpg',
				'eyebrow'    => 'Be part of the story',
				'heading'    => 'Stand with ' . $krm_ministry['title'],
				'text'       => 'Every gift — one-time or monthly — goes to the work you have just read about, bringing the hope of Jesus and practical care to families who need it most.',
				'points'     => array( 'Choose any amount, one-time or monthly', 'Your gift goes to this ministry’s work in Pakistan', 'You will be part of the next story we share' ),
				'form'       => array(
					'id'   => 'give-main',
					'give' => $krm_ministry['title'],
				),
			)
		);
		?>
		</div>

		<?php $krm_related = krm_related_posts( $krm_id ); ?>
		<?php if ( $krm_related ) : ?>
		<section class="related">
		<div class="wrap">
			<div class="related-head">
			<div><p class="eyebrow">Keep reading</p><h2>More stories of hope</h2></div>
			<a class="button button-secondary" href="<?php echo esc_url( krm_blog_url() ); ?>">View all stories</a>
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
