<?php
/**
 * News & Blogs (the posts page) — markup from blog.html, with every story rendered on the server.
 * The category chips and live search filter the rendered cards (assets/js/blog.js), as in the design.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $wp_query;
$krm_posts      = $wp_query->posts;
$krm_featured   = ( ! is_paged() && $krm_posts ) ? $krm_posts[0] : null;
$krm_rest       = $krm_featured ? array_slice( $krm_posts, 1 ) : $krm_posts;
$krm_stats      = krm_stats();
$krm_categories = array( 'All' );
foreach ( $krm_posts as $krm_p ) {
	$krm_c = krm_post_category( $krm_p->ID );
	if ( $krm_c && ! in_array( $krm_c, $krm_categories, true ) ) {
		$krm_categories[] = $krm_c;
	}
}
?>
	<main id="main">
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-38-scaled.jpg' ) ); ?>')">
		<div class="wrap">
		<ol class="breadcrumb"><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li><li aria-current="page">News &amp; Blogs</li></ol>
		<p class="eyebrow">News &amp; Blogs</p>
		<h1>Stories of hope <em>from the field</em></h1>
		<p class="lead">Real moments from the villages, kilns and churches we serve — and the people whose lives are being changed by the love of Jesus.</p>
		</div>
	</section>

	<section class="section" style="padding-top: clamp(48px, 6vw, 72px)">
		<div class="wrap">
		<div id="featured">
		<?php
		if ( $krm_featured ) {
			krm_featured_card( $krm_featured ); }
		?>
		</div>

		<div class="toolbar">
			<div>
			<p class="eyebrow">Latest stories</p>
			<h2>Read, pray and give</h2>
			</div>
			<label class="search">
			<span class="visually-hidden">Search stories</span>
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="M21 21l-4.3-4.3"></path></svg>
			<input type="search" id="search" placeholder="Search stories…">
			</label>
		</div>
		<div class="controls">
			<div class="filters" id="filters" role="group" aria-label="Filter by category">
			<?php
			foreach ( $krm_categories as $krm_cat ) :
				?>
				<button type="button" class="chip" data-filter="<?php echo esc_attr( $krm_cat ); ?>" aria-pressed="<?php echo 'All' === $krm_cat ? 'true' : 'false'; ?>"><?php echo esc_html( $krm_cat ); ?></button><?php endforeach; ?></div>
			<span class="post-meta" id="result-count" aria-live="polite"><?php echo esc_html( count( $krm_rest ) . ( 1 === count( $krm_rest ) ? ' story' : ' stories' ) ); ?></span>
		</div>
		<div class="post-grid" id="post-grid">
		<?php
		foreach ( $krm_posts as $krm_post ) {
			krm_post_card( $krm_post, krm_blog_card_attrs( $krm_post, $krm_featured ) ); }
		?>
		</div>
		<?php krm_pagination(); ?>
		<div class="empty" id="empty"<?php echo $krm_posts ? ' hidden' : ''; ?>><strong>No stories found</strong>Try a different word or category.</div>
		
		<div class="impact-strip">
			<div class="impact-item"><span class="impact-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"></path></svg></span><div><strong><?php echo esc_html( $krm_stats[0]['value'] ); ?>+</strong><span>communities reached</span></div></div>
			<div class="impact-item"><span class="impact-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg></span><div><strong><?php echo esc_html( $krm_stats[2]['value'] ); ?>+</strong><span>children supported</span></div></div>
			<div class="impact-item"><span class="impact-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15zM20 17v5H6.5A2.5 2.5 0 0 1 4 19.5"></path></svg></span><div><strong><?php echo esc_html( $krm_stats[3]['value'] ); ?>+</strong><span>Bibles distributed</span></div></div>
		</div>
		</div>
	</section>

	<section class="section" style="padding-top: 0">
		<div class="wrap">
		<?php
		get_template_part(
			'template-parts/give-band',
			null,
			array(
				'image'   => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-19.jpg',
				'eyebrow' => 'Be part of the next story',
				'heading' => 'Every story here began with someone who gave',
				'text'    => 'Your generosity puts Bibles in hands, children in classrooms and hope in homes across Pakistan.',
				'points'  => array( 'Bibles for families and new believers', 'Education and care for children', 'Practical help for widows and the vulnerable' ),
				'form'    => array(
					'short'   => true,
					'heading' => 'Give to the mission',
				),
			)
		);
		?>
		</div>
	</section>
	</main>
<?php
get_footer();
