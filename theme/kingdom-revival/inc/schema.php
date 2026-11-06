<?php
/**
 * Structured data (JSON-LD): Organization, WebSite, WebPage, BreadcrumbList, BlogPosting, Event and VideoObject.
 *
 * Every value is something the page shows: the breadcrumb trail (krm_breadcrumbs() prints the visible one too), a
 * story's title, date, cover and author box, an event's date and venue, a sermon's embedded video, and the contact
 * details and founder shown in the footer and on the About page.
 *
 * - No SEO plugin: the theme prints one graph with all of it.
 * - Yoast SEO and Rank Math: the plugin's graph is kept. The theme adds only what it lacks (Event, VideoObject, and
 *   an Organization or BreadcrumbList the plugin did not print) and corrects what does not match the page: the
 *   breadcrumb trail, a story's author and headline, and Rank Math's default "Article" on pages that are not stories.
 * - SureRank and other SEO plugins: the plugin's graph is kept and SureRank's breadcrumb trail is set to the visible
 *   one; the theme prints a separate block with only Event and VideoObject, which those plugins don't build from
 *   the theme's fields.
 *
 * FAQPage is deliberately not used: the donate and partner FAQs are visible, but Google shows FAQ rich results only
 * for well-known government and health sites.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/* ---------- Breadcrumbs (visible trail and BreadcrumbList) ---------- */

/**
 * Breadcrumb trail of the current view: list of [ name, url ]. The last item is the current page.
 */
function krm_breadcrumbs() {
	if ( is_front_page() || is_404() ) {
		return array();
	}
	$trail = array( array( 'Home', home_url( '/' ) ) );
	if ( is_singular( 'ministry' ) ) {
		// Home / What We Do / {Group} / {Ministry} (Phase 21).
		$trail[] = array( __( 'What We Do', 'kingdom-revival' ), krm_ministries_url() );
		$group   = krm_current_ministry_group();
		if ( $group ) {
			$trail[] = array( $group['name'], $group['url'] );
		}
	} elseif ( krm_current_group() ) {
		$trail[] = array( __( 'What We Do', 'kingdom-revival' ), krm_ministries_url() );
	} elseif ( is_singular( 'post' ) ) {
		$trail[] = array( get_option( 'page_for_posts' ) ? get_the_title( (int) get_option( 'page_for_posts' ) ) : __( 'News & Updates', 'kingdom-revival' ), krm_blog_url() );
	} elseif ( is_page() ) {
		// Child pages (Our Impact → Testimonies, Partner With Us → Prayer Request) show their parents.
		foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $ancestor ) {
			$trail[] = array( get_the_title( $ancestor ), get_permalink( $ancestor ) );
		}
	} elseif ( is_singular( array( 'sermon', 'event' ) ) ) {
		$trail[] = array( krm_fill( krm_c( 'types.' . get_post_type( get_queried_object() ) . 's_heading', 'site' ) ), get_post_type_archive_link( get_post_type( get_queried_object() ) ) );
	}

	$title = '';
	if ( is_singular() ) {
		$title = get_the_title( get_queried_object() );
	} elseif ( is_home() ) {
		$title = get_the_title( (int) get_option( 'page_for_posts' ) );
	} elseif ( krm_current_group() ) {
		$title = krm_current_group()['name'];
	} elseif ( is_post_type_archive( 'ministry' ) ) {
		$title = __( 'What We Do', 'kingdom-revival' );
	} elseif ( is_post_type_archive( array( 'sermon', 'event', 'testimonial' ) ) ) {
		$title = krm_fill( krm_c( 'types.' . krm_queried_post_type() . 's_heading', 'site' ) );
	} elseif ( is_tax( 'sermon_series' ) ) {
		$title = single_term_title( '', false );
	} elseif ( is_search() ) {
		$title = __( 'Search', 'kingdom-revival' );
	} elseif ( is_archive() ) {
		$title = get_the_archive_title();
	}
	$title = krm_schema_text( $title );
	if ( '' === $title ) {
		return array();
	}
	$trail[] = array( $title, krm_schema_url() );
	return $trail;
}

