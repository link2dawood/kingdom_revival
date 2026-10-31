<?php
/**
 * 301 redirects from the previous site's addresses (kingdomrevivalministries.com before this theme) to the new
 * pages, editable in Kingdom Revival → Settings → Redirects.
 *
 * The list is checked before WordPress shows a page, so an old page that still exists in the database (for
 * example the old Elementor "Donate us" page) is redirected rather than shown.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default redirects: every public address in the previous site's sitemap whose address changed, including the
 * Robo Gallery photo pages (/gallery/…). Addresses that stay the same (/, /about/, /contact/) are not listed.
 */
function krm_default_redirects() {
	return implode(
		"\n",
		array(
			'/donate-us/ {donate}',
			'/latest-news-blogs/ {blog}',
			'/baptisms/ /ministries/baptisms/',
			'/bricks-kiln-slaves-children/ /ministries/brick-kiln-children/',
			'/clean-water-project/ /ministries/clean-water-project/',
			'/deliverance-and-healing-ministry/ /ministries/deliverance-and-healing/',
			'/freedom-from-slavery-missions/ /ministries/freedom-from-slavery/',
			'/free-medical-camp-for-brick-kiln-slaves-and-village-people/ /ministries/free-medical-camps/',
			'/leadership-and-discipleship/ /ministries/leadership-and-discipleship/',
			'/orphanage-and-underprivileged-children-care/ /ministries/orphan-and-children-care/',
			'/orphanages-care/ /ministries/orphan-and-children-care/',
			'/outreach-ministry/ /ministries/outreach-ministry/',
			'/outreach-evangelism/ /ministries/outreach-ministry/',
			'/urdu-bibles-distribution/ /ministries/urdu-bibles/',
			'/widows-ministry/ /ministries/widows-ministry/',
			'/sunday-school/ /ministries/children-ministry/',
			'/gallery/bricks-kiln-slaves-children/ /ministries/brick-kiln-children/',
			'/gallery/deliverance-and-healing-ministry/ /ministries/deliverance-and-healing/',
			'/gallery/leadership-and-discipleship/ /ministries/leadership-and-discipleship/',
			'/gallery/orphanages-care/ /ministries/orphan-and-children-care/',
			'/gallery/outreach-ministry/ /ministries/outreach-ministry/',
			'/gallery/widows-ministry/ /ministries/widows-ministry/',
			'/executive-message/ {about}#founder',
			'/services/ {home}#all-ministries',
			'/form/ {home}',
			'/elementor-2338/ {home}',
			'/sample-page/ {home}',
			'/hello-world/ {blog}',
			'/category/ministries/ /ministries/',
			'/category/uncategorized/ {blog}',
		)
	);
}

/**
 * Parsed redirects: normalised old path => target as typed.
 *
 * @return array<string,string>
 */
function krm_redirects() {
	$out = array();
	foreach ( preg_split( '/\R/', (string) krm_opt( 'redirects' ) ) as $line ) {
		$line = trim( $line );
		if ( '' === $line || '#' === $line[0] ) {
			continue;
		}
		$parts = preg_split( '/\s+(?:→|->)?\s*/u', $line, 2 );
		if ( 2 !== count( $parts ) || '' === trim( $parts[1] ) ) {
			continue;
		}
		$out[ krm_redirect_path( $parts[0] ) ] = trim( $parts[1] );
	}
	return $out;
}

/**
 * Normalise a path or URL for matching: lower case, no domain, no query, no trailing slash.
 *
 * @param string $path Path or URL.
 */
function krm_redirect_path( $path ) {
	$path = (string) wp_parse_url( trim( $path ), PHP_URL_PATH );
	$path = '/' . trim( strtolower( rawurldecode( $path ) ), '/' );
	return '/' === $path ? '/' : $path;
}

/**
 * Absolute URL of a redirect target: a full URL, a /path on this site or a {placeholder} (see krm_resolve_url()).
 *
 * @param string $target Target as typed.
 */
function krm_redirect_target( $target ) {
	if ( '/' === substr( $target, 0, 1 ) ) {
		return home_url( $target );
	}
	return krm_resolve_url( $target );
}

/**
 * Where an address on this site is redirected to, or '' when it is not in the Redirects list.
 *
 * @param string $url Path or URL on this site.
 */
function krm_redirect_for( $url ) {
	$map = krm_redirects();
	if ( ! $map ) {
		return '';
	}
	// Path relative to the site (handles WordPress installed in a sub-folder).
	$request = krm_redirect_path( $url );
	$base    = krm_redirect_path( home_url( '/' ) );
	if ( '/' !== $base && 0 === strpos( $request, $base ) ) {
		$request = krm_redirect_path( substr( $request, strlen( $base ) ) );
	}
	if ( ! isset( $map[ $request ] ) ) {
		return '';
	}
	$to = krm_redirect_target( $map[ $request ] );
	if ( ! $to || krm_redirect_path( $to ) === krm_redirect_path( home_url( $request ) ) ) {
		return '';
	}
	return $to;
}

add_action(
	'template_redirect',
	function () {
		if ( is_admin() || wp_doing_ajax() || ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}
		$to = krm_redirect_for( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
		if ( ! $to ) {
			return;
		}
		// Targets are entered by administrators and may point to another site, so wp_safe_redirect() is not used.
		wp_redirect( esc_url_raw( $to ), 301, 'Kingdom Revival theme' ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
		exit;
	},
	0
);
