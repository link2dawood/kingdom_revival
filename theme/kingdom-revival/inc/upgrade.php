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

	$page_item     = function ( $slug, $title, $classes = '' ) use ( $page_ids ) {
		return ( isset( $page_ids[ $slug ] ) ? array(
			'title'  => $title,
			'type'   => 'post_type',
			'object' => 'page',
			'id'     => $page_ids[ $slug ],
		) : array(
			'title' => $title,
			'url'   => home_url( user_trailingslashit( $slug ) ),
		) ) + array( 'classes' => $classes );
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

	$groups = array();
	foreach ( $data['groups'] as $gslug => $group ) {
		$children = array();
		foreach ( $data['items'] as $slug => $m ) {
			// A hidden ministry that exists as a draft gets its menu item too: menus leave out links to unpublished
			// posts (krm_menu_tree()), so it appears in its place once it is published (Phase 22).
			if ( $m['group'] === $gslug && ( 'live' === $m['status'] || isset( $ministry_ids[ $slug ] ) ) ) {
				$children[ $m['order'] ] = $ministry_item( $slug );
			}
		}
		ksort( $children );
		$groups[] = array(
			'title'    => $group['name'],
			'url'      => krm_group_url( $gslug ),
			'children' => array_values( $children ),
		);
	}
	$groups[] = array(
		'title'   => 'View all of What We Do →',
		'archive' => 'ministry',
		'classes' => 'menu-all',
	);

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
				array(
					'title'    => 'Our Impact',
					'url'      => '#',
					'children' => array(
						$page_item( 'our-impact', 'Our Impact' ),
						$page_item( 'testimonies', 'Testimonials' ),
						$page_item( 'gallery', 'Photo & Video Gallery' ),
					),
				),
				$page_item( 'news', 'News & Updates' ),
				$page_item( 'partner-with-us', 'Partner With Us' ),
				$page_item( 'contact', 'Contact' ),
				// The red button at the end of the navbar (Phase 22; class menu-donate, theme-global.css).
				$page_item( 'donate', 'Donate Now', 'menu-donate' ), // "Donate Now" since 1.23.0.
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
				$page_item( 'donate', 'Donate' ),
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
					'title'   => 'View all →',
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
			'menu_order'   => $m['order'],
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
	if ( 'live' !== $m['status'] ) {
		update_post_meta( $id, '_krm_theme_hidden', 1 ); // Published by krm_sync_ministry_status() once the data file says live.
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
 *  - page titles "About" → "About Us" and "Donate" kept (it was "Donate / Give" until 1.18.2; slugs unchanged), when still the old title;
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

/*
 * Fifth step (theme 1.15.9, option krm_sitemap_version 5): the home page's "Where we work" counts get dummy figures
 * where a saved home page still holds the earlier "[00] villages" defaults (exact values only).
 */
add_action(
	'init',
	function () {
		if ( 4 !== (int) get_option( 'krm_sitemap_version' ) ) {
			return;
		}
		update_option( 'krm_sitemap_version', 5 );
		$counts = array(
			'Faisalabad'     => '40 villages · 8 kilns',
			'Jhang'          => '25 villages · 4 kilns',
			'Toba Tek Singh' => '9 villages',
			'Chiniot'        => '6 villages',
		);
		foreach ( get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_key'       => '_krm_content_home', // phpcs:ignore WordPress.DB.SlowDBQuery -- runs once.
			)
		) as $post_id ) {
			$values = get_post_meta( $post_id, '_krm_content_home', true );
			if ( ! is_array( $values ) || empty( $values['where']['places'] ) || ! is_array( $values['where']['places'] ) ) {
				continue;
			}
			$changed = false;
			foreach ( $values['where']['places'] as $i => $place ) {
				$name = isset( $place['name'] ) ? $place['name'] : '';
				if ( isset( $counts[ $name ], $place['count'] ) && in_array( $place['count'], array( '[00] villages · [00] kilns', '[00] villages' ), true ) ) {
					$values['where']['places'][ $i ]['count'] = $counts[ $name ];
					$changed                                  = true;
				}
			}
			if ( $changed ) {
				update_post_meta( $post_id, '_krm_content_home', $values );
			}
		}
	},
	24
);

/*
 * Sixth step (theme 1.15.10, option krm_sitemap_version 6): a saved home page that still holds the founder quote
 * placeholder gets the dummy quote (exact value only).
 */
add_action(
	'init',
	function () {
		if ( 5 !== (int) get_option( 'krm_sitemap_version' ) ) {
			return;
		}
		update_option( 'krm_sitemap_version', 6 );
		foreach ( get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_key'       => '_krm_content_home', // phpcs:ignore WordPress.DB.SlowDBQuery -- runs once.
			)
		) as $post_id ) {
			$values = get_post_meta( $post_id, '_krm_content_home', true );
			if ( is_array( $values ) && isset( $values['founder']['quote'] ) && '“[A short quote from Pastor Adil on why this work matters.]”' === $values['founder']['quote'] ) {
				$values['founder']['quote'] = '“Every child taught, every family set free from the kilns, every village that hears the Gospel — that is God’s Kingdom breaking in. That is why we keep going.”';
				update_post_meta( $post_id, '_krm_content_home', $values );
			}
		}
	},
	25
);

/*
 * Seventh step (Phase 21, theme 1.16.0, option krm_sitemap_version 7): "Ministries" labels become "What We Do", the
 * menus get the group pages and the Our Impact dropdown. Done in place, so anything edited by hand is kept:
 *  - main menu: a "#" item named like a ministry group links to the group page (/what-we-do/{group}/); the What We
 *    Do archive item under What We Do ("All ministries") becomes "View all →", the last item of the panel; a top-level
 *    Our Impact item with no sub-items becomes a dropdown: Our Impact · Testimonies · Photo & Video Gallery;
 *  - footer: "All ministries →" becomes "View all →";
 *  - Site text and home page labels still holding the earlier default (exact value only) get the new one.
 */
add_action(
	'init',
	function () {
		if ( 6 !== (int) get_option( 'krm_sitemap_version' ) ) {
			return;
		}
		update_option( 'krm_sitemap_version', 7 );
		krm_upgrade_what_we_do();
	},
	26
);

/*
 * Eighth step (Phase 22, theme 1.17.0, option krm_sitemap_version 8): the client's navbar on every site.
 *  - Main menu and Footer Quick Links: when the menu assigned to the location is not already the sitemap's menu
 *    (same items, labels and nesting), a new menu is built from krm_menu_definitions() and assigned; the menu that
 *    was there is kept, renamed "… (before 1.17)" and unassigned (Appearance → Menus). This also replaces menus made
 *    by hand — e.g. the previous site's "Ministries" menu still assigned on the live site, which steps 1 and 7 left
 *    alone — as the client asked for exactly this navbar.
 *  - Persecuted Christian Support, Food Relief and Disaster Relief & Recovery Ministry: drafts made by the theme are
 *    published (their pages are "coming soon"); Youth Ministry / Women Empowerment stays a draft.
 */
