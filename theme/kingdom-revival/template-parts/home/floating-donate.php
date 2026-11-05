<?php
/**
 * Home: floating donation panel (the home page's own; other pages get the one from site.js).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<div style="position: fixed; right: 18px; bottom: 18px; z-index: 10000; display: flex; flex-direction: column; align-items: flex-end; gap: 12px; font-family: 'Plus Jakarta Sans', sans-serif">
<div id="floating-donation-panel" role="region" aria-label="Choose a donation amount" style="display: none; width: min(360px, calc(100vw - 56px)); box-sizing: border-box; padding: 22px; background: var(--color-surface); color: var(--color-text); border: 1px solid var(--color-border-2); border-radius: 12px; box-shadow: 0 12px 36px rgba(var(--shadow-rgb), calc(0.24 * var(--shadow-k)))">
<p style="margin: 0 0 4px; font-size: 12px; font-weight: 800; letter-spacing: 0.08em; color: var(--color-secondary-text)"><?php krm_t( 'float.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: 21px; font-weight: 800"><?php krm_t( 'float.heading' ); ?></h2>
<p style="margin: 6px 0 0; font-size: 14px; color: var(--color-text-muted)"><?php krm_t( 'float.text' ); ?></p>
<div role="group" aria-label="Donation amount" style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; margin-top: 16px">
<?php
foreach ( krm_home_amounts() as $krm_a => $krm_on ) :
	?>
<button type="button" data-amount="<?php echo esc_attr( $krm_a ); ?>" aria-pressed="<?php echo $krm_on ? 'true' : 'false'; ?>" style="font: 600 14px 'Plus Jakarta Sans', sans-serif; min-height: 42px; border-radius: 6px; cursor: pointer; border: 1px solid <?php echo $krm_on ? 'var(--color-button-navy)' : 'var(--color-input-border)'; ?>; background: <?php echo $krm_on ? 'var(--color-button-navy)' : 'var(--color-surface)'; ?>; color: <?php echo $krm_on ? 'var(--color-white)' : 'var(--color-text)'; ?>"><?php echo esc_html( $krm_a ); ?></button>
<?php endforeach; ?>
</div>
<a data-krm-donate="float" href="<?php echo esc_url( krm_home_donate_href( krm_home_selected_amount() ) ); ?>" style="margin-top: 14px; text-decoration: none; display: flex; justify-content: center; align-items: center; background: var(--color-secondary); color: var(--color-white); font-weight: 800; gap: 8px; padding: 13px 18px; border-radius: 6px; min-height: 44px; box-sizing: border-box">Donate <span><?php echo esc_html( 'Other' === krm_home_selected_amount() ? 'Any Amount' : krm_home_selected_amount() ); ?></span></a>
</div>
<button type="button" data-krm-float-toggle aria-controls="floating-donation-panel" aria-expanded="false" style="display: inline-flex; gap: 9px; align-items: center; justify-content: center; min-height: 54px; padding: 0 22px; border: 0; border-radius: 999px; background: var(--color-secondary); color: var(--color-white); box-shadow: 0 8px 24px rgba(var(--shadow-rgb), calc(0.28 * var(--shadow-k))); font: 800 15px 'Plus Jakarta Sans', sans-serif; cursor: pointer">
<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 5v14M5 12h14"></path></svg><span><?php krm_t( 'float.button' ); ?></span>
</button>
</div>
