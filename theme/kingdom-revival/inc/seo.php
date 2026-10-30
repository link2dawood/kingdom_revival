<?php
/**
 * SEO: the design's titles, descriptions and share images, handed to the SEO plugin.
 *
 * Metadata is managed by an SEO plugin (Rank Math or Yoast SEO recommended; SureRank, All in One SEO, SEOPress,
 * The SEO Framework and Slim SEO are detected too). While one is active the theme prints no title format, meta,
 * canonical, Open Graph, Twitter or JSON-LD tags of its own: it only offers the design's defaults to Rank Math,
 * Yoast SEO and SureRank for pages where the plugin's own field is empty. Without an SEO plugin the theme prints
 * basic tags itself so the site is never without them.
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
	if ( is_post_type_archive( 'ministry' ) ) {
		return 'Ministry Details | ' . $site;
	}
	if ( is_home() ) {
		return 'News & Blogs | ' . $site;
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
		'about'   => array( 'About Us', 'Kingdom Revival Ministries is a Gospel-centred ministry serving Pakistan’s forgotten — preaching Christ, planting churches and caring for orphans, widows and brick kiln families.' ),
		'contact' => array( 'Contact Us', 'Get in touch with Kingdom Revival Ministries in Faisalabad, Pakistan — for partnership, giving, prayer requests or visits.' ),
		'donate'  => array( 'Donate', 'Give to Kingdom Revival Ministries by PayPal, bank transfer, Western Union or MoneyGram and bring the hope of Jesus to Pakistan’s forgotten.' ),
		'partner' => array( 'Become a Partner', 'Become a partner with Kingdom Revival Ministries — pray, give monthly, sponsor a ministry or partner as a church to carry the Gospel to Pakistan’s forgotten.' ),
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
	if ( is_post_type_archive( 'ministry' ) ) {
		return 'Choose a ministry to see what it does and how your support reaches people.';
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
		// Story categories without their own description use the News & Blogs description.
		return is_tax( 'sermon_series' ) ? krm_fill( krm_c( 'footer.tagline', 'site' ) ) : 'Stories of hope from the field — read how the Gospel and practical care are changing lives across Pakistan.';
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

/* ---------- Structured data ---------- */

/**
 * ImageObject for the organisation logo: the Custom Logo when set, else the bundled logo (99×99).
 */
function krm_logo_schema() {
	$id    = (int) get_theme_mod( 'custom_logo' );
	$image = $id ? wp_get_attachment_image_src( $id, 'full' ) : false;
	if ( $image ) {
		return array(
			'@type'  => 'ImageObject',
			'url'    => $image[0],
			'width'  => (int) $image[1],
			'height' => (int) $image[2],
		);
	}
	return array(
		'@type'  => 'ImageObject',
		'url'    => krm_asset( 'images/krm-logo.png' ),
		'width'  => 99,
		'height' => 99,
	);
}

/**
 * Visible breadcrumb trail for the current view: list of [ name, url ].
 */
function krm_breadcrumbs() {
	$home  = array( 'Home', home_url( '/' ) );
	$trail = array();
	if ( is_singular( 'ministry' ) ) {
		$trail = array( $home, array( 'Ministries', home_url( '/#all-ministries' ) ), array( get_the_title(), get_permalink() ) );
	} elseif ( is_singular( 'post' ) ) {
		$trail = array( $home, array( 'News & Blogs', krm_blog_url() ), array( get_the_title(), get_permalink() ) );
	} elseif ( is_home() && ! is_front_page() ) {
		$trail = array( $home, array( 'News & Blogs', krm_blog_url() ) );
	} elseif ( is_page() && ! is_front_page() ) {
		$title = get_the_title();
		if ( 'partner' === krm_design_template() ) {
			$title = 'Become a Partner';
		}
		$trail = array( $home, array( $title, get_permalink() ) );
	}
	return $trail;
}

