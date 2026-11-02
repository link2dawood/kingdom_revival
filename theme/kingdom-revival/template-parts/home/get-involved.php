<?php
/**
 * Home: Pray / Give / Go.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- Pray Give Go -->
<section style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px)">
<div style="text-align: center; max-width: 680px; margin: 0 auto 44px">
<p style="margin: 0 0 8px; font-weight: 700; color: var(--color-secondary)"><?php krm_t( 'involved.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: clamp(30px, 3.4vw, 42px); font-weight: 800; line-height: 1.2"><?php krm_h( 'involved.heading' ); ?></h2>
</div>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 22px">
<?php
$krm_cards = array(
	'style' => array( 'border: 1px solid #E3E7F0; border-radius: 10px; padding: 30px; text-align: center', 'background: #0C1C4F; color: #FFFFFF; border-radius: 10px; padding: 30px; text-align: center', 'border: 1px solid #E3E7F0; border-radius: 10px; padding: 30px; text-align: center' ),
	'icon'  => array( '<span aria-hidden="true" style="width: 64px; height: 64px; margin: 0 auto; border-radius: 50%; background: var(--color-bg-tint); display: flex; align-items: center; justify-content: center"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0C1C4F" stroke-width="2"><path d="M12 2v6M9 5h6M5 22V11l7-3 7 3v11M9 22v-6h6v6"></path></svg></span>', '<span aria-hidden="true" style="width: 64px; height: 64px; margin: 0 auto; border-radius: 50%; background: #E18426; display: flex; align-items: center; justify-content: center"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#141B33" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg></span>', '<span aria-hidden="true" style="width: 64px; height: 64px; margin: 0 auto; border-radius: 50%; background: var(--color-bg-tint); display: flex; align-items: center; justify-content: center"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0C1C4F" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20"></path></svg></span>' ),
	'color' => array( '#5A6275', '#D6DDF0', '#5A6275' ),
);
foreach ( (array) krm_c( 'involved.items' ) as $krm_i => $krm_item ) :
	$krm_n = $krm_i % 3;
	?>
<div style="<?php echo esc_attr( $krm_cards['style'][ $krm_n ] ); ?>">
	<?php echo $krm_cards['icon'][ $krm_n ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed design markup. ?>
<h3 style="margin: 16px 0 8px; font-size: 22px; font-weight: 800"><?php echo krm_text( $krm_item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<p style="margin: 0; font-size: 15px; color: <?php echo esc_attr( $krm_cards['color'][ $krm_n ] ); ?>"><?php echo krm_text( $krm_item['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
</div>
<?php endforeach; ?>
</div>
</section>