add_action(
	'init',
	function () {
		if ( 7 !== (int) get_option( 'krm_sitemap_version' ) ) {
			return;
		}
		update_option( 'krm_sitemap_version', 8 );
		foreach ( krm_ministry_data()['items'] as $slug => $m ) {
			$post = get_page_by_path( $slug, OBJECT, 'ministry' );
			// The theme made these drafts (Setup, step 1 or step 3); mark them so the status sync may publish them.
			if ( $post && 'draft' === $post->post_status && ( 'live' !== $m['status'] || ! empty( $m['coming_soon'] ) ) ) {
				update_post_meta( $post->ID, '_krm_theme_hidden', 1 );
			}
		}
		krm_sync_ministry_status();
		krm_upgrade_nav_menus();
	},
	27
);

/*
 * The data file's status wins for ministries the theme itself hid: on each new theme version, a ministry marked
 * "live" in inc/data/ministries.php whose post is a draft the theme made (_krm_theme_hidden) is published. So
 * enabling Youth Ministry / Women Empowerment is one line: 'status' => 'live' (and a version bump). A ministry
 * someone drafted by hand is never published.
 */
add_action(
	'init',
	function () {
		if ( get_option( 'krm_ministry_status_version' ) === KRM_VERSION || ! post_type_exists( 'ministry' ) ) {
			return;
		}
		update_option( 'krm_ministry_status_version', KRM_VERSION );
		krm_sync_ministry_status();
	},
	28
);

/**
 * Publish the theme-hidden drafts whose data entry is now "live". See above.
 */
function krm_sync_ministry_status() {
	foreach ( krm_ministry_data()['items'] as $slug => $m ) {
		if ( 'live' !== $m['status'] ) {
			continue;
		}
		$post = get_page_by_path( $slug, OBJECT, 'ministry' );
		if ( $post && 'draft' === $post->post_status && get_post_meta( $post->ID, '_krm_theme_hidden', true ) ) {
			wp_update_post(
				array(
					'ID'          => $post->ID,
					'post_status' => 'publish',
				)
			);
			delete_post_meta( $post->ID, '_krm_theme_hidden' );
		}
	}
}

/**
 * A menu's items as "depth|title" lines (titles decoded), the shape compared by krm_upgrade_nav_menus().
 *
 * @param array $items Definition items (krm_menu_definitions()) or null to read the menu $menu_id.
 * @param int   $menu_id Menu term ID.
 * @return string[]
 */
function krm_menu_signature( $items, $menu_id = 0 ) {
	$out = array();
	if ( null === $items ) {
		$posts = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'publish' ) );
		$depth = array();
		foreach ( (array) $posts as $item ) {
			$d                = isset( $depth[ (int) $item->menu_item_parent ] ) ? $depth[ (int) $item->menu_item_parent ] + 1 : 0;
			$depth[ $item->ID ] = $d;
			$out[]            = $d . '|' . trim( wp_specialchars_decode( $item->title, ENT_QUOTES ) );
		}
		return $out;
	}
	$walk = function ( $list, $d ) use ( &$walk, &$out ) {
		foreach ( $list as $item ) {
			$out[] = $d . '|' . $item['title'];
			if ( ! empty( $item['children'] ) ) {
				$walk( $item['children'], $d + 1 );
			}
		}
	};
	$walk( $items, 0 );
	return $out;
}

/**
 * The menu with exactly this name, or null.
 *
 * @param string $name Menu name.
 * @return WP_Term|null
 */
function krm_menu_by_name( $name ) {
	foreach ( wp_get_nav_menus() as $menu ) {
		if ( $menu->name === $name ) {
			return $menu;
		}
	}
	return null;
}

/**
 * Give the Main menu and Footer Quick Links locations the sitemap's menus (step 8). Returns what was done.
 *
 * @return string[]
 */
function krm_upgrade_nav_menus() {
	$log       = array();
	$pages     = krm_ensure_pages( false );
	$defs      = krm_menu_definitions( krm_ministry_ids(), $pages['ids'] );
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = is_array( $locations ) ? $locations : array();
	foreach ( array( 'primary', 'footer_quick' ) as $location ) {
		list( $name, $items ) = $defs[ $location ];
		$current              = ! empty( $locations[ $location ] ) ? wp_get_nav_menu_object( $locations[ $location ] ) : null;
		// Drafts (e.g. Youth / Women) are left out of the comparison, as they are left out of the menu.
		if ( $current && krm_menu_signature( null, $current->term_id ) === krm_menu_signature( $items ) ) {
			$log[] = sprintf( '%s: already the sitemap menu.', $location );
			continue;
		}
		// Free the name: the assigned menu and any other menu called $name are kept as "… (before 1.17)".
		// (Looked up by exact name: wp_get_nav_menu_object() tries the slug first, and "Main navigation (before 1.10)"
		// still has the slug main-navigation.)
		$olds = array();
		foreach ( array_filter( array( $current, krm_menu_by_name( $name ) ) ) as $old ) {
			$olds[ $old->term_id ] = $old;
		}
		foreach ( $olds as $old ) {
			$base = $old->name . ' (before 1.17)';
			$new  = $base;
			for ( $n = 2; krm_menu_by_name( $new ) && (int) krm_menu_by_name( $new )->term_id !== (int) $old->term_id; $n++ ) {
				$new = $base . ' ' . $n;
			}
			wp_update_term( $old->term_id, 'nav_menu', array( 'name' => $new ) );
			$log[] = sprintf( '%s: kept the old menu as "%s".', $location, $new );
		}
		$menu_id = wp_create_nav_menu( $name );
		if ( is_wp_error( $menu_id ) ) {
			$log[] = sprintf( '%s: could not create %s (%s).', $location, $name, $menu_id->get_error_message() );
			continue;
		}
		krm_add_menu_items( $menu_id, $items, 0 );
		$locations[ $location ] = $menu_id;
		$log[]                  = sprintf( '%s: new menu "%s" assigned.', $location, $name );
	}
	set_theme_mod( 'nav_menu_locations', $locations );
	return $log;
}

/*
 * Ninth step (theme 1.17.1, option krm_sitemap_version 9): "Testimonies" becomes "Testimonials" and the page shows the
 * dummy testimonials (the "Share your testimony" form is gone, at the client's request).
 *  - The page title, menu items and saved page text still holding the earlier default (exact value) get the new one.
 *  - A site with no published testimonial and no sample testimonial (in any status, the trash included, so deleted
 *    samples stay deleted) gets the 12 dummy testimonials of inc/data/testimonials.php (marked _krm_sample).
 */
add_action(
	'init',
	function () {
		if ( 8 !== (int) get_option( 'krm_sitemap_version' ) ) {
			return;
		}
		update_option( 'krm_sitemap_version', 9 );
		krm_upgrade_testimonials();
	},
	29
);

/**
 * The ninth upgrade step. See the comment above.
 */
