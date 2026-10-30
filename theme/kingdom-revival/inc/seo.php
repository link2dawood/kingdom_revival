<?php
/**
 * SEO defaults: titles, meta descriptions, canonical URLs, Open Graph and JSON-LD.
 *
 * When Rank Math, Yoast SEO, All in One SEO or SEOPress is active, the theme prints none of these tags (the plugin
 * owns them) and only offers its descriptions as fallbacks for pages where the plugin has no description set.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is an SEO plugin active that owns titles, meta tags and schema?
 */
function krm_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
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
		$desc = term_description();
		return $desc ? wp_strip_all_tags( $desc ) : '';
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
		$site = get_bloginfo( 'name', 'display' );
		if ( is_front_page() ) {
			return $site . ' — Home';
		}
		if ( is_page() ) {
			$map    = krm_page_seo();
			$design = krm_design_template();
			if ( $design && isset( $map[ $design ] ) ) {
				return $map[ $design ][0] . ' | ' . $site;
			}
		}
		return $title;
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
		} elseif ( is_post_type_archive( 'ministry' ) ) {
			$canonical = get_post_type_archive_link( 'ministry' );
		} elseif ( is_category() || is_tag() ) {
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
	return krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg' );
}

/* Offer the theme's descriptions to SEO plugins as fallbacks only (no duplicate tags). */
add_filter(
	'wpseo_metadesc',
	function ( $desc ) {
		return '' !== trim( (string) $desc ) ? $desc : krm_meta_description();
	}
);
add_filter(
	'rank_math/frontend/description',
	function ( $desc ) {
		return '' !== trim( (string) $desc ) ? $desc : krm_meta_description();
	}
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
		if ( ! is_404() ) {
			return;
		}
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
		}
		wp_safe_redirect( $to, 301 );
		exit;
	}
);
