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
<section id="top" style="position: relative; background: #1A2550 url('<?php echo esc_url( krm_ref_url( krm_c( 'hero.image' ) ) ); ?>') center/cover no-repeat<?php krm_bg_small( krm_c( 'hero.image' ) ); ?>">
<?php if ( krm_c( 'hero.video' ) ) : ?>
<?php // Started by front.js once the page has loaded; until then the section's background (the same image) shows through. ?>
<video aria-hidden="true" data-krm-autoplay muted loop playsinline preload="none" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover"><source src="<?php echo esc_url( krm_media( krm_c( 'hero.video' ) ) ); ?>" type="video/mp4"></video>
<?php endif; ?>
<div aria-hidden="true" style="position: absolute; inset: 0; background: rgba(8,16,48,0.62)"></div>

<div style="position: relative; max-width: 1240px; margin: 0 auto; padding: clamp(56px, 7vw, 100px) clamp(16px, 3vw, 32px) clamp(110px, 10vw, 150px); display: flex; flex-wrap: wrap; gap: 40px 56px; align-items: center">
<div style="flex: 999 1 520px; min-width: 0; color: var(--color-white)">
<p style="margin: 0 0 14px; font-weight: 700; color: var(--color-accent)"><?php krm_t( 'hero.eyebrow' ); ?></p>
<h1 style="margin: 0; font-weight: 800; font-size: clamp(38px, 5vw, 60px); line-height: 1.12; letter-spacing: -0.02em"><?php krm_h( 'hero.title' ); ?></h1>
<p style="margin: 20px 0 0; max-width: 520px; font-size: 17px; color: #E4E8F3"><?php krm_t( 'hero.lead' ); ?></p>
<div style="display: flex; flex-wrap: wrap; gap: 16px 24px; align-items: center; margin-top: 30px">
<a class="k-g" href="<?php krm_u( 'hero.button1_url' ); ?>" style="text-decoration: none; background: var(--color-primary); color: var(--color-white); padding: 14px 24px; border-radius: 4px; font-weight: 700; min-height: 44px; box-sizing: border-box; display: inline-flex; gap: 8px; align-items: center; border: 1px solid #2A3F85"><?php krm_t( 'hero.button1_label' ); ?></a>
<a href="<?php krm_u( 'hero.button2_url' ); ?>" style="text-decoration: none; color: var(--color-white); font-weight: 600; display: inline-flex; gap: 12px; align-items: center; min-height: 44px"><span style="width: 46px; height: 46px; border-radius: 50%; background: var(--color-white); display: flex; align-items: center; justify-content: center"><svg width="16" height="16" viewBox="0 0 24 24" fill="#0C1C4F" aria-hidden="true"><path d="M7 4l13 8-13 8z"></path></svg></span><?php krm_t( 'hero.button2_label' ); ?></a>
</div>
</div>

<!-- Donation card -->
<div id="donate" style="flex: 1 1 340px; max-width: 400px; min-width: 0; background: var(--color-white); border-radius: 10px; padding: 26px; box-shadow: 0 20px 50px rgba(0,0,0,0.25)">
<span style="display: inline-block; background: var(--color-primary); color: var(--color-white); font-size: 11px; font-weight: 700; letter-spacing: 0.06em; padding: 4px 10px; border-radius: 3px"><?php krm_t( 'hero.card_badge' ); ?></span>
<h2 style="margin: 12px 0 8px; font-size: 21px; font-weight: 800; line-height: 1.3"><?php krm_t( 'hero.card_heading' ); ?></h2>
<p style="margin: 0; font-size: 14px; color: var(--color-text-muted)"><?php krm_t( 'hero.card_text' ); ?></p>
<p style="margin: 16px 0 0; font-size: 13px; line-height: 1.6; color: var(--color-text-muted)"><?php krm_t( 'hero.card_note' ); ?></p>
<div role="group" aria-label="Donation amount" style="display: grid; grid-template-columns: repeat(<?php echo (int) count( krm_home_amounts() ); ?>, minmax(0, 1fr)); gap: 6px; margin-top: 18px">
<?php
foreach ( krm_home_amounts() as $krm_a => $krm_on ) :
	?>
<button type="button" data-amount="<?php echo esc_attr( $krm_a ); ?>" aria-pressed="<?php echo $krm_on ? 'true' : 'false'; ?>" style="font: 600 14px 'Plus Jakarta Sans', sans-serif; min-height: 44px; border-radius: 4px; cursor: pointer; border: 1px solid <?php echo $krm_on ? 'var(--color-primary)' : '#D3D9E6'; ?>; background: <?php echo $krm_on ? 'var(--color-primary)' : 'var(--color-white)'; ?>; color: <?php echo $krm_on ? 'var(--color-white)' : 'var(--color-text)'; ?>"><?php echo esc_html( $krm_a ); ?></button>
<?php endforeach; ?>
</div>
<a class="k-y" data-krm-donate="hero" href="<?php echo esc_url( krm_home_donate_href( krm_home_selected_amount() ) ); ?>" style="margin-top: 14px; text-decoration: none; display: flex; justify-content: center; align-items: center; gap: 8px; background: var(--color-secondary); color: var(--color-white); font-weight: 700; padding: 14px; border-radius: 4px; min-height: 44px; box-sizing: border-box">Donate <span><?php echo esc_html( 'Other' === krm_home_selected_amount() ? 'Now' : krm_home_selected_amount() ); ?></span></a>
</div>
</div>
</section>
