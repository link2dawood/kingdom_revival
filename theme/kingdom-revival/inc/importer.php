<?php
/**
 * Kingdom Revival → Setup: imports the design's images, ministries, stories, pages and menus.
 *
 * Safe on an existing site:
 *  - nothing is deleted or overwritten; anything that already exists (same slug, same menu name) is skipped,
 *  - images already in the Media Library (same URL) are reused instead of downloaded again,
 *  - Reading settings and permalinks change only when their boxes are ticked,
 *  - menus are only assigned to this theme's empty menu locations.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_menu',
	function () {
		add_submenu_page( 'krm-settings', __( 'Setup', 'kingdom-revival' ), __( 'Setup', 'kingdom-revival' ), 'manage_options', 'krm-setup', 'krm_setup_page' );
	}
);

/**
 * The design's media manifest: remote URL => alt text.
 */
function krm_media_manifest() {
	return include KRM_DIR . '/inc/data/media.php';
}

/**
 * How many design files are already linked to Media Library items.
 */
function krm_media_done_count() {
	$map = get_option( 'krm_media_map', array() );
	$n   = 0;
	foreach ( array_keys( krm_media_manifest() ) as $url ) {
		if ( ! empty( $map[ $url ] ) && get_post( $map[ $url ] ) ) {
			++$n;
		}
	}
	return $n;
}

/**
 * Render Kingdom Revival → Setup.
 */
function krm_setup_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$total      = count( krm_media_manifest() );
	$done       = krm_media_done_count();
	$ministries = wp_count_posts( 'ministry' );
	$plain      = '' === get_option( 'permalink_structure' );
	$reading_ok = 'page' === get_option( 'show_on_front' ) && get_option( 'page_for_posts' );
	$checks     = array(
		'media'      => array( true, sprintf( /* translators: 1: done, 2: total */ __( 'Import the design’s images and video into the Media Library (%1$d of %2$d done). Files already in the library are reused, and descriptive alt text is added where missing.', 'kingdom-revival' ), $done, $total ) ),
		'ministries' => array( true, sprintf( /* translators: %d: count */ __( 'Create the 12 ministries and their groups (currently %d published). Existing ministries with the same slug are left as they are.', 'kingdom-revival' ), (int) $ministries->publish ) ),
		'posts'      => array( true, __( 'Create the 7 News & Blogs stories and their categories. Stories whose slug already exists are skipped.', 'kingdom-revival' ) ),
		'pages'      => array( true, __( 'Create the About, Contact, Donate, Become a Partner and News & Blogs pages if they do not exist.', 'kingdom-revival' ) ),
		'menus'      => array( true, __( 'Create the main and footer menus and assign them to this theme’s empty menu locations.', 'kingdom-revival' ) ),
		'reading'    => array( ! $reading_ok, $reading_ok ? __( 'Reading settings: a static front page and a posts page are already set. Tick to point the posts page at “News & Blogs” anyway.', 'kingdom-revival' ) : __( 'Reading settings: show a static front page and use “News & Blogs” as the posts page (required for the design).', 'kingdom-revival' ) ),
		'permalinks' => array( $plain, $plain ? __( 'Permalinks are “Plain”: switch to /post-name/ links (recommended for SEO).', 'kingdom-revival' ) : sprintf( /* translators: %s: structure */ __( 'Permalinks already use “%s”. Tick only if you want /post-name/ instead.', 'kingdom-revival' ), get_option( 'permalink_structure' ) ) ),
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Kingdom Revival Ministries — Setup', 'kingdom-revival' ); ?></h1>
		<p><?php esc_html_e( 'Brings the approved design’s content into WordPress so everything is editable. You can run it again at any time: completed steps are skipped, and nothing existing is deleted or overwritten.', 'kingdom-revival' ); ?></p>
		<?php if ( ! get_option( 'blog_public' ) ) : ?>
			<div class="notice notice-warning inline"><p><?php esc_html_e( 'Search engines are currently discouraged from indexing this site (Settings → Reading). Untick that option when the site goes live.', 'kingdom-revival' ); ?></p></div>
		<?php endif; ?>
		<form id="krm-setup">
			<fieldset>
				<?php foreach ( $checks as $key => $check ) : ?>
					<p><label><input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value="1" <?php checked( $check[0] ); ?>> <?php echo esc_html( $check[1] ); ?></label></p>
				<?php endforeach; ?>
			</fieldset>
			<p><button type="submit" class="button button-primary button-hero"><?php esc_html_e( 'Run setup', 'kingdom-revival' ); ?></button></p>
		</form>
		<div id="krm-progress" hidden><progress max="100" value="0" style="width:100%;max-width:520px"></progress><ol id="krm-log"></ol></div>
	</div>
	<script>
	(function () {
		var form = document.getElementById("krm-setup"), log = document.getElementById("krm-log"), bar = document.querySelector("#krm-progress progress");
		var nonce = <?php echo wp_json_encode( wp_create_nonce( 'krm_setup' ) ); ?>, ajax = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
		function say(text) { var li = document.createElement("li"); li.textContent = text; log.appendChild(li); }
		function call(step, offset, opts) {
			var data = new FormData();
			data.append("action", "krm_setup"); data.append("nonce", nonce); data.append("step", step); data.append("offset", offset);
			Object.keys(opts).forEach(function (k) { data.append(k, opts[k]); });
			return fetch(ajax, { method: "POST", body: data, credentials: "same-origin" }).then(function (r) { return r.json(); });
		}
		form.addEventListener("submit", function (e) {
			e.preventDefault();
			var opts = {};
			form.querySelectorAll("input[type=checkbox]").forEach(function (c) { opts[c.name] = c.checked ? "1" : ""; });
			form.querySelector("button").disabled = true;
			document.getElementById("krm-progress").hidden = false;
			function media(offset) {
				if (!opts.media) return Promise.resolve();
				return call("media", offset, opts).then(function (res) {
					if (!res.success) throw new Error(res.data && res.data.message || "Image import failed");
					(res.data.log || []).forEach(say);
					bar.value = Math.round(res.data.next / res.data.total * 80);
					if (res.data.next < res.data.total) return media(res.data.next);
				});
			}
			media(0).then(function () { return call("content", 0, opts); }).then(function (res) {
				if (!res.success) throw new Error(res.data && res.data.message || "Content import failed");
				(res.data.log || []).forEach(say);
				bar.value = 100;
				say("Setup finished.");
			}).catch(function (err) { say("Stopped: " + err.message + " — you can run setup again to continue."); })
				.then(function () { form.querySelector("button").disabled = false; });
		});
	})();
	</script>
	<?php
}

