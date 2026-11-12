<?php
/**
 * Home: 8. Stories from the Field — testimonies with a photo, the person's words, name and place, and a "Read Full Story"
 * link (theme 1.23.0). A row of cards on computers, a swipeable slider on phones (CSS scroll snap, no script).
 * Visitors see only stories with real words (none: no section). The link defaults to the person's testimony on the
 * Testimonials page (#testimony-{name}), else that page. Styles: .krm-stories in assets/css/front.css.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_items = krm_visible( krm_c( 'stories.items' ), 'quote' ); // Visitors: only stories with real words.
$krm_items = array_values(
	array_filter(
		$krm_items,
		function ( $story ) {
			return krm_sample_testimonial_enabled( isset( $story['name'] ) ? $story['name'] : '' );
		}
	)
);
if ( ! $krm_items ) {
	return;
}
$krm_none = ! array_filter(
	$krm_items,
	function ( $s ) {
		return ! krm_is_placeholder( $s['quote'] );
	}
);
$krm_more = trim( (string) krm_c( 'stories.more_label' ) );
?>
<!-- Stories from the field -->
<section id="stories" class="krm-sec krm-sec--soft krm-stories" aria-labelledby="krm-stories-title">
<div class="krm-sec__inner">
<div class="krm-sec__head krm-sec__head--center" data-reveal>
<?php krm_admin_only_note( $krm_none ); ?>
<p class="krm-sec__eyebrow"><?php krm_t( 'stories.eyebrow' ); ?></p>
<h2 id="krm-stories-title" class="krm-sec__title"><?php krm_h( 'stories.heading' ); ?></h2>
</div>
<ul class="krm-stories__list" data-reveal-stagger>
<?php foreach ( $krm_items as $krm_story ) : ?>
	<?php
	$krm_img  = trim( (string) ( $krm_story['image'] ?? '' ) );
	$krm_name = wp_strip_all_tags( krm_fill( $krm_story['name'] ) );
	$krm_url  = trim( (string) ( $krm_story['url'] ?? '' ) );
	$krm_url  = '' !== $krm_url ? krm_resolve_url( $krm_url ) : krm_page_url( 'testimonies' ) . ( '' !== $krm_name ? '#testimony-' . sanitize_title( $krm_name ) : '' );
	?>
<li class="krm-story">
	<?php if ( '' !== $krm_img ) : ?>
<div class="krm-story__media"><img <?php krm_img_src( $krm_img, '(max-width: 760px) 84vw, 400px' ); ?> alt="<?php echo krm_alt( $krm_img, $krm_story['alt'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" loading="lazy" decoding="async"></div>
	<?php endif; ?>
<figure class="krm-story__body">
<svg class="krm-story__mark" width="30" height="30" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 7h4v4c0 3-1.5 5-4 6l-.7-1.2C8 15 8.6 13.8 8.7 12H7zM14 7h4v4c0 3-1.5 5-4 6l-.7-1.2C15 15 15.6 13.8 15.7 12H14z"></path></svg>
<blockquote><?php echo krm_text( $krm_story['quote'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></blockquote>
<figcaption>
	<?php krm_avatar( $krm_story['name'], $krm_story['photo'] ?? '' ); ?>
<span><strong><?php echo krm_text( $krm_story['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong><small><?php echo krm_text( $krm_story['place'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></small></span>
</figcaption>
</figure>
	<?php if ( '' !== $krm_more && '' !== $krm_url ) : ?>
<a class="krm-story__link" href="<?php echo esc_url( $krm_url ); ?>"><?php echo krm_text( $krm_more ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="visually-hidden">: <?php echo esc_html( $krm_name ); ?></span> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></a>
	<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
<?php if ( '' !== trim( (string) krm_c( 'stories.all_label' ) ) ) : ?>
<p class="krm-sec__more"><a href="<?php krm_u( 'stories.all_url' ); ?>"><?php krm_t( 'stories.all_label' ); ?></a></p>
<?php endif; ?>
</div>
</section>
