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
			'/baptisms/ /what-we-do/baptisms-discipleship/',
			'/bricks-kiln-slaves-children/ /what-we-do/brick-kiln-family-care/',
			'/clean-water-project/ /what-we-do/clean-water/',
			'/deliverance-and-healing-ministry/ /what-we-do/prayer-deliverance/',
			'/freedom-from-slavery-missions/ /what-we-do/slavery-freedom/',
			'/free-medical-camp-for-brick-kiln-slaves-and-village-people/ /what-we-do/medical-health/',
			'/leadership-and-discipleship/ /what-we-do/leadership-training/',
			'/orphanage-and-underprivileged-children-care/ /what-we-do/children-orphan-care/',
			'/orphanages-care/ /what-we-do/children-orphan-care/',
			'/outreach-ministry/ /what-we-do/evangelism-outreach/',
			'/outreach-evangelism/ /what-we-do/evangelism-outreach/',
			'/urdu-bibles-distribution/ /what-we-do/bible-distribution/',
			'/widows-ministry/ /what-we-do/widows-support/',
			'/sunday-school/ /what-we-do/children-orphan-care/',
			'/gallery/bricks-kiln-slaves-children/ /what-we-do/brick-kiln-family-care/',
			'/gallery/deliverance-and-healing-ministry/ /what-we-do/prayer-deliverance/',
			'/gallery/leadership-and-discipleship/ /what-we-do/leadership-training/',
			'/gallery/orphanages-care/ /what-we-do/children-orphan-care/',
			'/gallery/outreach-ministry/ /what-we-do/evangelism-outreach/',
			'/gallery/widows-ministry/ /what-we-do/widows-support/',
			'/executive-message/ {about}#founder',
			'/services/ {ministries}',
			'/form/ {home}',
			'/elementor-2338/ {home}',
			'/sample-page/ {home}',
			'/hello-world/ {blog}',
			'/category/ministries/ {ministries}',
			'/category/uncategorized/ {blog}',
		)
	);
}

/**
 * Addresses this theme itself changed (theme 1.10.0, the client's sitemap): old path => new path or {placeholder}.
 * Used only when the old address no longer exists (WordPress would show "not found"), so a page someone has
 * since created at one of these addresses is never redirected away. Includes every ministry's earlier slug.
 */
function krm_theme_renames() {
	$map = array(
		'/ministries' => '{ministries}',
		'/blog'       => '{blog}',
		'/partner'    => '{partner}',
		'/prayer-request' => '{prayer}',
		'/partner-with-us/prayer-request' => '{prayer}',
		'/impact'         => '{impact}',
	);
	foreach ( krm_ministry_data()['items'] as $slug => $m ) {
		$map[ '/ministries/' . $slug ] = '/what-we-do/' . $slug . '/';
		foreach ( $m['was'] as $old ) {
			$map[ '/ministries/' . $old ] = '/what-we-do/' . $slug . '/';
			$map[ '/what-we-do/' . $old ] = '/what-we-do/' . $slug . '/';
		}
	}
	return $map;
}

/**
 * Stories moved to /news/{story}/ (theme 1.10.0): the new address of an old /{story}/ address, and of the old
 * category and tag lists (/category/…, /tag/… → /news/category/…, /news/tag/…). '' when nothing matches or when the
 * permalinks do not put stories under a base.
 *
 * @param string $url Path or URL on this site.
 */
function krm_moved_post_url( $url ) {
	$structure = (string) get_option( 'permalink_structure' );
	if ( '' === $structure || 0 === strpos( $structure, '/%' ) ) {
		return '';
	}
	$path = krm_redirect_path( $url );
	$base = krm_redirect_path( home_url( '/' ) );
	if ( '/' !== $base && 0 === strpos( $path, $base ) ) {
		$path = krm_redirect_path( substr( $path, strlen( $base ) ) );
	}
	if ( preg_match( '#^/([a-z0-9-]+)$#', $path, $m ) ) {
		$post = get_page_by_path( $m[1], OBJECT, 'post' );
		return $post && 'publish' === $post->post_status ? get_permalink( $post ) : '';
	}
	if ( preg_match( '#^/(category|tag)/([a-z0-9/-]+)$#', $path, $m ) ) {
		$term = get_term_by( 'slug', basename( $m[2] ), 'category' === $m[1] ? 'category' : 'post_tag' );
		$link = $term ? get_term_link( $term ) : '';
		return is_string( $link ) ? $link : '';
	}
	return '';
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
	// Lists saved before 1.10.0 point at /ministries/{old slug}/: send those straight to the new address.
	$renames = krm_theme_renames();
	$path    = krm_redirect_path( $target );
	if ( '/' === substr( $target, 0, 1 ) && isset( $renames[ $path ] ) && 0 === strpos( $path, '/ministries' ) ) {
		$target = $renames[ $path ];
	}
	// A ministry address of an earlier layout (/what-we-do/{slug}/, theme 1.10–1.15) goes straight to the ministry's
	// current address (/what-we-do/{group}/{slug}/, Phase 21), so each old link takes one hop.
	$path = krm_redirect_path( $target );
	if ( '/' === substr( $target, 0, 1 ) && preg_match( '#^/' . preg_quote( krm_wwd_base(), '#' ) . '/([a-z0-9-]+)$#', $path, $m ) ) {
		$ministry = krm_get_by_path( $m[1], 'ministry' );
		if ( $ministry && 'publish' === $ministry->post_status ) {
			return get_permalink( $ministry ) . (string) strstr( $target, '#' );
		}
	}
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
function krm_redirect_for( $url, $map = null ) {
	$map = null === $map ? krm_redirects() : $map;
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
		$uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		$to  = krm_redirect_for( $uri );
		if ( ! $to && is_404() ) {
			$to = krm_redirect_for( $uri, krm_theme_renames() );
			if ( ! $to ) {
				$to = krm_moved_post_url( $uri );
			}
			if ( $to && ! empty( $_SERVER['QUERY_STRING'] ) ) {
				$to = add_query_arg( wp_parse_args( sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ) ), $to );
			}
		}
		if ( ! $to ) {
			return;
		}
		// Targets are entered by administrators and may point to another site, so wp_safe_redirect() is not used.
		wp_redirect( esc_url_raw( $to ), 301, 'Kingdom Revival theme' ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
		exit;
	},
	0
);

/*
 * Unpublished ministries and the old one-level /what-we-do/{ministry}/ addresses are routed in inc/ministry.php
 * (301 to the ministry under its group, 302 to the group page while a ministry is not published).
 */
