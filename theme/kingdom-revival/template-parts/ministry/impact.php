<?php
/**
 * Ministry page 3: "The ministry in action" — what the work looks like, from content that already exists:
 *  - From the field: the ministry's latest story (News & Updates post with this Related ministry): its cover photo,
 *    title, date and opening paragraphs in the story's own words, and a link to the full story;
 *  - In pictures: three of the ministry's photos (after the hero and the intro pair) with their Media Library
 *    descriptions as captions.
 * Nothing is printed for a ministry with neither (visitors and administrators alike: there is nothing to fill in here
 * beyond writing a story or adding photos).
 *
 * @param array $args { ministry, photos }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m      = $args['ministry'];
$krm_photos = array_values( (array) $args['photos'] );
$krm_story  = krm_ministry_story_post( $krm_m );
// Photos not already shown above (1st = hero, 2nd and 3rd = intro pair); with few photos, what there is after the hero.
$krm_essay = count( $krm_photos ) > 3 ? array_slice( $krm_photos, 3, 3 ) : array_slice( $krm_photos, 1, 3 );
if ( ! $krm_story && ! $krm_essay ) {
	return;
}
?>
<section class="ministry-impact" aria-labelledby="impact-heading">
	<div class="ministry-head">
		<div>
			<p class="ministry-kicker"><?php krm_t( 'ministry.impact_eyebrow', 'site' ); ?></p>
			<h2 id="impact-heading"><?php echo krm_site_ministry_text( 'ministry.impact_heading', $krm_m['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped there. ?></h2>
		</div>
	</div>

	<?php
	if ( $krm_story ) :
		$krm_cover = get_post_thumbnail_id( $krm_story );
		$krm_open  = krm_story_opening( $krm_story );
		?>
	<article class="impact-story-card">
		<?php if ( $krm_cover ) : ?>
		<a class="impact-story-card__photo" href="<?php echo esc_url( get_permalink( $krm_story ) ); ?>" tabindex="-1" aria-hidden="true">
			<?php echo wp_get_attachment_image( $krm_cover, 'medium_large', false, array( 'sizes' => '(max-width: 760px) 100vw, 360px', 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '' ) ); ?>
		</a>
		<?php endif; ?>
		<div class="impact-story-card__body">
			<p class="impact-story-card__meta"><span><?php krm_t( 'ministry.impact_story', 'site' ); ?></span> · <time datetime="<?php echo esc_attr( get_the_date( 'c', $krm_story ) ); ?>"><?php echo esc_html( get_the_date( '', $krm_story ) ); ?></time></p>
			<h3><a href="<?php echo esc_url( get_permalink( $krm_story ) ); ?>"><?php echo esc_html( get_the_title( $krm_story ) ); ?></a></h3>
			<?php foreach ( $krm_open as $krm_para ) : ?>
			<p><?php echo esc_html( $krm_para ); ?></p>
			<?php endforeach; ?>
			<a class="impact-story-card__more" href="<?php echo esc_url( get_permalink( $krm_story ) ); ?>"><?php krm_t( 'ministry.impact_read', 'site' ); ?></a>
		</div>
	</article>
	<?php endif; ?>

	<?php if ( $krm_essay ) : ?>
	<h3 class="impact-essay__label"><?php krm_t( 'ministry.impact_photos', 'site' ); ?></h3>
	<div class="impact-essay" data-gallery>
		<?php foreach ( $krm_essay as $krm_photo ) : ?>
			<?php $krm_caption = krm_photo_caption( $krm_photo[0], $krm_photo[1] ); ?>
		<figure>
			<img <?php krm_img_src( $krm_photo[0], '(max-width: 560px) calc(100vw - 32px), (max-width: 1100px) 45vw, 260px', true ); ?> alt="<?php echo esc_attr( $krm_caption ); ?>" loading="lazy" decoding="async">
			<figcaption><?php echo esc_html( $krm_caption ); ?></figcaption>
		</figure>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
</section>
