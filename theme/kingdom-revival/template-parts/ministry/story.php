<?php
/**
 * Ministry page 6: one testimony in the home "Stories from the field" card — the first Testimonial linked to this
 * ministry. Without one, visitors see nothing and administrators a marked placeholder.
 *
 * @param array $args { ministry }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m    = $args['ministry'];
$krm_post = krm_ministry_testimonial( $krm_m );
if ( ! $krm_post && ! krm_show_placeholders() ) {
	return; // Visitors: no testimony yet.
}
?>
<section class="ministry-story" aria-labelledby="story-heading">
	<?php krm_admin_only_note( ! $krm_post ); ?>
	<div class="ministry-head">
		<div>
			<p class="ministry-kicker"><?php krm_t( 'ministry.story_eyebrow', 'site' ); ?></p>
			<h2 id="story-heading"><?php krm_t( 'ministry.story_heading', 'site' ); ?></h2>
		</div>
		<?php if ( $krm_post ) : ?>
		<p><a href="<?php echo esc_url( add_query_arg( 'ministry', $krm_m['slug'], krm_page_url( 'testimonies' ) ) ); ?>"><?php krm_t( 'ministry.story_more', 'site' ); ?></a></p>
		<?php endif; ?>
	</div>
	<figure class="testimony is-featured">
		<svg width="34" height="34" viewBox="0 0 24 24" fill="#E18426" aria-hidden="true"><path d="M7 7h4v4c0 3-1.5 5-4 6l-.7-1.2C8 15 8.6 13.8 8.7 12H7zM14 7h4v4c0 3-1.5 5-4 6l-.7-1.2C15 15 15.6 13.8 15.7 12H14z"></path></svg>
		<?php
		if ( $krm_post ) :
			$krm_role = (string) get_post_meta( $krm_post->ID, '_krm_role', true );
			$krm_img  = get_post_thumbnail_id( $krm_post );
			?>
		<blockquote><?php echo wp_kses_post( wpautop( $krm_post->post_content ) ); ?></blockquote>
		<figcaption>
			<?php if ( $krm_img ) : ?>
			<img src="<?php echo esc_url( (string) wp_get_attachment_image_url( $krm_img, 'thumbnail' ) ); ?>" alt="" width="48" height="48" loading="lazy" decoding="async">
			<?php else : ?>
			<span class="avatar" aria-hidden="true"></span>
			<?php endif; ?>
			<span><strong><?php echo esc_html( get_the_title( $krm_post ) ); ?></strong><?php if ( '' !== $krm_role ) : ?><small><?php echo esc_html( $krm_role ); ?></small><?php endif; ?></span>
		</figcaption>
		<?php else : ?>
		<blockquote><p><?php echo esc_html( sprintf( '[A testimony from %s, in their own words — to be supplied by the client.]', $krm_m['title'] ) ); ?></p></blockquote>
		<figcaption><span class="avatar" aria-hidden="true"></span><span><strong>[Name]</strong><small>[Village]</small></span></figcaption>
		<?php endif; ?>
	</figure>
</section>