function krm_upgrade_testimonials() {
	$page = krm_testimonies_page_id();
	if ( $page && 'Testimonies' === get_post_field( 'post_title', $page ) ) {
		wp_update_post(
			array(
				'ID'         => $page,
				'post_title' => 'Testimonials',
			)
		);
	}
	foreach ( wp_get_nav_menus() as $menu ) {
		foreach ( (array) wp_get_nav_menu_items( $menu->term_id, array( 'post_status' => 'any' ) ) as $item ) {
			if ( 'Testimonies' === $item->title ) {
				wp_update_post(
					array(
						'ID'         => $item->ID,
						'post_title' => 'Testimonials',
					)
				);
			}
		}
	}
	$renames = array(
		'_krm_content_testimonies' => array(
			'hero.title'        => array( 'Testimonies', 'Testimonials' ),
			'list.filter_label' => array( 'Filter testimonies by ministry', 'Filter testimonials by ministry' ),
			'list.count_text'   => array( 'Showing {n} testimonies', 'Showing {n} testimonials' ),
			'list.empty'        => array( 'Testimonies from the families and churches we serve will appear here.', 'Testimonials from the families and churches we serve will appear here.' ),
		),
		'_krm_content_impact'      => array(
			'more.heading'           => array( 'Testimonies & Gallery', 'Testimonials & Gallery' ),
			'more.testimonies_title' => array( 'Testimonies', 'Testimonials' ),
		),
	);
	foreach ( $renames as $key => $map ) {
		foreach ( get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_key'       => $key, // phpcs:ignore WordPress.DB.SlowDBQuery -- runs once.
			)
		) as $post_id ) {
			$values  = get_post_meta( $post_id, $key, true );
			$changed = false;
			foreach ( $map as $path => $pair ) {
				list( $section, $field ) = explode( '.', $path );
				if ( is_array( $values ) && isset( $values[ $section ][ $field ] ) && $pair[0] === $values[ $section ][ $field ] ) {
					$values[ $section ][ $field ] = $pair[1];
					$changed                      = true;
				}
			}
			if ( $changed ) {
				update_post_meta( $post_id, $key, $values );
			}
		}
	}
	$site = get_option( 'krm_site_text' );
	if ( is_array( $site ) && isset( $site['ministry']['story_more'] ) && 'More testimonies →' === $site['ministry']['story_more'] ) {
		$site['ministry']['story_more'] = 'More testimonials →';
		update_option( 'krm_site_text', $site, false );
	}

	$published = get_posts(
		array(
			'post_type'   => 'testimonial',
			'post_status' => 'publish',
			'numberposts' => 1,
			'fields'      => 'ids',
		)
	);
	$samples   = get_posts(
		array(
			'post_type'   => 'testimonial',
			'post_status' => array( 'publish', 'draft', 'pending', 'private', 'trash' ),
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_key'    => '_krm_sample', // phpcs:ignore WordPress.DB.SlowDBQuery -- runs once.
		)
	);
	if ( ! $published && ! $samples ) {
		krm_create_sample_testimonials();
	}
}

/**
 * Create the dummy testimonials of inc/data/testimonials.php (Setup and upgrade step 9). One whose slug exists is
 * skipped. Returns a log.
 *
 * @return string[]
 */
function krm_create_sample_testimonials() {
	$log   = array();
	$order = 0;
	foreach ( include KRM_DIR . '/inc/data/testimonials.php' as $t ) {
		$slug = sanitize_title( $t['name'] );
		++$order;
		if ( krm_get_by_path( $slug, 'testimonial' ) ) {
			$log[] = sprintf( 'Testimonial exists, left unchanged: %s', $t['name'] );
			continue;
		}
		$id    = wp_insert_post(
			array(
				'post_type'    => 'testimonial',
				'post_status'  => 'publish',
				'post_title'   => $t['name'],
				'post_name'    => $slug,
				'post_content' => $t['text'],
				'menu_order'   => $order,
				'meta_input'   => array(
					'_krm_role'     => $t['role'],
					'_krm_ministry' => $t['ministry'],
					'_krm_sample'   => 1,
				),
			),
			true
		);
		$log[] = is_wp_error( $id ) ? sprintf( 'Could not create testimonial %s: %s', $t['name'], $id->get_error_message() ) : sprintf( 'Created testimonial: %s', $t['name'] );
	}
	return $log;
}

/*
 * Page caches are emptied once per theme version (theme 1.17.1). Uploading a new version of the same theme is not a
 * theme switch, so cache plugins keep serving pages built with the old version to visitors (logged-out, so a private
 * window shows the old header while the logged-in admin sees the new one). Runs on the first request that reaches
 * WordPress after the update — the upload itself, or any admin page.
 */
add_action(
	'init',
	function () {
		if ( get_option( 'krm_cache_purged_version' ) === KRM_VERSION ) {
			return;
		}
		update_option( 'krm_cache_purged_version', KRM_VERSION );
		krm_purge_page_caches();
	},
	101
);

/**
 * Empty the page cache of the common cache plugins that are active. Returns the names of those purged.
 *
 * @return string[]
 */
function krm_purge_page_caches() {
	$done = array();
	if ( class_exists( '\SpeedyCache\Delete' ) && method_exists( '\SpeedyCache\Delete', 'run' ) ) {
		\SpeedyCache\Delete::run( array() ); // All pages, Varnish and SpeedyCache's CDN (the live site's cache).
		$done[] = 'SpeedyCache';
	}
	if ( has_action( 'litespeed_purge_all' ) ) {
		do_action( 'litespeed_purge_all' );
		$done[] = 'LiteSpeed Cache';
	}
	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
		$done[] = 'WP Rocket';
	}
	if ( function_exists( 'w3tc_flush_all' ) ) {
		w3tc_flush_all();
		$done[] = 'W3 Total Cache';
	}
	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
		$done[] = 'WP Super Cache';
	}
	if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
		sg_cachepress_purge_cache();
		$done[] = 'SiteGround Optimizer';
	}
	if ( has_action( 'wpfc_clear_all_cache' ) ) {
		do_action( 'wpfc_clear_all_cache', true );
		$done[] = 'WP Fastest Cache';
	}
	return $done;
}

/**
 * The seventh upgrade step. See the comment above.
 */
