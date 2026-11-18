<?php
/**
 * Ministry page 6 (Persecuted Christian Support only): latest posts from the Persecution Reports category instead of
 * a testimonial. Shows 3 cards with title, date, location and "Read more", plus a link to the full category archive.
 *
 * @param array $args { ministry }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m = $args['ministry'];

// Get the Persecution Reports category.
$krm_cat = get_category_by_slug( 'persecution-reports' );
if ( ! $krm_cat ) {
	$krm_cat = get_term_by( 'name', 'Persecution Reports', 'category' );
}
if ( ! $krm_cat ) {
	return; // Category not found.
}

// Query the 3 latest posts from this category.
$krm_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'cat'            => $krm_cat->term_id,
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

if ( ! $krm_query->have_posts() ) {
	return; // No posts found.
}

$krm_archive_url = get_category_link( $krm_cat->term_id );
?>
<section class="ministry-story ministry-reports" aria-labelledby="reports-heading">
	<div class="ministry-head">
		<div>
			<p class="ministry-kicker">From the news</p>
			<h2 id="reports-heading">Persecution Reports</h2>
		</div>
		<p><a href="<?php echo esc_url( $krm_archive_url ); ?>">View all reports</a></p>
	</div>

	<div class="reports-grid" data-reveal-stagger>
		<?php
		while ( $krm_query->have_posts() ) :
			$krm_query->the_post();
			$krm_cover = krm_post_cover( get_the_ID() );
			$krm_location = '';
			// Try to extract location from the post content (the first .post-location paragraph).
			$krm_content = get_the_content();
			if ( preg_match( '/<p class="post-location"[^>]*><strong>Location:<\/strong>\s*([^<]+)<\/p>/i', $krm_content, $krm_match ) ) {
				$krm_location = trim( $krm_match[1] );
			}
			?>
		<a class="report-card" href="<?php the_permalink(); ?>">
			<?php if ( $krm_cover ) : ?>
			<div class="report-card-media"><img <?php krm_img_src( $krm_cover, '(max-width: 560px) 100vw, (max-width: 900px) 50vw, 360px', true ); ?> alt="<?php echo esc_attr( krm_cover_alt( get_the_ID() ) ); ?>" loading="lazy" decoding="async"></div>
			<?php endif; ?>
			<div class="report-card-body">
				<h3><?php the_title(); ?></h3>
				<p class="report-card-meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></time>
					<?php if ( '' !== $krm_location ) : ?>
					<span class="report-card-location"><?php echo esc_html( $krm_location ); ?></span>
					<?php endif; ?>
				</p>
				<span class="read-more">Read more <?php echo krm_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</div>
		</a>
			<?php
		endwhile;
		wp_reset_postdata();
		?>
	</div>
</section>