/**
 * Print the visible breadcrumb trail.
 *
 * @param string $last_id Optional id attribute for the current-page item.
 */
function krm_breadcrumb_html( $last_id = '' ) {
	$trail = krm_breadcrumbs();
	$last  = count( $trail ) - 1;
	echo '<ol class="breadcrumb">';
	foreach ( $trail as $i => $crumb ) {
		if ( $i === $last ) {
			echo '<li aria-current="page"' . ( $last_id ? ' id="' . esc_attr( $last_id ) . '"' : '' ) . '>' . esc_html( $crumb[0] ) . '</li>';
		} else {
			echo '<li><a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a></li>';
		}
	}
	echo "</ol>\n";
}

/**
 * BreadcrumbList items of the current view.
 */
function krm_schema_breadcrumb_items() {
	$items = array();
	foreach ( krm_breadcrumbs() as $i => $crumb ) {
		$items[] = array_filter(
			array(
				'@type'    => 'ListItem',
				'position' => $i + 1,
				'name'     => $crumb[0],
				'item'     => $crumb[1],
			)
		);
	}
	return $items;
}

/* ---------- Helpers ---------- */

/**
 * Post type of a post type archive (the query var may be an array).
 */
function krm_queried_post_type() {
	$type = get_query_var( 'post_type' );
	return is_array( $type ) ? (string) reset( $type ) : (string) $type;
}

/**
 * Plain text from a title or field: no tags, entities decoded.
 *
 * @param string $text Text.
 */
function krm_schema_text( $text ) {
	return trim( html_entity_decode( wp_strip_all_tags( (string) $text ), ENT_QUOTES, 'UTF-8' ) );
}

/**
 * Canonical address of the current view, or '' (search results, 404).
 */
function krm_schema_url() {
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_singular() ) {
		return get_permalink( get_queried_object() );
	}
	$url = '';
	if ( is_home() ) {
		$url = krm_blog_url();
	} elseif ( krm_current_group() ) {
		$url = krm_current_group()['url'];
	} elseif ( is_post_type_archive() ) {
		$url = get_post_type_archive_link( krm_queried_post_type() );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$url = get_term_link( get_queried_object() );
	}
	if ( ! $url || is_wp_error( $url ) ) {
		return '';
	}
	$paged = (int) get_query_var( 'paged' );
	if ( $paged > 1 ) {
		$url = trailingslashit( $url ) . user_trailingslashit( 'page/' . $paged, 'paged' );
	}
	return $url;
}

/**
 * ISO 8601 date with the site's time zone offset from an event field ("Y-m-d H:i", site time).
 *
 * @param string $local Stored date.
 */
function krm_schema_date( $local ) {
	$date = '' !== (string) $local ? date_create_immutable( $local, wp_timezone() ) : false;
	return $date ? $date->format( 'c' ) : '';
}

/**
 * A sermon's video: the embed shown on the page plus its title, thumbnail and embed address from the provider.
 * The provider is asked once and the answer kept in post meta (a failed lookup is retried after a day).
 *
 * @param int $post_id Sermon ID.
 * @return array url, html, embed, thumbnail, title.
 */
