<?php
/**
 * Loading performance (Phase 10). Nothing here changes how a page looks:
 *  - hero backgrounds get a high-priority preload hint, and a smaller Media Library size on small screens and on
 *    standard-density desktops;
 *  - resized images WordPress creates (uploads and Setup's import) are saved as WebP;
 *  - portrait photos get 4:3 crops for cards and gallery tiles (Phase 15 step 5), made in the background for older uploads;
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
 * The 1536px copy when it can also serve standard-density desktops up to 1536px wide: it must be the full 1536px
 * wide and at least 960px tall, because a hero or band there is up to 1526px wide and 921px tall (cover never
 * enlarges it then). Landscape photos of 4:3 or 3:2 qualify; 16:9 and portrait photos keep the full image.
 *
 * @param int|string $ref Attachment ID or design URL.
 */
function krm_bg_desktop_url( $ref ) {
	$url = krm_bg_small_url( $ref );
	if ( ! $url ) {
		return '';
	}
	$id   = is_numeric( $ref ) ? (int) $ref : krm_media_id( (string) $ref );
	$copy = wp_get_attachment_image_src( $id, '1536x1536' );
	return ( $copy && $copy[1] >= 1536 && $copy[2] >= 960 ) ? $url : '';
}

/**
 * Media query of the standard-density desktop tier (--krm-bg-l). Screens of 1.5× or more keep the full image.
 */
function krm_bg_desktop_media() {
	return '(min-width: 821px) and (max-width: 1536px) and (max-resolution: 1.49dppx)';
}

/**
 * Inside a background's style attribute: the tablet copy as --krm-bg-m, the phone copy as --krm-bg-s and, when it is
 * big enough, the same 1536px copy for standard-density desktops as --krm-bg-l (used by the rules added in
 * krm_bg_small_css()). Prints nothing when there is no smaller copy, so the markup stays the design's.
 *
 * @param int|string $ref Attachment ID or design URL.
 */
function krm_bg_small( $ref ) {
	$tablet  = krm_bg_small_url( $ref );
	$phone   = krm_bg_small_url( $ref, 'medium_large' );
	$desktop = krm_bg_desktop_url( $ref );
	if ( $tablet ) {
		echo "; --krm-bg-m: url('" . esc_url( $tablet ) . "')";
	}
	if ( $phone && $phone !== $tablet ) {
		echo "; --krm-bg-s: url('" . esc_url( $phone ) . "')";
	}
	if ( $desktop ) {
		echo "; --krm-bg-l: url('" . esc_url( $desktop ) . "')";
	}
}

/**
 * The rules that swap in --krm-bg-m, --krm-bg-s and --krm-bg-l. They beat the inline background-image because they
 * are !important. Phones get the 768px copy: a hero is at most about 820px wide there, and phones of 2× density or
 * more see no difference at that size, while the file is about a third of the 1536px one.
 */
