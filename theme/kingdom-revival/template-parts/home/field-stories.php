<?php
/**
 * Home: field stories (featured video and photo gallery).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="field-stories" class="krm-field-stories" aria-labelledby="field-stories-title">
	<div class="krm-field-stories__inner">
	<div class="krm-field-stories__intro">
		<p class="krm-field-stories__eyebrow"><?php krm_t( 'field.eyebrow' ); ?></p>
		<h2 id="field-stories-title"><?php krm_t( 'field.heading' ); ?></h2>
		<p><?php krm_t( 'field.text' ); ?></p>
	</div>
	<div class="krm-field-stories__feature">
		<video class="krm-field-stories__video" controls playsinline preload="metadata" poster="<?php echo esc_url( krm_ref_url( krm_c( 'field.poster' ) ) ); ?>" title="<?php echo esc_attr( krm_fill( krm_c( 'field.video_title' ) ) ); ?>">
		<source src="<?php echo esc_url( krm_media( krm_c( 'field.video' ) ) ); ?>" type="video/mp4">
		Your browser does not support HTML video. <a href="<?php echo esc_url( krm_media( krm_c( 'field.video' ) ) ); ?>">Open the ministry video</a>.
		</video>
		<div class="krm-field-stories__feature-copy">
		<p class="krm-field-stories__eyebrow"><?php krm_t( 'field.feature_eyebrow' ); ?></p>
		<h3><?php krm_t( 'field.feature_heading' ); ?></h3>
		<p><?php krm_t( 'field.feature_text' ); ?></p>
		<a class="krm-field-stories__video-link" href="<?php echo esc_url( krm_media( krm_c( 'field.video' ) ) ); ?>" target="_blank" rel="noopener noreferrer"><?php krm_t( 'field.feature_link' ); ?></a>
		</div>
	</div>
	<div class="krm-field-stories__gallery" aria-label="Ministry photos" data-gallery>
		<?php foreach ( (array) krm_c( 'field.photos' ) as $krm_photo ) : ?>
		<figure>
			<img <?php krm_img_src( $krm_photo['image'] ); ?> alt="<?php echo krm_alt( $krm_photo['image'], $krm_photo['image_alt'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" loading="lazy" decoding="async">
			<figcaption><?php echo krm_text( $krm_photo['caption'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></figcaption>
		</figure>
		<?php endforeach; ?>
	</div>
	</div>
</section>