add_action(
	'wp_ajax_krm_setup',
	function () {
		if ( ! current_user_can( 'manage_options' ) || ! check_ajax_referer( 'krm_setup', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => 'Not allowed.' ), 403 );
		}
		$step = isset( $_POST['step'] ) ? sanitize_key( wp_unslash( $_POST['step'] ) ) : '';
		$opts = array();
		foreach ( array( 'media', 'ministries', 'posts', 'pages', 'menus', 'reading', 'permalinks' ) as $key ) {
			$opts[ $key ] = ! empty( $_POST[ $key ] );
		}
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}
		if ( 'media' === $step ) {
			wp_send_json_success( krm_import_media_batch( isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0 ) );
		}
		if ( 'content' === $step ) {
			wp_send_json_success( array( 'log' => krm_import_content( $opts ) ) );
		}
		wp_send_json_error( array( 'message' => 'Unknown step.' ), 400 );
	}
);

/* ---------------------------------------------------------------- Media */

/**
 * Import the next few design files.
 *
 * @param int $offset Start index in the manifest.
 */
function krm_import_media_batch( $offset ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$manifest = krm_media_manifest();
	$urls     = array_keys( $manifest );
	$map      = get_option( 'krm_media_map', array() );
	$map      = is_array( $map ) ? $map : array();
	$log      = array();
	$batch    = array_slice( $urls, $offset, 2 );

	foreach ( $batch as $url ) {
		$alt  = $manifest[ $url ];
		$name = wp_basename( wp_parse_url( $url, PHP_URL_PATH ) );
		if ( ! empty( $map[ $url ] ) && get_post( $map[ $url ] ) ) {
			continue;
		}
		$id = attachment_url_to_postid( $url );
		if ( $id ) {
			$log[] = sprintf( 'Reused from Media Library: %s', $name );
		} else {
			$tmp = download_url( $url, 300 );
			if ( is_wp_error( $tmp ) ) {
				$log[] = sprintf( 'Could not download %s (%s) — the page keeps using the original link.', $name, $tmp->get_error_message() );
				continue;
			}
			$id = media_handle_sideload(
				array(
					'name'     => $name,
					'tmp_name' => $tmp,
				),
				0,
				$alt
			);
			if ( is_wp_error( $id ) ) {
				wp_delete_file( $tmp );
				$log[] = sprintf( 'Could not import %s (%s).', $name, $id->get_error_message() );
				continue;
			}
			$log[] = sprintf( 'Imported: %s', $name );
		}
		if ( wp_attachment_is_image( $id ) && '' === (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) ) {
			update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( $alt ) );
		}
		$map[ $url ] = (int) $id;
		update_option( 'krm_media_map', $map, false );
	}

	return array(
		'log'   => $log,
		'next'  => min( count( $urls ), $offset + count( $batch ) ),
		'total' => count( $urls ),
	);
}

