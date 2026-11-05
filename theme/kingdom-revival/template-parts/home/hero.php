<?php
/**
 * Home: hero with background video and the "Make an impact" donation card.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- Hero -->
<?php krm_hero_preload( krm_c( 'hero.image' ) ); ?>
<section id="top" style="position: relative; background: var(--color-band-2) url('<?php echo esc_url( krm_ref_url( krm_c( 'hero.image' ) ) ); ?>') center/cover no-repeat<?php krm_bg_small( krm_c( 'hero.image' ) ); ?>">
<?php
if ( krm_c( 'hero.video' ) ) :
	// No src or poster in the HTML: front.js adds the sources and the poster only on screens 768px and wider, without
	// data saver or reduced motion, so phones never download the video. Until then (and on phones) the element is
	// empty and transparent and the section's background, the same photo, shows. Poster: the photo's WebP, else its
	// original JPG (both well under 200 KB).
	$krm_hero_img = krm_c( 'hero.image' );
	$krm_poster_w = krm_ref_url( $krm_hero_img );
	$krm_hero_id  = krm_media_id( (string) $krm_hero_img );
	$krm_poster_j = $krm_hero_id ? (string) wp_get_original_image_url( $krm_hero_id ) : '';
	$krm_poster_j = preg_match( '/\.jpe?g$/i', $krm_poster_j ) ? $krm_poster_j : $krm_poster_w;
	$krm_webm     = trim( (string) krm_c( 'hero.video_webm' ) );
	?>
<video aria-hidden="true" data-krm-autoplay muted loop playsinline preload="none" data-src-mp4="<?php echo esc_url( krm_media( krm_c( 'hero.video' ) ) ); ?>"<?php if ( '' !== $krm_webm ) : ?> data-src-webm="<?php echo esc_url( krm_media( $krm_webm ) ); ?>"<?php endif; ?> data-poster="<?php echo esc_url( $krm_poster_w ); ?>" data-poster-jpg="<?php echo esc_url( $krm_poster_j ); ?>" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover"></video>
<?php endif; ?>
<div aria-hidden="true" style="position: absolute; inset: 0; background: rgba(8,16,48,0.62)"></div>

<div style="position: relative; max-width: 1240px; margin: 0 auto; padding: calc(clamp(56px, 7vw, 100px) + var(--hero-offset, 0px)) clamp(16px, 3vw, 32px) clamp(110px, 10vw, 150px); display: flex; flex-wrap: wrap; gap: 40px 56px; align-items: center">
<div style="flex: 999 1 520px; min-width: 0; color: var(--color-white)">
<p style="margin: 0 0 14px; font-weight: 700; color: var(--color-accent)"><?php krm_t( 'hero.eyebrow' ); ?></p>
<h1 style="margin: 0; font-weight: 800; font-size: clamp(38px, 5vw, 60px); line-height: 1.12; letter-spacing: -0.02em"><?php krm_h( 'hero.title' ); ?></h1>
<p style="margin: 20px 0 0; max-width: 520px; font-size: 17px; color: var(--color-text-on-dark-2)"><?php krm_t( 'hero.lead' ); ?></p>
<div style="display: flex; flex-wrap: wrap; gap: 16px 24px; align-items: center; margin-top: 30px">
<a class="k-g" href="<?php krm_u( 'hero.button1_url' ); ?>" style="text-decoration: none; background: var(--color-button-navy); color: var(--color-white); padding: 14px 24px; border-radius: 4px; font-weight: 700; min-height: 44px; box-sizing: border-box; display: inline-flex; gap: 8px; align-items: center; border: 1px solid var(--color-band-3)"><?php krm_t( 'hero.button1_label' ); ?></a>
<a href="<?php krm_u( 'hero.button2_url' ); ?>" style="text-decoration: none; color: var(--color-white); font-weight: 600; display: inline-flex; gap: 12px; align-items: center; min-height: 44px"><span style="width: 46px; height: 46px; border-radius: 50%; background: var(--color-surface); display: flex; align-items: center; justify-content: center"><svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true" style="fill: var(--color-heading)"><path d="M7 4l13 8-13 8z"></path></svg></span><?php krm_t( 'hero.button2_label' ); ?></a>
</div>
</div>

<!-- Donation card -->
<div id="donate" style="flex: 1 1 340px; max-width: 400px; min-width: 0; background: var(--color-surface); border-radius: 10px; padding: 26px; box-shadow: 0 20px 50px rgba(0,0,0,0.25)">
<span style="display: inline-block; background: var(--color-band); color: var(--color-white); font-size: 11px; font-weight: 700; letter-spacing: 0.06em; padding: 4px 10px; border-radius: 3px"><?php krm_t( 'hero.card_badge' ); ?></span>
<h2 style="margin: 12px 0 8px; font-size: 21px; font-weight: 800; line-height: 1.3"><?php krm_t( 'hero.card_heading' ); ?></h2>
<p style="margin: 0; font-size: 14px; color: var(--color-text-muted)"><?php krm_t( 'hero.card_text' ); ?></p>
<p style="margin: 16px 0 0; font-size: 13px; line-height: 1.6; color: var(--color-text-muted)"><?php krm_t( 'hero.card_note' ); ?></p>
<div role="group" aria-label="Donation amount" style="display: grid; grid-template-columns: repeat(<?php echo (int) count( krm_home_amounts() ); ?>, minmax(0, 1fr)); gap: 6px; margin-top: 18px">
<?php
foreach ( krm_home_amounts() as $krm_a => $krm_on ) :
	?>
<button type="button" data-amount="<?php echo esc_attr( $krm_a ); ?>" aria-pressed="<?php echo $krm_on ? 'true' : 'false'; ?>" style="font: 600 14px 'Plus Jakarta Sans', sans-serif; min-height: 44px; border-radius: 4px; cursor: pointer; border: 1px solid <?php echo $krm_on ? 'var(--color-button-navy)' : 'var(--color-input-border)'; ?>; background: <?php echo $krm_on ? 'var(--color-button-navy)' : 'var(--color-surface)'; ?>; color: <?php echo $krm_on ? 'var(--color-white)' : 'var(--color-text)'; ?>"><?php echo esc_html( $krm_a ); ?></button>
<?php endforeach; ?>
</div>
<a class="k-y" data-krm-donate="hero" data-for="<?php echo esc_attr( krm_home_card_ministry() ); ?>" data-ministry="<?php echo esc_attr( krm_home_card_ministry_slug() ); ?>" href="<?php echo esc_url( krm_home_donate_href( krm_home_selected_amount(), krm_home_card_ministry(), krm_home_card_ministry_slug() ) ); ?>" style="margin-top: 14px; text-decoration: none; display: flex; justify-content: center; align-items: center; gap: 8px; background: var(--color-secondary); color: var(--color-white); font-weight: 700; padding: 14px; border-radius: 4px; min-height: 44px; box-sizing: border-box">Donate <span><?php echo esc_html( 'Other' === krm_home_selected_amount() ? 'Now' : krm_home_selected_amount() ); ?></span></a>
</div>
</div>
</section>
