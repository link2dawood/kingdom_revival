<?php
/**
 * Loading performance (Phase 10). Nothing here changes how a page looks:
 *  - hero backgrounds get a high-priority preload hint, and a smaller Media Library size on small screens;
 *  - resized images WordPress creates (uploads and Setup's import) are saved as WebP;
 *  - editor styles and Dashicons that plugins put on public pages are left out for visitors (Phase 15).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/* ---------- Background images ---------- */

/**
 * Smaller copy of a Media Library image for small screens, or '' when there is none (design URL not imported yet,
 * or the image is no bigger than that size anyway).
 *
 * @param int|string $ref  Attachment ID or design URL.
 * @param string     $size '1536x1536' for tablets (up to 820px wide) or 'medium_large' (768px) for phones.
 */
function krm_bg_small_url( $ref, $size = '1536x1536' ) {
	$id = is_numeric( $ref ) ? (int) $ref : krm_media_id( (string) $ref );
	if ( ! $id || ! wp_attachment_is_image( $id ) ) {
		return '';
	}
	$small = wp_get_attachment_image_url( $id, $size );
	return ( $small && wp_get_attachment_image_url( $id, 'full' ) !== $small ) ? $small : '';
}

/**
 * Inside a background's style attribute: the tablet copy as --krm-bg-m and the phone copy as --krm-bg-s (used by
 * the rules added in krm_bg_small_css()). Prints nothing when there is no smaller copy, so the markup stays the
 * design's.
 *
 * @param int|string $ref Attachment ID or design URL.
 */
function krm_bg_small( $ref ) {
	$tablet = krm_bg_small_url( $ref );
	$phone  = krm_bg_small_url( $ref, 'medium_large' );
	if ( $tablet ) {
		echo "; --krm-bg-m: url('" . esc_url( $tablet ) . "')";
	}
	if ( $phone && $phone !== $tablet ) {
		echo "; --krm-bg-s: url('" . esc_url( $phone ) . "')";
	}
}

/**
 * The rules that swap in --krm-bg-m and --krm-bg-s. They beat the inline background-image because they are
 * !important. Phones get the 768px copy: a hero is at most about 820px wide there, and phones of 2× density or more
 * see no difference at that size, while the file is about a third of the 1536px one.
 */
function krm_bg_small_css() {
	return '@media (max-width: 820px){[style*="--krm-bg-m"]{background-image:var(--krm-bg-m)!important}}'
		. '@media (max-width: 600px){[style*="--krm-bg-s"]{background-image:var(--krm-bg-s)!important}}';
}

/**
 * Preload the hero background, the page's largest element on first view. A CSS background is otherwise found only
 * after the stylesheets have loaded; this hint sits just before the hero, where the browser's preload scanner sees
 * it as early as one in <head>. Only the first call on a page prints anything.
 *
 * @param int|string $ref Attachment ID or design URL.
 */
function krm_hero_preload( $ref ) {
	static $done = false;
	if ( $done || ! $ref ) {
		return;
	}
	$done   = true;
	$full   = krm_ref_url( $ref );
	$tablet = krm_bg_small_url( $ref );
	$phone  = krm_bg_small_url( $ref, 'medium_large' );
	$tiers  = array();
	if ( $phone && $phone !== $tablet ) {
		$tiers[] = array( $phone, '(max-width: 600px)' );
		$tiers[] = array( $tablet ? $tablet : $full, '(min-width: 601px) and (max-width: 820px)' );
	} elseif ( $tablet ) {
		$tiers[] = array( $tablet, '(max-width: 820px)' );
	}
	if ( $tiers ) {
		$tiers[] = array( $full, '(min-width: 821px)' );
		foreach ( $tiers as $tier ) {
			printf( '<link rel="preload" as="image" href="%s" media="%s" fetchpriority="high">' . "
", esc_url( $tier[0] ), esc_attr( $tier[1] ) );
		}
	} elseif ( $full ) {
		printf( '<link rel="preload" as="image" href="%s" fetchpriority="high">' . "
", esc_url( $full ) );
	}
}

/* ---------- WebP ---------- */

/*
 * Save the resized copies WordPress makes of JPEG and PNG uploads (and the "-scaled" copy of very large ones) as
 * WebP: typically 25–35% smaller than JPEG and far smaller than PNG photos, at the same visible quality. The
 * original upload is kept as it is. Kingdom Revival → Settings → "Image format" turns it off.
 */
add_filter(
	'image_editor_output_format',
	function ( $formats ) {
		static $webp = null;
		if ( 'webp' !== krm_opt( 'image_format' ) ) {
			return $formats;
		}
		if ( null === $webp ) {
			$webp = false; // Guards against re-entry: the support check below runs this filter again.
			$webp = wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) );
		}
		if ( $webp ) {
			foreach ( array( 'image/jpeg', 'image/png' ) as $mime ) {
				if ( empty( $formats[ $mime ] ) ) {
					$formats[ $mime ] = 'image/webp';
				}
			}
		}
		return $formats;
	}
);

/* ---------- Styles plugins add to public pages ---------- */

/**
 * Style handles the theme's pages never use: Dashicons and the block editor's own interface styles, which some
 * form and gallery plugins enqueue on every public page (about 490 KB on the live site in 2026-11). Also Robo
 * Gallery's block stylesheet (before 5.2): the file is empty, but it depends on 'wp-editor', which pulls in all of
 * the editor styles; its authors removed it from the front end in 5.2 for that reason.
 */
function krm_unused_style_handles() {
	return array( 'dashicons', 'wp-components', 'wp-block-editor', 'wp-editor', 'wp-preferences', 'wp-reusable-blocks', 'wp-patterns', 'wp-media-utils', 'wp-commands', 'wp-edit-post', 'wp-format-library', 'block-robo-gallery-style-css' );
}

/**
 * Leave those styles out for visitors who are not logged in. Only directly queued handles are removed: when an
 * enqueued plugin style depends on one, WordPress still prints it, so nothing a plugin needs goes missing.
 * Kingdom Revival → Settings → "Unused plugin styles" turns this off.
 */
function krm_trim_plugin_styles() {
	if ( 'off' === krm_opt( 'trim_assets' ) || is_user_logged_in() || is_customize_preview() ) {
		return;
	}
	foreach ( krm_unused_style_handles() as $handle ) {
		if ( wp_style_is( $handle, 'enqueued' ) ) {
			wp_dequeue_style( $handle );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'krm_trim_plugin_styles', 999 );
add_action( 'wp_print_footer_scripts', 'krm_trim_plugin_styles', 1 );