/**
 * Reference for a design image: attachment ID when imported, otherwise the URL.
 *
 * @param string $url Design URL.
 * @return int|string
 */
function krm_import_ref( $url ) {
	$id = krm_media_id_fresh( $url );
	return $id ? $id : $url;
}

/**
 * Like krm_media_id(), but reads the option directly (the static cache may predate this request's import).
 *
 * @param string $url Design URL.
 */
function krm_media_id_fresh( $url ) {
	$map = get_option( 'krm_media_map', array() );
	return ( is_array( $map ) && ! empty( $map[ $url ] ) && get_post( $map[ $url ] ) ) ? (int) $map[ $url ] : 0;
}

/* ---------------------------------------------------------------- Content */

/**
 * Create ministries, stories, pages, menus and settings, as selected.
 *
 * @param array $opts Selected steps.
 * @return string[] Log lines.
 */
function krm_import_content( $opts ) {
	$log  = array();
	$data = include KRM_DIR . '/inc/data/ministries.php';

	// Ministries and groups.
	$ministry_ids = array();
	if ( $opts['ministries'] ) {
		$group_ids = array();
		foreach ( $data['groups'] as $slug => $group ) {
			$term = term_exists( $slug, 'ministry_group' );
			if ( ! $term ) {
				$term = wp_insert_term( $group['name'], 'ministry_group', array( 'slug' => $slug ) );
			}
			if ( ! is_wp_error( $term ) ) {
				$group_ids[ $slug ] = (int) $term['term_id'];
				update_term_meta( $group_ids[ $slug ], 'krm_order', $group['order'] );
			}
		}
		foreach ( $data['items'] as $slug => $m ) {
			$existing = get_page_by_path( $slug, OBJECT, 'ministry' );
			if ( $existing ) {
				$ministry_ids[ $slug ] = $existing->ID;
				$log[]                 = sprintf( 'Ministry exists, left unchanged: %s', $m['title'] );
				continue;
			}
			$gallery = array();
			$alts    = array();
			foreach ( $m['photos'] as $photo ) {
				$ref                   = krm_import_ref( $photo[0] );
				$gallery[]             = $ref;
				$alts[ (string) $ref ] = $photo[1];
			}
			$id = wp_insert_post(
				array(
					'post_type'    => 'ministry',
					'post_status'  => 'publish',
					'post_title'   => $m['title'],
					'post_name'    => $slug,
					'post_excerpt' => $m['excerpt'],
					'menu_order'   => $m['menu_order'],
					'meta_input'   => array(
						'_krm_focus'          => $m['focus'],
						'_krm_points'         => implode( "\n", $m['points'] ),
						'_krm_gallery'        => $gallery,
						'_krm_photo_alts'     => $alts,
						'_krm_explorer_image' => krm_import_ref( $m['explorer_image'] ),
						'_krm_home_order'     => $m['home_order'],
					),
				),
				true
			);
			if ( is_wp_error( $id ) ) {
				$log[] = sprintf( 'Could not create ministry %s: %s', $m['title'], $id->get_error_message() );
				continue;
			}
			if ( isset( $group_ids[ $m['group'] ] ) ) {
				wp_set_object_terms( $id, array( $group_ids[ $m['group'] ] ), 'ministry_group' );
			}
			$thumb = $gallery && is_int( $gallery[0] ) ? $gallery[0] : krm_media_id_fresh( $m['explorer_image'] );
			if ( $thumb ) {
				set_post_thumbnail( $id, $thumb );
			}
			$ministry_ids[ $slug ] = $id;
			$log[]                 = sprintf( 'Created ministry: %s', $m['title'] );
		}
	}
	foreach ( array_keys( $data['items'] ) as $slug ) {
		if ( ! isset( $ministry_ids[ $slug ] ) ) {
			$found = get_page_by_path( $slug, OBJECT, 'ministry' );
			if ( $found ) {
				$ministry_ids[ $slug ] = $found->ID;
			}
		}
	}

	// Stories.
	if ( $opts['posts'] ) {
		foreach ( include KRM_DIR . '/inc/data/posts.php' as $p ) {
			if ( get_page_by_path( $p['slug'], OBJECT, 'post' ) ) {
				$log[] = sprintf( 'Story exists, left unchanged: %s', $p['title'] );
				continue;
			}
			$cat = term_exists( $p['category'], 'category' );
			if ( ! $cat ) {
				$cat = wp_insert_term( $p['category'], 'category' );
			}
			$cover = krm_media_id_fresh( $p['cover'] );
			$meta  = array(
				'_krm_ministry'  => isset( $ministry_ids[ $p['ministry'] ] ) ? $ministry_ids[ $p['ministry'] ] : 0,
				'_krm_read_time' => (int) $p['readTime'],
				'_krm_cover_alt' => $p['coverAlt'],
			);
			if ( ! $cover ) {
				$meta['_krm_cover_url'] = $p['cover'];
			}
			$id = wp_insert_post(
				array(
					'post_type'     => 'post',
					'post_status'   => 'publish',
					'post_title'    => $p['title'],
					'post_name'     => $p['slug'],
					'post_excerpt'  => $p['excerpt'],
					'post_content'  => krm_story_blocks( $p['body'] ),
					'post_date'     => $p['date'] . ' 09:00:00',
					'post_category' => is_wp_error( $cat ) ? array() : array( (int) $cat['term_id'] ),
					'meta_input'    => $meta,
				),
				true
			);
			if ( is_wp_error( $id ) ) {
				$log[] = sprintf( 'Could not create story %s: %s', $p['title'], $id->get_error_message() );
				continue;
			}
			if ( $cover ) {
				set_post_thumbnail( $id, $cover );
			}
			$log[] = sprintf( 'Created story: %s', $p['title'] );
		}
	}

	// Pages.
	$pages    = array(
		'about'   => 'About',
		'contact' => 'Contact',
		'donate'  => 'Donate',
		'partner' => 'Become a Partner',
		'blog'    => 'News & Blogs',
	);
	$page_ids = array();
	foreach ( $pages as $slug => $title ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$page_ids[ $slug ] = $page->ID;
			if ( $opts['pages'] ) {
				$log[] = sprintf( 'Page exists, left unchanged: %s', $title );
			}
			continue;
		}
		if ( ! $opts['pages'] ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'     => 'page',
				'post_status'   => 'publish',
				'post_title'    => $title,
				'post_name'     => $slug,
				'page_template' => in_array( $slug, krm_design_pages(), true ) ? 'page-templates/' . $slug . '.php' : '',
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			$page_ids[ $slug ] = $id;
			$log[]             = sprintf( 'Created page: %s', $title );
		}
	}

	// Reading settings.
	if ( $opts['reading'] ) {
		if ( 'page' !== get_option( 'show_on_front' ) || ! get_option( 'page_on_front' ) ) {
			$home = get_page_by_path( 'home' );
			$home = $home ? $home->ID : wp_insert_post(
				array(
					'post_type'   => 'page',
					'post_status' => 'publish',
					'post_title'  => 'Home',
					'post_name'   => 'home',
				)
			);
			if ( $home && ! is_wp_error( $home ) ) {
				update_option( 'page_on_front', $home );
				update_option( 'show_on_front', 'page' );
				$log[] = 'Reading settings: the “Home” page is now the front page.';
			}
		}
		if ( ! empty( $page_ids['blog'] ) ) {
			update_option( 'page_for_posts', $page_ids['blog'] );
			$log[] = 'Reading settings: “News & Blogs” is now the posts page.';
		}
	}

	// Menus.
	if ( $opts['menus'] ) {
		$log = array_merge( $log, krm_import_menus( $ministry_ids, $page_ids, $data ) );
	}

	// Permalinks.
	if ( $opts['permalinks'] ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$log[] = 'Permalinks set to /post-name/.';
	}

	flush_rewrite_rules( false );
	update_option( 'krm_setup_done', time() );
	return $log;
}