function krm_bg_small_css() {
	return '@media (max-width: 820px){[style*="--krm-bg-m"]{background-image:var(--krm-bg-m)!important}}'
		. '@media (max-width: 600px){[style*="--krm-bg-s"]{background-image:var(--krm-bg-s)!important}}'
		. '@media ' . krm_bg_desktop_media() . '{[style*="--krm-bg-l"]{background-image:var(--krm-bg-l)!important}}';
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
	$done    = true;
	$full    = krm_ref_url( $ref );
	$tablet  = krm_bg_small_url( $ref );
	$phone   = krm_bg_small_url( $ref, 'medium_large' );
	$desktop = krm_bg_desktop_url( $ref );
	$tiers   = array();
	if ( $phone && $phone !== $tablet ) {
		$tiers[] = array( $phone, '(max-width: 600px)' );
		$tiers[] = array( $tablet ? $tablet : $full, '(min-width: 601px) and (max-width: 820px)' );
	} elseif ( $tablet ) {
		$tiers[] = array( $tablet, '(max-width: 820px)' );
	}
	if ( $desktop ) {
		// The same conditions as krm_bg_small_css(), so exactly one file matches each screen.
		$tiers[] = array( $desktop, krm_bg_desktop_media() );
		$tiers[] = array( $full, '(min-width: 1537px), (min-width: 821px) and (min-resolution: 1.5dppx)' );
	} elseif ( $tiers ) {
		$tiers[] = array( $full, '(min-width: 821px)' );
	}
	if ( $tiers ) {
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

/* ---------- 4:3 card crops ---------- */

/*
 * Cards and gallery tiles (home causes and field stories, story cards, Donate's impact cards, ministry and story
 * galleries, All Ministries) show a photo with object-fit: cover in a box never taller than 4:3. For a portrait photo
 * that is only a middle band, yet the browser picks a file by width and downloads the whole height: a 358px-wide
 * card on a 3× phone took the 1055×1536 copy. A centred 4:3 crop holds exactly that band, at about half the bytes.
 * The crops are made only for photos taller than 5:4; wider photos are already close to the box's shape.
 */

/**
 * Card crop sizes: name => width (height is 3/4 of it).
 */
function krm_card_sizes() {
	return array(
		'krm-card-480'  => 480,
		'krm-card-800'  => 800,
		'krm-card-1200' => 1200,
	);
}

add_action(
	'after_setup_theme',
	function () {
		foreach ( krm_card_sizes() as $name => $width ) {
			add_image_size( $name, $width, (int) ( $width * 3 / 4 ), true );
		}
	}
);

/**
 * Does an image get card crops? Only photos taller than 5:4 (portrait or square).
 *
 * @param array $meta Attachment metadata (width, height).
 */
function krm_card_wanted( $meta ) {
	return ! empty( $meta['width'] ) && ! empty( $meta['height'] ) && $meta['width'] * 4 < $meta['height'] * 5;
}

/**
 * Leave out the card sizes an image should not get: all of them for wider photos, and the ones wider than the
 * photo (WordPress would make an off-shape crop instead). Runs on upload and when missing sizes are made.
 *
 * @param array $sizes Sizes to make.
 * @param array $meta  Attachment metadata.
 */
function krm_card_filter_sizes( $sizes, $meta ) {
	foreach ( krm_card_sizes() as $name => $width ) {
		if ( isset( $sizes[ $name ] ) && ( ! krm_card_wanted( $meta ) || $meta['width'] < $width ) ) {
			unset( $sizes[ $name ] );
		}
	}
	return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'krm_card_filter_sizes', 10, 2 );
add_filter( 'wp_get_missing_image_subsizes', 'krm_card_filter_sizes', 10, 2 );

/**
 * The srcset of an image's card crops, or '' when it has none (the caller then uses the normal one).
 *
 * @param int $id Attachment ID.
 */
function krm_card_srcset( $id ) {
	$meta = wp_get_attachment_metadata( $id );
	if ( empty( $meta['sizes'] ) || ! is_array( $meta['sizes'] ) ) {
		return '';
	}
	foreach ( array_reverse( array_keys( krm_card_sizes() ) ) as $name ) {
		if ( ! empty( $meta['sizes'][ $name ] ) ) {
			return (string) wp_get_attachment_image_srcset( $id, $name, $meta );
		}
	}
	return '';
}

/**
 * Make the card crops for images uploaded before theme 1.8.1, a few per WP-Cron run so no request runs long. The
 * option holds the last attachment ID looked at (saved before each image, so one that fails is not retried
 * forever), or -1 when every image is done.
 */
function krm_card_backfill() {
	global $wpdb;
	$cursor = (int) get_option( 'krm_card_backfill', 0 );
	if ( $cursor < 0 ) {
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_mime_type IN ('image/jpeg', 'image/png', 'image/webp') AND ID > %d ORDER BY ID ASC LIMIT 20", $cursor ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	// Only the card crops: other sizes a site left out on purpose stay missing.
	$only_cards = function ( $sizes ) {
		return array_intersect_key( $sizes, krm_card_sizes() );
	};
	add_filter( 'wp_get_missing_image_subsizes', $only_cards, 20 );
	$made = 0;
	foreach ( $ids as $id ) {
		update_option( 'krm_card_backfill', (int) $id );
		$meta = wp_get_attachment_metadata( (int) $id );
		if ( ! is_array( $meta ) || ! krm_card_wanted( $meta ) || ! wp_get_missing_image_subsizes( (int) $id ) ) {
			continue;
		}
		wp_update_image_subsizes( (int) $id );
		if ( ++$made >= 3 ) {
			break;
		}
	}
	remove_filter( 'wp_get_missing_image_subsizes', $only_cards, 20 );
	if ( $made < 3 && count( $ids ) < 20 ) {
		update_option( 'krm_card_backfill', -1 );
	}
}
add_action( 'krm_card_backfill', 'krm_card_backfill' );

/* Keep the backfill going (one event at a time) until it is done. */
add_action(
	'init',
	function () {
		if ( (int) get_option( 'krm_card_backfill', 0 ) >= 0 && ! wp_next_scheduled( 'krm_card_backfill' ) ) {
			wp_schedule_single_event( time() + 60, 'krm_card_backfill' );
		}
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

/**
 * Photo bands (Phase 20): attributes for a section that shows a photo behind a see-through brand overlay
 * (theme-global.css [data-photo-band]). The photo is not in the HTML or CSS: effects.js sets it when the band nears
 * the screen (lazy), choosing the phone copy (768px) or the desktop copy. The desktop copy is the largest WebP/JPEG
 * copy of 250 KB or less. An empty photo field prints nothing, so the band keeps its plain colour.
 *
 * @param int|string $ref     Attachment ID or design URL (an image field's value).
 * @param string     $variant Overlay: freedom | stats | cta | partner.
 * @return string Attribute string, starting with a space.
 */
function krm_photo_band_attrs( $ref, $variant ) {
	if ( ! $ref ) {
		return '';
	}
	$large = '';
	$small = '';
	$id    = is_numeric( $ref ) ? (int) $ref : krm_media_id( (string) $ref );
	if ( $id && wp_attachment_is_image( $id ) ) {
		$meta = wp_get_attachment_metadata( $id );
		$dir  = dirname( (string) get_attached_file( $id ) );
		foreach ( array( '1536x1536', 'large', 'medium_large' ) as $size ) {
			if ( empty( $meta['sizes'][ $size ]['file'] ) ) {
				continue;
			}
			$path = $dir . '/' . $meta['sizes'][ $size ]['file'];
			if ( is_readable( $path ) && filesize( $path ) <= 250 * 1024 ) {
				$large = (string) wp_get_attachment_image_url( $id, $size );
				break;
			}
		}
		$small = (string) wp_get_attachment_image_url( $id, 'medium_large' );
	}
	if ( '' === $large ) {
		$large = krm_ref_url( $ref );
	}
	if ( '' === $large ) {
		return '';
	}
	return sprintf(
		' data-photo-band="%s" data-band-photo="%s" data-band-photo-s="%s"',
		esc_attr( $variant ),
		esc_url( $large ),
		esc_url( $small ? $small : $large )
	);
}

/* ---------- Page-builder and form-plugin files the theme's pages never use (theme 1.16.3) ---------- */

/*
 * The live site still has Elementor, Elementor Pro, Header Footer Elementor, Astra Sites, FormLayer and SureForms
 * active from the previous site. Every page here is drawn by the theme's own templates, so their 25+ stylesheets and
 * scripts (plus Elementor's Google Fonts and Font Awesome) were downloaded on every page for nothing and cost about 10
 * points of mobile PageSpeed. They are left out, except where they can matter:
 *  - a page or post whose content was built with Elementor and is printed by page.php / single.php (not the home page
 *    or a design page, which never print their content);
 *  - SureForms / FormLayer on a page whose content has one of their forms;
 *  - the Elementor editor and its preview, customizer previews and the admin.
 * Developers: return false from the 'krm_strip_builder_assets' filter to load everything again.
 */

/**
 * Whether the plugin files of the current view can be left out: [ builder (Elementor family), forms ].
 *
 * @return bool[]
 */
function krm_strip_builder_assets() {
	static $out = null;
	if ( null !== $out ) {
		return $out;
	}
	$out = array( false, false );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only checks of the editor's preview flags.
	if ( is_admin() || is_customize_preview() || isset( $_GET['elementor-preview'] ) || isset( $_GET['elementor_library'] ) || ! apply_filters( 'krm_strip_builder_assets', true ) ) {
		return $out;
	}
	$builder = true;
	$forms   = true;
	if ( is_singular() && ! is_front_page() && '' === krm_design_template() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			if ( 'builder' === get_post_meta( $post->ID, '_elementor_edit_mode', true ) ) {
				$builder = false;
			}
			if ( preg_match( '/sureforms|srfm|formlayer/i', (string) $post->post_content ) ) {
				$forms = false;
			}
		}
	}
	$out = array( $builder, $forms );
	return $out;
}

/**
 * Leave out the queued styles or scripts that belong to the plugins above.
 *
 * @param WP_Dependencies $deps wp_styles() or wp_scripts().
 */
function krm_dequeue_builder_assets( $deps ) {
	list( $builder, $forms ) = krm_strip_builder_assets();
	if ( ! $builder && ! $forms ) {
		return;
	}
	$builder_re = '#/(?:plugins/(?:elementor|elementor-pro|header-footer-elementor|astra-sites|ultimate-elementor|essential-addons-for-elementor-lite)/|uploads/elementor/)|fonts\.googleapis\.com/css\?family=Roboto#';
	$forms_re   = '#/plugins/(?:sureforms|formlayer)/#';
	foreach ( (array) $deps->queue as $handle ) {
		$src = isset( $deps->registered[ $handle ] ) ? (string) $deps->registered[ $handle ]->src : '';
		if ( ( $builder && ( preg_match( $builder_re, $src ) || 0 === strpos( $handle, 'elementor' ) || 0 === strpos( $handle, 'hfe-' ) || in_array( $handle, array( 'font-awesome', 'e-animations', 'swiper' ), true ) ) )
			|| ( $forms && preg_match( $forms_re, $src ) ) ) {
			wp_dequeue_style( $handle );
			wp_dequeue_script( $handle );
		}
	}
	// jQuery (+ migrate, 34 KB in the head) was only there for those plugins: the theme's scripts do not use it. It is
	// kept whenever a script still queued needs it (CookieAdmin, a form plugin on its page, anything else).
	if ( $builder && $deps instanceof WP_Scripts && in_array( 'jquery', (array) $deps->queue, true ) && ! krm_jquery_needed( $deps ) ) {
		wp_dequeue_script( 'jquery' );
	}
}

/**
 * Whether a queued script other than jQuery itself depends on jQuery (directly or through its own dependencies).
 *
 * @param WP_Scripts $deps wp_scripts().
 */
function krm_jquery_needed( $deps ) {
	$seen  = array();
	$needs = function ( $handle ) use ( &$needs, &$seen, $deps ) {
		if ( isset( $seen[ $handle ] ) || ! isset( $deps->registered[ $handle ] ) ) {
			return false;
		}
		$seen[ $handle ] = true;
		foreach ( (array) $deps->registered[ $handle ]->deps as $dep ) {
			if ( in_array( $dep, array( 'jquery', 'jquery-core', 'jquery-migrate' ), true ) || $needs( $dep ) ) {
				return true;
			}
		}
		return false;
	};
	foreach ( (array) $deps->queue as $handle ) {
		if ( ! in_array( $handle, array( 'jquery', 'jquery-core', 'jquery-migrate' ), true ) && $needs( $handle ) ) {
			return true;
		}
	}
	return false;
}
add_action(
	'wp_enqueue_scripts',
	function () {
		krm_dequeue_builder_assets( wp_styles() );
		krm_dequeue_builder_assets( wp_scripts() );
	},
	9999
);
// Plugins that queue files while the page is being printed (widgets, kits) are caught just before each print.
add_action(
	'wp_print_styles',
	function () {
		krm_dequeue_builder_assets( wp_styles() );
	},
	9999
);
add_action(
	'wp_print_scripts',
	function () {
		krm_dequeue_builder_assets( wp_scripts() );
	},
	9999
);
add_action(
	'wp_print_footer_scripts',
	function () {
		krm_dequeue_builder_assets( wp_styles() );
		krm_dequeue_builder_assets( wp_scripts() );
	},
	1
);
// Elementor's Google Fonts (Roboto, Roboto Slab) and its icon fonts are requested by Elementor itself.
add_filter(
	'elementor/frontend/print_google_fonts',
	function ( $print ) {
		return krm_strip_builder_assets()[0] ? false : $print;
	}
);
