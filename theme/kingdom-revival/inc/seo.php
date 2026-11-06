<?php
/**
 * SEO: the design's titles, descriptions and share images, handed to the SEO plugin.
 *
 * Metadata is managed by an SEO plugin (Rank Math or Yoast SEO recommended; SureRank, All in One SEO, SEOPress,
 * The SEO Framework and Slim SEO are detected too). While one is active the theme prints no title format, meta,
 * canonical, Open Graph or Twitter tags of its own: it only offers the design's defaults to Rank Math, Yoast SEO
 * and SureRank for pages where the plugin's own field is empty. Without an SEO plugin the theme prints basic tags
 * itself so the site is never without them. Structured data (JSON-LD) is in inc/schema.php.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * The active SEO plugin: yoast, rankmath, surerank, aioseo, seopress, tsf, slimseo, or '' for none.
 */
function krm_seo_plugin() {
	$plugins = array(
		'yoast'    => 'WPSEO_VERSION',
		'rankmath' => 'RANK_MATH_VERSION',
		'surerank' => 'SURERANK_VERSION',
		'aioseo'   => 'AIOSEO_VERSION',
		'seopress' => 'SEOPRESS_VERSION',
		'tsf'      => 'THE_SEO_FRAMEWORK_VERSION',
		'slimseo'  => 'SLIM_SEO_VER',
	);
	foreach ( $plugins as $name => $constant ) {
		if ( defined( $constant ) ) {
			// Rank Math prints no tags until its setup wizard is completed or skipped; until then the theme keeps
			// printing its own, so the site is never left without metadata.
			if ( 'rankmath' === $name && is_callable( array( 'RankMath\\Helper', 'is_invalid_registration' ) ) && \RankMath\Helper::is_invalid_registration() ) {
				continue;
			}
			return $name;
		}
	}
	return '';
}

/**
 * Is an SEO plugin active that owns titles, meta tags and schema?
 */
function krm_seo_plugin_active() {
	return '' !== krm_seo_plugin();
}

/**
 * The design's title for the current view ("About Us | Kingdom Revival Ministries"), or '' to use the default.
 */
function krm_seo_title() {
	$site = get_bloginfo( 'name', 'display' );
	if ( is_front_page() ) {
		return $site . ' — Home';
	}
	if ( krm_current_group() ) {
		return krm_current_group()['name'] . ' | What We Do | ' . $site;
	}
	if ( is_post_type_archive( 'ministry' ) ) {
		return 'What We Do | ' . $site;
	}
	if ( is_home() ) {
		return wp_strip_all_tags( get_the_title( (int) get_option( 'page_for_posts' ) ) ) . ' | ' . $site;
	}
	if ( is_page() ) {
		$map    = krm_page_seo();
		$design = krm_design_template();
		if ( $design && isset( $map[ $design ] ) ) {
			return $map[ $design ][0] . ' | ' . $site;
		}
	}
	return '';
}

/**
 * Design page titles and descriptions, keyed by page slug.
 */
function krm_page_seo() {
	return array(
		'about'       => array( 'About Us', 'Kingdom Revival Ministries is a Gospel-centred ministry serving Pakistan’s forgotten — preaching Christ, planting churches and caring for orphans, widows and brick kiln families.' ),
		'contact'     => array( 'Contact Us', 'Get in touch with Kingdom Revival Ministries in Faisalabad, Pakistan — for partnership, giving, prayer requests or visits.' ),
		'donate'      => array( 'Donate', 'Give to Kingdom Revival Ministries by PayPal, bank transfer, Western Union or MoneyGram and bring the hope of Jesus to Pakistan’s forgotten.' ),
		'partner'     => array( 'Partner With Us', 'Partner with Kingdom Revival Ministries — as a prayer partner, a financial partner, a church or ministry partner or a volunteer — to carry the Gospel to Pakistan’s forgotten.' ),
		'impact'      => array( 'Our Impact', 'The impact of Kingdom Revival Ministries across Pakistan: numbers for each ministry, testimonies and a photo and video gallery.' ),
		'testimonies' => array( 'Testimonies', 'Testimonies of lives changed through the Gospel and practical care of Kingdom Revival Ministries in Pakistan.' ),
		'gallery'     => array( 'Photo & Video Gallery', 'Photos and videos from every ministry of Kingdom Revival Ministries in Pakistan.' ),
		'prayer'      => array( 'Prayer Request', 'Send a prayer request to Kingdom Revival Ministries. Our pastors and prayer team pray over every request we receive.' ),
	);
}