/**
 * Story body (design blocks) → editor blocks.
 *
 * @param array $body Blocks from inc/data/posts.php.
 */
function krm_story_blocks( $body ) {
	$out   = array();
	$first = true;
	foreach ( $body as $b ) {
		switch ( $b['type'] ) {
			case 'p':
				$out[] = $first
					? '<!-- wp:paragraph {"className":"lead"} -->' . "\n" . '<p class="lead">' . esc_html( $b['text'] ) . "</p>\n<!-- /wp:paragraph -->"
					: "<!-- wp:paragraph -->\n<p>" . esc_html( $b['text'] ) . "</p>\n<!-- /wp:paragraph -->";
				$first = false;
				break;
			case 'h2':
				$out[] = "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( $b['text'] ) . "</h2>\n<!-- /wp:heading -->";
				break;
			case 'quote':
				$out[] = "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>" . esc_html( $b['text'] ) . "</p>\n<!-- /wp:paragraph --><cite>" . esc_html( $b['cite'] ) . "</cite></blockquote>\n<!-- /wp:quote -->";
				break;
			case 'img':
				$out[] = krm_image_block( $b['src'], $b['alt'], $b['caption'] );
				break;
			case 'list':
				$items = '';
				foreach ( $b['items'] as $item ) {
					$items .= "<!-- wp:list-item -->\n<li>" . esc_html( $item ) . "</li>\n<!-- /wp:list-item -->";
				}
				$out[] = '<!-- wp:list {"className":"checks"} -->' . "\n<ul class=\"wp-block-list checks\">" . $items . "</ul>\n<!-- /wp:list -->";
				break;
			case 'gallery':
				$inner = '';
				foreach ( $b['images'] as $img ) {
					$inner .= "\n" . krm_image_block( $img[0], $img[1], '' );
				}
				$out[] = '<!-- wp:gallery {"linkTo":"none"} -->' . "\n<figure class=\"wp-block-gallery has-nested-images columns-default is-cropped\">" . $inner . "</figure>\n<!-- /wp:gallery -->";
				break;
			case 'give':
				$out[] = "<!-- wp:shortcode -->\n[krm_give]\n<!-- /wp:shortcode -->";
				break;
		}
	}
	return implode( "\n\n", $out );
}