function krm_upgrade_what_we_do() {
	$groups = array();
	foreach ( krm_groups( true ) as $slug => $group ) {
		$groups[ $group['name'] ] = $slug;
	}
	$pages = krm_ensure_pages( false )['ids'];
	foreach ( get_nav_menu_locations() as $location => $menu_id ) {
		if ( ! $menu_id || ! in_array( $location, array( 'primary', 'footer_ministries', 'footer_quick' ), true ) ) {
			continue;
		}
		$items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
		if ( ! $items ) {
			continue;
		}
		$changed = false;
		$kids    = array();
		foreach ( $items as $item ) {
			$kids[ (int) $item->menu_item_parent ][] = $item;
		}
		foreach ( $items as $item ) {
			$title = wp_specialchars_decode( $item->title, ENT_QUOTES );
			if ( 'custom' === $item->type && '#' === trim( $item->url ) && isset( $groups[ $title ] ) ) {
				update_post_meta( $item->ID, '_menu_item_url', esc_url_raw( krm_group_url( $groups[ $title ] ) ) );
				$changed = true;
			}
			if ( 'post_type_archive' === $item->type && 'ministry' === $item->object && in_array( $title, array( 'All ministries', 'All ministries →' ), true ) ) {
				wp_update_post(
					array(
						'ID'         => $item->ID,
						'post_title' => 'View all →',
						// The panel's last item (krm_renumber_menu() puts it after its siblings).
						'menu_order' => 100000,
					)
				);
				$changed = true;
			}
			if ( 'primary' === $location && 0 === (int) $item->menu_item_parent && empty( $kids[ $item->ID ] ) && 'post_type' === $item->type && isset( $pages['our-impact'] ) && (int) $item->object_id === (int) $pages['our-impact'] ) {
				foreach ( array(
					'our-impact'  => 'Our Impact',
					'testimonies' => 'Testimonies',
					'gallery'     => 'Photo & Video Gallery',
				) as $slug => $label ) {
					if ( isset( $pages[ $slug ] ) ) {
						wp_update_nav_menu_item(
							$menu_id,
							0,
							array(
								'menu-item-title'     => $label,
								'menu-item-status'    => 'publish',
								'menu-item-parent-id' => $item->ID,
								'menu-item-type'      => 'post_type',
								'menu-item-object'    => 'page',
								'menu-item-object-id' => $pages[ $slug ],
								'menu-item-position'  => 100000,
							)
						);
					}
				}
				$changed = true;
			}
		}
		if ( $changed ) {
			krm_renumber_menu( $menu_id );
		}
	}

	// Labels: earlier default => new default, per Site text field and home page field.
	$site = get_option( 'krm_site_text' );
	if ( is_array( $site ) ) {
		$map = array(
			'footer.ministries_heading'  => array( 'Ministries', 'What We Do' ),
			'ministry.other_more'        => array( 'See all ministries →', 'Explore What We Do →' ),
			'ministries.eyebrow'         => array( 'Our Ministries', '' ),
			'ministries.list_heading'    => array( 'All ministries', 'Our ministry groups' ),
			'notfound.button1_label'     => array( 'Browse ministries', 'Explore What We Do' ),
		);
		foreach ( $map as $path => $values ) {
			list( $section, $field ) = explode( '.', $path );
			if ( isset( $site[ $section ][ $field ] ) && $values[0] === $site[ $section ][ $field ] ) {
				$site[ $section ][ $field ] = $values[1];
			}
		}
		update_option( 'krm_site_text', $site, false );
	}
	foreach ( get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => '_krm_content_home', // phpcs:ignore WordPress.DB.SlowDBQuery -- runs once.
		)
	) as $post_id ) {
		$values  = get_post_meta( $post_id, '_krm_content_home', true );
		$changed = false;
		foreach ( array(
			'hero.button1_label'   => array( 'Explore Ministries', 'Explore What We Do' ),
			'explorer.all_label'   => array( 'View All Ministries', 'Explore What We Do' ),
			'causes.eyebrow'       => array( 'Our Ministries', 'What We Do' ),
		) as $path => $pair ) {
			list( $section, $field ) = explode( '.', $path );
			if ( is_array( $values ) && isset( $values[ $section ][ $field ] ) && $pair[0] === $values[ $section ][ $field ] ) {
				$values[ $section ][ $field ] = $pair[1];
				$changed                      = true;
			}
		}
		if ( $changed ) {
			update_post_meta( $post_id, '_krm_content_home', $values );
		}
	}	// Our Impact's link cards (shown since Phase 21) had the heading of the section above them.
	foreach ( get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => '_krm_content_impact', // phpcs:ignore WordPress.DB.SlowDBQuery -- runs once.
		)
	) as $post_id ) {
		$values = get_post_meta( $post_id, '_krm_content_impact', true );
		if ( is_array( $values ) && isset( $values['more']['heading'] ) && 'Stories from the field' === $values['more']['heading'] ) {
			$values['more']['heading'] = 'Testimonies & Gallery';
			if ( isset( $values['more']['eyebrow'] ) && 'Lives changed' === $values['more']['eyebrow'] ) {
				$values['more']['eyebrow'] = 'Explore more';
			}
			update_post_meta( $post_id, '_krm_content_impact', $values );
		}
	}
}

/**
 * Renumber a menu's items in tree order (each item followed by its sub-items, siblings in their current order), so
 * items moved or added by the upgrade sit with their parent in Appearance → Menus.
 *
 * @param int $menu_id Menu ID.
 */
function krm_renumber_menu( $menu_id ) {
	// The menu's item posts themselves: wp_get_nav_menu_items() leaves out items linking to a draft (a hidden ministry).
	$items = get_posts(
		array(
			'post_type'      => 'nav_menu_item',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- runs once.
				array(
					'taxonomy' => 'nav_menu',
					'field'    => 'term_id',
					'terms'    => (int) $menu_id,
				),
			),
		)
	);
	if ( ! $items ) {
		return;
	}
	$kids = array();
	foreach ( $items as $item ) {
		$item->menu_item_parent                 = (int) get_post_meta( $item->ID, '_menu_item_menu_item_parent', true );
		$kids[ (int) $item->menu_item_parent ][] = $item;
	}
	$ids  = wp_list_pluck( $items, 'ID' );
	$n    = 0;
	$walk = function ( $parent ) use ( &$walk, &$n, $kids ) {
		if ( empty( $kids[ $parent ] ) ) {
			return;
		}
		usort(
			$kids[ $parent ],
			function ( $a, $b ) {
				return (int) $a->menu_order - (int) $b->menu_order;
			}
		);
		foreach ( $kids[ $parent ] as $item ) {
			++$n;
			if ( (int) $item->menu_order !== $n ) {
				wp_update_post(
					array(
						'ID'         => $item->ID,
						'menu_order' => $n,
					)
				);
			}
			$walk( (int) $item->ID );
		}
	};
	$walk( 0 );
	// Items whose parent is gone keep their place after the rest.
	foreach ( $items as $item ) {
		if ( (int) $item->menu_item_parent && ! in_array( (int) $item->menu_item_parent, $ids, true ) ) {
			wp_update_post(
				array(
					'ID'         => $item->ID,
					'menu_order' => ++$n,
				)
			);
		}
	}
}

/**
 * The second upgrade step. See the comment above.
 */
