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
		<video class="krm-field-stories__video" controls playsinline preload="none" data-krm-poster="<?php echo esc_url( krm_ref_url( krm_c( 'field.poster' ) ) ); ?>" title="<?php echo esc_attr( krm_fill( krm_c( 'field.video_title' ) ) ); ?>">
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
	<ul class="krm-field-stories__gallery" aria-label="<?php esc_attr_e( 'Ministry programs', 'kingdom-revival' ); ?>">
		<?php
		foreach ( (array) krm_c( 'field.photos' ) as $krm_photo ) :
			$krm_photo = wp_parse_args( (array) $krm_photo, array( 'image' => '', 'image_alt' => '', 'caption' => '', 'label' => '', 'url' => '' ) );
			$krm_href  = '' !== trim( (string) $krm_photo['url'] ) ? krm_url( $krm_photo['url'] ) : '';
			$krm_tag   = $krm_href ? 'a' : 'div';
			?>
		<li>
			<<?php echo $krm_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="krm-program-card"<?php echo $krm_href ? ' href="' . $krm_href . '"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<img <?php krm_img_src( $krm_photo['image'], '(max-width: 760px) 100vw, (max-width: 1240px) 33vw, 400px', true ); ?> alt="<?php echo krm_alt( $krm_photo['image'], $krm_photo['image_alt'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" loading="lazy" decoding="async">
				<span class="krm-program-card__body">
					<?php if ( '' !== trim( (string) krm_public( $krm_photo['label'] ) ) ) : ?><span class="krm-program-card__label"><?php echo krm_text( $krm_photo['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php endif; ?>
					<span class="krm-program-card__title"><?php echo krm_text( $krm_photo['caption'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php if ( $krm_href ) : ?><span class="krm-program-card__more"><?php esc_html_e( 'View ministry', 'kingdom-revival' ); ?> <span aria-hidden="true">&rarr;</span></span><?php endif; ?>
				</span>
			</<?php echo $krm_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		</li>
		<?php endforeach; ?>
	</ul>
	</div>
</section>
