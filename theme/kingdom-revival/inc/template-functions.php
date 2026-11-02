<?php
/**
 * Template functions: settings, URLs, media, logo, design page templates and shared icons.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default values for every theme setting. These are the details shown in the approved design.
 */
function krm_defaults() {
	return array(
		'phone'               => '+92-341-5418733',
		'phone_tel'           => '+923415418733',
		'email'               => 'Info@kingdomrevivalministries.com',
		'address'             => 'Chak No 220, RB Nasrat Colony, Street No. 3, Shadab Puli, Jhang Road, Faisalabad',
		'whatsapp'            => '923415418733',
		'schema_org_type'     => 'Organization',
		'address_locality'    => 'Faisalabad',
		'address_region'      => 'Punjab',
		'address_country'     => 'PK',
		'facebook'            => '',
		'youtube'             => '',
		'instagram'           => '',
		'paypal_me'           => 'https://www.paypal.me/Givetotheneedy7',
		'paypal_email'        => '',
		'paypal_mode'         => 'live',
		'donation_receipts'   => 'on',
		'receipt_subject'     => 'Thank you for your gift to Kingdom Revival Ministries',
		'receipt_message'     => "Dear {name},

Thank you for your {frequency} gift of {amount} to Kingdom Revival Ministries. It has been received and will be used for {fund}.

Gift: {amount} ({frequency})
Date: {date}
PayPal transaction: {transaction}

PayPal also emails you its own payment receipt. Please keep this email for your records.

Through your generosity the Gospel and practical care reach Pakistan’s forgotten: brick kiln families, orphans and widows. We are praying for you.

If you have any questions, email {email} or call {phone}.

God bless you,
Kingdom Revival Ministries",
		'bank_account_name'   => 'Adil Arif',
		'bank_cnic'           => '33100-9770620-7',
		'bank_name'           => 'Allied Bank Limited',
		'bank_iban'           => 'PK61ABPA0010057879410018',
		'bank_swift'          => 'ABPAPKKA',
		'bank_branch_code'    => '0066',
		'bank_branch_address' => 'Street No. 03, Nasrat Colony, near Shadab Puli, Jhang Road, Faisalabad 38000, Punjab, Pakistan',
		'bank_phone'          => '+92 341 5418733',
		'form_recipient'      => 'Info@kingdomrevivalministries.com',
		'newsletter_endpoint' => '',
		'newsletter_delay'    => 3,
		'redirects'           => krm_default_redirects(),
		'image_format'        => 'webp',
		'stat_1_value'        => '80',
		'stat_1_label'        => 'Communities reached',
		'stat_2_value'        => '200',
		'stat_2_label'        => 'Churches planted incl. house churches',
		'stat_3_value'        => '500',
		'stat_3_label'        => 'Children supported',
		'stat_4_value'        => '650',
		'stat_4_label'        => 'Bibles distributed',
		'stat_5_value'        => '400',
		'stat_5_label'        => 'Baptisms',
		// The home page design words two labels differently from the about page.
		'home_stat_1_label'   => 'Communities reached',
		'home_stat_2_label'   => 'Church planted included house churches',
		'home_stat_3_label'   => 'Children supported',
		'home_stat_4_label'   => 'Bible distributed',
		'home_stat_5_label'   => 'Baptisms',
	);
}

/**
 * Read one theme setting.
 *
 * @param string $key Setting key.
 * @return string
 */
function krm_opt( $key ) {
	static $settings = null;
	if ( null === $settings ) {
		$saved    = get_option( 'krm_settings', array() );
		$settings = wp_parse_args( is_array( $saved ) ? $saved : array(), krm_defaults() );
	}
	return isset( $settings[ $key ] ) ? (string) $settings[ $key ] : '';
}

/**
 * Tap-to-call link for the ministry phone number.
 */
function krm_tel() {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', krm_opt( 'phone_tel' ) );
}

/**
 * Mailto link for the ministry email.
 */
function krm_mailto() {
	return 'mailto:' . krm_opt( 'email' );
}

/**
 * WhatsApp chat link for the ministry number.
 */
function krm_whatsapp_url() {
	return 'https://wa.me/' . preg_replace( '/[^0-9]/', '', krm_opt( 'whatsapp' ) );
}

/**
 * The five impact stats. The numbers are shared; the home page has its own labels.
 *
 * @param string $context 'home' for the home page labels, '' for the about page labels.
 */
