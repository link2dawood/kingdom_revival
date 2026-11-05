<?php
/**
 * Home: founder.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- Founder -->
<section id="founder" style="background: var(--color-bg-soft)">
<div style="max-width: 1240px; margin: 0 auto; padding: clamp(64px, 7vw, 100px) clamp(16px, 3vw, 32px); display: flex; flex-wrap: wrap; gap: 36px 56px; align-items: center">
<div style="flex: 0 1 280px; min-width: 0">
<div style="aspect-ratio: 1 / 1; border-radius: 50%; overflow: hidden; background: var(--color-placeholder-2); border: 8px solid var(--color-surface); box-shadow: 0 10px 30px rgba(var(--shadow-rgb), calc(0.12 * var(--shadow-k)))"><img <?php krm_img_src( krm_c( 'founder.image' ), '264px' ); ?> alt="<?php echo krm_alt( krm_c( 'founder.image' ), krm_c( 'founder.image_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" data-zoom="founder" loading="lazy" decoding="async" style="display: block; width: 100%; height: 100%; object-fit: cover; object-position: center 18%; object-position: 8% center"></div>
</div>
<div style="flex: 999 1 480px; min-width: 0">
<p style="margin: 0 0 8px; font-weight: 700; color: var(--color-secondary-text)"><?php krm_t( 'founder.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: clamp(30px, 3.4vw, 42px); font-weight: 800; line-height: 1.2"><?php krm_h( 'founder.heading' ); ?></h2>
<p style="margin: 16px 0 0; font-size: 17px; color: var(--color-text-soft-2); max-width: 680px"><?php krm_t( 'founder.text' ); ?></p>
<?php if ( '' !== trim( (string) krm_c( 'founder.quote' ) ) ) : ?>
<blockquote style="margin: 22px 0 0; padding-left: 20px; border-left: 4px solid var(--color-orange); font-size: 20px; font-weight: 600; line-height: 1.5; color: var(--color-heading); max-width: 680px"><?php krm_t( 'founder.quote' ); ?></blockquote>
<?php endif; ?>
<div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 24px">
<a class="k-g" href="<?php krm_u( 'founder.button_url' ); ?>" style="text-decoration: none; display: inline-flex; align-items: center; background: var(--color-button-navy); color: var(--color-white); font-weight: 700; padding: 13px 24px; border-radius: 4px; min-height: 44px; box-sizing: border-box"><?php krm_t( 'founder.button_label' ); ?></a>
<?php if ( '' !== trim( (string) krm_c( 'founder.whatsapp_label' ) ) ) : ?>
<a href="<?php echo esc_url( krm_whatsapp_url() ); ?>" target="_blank" rel="noopener" style="text-decoration: none; display: inline-flex; align-items: center; border: 1.5px solid var(--color-heading); color: var(--color-heading); font-weight: 700; padding: 13px 24px; border-radius: 4px; min-height: 44px; box-sizing: border-box"><?php krm_t( 'founder.whatsapp_label' ); ?></a>
<?php endif; ?>
<?php if ( '' !== trim( (string) krm_c( 'founder.story_label' ) ) ) : ?>
<a href="<?php krm_u( 'founder.story_url' ); ?>" style="display: inline-flex; align-items: center; font-weight: 700; min-height: 44px; padding: 0 8px"><?php krm_t( 'founder.story_label' ); ?></a>
<?php endif; ?>
</div>
</div>
</div>
</section>
