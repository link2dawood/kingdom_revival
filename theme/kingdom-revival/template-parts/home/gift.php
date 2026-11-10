<?php
/**
 * Home: "Your gift at work" amount cards. Visitors see only cards with a real amount (none: no section).
 *
 * @param array $args { screen: content screen to read (default: the current page's; 'home' elsewhere). }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_screen = isset( $args['screen'] ) ? (string) $args['screen'] : ''; // 'home' when shown on another page.
$krm_cards  = krm_visible( krm_c( 'gift.cards', $krm_screen ), 'amount' ); // Visitors: only cards with a real amount.
if ( ! $krm_cards ) {
	return;
}
$krm_hidden = ! array_filter(
	$krm_cards,
	function ( $c ) {
		return ! krm_is_placeholder( $c['amount'] );
	}
);
?>
<!-- Your gift at work -->
<section aria-labelledby="gift-h" style="max-width: 1240px; margin: 0 auto; padding: clamp(64px, 7vw, 96px) clamp(16px, 3vw, 32px) 0">
<?php krm_admin_only_note( $krm_hidden ); ?>
<div style="display: flex; flex-wrap: wrap; gap: 12px 48px; justify-content: space-between; align-items: flex-end; margin-bottom: 28px">
<h2 id="gift-h" style="margin: 0; font-size: clamp(26px, 2.8vw, 34px); font-weight: 800; color: var(--color-heading)"><?php krm_t( 'gift.heading', $krm_screen ); ?></h2>
<p style="margin: 0; color: var(--color-text-muted)"><?php krm_t( 'gift.text', $krm_screen ); ?></p>
</div>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px">
<?php foreach ( $krm_cards as $krm_card ) : ?>
<a href="<?php echo krm_url( $krm_card['url'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" style="text-decoration: none; border: 1px solid var(--color-border); border-radius: 12px; padding: 24px; display: flex; flex-direction: column; gap: 6px">
<span style="font-size: 34px; font-weight: 800; color: var(--color-secondary-text); letter-spacing: -0.02em"><?php echo krm_text( $krm_card['amount'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<span style="font-weight: 700; color: var(--color-text)"><?php echo krm_text( $krm_card['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<span style="font-size: 14px; color: var(--color-text-muted)"><?php echo krm_text( $krm_card['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
</a>
<?php endforeach; ?>
</div>
</section>