function krm_stats( $context = '' ) {
	$stats  = array();
	$prefix = 'home' === $context ? 'home_' : '';
	for ( $i = 1; $i <= 5; $i++ ) {
		$stats[] = array(
			'value' => krm_opt( "stat_{$i}_value" ),
			'label' => krm_opt( "{$prefix}stat_{$i}_label" ),
		);
	}
	return $stats;
}

/**
 * URL of a file in the theme's assets/ folder (a child theme's copy wins).
 *
 * @param string $path Path inside assets/.
 */
function krm_asset( $path ) {
	return get_theme_file_uri( 'assets/' . ltrim( $path, '/' ) );
}

/* ---------- Media: remote design URLs resolve to imported attachments ---------- */

/**
 * Remote design URL => attachment ID, filled by the setup importer.
 */
function krm_media_map() {
	static $map = null;
	if ( null === $map ) {
		$map = get_option( 'krm_media_map', array() );
		$map = is_array( $map ) ? $map : array();
	}
	return $map;
}

/**
 * Attachment ID for a design image, or 0 if it has not been imported.
 *
 * @param string $url Remote URL from the design.
 */
function krm_media_id( $url ) {
	$map = krm_media_map();
	return isset( $map[ $url ] ) ? (int) $map[ $url ] : 0;
}

/**
 * URL for a design image: the imported Media Library copy when available, otherwise the original URL.
 * "krm-theme:images/content/x.jpg" names a photo shipped in the theme's assets/ folder (not on the old site).
 *
 * @param string $url  Remote URL from the design.
 * @param string $size Image size.
 */
function krm_media( $url, $size = 'full' ) {
	$id = krm_media_id( $url );
	if ( $id ) {
		$src = wp_attachment_is_image( $id ) ? wp_get_attachment_image_url( $id, $size ) : wp_get_attachment_url( $id );
		if ( $src ) {
			return $src;
		}
	}
	return 0 === strpos( $url, 'krm-theme:' ) ? krm_asset( substr( $url, 10 ) ) : $url;
}

/**
 * Print src (full size, so zooming stays sharp) plus srcset/sizes for an image.
 *
 * @param int|string $ref   Attachment ID or design URL.
 * @param string     $sizes Sizes attribute.
 */
function krm_img_src( $ref, $sizes = '(max-width: 820px) 100vw, 50vw' ) {
	$id  = is_numeric( $ref ) ? (int) $ref : krm_media_id( $ref );
	$src = is_numeric( $ref ) ? wp_get_attachment_image_url( $id, 'full' ) : krm_media( $ref );
	echo 'src="' . esc_url( (string) $src ) . '"';
	if ( $id ) {
		$srcset = wp_get_attachment_image_srcset( $id, 'full' );
		if ( $srcset ) {
			echo ' srcset="' . esc_attr( $srcset ) . '" sizes="' . esc_attr( $sizes ) . '"';
		}
	}
}

/**
 * URL of an image reference (attachment ID or design URL).
 *
 * @param int|string $ref  Reference.
 * @param string     $size Image size.
 */
function krm_ref_url( $ref, $size = 'full' ) {
	if ( is_numeric( $ref ) ) {
		return (string) wp_get_attachment_image_url( (int) $ref, $size );
	}
	return krm_media( (string) $ref, $size );
}

/**
 * Alt text of an image reference.
 *
 * @param int|string $ref      Reference.
 * @param string     $fallback Used when nothing better is known.
 */
function krm_ref_alt( $ref, $fallback = '' ) {
	if ( is_numeric( $ref ) ) {
		$alt = get_post_meta( (int) $ref, '_wp_attachment_image_alt', true );
		return '' !== $alt ? $alt : $fallback;
	}
	$id = krm_media_id( $ref );
	if ( $id ) {
		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		if ( '' !== $alt ) {
			return $alt;
		}
	}
	// Not imported yet: the design's own alt text for this file.
	static $manifest = null;
	if ( null === $manifest ) {
		$manifest = include KRM_DIR . '/inc/data/media.php';
	}
	return isset( $manifest[ $ref ] ) && '' !== $manifest[ $ref ] ? $manifest[ $ref ] : $fallback;
}

/**
 * Alt text for a story's cover: the alt saved with the story, else the image's own alt, else the title.
 *
 * @param int $post_id Post ID.
 */
