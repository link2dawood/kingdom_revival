<?php
/**
 * Security hardening (Phase 11). Nothing here changes how a page looks:
 *  - browser security headers on front-end pages,
 *  - a signed form token, so form posts must come from a page of this site (see krm_handle_form()),
 *  - no username lookup for visitors (/?author=1 and the public REST users list),
 *  - links opened in a new tab from formatted content fields get rel="noopener".
 *
 * Private records (Submissions, Donations) are limited to Editors and Administrators in inc/forms.php and
 * inc/donations.php.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/* ---------- Headers ---------- */

/**
 * Security headers sent with front-end pages. Filter `krm_security_headers` to change or remove one
 * (e.g. when the host or a security plugin already sends them).
 */
function krm_security_headers() {
	return apply_filters(
		'krm_security_headers',
		array(
			'X-Content-Type-Options' => 'nosniff',
			'X-Frame-Options'        => 'SAMEORIGIN',
			'Referrer-Policy'        => 'strict-origin-when-cross-origin',
			'Permissions-Policy'     => 'camera=(), microphone=(), geolocation=(), payment=()',
		)
	);
}

add_action(
	'send_headers',
	function () {
		if ( headers_sent() || is_admin() ) {
			return;
		}
		foreach ( krm_security_headers() as $name => $value ) {
			if ( '' !== (string) $value ) {
				header( $name . ': ' . $value );
			}
		}
	}
);

/* ---------- Form token ---------- */

/**
 * A token stamped with the time the page was built and signed with this site's secret keys. Form posts send it
 * back; krm_form_token_age() rejects posts without a valid one. It does not expire, so cached pages keep working.
 */
function krm_form_token() {
	$time = (string) time();
	return $time . '.' . hash_hmac( 'sha256', 'krm_form|' . $time, wp_salt( 'nonce' ) );
}

/**
 * Seconds since the page carrying a form token was built, or -1 when the token is missing or forged.
 *
 * @param string $token Submitted token.
 */
function krm_form_token_age( $token ) {
	$parts = explode( '.', (string) $token );
	if ( 2 !== count( $parts ) || ! ctype_digit( $parts[0] ) ) {
		return -1;
	}
	if ( ! hash_equals( hash_hmac( 'sha256', 'krm_form|' . $parts[0], wp_salt( 'nonce' ) ), $parts[1] ) ) {
		return -1;
	}
	return max( 0, time() - (int) $parts[0] );
}

/* ---------- Username lookup ---------- */

/*
 * /?author=1 redirects to the author's archive, which shows their login name in the address. Not for visitors.
 * Runs before WordPress's own canonical redirect (template_redirect, priority 10).
 */
add_action(
	'template_redirect',
	function () {
		if ( ! is_user_logged_in() && isset( $_GET['author'] ) && is_author() ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	},
	1
);

/* The REST users list is only needed by logged-in editors (the block editor). */
add_filter(
	'rest_endpoints',
	function ( $endpoints ) {
		if ( ! current_user_can( 'list_users' ) && ! current_user_can( 'edit_posts' ) ) {
			unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		}
		return $endpoints;
	}
);

/* ---------- Links in formatted fields ---------- */

/**
 * Add rel="noopener" to links that open in a new tab and have no rel of their own.
 *
 * @param string $html Sanitized inline HTML.
 */
function krm_link_rel( $html ) {
	if ( false === stripos( $html, 'target=' ) ) {
		return $html;
	}
	return preg_replace_callback(
		'#<a\s[^>]*>#i',
		function ( $m ) {
			if ( ! preg_match( '#\starget=["\']?_blank#i', $m[0] ) || preg_match( '#\srel=#i', $m[0] ) ) {
				return $m[0];
			}
			return substr( $m[0], 0, -1 ) . ' rel="noopener">';
		},
		$html
	);
}
