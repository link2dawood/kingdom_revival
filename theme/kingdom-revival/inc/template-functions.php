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
		'phone'               => '+92 302 0692993',
		'phone_tel'           => '+923020692993',
		'email'               => 'Info@kingdomrevivalministries.com',
		'address'             => 'Chak No 220, RB Nasrat Colony, Street No. 3, Shadab Puli, Jhang Road, Faisalabad',
		'whatsapp'            => '923020692993',
		'schema_org_type'     => 'Organization',
		'address_locality'    => 'Faisalabad',
		'address_region'      => 'Punjab',
		'address_country'     => 'PK',
		'facebook'            => 'https://www.facebook.com/share/19W1bbmX9F/?mibextid=wwXIfr',
		'youtube'             => '',
		'instagram'           => 'https://www.instagram.com/kingdomrevivalministries7/',
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
		'bank_phone'          => '+92 302 0692993',
		'form_recipient'      => 'Info@kingdomrevivalministries.com',
		// Web3Forms access key (public by design: it only lets forms send to the inbox it was created for).
		'web3forms_key'       => '4b1ed194-65df-4d6e-907b-f4e473b11f70',
		'newsletter_endpoint' => '',
		'newsletter_delay'    => 3,
		'redirects'           => krm_default_redirects(),
		'image_format'        => 'webp',
		'trim_assets'         => 'on',
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
		'stat_6_value'        => '18',
		'stat_6_label'        => 'Clean water hand pumps installed',
		// The home page design words two labels differently from the about page.
		'home_stat_1_label'   => 'Communities reached',
		'home_stat_2_label'   => 'Churches planted incl. house churches',
		'home_stat_3_label'   => 'Children supported',
		'home_stat_4_label'   => 'Urdu Bibles distributed',
		'home_stat_5_label'   => 'Baptisms',
		'home_stat_6_label'   => 'Clean water hand pumps installed',
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
 * The six impact stats. The numbers are shared; the home page has its own labels.
 *
 * @param string $context 'home' for the home page labels, '' for the about page labels.
 */
function krm_stats( $context = '' ) {
	$stats  = array();
	$prefix = 'home' === $context ? 'home_' : '';
	for ( $i = 1; $i <= 6; $i++ ) {
		$stats[] = array(
			'value' => krm_opt( "stat_{$i}_value" ),
			'label' => krm_plural_label( krm_opt( "{$prefix}stat_{$i}_label" ), krm_opt( "stat_{$i}_value" ) ),
		);
	}
	return $stats;
}

/**
 * A statistic label in the plural when its number is more than 1 (theme 1.23.0): "Church planted" → "Churches planted",
 * "Urdu Bible" → "Urdu Bibles". Only the nouns listed here change, whole words only, so a label already in the plural, or
 * worded some other way, is printed as it was typed.
 *
 * @param string     $label Label as saved.
 * @param string|int $value The number shown with it ("650", "1,200", "80+").
 */
