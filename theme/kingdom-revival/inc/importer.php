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
 * Alt texts that theme versions before 1.7.4 gave to every photo of a ministry; Setup replaces them.
 */
function krm_old_generic_alts() {
	return array( 'Baptism in a local community', 'Urdu Bible distribution', 'Children and families in a brick kiln community', 'Deliverance and healing ministry', 'Leadership and discipleship gathering', 'Children receiving care at an orphanage', 'Widows ministry' );
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
		'ministries' => array( true, sprintf( /* translators: %d: count */ __( 'Create the 16 ministries of the client’s sitemap and their two groups (currently %d published; “Youth Ministry / Women Empowerment” is created as a draft). Existing ministries with the same slug are left as they are.', 'kingdom-revival' ), (int) $ministries->publish ) ),
		'posts'      => array( true, __( 'Create the 6 clearly marked sample News stories and their categories, and 12 dummy Testimonials (one per ministry). Entries whose slug already exists are skipped. WordPress’s sample post “Hello world!” is moved to the trash if it was never edited.', 'kingdom-revival' ) ),
		'pages'      => array( true, __( 'Create the sitemap’s pages if they do not exist: About Us, Our Impact (with Testimonials and Photo & Video Gallery), News & Updates, Partner With Us (with Prayer Request), Contact and Donate.', 'kingdom-revival' ) ),
		'menus'      => array( true, __( 'Create the main and footer menus and assign them to this theme’s empty menu locations.', 'kingdom-revival' ) ),
		'reading'    => array( ! $reading_ok, $reading_ok ? __( 'Reading settings: a static front page and a posts page are already set. Tick to point the posts page at “News & Updates” anyway.', 'kingdom-revival' ) : __( 'Reading settings: show a static front page and use “News & Updates” as the posts page (required for the design).', 'kingdom-revival' ) ),
		'permalinks' => array( $plain, $plain ? __( 'Permalinks are “Plain”: switch to /news/post-name links (stories under News & Updates; recommended for SEO).', 'kingdom-revival' ) : sprintf( /* translators: %s: structure */ __( 'Permalinks already use “%s”. Tick only if you want /post-name/ instead.', 'kingdom-revival' ), get_option( 'permalink_structure' ) ) ),
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
			// Earlier versions gave every photo of a ministry the same alt text; give the Media Library copy this photo's own
			// (galleries show their own captions, so pages do not change).
			$id = (int) $map[ $url ];
			if ( wp_attachment_is_image( $id ) && in_array( get_post_meta( $id, '_wp_attachment_image_alt', true ), krm_old_generic_alts(), true ) ) {
				update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( $alt ) );
			}
			continue;
		}
		// Shipped with the theme ("krm-theme:" photos and the old site's Elementor thumbnails): imported from the theme's
		// copy under its own file name, so nothing is downloaded from the old site's elementor/thumbs folder.
		$local      = krm_media_local( $url );
		$theme_file = '' !== $local ? get_theme_file_path( 'assets/' . $local ) : '';
		if ( $theme_file ) {
			$name = wp_basename( $theme_file );
		}
		$id = 0 === strpos( $url, 'krm-theme:' ) ? 0 : attachment_url_to_postid( $url );
		if ( $id ) {
			$log[] = sprintf( 'Reused from Media Library: %s', $name );
		} else {
			if ( $theme_file ) {
				// Shipped with the theme: copy it, because media_handle_sideload() moves the file it is given.
				$tmp = wp_tempnam( $name );
				if ( ! $tmp || ! copy( $theme_file, $tmp ) ) {
					$log[] = sprintf( 'Could not copy %s — the page keeps using the theme’s copy.', $name );
					continue;
				}
			} else {
				$tmp = download_url( $url, 300 );
			}
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

/**
 * Bring an existing ministry's photos up to date with theme 1.7.4, touching only what was never edited:
 * a gallery that still shows only the old placeholder photo gets the ministry's own photos; the old sideways
 * Urdu Bible photo is swapped for the upright one, keeping its caption. Gallery captions are otherwise left alone.
 *
 * @param int   $id Ministry ID.
 * @param array $m  The ministry's data from inc/data/ministries.php.
 * @return bool Whether anything changed.
 */
function krm_upgrade_ministry_photos( $id, $m ) {
	$up          = 'https://kingdomrevivalministries.com/wp-content/uploads/';
	$placeholder = $up . 'elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg';
	$swaps       = array( $up . '2026/07/Urdu-Bible-Distribution-12.jpg' => 'krm-theme:images/content/urdu-bible-distribution-12.jpg' );
	$gallery     = (array) get_post_meta( $id, '_krm_gallery', true );
	$alts        = (array) get_post_meta( $id, '_krm_photo_alts', true );
	$changed     = false;

	$is_placeholder = static function ( $ref ) use ( $placeholder ) {
		return (string) $ref === $placeholder || (string) $ref === (string) krm_media_id_fresh( $placeholder );
	};
	if ( 1 === count( $gallery ) && $is_placeholder( $gallery[0] ) && ! $is_placeholder( $m['photos'][0][0] ) ) {
		$gallery = array_map( 'krm_import_ref', wp_list_pluck( $m['photos'], 0 ) );
		$alts    = array();
		foreach ( $gallery as $ref ) {
			$alts[ (string) $ref ] = $m['photos'][ count( $alts ) ][1];
		}
		if ( $is_placeholder( get_post_meta( $id, '_krm_explorer_image', true ) ) ) {
			update_post_meta( $id, '_krm_explorer_image', krm_import_ref( $m['explorer_image'] ) );
		}
		if ( is_int( $gallery[0] ) ) {
			set_post_thumbnail( $id, $gallery[0] );
		}
		$changed = true;
	}
	// Theme 1.14.0 (photos from the client's data folder): an empty gallery gets the ministry's photos (Church
	// Planting); a gallery that still holds only the theme's photos, unedited, gets the ones added since (Widows
	// Support). A gallery someone has edited — a photo the theme never had — is left alone.
	$data_refs = array();
	foreach ( (array) $m['photos'] as $photo ) {
		$data_refs[] = array( (string) $photo[0], (string) krm_media_id_fresh( $photo[0] ), (string) $photo[1], $photo[0] );
	}
	$in_data = static function ( $ref ) use ( $data_refs ) {
		foreach ( $data_refs as $d ) {
			if ( (string) $ref === $d[0] || ( '0' !== $d[1] && (string) $ref === $d[1] ) ) {
				return true;
			}
		}
		return false;
	};
	$unedited = $gallery ? count( array_filter( $gallery, $in_data ) ) === count( $gallery ) : true;
	if ( $data_refs && $unedited ) {
		$was_empty = ! $gallery;
		foreach ( $data_refs as $d ) {
			if ( ! in_array( $d[0], array_map( 'strval', $gallery ), true ) && ( '0' === $d[1] || ! in_array( $d[1], array_map( 'strval', $gallery ), true ) ) ) {
				$ref                   = krm_import_ref( $d[3] );
				$gallery[]             = $ref;
				$alts[ (string) $ref ] = $d[2];
				$changed               = true;
			}
		}
		if ( $was_empty && $gallery && is_int( $gallery[0] ) && ! has_post_thumbnail( $id ) ) {
			set_post_thumbnail( $id, $gallery[0] );
		}
	}
	foreach ( $swaps as $old => $new ) {
		$old_refs = array_filter( array( $old, (string) krm_media_id_fresh( $old ) ) );
		foreach ( $gallery as $i => $ref ) {
			if ( in_array( (string) $ref, $old_refs, true ) ) {
				$gallery[ $i ] = krm_import_ref( $new );
				if ( isset( $alts[ (string) $ref ] ) ) {
					// The caption stays the same; only the photo changes.
					$alts[ (string) $gallery[ $i ] ] = $alts[ (string) $ref ];
					unset( $alts[ (string) $ref ] );
				}
				$changed = true;
			}
		}
	}
	if ( $changed ) {
		update_post_meta( $id, '_krm_gallery', array_values( $gallery ) );
		update_post_meta( $id, '_krm_photo_alts', $alts );
	}
	return $changed;
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
		$group_ids = krm_ensure_ministry_groups();
		foreach ( $data['items'] as $slug => $m ) {
			$existing = krm_get_by_path( $slug, 'ministry' );
			if ( ! $existing ) {
				$any = get_posts(
					array(
						'post_type'   => 'ministry',
						'name'        => $slug,
						'post_status' => array( 'draft', 'private', 'pending' ),
						'numberposts' => 1,
					)
				);
				$existing = $any ? $any[0] : null;
			}
			if ( $existing ) {
				$ministry_ids[ $slug ] = $existing->ID;
				$log[]                 = krm_upgrade_ministry_photos( $existing->ID, $m )
					? sprintf( 'Ministry exists, photos updated to the theme’s new defaults: %s', $m['title'] )
					: sprintf( 'Ministry exists, left unchanged: %s', $m['title'] );
				continue;
			}
			$id = krm_create_ministry( $slug, $m, $group_ids, true );
			if ( is_wp_error( $id ) ) {
				$log[] = sprintf( 'Could not create ministry %s: %s', $m['title'], $id->get_error_message() );
				continue;
			}
			$ministry_ids[ $slug ] = $id;
			$log[]                 = sprintf( 'Created ministry: %s', $m['title'] );
		}
	}
	foreach ( array_keys( $data['items'] ) as $slug ) {
		if ( ! isset( $ministry_ids[ $slug ] ) ) {
			$found = krm_get_by_path( $slug, 'ministry' );
			if ( $found ) {
				$ministry_ids[ $slug ] = $found->ID;
			}
		}
	}

	// Stories.
	if ( $opts['posts'] ) {
		foreach ( include KRM_DIR . '/inc/data/posts.php' as $p ) {
			if ( krm_get_by_path( $p['slug'], 'post' ) ) {
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
				'_krm_sample'    => 1,
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

		krm_ensure_news_categories();

		// Dummy testimonials, one per ministry (inc/data/testimonials.php). Skipped when one with the same slug exists.
		$log = array_merge( $log, krm_create_sample_testimonials() );

		// WordPress's sample post would show in News & Updates. Trashed only while it is still untouched.
		$sample = krm_get_by_path( 'hello-world', 'post' );
		if ( $sample && 'publish' === $sample->post_status && $sample->post_modified_gmt === $sample->post_date_gmt ) {
			wp_trash_post( $sample->ID );
			$log[] = 'Moved WordPress’s sample post “Hello world!” to the trash';
		}
	}

	// Pages (inc/data/pages.php).
	$found    = krm_ensure_pages( (bool) $opts['pages'] );
	$page_ids = $found['ids'];
	$log      = array_merge( $log, $found['log'] );
	if ( $opts['pages'] ) {
		$log = array_merge( $log, krm_ensure_policy_pages() ); // Footer policy pages (inc/data/policies.php, theme 1.23.0).
	}

	// Reading settings.
	if ( $opts['reading'] ) {
		if ( 'page' !== get_option( 'show_on_front' ) || ! get_option( 'page_on_front' ) ) {
			$home = krm_get_by_path( 'home' );
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
		if ( ! empty( $page_ids['news'] ) ) {
			update_option( 'page_for_posts', $page_ids['news'] );
			$log[] = 'Reading settings: “News & Updates” is now the posts page.';
		}
	}

	// Menus.
	if ( $opts['menus'] ) {
		$log = array_merge( $log, krm_import_menus( $ministry_ids, $page_ids, $data ) );
	}

	// Permalinks.
	if ( $opts['permalinks'] ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( krm_permalink_structure() );
		$log[] = 'Permalinks set to /news/post-name (stories live under News & Updates; no "/" at the end of addresses).';
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

	$menus = krm_menu_definitions( $ministry_ids, $page_ids );

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