function krm_sermon_video( $post_id ) {
	$url = (string) get_post_meta( $post_id, '_krm_video', true );
	$out = array(
		'url'       => $url,
		'html'      => '',
		'embed'     => '',
		'thumbnail' => '',
		'title'     => '',
	);
	if ( '' === $url ) {
		return $out;
	}
	$cache = get_post_meta( $post_id, '_krm_video_oembed', true );
	if ( is_array( $cache ) && isset( $cache['url'], $cache['html'], $cache['time'] ) && $cache['url'] === $url && ( '' !== $cache['html'] || $cache['time'] > time() - DAY_IN_SECONDS ) ) {
		return array_merge( $out, $cache );
	}
	$html = wp_oembed_get( $url );
	$data = $html ? _wp_oembed_get_object()->get_data( $url ) : false;
	$out  = array(
		'url'       => $url,
		'html'      => $html ? $html : '',
		'embed'     => $html && preg_match( '/<iframe[^>]+src=["\']([^"\']+)/i', $html, $m ) ? esc_url_raw( html_entity_decode( $m[1] ) ) : '',
		'thumbnail' => $data && ! empty( $data->thumbnail_url ) ? esc_url_raw( $data->thumbnail_url ) : '',
		'title'     => $data && ! empty( $data->title ) ? sanitize_text_field( $data->title ) : '',
		'time'      => time(),
	);
	update_post_meta( $post_id, '_krm_video_oembed', wp_slash( $out ) );
	return $out;
}

/**
 * Logo ImageObject: the Custom Logo when set, else the bundled 512×512 logo (Google asks for at least 112×112).
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
		'url'    => krm_asset( 'images/krm-logo-large.png' ),
		'width'  => 512,
		'height' => 512,
	);
}

/* ---------- Nodes ---------- */

/**
 * The ministry: contact details from Settings (shown in the footer), founder from the About page.
 *
 * @param string $id Node @id.
 */
function krm_schema_organization( $id ) {
	$city    = krm_opt( 'address_locality' );
	$address = trim( preg_replace( '/\s*\n\s*/', ', ', krm_opt( 'address' ) ) );
	if ( $city ) {
		// The Address setting ends with the city ("…, Jhang Road, Faisalabad"); it is given separately.
		$address = preg_replace( '/,\s*' . preg_quote( $city, '/' ) . '\.?$/i', '', $address );
	}
	$founder = krm_schema_text( krm_c( 'founder.heading', 'about' ) );
	return array_filter(
		array(
			'@type'     => 'NGO' === krm_opt( 'schema_org_type' ) ? 'NGO' : 'Organization',
			'@id'       => $id,
			'name'      => krm_schema_text( get_bloginfo( 'name' ) ),
			'url'       => home_url( '/' ),
			'logo'      => krm_logo_schema(),
			'email'     => krm_opt( 'email' ),
			'telephone' => krm_opt( 'phone_tel' ),
			'address'   => array_filter(
				array(
					'@type'           => 'PostalAddress',
					'streetAddress'   => $address,
					'addressLocality' => $city,
					'addressRegion'   => krm_opt( 'address_region' ),
					'addressCountry'  => krm_opt( 'address_country' ),
				)
			),
			'founder'   => $founder ? array(
				'@type' => 'Person',
				'name'  => $founder,
			) : '',
			'sameAs'    => array_values( array_filter( array( krm_opt( 'facebook' ), krm_opt( 'youtube' ), krm_opt( 'instagram' ) ) ) ),
		)
	);
}

/**
 * The story author as shown in the author box (Site text → Story pages).
 */
function krm_schema_story_author() {
	return array(
		'@type' => 'Organization',
		'name'  => krm_schema_text( krm_c( 'story.author_name', 'site' ) ),
		'url'   => home_url( '/' ),
	);
}

/**
 * BlogPosting for a story.
 *
 * @param array $ids organization, webpage and url.
 */
function krm_schema_story( $ids ) {
	$post = get_queried_object();
	return array_filter(
		array(
			'@type'            => 'BlogPosting',
			'@id'              => $ids['url'] . '#article',
			'headline'         => krm_schema_text( get_the_title( $post ) ),
			'description'      => krm_meta_description(),
			'image'            => krm_ref_url( krm_post_cover( $post->ID ), 'full' ),
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'author'           => krm_schema_story_author(),
			'publisher'        => array( '@id' => $ids['organization'] ),
			'mainEntityOfPage' => $ids['webpage'] ? array( '@id' => $ids['webpage'] ) : '',
			'articleSection'   => krm_post_category( $post->ID ),
			'inLanguage'       => get_bloginfo( 'language' ),
		)
	);
}

