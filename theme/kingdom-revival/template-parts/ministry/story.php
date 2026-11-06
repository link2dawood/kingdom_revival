<?php
/**
 * Ministry page 6: one testimony in the home "Stories from the field" card — the first Testimonial linked to this
 * ministry. Until a real testimonial is published, use that ministry's clearly marked sample testimonial from the
 * setup data so every ministry page has a complete example card.
 *
 * @param array $args { ministry }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m    = $args['ministry'];
$krm_post = krm_ministry_testimonial( $krm_m );
$krm_sample = null;
if ( ! $krm_post ) {
	foreach ( include KRM_DIR . '/inc/data/testimonials.php' as $krm_testimonial ) {
		if ( $krm_m['slug'] === $krm_testimonial['ministry'] && krm_sample_testimonial_enabled( $krm_testimonial['name'] ) ) {
			$krm_sample = $krm_testimonial;
			break;
		}
	}
}
if ( ! $krm_post && ! $krm_sample ) {
	return;
}
?>
<section class="ministry-story" aria-labelledby="story-heading">
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
		<svg width="34" height="34" viewBox="0 0 24 24" aria-hidden="true" style="fill: var(--color-orange)"><path d="M7 7h4v4c0 3-1.5 5-4 6l-.7-1.2C8 15 8.6 13.8 8.7 12H7zM14 7h4v4c0 3-1.5 5-4 6l-.7-1.2C15 15 15.6 13.8 15.7 12H14z"></path></svg>
		<?php
		if ( $krm_post ) :
			$krm_role = (string) get_post_meta( $krm_post->ID, '_krm_role', true );
			$krm_img  = get_post_thumbnail_id( $krm_post );
			?>
		<blockquote><?php echo wp_kses_post( wpautop( $krm_post->post_content ) ); ?></blockquote>
		<figcaption>
			<?php krm_avatar( get_the_title( $krm_post ), $krm_img ); ?>
			<span><strong><?php echo esc_html( get_the_title( $krm_post ) ); ?></strong><?php if ( '' !== $krm_role ) : ?><small><?php echo esc_html( $krm_role ); ?></small><?php endif; ?></span>
		</figcaption>
		<?php else : ?>
		<blockquote><p><?php echo esc_html( $krm_sample['text'] ); ?></p></blockquote>
		<figcaption><?php krm_avatar( $krm_sample['name'] ); ?><span><strong><?php echo esc_html( $krm_sample['name'] ); ?></strong><small><?php echo esc_html( $krm_sample['role'] ); ?></small></span></figcaption>
		<?php endif; ?>
	</figure>
</section>