/**
 * Meta description for the current view.
 */
function krm_meta_description() {
	if ( is_front_page() ) {
		return 'We preach the Gospel, plant churches and care for brick kiln families, orphans and widows across Pakistan and beyond.';
	}
	if ( is_home() ) {
		return 'Stories of hope from the field — read how the Gospel and practical care are changing lives across Pakistan.';
	}
	if ( krm_current_group() ) {
		// The group's intro; while that is still a placeholder, its ministries by name.
		$group = krm_current_group();
		$intro = trim( (string) krm_public( $group['intro'] ) );
		if ( '' !== $intro && ! krm_is_placeholder( $intro ) ) {
			return wp_strip_all_tags( $intro );
		}
		return sprintf(
			/* translators: 1: group name, 2: list of ministries */
			__( '%1$s of Kingdom Revival Ministries in Pakistan: %2$s.', 'kingdom-revival' ),
			$group['name'],
			implode( ', ', wp_list_pluck( $group['ministries'], 'title' ) )
		);
	}
	if ( is_post_type_archive( 'ministry' ) ) {
		return wp_strip_all_tags( krm_fill( krm_c( 'ministries.lead', 'site' ) ) );
	}
	$types = array(
		'sermon'      => 'sermons',
		'event'       => 'events',
		'testimonial' => 'testimonials',
	);
	foreach ( $types as $type => $key ) {
		if ( is_post_type_archive( $type ) ) {
			// The list's introduction (Site text), else the site's tagline.
			$intro = trim( krm_fill( krm_c( 'types.' . $key . '_intro', 'site' ) ) );
			return '' !== $intro ? $intro : krm_fill( krm_c( 'footer.tagline', 'site' ) );
		}
	}
	if ( is_singular() ) {
		$post   = get_queried_object();
		$map    = krm_page_seo();
		$design = krm_design_template();
		if ( $design && isset( $map[ $design ] ) ) {
			return $map[ $design ][1];
		}
		// A ministry whose short description is still a [placeholder] (e.g. one listed as "coming soon", Phase 22):
		// its name and group, so the page never shows the placeholder and every ministry keeps its own description.
		if ( is_singular( 'ministry' ) && ( ! has_excerpt( $post ) || krm_is_placeholder( $post->post_excerpt ) ) ) {
			$m = krm_current_ministry();
			/* translators: 1: ministry name, 2: its group, e.g. "Compassion & Humanitarian Ministries" */
			$desc = sprintf( __( '%1$s: part of the %2$s of Kingdom Revival Ministries in Pakistan.', 'kingdom-revival' ), $m['title'], $m['group'] );
			return $desc . ( ! empty( $m['soon'] ) ? ' ' . __( 'Details about this ministry are coming soon.', 'kingdom-revival' ) : '' );
		}
		if ( has_excerpt( $post ) ) {
			return wp_strip_all_tags( get_the_excerpt( $post ) );
		}
		return wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 28, '…' );
	}
	if ( is_category() || is_tag() || is_tax() ) {
		$desc = trim( wp_strip_all_tags( term_description() ) );
		if ( '' !== $desc ) {
			return $desc;
		}
		// Story categories without their own description: the News & Updates description, named after the category
		// so that every list page has its own description.
		if ( is_tax( 'sermon_series' ) ) {
			return krm_fill( krm_c( 'footer.tagline', 'site' ) );
		}
		$term = get_queried_object();
		$name = $term && isset( $term->name ) ? $term->name : '';
		return ( '' !== $name ? $name . ': stories' : 'Stories' ) . ' of hope from the field — read how the Gospel and practical care are changing lives across Pakistan.';
	}
	return '';
}

/* ---------- Titles (design format: "About Us | Kingdom Revival Ministries") ---------- */

add_filter(
	'document_title_separator',
	function ( $sep ) {
		return krm_seo_plugin_active() ? $sep : '|';
	}
);
add_filter(
	'pre_get_document_title',
	function ( $title ) {
		if ( krm_seo_plugin_active() || '' !== $title ) {
			return $title;
		}
		$design = krm_seo_title();
		return '' !== $design ? $design : $title;
	},
	20
);

/* ---------- Meta tags when no SEO plugin is active ---------- */

