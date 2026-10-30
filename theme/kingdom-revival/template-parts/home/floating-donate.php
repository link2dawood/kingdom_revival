<?php
/**
 * Home: floating donation panel (the home page's own; other pages get the one from site.js).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<div style="position: fixed; right: 18px; bottom: 18px; z-index: 10000; display: flex; flex-direction: column; align-items: flex-end; gap: 12px; font-family: 'Plus Jakarta Sans', sans-serif">
<div id="floating-donation-panel" aria-label="Choose a donation amount" style="display: none; width: min(360px, calc(100vw - 56px)); box-sizing: border-box; padding: 22px; background: #FFFFFF; color: #141B33; border: 1px solid #DDE3F0; border-radius: 12px; box-shadow: 0 12px 36px rgba(12,28,79,0.24)">
<p style="margin: 0 0 4px; font-size: 12px; font-weight: 800; letter-spacing: 0.08em; color: #D4070B">SUPPORT THE MISSION</p>
<h2 style="margin: 0; font-size: 21px; font-weight: 800">Choose an amount</h2>
<p style="margin: 6px 0 0; font-size: 14px; color: #5A6275">Your gift helps share the Gospel and care for vulnerable families.</p>
<div role="group" aria-label="Donation amount" style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; margin-top: 16px">
<?php
foreach ( array( '$25', '$50', '$100', '$250', 'Other' ) as $krm_a ) :
	$krm_on = '$50' === $krm_a;
	?>
<button type="button" data-amount="<?php echo esc_attr( $krm_a ); ?>" aria-pressed="<?php echo $krm_on ? 'true' : 'false'; ?>" style="font: 600 14px 'Plus Jakarta Sans', sans-serif; min-height: 42px; border-radius: 6px; cursor: pointer; border: 1px solid <?php echo $krm_on ? '#0C1C4F' : '#D3D9E6'; ?>; background: <?php echo $krm_on ? '#0C1C4F' : '#FFFFFF'; ?>; color: <?php echo $krm_on ? '#FFFFFF' : '#141B33'; ?>"><?php echo esc_html( $krm_a ); ?></button>
<?php endforeach; ?>
</div>
<a data-krm-donate="float" href="<?php echo esc_url( krm_home_donate_href( '$50' ) ); ?>" style="margin-top: 14px; text-decoration: none; display: flex; justify-content: center; align-items: center; background: #D4070B; color: #FFFFFF; font-weight: 800; gap: 8px; padding: 13px 18px; border-radius: 6px; min-height: 44px; box-sizing: border-box">Donate <span>$50</span></a>
</div>
<button type="button" data-krm-float-toggle aria-controls="floating-donation-panel" aria-expanded="false" style="display: inline-flex; gap: 9px; align-items: center; justify-content: center; min-height: 54px; padding: 0 22px; border: 0; border-radius: 999px; background: #D4070B; color: #FFFFFF; box-shadow: 0 8px 24px rgba(12,28,79,0.28); font: 800 15px 'Plus Jakarta Sans', sans-serif; cursor: pointer">
<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 5v14M5 12h14"></path></svg><span>Donate</span>
</button>
</div>
