<?php
/**
 * Header for the home page: the home page design's own top bar and header (inline styles kept as designed).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0C1C4F">
<?php if ( ! has_site_icon() ) : ?>
<link rel="icon" type="image/png" href="<?php echo esc_url( krm_asset( 'images/krm-logo.png' ) ); ?>">
<link rel="apple-touch-icon" href="<?php echo esc_url( krm_asset( 'images/krm-logo.png' ) ); ?>">
<?php endif; ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>
<div style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: #141B33; background: #FFFFFF; font-size: 16px; line-height: 1.6">

<!-- Top bar -->
<div style="background: #0C1C4F; color: #E6EAF5; font-size: 13px">
<div style="max-width: 1240px; margin: 0 auto; padding: 8px clamp(16px, 3vw, 32px); display: flex; flex-wrap: wrap; gap: 8px 24px; align-items: center; justify-content: space-between">
<div style="display: flex; flex-wrap: wrap; gap: 6px 24px">
<a href="<?php echo esc_url( krm_tel() ); ?>" style="color: #E6EAF5; text-decoration: none; display: inline-flex; gap: 8px; align-items: center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"></path></svg><?php echo esc_html( krm_opt( 'phone' ) ); ?></a>
<a href="<?php echo esc_url( krm_mailto() ); ?>" style="color: #E6EAF5; text-decoration: none; display: inline-flex; gap: 8px; align-items: center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M22 6l-10 7L2 6"></path></svg><?php echo esc_html( krm_opt( 'email' ) ); ?></a>
</div>
<div style="display: flex; gap: 16px; align-items: center">
<a <?php krm_social_href( 'facebook' ); ?> aria-label="Facebook" style="color: #E6EAF5; display: inline-flex"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
<a <?php krm_social_href( 'youtube' ); ?> aria-label="YouTube" style="color: #E6EAF5; display: inline-flex"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="4"></rect><path d="M10 9l5 3-5 3z"></path></svg></a>
<a <?php krm_social_href( 'instagram' ); ?> aria-label="Instagram" style="color: #E6EAF5; display: inline-flex"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"></rect><circle cx="12" cy="12" r="4"></circle></svg></a>
<a class="k-y" href="#donate" style="background: #D4070B; color: #FFFFFF; text-decoration: none; font-weight: 700; padding: 6px 14px; border-radius: 4px"><?php krm_t( 'header.donate_label', 'site' ); ?></a>
</div>
</div>
</div>

<!-- Nav -->
<header style="background: #FFFFFF; border-bottom: 1px solid #E3E7F0">
<div class="k-nav" style="position: relative; max-width: 1240px; margin: 0 auto; padding: 14px clamp(16px, 3vw, 32px); display: flex; flex-wrap: wrap; gap: 12px 32px; align-items: center; justify-content: space-between">
<a href="#top" style="display: flex; gap: 10px; align-items: center">
<img src="<?php echo esc_url( krm_logo_url() ); ?>" alt="<?php echo esc_attr( krm_fill( krm_c( 'header.logo_alt', 'site' ) ) ); ?>" width="64" height="64" style="width: 64px; height: 64px; display: block; object-fit: contain">
<span style="display: flex; flex-direction: column; line-height: 1.05"><span style="font-weight: 800; font-size: 20px; color: #0C1C4F"><?php krm_t( 'header.brand_name', 'site' ); ?></span><span style="font-size: 11px; font-weight: 600; letter-spacing: 0.12em; color: #5A6275"><?php krm_t( 'header.brand_sub', 'site' ); ?></span></span>
</a>
<nav class="site-nav" aria-label="Main">
<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-menu"><span class="menu-toggle-bars" aria-hidden="true"></span>Menu</button>
<?php krm_primary_menu(); ?>
</nav>
</div>
</header>