function krm_cover_alt( $post_id ) {
	$alt = (string) get_post_meta( $post_id, '_krm_cover_alt', true );
	return '' !== $alt ? $alt : krm_ref_alt( krm_post_cover( $post_id ), get_the_title( $post_id ) );
}

/* ---------- Internal links ---------- */

/**
 * A post of the given type by its path. get_page_by_path() falls back to attachments, and imported photos share slugs
 * with content (Widows-ministry-1.jpg is the attachment "widows-ministry"), so anything of another type is ignored.
 *
 * @param string $path      Post path (slug).
 * @param string $post_type Post type.
 * @return WP_Post|null
 */
function krm_get_by_path( $path, $post_type = 'page' ) {
	$post = get_page_by_path( $path, OBJECT, $post_type );
	return ( $post && $post_type === $post->post_type ) ? $post : null;
}

/**
 * Permalink of a page by its path (about, contact, donate, partner…).
 *
 * @param string $path Page path.
 */
function krm_page_url( $path ) {
	static $cache = array();
	if ( ! isset( $cache[ $path ] ) ) {
		$page = null;
		// A design page is found by its template first, so renaming the page or its slug keeps links working.
		if ( in_array( $path, krm_design_pages(), true ) ) {
			$pages = krm_design_template_pages();
			$page  = isset( $pages[ $path ] ) ? $pages[ $path ] : null;
		}
		if ( ! $page ) {
			$page = krm_get_by_path( $path );
		}
		$cache[ $path ] = ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : home_url( user_trailingslashit( $path ) );
	}
	return $cache[ $path ];
}

/**
 * The published page using each design template ('about' => WP_Post, …; the oldest one when several do),
 * found with one query for all of them.
 */
function krm_design_template_pages() {
	static $pages = null;
	if ( null === $pages ) {
		$pages = array();
		$found = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 50,
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => array_map( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
					function ( $slug ) {
						return 'page-templates/' . $slug . '.php';
					},
					krm_design_pages()
				),
				'meta_compare'   => 'IN',
				'orderby'        => 'ID',
				'order'          => 'ASC',
				'no_found_rows'  => true,
			)
		);
		foreach ( $found as $page ) {
			$slug = basename( (string) get_page_template_slug( $page ), '.php' );
			if ( ! isset( $pages[ $slug ] ) ) {
				$pages[ $slug ] = $page;
			}
		}
	}
	return $pages;
}

/**
 * URL of the News & Blogs (posts) page.
 */
function krm_blog_url() {
	$id = (int) get_option( 'page_for_posts' );
	return $id ? get_permalink( $id ) : krm_page_url( 'blog' );
}

/**
 * Permalink of a ministry by slug.
 *
 * @param string $slug Ministry slug.
 */
function krm_ministry_url( $slug ) {
	static $cache = array();
	if ( ! isset( $cache[ $slug ] ) ) {
		$post           = krm_get_by_path( $slug, 'ministry' );
		$cache[ $slug ] = $post ? get_permalink( $post ) : home_url( user_trailingslashit( 'ministries/' . $slug ) );
	}
	return $cache[ $slug ];
}

/**
 * URL of the Donate page.
 */
function krm_donate_url() {
	return krm_page_url( 'donate' );
}

/**
 * All ministries in a normalised shape. Falls back to the design data until content is imported.
 *
 * @param string $order 'menu' (navigation/donation order) or 'home' (homepage explorer order).
 * @return array[]
 */
function krm_ministries( $order = 'menu' ) {
	static $cache = array();
	if ( isset( $cache[ $order ] ) ) {
		return $cache[ $order ];
	}
	$items = array();
	$posts = get_posts(
		array(
			'post_type'      => 'ministry',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'no_found_rows'  => true,
		)
	);
	if ( $posts ) {
		foreach ( $posts as $post ) {
			$image = get_post_meta( $post->ID, '_krm_explorer_image', true );
			if ( ! $image ) {
				$image = get_post_thumbnail_id( $post );
			}
			$items[] = array(
				'slug'       => $post->post_name,
				'title'      => get_the_title( $post ),
				'desc'       => $post->post_excerpt,
				'url'        => get_permalink( $post ),
				'image'      => $image,
				'menu_order' => (int) $post->menu_order,
				'home_order' => (int) get_post_meta( $post->ID, '_krm_home_order', true ) ? (int) get_post_meta( $post->ID, '_krm_home_order', true ) : 999,
			);
		}
	} else {
		$data = include KRM_DIR . '/inc/data/ministries.php';
		foreach ( $data['items'] as $slug => $m ) {
			$items[] = array(
				'slug'       => $slug,
				'title'      => $m['title'],
				'desc'       => $m['excerpt'],
				'url'        => krm_ministry_url( $slug ),
				'image'      => $m['explorer_image'],
				'menu_order' => $m['menu_order'],
				'home_order' => $m['home_order'],
			);
		}
	}
	$key = 'home' === $order ? 'home_order' : 'menu_order';
	usort(
		$items,
		function ( $a, $b ) use ( $key ) {
			return $a[ $key ] - $b[ $key ];
		}
	);
	$cache[ $order ] = $items;
	return $items;
}