/**
 * Event for an event page with a start date and a venue or address (as shown under When and Where).
 *
 * @param array $ids organization, webpage and url; organization may instead be a full node to embed.
 */
function krm_schema_event( $ids ) {
	$post    = get_queried_object();
	$start   = krm_schema_date( get_post_meta( $post->ID, '_krm_event_start', true ) );
	$venue   = (string) get_post_meta( $post->ID, '_krm_event_venue', true );
	$address = (string) get_post_meta( $post->ID, '_krm_event_address', true );
	if ( ! $start || ( '' === $venue && '' === $address ) ) {
		return array();
	}
	$image = get_post_thumbnail_id( $post );
	return array_filter(
		array(
			'@type'               => 'Event',
			'@id'                 => $ids['url'] . '#event',
			'name'                => krm_schema_text( get_the_title( $post ) ),
			'description'         => krm_meta_description(),
			'url'                 => $ids['url'],
			'image'               => krm_ref_url( $image ? $image : krm_c( 'types.image', 'site' ), 'full' ),
			'startDate'           => $start,
			'endDate'             => krm_schema_date( get_post_meta( $post->ID, '_krm_event_end', true ) ),
			'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
			'location'            => array_filter(
				array(
					'@type'   => 'Place',
					'name'    => $venue,
					'address' => '' !== $address ? array(
						'@type'         => 'PostalAddress',
						'streetAddress' => $address,
					) : '',
				)
			),
			'organizer'           => is_array( $ids['organization'] ) ? $ids['organization'] : array( '@id' => $ids['organization'] ),
			'mainEntityOfPage'    => $ids['webpage'] ? array( '@id' => $ids['webpage'] ) : '',
		)
	);
}

/**
 * VideoObject for a sermon whose video is embedded on the page (a plain link is not a video on the page).
 *
 * @param array $ids webpage and url.
 */
function krm_schema_video( $ids ) {
	$post  = get_queried_object();
	$video = krm_sermon_video( $post->ID );
	if ( '' === $video['html'] ) {
		return array();
	}
	$thumb = $video['thumbnail'];
	if ( ! $thumb ) {
		$image = get_post_thumbnail_id( $post );
		$thumb = krm_ref_url( $image ? $image : krm_c( 'types.image', 'site' ), 'full' );
	}
	return array_filter(
		array(
			'@type'        => 'VideoObject',
			'@id'          => $ids['url'] . '#video',
			'name'         => $video['title'] ? $video['title'] : krm_schema_text( get_the_title( $post ) ),
			'description'  => krm_meta_description(),
			'thumbnailUrl' => $thumb,
			// The date the sermon was published here, shown on the page.
			'uploadDate'   => get_the_date( 'c', $post ),
			'embedUrl'     => $video['embed'],
			'isPartOf'     => $ids['webpage'] ? array( '@id' => $ids['webpage'] ) : '',
		)
	);
}

/**
 * Event / VideoObject node for the current view, if any.
 *
 * @param array $ids organization, webpage and url.
 */
function krm_schema_main_entities( $ids ) {
	$nodes = array();
	if ( is_singular( 'event' ) ) {
		$nodes[] = krm_schema_event( $ids );
	}
	if ( is_singular( 'sermon' ) ) {
		$nodes[] = krm_schema_video( $ids );
	}
	if ( is_post_type_archive( 'ministry' ) ) {
		$nodes[] = krm_schema_ministry_list( $ids );
	}
	return array_values( array_filter( $nodes ) );
}

/**
 * What We Do and its group pages: the ministries as an ItemList, in the order the page shows them.
 *
 * @param array $ids Node ids (url).
 */
