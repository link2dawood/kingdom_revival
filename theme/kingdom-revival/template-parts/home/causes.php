<?php
/**
 * Home: "You Can Bring Hope to Many by Giving a Little" cause cards.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- Ministries / causes -->
<section id="ministries" style="background: #F4F6FB">
<div style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px)">
<div style="text-align: center; max-width: 720px; margin: 0 auto 48px">
<p style="margin: 0 0 8px; font-weight: 700; color: #D4070B">Our Ministries</p>
<h2 style="margin: 0; font-size: clamp(30px, 3.4vw, 42px); font-weight: 800; line-height: 1.2; letter-spacing: -0.01em">You Can Bring Hope to Many by Giving a Little</h2>
</div>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 26px">
<?php foreach ( krm_home_causes() as $krm_i => $krm_c ) : ?>
<article class="k-card" style="background: #FFFFFF; border-radius: 10px; overflow: hidden; box-shadow: 0 6px 20px rgba(12,28,79,0.07); display: flex; flex-direction: column">
<div style="position: relative; height: 210px; background: <?php echo esc_attr( $krm_i % 2 ? '#CBD4E8' : '#DDE3F0' ); ?>; display: flex; align-items: flex-start; justify-content: flex-end; padding: 10px; box-sizing: border-box">
<img loading="lazy" decoding="async" <?php krm_img_src( $krm_c[2], '(max-width: 760px) 100vw, 400px' ); ?> alt="<?php echo esc_attr( $krm_c[1] ); ?>" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover">
<span style="position: absolute; left: 14px; bottom: 14px; background: #0C1C4F; color: #FFFFFF; font-size: 11px; font-weight: 700; letter-spacing: 0.06em; padding: 4px 10px; border-radius: 3px"><?php echo esc_html( $krm_c[0] ); ?></span>
</div>
<div style="padding: 22px; display: flex; flex-direction: column; gap: 10px; flex: 1 1 auto">
<h3 style="margin: 0; font-size: 19px; font-weight: 800; line-height: 1.3"><?php echo esc_html( $krm_c[1] ); ?></h3>
<p style="margin: 0; font-size: 14px; color: #5A6275"><?php echo esc_html( $krm_c[3] ); ?></p>
<p style="margin: auto 0 0; padding-top: 8px; font-size: 13px; line-height: 1.6; color: #5A6275">Your gift brings practical care and lasting hope through this ministry.</p>
<a class="k-y" href="<?php echo esc_url( krm_page_url( 'donate' ) ); ?>" style="margin-top: 8px; align-self: flex-start; text-decoration: none; display: inline-flex; gap: 8px; align-items: center; background: #D4070B; color: #FFFFFF; font-weight: 700; font-size: 14px; padding: 10px 18px; border-radius: 4px; min-height: 44px; box-sizing: border-box">Donate Now</a>
</div>
</article>
<?php endforeach; ?>
</div>
</div>
</section>
