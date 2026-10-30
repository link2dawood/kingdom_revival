<?php
/**
 * Grid of results for archives and search, using the story card from News & Blogs.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
	<section class="section" style="padding-top: clamp(48px, 6vw, 72px)">
		<div class="wrap">
		<?php if ( have_posts() ) : ?>
		<div class="post-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				if ( 'post' === get_post_type() ) {
					krm_post_card( get_post() );
				} else {
					?>
			<a class="post-card" href="<?php the_permalink(); ?>"><div class="post-card-body"><h3><?php the_title(); ?></h3><p><?php echo esc_html( get_the_excerpt() ); ?></p><span class="read-more">Read more <?php echo krm_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></div></a>
					<?php
				}
			endwhile;
			?>
		</div>
			<?php krm_pagination(); ?>
		<?php else : ?>
		<div class="empty"><strong>No stories found</strong>Try a different word or category.</div>
		<?php endif; ?>
		</div>
	</section>
