<?php
/**
 * Home: flagship cards (Bible Distribution, Church Planting).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_buttons = array(
	array( 'k-y', 'var(--color-secondary)' ),
	array( 'k-g', 'var(--color-primary)' ),
);
?>
<!-- Flagship: Bibles + Church planting -->
<section style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px); display: grid; grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr)); gap: 26px">
<?php
foreach ( array_values( (array) krm_c( 'flagship.cards' ) ) as $krm_i => $krm_card ) :
	$krm_button = $krm_buttons[ $krm_i % 2 ];
	?>
<article style="border-radius: 14px; overflow: hidden; background: var(--color-bg-soft); display: flex; flex-direction: column">
<div style="position: relative; height: 260px; background: <?php echo esc_attr( $krm_i % 2 ? '#CBD4E8' : '#DDE3F0' ); ?>">
	<?php if ( $krm_card['image'] ) : ?>
<img loading="lazy" decoding="async" <?php krm_img_src( $krm_card['image'], '(max-width: 760px) 100vw, 600px', true ); ?> alt="<?php echo krm_alt( $krm_card['image'], $krm_card['image_alt'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover">
	<?php endif; ?>
</div>
<div style="padding: 28px; display: flex; flex-direction: column; gap: 10px; flex: 1 1 auto">
<p style="margin: 0; font-size: 13px; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: var(--color-secondary)"><?php echo krm_text( $krm_card['eyebrow'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<h3 style="margin: 0; font-size: 26px; font-weight: 800; color: var(--color-primary)"><?php echo krm_text( $krm_card['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<p style="margin: 0; color: var(--color-text-muted)"><?php echo krm_text( $krm_card['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php if ( '' !== trim( (string) $krm_card['button_label'] ) ) : ?>
<a class="<?php echo esc_attr( $krm_button[0] ); ?>" href="<?php echo krm_url( $krm_card['button_url'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" style="margin-top: auto; align-self: flex-start; text-decoration: none; background: <?php echo esc_attr( $krm_button[1] ); ?>; color: var(--color-white); font-weight: 700; padding: 12px 22px; border-radius: 6px; min-height: 44px; box-sizing: border-box; display: inline-flex; align-items: center"><?php echo krm_text( $krm_card['button_label'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	<?php endif; ?>
</div>
</article>
<?php endforeach; ?>
</section>
