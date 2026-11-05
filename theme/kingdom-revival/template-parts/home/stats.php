<?php
/**
 * Home: impact stats bar (Kingdom Revival → Settings / Customizer → Impact numbers).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_icons = krm_home_stat_icons();
$krm_stats = krm_visible_stats( 'home' ); // A number not set yet is left out for visitors.
if ( ! $krm_stats ) {
	return;
}
?>
<!-- Stats bar -->
<section aria-label="Impact" style="position: relative; max-width: 1240px; margin: -64px auto 0; padding: 0 clamp(16px, 3vw, 32px)">
<div style="display: flex; flex-wrap: wrap; gap: 0; background: var(--color-surface); border-radius: 10px; box-shadow: 0 14px 40px rgba(var(--shadow-rgb), calc(0.12 * var(--shadow-k))); overflow: hidden">
<div style="flex: 3 1 560px; display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 16px; padding: 24px 28px">
<?php foreach ( $krm_stats as $krm_i => $krm_stat ) : ?>
<div style="display: flex; gap: 14px; align-items: center">
<span aria-hidden="true" style="flex: 0 0 48px; width: 48px; height: 48px; border-radius: 50%; background: var(--color-bg-tint); display: flex; align-items: center; justify-content: center"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2" style="stroke: var(--color-heading)"><path d="<?php echo esc_attr( $krm_icons[ $krm_i ] ); ?>"></path></svg></span>
<span style="display: flex; flex-direction: column; line-height: 1.2"><strong data-krm-count style="font-size: 28px; font-weight: 800"><?php echo esc_html( $krm_stat['value'] ); ?>+</strong><span style="font-size: 13px; color: var(--color-text-muted)"><?php echo esc_html( $krm_stat['label'] ); ?></span></span>
</div>
<?php endforeach; ?>
</div>
<div style="flex: 1 1 280px; background: var(--color-band); color: var(--color-white); padding: 24px 28px; display: flex; flex-direction: column; justify-content: center; gap: 10px">
<strong style="font-size: 18px; line-height: 1.3">Our prayer: lives transformed by Christ</strong>
<span style="display: flex; gap: 10px; align-items: center; font-size: 13px; color: var(--color-text-on-dark)">
<span aria-hidden="true" style="display: flex"><span style="width: 28px; height: 28px; border-radius: 50%; background: var(--color-placeholder-2); border: 2px solid var(--color-band)"></span><span style="width: 28px; height: 28px; border-radius: 50%; background: var(--color-gold); border: 2px solid var(--color-band); margin-left: -10px"></span><span style="width: 28px; height: 28px; border-radius: 50%; background: var(--color-accent); border: 2px solid var(--color-band); margin-left: -10px"></span></span>
Across Pakistan and beyond</span>
<a href="<?php echo esc_url( krm_page_url( 'impact' ) ); ?>" style="align-self: flex-start; display: inline-flex; align-items: center; min-height: 44px; color: var(--color-white); font-weight: 700; font-size: 14px; text-decoration: underline; text-underline-offset: 3px"><?php esc_html_e( 'See our impact →', 'kingdom-revival' ); ?></a>
</div>
</div>
</section>