function krm_schema_ministry_list( $ids ) {
	$items = array();
	$group = krm_current_group();
	foreach ( array_values( $group ? $group['ministries'] : krm_ministries() ) as $i => $m ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'url'      => $m['url'],
			'name'     => krm_schema_text( $m['title'] ),
		);
	}
	return $items ? array(
		'@type'           => 'ItemList',
		'@id'             => $ids['url'] . '#ministries',
		'name'            => $group ? $group['name'] : __( 'What We Do', 'kingdom-revival' ),
		'numberOfItems'   => count( $items ),
		'itemListElement' => $items,
	) : array();
}

/**
 * The theme's own graph (no SEO plugin).
 */
function krm_schema_graph() {
	$home = home_url( '/' );
	$url  = is_search() || is_404() ? '' : krm_schema_url();
	$ids  = array(
		'organization' => $home . '#organization',
		'website'      => $home . '#website',
		'webpage'      => $url ? $url . '#webpage' : '',
		'url'          => $url,
	);

	$graph   = array( krm_schema_organization( $ids['organization'] ) );
	$graph[] = array(
		'@type'           => 'WebSite',
		'@id'             => $ids['website'],
		'url'             => $home,
		'name'            => krm_schema_text( get_bloginfo( 'name' ) ),
		'publisher'       => array( '@id' => $ids['organization'] ),
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
	if ( ! $url ) {
		return $graph;
	}

	$type = 'WebPage';
	if ( is_home() || is_archive() ) {
		$type = 'CollectionPage';
	} elseif ( 'about' === krm_design_template() ) {
		$type = 'AboutPage';
	} elseif ( 'contact' === krm_design_template() ) {
		$type = 'ContactPage';
	}
	$crumbs  = krm_schema_breadcrumb_items();
	$graph[] = array_filter(
		array(
			'@type'       => $type,
			'@id'         => $ids['webpage'],
			'url'         => $url,
			'name'        => krm_schema_text( wp_get_document_title() ),
			'description' => krm_meta_description(),
			'isPartOf'    => array( '@id' => $ids['website'] ),
			'about'       => is_front_page() || 'AboutPage' === $type ? array( '@id' => $ids['organization'] ) : '',
			'breadcrumb'  => $crumbs ? array( '@id' => $url . '#breadcrumb' ) : '',
			'inLanguage'  => get_bloginfo( 'language' ),
		)
	);
	if ( $crumbs ) {
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => $url . '#breadcrumb',
			'itemListElement' => $crumbs,
		);
	}
	if ( is_singular( 'post' ) ) {
		$graph[] = krm_schema_story( $ids );
	}
	return array_merge( $graph, krm_schema_main_entities( $ids ) );
}

/**
 * Print a JSON-LD block. JSON_HEX_TAG keeps "</script>" in a title from closing the tag.
 *
 * @param array $graph Nodes.
 */
function krm_schema_print( $graph ) {
	if ( ! $graph ) {
		return;
	}
	echo '<script type="application/ld+json" class="krm-schema">' . wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => array_values( $graph ),
		),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
	) . "</script>\n";
}

add_action(
	'wp_head',
	function () {
		$plugin = krm_seo_plugin();
		if ( '' === $plugin ) {
			krm_schema_print( krm_schema_graph() );
		} elseif ( ! in_array( $plugin, array( 'yoast', 'rankmath' ), true ) && krm_schema_url() ) {
			// SureRank and others: only what the plugin doesn't build, with the organizer written out in full.
			$org = krm_schema_organization( home_url( '/#organization' ) );
			unset( $org['@id'] );
			krm_schema_print(
				krm_schema_main_entities(
					array(
						'organization' => $org,
						'webpage'      => '',
						'url'          => krm_schema_url(),
					)
				)
			);
		}
	},
	20
);

/* ---------- Yoast SEO and Rank Math: complete and correct the plugin's graph ---------- */

/**
 * Types of a node as a list.
 *
 * @param mixed $node Node.
 */
function krm_schema_types( $node ) {
	return is_array( $node ) && isset( $node['@type'] ) ? (array) $node['@type'] : array();
}

