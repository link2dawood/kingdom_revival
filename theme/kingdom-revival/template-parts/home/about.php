<?php
/**
 * Home: about section with the photo collage.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- About -->
<section id="about" style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px); display: flex; flex-wrap: wrap; gap: 48px 64px; align-items: center">
<div style="flex: 1 1 420px; min-width: 0; position: relative; min-height: 460px">
<div style="position: absolute; left: 0; top: 0; width: 78%; height: 340px; background: var(--color-placeholder); border-radius: 10px; overflow: hidden"><img <?php krm_img_src( krm_c( 'about.image1' ), '(max-width: 900px) 75vw, 460px' ); ?> alt="<?php echo krm_alt( krm_c( 'about.image1' ), krm_c( 'about.image1_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" data-zoom="about" loading="lazy" decoding="async" style="width: 100%; height: 100%; object-fit: cover"></div>
<div style="position: absolute; right: 0; bottom: 0; width: 52%; height: 270px; background: var(--color-placeholder-2); border-radius: 10px; border: 6px solid var(--color-surface); overflow: hidden"><img <?php krm_img_src( krm_c( 'about.image2' ), '(max-width: 900px) 50vw, 310px' ); ?> alt="<?php echo krm_alt( krm_c( 'about.image2' ), krm_c( 'about.image2_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" data-zoom="about" loading="lazy" decoding="async" style="width: 100%; height: 100%; object-fit: cover"></div>
<span aria-hidden="true" style="position: absolute; left: -14px; top: -14px; width: 64px; height: 64px; border-radius: 50%; background: var(--color-orange); border: 5px solid var(--color-surface); display: flex; align-items: center; justify-content: center"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke-width="2" style="stroke: var(--color-text)"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg></span>
</div>
<div style="flex: 1 1 440px; min-width: 0">
<p style="margin: 0 0 8px; font-weight: 700; color: var(--color-secondary-text)"><?php krm_t( 'about.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: clamp(30px, 3.4vw, 42px); font-weight: 800; line-height: 1.2; letter-spacing: -0.01em"><?php krm_h( 'about.heading' ); ?></h2>
<ul style="list-style: none; margin: 24px 0 0; padding: 0; display: flex; flex-direction: column; gap: 12px">
<?php foreach ( krm_lines( krm_c( 'about.checks' ) ) as $krm_line ) : ?>
<li style="display: flex; gap: 12px; align-items: center; font-weight: 500"><span aria-hidden="true" style="flex: 0 0 24px; width: 24px; height: 24px; border-radius: 50%; background: var(--color-band); display: flex; align-items: center; justify-content: center"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke-width="3" style="stroke: var(--color-white)"><path d="M5 12l5 5L20 7"></path></svg></span><?php echo krm_text( $krm_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
<?php endforeach; ?>
</ul>
<p style="margin: 22px 0 0; color: var(--color-text-muted)"><?php krm_t( 'about.text' ); ?></p>
<a class="k-y" href="<?php krm_u( 'about.button_url' ); ?>" style="margin-top: 26px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: var(--color-secondary); color: var(--color-white); font-weight: 700; padding: 13px 24px; border-radius: 4px; min-height: 44px; box-sizing: border-box"><?php krm_t( 'about.button_label' ); ?></a>
</div>
</section>