add_action(
	'wp_head',
	function () {
		if ( krm_seo_plugin_active() ) {
			return;
		}
		$desc = krm_meta_description();
		if ( $desc ) {
			echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		}

		// Core prints canonical links for single posts and pages; add them for listing pages.
		$canonical = '';
		if ( is_home() && ! is_front_page() ) {
			$canonical = krm_blog_url();
		} elseif ( krm_current_group() ) {
			$canonical = krm_current_group()['url'];
		} elseif ( is_post_type_archive() ) {
			$canonical = get_post_type_archive_link( get_query_var( 'post_type' ) );
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$canonical = get_term_link( get_queried_object() );
		}
		if ( $canonical && ! is_wp_error( $canonical ) ) {
			$paged = (int) get_query_var( 'paged' );
			if ( $paged > 1 ) {
				$canonical = trailingslashit( $canonical ) . user_trailingslashit( 'page/' . $paged, 'paged' );
			}
			echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
		}

		// Open Graph / Twitter.
		$url = is_singular() ? get_permalink() : $canonical;
		if ( ! $url ) {
			$url = home_url( $GLOBALS['wp']->request ? user_trailingslashit( $GLOBALS['wp']->request ) : '/' );
		}
		$image = krm_og_image();
		$tags  = array(
			'og:locale'      => get_locale(),
			'og:site_name'   => get_bloginfo( 'name' ),
			'og:type'        => is_singular( 'post' ) ? 'article' : 'website',
			'og:title'       => wp_get_document_title(),
			'og:description' => $desc,
			'og:url'         => $url,
			'og:image'       => $image,
			'twitter:card'   => 'summary_large_image',
		);
		if ( is_singular( 'post' ) ) {
			$tags['article:published_time'] = get_the_date( 'c' );
			$tags['article:modified_time']  = get_the_modified_date( 'c' );
		}
		foreach ( $tags as $property => $content ) {
			if ( '' === (string) $content ) {
				continue;
			}
			$attr = 0 === strpos( $property, 'twitter:' ) ? 'name' : 'property';
			printf( '<meta %s="%s" content="%s">' . "\n", esc_attr( $attr ), esc_attr( $property ), esc_attr( $content ) );
		}
	},
	3
);

/**
 * Social sharing image for the current view.
 */
function krm_og_image() {
	$screen = krm_current_screen();
	if ( $screen ) {
		// Design pages: the page's hero image (Page content → Hero).
		$hero = krm_c( 'hero.image', $screen );
		if ( $hero ) {
			return krm_ref_url( $hero, 'large' );
		}
	}
	if ( is_singular( 'post' ) ) {
		return krm_ref_url( krm_post_cover( get_queried_object_id() ), 'large' );
	}
	if ( krm_current_group() && krm_current_group()['image'] ) {
		return krm_ref_url( krm_current_group()['image'], 'large' );
	}
	if ( is_singular( 'ministry' ) ) {
		$gallery = (array) get_post_meta( get_queried_object_id(), '_krm_gallery', true );
		if ( ! empty( $gallery[0] ) ) {
			return krm_ref_url( $gallery[0], 'large' );
		}
	}
	if ( is_singular() && has_post_thumbnail() ) {
		return get_the_post_thumbnail_url( null, 'large' );
	}
	if ( is_singular( array( 'sermon', 'event' ) ) || is_post_type_archive( array( 'sermon', 'event', 'testimonial' ) ) || is_tax( 'sermon_series' ) ) {
		return krm_ref_url( krm_c( 'types.image', 'site' ), 'large' );
	}
	return krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg' );
}

/* ---------- Design defaults for SEO plugins (used only where the plugin's own field is empty) ---------- */

/**
 * Does the current page have its own SEO title in the active plugin?
 *
 * @param string $meta_key Post meta key the plugin stores it in.
 */
function krm_has_custom_seo_title( $meta_key ) {
	$id = is_singular() ? get_queried_object_id() : ( is_home() ? (int) get_option( 'page_for_posts' ) : 0 );
	return $id && '' !== trim( (string) get_post_meta( $id, $meta_key, true ) );
}

/**
 * Keep a plugin value, or use the design default when the plugin's value is empty.
 *
 * @param string $value   Plugin value.
 * @param string $fallback Design default.
 */
function krm_seo_fallback( $value, $fallback ) {
	return '' !== trim( wp_strip_all_tags( (string) $value ) ) ? $value : $fallback;
}

/*
 * Group pages (/what-we-do/{group}/, Phase 21) are the ministry archive with a group chosen, so an SEO plugin would
 * give them /what-we-do/ as their canonical address: Yoast SEO and Rank Math get the group page's own address.
 */