/**
 * Replace every reference to one @id inside a value.
 *
 * @param mixed  $value Value.
 * @param string $id    @id to replace.
 * @param array  $with  Replacement.
 */
function krm_schema_replace_ref( $value, $id, $with ) {
	if ( ! is_array( $value ) ) {
		return $value;
	}
	if ( isset( $value['@id'] ) && $value['@id'] === $id && ! isset( $value['@type'] ) ) {
		return $with;
	}
	foreach ( $value as $key => $item ) {
		$value[ $key ] = krm_schema_replace_ref( $item, $id, $with );
	}
	return $value;
}

/**
 * Complete and correct an SEO plugin's graph. Keys are kept (Rank Math uses named entries).
 *
 * @param array $graph Plugin graph nodes.
 */
function krm_schema_merge( $graph ) {
	$graph   = is_array( $graph ) ? $graph : array();
	$url     = krm_schema_url();
	$article = array( 'Article', 'BlogPosting', 'NewsArticle' );
	$ids     = array(
		'organization' => '',
		'website'      => '',
		'webpage'      => '',
		'url'          => $url,
	);
	foreach ( $graph as $node ) {
		$types = krm_schema_types( $node );
		$id    = isset( $node['@id'] ) ? $node['@id'] : '';
		if ( ! $ids['organization'] && array_intersect( $types, array( 'Organization', 'NGO' ) ) ) {
			$ids['organization'] = $id;
		} elseif ( ! $ids['website'] && in_array( 'WebSite', $types, true ) ) {
			$ids['website'] = $id;
		} elseif ( ! $ids['webpage'] && array_intersect( $types, array( 'WebPage', 'CollectionPage', 'AboutPage', 'ContactPage', 'ItemPage', 'ProfilePage', 'SearchResultsPage' ) ) ) {
			$ids['webpage'] = $id;
		}
	}

	// Organization: add ours when the plugin has none, else fill in what it left out.
	$ours = krm_schema_organization( home_url( '/#organization' ) );
	if ( ! $ids['organization'] ) {
		$ids['organization'] = $ours['@id'];
		$graph[]             = $ours;
		foreach ( $graph as $key => $node ) {
			if ( ( in_array( 'WebSite', krm_schema_types( $node ), true ) || array_intersect( krm_schema_types( $node ), $article ) ) && empty( $node['publisher'] ) ) {
				$graph[ $key ]['publisher'] = array( '@id' => $ids['organization'] );
			}
		}
	} else {
		foreach ( $graph as $key => $node ) {
			if ( isset( $node['@id'] ) && $node['@id'] === $ids['organization'] ) {
				unset( $ours['@type'], $ours['@id'], $ours['name'] );
				$graph[ $key ] = $node + $ours;
			}
		}
	}

	// BreadcrumbList: the visible trail.
	$crumbs = krm_schema_breadcrumb_items();
	$found  = false;
	foreach ( $graph as $key => $node ) {
		if ( in_array( 'BreadcrumbList', krm_schema_types( $node ), true ) ) {
			if ( $crumbs && ! $found ) {
				$graph[ $key ]['itemListElement'] = $crumbs;
				$found                            = true;
			} else {
				unset( $graph[ $key ] );
			}
		}
	}
	if ( $crumbs && ! $found && $url ) {
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => $url . '#breadcrumb',
			'itemListElement' => $crumbs,
		);
		foreach ( $graph as $key => $node ) {
			if ( isset( $node['@id'] ) && $node['@id'] === $ids['webpage'] && empty( $node['breadcrumb'] ) ) {
				$graph[ $key ]['breadcrumb'] = array( '@id' => $url . '#breadcrumb' );
			}
		}
	}
	if ( ! $crumbs ) {
		foreach ( $graph as $key => $node ) {
			unset( $graph[ $key ]['breadcrumb'] );
		}
	}

	// Stories: the headline is the page's title and the author is the author box, not the WordPress user.
	if ( is_singular( 'post' ) ) {
		$title  = krm_schema_text( get_the_title( get_queried_object() ) );
		$author = krm_schema_story_author();
		foreach ( $graph as $key => $node ) {
			if ( ! array_intersect( krm_schema_types( $node ), $article ) ) {
				continue;
			}
			// Keep a custom headline; replace one that is the title plus " - Site name".
			if ( isset( $node['headline'] ) && 0 === strpos( krm_schema_text( $node['headline'] ), $title ) ) {
				$graph[ $key ]['headline'] = $title;
			}
			$person = isset( $node['author']['@id'] ) ? $node['author']['@id'] : '';
			if ( $person && $person !== $ids['organization'] ) {
				foreach ( $graph as $k => $n ) {
					$graph[ $k ] = isset( $n['@id'] ) && $n['@id'] === $person ? $n : krm_schema_replace_ref( $n, $person, $author );
				}
			}
			$graph[ $key ]['author'] = $author;
		}
	}

	// Event and VideoObject, when the plugin has none.
	foreach ( krm_schema_main_entities( $ids ) as $entity ) {
		$exists = false;
		foreach ( $graph as $node ) {
			$exists = $exists || in_array( $entity['@type'], krm_schema_types( $node ), true );
		}
		if ( ! $exists ) {
			$graph[] = $entity;
		}
	}

	// Drop Person nodes nothing points to any more (the WordPress user who published a story or page).
	$json = wp_json_encode( $graph );
	foreach ( $graph as $key => $node ) {
		if ( in_array( 'Person', krm_schema_types( $node ), true ) && ! array_intersect( krm_schema_types( $node ), array( 'Organization' ) ) && isset( $node['@id'] ) && substr_count( $json, wp_json_encode( $node['@id'] ) ) < 2 ) {
			unset( $graph[ $key ] );
		}
	}
	return $graph;
}

