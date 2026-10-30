<?php
/**
 * Header for inner pages (about, ministries, stories, contact, donate, partner…).
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
	<div class="topbar">
	<div class="wrap topbar-inner">
		<div class="topbar-contacts">
		<a href="<?php echo esc_url( krm_tel() ); ?>"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"></path></svg><?php echo esc_html( krm_opt( 'phone' ) ); ?></a>
		<a class="topbar-email" href="<?php echo esc_url( krm_mailto() ); ?>"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M22 6l-10 7L2 6"></path></svg><?php echo esc_html( krm_opt( 'email' ) ); ?></a>
		</div>
		<a class="donate-link" href="<?php echo esc_url( krm_donate_url() ); ?>"><?php krm_t( 'header.donate_label', 'site' ); ?></a>
	</div>
	</div>
	<header class="site-header">
	<div class="wrap header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<img src="<?php echo esc_url( krm_logo_url() ); ?>" alt="<?php echo esc_attr( krm_fill( krm_c( 'header.logo_alt', 'site' ) ) ); ?>" width="64" height="64">
		<span class="brand-text"><strong><?php krm_t( 'header.brand_name', 'site' ); ?></strong><small><?php krm_t( 'header.brand_sub', 'site' ); ?></small></span>
		</a>
		<nav class="site-nav" aria-label="Main">
		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-menu"><span class="menu-toggle-bars" aria-hidden="true"></span>Menu</button>
		<?php krm_primary_menu(); ?>
		</nav>
	</div>
	</header>
<?php if ( is_singular( 'post' ) ) : ?>
	<div class="progress" aria-hidden="true"><span></span></div>
<?php endif; ?>