function krm_plural_label( $label, $value ) {
	$label  = (string) $label;
	$number = (float) preg_replace( '/[^0-9.]/', '', (string) $value );
	if ( $number <= 1 || '' === trim( $label ) ) {
		return $label;
	}
	$plurals = array(
		'baptism'   => 'baptisms',
		'believer'  => 'believers',
		'bible'     => 'bibles',
		'camp'      => 'camps',
		'child'     => 'children',
		'church'    => 'churches',
		'community' => 'communities',
		'district'  => 'districts',
		'family'    => 'families',
		'home'      => 'homes',
		'kiln'      => 'kilns',
		'leader'    => 'leaders',
		'life'      => 'lives',
		'meal'      => 'meals',
		'orphan'    => 'orphans',
		'pastor'    => 'pastors',
		'patient'   => 'patients',
		'project'   => 'projects',
		'pump'      => 'pumps',
		'village'   => 'villages',
		'well'      => 'wells',
		'widow'     => 'widows',
	);
	return preg_replace_callback(
		// The counted noun is the last word, or the one before a past participle or "incl." ("Church planted incl. house
		// churches"); a noun used to describe another ("Medical camp patients", "Brick kiln families") is left alone.
		'/\b(' . implode( '|', array_keys( $plurals ) ) . ')\b(?![\'’]s)(?=\s*$|\s+[a-z]+ed\b|\s+incl)/iu',
		function ( $m ) use ( $plurals ) {
			$plural = $plurals[ strtolower( $m[1] ) ];
			if ( strtoupper( $m[1] ) === $m[1] && strlen( $m[1] ) > 1 ) {
				return strtoupper( $plural );
			}
			return ctype_upper( $m[1][0] ) ? ucfirst( $plural ) : $plural;
		},
		$label
	);
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
 *
 * The first call also loads those attachments and their meta into the object cache in two queries; every design
 * image would otherwise cost two queries of its own when its URL is resolved (about 45 on the home page).
 */
function krm_media_map() {
	static $map = null;
	if ( null === $map ) {
		$map = get_option( 'krm_media_map', array() );
		$map = is_array( $map ) ? $map : array();
		if ( $map ) {
			_prime_post_caches( array_unique( array_map( 'intval', array_values( $map ) ) ), false, true );
		}
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
 * Path inside assets/ of the theme's copy of a design image, or '' when the theme has none: "krm-theme:…" photos
 * (not on the old site) and the old site's Elementor thumbnails (inc/data/media-local.php).
 *
 * @param string $url Remote URL from the design.
 */
function krm_media_local( $url ) {
	static $local = null;
	if ( 0 === strpos( $url, 'krm-theme:' ) ) {
		return substr( $url, 10 );
	}
	if ( null === $local ) {
		$local = include KRM_DIR . '/inc/data/media-local.php';
	}
	return isset( $local[ $url ] ) ? $local[ $url ] : '';
}

/**
 * URL for a design image: the imported Media Library copy when available, otherwise the theme's copy, otherwise the
 * original URL.
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
	$local = krm_media_local( $url );
	return '' !== $local ? krm_asset( $local ) : $url;
}

/**
 * Print src (full size, so zooming stays sharp) plus srcset/sizes for an image.
 *
 * With $card, the srcset lists the image's 4:3 card crops when it has them (krm_card_srcset()). Only for boxes that
 * are never taller than 4:3 and show the middle of the photo (object-fit: cover), where the crop looks the same.
 *
 * @param int|string $ref   Attachment ID or design URL.
 * @param string     $sizes Sizes attribute.
 * @param bool       $card  Use the 4:3 card crops.
 */
function krm_img_src( $ref, $sizes = '(max-width: 820px) 100vw, 50vw', $card = false ) {
	$id    = is_numeric( $ref ) ? (int) $ref : krm_media_id( $ref );
	$src   = is_numeric( $ref ) ? wp_get_attachment_image_url( $id, 'full' ) : krm_media( $ref );
	$focal = krm_focal( $ref );
	echo 'src="' . esc_url( (string) $src ) . '"';
	if ( '' !== $focal ) {
		// Where the faces are: effects.js makes it the photo's object-position, so a cropped photo keeps them in view.
		echo ' data-focal="' . esc_attr( $focal ) . '"';
	}
	if ( $id ) {
		// The 4:3 card crops are cut from the middle of the photo, which can lose the faces: full sizes then.
		$srcset = $card && '' === $focal ? krm_card_srcset( $id ) : '';
		if ( ! $srcset ) {
			$srcset = wp_get_attachment_image_srcset( $id, 'full' );
		}
		if ( $srcset ) {
			echo ' srcset="' . esc_attr( $srcset ) . '" sizes="' . esc_attr( $sizes ) . '"';
		}
	}
}

/**
 * Data built by tools/focal/build_focal.py (inc/data/focal.php): face positions and the theme photos' shapes.
 */
function krm_focal_data() {
	static $data = null;
	if ( null === $data ) {
		$file = KRM_DIR . '/inc/data/focal.php';
		$data = is_readable( $file ) ? include $file : array();
		$data = is_array( $data ) ? $data + array( 'focal' => array(), 'ratio' => array() ) : array( 'focal' => array(), 'ratio' => array() );
	}
	return $data;
}

/**
 * Key of a photo in the focal data: its file name, lower case, without extension, size (-300x225), -scaled /
 * -rotated and edit (-e1787…) suffixes, so every copy of one photo shares the key.
 *
 * @param string $name File name, path or URL.
 */
function krm_focal_key( $name ) {
	$name = strtolower( pathinfo( (string) wp_parse_url( $name, PHP_URL_PATH ), PATHINFO_FILENAME ) );
	$name = preg_replace( '/-\d+x\d+$/', '', $name );
	$name = preg_replace( '/-e\d{10,}$/', '', $name );
	$name = preg_replace( '/-(scaled|rotated)$/', '', $name );
	return preg_replace( '/-e\d{10,}$/', '', $name );
}

/**
 * File name behind an image reference: the attachment's file, or the design URL / theme path itself.
 *
 * @param int|string $ref Attachment ID or design URL.
 */
function krm_ref_file( $ref ) {
	$id = is_numeric( $ref ) ? (int) $ref : krm_media_id( (string) $ref );
	if ( $id ) {
		$file = (string) get_post_meta( $id, '_wp_attached_file', true );
		if ( '' !== $file ) {
			return $file;
		}
	}
	return is_numeric( $ref ) ? '' : (string) $ref;
}

/**
 * Focus point of a photo ("x% y%", where its faces are) or '' when no face was found or the photo is unknown.
 *
 * @param int|string $ref Attachment ID or design URL.
 */
function krm_focal( $ref ) {
	$file = krm_ref_file( $ref );
	if ( '' === $file ) {
		return '';
	}
	$data = krm_focal_data();
	$key  = krm_focal_key( $file );
	return isset( $data['focal'][ $key ] ) ? (string) $data['focal'][ $key ] : '';
}

/**
 * Shape of a photo (width / height), or 0 when unknown: attachment metadata, else the theme copy's measured shape.
 *
 * @param int|string $ref Attachment ID or design URL.
 */
function krm_img_ratio( $ref ) {
	$id = is_numeric( $ref ) ? (int) $ref : krm_media_id( (string) $ref );
	if ( $id ) {
		$meta = wp_get_attachment_metadata( $id );
		if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
			return round( $meta['width'] / $meta['height'], 4 );
		}
	}
	$local = is_numeric( $ref ) ? '' : krm_media_local( (string) $ref );
	$data  = krm_focal_data();
	return '' !== $local && isset( $data['ratio'][ $local ] ) ? (float) $data['ratio'][ $local ] : 0.0;
}

/**
 * style attribute giving a gallery tile its photo's shape (--r, used by the .krm-just rows), or '' when unknown.
 *
 * @param int|string $ref Attachment ID or design URL.
 */
function krm_ratio_style( $ref ) {
	$r = krm_img_ratio( $ref );
	return $r > 0 ? ' style="--r: ' . esc_attr( (string) max( 0.4, min( 3.0, $r ) ) ) . '"' : '';
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
		$post = krm_get_by_path( $slug, 'ministry' );
		$data = krm_ministry_data();
		$grp  = isset( $data['items'][ $slug ]['group'] ) ? krm_group_path( $data['items'][ $slug ]['group'] ) . '/' : '';
		// Before Setup has created the posts: the address the ministry will have (/what-we-do/{group}/{slug}/).
		$cache[ $slug ] = $post ? get_permalink( $post ) : home_url( user_trailingslashit( krm_wwd_base() . '/' . $grp . $slug ) );
	}
	return $cache[ $slug ];
}

/**
 * URL of the What We Do page (the ministry archive, /what-we-do/).
 */
function krm_ministries_url() {
	$url = get_post_type_archive_link( 'ministry' );
	return $url ? $url : home_url( user_trailingslashit( krm_wwd_base() ) );
}

/**
 * The What We Do address base ("what-we-do"): the ministry archive and the start of every group and ministry address.
 */
function krm_wwd_base() {
	return 'what-we-do';
}

/**
 * The address segment of a ministry group's page (/what-we-do/{path}/): the data file's "path", else the group slug.
 *
 * @param string $group Group slug (ministry_group term slug).
 */
function krm_group_path( $group ) {
	$data = krm_ministry_data();
	return isset( $data['groups'][ $group ]['path'] ) ? $data['groups'][ $group ]['path'] : sanitize_title( $group );
}

/**
 * URL of a ministry group's page.
 *
 * @param string $group Group slug.
 */
function krm_group_url( $group ) {
	return home_url( user_trailingslashit( krm_wwd_base() . '/' . krm_group_path( $group ) ) );
}

/**
 * The group slug whose page is at /what-we-do/{path}/, or '' when no group has that path.
 *
 * @param string $path Address segment.
 */
function krm_group_by_path( $path ) {
	foreach ( krm_groups( true ) as $slug => $group ) {
		if ( $group['path'] === $path ) {
			return $slug;
		}
	}
	return '';
}

/**
 * The ministry groups, in order: slug => { slug, name, short, path, url, image, intro (raw; may be a [placeholder],
 * see krm_public()), ministries (published, in the client's order) }. Name: the Ministry Group term (Ministries →
 * Ministry Groups), else the data file. Image and intro: Site text → What We Do groups, else the data file; a group
 * added in the admin uses its term description as the intro.
 *
 * @param bool $all Also groups with no published ministry (address lookups); false = only groups with live ministries.
 * @return array[]
 */
function krm_groups( $all = false ) {
	static $cache = null;
	if ( null === $cache ) {
		$data  = krm_ministry_data();
		$cache = array();
		$terms = get_terms(
			array(
				'taxonomy'   => 'ministry_group',
				'hide_empty' => false,
			)
		);
		$terms = is_wp_error( $terms ) ? array() : $terms;
		$order = array();
		foreach ( $data['groups'] as $slug => $g ) {
			// Theme 1.23.3: a saved intro still holding the old "[… to be supplied by the client]" text shows the dummy default.
			$intro = (string) krm_c( 'groups.' . $slug . '_intro', 'site' );
			if ( krm_is_placeholder( $intro ) ) {
				$intro = (string) $g['intro'];
			}
			$cache[ $slug ] = array(
				'slug'  => $slug,
				'name'  => $g['name'],
				'short' => $g['short'],
				'path'  => krm_group_path( $slug ),
				'image' => krm_c( 'groups.' . $slug . '_image', 'site' ),
				'intro' => $intro,
			);
			$order[ $slug ] = (int) $g['order'];
		}
		foreach ( $terms as $term ) {
			$name = wp_specialchars_decode( $term->name, ENT_QUOTES );
			if ( ! isset( $cache[ $term->slug ] ) ) {
				$cache[ $term->slug ] = array(
					'slug'  => $term->slug,
					'name'  => $name,
					'short' => $name,
					'path'  => krm_group_path( $term->slug ),
					'image' => '',
					'intro' => wp_strip_all_tags( (string) $term->description ),
				);
				$order[ $term->slug ] = 99;
			} else {
				$cache[ $term->slug ]['name'] = $name;
			}
			$saved = get_term_meta( $term->term_id, 'krm_order', true );
			if ( '' !== $saved ) {
				$order[ $term->slug ] = (int) $saved;
			}
		}
		foreach ( $cache as $slug => $group ) {
			$cache[ $slug ]['url']        = krm_group_url( $slug );
			$cache[ $slug ]['ministries'] = array();
		}
		foreach ( krm_ministries() as $m ) {
			if ( isset( $cache[ $m['group_slug'] ] ) ) {
				$cache[ $m['group_slug'] ]['ministries'][] = $m;
			}
		}
		foreach ( $cache as $slug => $group ) {
			if ( '' === (string) $group['image'] && $group['ministries'] ) {
				$cache[ $slug ]['image'] = $group['ministries'][0]['image'];
			}
		}
		uksort(
			$cache,
			function ( $a, $b ) use ( $order ) {
				return $order[ $a ] - $order[ $b ];
			}
		);
	}
	if ( $all ) {
		return $cache;
	}
	return array_filter(
		$cache,
		function ( $g ) {
			return (bool) $g['ministries'];
		}
	);
}

/**
 * The group whose page is being viewed (/what-we-do/{group}/), as an item of krm_groups(), or null.
 */
function krm_current_group() {
	if ( ! is_post_type_archive( 'ministry' ) ) {
		return null;
	}
	$slug = krm_group_by_path( (string) get_query_var( 'krm_group' ) );
	$all  = krm_groups( true );
	return '' !== $slug && isset( $all[ $slug ] ) ? $all[ $slug ] : null;
}

/**
 * The group of the ministry being viewed, as an item of krm_groups(), or null.
 */
function krm_current_ministry_group() {
	if ( ! is_singular( 'ministry' ) ) {
		return null;
	}
	$all  = krm_groups( true );
	$slug = krm_ministry_group_slug( get_queried_object() );
	return isset( $all[ $slug ] ) ? $all[ $slug ] : null;
}

/**
 * URL of the Donate page.
 */
function krm_donate_url() {
	return krm_page_url( 'donate' );
}

/**
 * All ministries in a normalised shape: the one list every page reads ministry content from.
 *
 * Each item: slug, title, url, image, group (name), group_short (tab label), group_slug, tag, desc (Excerpt), summary
 * (card text), raised, goal (whole dollars as strings, '' when not set), stats ([ value, label ] pairs), sections
 * (extra panels: heading, text, points), camps ([ date, location, people served ]), churches ([ name, place, planted ]),
 * gifts ([ amount, title, text ]), layout ('freedom' or ''), menu_order, home_order. Only published ministries are listed (a "hidden"
 * ministry of the data file is a draft post, or skipped while the list falls back to the data file).
 * A ministry post's own fields win; anything it has not set falls back to inc/data/ministries.php by slug, and the
 * whole list falls back to that file until Setup has created the posts.
 *
 * @param string $order 'menu' (navigation/donation order) or 'home' (homepage explorer order; ministries whose
 *                      explorer position is 0 are left out).
 * @return array[]
 */
function krm_ministries( $order = 'menu' ) {
	static $cache = array();
	if ( isset( $cache[ $order ] ) ) {
		return $cache[ $order ];
	}
	$data  = krm_ministry_data();
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
			$d     = isset( $data['items'][ $post->post_name ] ) ? $data['items'][ $post->post_name ] : array();
			$image = get_post_meta( $post->ID, '_krm_explorer_image', true );
			if ( ! $image ) {
				$image = get_post_thumbnail_id( $post );
			}
			$groups = get_the_terms( $post, 'ministry_group' );
			$group  = $groups && ! is_wp_error( $groups ) ? $groups[0] : null;
			$home   = get_post_meta( $post->ID, '_krm_home_order', true );
			$meta   = function ( $key, $fallback ) use ( $post ) {
				return metadata_exists( 'post', $post->ID, $key ) ? get_post_meta( $post->ID, $key, true ) : $fallback;
			};
			$stats    = $meta( '_krm_stats', null );
			$sections = $meta( '_krm_sections', null );
			$camps    = $meta( '_krm_camps', null );
			$churches = $meta( '_krm_churches', null );
			$gifts    = $meta( '_krm_gifts', null );
			$items[] = array(
				'id'         => $post->ID,
				'slug'       => $post->post_name,
				// Plain text ("&", not "&amp;"): templates escape it, and links pass it to Donate's fund list.
				'group'      => $group ? wp_specialchars_decode( $group->name, ENT_QUOTES ) : '',
				'group_slug' => $group ? $group->slug : '',
				'title'      => wp_specialchars_decode( get_the_title( $post ), ENT_QUOTES ),
				'desc'       => $post->post_excerpt,
				'summary'    => (string) $meta( '_krm_summary', isset( $d['summary'] ) ? $d['summary'] : '' ),
				'tag'        => (string) $meta( '_krm_tag', isset( $d['tag'] ) ? $d['tag'] : ( $group ? $group->name : '' ) ),
				'raised'     => (string) $meta( '_krm_raised', isset( $d['raised'] ) ? $d['raised'] : '' ),
				'goal'       => (string) $meta( '_krm_goal', isset( $d['goal'] ) ? $d['goal'] : '' ),
				'stats'      => null === $stats ? ( isset( $d['stats'] ) ? $d['stats'] : array() ) : krm_parse_stats( $stats ),
				'sections'   => null === $sections ? ( isset( $d['sections'] ) ? $d['sections'] : array() ) : krm_parse_sections( $sections ),
				'camps'      => null === $camps ? ( isset( $d['camps'] ) ? $d['camps'] : array() ) : krm_parse_rows( $camps, 3 ),
				'churches'   => null === $churches ? ( isset( $d['churches'] ) ? $d['churches'] : array() ) : krm_parse_rows( $churches, 3 ),
				'gifts'      => null === $gifts ? ( isset( $d['gifts'] ) ? $d['gifts'] : array() ) : krm_parse_rows( $gifts, 3 ),
				'layout'     => isset( $d['layout'] ) ? $d['layout'] : '',
				'url'        => get_permalink( $post ),
				'image'      => $image,
				'menu_order' => (int) $post->menu_order,
				'home_order' => '0' === (string) $home ? 0 : ( (int) $home ? (int) $home : 999 ),
				'soon'       => krm_ministry_soon( $post->ID, $post->post_name ),
			);
		}
	} else {
		foreach ( $data['items'] as $slug => $m ) {
			if ( 'live' !== $m['status'] ) {
				continue;
			}
			$items[] = array(
				'id'         => 0,
				'slug'       => $slug,
				'group'      => isset( $data['groups'][ $m['group'] ]['name'] ) ? $data['groups'][ $m['group'] ]['name'] : '',
				'group_slug' => $m['group'],
				'title'      => $m['title'],
				'desc'       => $m['excerpt'],
				'summary'    => $m['summary'],
				'tag'        => $m['tag'],
				'raised'     => $m['raised'],
				'goal'       => $m['goal'],
				'stats'      => $m['stats'],
				'sections'   => $m['sections'],
				'camps'      => isset( $m['camps'] ) ? $m['camps'] : array(),
				'churches'   => isset( $m['churches'] ) ? $m['churches'] : array(),
				'gifts'      => isset( $m['gifts'] ) ? $m['gifts'] : array(),
				'layout'     => isset( $m['layout'] ) ? $m['layout'] : '',
				'url'        => krm_ministry_url( $slug ),
				'image'      => $m['explorer_image'],
				'menu_order' => $m['order'],
				'home_order' => $m['home_order'],
				'soon'       => ! empty( $m['coming_soon'] ),
			);
		}
	}
	foreach ( $items as $i => $m ) {
		if ( $m['soon'] ) {
			$items[ $i ] = krm_ministry_soon_strip( $m );
			$m           = $items[ $i ];
		}
		if ( '' === trim( $m['summary'] ) ) {
			$items[ $i ]['summary'] = $m['desc'];
		}
		$items[ $i ]['group_short'] = isset( $data['groups'][ $m['group_slug'] ]['short'] ) ? $data['groups'][ $m['group_slug'] ]['short'] : $m['group'];
	}
	if ( 'home' === $order ) {
		$items = array_values(
			array_filter(
				$items,
				function ( $m ) {
					return $m['home_order'] > 0;
				}
			)
		);
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
 * Is a ministry's page still "coming soon" (Phase 22)? The editor's "Details coming soon" box once saved, else the
 * data file's coming_soon flag.
 *
 * @param int    $post_id Ministry post ID (0 before Setup).
 * @param string $slug    Ministry slug.
 */
function krm_ministry_soon( $post_id, $slug ) {
	if ( $post_id && metadata_exists( 'post', $post_id, '_krm_coming_soon' ) ) {
		return '1' === (string) get_post_meta( $post_id, '_krm_coming_soon', true );
	}
	$data = krm_ministry_data();
	return ! empty( $data['items'][ $slug ]['coming_soon'] );
}

/**
 * A "coming soon" ministry shows no figures it doesn't have yet: no stats, gift cards, extra sections, timelines or
 * fundraising figures, and it stays out of the home page explorer.
 *
 * @param array $m A ministry from krm_ministries().
 * @return array
 */
function krm_ministry_soon_strip( $m ) {
	foreach ( array( 'stats', 'sections', 'camps', 'churches', 'gifts' ) as $key ) {
		$m[ $key ] = array();
	}
	$m['raised']     = '';
	$m['goal']       = '';
	$m['home_order'] = 0;
	$m['layout']     = '';
	// Its card: the coming-soon line while it has no text of its own, its group's photo while it has none.
	if ( '' === trim( (string) $m['summary'] ) || krm_is_placeholder( $m['summary'] ) ) {
		$m['summary'] = __( 'Details about this ministry are coming soon.', 'kingdom-revival' );
	}
	if ( ! $m['image'] && '' !== $m['group_slug'] ) {
		$m['image'] = krm_c( 'groups.' . $m['group_slug'] . '_image', 'site' );
	}
	return $m;
}

/**
 * A ministry's photos: [ reference, alt text ] pairs, from its gallery (or the data file before Setup).
 *
 * @param array $m A ministry from krm_ministries().
 * @return array[]
 */
function krm_ministry_photos( $m ) {
	$out = array();
	if ( $m['id'] ) {
		$alts = (array) get_post_meta( $m['id'], '_krm_photo_alts', true );
		foreach ( array_filter( (array) get_post_meta( $m['id'], '_krm_gallery', true ) ) as $ref ) {
			$out[] = array( $ref, ! empty( $alts[ (string) $ref ] ) ? $alts[ (string) $ref ] : krm_ref_alt( $ref, $m['title'] ) );
		}
		return $out;
	}
	$data = krm_ministry_data();
	return isset( $data['items'][ $m['slug'] ] ) ? $data['items'][ $m['slug'] ]['photos'] : array();
}

/**
 * The ministry data file (inc/data/ministries.php), loaded once.
 *
 * @return array{groups:array,items:array}
 */
function krm_ministry_data() {
	static $data = null;
	if ( null === $data ) {
		$data = include KRM_DIR . '/inc/data/ministries.php';
	}
	return $data;
}

/**
 * Ministry groups in their order, for the What We Do filter tabs and the About page: slug => short name
 * ("Gospel & Spiritual"). Groups no published ministry is in are left out.
 *
 * @param array[] $ministries From krm_ministries().
 * @return string[]
 */
function krm_ministry_groups( $ministries ) {
	$used  = wp_list_pluck( $ministries, 'group_short', 'group_slug' );
	$order = array();
	foreach ( krm_ministry_data()['groups'] as $slug => $group ) {
		$order[ $slug ] = $group['order'];
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'ministry_group',
			'hide_empty' => false,
		)
	);
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$saved = get_term_meta( $term->term_id, 'krm_order', true );
			if ( '' !== $saved ) {
				$order[ $term->slug ] = (int) $saved;
			}
		}
	}
	$groups = array();
	foreach ( $used as $slug => $name ) {
		if ( '' !== $slug ) {
			$groups[ $slug ] = $name;
		}
	}
	uksort(
		$groups,
		function ( $a, $b ) use ( $order ) {
			return ( isset( $order[ $a ] ) ? $order[ $a ] : 99 ) - ( isset( $order[ $b ] ) ? $order[ $b ] : 99 );
		}
	);
	return $groups;
}

