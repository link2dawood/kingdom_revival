<?php
/**
 * Menus rendered with the design's exact markup.
 *
 * A menu item that has children becomes a <button class="sub-toggle"> with a nested <ul class="submenu">,
 * which is what assets/js/site.js and the design's CSS expect. Without an assigned menu, the design's own
 * menu is generated from the pages and ministries.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Menu tree for a location: a list of nodes { title, url, classes, ministry, current, ancestor, children }.
 *
 * @param string $location Menu location.
 * @return array|null Null when no menu is assigned.
 */
function krm_menu_tree( $location ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return null;
	}
	$items = wp_get_nav_menu_items( $locations[ $location ], array( 'update_post_term_cache' => false ) );
	if ( ! $items ) {
		return null;
	}
	_wp_menu_item_classes_by_context( $items );

	$nodes = array();
	foreach ( $items as $item ) {
		$ministry = ( 'ministry' === $item->object && 'post_type' === $item->type ) ? get_post_field( 'post_name', $item->object_id ) : '';
		// Keep only classes typed in the menu editor (e.g. "menu-all"), not WordPress's generated ones.
		$classes            = array_filter(
			(array) $item->classes,
			function ( $c ) {
				return '' !== $c && ! preg_match( '/^(menu-item|current|page[_-]item)/', $c );
			}
		);
		$url                = krm_menu_url( $item->url );
		$nodes[ $item->ID ] = array(
			'title'    => $item->title,
			'url'      => $url,
			'classes'  => array_values( array_unique( $classes ) ),
			'ministry' => $ministry,
			// The posts page counts as current on single stories, as in the design. WordPress doesn't know a
			// rewritten link ("#contact" → /contact/) is the current page, so that is checked here.
			'current'  => ! empty( $item->current ) || ( is_singular( 'post' ) && in_array( 'current_page_parent', (array) $item->classes, true ) )
				|| ( $url !== $item->url && krm_url_is_current( $url ) ),
			'ancestor' => ! empty( $item->current_item_ancestor ),
			'parent'   => (int) $item->menu_item_parent,
			'children' => array(),
		);
	}
	$tree = array();
	foreach ( array_reverse( array_keys( $nodes ), true ) as $id ) {
		$parent = $nodes[ $id ]['parent'];
		if ( $parent && isset( $nodes[ $parent ] ) ) {
			array_unshift( $nodes[ $parent ]['children'], $nodes[ $id ] );
		}
	}
	foreach ( $nodes as $node ) {
		if ( ! $node['parent'] || ! isset( $nodes[ $node['parent'] ] ) ) {
			$tree[] = $node;
		}
	}
	return $tree;
}

/**
 * The design's main menu, built from pages and ministries (used until a menu is assigned).
 */
function krm_default_primary_tree() {
	$groups = array();
	$terms  = get_terms(
		array(
			'taxonomy'   => 'ministry_group',
			'hide_empty' => true,
			'orderby'    => 'term_order',
		)
	);
	if ( ! is_wp_error( $terms ) && $terms ) {
		usort(
			$terms,
			function ( $a, $b ) {
				return (int) get_term_meta( $a->term_id, 'krm_order', true ) - (int) get_term_meta( $b->term_id, 'krm_order', true );
			}
		);
		foreach ( $terms as $term ) {
			$posts    = get_posts(
				array(
					'post_type'      => 'ministry',
					'posts_per_page' => 100,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'no_found_rows'  => true,
					'tax_query'      => array(
						array(
							'taxonomy' => 'ministry_group',
							'terms'    => $term->term_id,
						),
					), // phpcs:ignore WordPress.DB.SlowDBQuery
				)
			);
			$children = array();
			foreach ( $posts as $p ) {
				$children[] = krm_node( get_the_title( $p ), get_permalink( $p ), array(), $p->post_name, is_single( $p->ID ) );
			}
			$groups[] = krm_node( $term->name, '#', array(), '', false, $children );
		}
	} else {
		$data = include KRM_DIR . '/inc/data/ministries.php';
		foreach ( $data['groups'] as $gslug => $group ) {
			$children = array();
			foreach ( $data['items'] as $slug => $m ) {
				if ( $m['group'] === $gslug ) {
					$current                      = is_singular( 'ministry' ) && get_queried_object()->post_name === $slug;
					$children[ $m['menu_order'] ] = krm_node( $m['title'], krm_ministry_url( $slug ), array(), $slug, $current );
				}
			}
			ksort( $children );
			$groups[] = krm_node( $group['name'], '#', array(), '', false, array_values( $children ) );
		}
	}
	array_unshift( $groups, krm_node( __( 'All ministries', 'kingdom-revival' ), krm_ministries_url(), array( 'menu-all' ), '', is_post_type_archive( 'ministry' ) ) );

	return array(
		krm_node( __( 'Home', 'kingdom-revival' ), home_url( '/' ), array(), '', is_front_page() ),
		krm_node( __( 'Ministries', 'kingdom-revival' ), '#', array(), '', false, $groups ),
		krm_node( __( 'About', 'kingdom-revival' ), krm_page_url( 'about' ), array(), '', 'about' === krm_design_template() ),
		krm_node( __( 'News & Blogs', 'kingdom-revival' ), krm_blog_url(), array(), '', is_home() || is_singular( 'post' ) ),
		krm_node( __( 'Contact', 'kingdom-revival' ), krm_page_url( 'contact' ), array(), '', 'contact' === krm_design_template() ),
	);
}

