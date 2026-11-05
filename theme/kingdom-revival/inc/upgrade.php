<?php
/**
 * The client's sitemap (theme 1.10.0): shared page, category and menu definitions used by Setup, and the one-time
 * upgrade of sites set up with an earlier version.
 *
 * Upgrade rules (krm_upgrade_to_sitemap(), once per site, option krm_sitemap_version):
 *  - Ministries are renamed to the sitemap's slugs and names (WordPress keeps the old slug, and inc/redirects.php
 *    sends old addresses on), regrouped into the two groups and re-ordered, but only where the value is still the
 *    theme's earlier default. Children Ministry is merged into Children & Orphan Care (its photos are added there and
 *    the old ministry becomes a draft). The new ministries are created; "Youth Ministry / Women Empowerment" as a
 *    draft, so it shows nowhere until it is published.
 *  - Pages: "News & Blogs" (/blog/) becomes "News & Updates" (/news/), "Become a Partner" (/partner/) becomes
 *    "Partner With Us" (/partner-with-us/), when they still have those slugs. Our Impact, Testimonies, Photo & Video
 *    Gallery and Prayer Request are created.
 *  - Categories: "Blogs" and "Updates" are created; the stories' topic categories are filed under Blogs.
 *  - Menus: the menus Setup created are replaced by new ones with the same names; the old ones are kept, renamed
 *    "… (before 1.10)" and unassigned. Menus made by hand are left alone.
 *  - Saved page text that still holds an old default name is updated to the new name.
 *  - A saved home hero video still set to the old default (2026/08/a.mp4) becomes the theme's assets/video/hero.mp4.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/* ---------- Shared definitions ---------- */

/**
 * The page list (inc/data/pages.php).
 *
 * @return array slug => [ title, template, parent slug ].
 */
function krm_page_data() {
	return include KRM_DIR . '/inc/data/pages.php';
}

/**
 * Create the sitemap's pages that do not exist yet (by template first, then by path).
 *
 * @param bool $create Create missing pages (false: only look them up).
 * @return array{ids:array,log:array} slug => page ID, log lines.
 */
function krm_ensure_pages( $create ) {
	$ids = array();
	$log = array();
	foreach ( krm_page_data() as $slug => $page ) {
		list( $title, $template, $parent ) = $page;
		$found = null;
		if ( $template ) {
			$pages = get_posts(
				array(
					'post_type'      => 'page',
					'post_status'    => array( 'publish', 'draft', 'private' ),
					'posts_per_page' => 1,
					'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
					'meta_value'     => 'page-templates/' . $template . '.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
					'orderby'        => 'ID',
					'order'          => 'ASC',
				)
			);
			$found = $pages ? $pages[0] : null;
		}
		if ( ! $found ) {
			$found = krm_get_by_path( ( $parent ? $parent . '/' : '' ) . $slug );
		}
		if ( ! $found && 'news' === $slug && get_option( 'page_for_posts' ) ) {
			$found = get_post( (int) get_option( 'page_for_posts' ) );
		}
		if ( $found ) {
			$wanted_parent = $parent && isset( $ids[ $parent ] ) ? (int) $ids[ $parent ] : 0;
			if ( $create && (int) $found->post_parent !== $wanted_parent ) {
				wp_update_post( array( 'ID' => $found->ID, 'post_parent' => $wanted_parent ) );
			}
			$ids[ $slug ] = $found->ID;
			continue;
		}
		if ( ! $create ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'     => 'page',
				'post_status'   => 'publish',
				'post_title'    => $title,
				'post_name'     => $slug,
				'post_parent'   => $parent && isset( $ids[ $parent ] ) ? $ids[ $parent ] : 0,
				'page_template' => $template ? 'page-templates/' . $template . '.php' : '',
			),
			true
		);
		if ( ! is_wp_error( $id ) ) {
			$ids[ $slug ] = $id;
			$log[]        = sprintf( 'Created page: %s', $title );
		}
	}
	return array(
		'ids' => $ids,
		'log' => $log,
	);
}

