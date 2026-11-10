<?php
/**
 * Home: Pray / Give / Go, each card with a button at its foot (Pray and Go outline, Give the red Donate style).
 *
 * @param array $args { screen: content screen to read (default: the current page's; 'home' elsewhere). }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_screen = isset( $args['screen'] ) ? (string) $args['screen'] : ''; // 'home' when shown on another page.
?>
<!-- Pray Give Go -->
<section style="max-width: 1320px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px)">
<div style="text-align: center; max-width: 680px; margin: 0 auto 44px">
<p style="margin: 0 0 8px; font-weight: 700; color: var(--color-secondary-text)"><?php krm_t( 'involved.eyebrow', $krm_screen ); ?></p>
<h2 style="margin: 0; font-size: clamp(30px, 3.4vw, 42px); font-weight: 800; line-height: 1.2"><?php krm_h( 'involved.heading', $krm_screen ); ?></h2>
</div>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 22px">
<?php
$krm_cards = array(
	'style' => array( 'border: 1px solid var(--color-border); border-radius: 10px; padding: 30px; text-align: center; display: flex; flex-direction: column', 'background: var(--color-band); color: var(--color-white); border: 1px solid var(--color-band); border-radius: 10px; padding: 30px; text-align: center; display: flex; flex-direction: column', 'border: 1px solid var(--color-border); border-radius: 10px; padding: 30px; text-align: center; display: flex; flex-direction: column' ),
	'button' => array( 'button button-secondary', 'button button-primary', 'button button-secondary' ),
	'icon'  => array( '<span aria-hidden="true" style="width: 64px; height: 64px; margin: 0 auto; border-radius: 50%; background: var(--color-bg-tint); display: flex; align-items: center; justify-content: center"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke-width="2" style="stroke: var(--color-heading)"><path d="M12 2v6M9 5h6M5 22V11l7-3 7 3v11M9 22v-6h6v6"></path></svg></span>', '<span aria-hidden="true" style="width: 64px; height: 64px; margin: 0 auto; border-radius: 50%; background: var(--color-orange); display: flex; align-items: center; justify-content: center"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke-width="2" style="stroke: var(--color-text)"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg></span>', '<span aria-hidden="true" style="width: 64px; height: 64px; margin: 0 auto; border-radius: 50%; background: var(--color-bg-tint); display: flex; align-items: center; justify-content: center"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke-width="2" style="stroke: var(--color-heading)"><circle cx="12" cy="12" r="10"></circle><path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20"></path></svg></span>' ),
	'color' => array( 'var(--color-text-muted)', 'var(--color-text-on-dark)', 'var(--color-text-muted)' ),
);
// Buttons (theme 1.20.2): a list saved before then has no button keys, so it takes the default button of its position.
$krm_default_items = krm_content_schema( 'home' )['involved']['fields']['items']['default'];
foreach ( (array) krm_c( 'involved.items', $krm_screen ) as $krm_i => $krm_item ) :
	$krm_n = $krm_i % 3;
	if ( ! array_key_exists( 'button', $krm_item ) && isset( $krm_default_items[ $krm_i ] ) ) {
		$krm_item['button'] = $krm_default_items[ $krm_i ]['button'];
		$krm_item['url']    = $krm_default_items[ $krm_i ]['url'];
	}
	$krm_label = trim( (string) krm_public( isset( $krm_item['button'] ) ? $krm_item['button'] : '' ) );
	?>
<div style="<?php echo esc_attr( $krm_cards['style'][ $krm_n ] ); ?>">
	<?php echo $krm_cards['icon'][ $krm_n ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed design markup. ?>
<h3 style="margin: 16px 0 8px; font-size: 22px; font-weight: 800"><?php echo krm_text( $krm_item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<p style="margin: 0; font-size: 15px; color: <?php echo esc_attr( $krm_cards['color'][ $krm_n ] ); ?>"><?php echo krm_text( $krm_item['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php if ( '' !== $krm_label ) : ?>
<p style="margin: auto 0 0; padding-top: 24px"><a class="<?php echo esc_attr( $krm_cards['button'][ $krm_n ] ); ?>" href="<?php echo krm_url( isset( $krm_item['url'] ) ? $krm_item['url'] : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- krm_url() escapes. ?>"><?php echo krm_text( $krm_label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></p>
	<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
</section>