/**
 * Impact stats from the "value | label" lines of the ministry editor.
 *
 * @param string|array $raw Lines, or already parsed pairs.
 * @return array[] [ value, label ] pairs.
 */
function krm_parse_stats( $raw ) {
	if ( is_array( $raw ) ) {
		return $raw;
	}
	$out = array();
	foreach ( preg_split( '/\R/', (string) $raw ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( '' !== $parts[0] ) {
			$out[] = array( $parts[0], isset( $parts[1] ) ? $parts[1] : '' );
		}
	}
	return $out;
}

/**
 * Rows of "a | b | c" lines (e.g. medical camps: date | location | people served). Empty lines are skipped.
 *
 * @param string|array $raw  Lines, or already parsed rows.
 * @param int          $cols Number of columns.
 * @return array[]
 */
function krm_parse_rows( $raw, $cols ) {
	if ( is_array( $raw ) ) {
		return $raw;
	}
	$out = array();
	foreach ( preg_split( '/\R/', (string) $raw ) as $line ) {
		if ( '' === trim( $line ) ) {
			continue;
		}
		$out[] = array_pad( array_map( 'trim', explode( '|', $line, $cols ) ), $cols, '' );
	}
	return $out;
}

/**
 * Extra ministry sections from the editor's text: a line "## Heading" starts a section, lines starting with "- " are
 * its points, other lines its text.
 *
 * @param string|array $raw Text, or already parsed sections.
 * @return array[] { heading, text, points }.
 */
function krm_parse_sections( $raw ) {
	if ( is_array( $raw ) ) {
		return $raw;
	}
	$out = array();
	$cur = null;
	foreach ( preg_split( '/\R/', (string) $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		if ( 0 === strpos( $line, '##' ) ) {
			if ( $cur ) {
				$out[] = $cur;
			}
			$cur = array(
				'heading' => trim( substr( $line, 2 ) ),
				'text'    => '',
				'points'  => array(),
			);
			continue;
		}
		if ( ! $cur ) {
			$cur = array(
				'heading' => '',
				'text'    => '',
				'points'  => array(),
			);
		}
		if ( 0 === strpos( $line, '- ' ) ) {
			$cur['points'][] = trim( substr( $line, 2 ) );
		} else {
			$cur['text'] = trim( $cur['text'] . ' ' . $line );
		}
	}
	if ( $cur ) {
		$out[] = $cur;
	}
	return $out;
}

/**
 * Sections as the editor shows them (the reverse of krm_parse_sections()).
 *
 * @param array[] $sections Sections.
 */
function krm_sections_text( $sections ) {
	$lines = array();
	foreach ( (array) $sections as $s ) {
		$lines[] = '## ' . $s['heading'];
		if ( '' !== $s['text'] ) {
			$lines[] = $s['text'];
		}
		foreach ( (array) $s['points'] as $p ) {
			$lines[] = '- ' . $p;
		}
		$lines[] = '';
	}
	return trim( implode( "\n", $lines ) );
}

/**
 * A card amount for display ('$12,500'), or the design's "[Amount]" placeholder when it is not set.
 *
 * @param string $amount Digits.
 */
function krm_card_amount( $amount ) {
	$amount = preg_replace( '/[^0-9]/', '', (string) $amount );
	return '' === $amount ? '[Amount]' : '$' . number_format_i18n( (int) $amount );
}

/**
 * Whether a giving card shows its raised / goal line and progress bar: once a goal is set (administrators always,
 * with "[Amount]" while it is not). The Donate button shows either way.
 *
 * @param string $goal Goal amount.
 */
function krm_show_progress( $goal ) {
	return krm_show_placeholders() || (int) preg_replace( '/[^0-9]/', '', (string) $goal ) > 0;
}

/**
 * Share of the goal raised, 0–100 (0 when either amount is not set).
 *
 * @param array $m A ministry from krm_ministries().
 */
function krm_raised_pct( $m ) {
	$raised = (int) preg_replace( '/[^0-9]/', '', (string) $m['raised'] );
	$goal   = (int) preg_replace( '/[^0-9]/', '', (string) $m['goal'] );
	return $goal > 0 ? (int) min( 100, round( $raised / $goal * 100 ) ) : 0;
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
		'url'   => krm_ministries_url(),
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
 * The News & Updates sections of a post: the top-level categories it is filed under (Blogs, Updates, …).
 *
 * @param int $post_id Post ID.
 * @return string[] Names.
 */
function krm_post_sections( $post_id ) {
	$out = array();
	foreach ( get_the_category( $post_id ) as $cat ) {
		$top = $cat;
		while ( $top->parent ) {
			$parent = get_category( $top->parent );
			if ( ! $parent || is_wp_error( $parent ) ) {
				break;
			}
			$top = $parent;
		}
		$out[ $top->term_id ] = $top->name;
	}
	return array_values( $out );
}

/**
 * The News & Updates filter: every top-level category except "Uncategorized", by name.
 *
 * @return string[] Names.
 */
function krm_news_sections() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'parent'     => 0,
			'hide_empty' => false,
			'exclude'    => array( (int) get_option( 'default_category' ) ),
			'orderby'    => 'name',
		)
	);
	return is_wp_error( $terms ) ? array() : wp_list_pluck( $terms, 'name' );
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
 * URL of the site logo: the Custom Logo set in the Customizer, else the logo bundled with the theme (192×192).
 * For the header, footer and other small places use krm_logo_img(), which lets the browser pick the right size.
 *
 * @param string $size Image size for an uploaded logo.
 */
function krm_logo_url( $size = 'medium' ) {
	$id  = (int) get_theme_mod( 'custom_logo' );
	$url = $id ? wp_get_attachment_image_url( $id, $size ) : '';
	return $url ? $url : krm_asset( 'images/krm-logo.png' );
}

/**
 * The logo as an <img> with a srcset, so each screen downloads only the size it shows: 64, 128 or 192 px of the
 * bundled logo, or the Customizer logo's own WordPress sizes (never the full upload).
 *
 * @param int    $px    Width and height attributes (the design's size).
 * @param string $sizes The sizes attribute: how wide the logo is shown.
 * @param string $alt   Alt text ('' for a decorative copy).
 * @param string $extra Further attributes, already escaped (e.g. ' loading="lazy"').
 */
function krm_logo_img( $px = 64, $sizes = '64px', $alt = '', $extra = '' ) {
	$id = (int) get_theme_mod( 'custom_logo' );
	if ( $id && wp_attachment_is_image( $id ) ) {
		// 'medium' keeps the logo's shape ('thumbnail' is a square crop); the srcset holds its uncropped sizes.
		$src    = wp_get_attachment_image_url( $id, 'medium' );
		$srcset = wp_get_attachment_image_srcset( $id, 'full' );
	} else {
		$src    = '';
		$srcset = '';
	}
	if ( ! $src ) {
		$src    = krm_asset( 'images/krm-logo-64.png' );
		$srcset = krm_asset( 'images/krm-logo-64.png' ) . ' 64w, ' . krm_asset( 'images/krm-logo-128.png' ) . ' 128w, ' . krm_asset( 'images/krm-logo.png' ) . ' 192w';
	}
	printf(
		'<img src="%s"%s alt="%s" width="%d" height="%d"%s>',
		esc_url( $src ),
		$srcset ? ' srcset="' . esc_attr( $srcset ) . '" sizes="' . esc_attr( $sizes ) . '"' : '',
		esc_attr( $alt ),
		(int) $px,
		(int) $px,
		$extra // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attribute strings from templates.
	);
}

/* ---------- Design page templates (page-templates/*.php) ---------- */

/**
 * Slugs of the pages that have their own design template in page-templates/.
 */
function krm_design_pages() {
	return array( 'about', 'contact', 'donate', 'partner', 'impact', 'testimonies', 'gallery', 'prayer' );
}

/**
 * Which design template the current page uses (a key of krm_design_pages()), or ''.
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

/* ---------- Placeholders: shown to admins, hidden from visitors ---------- */

/**
 * Whether placeholder content is shown: to logged-in administrators only. Visitors never see a data-driven section
 * (counters, gift cards, stories, churches, camps, projects, team, testimonies) that has no real data, nor a single
 * counter, card or person that is still a placeholder. Filter 'krm_show_placeholders' to change who sees them.
 */
function krm_show_placeholders() {
	static $show = null;
	if ( null === $show ) {
		$show = (bool) apply_filters( 'krm_show_placeholders', is_user_logged_in() && current_user_can( 'manage_options' ) );
	}
	return $show;
}

/**
 * Whether a value is not real data yet: empty, or holding a [bracketed placeholder] such as "[$ ]" or "[Name]".
 *
 * @param mixed $value Value.
 */
function krm_is_placeholder( $value ) {
	$value = trim( wp_strip_all_tags( (string) $value ) );
	return '' === $value || (bool) preg_match( '/\[[^\]]*\]/', $value );
}

/**
 * The items of a data-driven list that may be shown: all of them for an administrator, else only those whose key
 * value is real (krm_is_placeholder()).
 *
 * @param array           $items Items.
 * @param string|int|callable $key   The item's key holding its value (e.g. 'amount', 0), or a callback returning it.
 * @return array
 */
function krm_visible( $items, $key ) {
	$items = array_values( (array) $items );
	if ( krm_show_placeholders() ) {
		return $items;
	}
	return array_values(
		array_filter(
			$items,
			function ( $item ) use ( $key ) {
				if ( is_callable( $key ) ) {
					$value = call_user_func( $key, $item );
				} else {
					$value = is_array( $item ) && isset( $item[ $key ] ) ? $item[ $key ] : '';
				}
				return ! krm_is_placeholder( $value );
			}
		)
	);
}

/**
 * The one filter for client content printed on the site: for visitors, a value holding a [bracketed placeholder]
 * ("[Amount]", "[00]", "[District]", "[Name]"…) is treated as empty (''); lists and arrays are filtered item by item.
 * Administrators get the value unchanged. krm_c(), krm_text() and krm_inline() pass everything through it; templates
 * printing other client data (ministries, posts' meta) call it directly.
 *
 * @param mixed $value Value.
 * @return mixed
 */
function krm_public( $value ) {
	if ( krm_show_placeholders() ) {
		return $value;
	}
	if ( is_array( $value ) ) {
		return array_map( 'krm_public', $value );
	}
	if ( is_string( $value ) && preg_match( '/\[[^\]]*\]/', $value ) ) {
		return '';
	}
	return $value;
}

/**
 * A text with its [placeholder] parts taken out for visitors, with the separator before them ("Help free a family ·
 * [Amount]" → "Help free a family"). For labels that still make sense without the missing part.
 *
 * @param string $value Text.
 */
function krm_strip_placeholders( $value ) {
	if ( krm_show_placeholders() ) {
		return (string) $value;
	}
	return trim( preg_replace( '/\s*[·|:–—-]?\s*\[[^\]]*\]/u', '', (string) $value ) );
}

/**
 * Print, for administrators only, a small label on a section that visitors do not see until it has real data.
 *
 * @param bool $hidden Whether visitors would not see this section.
 */
function krm_admin_only_note( $hidden = true ) {
	if ( $hidden && krm_show_placeholders() ) {
		echo '<p class="krm-admin-only">' . esc_html__( 'Only administrators see this section: visitors don’t, until it has real content.', 'kingdom-revival' ) . '</p>';
	}
}

/* ---------- Testimonial samples and avatars ---------- */

/**
 * Whether a named dummy testimonial is enabled for public display in inc/data/testimonials.php.
 * Administrators always see samples so they can review and replace them.
 *
 * @param string $name Testimonial name.
 * @return bool
 */
function krm_sample_testimonial_enabled( $name ) {
	if ( krm_show_placeholders() ) {
		return true;
	}
	static $enabled = null;
	if ( null === $enabled ) {
		$enabled = array();
		foreach ( include KRM_DIR . '/inc/data/testimonials.php' as $item ) {
			$enabled[ sanitize_title( $item['name'] ) ] = ! empty( $item['is_sample'] );
		}
	}
	$key = sanitize_title( $name );
	return ! array_key_exists( $key, $enabled ) || $enabled[ $key ];
}

/**
 * Remove disabled sample testimonials from a list for public visitors.
 *
 * @param WP_Post[] $posts Testimonials.
 * @return WP_Post[]
 */
function krm_public_testimonials( $posts ) {
	return array_values(
		array_filter(
			(array) $posts,
			function ( $post ) {
				return ! get_post_meta( $post->ID, '_krm_sample', true ) || krm_sample_testimonial_enabled( get_the_title( $post ) );
			}
		)
	);
}

/**
 * A testimonial avatar: photo when supplied, otherwise the first grapheme of the person's name.
 * Titles are ignored. The deterministic colour class keeps the same person on the same brand pair everywhere.
 *
 * @param string     $name  Person's name.
 * @param int|string $photo Attachment ID or image URL.
 */
function krm_avatar( $name, $photo = null ) {
	$name = trim( wp_strip_all_tags( krm_fill( (string) $name ) ) );
	if ( $photo ) {
		$url = is_numeric( $photo ) ? wp_get_attachment_image_url( (int) $photo, 'thumbnail' ) : krm_ref_url( (string) $photo, 'thumbnail' );
		if ( $url ) {
			echo '<img class="krm-avatar" src="' . esc_url( $url ) . '" alt="" width="48" height="48" loading="lazy" decoding="async" aria-hidden="true">';
			return;
		}
	}
	$untitled = trim( preg_replace( '/^(?:(?:Pastor|Ps\.|Rev\.|Dr\.|Mr\.|Mrs\.|Miss|Bro\.|Sis\.|Brother|Sister)\s+)+/iu', '', $name ) );
	if ( function_exists( 'grapheme_extract' ) ) {
		$letter = grapheme_extract( $untitled, 1, GRAPHEME_EXTR_COUNT );
	} elseif ( function_exists( 'mb_substr' ) ) {
		$letter = mb_substr( $untitled, 0, 1, 'UTF-8' );
	} else {
		$letter = substr( $untitled, 0, 1 );
	}
	$letter = function_exists( 'mb_strtoupper' ) ? mb_strtoupper( (string) $letter, 'UTF-8' ) : strtoupper( (string) $letter );
	$tone   = (int) sprintf( '%u', crc32( function_exists( 'mb_strtolower' ) ? mb_strtolower( $name, 'UTF-8' ) : strtolower( $name ) ) ) % 4;
	echo '<span class="krm-avatar krm-avatar--' . esc_attr( (string) $tone ) . '" aria-hidden="true">';
	if ( '' !== $letter ) {
		echo esc_html( $letter );
	} else {
		echo '<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm0 2c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5Z"/></svg>';
	}
	echo '</span>';
}

/**
 * Whether the Testimonies page is hidden. Never since Phase 21 (the client wants it visible): while no Testimonial is
 * published it shows a short line and the "Share your testimony" form, and the list appears above the form once one
 * is published. Kept so code written against earlier versions still works.
 */
function krm_testimonies_hidden() {
	return false;
}

/**
 * The ID of the published Testimonies page, or 0.
 */
function krm_testimonies_page_id() {
	$pages = krm_design_template_pages();
	return isset( $pages['testimonies'] ) ? (int) $pages['testimonies']->ID : 0;
}

/**
 * Browser UI colours (meta theme-color) for the light and dark themes (Phase 20). The same values as the
 * --color-primary (light) and --color-bg (dark) tokens in theme-global.css.
 *
 * @return string[] { light, dark }
 */
function krm_theme_colors() {
	return array(
		'light' => '#0C1C4F',
		'dark'  => '#0A1230',
	);
}

/**
 * Prints the <head> parts of the light/dark theme: color-scheme and theme-color metas, and the tiny inline script
 * that runs before the stylesheets are applied. It adds the "js" class to <html> and sets data-theme from the
 * visitor's saved choice, so the page never paints in the wrong theme first. With no saved choice the CSS follows
 * the device setting (prefers-color-scheme).
 */
function krm_theme_head() {
	$c = krm_theme_colors();
	?>
	<meta name="color-scheme" content="light dark">
	<meta name="theme-color" content="<?php echo esc_attr( $c['light'] ); ?>" media="(prefers-color-scheme: light)" data-light="<?php echo esc_attr( $c['light'] ); ?>" data-dark="<?php echo esc_attr( $c['dark'] ); ?>">
	<meta name="theme-color" content="<?php echo esc_attr( $c['dark'] ); ?>" media="(prefers-color-scheme: dark)" data-light="<?php echo esc_attr( $c['light'] ); ?>" data-dark="<?php echo esc_attr( $c['dark'] ); ?>">
	<script>(function(h){h.classList.add("js");var t;try{t=localStorage.getItem("krm-theme")}catch(e){}if(t==="dark"||t==="light"){h.setAttribute("data-theme",t);var m=document.querySelectorAll('meta[name="theme-color"]');for(var i=0;i<m.length;i++)m[i].setAttribute("content",m[i].getAttribute("data-"+t))}})(document.documentElement);</script>
	<?php
}

/**
 * The light/dark toggle button (header on desktop, mobile menu). effects.js keeps aria-label and aria-pressed in
 * step with the theme; the sun/moon icon is switched by CSS so it is right from the first paint.
 */
function krm_theme_toggle() {
	?>
	<button class="theme-toggle" type="button" data-theme-toggle aria-pressed="false" aria-label="<?php esc_attr_e( 'Switch to dark mode', 'kingdom-revival' ); ?>">
		<svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"></path></svg>
		<svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="4.5"></circle><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"></path></svg>
	</button>
	<?php
}

/**
 * The footer's policy links (theme 1.23.0): Privacy Policy, Terms & Conditions, Donation/Refund Policy and Financial
 * Accountability, each only once its page is published. The pages come from inc/data/policies.php (Setup and the
 * 1.23.0 update step create them); Privacy Policy is the page chosen in Settings → Privacy when that one is published.
 *
 * @return array[] [ link text, URL ] in the data file's order.
 */
function krm_policy_links() {
	static $links = null;
	if ( null !== $links ) {
		return $links;
	}
	$links = array();
	foreach ( include KRM_DIR . '/inc/data/policies.php' as $slug => $policy ) {
		$page    = null;
		$privacy = 'privacy-policy' === $slug ? (int) get_option( 'wp_page_for_privacy_policy' ) : 0;
		if ( $privacy && 'publish' === get_post_status( $privacy ) ) {
			$page = get_post( $privacy );
		}
		if ( ! $page ) {
			$page = krm_get_by_path( $slug );
		}
		if ( $page && 'publish' === $page->post_status ) {
			$links[] = array( $policy[1], get_permalink( $page ) );
		}
	}
	return $links;
}