/**
 * The News & Updates sections: "Blogs" (the stories' topic categories are filed under it) and "Updates".
 * More can be added in Posts → Categories: every top-level category becomes a filter on the News page.
 */
function krm_ensure_news_categories() {
	$ids = array();
	foreach ( array(
		'blogs'   => 'Blogs',
		'updates' => 'Updates',
	) as $slug => $name ) {
		$term = term_exists( $slug, 'category' );
		if ( ! $term ) {
			$term = wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
		}
		if ( ! is_wp_error( $term ) && $term ) {
			$ids[ $slug ] = (int) $term['term_id'];
		}
	}
	if ( empty( $ids['blogs'] ) ) {
		return;
	}
	$topics = array();
	foreach ( include KRM_DIR . '/inc/data/posts.php' as $p ) {
		$topics[] = $p['category'];
	}
	foreach ( array_unique( $topics ) as $name ) {
		$term = get_term_by( 'name', $name, 'category' );
		if ( $term && 0 === (int) $term->parent && ! in_array( (int) $term->term_id, $ids, true ) ) {
			wp_update_term( $term->term_id, 'category', array( 'parent' => $ids['blogs'] ) );
		}
	}
}

/**
 * The sitemap's menus: location => [ menu name, items ].
 *
 * @param array $ministry_ids Ministry slug => post ID.
 * @param array $page_ids     Page slug => post ID.
 */
function krm_menu_definitions( $ministry_ids, $page_ids ) {
	$data = krm_ministry_data();

	$page_item     = function ( $slug, $title ) use ( $page_ids ) {
		return isset( $page_ids[ $slug ] ) ? array(
			'title'  => $title,
			'type'   => 'post_type',
			'object' => 'page',
			'id'     => $page_ids[ $slug ],
		) : array(
			'title' => $title,
			'url'   => home_url( user_trailingslashit( $slug ) ),
		);
	};
	$ministry_item = function ( $slug ) use ( $ministry_ids, $data ) {
		$title = $data['items'][ $slug ]['title'];
		return isset( $ministry_ids[ $slug ] ) ? array(
			'title'  => $title,
			'type'   => 'post_type',
			'object' => 'ministry',
			'id'     => $ministry_ids[ $slug ],
		) : array(
			'title' => $title,
			'url'   => krm_ministry_url( $slug ),
		);
	};

	$groups = array(
		array(
			'title'   => 'All ministries',
			'archive' => 'ministry',
			'classes' => 'menu-all',
		),
	);
	foreach ( $data['groups'] as $gslug => $group ) {
		$children = array();
		foreach ( $data['items'] as $slug => $m ) {
			if ( $m['group'] === $gslug && 'live' === $m['status'] ) {
				$children[ $m['menu_order'] ] = $ministry_item( $slug );
			}
		}
		ksort( $children );
		$groups[] = array(
			'title'    => $group['name'],
			'url'      => '#',
			'children' => array_values( $children ),
		);
	}

	return array(
		'primary'           => array(
			'Main navigation',
			array(
				array(
					'title' => 'Home',
					'url'   => home_url( '/' ),
				),
				$page_item( 'about', 'About Us' ),
				array(
					'title'    => 'What We Do',
					'url'      => '#',
					'children' => $groups,
				),
				$page_item( 'our-impact', 'Our Impact' ),
				$page_item( 'news', 'News & Updates' ),
				$page_item( 'partner-with-us', 'Partner With Us' ),
				$page_item( 'contact', 'Contact' ),
			),
		),
		'footer_quick'      => array(
			'Footer — Quick Links',
			array(
				array(
					'title' => 'Home',
					'url'   => home_url( '/' ),
				),
				$page_item( 'about', 'About Us' ),
				array(
					'title'   => 'What We Do',
					'archive' => 'ministry',
				),
				$page_item( 'our-impact', 'Our Impact' ),
				$page_item( 'news', 'News & Updates' ),
				$page_item( 'partner-with-us', 'Partner With Us' ),
				$page_item( 'contact', 'Contact' ),
				$page_item( 'donate', 'Donate / Give' ),
			),
		),
		'footer_ministries' => array(
			'Footer — Ministries',
			array(
				$ministry_item( 'evangelism-outreach' ),
				$ministry_item( 'bible-distribution' ),
				$ministry_item( 'children-orphan-care' ),
				$ministry_item( 'slavery-freedom' ),
				$ministry_item( 'brick-kiln-family-care' ),
				array(
					'title'   => 'All ministries →',
					'archive' => 'ministry',
				),
			),
		),
	);
}

