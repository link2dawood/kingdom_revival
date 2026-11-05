<?php
/**
 * Home: "You Can Bring Hope to Many by Giving a Little" cause cards.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- Ministries / causes -->
<section id="ministries" style="background: var(--color-bg-soft)">
<div style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px)">
<div style="text-align: center; max-width: 720px; margin: 0 auto 48px">
<p style="margin: 0 0 8px; font-weight: 700; color: var(--color-secondary-text)"><?php krm_t( 'causes.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: clamp(30px, 3.4vw, 42px); font-weight: 800; line-height: 1.2; letter-spacing: -0.01em"><?php krm_h( 'causes.heading' ); ?></h2>
</div>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 26px">
<?php foreach ( (array) krm_c( 'causes.cards' ) as $krm_i => $krm_card ) : ?>
<article class="k-card" style="background: var(--color-surface); border-radius: 10px; overflow: hidden; box-shadow: 0 6px 20px rgba(var(--shadow-rgb), calc(0.07 * var(--shadow-k))); display: flex; flex-direction: column">
<div style="position: relative; height: 210px; background: <?php echo esc_attr( $krm_i % 2 ? 'var(--color-placeholder-2)' : 'var(--color-placeholder)' ); ?>; display: flex; align-items: flex-start; justify-content: flex-end; padding: 10px; box-sizing: border-box">
<img loading="lazy" decoding="async" <?php krm_img_src( $krm_card['image'], '(max-width: 760px) 100vw, 400px', true ); ?> alt="<?php echo krm_alt( $krm_card['image'], $krm_card['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center 20%">
<span style="position: absolute; left: 14px; bottom: 14px; background: var(--color-band); color: var(--color-white); font-size: 11px; font-weight: 700; letter-spacing: 0.06em; padding: 4px 10px; border-radius: 3px"><?php echo krm_text( $krm_card['tag'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
</div>
<div style="padding: 22px; display: flex; flex-direction: column; gap: 10px; flex: 1 1 auto">
<h3 style="margin: 0; font-size: 19px; font-weight: 800; line-height: 1.3"><?php echo krm_text( $krm_card['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<p style="margin: 0; font-size: 14px; color: var(--color-text-muted)"><?php echo krm_text( $krm_card['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<p style="margin: auto 0 0; padding-top: 8px; font-size: 13px; line-height: 1.6; color: var(--color-text-muted)"><?php krm_t( 'causes.note' ); ?></p>
<a class="k-y" href="<?php krm_u( 'causes.button_url' ); ?>" style="margin-top: 8px; align-self: flex-start; text-decoration: none; display: inline-flex; gap: 8px; align-items: center; background: var(--color-secondary); color: var(--color-white); font-weight: 700; font-size: 14px; padding: 10px 18px; border-radius: 4px; min-height: 44px; box-sizing: border-box"><?php krm_t( 'causes.button_label' ); ?></a>
</div>
</article>
<?php endforeach; ?>
</div>
</div>
</section>