foreach ( array( 'wpseo_canonical', 'wpseo_opengraph_url', 'rank_math/frontend/canonical' ) as $krm_hook ) {
	add_filter(
		$krm_hook,
		function ( $url ) {
			return krm_current_group() ? krm_current_group()['url'] : $url;
		}
	);
}

/* Yoast SEO. */
add_filter(
	'wpseo_title',
	function ( $title ) {
		$design = krm_seo_title();
		return '' !== $design && ! krm_has_custom_seo_title( '_yoast_wpseo_title' ) ? $design : $title;
	}
);
foreach ( array( 'wpseo_opengraph_title', 'wpseo_twitter_title' ) as $krm_hook ) {
	add_filter(
		$krm_hook,
		function ( $title ) {
			$design = krm_seo_title();
			// Yoast's own share title, then its SEO title, take precedence over the design title.
			return '' !== $design && ! krm_has_custom_seo_title( '_yoast_wpseo_opengraph-title' ) && ! krm_has_custom_seo_title( '_yoast_wpseo_title' ) ? $design : $title;
		}
	);
}
foreach ( array( 'wpseo_metadesc', 'wpseo_opengraph_desc', 'wpseo_twitter_description' ) as $krm_hook ) {
	add_filter(
		$krm_hook,
		function ( $desc ) {
			return krm_seo_fallback( $desc, krm_meta_description() );
		}
	);
}
add_filter(
	'wpseo_add_opengraph_additional_images',
	function ( $images ) {
		if ( is_object( $images ) && method_exists( $images, 'has_images' ) && ! $images->has_images() && krm_og_image() ) {
			$images->add_image_by_url( krm_og_image() );
		}
		return $images;
	}
);

/* Rank Math. */
add_filter(
	'rank_math/frontend/title',
	function ( $title ) {
		$design = krm_seo_title();
		return '' !== $design && ! krm_has_custom_seo_title( 'rank_math_title' ) ? $design : $title;
	}
);
add_filter(
	'rank_math/frontend/description',
	function ( $desc ) {
		return krm_seo_fallback( $desc, krm_meta_description() );
	}
);
foreach ( array( 'facebook', 'twitter' ) as $krm_network ) {
	add_action(
		"rank_math/opengraph/{$krm_network}/add_additional_images",
		function ( $images ) {
			if ( is_object( $images ) && method_exists( $images, 'has_images' ) && ! $images->has_images() && krm_og_image() ) {
				$images->add_image_by_url( krm_og_image() );
			}
		}
	);
}

/* SureRank (the plugin used on the current live site). */
add_filter(
	'surerank_final_title',
	function ( $title ) {
		$design = krm_seo_title();
		return '' !== $design && ! krm_has_custom_seo_title( 'surerank_settings_page_title' ) ? $design : $title;
	}
);
add_filter(
	'surerank_set_meta',
	function ( $meta ) {
		if ( ! is_array( $meta ) ) {
			return $meta;
		}
		$design = krm_seo_title();
		if ( '' !== $design && ! krm_has_custom_seo_title( 'surerank_settings_page_title' ) ) {
			foreach ( array( 'facebook_title', 'twitter_title' ) as $key ) {
				if ( ! krm_has_custom_seo_title( 'surerank_settings_' . $key ) ) {
					$meta[ $key ] = $design;
				}
			}
		}
		$desc  = krm_meta_description();
		$image = krm_og_image();
		foreach ( array( 'page_description', 'facebook_description', 'twitter_description' ) as $key ) {
			$meta[ $key ] = krm_seo_fallback( isset( $meta[ $key ] ) ? $meta[ $key ] : '', $desc );
		}
		foreach ( array( 'facebook_image_url', 'twitter_image_url' ) as $key ) {
			$meta[ $key ] = krm_seo_fallback( isset( $meta[ $key ] ) ? $meta[ $key ] : '', $image );
		}
		return $meta;
	},
	20
);

/* ---------- Sitemaps & indexing ---------- */

/*
 * Single testimonials redirect to /testimonials/, so they are left out of every sitemap (the list page stays in:
 * Yoast SEO, Rank Math and SureRank list it with the archives, core sitemaps in the archives sitemap below).
 */
add_filter(
	'wp_sitemaps_post_types',
	function ( $types ) {
		unset( $types['testimonial'] );
		return $types;
	}
);
foreach ( array( 'wpseo_sitemap_entry', 'rank_math/sitemap/entry' ) as $krm_hook ) {
	add_filter(
		$krm_hook,
		function ( $url, $type, $item ) {
			// Rank Math passes database rows rather than WP_Post objects.
			return 'post' === $type && isset( $item->post_type ) && 'testimonial' === $item->post_type ? false : $url;
		},
		10,
		3
	);
}
add_filter(
	'surerank_sitemap_posts_cache_args',
	function ( $args, $post_type ) {
		if ( 'testimonial' === $post_type ) {
			$args['post__in'] = array( 0 );
		}
		return $args;
	},
	10,
	2
);

