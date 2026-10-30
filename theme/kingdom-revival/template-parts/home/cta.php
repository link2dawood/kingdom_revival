<?php
/**
 * Home: Matthew 28:19 call to action.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- CTA banner -->
<section style="max-width: 1240px; margin: 0 auto; padding: clamp(64px, 7vw, 100px) clamp(16px, 3vw, 32px)">
<div style="background: #0C1C4F; color: #FFFFFF; border-radius: 14px; padding: clamp(32px, 5vw, 56px); display: flex; flex-wrap: wrap; gap: 24px 48px; align-items: center; justify-content: space-between">
<div style="flex: 999 1 480px">
<p style="margin: 0; font-size: clamp(24px, 2.8vw, 34px); font-weight: 800; line-height: 1.25"><?php krm_t( 'cta.quote' ); ?></p>
<p style="margin: 8px 0 0; color: #F5A04A; font-weight: 700"><?php krm_t( 'cta.cite' ); ?></p>
</div>
<a class="k-y" href="<?php krm_u( 'cta.button_url' ); ?>" style="text-decoration: none; background: #D4070B; color: #FFFFFF; font-weight: 800; padding: 16px 30px; border-radius: 4px; min-height: 44px; box-sizing: border-box; display: inline-flex; align-items: center"><?php krm_t( 'cta.button_label' ); ?></a>
</div>
</section>
