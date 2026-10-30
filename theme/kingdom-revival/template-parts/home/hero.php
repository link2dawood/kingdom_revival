<?php
/**
 * Home: hero with background video and the "Make an impact" donation card.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- Hero -->
<section id="top" style="position: relative; background: #1A2550 url('<?php echo esc_url( krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg' ) ); ?>') center/cover no-repeat">
<video aria-hidden="true" autoplay muted loop playsinline preload="none" poster="<?php echo esc_url( krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg' ) ); ?>" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover"><source src="<?php echo esc_url( krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/a.mp4' ) ); ?>" type="video/mp4"></video>
<div aria-hidden="true" style="position: absolute; inset: 0; background: rgba(8,16,48,0.62)"></div>

<div style="position: relative; max-width: 1240px; margin: 0 auto; padding: clamp(56px, 7vw, 100px) clamp(16px, 3vw, 32px) clamp(110px, 10vw, 150px); display: flex; flex-wrap: wrap; gap: 40px 56px; align-items: center">
<div style="flex: 999 1 520px; min-width: 0; color: #FFFFFF">
<p style="margin: 0 0 14px; font-weight: 700; color: #F5A04A">Bringing Hope to the Forgotten</p>
<h1 style="margin: 0; font-weight: 800; font-size: clamp(38px, 5vw, 60px); line-height: 1.12; letter-spacing: -0.02em">Sharing the Hope of <span style="color: #F5A04A">Jesus Christ</span> with the Unreached &amp; <span style="color: #F5A04A">Vulnerable</span></h1>
<p style="margin: 20px 0 0; max-width: 520px; font-size: 17px; color: #E4E8F3">We preach the Gospel, plant churches and care for brick kiln families, orphans and widows across Pakistan and beyond.</p>
<div style="display: flex; flex-wrap: wrap; gap: 16px 24px; align-items: center; margin-top: 30px">
<a class="k-g" href="#ministries" style="text-decoration: none; background: #0C1C4F; color: #FFFFFF; padding: 14px 24px; border-radius: 4px; font-weight: 700; min-height: 44px; box-sizing: border-box; display: inline-flex; gap: 8px; align-items: center; border: 1px solid #2A3F85">Explore Ministries</a>
<a href="#field-stories" style="text-decoration: none; color: #FFFFFF; font-weight: 600; display: inline-flex; gap: 12px; align-items: center; min-height: 44px"><span style="width: 46px; height: 46px; border-radius: 50%; background: #FFFFFF; display: flex; align-items: center; justify-content: center"><svg width="16" height="16" viewBox="0 0 24 24" fill="#0C1C4F" aria-hidden="true"><path d="M7 4l13 8-13 8z"></path></svg></span>Watch Our Story</a>
</div>
</div>

<!-- Donation card -->
<div id="donate" style="flex: 1 1 340px; max-width: 400px; min-width: 0; background: #FFFFFF; border-radius: 10px; padding: 26px; box-shadow: 0 20px 50px rgba(0,0,0,0.25)">
<span style="display: inline-block; background: #0C1C4F; color: #FFFFFF; font-size: 11px; font-weight: 700; letter-spacing: 0.06em; padding: 4px 10px; border-radius: 3px">MAKE AN IMPACT</span>
<h2 style="margin: 12px 0 8px; font-size: 21px; font-weight: 800; line-height: 1.3">Help Brick Kiln Children Go to School &amp; Know Christ</h2>
<p style="margin: 0; font-size: 14px; color: #5A6275">Your gift provides Sunday school, education and care for children growing up beside the kilns.</p>
<p style="margin: 16px 0 0; font-size: 13px; line-height: 1.6; color: #5A6275">Every gift helps children learn, grow in faith and build a brighter future.</p>
<div role="group" aria-label="Donation amount" style="display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 6px; margin-top: 18px">
<?php
foreach ( array( '$25', '$50', '$100', '$250', 'Other' ) as $krm_a ) :
	$krm_on = '$50' === $krm_a;
	?>
<button type="button" data-amount="<?php echo esc_attr( $krm_a ); ?>" aria-pressed="<?php echo $krm_on ? 'true' : 'false'; ?>" style="font: 600 14px 'Plus Jakarta Sans', sans-serif; min-height: 44px; border-radius: 4px; cursor: pointer; border: 1px solid <?php echo $krm_on ? '#0C1C4F' : '#D3D9E6'; ?>; background: <?php echo $krm_on ? '#0C1C4F' : '#FFFFFF'; ?>; color: <?php echo $krm_on ? '#FFFFFF' : '#141B33'; ?>"><?php echo esc_html( $krm_a ); ?></button>
<?php endforeach; ?>
</div>
<a class="k-y" data-krm-donate="hero" href="<?php echo esc_url( krm_home_donate_href( '$50' ) ); ?>" style="margin-top: 14px; text-decoration: none; display: flex; justify-content: center; align-items: center; gap: 8px; background: #D4070B; color: #FFFFFF; font-weight: 700; padding: 14px; border-radius: 4px; min-height: 44px; box-sizing: border-box">Donate <span>$50</span></a>
</div>
</div>
</section>
