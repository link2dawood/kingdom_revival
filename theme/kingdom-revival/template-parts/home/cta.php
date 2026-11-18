<?php
/**
 * Home: Matthew 28:19 call to action.
 *
 * @param array $args { screen: content screen to read (default: the current page's; 'home' elsewhere). }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_screen = isset( $args['screen'] ) ? (string) $args['screen'] : ''; // 'home' when shown on another page.
?>
<!-- CTA banner -->
<section style="max-width: 1240px; margin: 0 auto; padding: clamp(64px, 7vw, 100px) clamp(16px, 3vw, 32px)">
<div<?php echo krm_photo_band_attrs( krm_c( 'cta.band_photo', $krm_screen ), 'cta' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> style="background: var(--color-band); color: var(--color-white); border-radius: 14px; overflow: hidden; padding: clamp(32px, 5vw, 56px); display: flex; flex-wrap: wrap; gap: 24px 48px; align-items: center; justify-content: space-between">
<div style="flex: 999 1 480px">
<p style="margin: 0; font-size: clamp(24px, 2.8vw, 34px); font-weight: 800; line-height: 1.25"><?php krm_t( 'cta.quote', $krm_screen ); ?></p>
<p style="margin: 8px 0 0; color: var(--color-accent); font-weight: 700"><?php krm_t( 'cta.cite', $krm_screen ); ?></p>
</div>
<a class="k-y" href="<?php krm_u( 'cta.button_url', $krm_screen ); ?>" style="text-decoration: none; background: var(--color-secondary); color: var(--color-white); font-weight: 800; padding: 16px 30px; border-radius: 4px; min-height: 44px; box-sizing: border-box; display: inline-flex; align-items: center"><?php krm_t( 'cta.button_label', $krm_screen ); ?></a>
</div>
</section>
