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
<div style="aspect-ratio: 1 / 1; border-radius: 50%; overflow: hidden; background: #CBD4E8; border: 8px solid var(--color-white); box-shadow: 0 10px 30px rgba(12,28,79,0.12)"><img <?php krm_img_src( krm_c( 'founder.image' ), '264px' ); ?> alt="<?php echo krm_alt( krm_c( 'founder.image' ), krm_c( 'founder.image_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" data-zoom="founder" loading="lazy" decoding="async" style="display: block; width: 100%; height: 100%; object-fit: cover; object-position: center 18%; object-position: 8% center"></div>
</div>
<div style="flex: 999 1 480px; min-width: 0">
<p style="margin: 0 0 8px; font-weight: 700; color: var(--color-secondary)"><?php krm_t( 'founder.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: clamp(30px, 3.4vw, 42px); font-weight: 800; line-height: 1.2"><?php krm_h( 'founder.heading' ); ?></h2>
<p style="margin: 16px 0 0; font-size: 17px; color: #3E465C; max-width: 680px"><?php krm_t( 'founder.text' ); ?></p>
<a class="k-g" href="<?php krm_u( 'founder.button_url' ); ?>" style="margin-top: 22px; text-decoration: none; display: inline-flex; align-items: center; background: var(--color-primary); color: var(--color-white); font-weight: 700; padding: 13px 24px; border-radius: 4px; min-height: 44px; box-sizing: border-box"><?php krm_t( 'founder.button_label' ); ?></a>
</div>
</div>
</section>