/* Leave out author archives (they expose usernames and add no content; stories are by the ministry team). */
add_filter(
	'wp_sitemaps_add_provider',
	function ( $provider, $name ) {
		return 'users' === $name ? false : $provider;
	},
	10,
	2
);
add_filter( 'wpseo_sitemap_exclude_author', '__return_empty_array' );

/*
 * Addresses in Kingdom Revival → Settings → Redirects are left out of every sitemap. When the theme is installed on
 * the previous site's database, the old Elementor pages (Donate us, Baptisms…), "Hello world!" and Uncategorized are
 * still published but 301 to their new pages, and a sitemap should only list pages that answer 200.
 */

/**
 * IDs of published posts of a type whose address is redirected.
 *
 * @param string $post_type Post type.
 */
function krm_redirected_post_ids( $post_type ) {
	$ids = array();
	foreach ( array_keys( krm_redirects() ) as $path ) {
		$page       = get_page_by_path( trim( $path, '/' ), OBJECT, $post_type );
		$candidates = array( url_to_postid( home_url( user_trailingslashit( $path ) ) ), $page ? $page->ID : 0 );
		foreach ( array_filter( $candidates ) as $id ) {
			if ( get_post_type( $id ) === $post_type && krm_redirect_for( get_permalink( $id ) ) ) {
				$ids[] = $id;
			}
		}
	}
	return array_values( array_unique( $ids ) );
}

/**
 * Sitemap post query (core sitemaps, SureRank): leave out redirected posts.
 *
 * @param array  $args      WP_Query arguments.
 * @param string $post_type Post type.
 */
function krm_sitemap_posts_args( $args, $post_type ) {
	$skip = krm_redirected_post_ids( $post_type );
	if ( $skip ) {
		$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), $skip );
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'krm_sitemap_posts_args', 10, 2 );
add_filter( 'surerank_sitemap_posts_cache_args', 'krm_sitemap_posts_args', 10, 2 );

/**
 * Sitemap term query (core sitemaps, SureRank): leave out redirected terms.
 *
 * @param array  $args     get_terms() arguments.
 * @param string $taxonomy Taxonomy.
 */
function krm_sitemap_terms_args( $args, $taxonomy ) {
	$skip  = array();
	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		)
	);
	foreach ( is_array( $terms ) ? $terms : array() as $term ) {
		$link = get_term_link( $term );
		if ( ! is_wp_error( $link ) && krm_redirect_for( $link ) ) {
			$skip[] = $term->term_id;
		}
	}
	if ( $skip ) {
		$args['exclude'] = array_merge( isset( $args['exclude'] ) ? wp_parse_id_list( $args['exclude'] ) : array(), $skip );
	}
	return $args;
}
add_filter( 'wp_sitemaps_taxonomies_query_args', 'krm_sitemap_terms_args', 10, 2 );
add_filter( 'surerank_sitemap_taxonomies_cache_args', 'krm_sitemap_terms_args', 10, 2 );

foreach ( array( 'wpseo_sitemap_entry', 'rank_math/sitemap/entry' ) as $krm_hook ) {
	add_filter(
		$krm_hook,
		function ( $url ) {
			// An empty entry is skipped by Yoast SEO and Rank Math.
			return is_array( $url ) && ! empty( $url['loc'] ) && krm_redirect_for( $url['loc'] ) ? false : $url;
		}
	);
}

/*
 * Core sitemaps list single entries but not list pages: add /what-we-do/ (What We Do) and its group pages, /sermons/, /events/ and
 * /testimonials/ while they have published items, so an empty list is never submitted.
 */
