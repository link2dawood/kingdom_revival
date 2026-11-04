<?php
/**
 * Ministry page 5: up to nine of the ministry's photos, opened full screen by the photo viewer (viewer.js,
 * [data-gallery]). With more photos, a link goes to the Gallery filtered to this ministry.
 *
 * @param array $args { ministry, photos }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m      = $args['ministry'];
$krm_photos = array_slice( $args['photos'], 0, 9 );
if ( ! $krm_photos && ! krm_show_placeholders() ) {
	return; // Visitors: no photo section until the ministry has photos (administrators see the empty note).
}
?>
<section class="ministry-photos" aria-labelledby="gallery-heading">
	<div class="ministry-head">
		<h2 id="gallery-heading"><?php echo krm_site_ministry_text( 'ministry.photos_heading', $krm_m['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped there. ?></h2>
		<?php if ( $krm_photos ) : ?>
		<p class="ministry-tip"><?php echo krm_icon( 'zoom' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php krm_t( 'ministry.photos_tip', 'site' ); ?></p>
		<?php endif; ?>
	</div>
	<?php if ( $krm_photos ) : ?>
	<div class="gallery" id="ministry-gallery" data-gallery>
		<?php foreach ( $krm_photos as $krm_photo ) : ?>
		<figure class="photo"><img <?php krm_img_src( $krm_photo[0], '(max-width: 520px) calc(100vw - 32px), (max-width: 760px) calc(50vw - 25px), (max-width: 1100px) calc(33vw - 24px), 260px', true ); ?> alt="<?php echo esc_attr( $krm_photo[1] ); ?>" loading="lazy" decoding="async"></figure>
		<?php endforeach; ?>
	</div>
		<?php if ( count( $args['photos'] ) > 9 ) : ?>
	<p class="ministry-more"><a href="<?php echo esc_url( add_query_arg( 'ministry', $krm_m['slug'], krm_page_url( 'gallery' ) ) ); ?>"><?php echo krm_site_ministry_text( 'ministry.photos_more', $krm_m['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></p>
		<?php endif; ?>
	<?php else : ?>
	<p class="empty-gallery"><?php krm_t( 'ministry.photos_empty', 'site' ); ?></p>
	<?php endif; ?>
</section>