/**
 * One menu node.
 *
 * @param string $title    Label.
 * @param string $url      Link.
 * @param array  $classes  CSS classes.
 * @param string $ministry Ministry slug, if the node links to one.
 * @param bool   $current  Whether this is the current page.
 * @param array  $children Child nodes.
 */
function krm_node( $title, $url, $classes = array(), $ministry = '', $current = false, $children = array() ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter -- read by compact().
	return compact( 'title', 'url', 'classes', 'ministry', 'current', 'children' ) + array( 'ancestor' => false );
}

/**
 * Does a node or any of its descendants represent the current page?
 *
 * @param array $node Node.
 */
function krm_node_has_current( $node ) {
	foreach ( $node['children'] as $child ) {
		if ( $child['current'] || krm_node_has_current( $child ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Does a node contain links to ministries?
 *
 * @param array $node Node.
 */
function krm_node_has_ministry( $node ) {
	foreach ( $node['children'] as $child ) {
		if ( $child['ministry'] || krm_node_has_ministry( $child ) ) {
			return true;
		}
	}
	return false;
}

/**
 * On the home page, links to one of its sections ("/#field-stories") become in-page anchors. The Home link itself
 * stays the home permalink, as in the global header on every other page.
 *
 * @param string $url Link URL.
 */
function krm_local_url( $url ) {
	if ( ! is_front_page() ) {
		return $url;
	}
	$home = trailingslashit( home_url( '/' ) );
	if ( 0 === strpos( $url, $home . '#' ) ) {
		return substr( $url, strlen( $home ) );
	}
	return $url;
}

/**
 * A menu link that names a design page by an anchor or by its old static file ("#contact", "/#contact",
 * "contact.html", "index.html", "blog-post.html?post=…") points at that page's WordPress permalink instead, so menus
 * built by hand or kept from the previous site reach the real pages on any installation. Anchors of home page
 * sections that are not pages (#all-ministries, #top…) and every other link are returned unchanged.
 *
 * @param string $url Menu item URL.
 * @return string
 */
function krm_menu_url( $url ) {
	$url  = trim( (string) $url );
	$home = untrailingslashit( home_url() );
	$path = (string) wp_parse_url( $home, PHP_URL_PATH );
	$rel  = $url;
	if ( 0 === stripos( $rel, $home ) ) {
		$rel = substr( $rel, strlen( $home ) );
	} elseif ( '' !== $path && 0 === strpos( $rel, $path . '/' ) ) {
		$rel = substr( $rel, strlen( $path ) );
	}
	// WordPress saves a custom link typed as "contact.html" as "http://contact.html".
	$rel = preg_replace( '#^(?:https?://)?\.?/?(?=[a-z-]+\.html(?:[?\#]|$)|\#)#', '', $rel );

	$pages = array(
		'about'   => 'about',
		'contact' => 'contact',
		'donate'  => 'donate',
		'partner' => 'partner',
		'blog'    => 'blog',
	);
	if ( preg_match( '/^#([a-z-]+)$/', $rel, $m ) && isset( $pages[ $m[1] ] ) ) {
		return 'blog' === $m[1] ? krm_blog_url() : krm_page_url( $m[1] );
	}
	if ( ! preg_match( '/^([a-z-]+)\.html(\?[^#]*)?(#.*)?$/', $rel, $m ) ) {
		return $url;
	}
	$query = isset( $m[2] ) ? $m[2] : '';
	$hash  = isset( $m[3] ) ? $m[3] : '';
	parse_str( ltrim( $query, '?' ), $args );
	switch ( $m[1] ) {
		case 'index':
			return home_url( '/' ) . $hash;
		case 'blog':
			return krm_blog_url() . $hash;
		case 'all-ministries':
			return empty( $args['ministry'] ) ? krm_ministries_url() : krm_ministry_url( sanitize_title( $args['ministry'] ) ) . $hash;
		case 'blog-post':
			$post = empty( $args['post'] ) ? null : krm_get_by_path( sanitize_title( $args['post'] ), 'post' );
			return ( $post ? get_permalink( $post ) : krm_blog_url() ) . $hash;
	}
	return isset( $pages[ $m[1] ] ) ? krm_page_url( $pages[ $m[1] ] ) . $query . $hash : $url;
}

/**
 * Does a link (query and anchor ignored) point at the page being viewed?
 *
 * @param string $url Link URL.
 */
function krm_url_is_current( $url ) {
	if ( is_front_page() ) {
		$here = home_url( '/' );
	} elseif ( is_home() ) {
		$here = krm_blog_url();
	} elseif ( is_singular() ) {
		$here = get_permalink();
	} else {
		return false;
	}
	return untrailingslashit( strtok( $url, '?#' ) ) === untrailingslashit( $here );
}

/**
 * Print nodes as the design's menu list items.
 *
 * @param array $nodes Nodes.
 * @param int   $depth Depth.
 */
function krm_render_nodes( $nodes, $depth = 0 ) {
	foreach ( $nodes as $node ) {
		$has_children = ! empty( $node['children'] );
		$classes      = $node['classes'];
		if ( $has_children ) {
			$classes[] = 'has-sub';
			// The design highlights "Ministries" on ministry pages and on the All Ministries page (/ministries/). The
			// home page highlights nothing, as in the design.
			if ( 0 === $depth && ! is_front_page() && ( krm_node_has_current( $node ) || ( is_post_type_archive( 'ministry' ) && krm_node_has_ministry( $node ) ) ) ) {
				$classes[] = 'is-current';
			}
		}
		$attrs = $classes ? ' class="' . esc_attr( implode( ' ', array_unique( $classes ) ) ) . '"' : '';
		if ( $has_children && 0 === $depth ) {
			$attrs .= ' id="' . esc_attr( 'nav-' . sanitize_title( $node['title'] ) ) . '"';
		}
		echo '<li' . $attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
		if ( $has_children ) {
			echo '<button class="sub-toggle" type="button" aria-expanded="false">' . esc_html( $node['title'] ) . krm_icon( 'chev' ) . '</button>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<ul class="submenu">';
			krm_render_nodes( $node['children'], $depth + 1 );
			echo '</ul>';
		} else {
			printf(
				'<a href="%s"%s%s>%s</a>',
				esc_url( krm_local_url( $node['url'] ) ),
				$node['ministry'] ? ' data-ministry="' . esc_attr( $node['ministry'] ) . '"' : '',
				$node['current'] ? ' aria-current="page"' : '',
				esc_html( $node['title'] )
			);
		}
		echo '</li>';
	}
}

/**
 * Print the main menu <ul>.
 */
function krm_primary_menu() {
	$tree = krm_menu_tree( 'primary' );
	echo '<ul class="menu" id="site-menu">';
	krm_render_nodes( null === $tree ? krm_default_primary_tree() : $tree );
	echo '</ul>';
}

/**
 * Flat list of links for a footer location, with a design fallback.
 *
 * @param string $location Menu location.
 * @return array[] { title, url }
 */
function krm_footer_links( $location ) {
	$tree = krm_menu_tree( $location );
	if ( null !== $tree ) {
		return array_map(
			function ( $n ) {
				return array(
					'title' => $n['title'],
					'url'   => $n['url'],
				);
			},
			$tree
		);
	}
	switch ( $location ) {
		case 'footer_quick':
			return array(
				array(
					'title' => __( 'Home', 'kingdom-revival' ),
					'url'   => home_url( '/' ),
				),
				array(
					'title' => __( 'About', 'kingdom-revival' ),
					'url'   => krm_page_url( 'about' ),
				),
				array(
					'title' => __( 'News & Blogs', 'kingdom-revival' ),
					'url'   => krm_blog_url(),
				),
				array(
					'title' => __( 'Contact', 'kingdom-revival' ),
					'url'   => krm_page_url( 'contact' ),
				),
				array(
					'title' => __( 'Become a Partner', 'kingdom-revival' ),
					'url'   => krm_page_url( 'partner' ),
				),
				array(
					'title' => __( 'Donate', 'kingdom-revival' ),
					'url'   => krm_donate_url(),
				),
			);
		case 'footer_ministries':
			$links = array();
			foreach ( array(
				'children-ministry'           => 'Children Ministry',
				'orphan-and-children-care'    => 'Orphan & Children Care',
				'freedom-from-slavery'        => 'Freedom from Slavery',
				'urdu-bibles'                 => 'Urdu Bibles',
				'leadership-and-discipleship' => 'Leadership & Discipleship',
			) as $slug => $title ) {
				$links[] = array(
					'title' => $title,
					'url'   => krm_ministry_url( $slug ),
				);
			}
			$links[] = array(
				'title' => __( 'All ministries →', 'kingdom-revival' ),
				'url'   => krm_ministries_url(),
			);
			return $links;
	}
	return array();
}