/**
 * Add menu items (and their children) to a menu.
 *
 * @param int   $menu_id   Menu ID.
 * @param array $items     Items: title, url, type, object, id, archive (post type), classes, children.
 * @param int   $parent_id Parent menu item ID (0 for top level).
 */
function krm_add_menu_items( $menu_id, $items, $parent_id ) {
	foreach ( $items as $item ) {
		$args = array(
			'menu-item-title'     => $item['title'],
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => $parent_id,
			'menu-item-classes'   => isset( $item['classes'] ) ? $item['classes'] : '',
		);
		if ( isset( $item['archive'] ) ) {
			$args['menu-item-type']   = 'post_type_archive';
			$args['menu-item-object'] = $item['archive'];
		} elseif ( isset( $item['type'] ) ) {
			$args['menu-item-type']      = 'post_type';
			$args['menu-item-object']    = $item['object'];
			$args['menu-item-object-id'] = $item['id'];
		} else {
			$args['menu-item-type'] = 'custom';
			$args['menu-item-url']  = $item['url'];
		}
		$id = wp_update_nav_menu_item( $menu_id, 0, $args );
		if ( ! is_wp_error( $id ) && ! empty( $item['children'] ) ) {
			krm_add_menu_items( $menu_id, $item['children'], $id );
		}
	}
}

/**
 * Ministry slug => post ID for every ministry of the data file that exists (any status).
 */
function krm_ministry_ids() {
	$ids = array();
	foreach ( array_keys( krm_ministry_data()['items'] ) as $slug ) {
		$found = get_posts(
			array(
				'post_type'   => 'ministry',
				'name'        => $slug,
				'post_status' => array( 'publish', 'draft', 'private', 'pending' ),
				'numberposts' => 1,
				'fields'      => 'ids',
			)
		);
		if ( $found ) {
			$ids[ $slug ] = (int) $found[0];
		}
	}
	return $ids;
}

/**
 * Create one ministry post from the data file.
 *
 * @param string $slug      Slug.
 * @param array  $m         Data entry.
 * @param array  $group_ids Group slug => term ID.
 * @param bool   $import    Import the photos into the Media Library (Setup) or reference them (upgrade).
 * @return int|WP_Error
 */