add_filter(
	'wpseo_schema_graph',
	function ( $graph ) {
		return array_values( krm_schema_merge( $graph ) );
	},
	20
);

add_filter(
	'rank_math/json_ld',
	function ( $data ) {
		if ( ! is_array( $data ) ) {
			return $data;
		}
		// Rank Math gives every page an "Article" by default; only stories are articles. A schema chosen in the
		// page's Rank Math Schema tab is kept.
		if ( is_singular() && ! is_singular( 'post' ) && ! ( class_exists( '\RankMath\Schema\DB' ) && \RankMath\Schema\DB::get_schemas( get_queried_object_id() ) ) ) {
			foreach ( $data as $key => $node ) {
				if ( isset( $node['@id'] ) && '#richSnippet' === substr( $node['@id'], -12 ) && array_intersect( krm_schema_types( $node ), array( 'Article', 'BlogPosting', 'NewsArticle' ) ) ) {
					unset( $data[ $key ] );
				}
			}
		}
		return krm_schema_merge( $data );
	},
	100
);

/* Rank Math prints no WebSite/WebPage on content types without a default schema type (ministries, sermons, events). */
add_filter(
	'rank_math/schema/add_global_entities',
	function ( $add ) {
		return is_singular( array( 'ministry', 'sermon', 'event' ) ) ? true : $add;
	}
);

/* SureRank: its BreadcrumbList follows the visible trail. */
add_filter(
	'surerank_schema_data',
	function ( $data ) {
		if ( ! is_array( $data ) || ! isset( $data['current'] ) ) {
			return $data;
		}
		$items = array();
		foreach ( krm_breadcrumbs() as $crumb ) {
			if ( $crumb[1] ) {
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => count( $items ) + 1,
					'item'     => array(
						'@id'  => $crumb[1],
						'name' => $crumb[0],
					),
				);
			}
		}
		// Without a visible trail (home, ministries list) SureRank's own one is kept: an empty list would print an
		// empty BreadcrumbList.
		if ( $items ) {
			$data['current']['breadcrumbs'] = $items;
		}
		return $data;
	}
);