function krm_upgrade_sitemap_2() {
	foreach ( array(
		'about'  => array( 'About', 'About Us' ),
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
				$update['menu_order'] = $m['order'];
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
		'Donate Now'                                   => 'Donate',
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

/*
 * Theme 1.18.1 (option krm_contact_version 1): the ministry's new phone / WhatsApp number (+92 302 0692993) and its
 * Facebook and Instagram pages. Saved Settings still holding the old number (+92 341 5418733, any spelling) get the
 * new one; an empty Facebook or Instagram URL gets the new link. Values changed by hand to anything else are kept.
 */
add_action(
	'init',
	function () {
		if ( (int) get_option( 'krm_contact_version' ) >= 1 ) {
			return;
		}
		update_option( 'krm_contact_version', 1 );
		$saved = get_option( 'krm_settings', array() );
		if ( ! is_array( $saved ) || ! $saved ) {
			return; // Nothing saved: the new defaults apply.
		}
		$defaults = krm_defaults();
		$changed  = false;
		foreach ( array( 'phone', 'phone_tel', 'whatsapp', 'bank_phone' ) as $key ) {
			if ( isset( $saved[ $key ] ) && in_array( preg_replace( '/\D/', '', (string) $saved[ $key ] ), array( '923415418733', '03415418733', '3415418733', '00923415418733' ), true ) ) {
				$saved[ $key ] = $defaults[ $key ];
				$changed       = true;
			}
		}
		foreach ( array( 'facebook', 'instagram' ) as $key ) {
			if ( isset( $saved[ $key ] ) && '' === trim( (string) $saved[ $key ] ) ) {
				$saved[ $key ] = $defaults[ $key ];
				$changed       = true;
			}
		}
		if ( $changed ) {
			update_option( 'krm_settings', $saved );
		}
	},
	30
);

/*
 * Theme 1.18.2 (option krm_contact_version 2): the red navbar button reads "Donate" instead of "Donate / Give", at the
 * client's request. Menu items and the Donate page still titled exactly "Donate / Give" become "Donate" (menu items
 * with no title of their own show the page title). Other titles set by hand are kept.
 */
add_action(
	'init',
	function () {
		if ( (int) get_option( 'krm_contact_version' ) >= 2 ) {
			return;
		}
		update_option( 'krm_contact_version', 2 );
		$posts = get_posts(
			array(
				'post_type'      => array( 'nav_menu_item', 'page' ),
				'post_status'    => 'any',
				'posts_per_page' => 50,
				'title'          => 'Donate / Give',
			)
		);
		foreach ( $posts as $post ) {
			if ( 'Donate / Give' === wp_specialchars_decode( $post->post_title, ENT_QUOTES ) ) {
				wp_update_post(
					array(
						'ID'         => $post->ID,
						'post_title' => 'Donate',
					)
				);
			}
		}
	},
	31
);

/*
 * Theme 1.18.3: the ministry's new Web3Forms access key. A Settings value still holding the previous key
 * (df5b0a03-…) is replaced; any other key typed by hand is kept.
 */
add_action(
	'init',
	function () {
		$settings = get_option( 'krm_settings' );
		if ( is_array( $settings ) && isset( $settings['web3forms_key'] ) && 'df5b0a03-783c-4607-a037-17855f320d71' === trim( (string) $settings['web3forms_key'] ) ) {
			$settings['web3forms_key'] = '4b1ed194-65df-4d6e-907b-f4e473b11f70';
			update_option( 'krm_settings', $settings );
		}
	}
);

/*
 * Theme 1.18.4: photos rotated in the image editor got their 4:3 card crops cut from the sideways original (see
 * krm_card_stale()). Run the card backfill over every image once more; it now remakes those crops from the
 * rotated file.
 */
add_action(
	'init',
	function () {
		if ( get_option( 'krm_card_rotate_fix' ) ) {
			return;
		}
		update_option( 'krm_card_rotate_fix', 1, false );
		update_option( 'krm_card_backfill', 0 );
	}
);

/*
 * Theme 1.19.2: the home donation card's heading is a general line now. A saved heading still holding the old default
 * ("Help Brick Kiln Children Go to School & Know Christ") is replaced; one typed by hand is kept.
 */
add_action(
	'init',
	function () {
		$front = (int) get_option( 'page_on_front' );
		$saved = $front ? get_post_meta( $front, '_krm_content_home', true ) : null;
		if ( is_array( $saved ) && isset( $saved['hero']['card_heading'] ) && 'Help Brick Kiln Children Go to School & Know Christ' === trim( (string) $saved['hero']['card_heading'] ) ) {
			$saved['hero']['card_heading'] = 'Bring Hope to Pakistan’s Forgotten';
			update_post_meta( $front, '_krm_content_home', $saved );
		}
	}
);

/*
 * Theme 1.19.4: longer ministry short descriptions (the Excerpt, shown in the home "What We Do" explorer and on cards
 * without their own card text). A ministry whose saved Excerpt is still the earlier default gets the new wording from
 * inc/data/ministries.php; an Excerpt edited by hand is kept.
 */
add_action(
	'init',
	function () {
		if ( get_option( 'krm_excerpt_version' ) >= 1 || ! post_type_exists( 'ministry' ) ) {
			return;
		}
		update_option( 'krm_excerpt_version', 1 );
		$data = krm_ministry_data();
		foreach ( array(
			'evangelism-outreach' => 'Taking the Gospel to villages and unreached communities across Pakistan.',
			'church-planting' => 'Local congregations and house churches, led by pastors we train and walk alongside.',
			'bible-distribution' => 'Placing Urdu Bibles in the hands of families and new believers.',
			'leadership-training' => 'Equipping pastors and leaders to shepherd local and house churches.',
			'baptisms-discipleship' => 'New believers publicly following Jesus Christ in baptism, and discipled to grow in their faith.',
			'prayer-deliverance' => 'Prayer for the sick and oppressed in the power of Jesus Christ.',
			'slavery-freedom' => 'Missions that help families held in debt bondage at brick kilns walk free.',
			'brick-kiln-family-care' => 'Care, education and the Gospel for families who live and work beside the brick kilns.',
			'children-orphan-care' => 'Shelter, food, schooling and daily care for orphaned and underprivileged children.',
			'clean-water' => 'Safe drinking water for villages and kiln communities that go without.',
			'widows-support' => 'Support, fellowship and practical help for widows in the church and village.',
			'medical-health' => 'Free check-ups and medicine for brick kiln workers and village families.',
		) as $slug => $old ) {
			$post = krm_get_by_path( $slug, 'ministry' );
			if ( $post && $old === trim( (string) $post->post_excerpt ) && isset( $data['items'][ $slug ]['excerpt'] ) ) {
				wp_update_post(
					array(
						'ID'           => $post->ID,
						'post_excerpt' => $data['items'][ $slug ]['excerpt'],
					)
				);
			}
		}
	},
	30
);

/*
 * Theme 1.20.1: the home freedom section's heading and text have new wording. Saved values still holding the earlier
 * defaults are replaced; text typed by hand is kept.
 */
add_action(
	'init',
	function () {
		$front = (int) get_option( 'page_on_front' );
		$saved = $front ? get_post_meta( $front, '_krm_content_home', true ) : null;
		if ( ! is_array( $saved ) || empty( $saved['freedom'] ) ) {
			return;
		}
		$changed = false;
		foreach ( array(
			'heading' => array( 'Behind every brick, a family waiting to be free.', 'Behind every brick, a family <span class="hl">waiting to be free</span>.' ),
			'text'    => array( 'Whole families labour at brick kilns under debts passed down for generations. Here is exactly how your gift brings a family out.', 'Whole families labour at brick kilns under debts that can pass from one generation to the next. This is how they become trapped, and how your gift can bring them out.' ),
		) as $key => $pair ) {
			if ( isset( $saved['freedom'][ $key ] ) && $pair[0] === trim( (string) $saved['freedom'][ $key ] ) ) {
				$saved['freedom'][ $key ] = $pair[1];
				$changed                  = true;
			}
		}
		if ( $changed ) {
			update_post_meta( $front, '_krm_content_home', $saved );
		}
	}
);

/*
 * Theme 1.20.3: clean water wording. A saved home "Bringing Christ" text and a Clean Water Projects Excerpt still
 * holding the earlier default get the new wording; text edited by hand is kept.
 */
add_action(
	'init',
	function () {
		if ( get_option( 'krm_water_copy_version' ) >= 1 || ! post_type_exists( 'ministry' ) ) {
			return;
		}
		update_option( 'krm_water_copy_version', 1 );
		$front = (int) get_option( 'page_on_front' );
		$saved = $front ? get_post_meta( $front, '_krm_content_home', true ) : null;
		$old   = 'We preach the Gospel and plant churches among the unreached. We provide Sunday school and education for children growing up beside the kilns. We hold medical camps, place Urdu Bibles in the hands of new believers, and care for orphans and widows.';
		if ( is_array( $saved ) && isset( $saved['mission']['text'] ) && $old === trim( (string) $saved['mission']['text'] ) ) {
			$saved['mission']['text'] = str_replace( 'We hold medical camps, ', 'We hold medical camps, install clean water hand pumps at brick kilns, ', $old );
			update_post_meta( $front, '_krm_content_home', $saved );
		}
		$post = krm_get_by_path( 'clean-water', 'ministry' );
		if ( $post && 'We install hand pumps so families can reach safe, clean water close to home. Each pump serves daily needs and brings relief to the whole community.' === trim( (string) $post->post_excerpt ) ) {
			wp_update_post(
				array(
					'ID'           => $post->ID,
					'post_excerpt' => 'We install hand pumps at brick kilns and in communities in need, so families can reach clean water close to home. Each pump serves daily needs and brings relief to the whole community.',
				)
			);
		}
	},
	30
);

/**
 * Theme 1.21.3: clean water photos. A saved home "Clean water" section whose photo is still the old empty default gets
 * the three Clean Water Project photos (the schema defaults); a photo chosen by hand is kept.
 */
add_action(
	'init',
	function () {
		if ( get_option( 'krm_water_copy_version' ) < 1 || get_option( 'krm_water_copy_version' ) >= 2 ) {
			return;
		}
		update_option( 'krm_water_copy_version', 2 );
		$front = (int) get_option( 'page_on_front' );
		$saved = $front ? get_post_meta( $front, '_krm_content_home', true ) : null;
		if ( is_array( $saved ) && isset( $saved['water'] ) && is_array( $saved['water'] ) && '' === trim( (string) ( $saved['water']['image'] ?? '' ) ) && ! isset( $saved['water']['image2'] ) ) {
			unset( $saved['water']['image'], $saved['water']['image_alt'] );
			update_post_meta( $front, '_krm_content_home', $saved );
		}
	},
	31
);

/**
 * Theme 1.21.4: new photo of Pastor Adil. Saved home "About" founder photo, home "Founder" photo and About page
 * "Founder testimony" photo still holding an earlier default (as the design URL or its imported attachment) get the
 * new photo; a photo chosen by hand is kept. Pastor Adil's card leaves the About page "Our staff" list (he stays in the
 * Founder section).
 */
add_action(
	'init',
	function () {
		if ( (int) get_option( 'krm_founder_photo_version' ) >= 1 ) {
			return;
		}
		update_option( 'krm_founder_photo_version', 1 );
		$is_old = function ( $value, $old ) {
			$value = is_scalar( $value ) ? trim( (string) $value ) : '';
			if ( $value === $old ) {
				return true;
			}
			return is_numeric( $value ) && (int) $value > 0 && (int) $value === (int) krm_media_id( $old );
		};
		$front = (int) get_option( 'page_on_front' );
		$saved = $front ? get_post_meta( $front, '_krm_content_home', true ) : null;
		if ( is_array( $saved ) ) {
			$changed = false;
			if ( isset( $saved['about']['founder_photo'] ) && $is_old( $saved['about']['founder_photo'], 'krm-theme:images/content/team-adil-arif.jpg' ) ) {
				$saved['about']['founder_photo'] = 'krm-theme:images/content/pastor-adil-arif.jpg';
				$changed                         = true;
			}
			if ( isset( $saved['founder']['image'] ) && $is_old( $saved['founder']['image'], 'krm-theme:images/content/founder-adil-arif.jpg' ) ) {
				$saved['founder']['image'] = 'krm-theme:images/content/pastor-adil-arif.jpg';
				$changed                   = true;
			}
			if ( $changed ) {
				update_post_meta( $front, '_krm_content_home', $saved );
			}
		}
		foreach ( get_pages( array( 'meta_key' => '_krm_content_about' ) ) as $page ) {
			$saved = get_post_meta( $page->ID, '_krm_content_about', true );
			if ( ! is_array( $saved ) ) {
				continue;
			}
			$changed = false;
			if ( isset( $saved['founder']['image'] ) && $is_old( $saved['founder']['image'], 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/file_00000000b46471f484befac1dc0ee291.png' ) ) {
				$saved['founder']['image'] = 'krm-theme:images/content/pastor-adil-arif-portrait.jpg';
				$changed                   = true;
			}
			if ( isset( $saved['team']['main'] ) && is_array( $saved['team']['main'] ) ) {
				$kept = array_values(
					array_filter(
						$saved['team']['main'],
						function ( $person ) {
							return ! ( is_array( $person ) && 'Pastor Adil Arif' === trim( (string) ( $person['name'] ?? '' ) ) );
						}
					)
				);
				if ( count( $kept ) !== count( $saved['team']['main'] ) ) {
					$saved['team']['main'] = $kept;
					$changed               = true;
				}
			}
			if ( $changed ) {
				update_post_meta( $page->ID, '_krm_content_about', $saved );
			}
		}
	},
	32
);

/**
 * Theme 1.21.3: home mission heading "Bringing Christ / to the brick kilns of Pakistan." becomes "Taking the Gospel /
 * to the villages of Pakistan." in a saved home page that still holds the old default; edited lines are kept.
 */
add_action(
	'init',
	function () {
		if ( get_option( 'krm_mission_heading_version' ) >= 1 ) {
			return;
		}
		update_option( 'krm_mission_heading_version', 1 );
		$front = (int) get_option( 'page_on_front' );
		$saved = $front ? get_post_meta( $front, '_krm_content_home', true ) : null;
		if ( ! is_array( $saved ) || empty( $saved['mission'] ) || ! is_array( $saved['mission'] ) ) {
			return;
		}
		$changed = false;
		foreach ( array( 'heading1' => array( 'Bringing Christ', 'Taking the Gospel' ), 'heading2' => array( 'to the brick kilns of Pakistan.', 'to the villages of Pakistan.' ) ) as $key => $pair ) {
			if ( isset( $saved['mission'][ $key ] ) && $pair[0] === trim( (string) $saved['mission'][ $key ] ) ) {
				$saved['mission'][ $key ] = $pair[1];
				$changed                  = true;
			}
		}
		if ( $changed ) {
			update_post_meta( $front, '_krm_content_home', $saved );
		}
	},
	32
);

/**
 * Theme 1.22.2: the home "Three Ways to Stand With Us" small heading "Get Involved" becomes "Make a Difference" in a
 * saved home page that still holds the old default; a heading typed by hand is kept.
 */
add_action(
	'init',
	function () {
		if ( (int) get_option( 'krm_involved_eyebrow_version' ) >= 1 ) {
			return;
		}
		update_option( 'krm_involved_eyebrow_version', 1 );
		$front = (int) get_option( 'page_on_front' );
		$saved = $front ? get_post_meta( $front, '_krm_content_home', true ) : null;
		if ( is_array( $saved ) && isset( $saved['involved']['eyebrow'] ) && 'Get Involved' === trim( (string) $saved['involved']['eyebrow'] ) ) {
			$saved['involved']['eyebrow'] = 'Make a Difference';
			update_post_meta( $front, '_krm_content_home', $saved );
		}
	},
	33
);

/**
 * Theme 1.22.4: the client's Clean Water Project photos (assets/images/content/clean-water-project-01…27.jpg, listed in
 * inc/data/ministries.php). An existing Clean Water Projects ministry whose gallery is still empty gets them with
 * their captions, and its photo on cards / the home explorer, while still the shared placeholder ("General pictures
 * 14"), becomes the celebrating families photo. A gallery or photo chosen by hand is kept.
 */
add_action(
	'init',
	function () {
		if ( (int) get_option( 'krm_water_photos_version' ) >= 1 || ! post_type_exists( 'ministry' ) ) {
			return;
		}
		update_option( 'krm_water_photos_version', 1 );
		$post = krm_get_by_path( 'clean-water', 'ministry' );
		$data = krm_ministry_data();
		if ( ! $post || empty( $data['items']['clean-water']['photos'] ) ) {
			return;
		}
		$m = $data['items']['clean-water'];
		if ( ! array_filter( (array) get_post_meta( $post->ID, '_krm_gallery', true ) ) ) {
			$alts = array();
			foreach ( $m['photos'] as $photo ) {
				$alts[ $photo[0] ] = $photo[1];
			}
			update_post_meta( $post->ID, '_krm_gallery', wp_list_pluck( $m['photos'], 0 ) );
			update_post_meta( $post->ID, '_krm_photo_alts', $alts );
		}
		$placeholder = 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg';
		$explorer    = (string) get_post_meta( $post->ID, '_krm_explorer_image', true );
		if ( '' === $explorer || $placeholder === $explorer || ( krm_media_id( $placeholder ) && (string) krm_media_id( $placeholder ) === $explorer ) ) {
			update_post_meta( $post->ID, '_krm_explorer_image', $m['explorer_image'] );
		}
		if ( function_exists( 'krm_purge_page_caches' ) ) {
			krm_purge_page_caches();
		}
	},
	34
);

/**
 * Theme 1.22.7: addresses without a trailing slash (/about, not /about/). A site on the theme's structure
 * "/news/%postname%/" moves to "/news/%postname%" once; WordPress then links every page that way and redirects the old
 * "/about/" addresses (301). A structure chosen by hand (anything else) is left alone. See inc/permalinks.php.
 */
add_action(
	'init',
	function () {
		if ( (int) get_option( 'krm_permalink_version' ) >= 1 ) {
			return;
		}
		update_option( 'krm_permalink_version', 1 );
		if ( in_array( get_option( 'permalink_structure' ), array( '/news/%postname%/', '/%postname%/' ), true ) ) {
			global $wp_rewrite;
			$wp_rewrite->set_permalink_structure( krm_permalink_structure() );
			add_action(
				'shutdown',
				function () {
					update_option( 'krm_flush_rewrite', 1 ); // Rules are rebuilt on the next request.
				}
			);
			if ( function_exists( 'krm_purge_page_caches' ) ) {
				krm_purge_page_caches(); // Cached pages still hold the old "/about/" links.
			}
		}
	},
	35
);

/**
 * Create the footer's policy pages that are missing (inc/data/policies.php): Privacy Policy, Terms & Conditions,
 * Donation & Refund Policy, Financial Accountability. A page that exists at the address is kept as it is, with one
 * exception: WordPress's own "Privacy Policy" draft (made at install, still holding its "Suggested text:" template) gets
 * the theme's text and is published. Contact placeholders and links inside the text are filled in now.
 *
 * @return string[] Log lines.
 */
function krm_ensure_policy_pages() {
	$log  = array();
	$fill = function ( $html ) {
		$html = strtr(
			$html,
			array(
				'{email}'   => esc_html( krm_opt( 'email' ) ),
				'{phone}'   => esc_html( krm_opt( 'phone' ) ),
				'{address}' => esc_html( krm_opt( 'address' ) ),
			)
		);
		return preg_replace_callback(
			'#href="(/[a-z0-9/-]*)"#',
			function ( $m ) {
				return 'href="' . esc_url( krm_link_slash( home_url( $m[1] ) ) ) . '"';
			},
			$html
		);
	};
	foreach ( include KRM_DIR . '/inc/data/policies.php' as $slug => $policy ) {
		$page = null;
		if ( 'privacy-policy' === $slug && get_option( 'wp_page_for_privacy_policy' ) ) {
			$page = get_post( (int) get_option( 'wp_page_for_privacy_policy' ) );
			$page = ( $page && 'page' === $page->post_type && 'trash' !== $page->post_status ) ? $page : null;
		}
		if ( ! $page ) {
			$page = krm_get_by_path( $slug );
		}
		if ( $page ) {
			$wp_template = 'privacy-policy' === $slug && 'draft' === $page->post_status && false !== strpos( $page->post_content, 'Suggested text:' );
			if ( $wp_template ) {
				wp_update_post(
					array(
						'ID'           => $page->ID,
						'post_title'   => $policy[0],
						'post_content' => $fill( $policy[2] ),
						'post_status'  => 'publish',
					)
				);
				update_option( 'wp_page_for_privacy_policy', $page->ID );
				$log[] = sprintf( 'Published page: %s (WordPress’s draft, with the theme’s text)', $policy[0] );
			}
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $policy[0],
				'post_name'    => $slug,
				'post_content' => $fill( $policy[2] ),
			),
			true
		);
		if ( ! is_wp_error( $id ) ) {
			if ( 'privacy-policy' === $slug ) {
				update_option( 'wp_page_for_privacy_policy', $id );
			}
			$log[] = sprintf( 'Created page: %s', $policy[0] );
		}
	}
	return $log;
}

/**
 * Theme 1.23.0 (option krm_home_layout_version 1): the new home page.
 * - The footer's policy pages are created on sites that were set up before (krm_ensure_policy_pages()).
 * - Saved home page content still holding an earlier default for a field whose default changed is cleared, so the new
 *   default shows ("About Us" → "Who We Are", "Since 2018" → "Founded in 2018", the hero buttons, the news heading, the
 *   map's places list without Lahore and Karachi…); anything typed by hand is kept.
 * - The red navbar button "Donate" reads "Donate Now" (menu items titled exactly "Donate" with the menu-donate class).
 * - Cached pages are cleared (krm_purge_page_caches() runs once per version anyway).
 */
add_action(
	'init',
	function () {
		if ( (int) get_option( 'krm_home_layout_version' ) >= 1 ) {
			return;
		}
		update_option( 'krm_home_layout_version', 1 );
		if ( get_option( 'krm_setup_done' ) || krm_get_by_path( 'about' ) ) {
			krm_ensure_policy_pages();
		}
		$front = (int) get_option( 'page_on_front' );
		$saved = $front ? get_post_meta( $front, '_krm_content_home', true ) : null;
		if ( is_array( $saved ) ) {
			$old = array(
				'hero.button1_label' => 'Explore What We Do',
				'hero.button1_url'   => '#ministries',
				'hero.button2_label' => 'Watch Our Story',
				'hero.button2_url'   => '#field-stories',
				'about.eyebrow'      => 'About Us',
				'about.lead'         => 'Kingdom Revival Ministries exists to glorify God by making disciples of Jesus Christ — proclaiming the Gospel among unreached communities, planting churches, equipping leaders, and serving vulnerable children and brick kiln families across Pakistan.',
				'about.badge_title'  => 'Since 2018',
				'about.badge_text'   => 'Serving in Faisalabad & beyond',
				'about.badge_title_2' => 'Founded in 2018', // 1.23.0's badge, before the "Founded in 2018" tag took over.
				'where.region'       => 'Punjab, Pakistan',
				'news.eyebrow'       => 'From the field',
				'news.heading'       => 'Latest news & blogs',
				'news.all_label'     => 'View all posts →',
				'news.more_label'    => 'Read more →',
			);
			$changed = false;
			foreach ( $old as $path => $value ) {
				list( $section, $field ) = explode( '.', preg_replace( '/_2$/', '', $path ) );
				if ( isset( $saved[ $section ][ $field ] ) && is_string( $saved[ $section ][ $field ] ) && trim( $saved[ $section ][ $field ] ) === $value ) {
					unset( $saved[ $section ][ $field ] );
					$changed = true;
				}
			}
			// The map's places: a saved list that is still the earlier default four towns gets the new default (with
			// Lahore and Karachi); a list edited by hand is kept as it is.
			if ( isset( $saved['where']['places'] ) && is_array( $saved['where']['places'] ) ) {
				$names = array_map(
					function ( $p ) {
						return is_array( $p ) ? strtolower( trim( (string) ( $p['name'] ?? '' ) ) ) : '';
					},
					$saved['where']['places']
				);
				if ( array( 'faisalabad', 'jhang', 'toba tek singh', 'chiniot' ) === $names ) {
					unset( $saved['where']['places'] );
					$changed = true;
				}
			}
			// Stories saved before 1.23.0 have no photo or link: they get the default story's photo when the name matches.
			if ( isset( $saved['stories']['items'] ) && is_array( $saved['stories']['items'] ) ) {
				$defaults = array();
				foreach ( krm_content_schema( 'home' )['stories']['fields']['items']['default'] as $d ) {
					$defaults[ $d['name'] ] = $d;
				}
				foreach ( $saved['stories']['items'] as $i => $story ) {
					$name = is_array( $story ) ? trim( (string) ( $story['name'] ?? '' ) ) : '';
					if ( isset( $defaults[ $name ] ) && ! array_key_exists( 'image', $story ) ) {
						$saved['stories']['items'][ $i ]['image'] = $defaults[ $name ]['image'];
						$saved['stories']['items'][ $i ]['alt']   = $defaults[ $name ]['alt'];
						$changed                                  = true;
					}
				}
			}
			if ( $changed ) {
				update_post_meta( $front, '_krm_content_home', $saved );
			}
		}
		// The navbar button: its menu item has the menu-donate class and either no title of its own (it then shows the
		// Donate page's title) or "Donate". A title typed by hand is kept.
		$items = get_posts(
			array(
				'post_type'      => 'nav_menu_item',
				'post_status'    => 'any',
				'posts_per_page' => 200,
				'meta_key'       => '_menu_item_classes', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_compare'   => 'LIKE',
				'meta_value'     => 'menu-donate', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);
		foreach ( $items as $item ) {
			$title = trim( wp_specialchars_decode( $item->post_title, ENT_QUOTES ) );
			if ( in_array( 'menu-donate', (array) get_post_meta( $item->ID, '_menu_item_classes', true ), true ) && in_array( $title, array( '', 'Donate' ), true ) ) {
				wp_update_post(
					array(
						'ID'         => $item->ID,
						'post_title' => 'Donate Now',
					)
				);
			}
		}
	},
	36
);

/**
 * Theme 1.26.0: the Bible Distribution ministry page's hero (its first gallery photo) becomes the Contact page's hero
 * photo (Urdu Bible Distribution 17): that photo moves to the front of the gallery, only while the gallery still
 * starts with the earlier default (Urdu Bible Distribution 18). A hero photo chosen by hand is kept.
 */
add_action(
	'init',
	function () {
		if ( (int) get_option( 'krm_bible_hero_version' ) >= 1 || ! post_type_exists( 'ministry' ) ) {
			return;
		}
		update_option( 'krm_bible_hero_version', 1 );
		$post = krm_get_by_path( 'bible-distribution', 'ministry' );
		if ( ! $post ) {
			return;
		}
		$up      = 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/';
		$is      = function ( $ref, $url ) {
			return (string) $ref === $url || ( krm_media_id( $url ) && (string) krm_media_id( $url ) === (string) $ref );
		};
		$gallery = array_values( array_filter( (array) get_post_meta( $post->ID, '_krm_gallery', true ) ) );
		if ( ! $gallery || ! $is( $gallery[0], $up . 'Urdu-Bible-Distribution-18-scaled.jpg' ) ) {
			return;
		}
		foreach ( $gallery as $i => $ref ) {
			if ( $is( $ref, $up . 'Urdu-Bible-Distribution-17-scaled.jpg' ) ) {
				unset( $gallery[ $i ] );
				array_unshift( $gallery, $ref );
				update_post_meta( $post->ID, '_krm_gallery', array_values( $gallery ) );
				if ( function_exists( 'krm_purge_page_caches' ) ) {
					krm_purge_page_caches();
				}
				return;
			}
		}
	},
	37
);

/**
 * Theme 1.26.0: the About page's new "What drives us" section is headed "What we are here to do", so the Vision &
 * Mission heading that said the same becomes "Why we exist" in a saved About page still holding the old default.
 * A heading typed by hand is kept.
 */
add_action(
	'init',
	function () {
		if ( (int) get_option( 'krm_about_mission_heading_version' ) >= 1 ) {
			return;
		}
		update_option( 'krm_about_mission_heading_version', 1 );
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'     => 'page-templates/about.php', // phpcs:ignore WordPress.DB.SlowDBQuery
			)
		);
		foreach ( $pages as $id ) {
			$saved = get_post_meta( $id, '_krm_content_about', true );
			if ( is_array( $saved ) && isset( $saved['mission']['heading'] ) && 'What we are here to do' === trim( wp_strip_all_tags( (string) $saved['mission']['heading'] ) ) ) {
				$saved['mission']['heading'] = 'Why we exist';
				update_post_meta( $id, '_krm_content_about', $saved );
			}
		}
	},
	38
);
