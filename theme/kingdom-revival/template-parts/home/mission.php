<?php
/**
 * Home: "Taking the Gospel to the villages of Pakistan" (theme 1.21.3; was "Bringing Christ to the brick kilns of Pakistan") — mission statement with Luke 4:18, after the ministry
 * explorer, with a photo slider beside the text (progress bars, arrows, caption; script in assets/js/front.js).
 * Text and slides: Pages → Home → Page content → Mission. Styles: .krm-mission in assets/css/front.css.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_b1 = trim( (string) krm_c( 'mission.button1_label' ) );
$krm_b2 = trim( (string) krm_c( 'mission.button2_label' ) );
$krm_gw = trim( (string) krm_c( 'mission.ghost' ) );
$krm_sl = array_values(
	array_filter(
		(array) krm_c( 'mission.slides' ),
		static function ( $s ) {
			return is_array( $s ) && '' !== trim( (string) ( $s['image'] ?? '' ) );
		}
	)
);
?>
<!-- Mission: villages of Pakistan -->
<section id="mission" class="krm-mission" aria-labelledby="krm-mission-title">
<div class="krm-mission__inner<?php echo $krm_sl ? ' krm-mission__inner--slider' : ''; ?>" data-reveal>
<div class="krm-mission__copy">
<p class="krm-mission__eyebrow"><?php krm_t( 'mission.eyebrow' ); ?></p>
<h2 id="krm-mission-title" class="krm-mission__title"><?php krm_t( 'mission.heading1' ); ?> <em><?php krm_t( 'mission.heading2' ); ?></em></h2>
<p class="krm-mission__text"><?php krm_t( 'mission.text' ); ?></p>
<blockquote class="krm-mission__verse">
<p><?php krm_t( 'mission.quote' ); ?></p>
<cite><?php krm_t( 'mission.cite' ); ?></cite>
</blockquote>
<?php if ( '' !== $krm_b1 || '' !== $krm_b2 ) : ?>
<div class="krm-mission__actions">
<?php if ( '' !== $krm_b1 ) : ?>
<a class="button button-primary" href="<?php krm_u( 'mission.button1_url' ); ?>"><?php krm_t( 'mission.button1_label' ); ?></a>
<?php endif; ?>
<?php if ( '' !== $krm_b2 ) : ?>
<a class="button button-light" href="<?php krm_u( 'mission.button2_url' ); ?>"><?php krm_t( 'mission.button2_label' ); ?></a>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
<?php if ( $krm_sl ) : ?>
<div class="krm-mission__slider" data-krm-slider role="region" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Photos from the field', 'kingdom-revival' ); ?>">
<?php if ( count( $krm_sl ) > 1 ) : ?>
<div class="krm-mission__bars" aria-hidden="true"><?php foreach ( $krm_sl as $krm_i => $krm_s ) : ?><span class="krm-mission__bar"><i></i></span><?php endforeach; ?></div>
<?php endif; ?>
<?php foreach ( $krm_sl as $krm_i => $krm_s ) : ?>
<figure class="krm-mission__slide<?php echo 0 === $krm_i ? ' is-active' : ''; ?>" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: slide count */ __( '%1$d of %2$d', 'kingdom-revival' ), $krm_i + 1, count( $krm_sl ) ) ); ?>"<?php echo 0 === $krm_i ? '' : ' aria-hidden="true"'; ?>>
<img <?php krm_img_src( $krm_s['image'], '(max-width: 959px) 100vw, 480px' ); ?> alt="<?php echo krm_alt( $krm_s['image'], $krm_s['alt'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" loading="lazy" decoding="async">
<?php if ( '' !== trim( (string) ( $krm_s['title'] ?? '' ) . (string) ( $krm_s['meta'] ?? '' ) ) ) : ?>
<figcaption class="krm-mission__cap"><?php if ( '' !== trim( (string) $krm_s['title'] ) ) : ?><strong><?php echo krm_text( $krm_s['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong><?php endif; ?><?php if ( '' !== trim( (string) ( $krm_s['meta'] ?? '' ) ) ) : ?> <span><?php echo krm_text( $krm_s['meta'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php endif; ?></figcaption>
<?php endif; ?>
</figure>
<?php endforeach; ?>
<?php if ( count( $krm_sl ) > 1 ) : ?>
<button type="button" class="krm-mission__nav krm-mission__nav--prev" data-krm-prev aria-label="<?php esc_attr_e( 'Previous photo', 'kingdom-revival' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="M15 5l-7 7 7 7" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
<button type="button" class="krm-mission__nav krm-mission__nav--next" data-krm-next aria-label="<?php esc_attr_e( 'Next photo', 'kingdom-revival' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="M9 5l7 7-7 7" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
<?php if ( '' !== $krm_gw ) : ?>
<span class="krm-mission__ghost" aria-hidden="true"><?php echo esc_html( $krm_gw ); ?></span>
<?php endif; ?>
</section>
