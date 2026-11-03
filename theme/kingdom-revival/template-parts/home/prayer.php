<?php
/**
 * Home: prayer request form. Posts to WordPress like the contact form (inc/forms.php, form "prayer");
 * assets/js/front.js sends it.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_input = "font: 400 16px 'Plus Jakarta Sans', sans-serif; padding: 12px 14px; border: 1px solid #D3D9E6; border-radius: 8px; background: #FFFFFF; min-height: 46px; box-sizing: border-box";
?>
<!-- Prayer request -->
<section id="prayer" style="max-width: 1240px; margin: 0 auto; padding: 0 clamp(16px, 3vw, 32px) clamp(72px, 8vw, 110px)">
<div style="border-radius: 16px; background: var(--color-bg-soft); padding: clamp(28px, 4vw, 52px); display: flex; flex-wrap: wrap; gap: 32px 56px">
<div style="flex: 1 1 320px; min-width: 0">
<p style="margin: 0 0 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--color-secondary)"><?php krm_t( 'prayer.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: clamp(30px, 3.4vw, 40px); font-weight: 800; line-height: 1.15; color: var(--color-primary)"><?php krm_h( 'prayer.heading' ); ?></h2>
<p style="margin: 14px 0 0; color: var(--color-text-muted)"><?php krm_t( 'prayer.text' ); ?></p>
<?php if ( '' !== trim( (string) krm_c( 'prayer.whatsapp_label' ) ) ) : ?>
<a href="<?php echo esc_url( krm_whatsapp_url() ); ?>" target="_blank" rel="noopener" style="margin-top: 16px; display: inline-flex; gap: 8px; align-items: center; font-weight: 700; min-height: 44px"><?php krm_t( 'prayer.whatsapp_label' ); ?></a>
<?php endif; ?>
</div>
<form data-krm-prayer novalidate style="flex: 1.3 1 380px; min-width: 0; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px">
<p class="krm-hp" aria-hidden="true" style="position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden"><label>Leave this field empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
<label class="krm-prayer__half" style="display: flex; flex-direction: column; gap: 6px; font-size: 14px; font-weight: 600">Your name<input type="text" name="name" autocomplete="name" required maxlength="200" style="<?php echo esc_attr( $krm_input ); ?>"></label>
<label class="krm-prayer__half" style="display: flex; flex-direction: column; gap: 6px; font-size: 14px; font-weight: 600">Email or WhatsApp<input type="text" name="contact" autocomplete="email" required maxlength="200" style="<?php echo esc_attr( $krm_input ); ?>"></label>
<label style="grid-column: span 2; display: flex; flex-direction: column; gap: 6px; font-size: 14px; font-weight: 600">Your prayer request<textarea name="request" rows="4" required maxlength="5000" style="font: 400 16px 'Plus Jakarta Sans', sans-serif; padding: 12px 14px; border: 1px solid #D3D9E6; border-radius: 8px; background: #FFFFFF; resize: vertical"></textarea></label>
<button type="submit" class="k-g" style="grid-column: span 2; justify-self: start; font: 700 15px 'Plus Jakarta Sans', sans-serif; background: var(--color-primary); color: var(--color-white); border: 0; border-radius: 8px; padding: 0 26px; min-height: 48px; cursor: pointer"><?php krm_t( 'prayer.button' ); ?></button>
<p data-krm-prayer-status role="status" hidden style="grid-column: span 2; margin: 0; font-weight: 600; color: #0F6B3A"><?php krm_t( 'prayer.success' ); ?></p>
<p data-krm-prayer-error role="alert" hidden style="grid-column: span 2; margin: 0; font-weight: 600; color: var(--color-secondary)"><?php krm_t( 'prayer.error' ); ?></p>
</form>
</div>
</section>
