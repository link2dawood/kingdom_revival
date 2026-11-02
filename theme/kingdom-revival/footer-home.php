<?php
/**
 * Footer for the home page (the home page design's own footer).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- Footer -->
<footer id="contact" style="background: #08133A; color: #C8D0E6">
<div style="max-width: 1240px; margin: 0 auto; padding: clamp(56px, 6vw, 80px) clamp(16px, 3vw, 32px) 28px">
<div style="display: flex; flex-wrap: wrap; gap: 40px 56px">
<div style="flex: 2 1 300px; min-width: 0">
<div style="display: flex; gap: 14px; align-items: center">
<span style="flex: 0 0 76px; width: 76px; height: 76px; border-radius: 50%; background: var(--color-white); display: flex; align-items: center; justify-content: center"><img src="<?php echo esc_url( krm_logo_url() ); ?>" alt="" width="64" height="64" style="width: 64px; height: 64px; display: block; object-fit: contain"></span>
<p style="margin: 0; font-weight: 800; font-size: 22px; line-height: 1.2; color: var(--color-white)"><?php krm_t( 'footer.name', 'site' ); ?></p>
</div>
<p style="margin: 10px 0 0; max-width: 360px; font-size: 15px"><?php krm_t( 'footer.tagline_home', 'site' ); ?></p>
</div>
<div style="flex: 1 1 160px; min-width: 0">
<h3 style="margin: 0 0 12px; font-size: 16px; font-weight: 700; color: var(--color-white)"><?php krm_t( 'footer.quick_heading', 'site' ); ?></h3>
<div style="display: flex; flex-direction: column; gap: 8px; font-size: 15px">
<?php foreach ( krm_footer_links( 'footer_home' ) as $krm_link ) : ?>
<a href="<?php echo esc_url( krm_local_url( $krm_link['url'] ) ); ?>" style="color: #C8D0E6; text-decoration: none"><?php echo esc_html( $krm_link['title'] ); ?></a>
<?php endforeach; ?>
</div>
</div>
<div style="flex: 1 1 260px; min-width: 0">
<h3 style="margin: 0 0 12px; font-size: 16px; font-weight: 700; color: var(--color-white)"><?php krm_t( 'footer.contact_heading', 'site' ); ?></h3>
<address style="font-style: normal; display: flex; flex-direction: column; gap: 8px; font-size: 15px">
<span><?php echo esc_html( krm_opt( 'address' ) ); ?></span>
<a href="<?php echo esc_url( krm_tel() ); ?>" style="color: var(--color-white)"><?php echo esc_html( krm_opt( 'phone' ) ); ?></a>
<a href="<?php echo esc_url( krm_mailto() ); ?>" style="color: var(--color-white)"><?php echo esc_html( krm_opt( 'email' ) ); ?></a>
</address>
</div>
</div>
<div style="margin-top: 48px; padding-top: 20px; border-top: 1px solid #22306A; font-size: 13px; display: flex; flex-wrap: wrap; gap: 8px 24px; justify-content: space-between">
<span><?php krm_t( 'footer.copyright', 'site' ); ?></span>
<span><?php krm_t( 'footer.location', 'site' ); ?></span>
</div>
</div>
</footer>

</div>
<?php wp_footer(); ?>
</body>
</html>
