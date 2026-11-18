<?php
/**
 * Arrow icons: on the front end, the text arrows "→" and "←" in link and button labels ("Read more →",
 * "← All stories", "View all posts →", …) are printed as an inline SVG arrow that takes the text colour, so they
 * look the same in every font and browser. Editors keep typing → / ← in the content fields.
 *
 * Only text between tags is changed: tags and their attributes (alt, title, aria-label…), <script>, <style>,
 * <title>, <textarea>, <option> and comments are left as they are. Admin, feeds, REST, AJAX and non-HTML responses
 * are never touched.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * The inline SVG for one arrow.
 *
 * @param string $dir 'right' or 'left'.
 * @return string
 */
function krm_arrow_svg( $dir = 'right' ) {
	$path = 'left' === $dir ? 'M19 12H5m6-6-6 6 6 6' : 'M5 12h14m-6-6 6 6-6 6';
	return '<svg class="krm-arrow krm-arrow--' . $dir . '" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path d="' . $path . '" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

/**
 * Replace the text arrows of an HTML document with SVG arrows (text nodes only).
 *
 * @param string $html Page HTML.
 * @return string
 */
function krm_arrows_in_html( $html ) {
	if ( false === strpos( $html, '→' ) && false === strpos( $html, '←' ) && false === stripos( $html, '&rarr;' ) && false === stripos( $html, '&larr;' ) && false === strpos( $html, '&#8594;' ) && false === strpos( $html, '&#8592;' ) ) {
		return $html;
	}
	$parts = preg_split( '~(<script\b.*?</script>|<style\b.*?</style>|<title\b.*?</title>|<textarea\b.*?</textarea>|<option\b.*?</option>|<!--.*?-->|<[^>]*>)~is', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	if ( ! is_array( $parts ) ) {
		return $html;
	}
	$right = krm_arrow_svg( 'right' );
	$left  = krm_arrow_svg( 'left' );
	foreach ( $parts as $i => $part ) {
		if ( 1 === $i % 2 || '' === $part ) {
			continue; // A tag or a skipped element.
		}
		// The space next to an arrow is absorbed: the icon has its own margin, so spacing is the same everywhere.
		$part        = preg_replace( '~(?:\s|&nbsp;)*(?:→|&rarr;|&#8594;)~iu', $right, $part );
		$parts[ $i ] = preg_replace( '~(?:←|&larr;|&#8592;)(?:\s|&nbsp;)*~iu', $left, $part );
	}
	return implode( '', $parts );
}

add_action(
	'template_redirect',
	function () {
		if ( is_admin() || is_feed() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || is_customize_preview() ) {
			return;
		}
		ob_start(
			function ( $html ) {
				foreach ( headers_list() as $header ) {
					if ( 0 === stripos( $header, 'content-type:' ) && false === stripos( $header, 'text/html' ) ) {
						return $html;
					}
				}
				return krm_arrows_in_html( $html );
			}
		);
	},
	0
);