add_action(
	'wp_sitemaps_init',
	function ( $sitemaps ) {
		if ( ! class_exists( 'KRM_Sitemaps_Archives' ) ) {
			/**
			 * Sitemap of the ministry, sermon, event and testimonial list pages: /wp-sitemap-archives-1.xml.
			 */
			class KRM_Sitemaps_Archives extends WP_Sitemaps_Provider {
				/** Set up the provider. */
				public function __construct() {
					$this->name        = 'archives';
					$this->object_type = 'archive';
				}

				/**
				 * List pages with at least one published item.
				 *
				 * @param int    $page_num       Page of results.
				 * @param string $object_subtype Unused.
				 */
				public function get_url_list( $page_num, $object_subtype = '' ) {
					$urls = array();
					$pages = krm_design_template_pages();
					foreach ( array( 'ministry', 'sermon', 'event', 'testimonial' ) as $type ) {
						if ( 'testimonial' === $type && isset( $pages['testimonies'] ) ) {
							continue; // /testimonials/ redirects to the Testimonies page, which is in the pages sitemap.
						}
						$link = post_type_exists( $type ) ? get_post_type_archive_link( $type ) : false;
						if ( $link && (int) wp_count_posts( $type )->publish > 0 ) {
							$urls[] = array( 'loc' => $link );
						}
						if ( 'ministry' === $type ) {
							// The group pages (/what-we-do/{group}/) of groups with a published ministry (Phase 21).
							foreach ( krm_groups() as $group ) {
								$urls[] = array( 'loc' => $group['url'] );
							}
						}
					}
					return $urls;
				}

				/**
				 * One page at most.
				 *
				 * @param string $object_subtype Unused.
				 */
				public function get_max_num_pages( $object_subtype = '' ) {
					return $this->get_url_list( 1 ) ? 1 : 0;
				}
			}
		}
		$sitemaps->registry->add_provider( 'archives', new KRM_Sitemaps_Archives() );
	}
);

/* ---------- Old static-site URLs → WordPress URLs (301) ---------- */

add_action(
	'template_redirect',
	function () {
		// Matched by path, not only on 404s: "?ministry=…" is also WordPress's own query variable for ministries.
		$path = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
		if ( ! preg_match( '#/(index|about|contact|donate|partner|blog|blog-post|all-ministries)\.html$#', $path, $m ) ) {
			return;
		}
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$ministry = isset( $_GET['ministry'] ) ? sanitize_title( wp_unslash( $_GET['ministry'] ) ) : '';
		$story    = isset( $_GET['post'] ) ? sanitize_title( wp_unslash( $_GET['post'] ) ) : '';
		// phpcs:enable
		switch ( $m[1] ) {
			case 'index':
				$to = home_url( '/' );
				break;
			case 'blog':
				$to = krm_blog_url();
				break;
			case 'blog-post':
				$found = $story ? krm_get_by_path( $story, 'post' ) : null;
				$to    = $found ? get_permalink( $found ) : krm_blog_url();
				break;
			case 'all-ministries':
				$to = $ministry ? krm_ministry_url( $ministry ) : get_post_type_archive_link( 'ministry' );
				break;
			default:
				$to = krm_page_url( $m[1] );
				// Keep the donate/partner pre-fill parameters (?amount=…&for=…, ?type=…) exactly as sent.
				$query = isset( $_SERVER['QUERY_STRING'] ) ? wp_unslash( $_SERVER['QUERY_STRING'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- the whole URL goes through esc_url_raw() below.
				if ( '' !== $query ) {
					$to = esc_url_raw( $to . '?' . $query );
				}
		}
		wp_safe_redirect( $to, 301 );
		exit;
	},
	1 // Before WordPress's own canonical redirect, so each old link takes a single hop.
);

/* ---------- Dashboard: point to an SEO plugin ---------- */

add_action(
	'admin_notices',
	function () {
		$screen = get_current_screen();
		if ( ! $screen || 'toplevel_page_krm-settings' !== $screen->id || ! current_user_can( 'install_plugins' ) ) {
			return;
		}
		if ( defined( 'RANK_MATH_VERSION' ) && ! krm_seo_plugin_active() ) {
			$message = __( 'Rank Math is installed but its setup wizard has not been completed, so it is not printing any SEO tags yet. Until then the theme prints basic tags. Finish the wizard in Rank Math → Dashboard.', 'kingdom-revival' );
		} elseif ( ! krm_seo_plugin_active() ) {
			$message = __( 'No SEO plugin is active. Install Rank Math or Yoast SEO (both free) to manage each page’s SEO title, description, social image and sitemap. Until then the theme prints basic tags itself.', 'kingdom-revival' );
		} else {
			return;
		}
		printf(
			'<div class="notice notice-warning"><p>%1$s <a href="%2$s">%3$s</a></p></div>',
			esc_html( $message ),
			esc_url( admin_url( 'plugin-install.php?s=seo&tab=search&type=term' ) ),
			esc_html__( 'Find an SEO plugin', 'kingdom-revival' )
		);
	}
);
