<?php
/**
 * The global header (topbar + main menu) for every page, the home page included. The menu is the "Primary menu"
 * location (Appearance → Menus), printed by krm_primary_menu() in inc/nav.php.
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
	<?php krm_theme_head(); ?>
	<?php if ( ! has_site_icon() ) : ?>
	<link rel="icon" type="image/png" href="<?php echo esc_url( krm_asset( 'images/krm-logo.png' ) ); ?>">
	<link rel="apple-touch-icon" href="<?php echo esc_url( krm_asset( 'images/krm-logo.png' ) ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div class="krm-progress is-idle" aria-hidden="true"></div>
	<a class="skip-link" href="#main">Skip to content</a>
	<div class="topbar" role="region" aria-label="Contact and giving">
	<div class="wrap topbar-inner">
		<div class="topbar-contacts">
		<a href="<?php echo esc_url( krm_tel() ); ?>"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"></path></svg><?php echo esc_html( krm_opt( 'phone' ) ); ?></a>
		<a class="topbar-email" href="<?php echo esc_url( krm_mailto() ); ?>"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M22 6l-10 7L2 6"></path></svg><?php echo esc_html( krm_opt( 'email' ) ); ?></a>
		</div>
		<?php if ( '' !== trim( (string) krm_c( 'header.verse_short', 'site' ) ) ) : ?>
		<div class="topbar-verse" data-krm-verse>
			<button class="topbar-verse-btn" type="button" aria-expanded="false" aria-controls="topbar-verse-full"><span class="topbar-verse-text">&ldquo;<?php krm_t( 'header.verse_short', 'site' ); ?>&rdquo;</span> <cite>&mdash; <?php krm_t( 'header.verse_ref', 'site' ); ?></cite></button>
			<p class="topbar-verse-full" id="topbar-verse-full">&ldquo;<?php krm_t( 'header.verse_full', 'site' ); ?>&rdquo; <cite>&mdash; <?php krm_t( 'header.verse_ref', 'site' ); ?></cite></p>
		</div>
		<?php endif; ?>
		<?php $krm_social = krm_social_links(); ?>
		<?php if ( $krm_social ) : ?>
		<div class="topbar-actions">
			<?php foreach ( $krm_social as $krm_network => $krm_link ) : ?>
			<a class="topbar-social" href="<?php echo esc_url( $krm_link[1] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $krm_link[0] . " (opens in a new tab)" ); ?>"><?php echo krm_icon( $krm_network ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<?php endforeach; ?>
			<a class="donate-link" href="<?php echo esc_url( krm_donate_url() ); ?>"><?php krm_t( 'header.donate_label', 'site' ); ?></a>
		</div>
		<?php else : ?>
		<a class="donate-link" href="<?php echo esc_url( krm_donate_url() ); ?>"><?php krm_t( 'header.donate_label', 'site' ); ?></a>
		<?php endif; ?>
	</div>
	</div>
	<header class="site-header">
	<div class="wrap header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php krm_logo_img( 64, '(max-width: 560px) 52px, 64px', krm_fill( krm_c( 'header.logo_alt', 'site' ) ) ); ?>
		<span class="brand-text"><strong><?php krm_t( 'header.brand_name', 'site' ); ?></strong><small><?php krm_t( 'header.brand_sub', 'site' ); ?></small></span>
		</a>
		<nav class="site-nav" aria-label="Main">
		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-menu"><span class="menu-toggle-bars" aria-hidden="true"></span>Menu</button>
		<?php krm_primary_menu(); ?>
		</nav>
		<?php krm_theme_toggle(); ?>
	</div>
	</header>