function krm_create_ministry( $slug, $m, $group_ids, $import ) {
	$gallery = array();
	$alts    = array();
	foreach ( $m['photos'] as $photo ) {
		$ref                   = $import && function_exists( 'krm_import_ref' ) ? krm_import_ref( $photo[0] ) : ( krm_media_id( $photo[0] ) ? krm_media_id( $photo[0] ) : $photo[0] );
		$gallery[]             = $ref;
		$alts[ (string) $ref ] = $photo[1];
	}
	$explorer = '';
	if ( $m['explorer_image'] ) {
		$explorer = $import && function_exists( 'krm_import_ref' ) ? krm_import_ref( $m['explorer_image'] ) : ( krm_media_id( $m['explorer_image'] ) ? krm_media_id( $m['explorer_image'] ) : $m['explorer_image'] );
	}
	$id = wp_insert_post(
		array(
			'post_type'    => 'ministry',
			'post_status'  => 'live' === $m['status'] ? 'publish' : 'draft',
			'post_title'   => $m['title'],
			'post_name'    => $slug,
			'post_excerpt' => $m['excerpt'],
			'menu_order'   => $m['menu_order'],
			'meta_input'   => array(
				'_krm_focus'          => $m['focus'],
				'_krm_points'         => implode( "\n", $m['points'] ),
				'_krm_gallery'        => $gallery,
				'_krm_photo_alts'     => $alts,
				'_krm_explorer_image' => $explorer,
				'_krm_home_order'     => $m['home_order'],
			),
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		return $id;
	}
	if ( isset( $group_ids[ $m['group'] ] ) ) {
		wp_set_object_terms( $id, array( $group_ids[ $m['group'] ] ), 'ministry_group' );
	}
	$thumb = $gallery && is_int( $gallery[0] ) ? $gallery[0] : ( is_int( $explorer ) ? $explorer : 0 );
	if ( $thumb ) {
		set_post_thumbnail( $id, $thumb );
	}
	return $id;
}

/**
 * The ministry groups of the data file as terms: group slug => term ID.
 */
function krm_ensure_ministry_groups() {
	$ids = array();
	foreach ( krm_ministry_data()['groups'] as $slug => $group ) {
		$term = term_exists( $slug, 'ministry_group' );
		if ( ! $term ) {
			$term = wp_insert_term( $group['name'], 'ministry_group', array( 'slug' => $slug ) );
		}
		if ( ! is_wp_error( $term ) && $term ) {
			$ids[ $slug ] = (int) $term['term_id'];
			update_term_meta( $ids[ $slug ], 'krm_order', $group['order'] );
		}
	}
	return $ids;
}

/* ---------- Upgrade ---------- */

add_action(
	'init',
	function () {
		if ( (int) get_option( 'krm_sitemap_version' ) >= 1 || ! post_type_exists( 'ministry' ) ) {
			return;
		}
		// One request at a time (the lock expires after ten minutes if a request dies half way).
		$lock = (int) get_option( 'krm_upgrade_lock' );
		if ( $lock && $lock > time() - 600 ) {
			return;
		}
		update_option( 'krm_upgrade_lock', time(), false );
		krm_upgrade_to_sitemap();
		update_option( 'krm_sitemap_version', 1 );
		delete_option( 'krm_upgrade_lock' );
	},
	20
);

/*
 * Second step (Phase 18 decisions, option krm_sitemap_version 2), also on sites that ran the first step:
 *  - page titles "About" → "About Us" and "Donate" → "Donate / Give" (slugs unchanged), when still the old title;
 *  - stories move to /news/{story}/: a permalink structure still set to Setup's /%postname%/ becomes
 *    /news/%postname%/ (old story addresses redirect, inc/redirects.php);
 *  - proposed wording (DRAFT — CLIENT TO APPROVE) replaces the earlier defaults it was written for, where a ministry
 *    or a saved page still holds the earlier default.
 */
add_action(
	'init',
	function () {
		if ( 1 !== (int) get_option( 'krm_sitemap_version' ) ) {
			return;
		}
		update_option( 'krm_sitemap_version', 2 );
		krm_upgrade_sitemap_2();
	},
	21
);

/*
 * Third step (Phase 19 follow-up, option krm_sitemap_version 3): the ministries the data file marks "hidden" become
 * drafts while their description is still the [placeholder] (a ministry someone has written up stays published).
 */
add_action(
	'init',
	function () {
		if ( 2 !== (int) get_option( 'krm_sitemap_version' ) ) {
			return;
		}
		update_option( 'krm_sitemap_version', 3 );
		foreach ( krm_ministry_data()['items'] as $slug => $m ) {
			if ( 'live' === $m['status'] ) {
				continue;
			}
			$post = get_page_by_path( $slug, OBJECT, 'ministry' );
			if ( $post && 'publish' === $post->post_status && krm_is_placeholder( $post->post_excerpt ) ) {
				wp_update_post(
					array(
						'ID'          => $post->ID,
						'post_status' => 'draft',
					)
				);
			}
		}
	},
	22
);

/*
 * Fourth step (option krm_sitemap_version 4): the top bar button reads "Donate", where the saved Site text still holds
 * the earlier default "Donate / Give".
 */
add_action(
	'init',
	function () {
		if ( 3 !== (int) get_option( 'krm_sitemap_version' ) ) {
			return;
		}
		update_option( 'krm_sitemap_version', 4 );
		$site = get_option( 'krm_site_text' );
		if ( is_array( $site ) && isset( $site['header']['donate_label'] ) && 'Donate / Give' === $site['header']['donate_label'] ) {
			$site['header']['donate_label'] = 'Donate';
			update_option( 'krm_site_text', $site, false );
		}
	},
	23
);

/**
 * The second upgrade step. See the comment above.
 */
function krm_upgrade_sitemap_2() {
	foreach ( array(
		'about'  => array( 'About', 'About Us' ),
		'donate' => array( 'Donate', 'Donate / Give' ),
	) as $template => $titles ) {
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'any',
				'posts_per_page' => 5,
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => 'page-templates/' . $template . '.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);
		foreach ( $pages as $page ) {
			if ( wp_specialchars_decode( $page->post_title, ENT_QUOTES ) === $titles[0] ) {
				wp_update_post(
					array(
						'ID'         => $page->ID,
						'post_title' => $titles[1],
					)
				);
			}
		}
	}

	if ( '/%postname%/' === get_option( 'permalink_structure' ) ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/news/%postname%/' );
		// Flush on the next request: category and tag rules were registered in this one with the old base.
		add_action(
			'shutdown',
			function () {
				update_option( 'krm_flush_rewrite', 1 );
			}
		);
	}

	// Ministry excerpts that are still the earlier default get the proposed wording.
	$data = krm_ministry_data();
	foreach ( array(
		'brick-kiln-family-care' => 'Education, care and the Gospel for children growing up beside the kilns.',
		'baptisms-discipleship'  => 'New believers publicly following Jesus Christ in baptism.',
	) as $slug => $old ) {
		$post = krm_get_by_path( $slug, 'ministry' );
		if ( $post && $post->post_excerpt === $old && isset( $data['items'][ $slug ] ) ) {
			wp_update_post(
				array(
					'ID'           => $post->ID,
					'post_excerpt' => $data['items'][ $slug ]['excerpt'],
				)
			);
		}
	}

	// Saved page text still holding a placeholder that now has proposed wording.
	$drafts = array(
		'impact'      => array( 'hero.lead', 'more.testimonies_text', 'more.gallery_text' ),
		'testimonies' => array( 'hero.lead' ),
		'gallery'     => array( 'hero.lead' ),
		'about'       => array( 'join.text' ),
	);
	foreach ( $drafts as $screen => $paths ) {
		$post_id = krm_content_post_id( $screen );
		$saved   = $post_id ? get_post_meta( $post_id, '_krm_content_' . $screen, true ) : array();
		if ( ! is_array( $saved ) || ! $saved ) {
			continue;
		}
		$schema = krm_content_schema( $screen );
		foreach ( $paths as $path ) {
			list( $section, $field ) = explode( '.', $path );
			if ( isset( $saved[ $section ][ $field ] ) && preg_match( '/^\[.*to be supplied by the client\]$/', (string) $saved[ $section ][ $field ] ) ) {
				$saved[ $section ][ $field ] = $schema[ $section ]['fields'][ $field ]['default'];
			}
		}
		update_post_meta( $post_id, '_krm_content_' . $screen, $saved );
	}
	$site = get_option( 'krm_site_text' );
	if ( is_array( $site ) && isset( $site['ministries']['lead'] ) && 'Each ministry carries the hope of Jesus Christ and practical care to children, families and communities across Pakistan. Choose a ministry to see its work, its photos and how you can support it.' === $site['ministries']['lead'] ) {
		$site['ministries']['lead'] = krm_content_schema( 'site' )['ministries']['fields']['lead']['default'];
		update_option( 'krm_site_text', $site, false );
	}
}

/**
 * Earlier theme defaults of each ministry: old slug => [ title, 1.9 menu order, 1.9 home order, 1.10 menu order ].
 */
function krm_old_ministries() {
	return array(
		'children-ministry'           => array( 'Children Ministry', 1, 1, 10 ),
		'orphan-and-children-care'    => array( 'Orphan & Children Care', 2, 2, 11 ),
		'brick-kiln-children'         => array( 'Brick Kiln Children', 3, 3, 12 ),
		'widows-ministry'             => array( 'Widows Ministry', 4, 10, 9 ),
		'freedom-from-slavery'        => array( 'Freedom from Slavery', 5, 4, 13 ),
		'free-medical-camps'          => array( 'Free Medical Camps', 6, 5, 7 ),
		'clean-water-project'         => array( 'Clean Water Project', 7, 6, 8 ),
		'outreach-ministry'           => array( 'Outreach Ministry', 8, 11, 1 ),
		'urdu-bibles'                 => array( 'Urdu Bibles', 9, 7, 3 ),
		'baptisms'                    => array( 'Baptisms', 10, 8, 4 ),
		'deliverance-and-healing'     => array( 'Deliverance & Healing', 11, 9, 6 ),
		'leadership-and-discipleship' => array( 'Leadership & Discipleship', 12, 12, 5 ),
		'church-planting'             => array( 'Church Planting', 0, 0, 2 ),
	);
}

/**
 * Move an earlier site to the client's sitemap. See the file comment.
 */
function krm_upgrade_to_sitemap() {
	$data        = krm_ministry_data();
	$old         = krm_old_ministries();
	$old_groups  = array( 'children-and-families', 'freedom-and-care', 'gospel-and-church', 'evangelism-and-church', 'compassion', 'children-and-education', 'freedom' );
	$group_ids   = krm_ensure_ministry_groups();
	$has_content = (bool) get_posts(
		array(
			'post_type'   => 'ministry',
			'post_status' => 'any',
			'numberposts' => 1,
			'fields'      => 'ids',
		)
	);

	// Ministries.
	if ( $has_content ) {
		foreach ( $data['items'] as $slug => $m ) {
			$post = krm_get_by_path( $slug, 'ministry' );
			$was  = '';
			foreach ( $m['was'] as $old_slug ) {
				if ( $post ) {
					break;
				}
				$post = krm_get_by_path( $old_slug, 'ministry' );
				$was  = $post ? $old_slug : '';
			}
			if ( ! $post ) {
				krm_create_ministry( $slug, $m, $group_ids, false );
				continue;
			}
			$key    = $was ? $was : $slug;
			$update = array( 'ID' => $post->ID );
			if ( $was ) {
				$update['post_name'] = $slug;
			}
			// WordPress stores "&" in titles as "&amp;".
			$title = wp_specialchars_decode( $post->post_title, ENT_QUOTES );
			if ( isset( $old[ $key ] ) && $title === $old[ $key ][0] && $title !== $m['title'] ) {
				$update['post_title'] = $m['title'];
			}
			if ( ! isset( $old[ $key ] ) || in_array( (int) $post->menu_order, array( $old[ $key ][1], $old[ $key ][3] ), true ) ) {
				$update['menu_order'] = $m['menu_order'];
			}
			if ( count( $update ) > 1 ) {
				wp_update_post( $update );
			}
			$home = (string) get_post_meta( $post->ID, '_krm_home_order', true );
			if ( isset( $old[ $key ] ) && in_array( $home, array( '', '0', (string) $old[ $key ][2] ), true ) ) {
				update_post_meta( $post->ID, '_krm_home_order', $m['home_order'] );
			}
			$terms = wp_get_object_terms( $post->ID, 'ministry_group', array( 'fields' => 'slugs' ) );
			if ( ! is_wp_error( $terms ) && ! array_diff( $terms, $old_groups ) && isset( $group_ids[ $m['group'] ] ) ) {
				wp_set_object_terms( $post->ID, array( $group_ids[ $m['group'] ] ), 'ministry_group' );
			}
		}

		// Children Ministry is now part of Children & Orphan Care.
		$merged = krm_get_by_path( 'children-ministry', 'ministry' );
		$into   = krm_get_by_path( 'children-orphan-care', 'ministry' );
		if ( $merged && $into && 'publish' === $merged->post_status ) {
			$gallery = array_values( array_filter( (array) get_post_meta( $into->ID, '_krm_gallery', true ) ) );
			$alts    = (array) get_post_meta( $into->ID, '_krm_photo_alts', true );
			foreach ( array_filter( (array) get_post_meta( $merged->ID, '_krm_gallery', true ) ) as $ref ) {
				if ( ! in_array( $ref, $gallery, true ) ) {
					$gallery[] = $ref;
				}
			}
			$alts += (array) get_post_meta( $merged->ID, '_krm_photo_alts', true );
			update_post_meta( $into->ID, '_krm_gallery', $gallery );
			update_post_meta( $into->ID, '_krm_photo_alts', $alts );
			wp_update_post(
				array(
					'ID'          => $merged->ID,
					'post_status' => 'draft',
				)
			);
			$into_groups = wp_get_object_terms( $into->ID, 'ministry_group', array( 'fields' => 'ids' ) );
			if ( ! is_wp_error( $into_groups ) ) {
				wp_set_object_terms( $merged->ID, $into_groups, 'ministry_group' );
			}
			// Stories about the old ministry now point to the merged one.
			foreach ( get_posts(
				array(
					'post_type'      => array( 'post', 'testimonial' ),
					'post_status'    => 'any',
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'meta_key'       => '_krm_ministry', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
					'meta_value'     => $merged->ID, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				)
			) as $story ) {
				update_post_meta( $story, '_krm_ministry', $into->ID );
			}
		}

		foreach ( $old_groups as $gslug ) {
			$term = get_term_by( 'slug', $gslug, 'ministry_group' );
			if ( $term && ! isset( $data['groups'][ $gslug ] ) ) {
				$count = get_objects_in_term( $term->term_id, 'ministry_group' );
				if ( ! is_wp_error( $count ) && ! $count ) {
					wp_delete_term( $term->term_id, 'ministry_group' );
				}
			}
		}
	}

	// Pages: renames, then the new pages (only on sites Setup has already filled).
	$renames = array(
		'blog'    => array( 'news', 'News & Blogs', 'News & Updates' ),
		'partner' => array( 'partner-with-us', 'Become a Partner', 'Partner With Us' ),
	);
	foreach ( $renames as $from => $to ) {
		$page = krm_get_by_path( $from );
		if ( $page && ! krm_get_by_path( $to[0] ) ) {
			$update = array(
				'ID'        => $page->ID,
				'post_name' => $to[0],
			);
			if ( $page->post_title === $to[1] ) {
				$update['post_title'] = $to[2];
			}
			wp_update_post( $update );
		}
	}
	$set_up = $has_content || get_option( 'krm_setup_done' );
	$pages  = krm_ensure_pages( (bool) $set_up );
	if ( $set_up ) {
		krm_ensure_news_categories();
	}

	// Menus Setup made: replaced, the old ones kept.
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = is_array( $locations ) ? $locations : array();
	if ( $set_up ) {
		foreach ( krm_menu_definitions( krm_ministry_ids(), $pages['ids'] ) as $location => $menu ) {
			$current = ! empty( $locations[ $location ] ) ? wp_get_nav_menu_object( $locations[ $location ] ) : null;
			if ( $current && $current->name !== $menu[0] ) {
				continue; // A menu made by hand.
			}
			if ( $current ) {
				wp_update_term( $current->term_id, 'nav_menu', array( 'name' => $menu[0] . ' (before 1.10)' ) );
			}
			$menu_id = wp_create_nav_menu( $menu[0] );
			if ( is_wp_error( $menu_id ) ) {
				continue;
			}
			krm_add_menu_items( $menu_id, $menu[1], 0 );
			$locations[ $location ] = $menu_id;
		}
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	krm_upgrade_saved_names();
	clean_term_cache( array(), 'ministry_group' );
	update_option( 'krm_flush_rewrite', 1 ); // /ministries/ is now /what-we-do/ (flushed at the end of init).
}

/**
 * Old default text => new text, for page text saved before 1.10.0. Only exact values are replaced.
 */
function krm_renamed_text() {
	return array(
		'Twelve ministries. One Gospel. Explore them.' => 'One Gospel, shared in word and deed.',
		'Twelve ministries. <em>One Gospel.</em>'      => 'What We Do',
		'Flagship · Urdu Bibles'                       => 'Flagship · Bible Distribution',
		'Flagship ministry · Freedom from Slavery'     => 'Flagship ministry · Slavery & Freedom',
		'Urdu Bible Distribution'                      => 'Bible Distribution',
		'Orphanage & Children Care'                    => 'Children & Orphan Care',
		'Widows Ministry'                              => 'Widows Support',
		'Deliverance & Healing'                        => 'Prayer & Deliverance Ministry',
		'Freedom from Slavery'                         => 'Slavery & Freedom Ministry',
		'Brick Kiln Children'                          => 'Brick Kiln Family Care',
		'Donate Now'                                   => 'Donate / Give',
		'News & Blogs'                                 => 'News & Updates',
		'Gifts given “where it is needed most” are used across all twelve ministries.' => 'Gifts given “where it is needed most” are used across all our ministries.',
		'Supports all 12 ministries'                   => 'Supports all our ministries',
	);
}

/**
 * Apply krm_renamed_text() to saved page text and Site text.
 */
function krm_upgrade_saved_names() {
	$map     = krm_renamed_text();
	$replace = function ( $values, $path = '' ) use ( &$replace, $map ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $k => $v ) {
				$values[ $k ] = $replace( $v, $path . '.' . $k );
			}
			return $values;
		}
		// The About collage badge now counts the published ministries itself.
		if ( '.story.badge_number' === $path && '12' === $values ) {
			return '';
		}
		// "Donate Now" only as the header button.
		if ( 'Donate Now' === $values && '.header.donate_label' !== $path ) {
			return $values;
		}
		// The hero's old default video becomes the client's hero video (only when it was never changed).
		if ( '.hero.video' === $path && 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/a.mp4' === $values ) {
			return 'krm-theme:video/hero.mp4';
		}
		return is_string( $values ) && isset( $map[ $values ] ) ? $map[ $values ] : $values;
	};
	$site = get_option( 'krm_site_text' );
	if ( is_array( $site ) ) {
		update_option( 'krm_site_text', $replace( $site ), false );
	}
	foreach ( array_keys( krm_content_screens() ) as $screen ) {
		if ( 'site' === $screen ) {
			continue;
		}
		foreach ( get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_key'       => '_krm_content_' . $screen, // phpcs:ignore WordPress.DB.SlowDBQuery -- runs once.
			)
		) as $post_id ) {
			$values = get_post_meta( $post_id, '_krm_content_' . $screen, true );
			if ( is_array( $values ) ) {
				update_post_meta( $post_id, '_krm_content_' . $screen, $replace( $values ) );
			}
		}
	}
}
