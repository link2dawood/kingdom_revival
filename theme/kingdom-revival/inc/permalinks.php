<?php
/**
 * Addresses without a trailing slash (theme 1.22.7): /about, /contact, /what-we-do/compassion-humanitarian-ministries,
 * /news/a-story, not /about/.
 *
 * WordPress itself builds every page, post, ministry and archive link from the permalink structure, which is now
 * "/news/%postname%" (no slash at the end), and its canonical redirect sends an old "/about/" to "/about" with a 301.
 * This file covers what WordPress does not:
 *  - krm_link_slash(): links the theme writes as text — {placeholder} links, links saved in Page content, custom menu
 *    links, redirect targets — follow the same structure (query and #anchor kept; files, wp-admin, wp-json untouched).
 *  - The theme's own routes (ministry group pages, /projects/…) answer with or without the slash, so a request with the
 *    other form is redirected (301) to the one form.
 * Everything follows the permalink setting: with a structure ending in "/" the slash is added instead.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * The permalink structure the theme sets: stories under /news/, no trailing slash.
 */
function krm_permalink_structure() {
	return '/news/%postname%';
}

/**
 * Give an internal link the trailing slash form of the permalink structure (none for "/news/%postname%").
 * External links, mailto:/tel:, "#anchor" / "?query" only links, the home page, files (".pdf") and WordPress system
 * paths (/wp-admin, /wp-content, /wp-json…) are returned unchanged.
 *
 * @param string $url Link.
 * @return string
 */
function krm_link_slash( $url ) {
	if ( ! is_string( $url ) || '' === $url || ! get_option( 'permalink_structure' ) ) {
		return $url;
	}
	$p = wp_parse_url( $url );
	if ( false === $p || empty( $p['path'] ) ) {
		return $url;
	}
	$home = wp_parse_url( home_url( '/' ) );
	if ( isset( $p['host'] ) ) {
		if ( empty( $home['host'] ) || strtolower( $p['host'] ) !== strtolower( $home['host'] ) ) {
			return $url; // Another site.
		}
	} elseif ( isset( $p['scheme'] ) || '/' !== $p['path'][0] ) {
		return $url; // mailto:, tel:, or a relative link.
	}
	$path = $p['path'];
	$base = untrailingslashit( isset( $home['path'] ) ? $home['path'] : '' );
	$bare = untrailingslashit( $path );
	if ( $bare === $base || preg_match( '#\.[a-z0-9]{2,5}$#i', $bare ) || false !== strpos( $bare . '/', '/wp-' ) ) {
		return $url; // Home page, a file, or WordPress itself.
	}
	$new = user_trailingslashit( $bare );
	if ( $new === $path ) {
		return $url;
	}
	// Replace only the path (after the host, if there is one), keeping ?query and #anchor.
	$from = isset( $p['host'] ) ? stripos( $url, $p['host'] ) + strlen( $p['host'] ) : 0;
	$at   = strpos( $url, $path, $from );
	return false === $at ? $url : substr_replace( $url, $new, $at, strlen( $path ) );
}

// Custom menu links (stored as typed, e.g. ".../what-we-do/gospel-spiritual-ministries/").
add_filter(
	'nav_menu_link_attributes',
	function ( $atts ) {
		if ( isset( $atts['href'] ) ) {
			$atts['href'] = krm_link_slash( $atts['href'] );
		}
		return $atts;
	}
);

/*
 * One address per page: a GET request whose path has the other trailing-slash form is redirected (301). WordPress's
 * canonical redirect (priority 10) already does this for its own pages; this catches the theme's routes (ministry
 * group pages, /projects/…) and anything else it leaves alone.
 */
add_action(
	'template_redirect',
	function () {
		if ( is_admin() || wp_doing_ajax() || ! get_option( 'permalink_structure' ) || ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}
		$method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_key( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : 'GET';
		if ( 'GET' !== $method && 'HEAD' !== $method ) {
			return;
		}
		if ( is_preview() || is_feed() || is_robots() || is_trackback() || is_search() ) {
			return;
		}
		$uri  = wp_unslash( $_SERVER['REQUEST_URI'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- compared, then rebuilt with home_url() and esc_url_raw().
		$path = (string) wp_parse_url( $uri, PHP_URL_PATH );
		$home = wp_parse_url( home_url( '/' ) );
		if ( '' === $path || empty( $home['host'] ) ) {
			return;
		}
		// Origin of the site (scheme, host, port); the request path already holds any sub-folder.
		$origin = $home['scheme'] . '://' . $home['host'] . ( isset( $home['port'] ) ? ':' . $home['port'] : '' );
		$want   = (string) wp_parse_url( krm_link_slash( $origin . $path ), PHP_URL_PATH );
		if ( '' === $want || $want === $path ) {
			return;
		}
		$query = (string) wp_parse_url( $uri, PHP_URL_QUERY );
		wp_safe_redirect( esc_url_raw( $origin . $want . ( '' !== $query ? '?' . $query : '' ) ), 301, 'Kingdom Revival' );
		exit;
	},
	11
);