/**
 * One image block (full size, so photos stay sharp when zoomed).
 *
 * @param string $url     Design URL.
 * @param string $alt     Alt text.
 * @param string $caption Caption.
 */
function krm_image_block( $url, $alt, $caption ) {
	$id    = krm_media_id_fresh( $url );
	$src   = $id ? wp_get_attachment_url( $id ) : $url;
	$attrs = $id ? '{"id":' . $id . ',"sizeSlug":"full","linkDestination":"none"}' : '{"sizeSlug":"full","linkDestination":"none"}';
	$class = $id ? ' class="wp-image-' . $id . '"' : '';
	$cap   = '' !== $caption ? '<figcaption class="wp-element-caption">' . esc_html( $caption ) . '</figcaption>' : '';
	return '<!-- wp:image ' . $attrs . " -->\n<figure class=\"wp-block-image size-full\"><img src=\"" . esc_url( $src ) . '" alt="' . esc_attr( $alt ) . '"' . $class . '/>' . $cap . "</figure>\n<!-- /wp:image -->";
}

/**
 * Build the design's menus and assign them to empty locations.
 *
 * @param array $ministry_ids Ministry slug => post ID.
 * @param array $page_ids     Page slug => post ID.
 * @param array $data         Ministry data (inc/data/ministries.php).
 */
