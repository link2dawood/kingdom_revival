<?php
/**
 * Home: flagship ministry "Freedom from Slavery" — the five steps and the before/after photo slider
 * (assets/js/front.js moves the split).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- Flagship: Freedom from Slavery -->
<section id="freedom" style="background: var(--color-primary); color: var(--color-white)">
<div style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px)">
<div style="display: flex; flex-wrap: wrap; gap: 24px 64px; margin-bottom: 44px">
<div style="flex: 999 1 520px; min-width: 0">
<p style="margin: 0 0 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--color-accent)"><?php krm_t( 'freedom.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: clamp(34px, 4.4vw, 54px); font-weight: 800; line-height: 1.1; letter-spacing: -0.02em"><?php krm_h( 'freedom.heading' ); ?></h2>
</div>
<p style="margin: 0; flex: 1 1 340px; align-self: flex-end; color: var(--color-text-on-dark)"><?php krm_t( 'freedom.text' ); ?></p>
</div>
<ol style="list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 14px">
<?php foreach ( array_values( (array) krm_c( 'freedom.steps' ) ) as $krm_i => $krm_step ) : ?>
<li style="background: #1A2550; border-radius: 12px; padding: 22px">
<span style="display: block; font-size: 28px; font-weight: 800; color: var(--color-accent)"><?php echo esc_html( sprintf( '%02d', $krm_i + 1 ) ); ?></span>
<strong style="display: block; margin-top: 6px; font-size: 18px"><?php echo krm_text( $krm_step['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
<span style="display: block; margin-top: 6px; font-size: 14px; color: var(--color-text-on-dark)"><?php echo krm_text( $krm_step['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
</li>
<?php endforeach; ?>
</ol>
<div data-krm-compare style="position: relative; height: clamp(300px, 40vw, 520px); overflow: hidden; border-radius: 14px; margin-top: 40px; background: #2A3F85">
<div style="position: absolute; inset: 0; background: #CBD4E8; display: flex; align-items: flex-end; justify-content: flex-end; padding: 18px">
<?php if ( krm_c( 'freedom.after' ) ) : ?>
<img loading="lazy" decoding="async" <?php krm_img_src( krm_c( 'freedom.after' ), '(max-width: 1240px) 100vw, 1176px' ); ?> alt="<?php echo krm_alt( krm_c( 'freedom.after' ), krm_c( 'freedom.after_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover">
<?php endif; ?>
<span style="position: relative; background: var(--color-white); color: var(--color-text); padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 700"><?php krm_t( 'freedom.after_label' ); ?></span>
</div>
<div data-krm-compare-before style="position: absolute; inset: 0; background: #5A6275; clip-path: inset(0 50% 0 0); display: flex; align-items: flex-end; padding: 18px">
<?php if ( krm_c( 'freedom.before' ) ) : ?>
<img loading="lazy" decoding="async" <?php krm_img_src( krm_c( 'freedom.before' ), '(max-width: 1240px) 100vw, 1176px' ); ?> alt="<?php echo krm_alt( krm_c( 'freedom.before' ), krm_c( 'freedom.before_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover">
<?php endif; ?>
<span style="position: relative; background: var(--color-secondary); color: var(--color-white); padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 700"><?php krm_t( 'freedom.before_label' ); ?></span>
</div>
<div data-krm-compare-line aria-hidden="true" style="position: absolute; top: 0; bottom: 0; left: 50%; width: 3px; margin-left: -1.5px; background: var(--color-white)"></div>
<div data-krm-compare-line aria-hidden="true" style="position: absolute; top: 50%; left: 50%; width: 50px; height: 50px; margin: -25px 0 0 -25px; border-radius: 50%; background: var(--color-white); display: flex; align-items: center; justify-content: center"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0C1C4F" stroke-width="2"><path d="M9 6l-6 6 6 6M15 6l6 6-6 6"></path></svg></div>
</div>
<div style="display: flex; flex-wrap: wrap; gap: 16px 32px; align-items: center; justify-content: space-between; margin-top: 18px">
<label style="display: flex; flex: 1 1 300px; gap: 14px; align-items: center; font-size: 14px; color: var(--color-text-on-dark)"><?php krm_t( 'freedom.compare_label' ); ?>
<input type="range" min="0" max="100" value="50" data-krm-compare-range style="flex: 1 1 200px; accent-color: #E18426; min-height: 44px">
</label>
<a class="k-o" href="<?php krm_u( 'freedom.button_url' ); ?>" style="text-decoration: none; background: #E18426; color: var(--color-text); font-weight: 700; padding: 14px 24px; border-radius: 999px; min-height: 44px; box-sizing: border-box; display: inline-flex; align-items: center"><?php krm_t( 'freedom.button_label' ); ?></a>
</div>
</div>
</section>