add_action(
	'wp_head',
	function () {
		if ( krm_seo_plugin_active() ) {
			return;
		}
		$home   = home_url( '/' );
		$org_id = $home . '#organization';
		$site   = $home . '#website';
		$same   = array_values( array_filter( array( krm_opt( 'facebook' ), krm_opt( 'youtube' ), krm_opt( 'instagram' ) ) ) );

		$graph   = array();
		$graph[] = array_filter(
			array(
				'@type'     => 'NGO',
				'@id'       => $org_id,
				'name'      => get_bloginfo( 'name' ),
				'url'       => $home,
				'logo'      => krm_logo_schema(),
				'email'     => krm_opt( 'email' ),
				'telephone' => krm_opt( 'phone_tel' ),
				'address'   => array(
					'@type'           => 'PostalAddress',
					'streetAddress'   => krm_opt( 'address' ),
					'addressLocality' => 'Faisalabad',
					'addressRegion'   => 'Punjab',
					'addressCountry'  => 'PK',
				),
				'founder'   => array(
					'@type' => 'Person',
					'name'  => 'Pastor Adil Arif',
				),
				'sameAs'    => $same,
			)
		);
		$graph[] = array(
			'@type'           => 'WebSite',
			'@id'             => $site,
			'url'             => $home,
			'name'            => get_bloginfo( 'name' ),
			'publisher'       => array( '@id' => $org_id ),
			'inLanguage'      => get_bloginfo( 'language' ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => array(
					'@type'       => 'EntryPoint',
					'urlTemplate' => home_url( '/?s={search_term_string}' ),
				),
				'query-input' => 'required name=search_term_string',
			),
		);

		$crumbs = krm_breadcrumbs();
		$url    = is_singular() ? get_permalink() : ( is_home() ? krm_blog_url() : '' );
		if ( is_front_page() ) {
			$url = $home;
		}
		if ( $url ) {
			$page = array(
				'@type'      => is_home() ? 'CollectionPage' : 'WebPage',
				'@id'        => $url . '#webpage',
				'url'        => $url,
				'name'       => wp_get_document_title(),
				'isPartOf'   => array( '@id' => $site ),
				'about'      => array( '@id' => $org_id ),
				'inLanguage' => get_bloginfo( 'language' ),
			);
			$desc = krm_meta_description();
			if ( $desc ) {
				$page['description'] = $desc;
			}
			if ( $crumbs ) {
				$page['breadcrumb'] = array( '@id' => $url . '#breadcrumb' );
			}
			$graph[] = $page;
		}
		if ( $crumbs && $url ) {
			$items = array();
			foreach ( $crumbs as $i => $crumb ) {
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => $i + 1,
					'name'     => wp_strip_all_tags( $crumb[0] ),
					'item'     => $crumb[1],
				);
			}
			$graph[] = array(
				'@type'           => 'BreadcrumbList',
				'@id'             => $url . '#breadcrumb',
				'itemListElement' => $items,
			);
		}
		if ( is_singular( 'post' ) ) {
			$post    = get_queried_object();
			$graph[] = array_filter(
				array(
					'@type'            => 'BlogPosting',
					'@id'              => $url . '#article',
					'headline'         => get_the_title( $post ),
					'description'      => krm_meta_description(),
					'image'            => krm_ref_url( krm_post_cover( $post->ID ), 'full' ),
					'datePublished'    => get_the_date( 'c', $post ),
					'dateModified'     => get_the_modified_date( 'c', $post ),
					'author'           => array(
						'@type' => 'Organization',
						'name'  => 'Kingdom Revival Ministries Team',
						'url'   => $home,
					),
					'publisher'        => array( '@id' => $org_id ),
					'mainEntityOfPage' => array( '@id' => $url . '#webpage' ),
					'articleSection'   => krm_post_category( $post->ID ),
					'inLanguage'       => get_bloginfo( 'language' ),
				)
			);
		}
		echo '<script type="application/ld+json">' . wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		) . "</script>\n";
	},
	20
);

/* ---------- Sitemaps & indexing ---------- */

/*
 * Single testimonials redirect to /testimonials/, so they are left out of every sitemap (the list page stays in).
 */
add_filter(
	'wp_sitemaps_post_types',
	function ( $types ) {
		unset( $types['testimonial'] );
		return $types;
	}
);
add_filter(
	'wpseo_sitemap_exclude_post_type',
	function ( $exclude, $post_type ) {
		return 'testimonial' === $post_type ? true : $exclude;
	},
	10,
	2
);
add_filter(
	'rank_math/sitemap/exclude_post_type',
	function ( $exclude, $post_type ) {
		return 'testimonial' === $post_type ? true : $exclude;
	},
	10,
	2
);

/* Core sitemaps: leave out author archives (they expose usernames and add no content). */
add_filter(
	'wp_sitemaps_add_provider',
	function ( $provider, $name ) {
		return 'users' === $name ? false : $provider;
	},
	10,
	2
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
				$found = $story ? get_page_by_path( $story, OBJECT, 'post' ) : null;
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