function krm_import_menus( $ministry_ids, $page_ids, $data ) {
	$log       = array();
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = is_array( $locations ) ? $locations : array();

	$page_item     = function ( $slug, $title ) use ( $page_ids ) {
		return isset( $page_ids[ $slug ] ) ? array(
			'title'  => $title,
			'type'   => 'post_type',
			'object' => 'page',
			'id'     => $page_ids[ $slug ],
		) : array(
			'title' => $title,
			'url'   => krm_page_url( $slug ),
		);
	};
	$ministry_item = function ( $slug, $title ) use ( $ministry_ids ) {
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
			'url'     => home_url( '/#all-ministries' ),
			'classes' => 'menu-all',
		),
	);
	foreach ( $data['groups'] as $gslug => $group ) {
		$children = array();
		foreach ( $data['items'] as $slug => $m ) {
			if ( $m['group'] === $gslug ) {
				$children[ $m['menu_order'] ] = $ministry_item( $slug, $m['title'] );
			}
		}
		ksort( $children );
		$groups[] = array(
			'title'    => $group['name'],
			'url'      => '#',
			'children' => array_values( $children ),
		);
	}

	$menus = array(
		'primary'           => array(
			'Main navigation',
			array(
				array(
					'title' => 'Home',
					'url'   => home_url( '/' ),
				),
				array(
					'title'    => 'Ministries',
					'url'      => '#',
					'children' => $groups,
				),
				$page_item( 'about', 'About' ),
				$page_item( 'blog', 'News & Blogs' ),
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
				$page_item( 'about', 'About' ),
				$page_item( 'blog', 'News & Blogs' ),
				$page_item( 'contact', 'Contact' ),
				$page_item( 'partner', 'Become a Partner' ),
				$page_item( 'donate', 'Donate' ),
			),
		),
		'footer_ministries' => array(
			'Footer — Ministries',
			array(
				$ministry_item( 'children-ministry', 'Children Ministry' ),
				$ministry_item( 'orphan-and-children-care', 'Orphan & Children Care' ),
				$ministry_item( 'freedom-from-slavery', 'Freedom from Slavery' ),
				$ministry_item( 'urdu-bibles', 'Urdu Bibles' ),
				$ministry_item( 'leadership-and-discipleship', 'Leadership & Discipleship' ),
				array(
					'title' => 'All ministries →',
					'url'   => home_url( '/#all-ministries' ),
				),
			),
		),
		'footer_home'       => array(
			'Footer — Home Page Quick Links',
			array(
				array(
					'title' => 'Home',
					'url'   => home_url( '/' ),
				),
				$page_item( 'about', 'About' ),
				$page_item( 'blog', 'Latest News & Blogs' ),
				array(
					'title' => 'Donate',
					'url'   => home_url( '/#donate' ),
				),
				$page_item( 'contact', 'Contact' ),
				$page_item( 'partner', 'Become a Partner' ),
			),
		),
	);

	foreach ( $menus as $location => $menu ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			$log[] = sprintf( 'Menu location already has a menu, left unchanged: %s', $menu[0] );
			continue;
		}
		$existing = wp_get_nav_menu_object( $menu[0] );
		if ( $existing ) {
			$menu_id = $existing->term_id;
		} else {
			$menu_id = wp_create_nav_menu( $menu[0] );
			if ( is_wp_error( $menu_id ) ) {
				$log[] = sprintf( 'Could not create menu %s.', $menu[0] );
				continue;
			}
			krm_add_menu_items( $menu_id, $menu[1], 0 );
			$log[] = sprintf( 'Created menu: %s', $menu[0] );
		}
		$locations[ $location ] = $menu_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );
	return $log;
}

/**
 * Add menu items (and their children) to a menu.
 *
 * @param int   $menu_id   Menu ID.
 * @param array $items     Items: title, url, type, object, object_id, classes, children.
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
		if ( isset( $item['type'] ) ) {
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