/**
 * The ministry a story belongs to.
 *
 * @param int $post_id Post ID.
 * @return array{title:string,url:string}
 */
function krm_post_ministry( $post_id ) {
	$ministry_id = (int) get_post_meta( $post_id, '_krm_ministry', true );
	$ministry    = $ministry_id ? get_post( $ministry_id ) : null;
	if ( $ministry && 'ministry' === $ministry->post_type && 'publish' === $ministry->post_status ) {
		return array(
			'title' => get_the_title( $ministry ),
			'url'   => get_permalink( $ministry ),
		);
	}
	return array(
		'title' => 'Kingdom Revival Ministries',
		'url'   => home_url( '/#all-ministries' ),
	);
}

/**
 * Reading time in minutes: the saved value, or an estimate from the word count.
 *
 * @param int $post_id Post ID.
 */
function krm_read_time( $post_id ) {
	$saved = (int) get_post_meta( $post_id, '_krm_read_time', true );
	if ( $saved > 0 ) {
		return $saved;
	}
	$words = str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * First category name of a post (the design shows one tag per story).
 *
 * @param int $post_id Post ID.
 */
function krm_post_category( $post_id ) {
	$cats = get_the_category( $post_id );
	return $cats ? $cats[0]->name : '';
}

/**
 * Cover image reference of a post: featured image, else the design URL saved at import.
 *
 * @param int $post_id Post ID.
 * @return int|string
 */
function krm_post_cover( $post_id ) {
	$id = get_post_thumbnail_id( $post_id );
	return $id ? $id : (string) get_post_meta( $post_id, '_krm_cover_url', true );
}

/* ---------- Icons (assets/icons/*.svg, copied from the design) ---------- */

/**
 * Inline SVG markup of a theme icon. The files are the theme's own, so the markup is trusted.
 *
 * @param string $name Icon file name without extension (check, clock, cal, arrow, zoom, heart, heart18, chev).
 * @return string
 */
function krm_icon( $name ) {
	static $cache = array();
	$name         = sanitize_key( $name );
	if ( ! isset( $cache[ $name ] ) ) {
		$file           = get_theme_file_path( 'assets/icons/' . $name . '.svg' );
		$cache[ $name ] = is_readable( $file ) ? trim( (string) file_get_contents( $file ) ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
	}
	return $cache[ $name ];
}

/* ---------- Logo ---------- */

/**
 * URL of the site logo: the Custom Logo set in the Customizer, else the logo bundled with the theme.
 *
 * @param string $size Image size for an uploaded logo.
 */
function krm_logo_url( $size = 'full' ) {
	$id  = (int) get_theme_mod( 'custom_logo' );
	$url = $id ? wp_get_attachment_image_url( $id, $size ) : '';
	return $url ? $url : krm_asset( 'images/krm-logo.png' );
}

/* ---------- Design page templates (page-templates/*.php) ---------- */

/**
 * Slugs of the pages that have their own design template in page-templates/.
 */
function krm_design_pages() {
	return array( 'about', 'contact', 'donate', 'partner' );
}

/**
 * Which design template the current page uses ('about', 'contact', 'donate', 'partner'), or ''.
 *
 * A page uses one when it is assigned in Page Attributes → Template, or, with no template assigned, when its slug
 * matches (see the page_template_hierarchy filter in inc/setup.php).
 */
function krm_design_template() {
	if ( ! is_page() ) {
		return '';
	}
	$template = get_page_template_slug( get_queried_object_id() );
	if ( $template ) {
		return preg_match( '#^page-templates/([a-z]+)\.php$#', $template, $m ) && in_array( $m[1], krm_design_pages(), true ) ? $m[1] : '';
	}
	$slug = get_queried_object()->post_name;
	return in_array( $slug, krm_design_pages(), true ) ? $slug : '';
}
