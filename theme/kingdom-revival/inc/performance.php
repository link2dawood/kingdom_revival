<?php
/**
 * Loading performance (Phase 10). Nothing here changes how a page looks:
 *  - hero backgrounds get a high-priority preload hint, and a smaller Media Library size on small screens;
 *  - resized images WordPress creates (uploads and Setup's import) are saved as WebP.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/* ---------- Background images ---------- */

/**
 * Smaller copy of a Media Library image for screens up to 820px wide, or '' when there is none (design URL not
 * imported yet, or the image is no bigger than that size anyway).
 *
 * @param int|string $ref Attachment ID or design URL.
 */
function krm_bg_small_url( $ref ) {
	$id = is_numeric( $ref ) ? (int) $ref : krm_media_id( (string) $ref );
	if ( ! $id || ! wp_attachment_is_image( $id ) ) {
		return '';
	}
	$small = wp_get_attachment_image_url( $id, '1536x1536' );
	return ( $small && wp_get_attachment_image_url( $id, 'full' ) !== $small ) ? $small : '';
}

/**
 * Inside a background's style attribute: the small-screen copy as --krm-bg-m (used by the rule added in
 * krm_bg_small_css()). Prints nothing when there is no smaller copy, so the markup stays the design's.
 *
 * @param int|string $ref Attachment ID or design URL.
 */
function krm_bg_small( $ref ) {
	$small = krm_bg_small_url( $ref );
	if ( $small ) {
		echo "; --krm-bg-m: url('" . esc_url( $small ) . "')";
	}
}

/**
 * The rule that swaps in --krm-bg-m. It beats the inline background-image because it is !important.
 */
function krm_bg_small_css() {
	return '@media (max-width: 820px){[style*="--krm-bg-m"]{background-image:var(--krm-bg-m)!important}}';
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
	$done  = true;
	$full  = krm_ref_url( $ref );
	$small = krm_bg_small_url( $ref );
	if ( $small ) {
		printf( '<link rel="preload" as="image" href="%s" media="(max-width: 820px)" fetchpriority="high">' . "\n", esc_url( $small ) );
		printf( '<link rel="preload" as="image" href="%s" media="(min-width: 821px)" fetchpriority="high">' . "\n", esc_url( $full ) );
	} elseif ( $full ) {
		printf( '<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n", esc_url( $full ) );
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
