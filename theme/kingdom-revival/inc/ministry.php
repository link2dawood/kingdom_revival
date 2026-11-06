<?php
/**
 * Ministries: post type, groups and editing fields.
 *
 * Fields:
 *  - Excerpt            → hero lead / homepage explorer text / meta description.
 *  - _krm_focus         → "Ministry focus" panel.
 *  - _krm_points        → "How this ministry serves" list (one per line).
 *  - _krm_gallery       → photos (attachment IDs or design URLs, in order). The first photo is the hero background.
 *  - _krm_explorer_image→ homepage explorer image.
 *  - _krm_home_order    → position in the homepage explorer (01–12; 0 = not in the explorer).
 *  - _krm_sections     → extra panels on the ministry page ("## Heading", text, "- point" lines).
 *  - _krm_camps        → camps / projects timeline ("date | location | people served" lines); hidden when empty.
 *  - _krm_churches     → churches & house churches planted ("name | place | planted" lines); hidden when empty.
 *  - _krm_gifts        → "How your gift helps" cards ("amount | what it gives | text" lines).
 *  - Status: a published ministry is live; a draft is hidden everywhere (the data file's "hidden" status).
 *  - _krm_tag, _krm_summary, _krm_raised, _krm_goal, _krm_stats → the What We Do card: label on the photo,
 *    card text, amount raised and goal (whole dollars) and impact stats ("value | label" lines). Fields left unset
 *    use the ministry's entry in inc/data/ministries.php (see krm_ministries()).
 *  - menu_order         → order in menus, the donate and partner forms.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		add_rewrite_tag( '%krm_group%', '([^/]+)' ); // Also registers the krm_group query variable.
		register_post_type(
			'ministry',
			array(
				'labels'        => array(
					// "name" titles the archive (/what-we-do/: its feed, archive title); the dashboard menu keeps "Ministries".
					'name'          => __( 'What We Do', 'kingdom-revival' ),
					'menu_name'     => __( 'Ministries', 'kingdom-revival' ),
					'singular_name' => __( 'Ministry', 'kingdom-revival' ),
					'add_new_item'  => __( 'Add New Ministry', 'kingdom-revival' ),
					'edit_item'     => __( 'Edit Ministry', 'kingdom-revival' ),
					'all_items'     => __( 'All Ministries', 'kingdom-revival' ),
					'search_items'  => __( 'Search Ministries', 'kingdom-revival' ),
				),
				'public'        => true,
				'has_archive'   => krm_wwd_base(),
				// /what-we-do/{group}/{ministry}/ (Phase 21): %krm_group% is the group page's path (krm_group_path()).
				'rewrite'       => array(
					'slug'       => krm_wwd_base() . '/%krm_group%',
					'with_front' => false,
					'walk_dirs'  => false,
				),
				'menu_icon'     => 'dashicons-groups',
				'menu_position' => 21,
				'supports'      => array( 'title', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
				'show_in_rest'  => true,
			)
		);

		register_taxonomy(
			'ministry_group',
			'ministry',
			array(
				'labels'            => array(
					'name'          => __( 'Ministry Groups', 'kingdom-revival' ),
					'singular_name' => __( 'Ministry Group', 'kingdom-revival' ),
				),
				'public'            => false,
				'show_ui'           => true,
				'show_admin_column' => true,
				'hierarchical'      => true,
				'show_in_rest'      => true,
			)
		);

		// /what-we-do/{group}/ is the group page; a single segment that is not a group (an old /what-we-do/{ministry}/
		// address) is redirected by krm_route_what_we_do(). After the archive's own rules (/what-we-do/page/2/).
		add_rewrite_rule( '^' . krm_wwd_base() . '/([^/]+)/?$', 'index.php?post_type=ministry&krm_group=$matches[1]', 'top' );

		foreach ( array( '_krm_focus', '_krm_points' ) as $key ) {
			register_post_meta(
				'ministry',
				$key,
				array(
					'type'          => 'string',
					'single'        => true,
					'show_in_rest'  => false,
					'auth_callback' => 'krm_can_edit_meta',
				)
			);
		}
	}
);

/**
 * Meta auth callback: only users who can edit the ministry may change its meta.
 *
 * @param bool   $allowed  Default permission.
 * @param string $meta_key Meta key.
 * @param int    $post_id  Post ID.
 */
function krm_can_edit_meta( $allowed, $meta_key, $post_id ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed -- WordPress callback signature.
	return current_user_can( 'edit_post', $post_id );
}

/* Flush rewrite rules once after the theme is switched on, so /ministries/ works immediately. */
add_action(
	'after_switch_theme',
	function () {
		update_option( 'krm_flush_rewrite', 1 );
	}
);
add_action(
	'init',
	function () {
		if ( get_option( 'krm_flush_rewrite' ) ) {
			flush_rewrite_rules( false );
			delete_option( 'krm_flush_rewrite' );
		}
	},
	99
);

/*
 * "?ministry=slug" on a page (Donate's "Support this ministry" links, /donate/?ministry=clean-water#give) is a
 * pre-fill parameter for that page, not a ministry query. "ministry" is also the post type's public query variable,
 * so WordPress would otherwise load that ministry (a 500 on Donate) or 404 for an unknown slug. Ministry addresses
 * (/what-we-do/{slug}/) never set "pagename", so they are unaffected.
 */
add_filter(
	'request',
	function ( $vars ) {
		if ( isset( $vars['ministry'] ) && ( ! empty( $vars['pagename'] ) || ! empty( $vars['page_id'] ) ) ) {
			unset( $vars['ministry'] );
			if ( isset( $vars['post_type'] ) && 'ministry' === $vars['post_type'] ) {
				unset( $vars['post_type'], $vars['name'] );
			}
		}
		return $vars;
	}
);

/* ---------- Addresses: /what-we-do/{group}/{ministry}/ (Phase 21) ---------- */

/*
 * A ministry's permalink puts its group page's path in place of %krm_group%: its Ministry Group (the term), else its
 * group in the data file, else "ministry" (a ministry with no group still opens; it just has no group page above it).
 */
add_filter(
	'post_type_link',
	function ( $link, $post ) {
		if ( 'ministry' !== $post->post_type || false === strpos( $link, '%krm_group%' ) ) {
			return $link;
		}
		$group = krm_ministry_group_slug( $post );
		return str_replace( '%krm_group%', '' !== $group ? krm_group_path( $group ) : 'ministry', $link );
	},
	10,
	2
);

/**
 * The group slug of a ministry post (the ministry_group term, else the data file's group), or ''.
 *
 * @param WP_Post $post Ministry.
 */
function krm_ministry_group_slug( $post ) {
	$terms = get_the_terms( $post, 'ministry_group' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		return $terms[0]->slug;
	}
	$data = krm_ministry_data();
	return isset( $data['items'][ $post->post_name ]['group'] ) ? $data['items'][ $post->post_name ]['group'] : '';
}

/*
 * Routing of /what-we-do/{segment}/ and /what-we-do/{group}/{ministry}/:
 *  - {segment} is a group path → the group page (archive-ministry.php shows the group);
 *  - {segment} is a ministry (its slug or an earlier slug, "was") → 301 to its address under its group, or, while the
 *    ministry is not published (a draft waiting for its description), 302 to its group page;
 *  - anything else → 404.
 *  - A published ministry opened under the wrong group → 301 to its address; an unpublished one (404) → 302 to its
 *    group page, so the address works again by itself once the ministry is published.
 */
add_action(
	'template_redirect',
	function () {
		$segment = (string) get_query_var( 'krm_group' );
		if ( '' === $segment ) {
			return;
		}
		$data = krm_ministry_data();
		if ( is_singular( 'ministry' ) ) {
			$want = get_permalink( get_queried_object() );
			if ( $want && krm_redirect_path( $want ) !== krm_redirect_path( add_query_arg( array() ) ) ) {
				wp_safe_redirect( $want, 301, 'Kingdom Revival theme' );
				exit;
			}
			return;
		}
		$name = (string) get_query_var( 'name' );
		if ( '' === $name && krm_group_by_path( $segment ) ) {
			return; // A group page.
		}
		$slug = sanitize_title( '' !== $name ? $name : $segment );
		foreach ( $data['items'] as $key => $m ) {
			if ( in_array( $slug, (array) $m['was'], true ) ) {
				$slug = $key;
				break;
			}
		}
		$post = get_posts(
			array(
				'post_type'      => 'ministry',
				'name'           => $slug,
				'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
				'posts_per_page' => 1,
				'no_found_rows'  => true,
			)
		);
		$post = $post ? $post[0] : null;
		if ( $post && 'publish' === $post->post_status ) {
			wp_safe_redirect( get_permalink( $post ), 301, 'Kingdom Revival theme' );
			exit;
		}
		if ( $post || isset( $data['items'][ $slug ] ) ) {
			$group = $post ? krm_ministry_group_slug( $post ) : $data['items'][ $slug ]['group'];
			wp_safe_redirect( '' !== $group ? krm_group_url( $group ) : krm_ministries_url(), 302, 'Kingdom Revival theme' );
			exit;
		}
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	},
	0
);

/* Group pages list every ministry of the group in one page; the main query is not paged. */
add_action(
	'pre_get_posts',
	function ( $q ) {
		if ( ! is_admin() && $q->is_main_query() && $q->is_post_type_archive( 'ministry' ) ) {
			$q->set( 'posts_per_page', 100 );
		}
	}
);

/* ---------- Admin fields ---------- */

add_action(
	'add_meta_boxes_ministry',
	function () {
		add_meta_box( 'krm-ministry', __( 'Ministry page content', 'kingdom-revival' ), 'krm_ministry_box', 'ministry', 'normal', 'high' );
	}
);

/**
 * Ministry fields meta box.
 *
 * @param WP_Post $post Post.
 */
function krm_ministry_box( $post ) {
	wp_nonce_field( 'krm_ministry_save', 'krm_ministry_nonce' );
	$focus    = get_post_meta( $post->ID, '_krm_focus', true );
	$points   = get_post_meta( $post->ID, '_krm_points', true );
	$gallery  = (array) get_post_meta( $post->ID, '_krm_gallery', true );
	$explorer = get_post_meta( $post->ID, '_krm_explorer_image', true );
	$order    = get_post_meta( $post->ID, '_krm_home_order', true );
	$card     = krm_ministry_card_values( $post );
	$data     = krm_ministry_data();
	$drafts   = isset( $data['items'][ $post->post_name ]['drafts'] ) ? $data['items'][ $post->post_name ]['drafts'] : array();
	$names    = array(
		'excerpt'  => __( 'Excerpt (short description)', 'kingdom-revival' ),
		'summary'  => __( 'Card text', 'kingdom-revival' ),
		'sections' => __( 'Extra sections', 'kingdom-revival' ),
	);
	if ( $drafts ) :
		?>
	<div class="notice notice-warning inline" style="margin: 8px 0 12px"><p><strong><?php esc_html_e( 'DRAFT — CLIENT TO APPROVE', 'kingdom-revival' ); ?></strong> — <?php echo esc_html( sprintf( /* translators: %s: field names */ __( 'proposed wording, written from text already on the site, in: %s. Replace or confirm it once the client has signed it off.', 'kingdom-revival' ), implode( ', ', array_intersect_key( $names, array_flip( $drafts ) ) ) ) ); ?></p></div>
		<?php
	endif;
	?>
	<p class="description"><?php esc_html_e( 'The short description (shown in the page hero and on the home page) is the Excerpt field below the editor.', 'kingdom-revival' ); ?></p>
	<p><label for="krm-focus"><strong><?php esc_html_e( 'Ministry focus', 'kingdom-revival' ); ?></strong></label>
		<textarea class="widefat" rows="2" id="krm-focus" name="krm_focus"><?php echo esc_textarea( $focus ); ?></textarea></p>
	<p><label for="krm-points"><strong><?php esc_html_e( 'How this ministry serves (one point per line)', 'kingdom-revival' ); ?></strong></label>
		<textarea class="widefat" rows="4" id="krm-points" name="krm_points"><?php echo esc_textarea( $points ); ?></textarea></p>
	<p><label for="krm-gifts"><strong><?php esc_html_e( 'How your gift helps (amount cards)', 'kingdom-revival' ); ?></strong></label>
		<textarea class="widefat" rows="4" id="krm-gifts" name="krm_gifts"><?php echo esc_textarea( $card['gifts'] ); ?></textarea>
		<span class="description"><?php esc_html_e( 'One card per line: amount | what it gives | short text (e.g. “$10 | One Urdu Bible | Scripture for a family or new believer.”). Each card opens Donate with this ministry and amount chosen. Up to 4 are shown. Visitors see only cards with an amount (none: no section); administrators see “[$ ]” placeholder cards up to 3.', 'kingdom-revival' ); ?></span></p>
	<p><label for="krm-camps"><strong><?php esc_html_e( 'Camps / projects completed (optional)', 'kingdom-revival' ); ?></strong></label>
		<textarea class="widefat" rows="3" id="krm-camps" name="krm_camps"><?php echo esc_textarea( $card['camps'] ); ?></textarea>
		<span class="description"><?php esc_html_e( 'One camp or project per line: date | location | people served (e.g. “March 2027 | Jhang | 250”). Shown as a timeline on the ministry page; left empty, the timeline is not shown.', 'kingdom-revival' ); ?></span></p>
	<p><label for="krm-churches"><strong><?php esc_html_e( 'Churches & house churches planted (optional)', 'kingdom-revival' ); ?></strong></label>
		<textarea class="widefat" rows="3" id="krm-churches" name="krm_churches"><?php echo esc_textarea( $card['churches'] ); ?></textarea>
		<span class="description"><?php esc_html_e( 'One church per line: name | village or district | year planted or type (e.g. “House church | Jhang | 2025”). Shown as a list on the ministry page; left empty, the list is not shown.', 'kingdom-revival' ); ?></span></p>
	<p><label for="krm-sections"><strong><?php esc_html_e( 'Extra sections (optional)', 'kingdom-revival' ); ?></strong></label>
		<textarea class="widefat" rows="5" id="krm-sections" name="krm_sections"><?php echo esc_textarea( $card['sections'] ); ?></textarea>
		<span class="description"><?php esc_html_e( 'Each section starts with a line “## Heading”; then its text, and its points on lines starting with “- ”. Shown as panels under “How this ministry serves”.', 'kingdom-revival' ); ?></span></p>

	<p><strong><?php esc_html_e( 'Ministry photos', 'kingdom-revival' ); ?></strong><br>
		<span class="description"><?php esc_html_e( 'The first photo is also the page’s hero background. Leave empty to show “No photos are available for this ministry yet.”', 'kingdom-revival' ); ?></span></p>
	<div class="krm-gallery" data-krm-gallery>
		<ul class="krm-gallery-list">
			<?php
			foreach ( $gallery as $ref ) :
				if ( '' === $ref || null === $ref ) {
					continue;
				}
				?>
				<li data-ref="<?php echo esc_attr( $ref ); ?>"><img src="<?php echo esc_url( krm_ref_url( $ref, 'thumbnail' ) ); ?>" alt=""><button type="button" class="krm-remove" aria-label="<?php esc_attr_e( 'Remove photo', 'kingdom-revival' ); ?>"><span class="dashicons dashicons-no-alt" aria-hidden="true"></span></button></li>
			<?php endforeach; ?>
		</ul>
		<input type="hidden" name="krm_gallery" value="<?php echo esc_attr( implode( "\n", array_filter( $gallery ) ) ); ?>">
		<button type="button" class="button krm-add"><?php esc_html_e( 'Add photos', 'kingdom-revival' ); ?></button>
		<span class="description"><?php esc_html_e( 'Drag photos to reorder.', 'kingdom-revival' ); ?></span>
	</div>

	<hr>
	<p><strong><?php esc_html_e( 'What We Do card', 'kingdom-revival' ); ?></strong></p>
	<p><label for="krm-tag"><?php esc_html_e( 'Label on the photo', 'kingdom-revival' ); ?></label>
		<input type="text" class="regular-text" id="krm-tag" name="krm_tag" value="<?php echo esc_attr( $card['tag'] ); ?>"></p>
	<p><label for="krm-summary"><?php esc_html_e( 'Card text (empty: the Excerpt)', 'kingdom-revival' ); ?></label>
		<textarea class="widefat" rows="2" id="krm-summary" name="krm_summary"><?php echo esc_textarea( $card['summary'] ); ?></textarea></p>
	<p><label for="krm-raised"><?php esc_html_e( 'Raised ($, whole dollars)', 'kingdom-revival' ); ?></label>
		<input type="number" min="0" step="1" class="small-text" style="width:110px" id="krm-raised" name="krm_raised" value="<?php echo esc_attr( $card['raised'] ); ?>">
		<label for="krm-goal" style="margin-left:16px"><?php esc_html_e( 'Goal ($)', 'kingdom-revival' ); ?></label>
		<input type="number" min="0" step="1" class="small-text" style="width:110px" id="krm-goal" name="krm_goal" value="<?php echo esc_attr( $card['goal'] ); ?>">
		<br><span class="description"><?php esc_html_e( 'Not shown on the site (the raised / goal progress bar was removed in 1.15.9); kept for your own records.', 'kingdom-revival' ); ?></span></p>
	<p><label for="krm-stats"><?php esc_html_e( 'Impact stats (one per line: value | label, e.g. “1,200 | Bibles placed”; 3–4 are shown as counters on the ministry page and on Our Impact)', 'kingdom-revival' ); ?></label>
		<textarea class="widefat" rows="3" id="krm-stats" name="krm_stats"><?php echo esc_textarea( $card['stats'] ); ?></textarea></p>

	<hr>
	<p><strong><?php esc_html_e( 'Home page explorer', 'kingdom-revival' ); ?></strong></p>
	<div class="krm-single-image" data-krm-image>
		<img src="<?php echo esc_url( $explorer ? krm_ref_url( $explorer, 'medium' ) : '' ); ?>" alt="" style="max-width:220px;<?php echo $explorer ? '' : 'display:none'; ?>">
		<input type="hidden" name="krm_explorer_image" value="<?php echo esc_attr( $explorer ); ?>">
		<p><button type="button" class="button krm-pick"><?php esc_html_e( 'Choose explorer image', 'kingdom-revival' ); ?></button>
			<button type="button" class="button-link krm-clear"><?php esc_html_e( 'Remove', 'kingdom-revival' ); ?></button></p>
	</div>
	<p><label for="krm-home-order"><?php esc_html_e( 'Position in the home page explorer (1 = first, 0 = not shown there)', 'kingdom-revival' ); ?></label>
		<input type="number" min="0" class="small-text" id="krm-home-order" name="krm_home_order" value="<?php echo esc_attr( $order ); ?>"></p>
	<p class="description"><?php esc_html_e( 'Order in menus and the donation/partner forms uses the “Order” field in the Page Attributes box.', 'kingdom-revival' ); ?></p>
	<?php
}

add_action(
	'save_post_ministry',
	function ( $post_id ) {
		if ( ! isset( $_POST['krm_ministry_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['krm_ministry_nonce'] ), 'krm_ministry_save' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		update_post_meta( $post_id, '_krm_focus', sanitize_textarea_field( wp_unslash( $_POST['krm_focus'] ?? '' ) ) );
		update_post_meta( $post_id, '_krm_points', sanitize_textarea_field( wp_unslash( $_POST['krm_points'] ?? '' ) ) );
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- krm_clean_refs() keeps only attachment IDs and http(s) URLs.
		update_post_meta( $post_id, '_krm_gallery', krm_clean_refs( explode( "\n", wp_unslash( $_POST['krm_gallery'] ?? '' ) ) ) );
		$explorer = krm_clean_refs( array( wp_unslash( $_POST['krm_explorer_image'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- see above.
		update_post_meta( $post_id, '_krm_explorer_image', $explorer ? $explorer[0] : '' );
		$order = trim( sanitize_text_field( wp_unslash( $_POST['krm_home_order'] ?? '' ) ) );
		update_post_meta( $post_id, '_krm_home_order', '' === $order ? '' : absint( $order ) );
		update_post_meta( $post_id, '_krm_tag', sanitize_text_field( wp_unslash( $_POST['krm_tag'] ?? '' ) ) );
		update_post_meta( $post_id, '_krm_summary', sanitize_textarea_field( wp_unslash( $_POST['krm_summary'] ?? '' ) ) );
		foreach ( array( 'raised', 'goal' ) as $key ) {
			$amount = preg_replace( '/[^0-9]/', '', sanitize_text_field( wp_unslash( $_POST[ 'krm_' . $key ] ?? '' ) ) );
			update_post_meta( $post_id, '_krm_' . $key, $amount );
		}
		update_post_meta( $post_id, '_krm_stats', sanitize_textarea_field( wp_unslash( $_POST['krm_stats'] ?? '' ) ) );
		update_post_meta( $post_id, '_krm_sections', sanitize_textarea_field( wp_unslash( $_POST['krm_sections'] ?? '' ) ) );
		update_post_meta( $post_id, '_krm_camps', sanitize_textarea_field( wp_unslash( $_POST['krm_camps'] ?? '' ) ) );
		update_post_meta( $post_id, '_krm_churches', sanitize_textarea_field( wp_unslash( $_POST['krm_churches'] ?? '' ) ) );
		update_post_meta( $post_id, '_krm_gifts', sanitize_textarea_field( wp_unslash( $_POST['krm_gifts'] ?? '' ) ) );
	}
);

/**
 * Keep only attachment IDs and http(s) URLs.
 *
 * @param array $refs Raw references.
 * @return array
 */
function krm_clean_refs( $refs ) {
	$out = array();
	foreach ( $refs as $ref ) {
		$ref = trim( (string) $ref );
		if ( ctype_digit( $ref ) && (int) $ref > 0 ) {
			$out[] = (int) $ref;
		} elseif ( preg_match( '#^https?://#', $ref ) ) {
			$out[] = esc_url_raw( $ref );
		} elseif ( preg_match( '#^krm-theme:images/[a-z0-9/_.-]+$#i', $ref ) && false === strpos( $ref, '..' ) ) {
			$out[] = $ref; // A photo shipped with the theme that Setup has not copied to the Media Library yet.
		}
	}
	return $out;
}

add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		$screen = get_current_screen();
		if ( ! $screen || 'ministry' !== $screen->post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}
		wp_enqueue_media();
		wp_enqueue_script( 'krm-admin', krm_asset( 'js/admin.js' ), array( 'jquery', 'jquery-ui-sortable' ), KRM_VERSION, true );
		wp_add_inline_style( 'wp-admin', '.krm-gallery-list{display:flex;flex-wrap:wrap;gap:8px;margin:8px 0}.krm-gallery-list li{position:relative;margin:0;cursor:move}.krm-gallery-list img{display:block;width:96px;height:96px;object-fit:cover;border-radius:4px}.krm-remove{position:absolute;top:2px;right:2px;width:22px;height:22px;border:0;border-radius:50%;background:#b32d2e;color:#fff;cursor:pointer;line-height:1;display:flex;align-items:center;justify-content:center;padding:0}.krm-remove .dashicons{width:16px;height:16px;font-size:16px}' );
	}
);

/* Ministry list: show the explorer position and order. */
add_filter(
	'manage_ministry_posts_columns',
	function ( $cols ) {
		$cols['krm_order'] = __( 'Order (menu / home)', 'kingdom-revival' );
		return $cols;
	}
);
add_action(
	'manage_ministry_posts_custom_column',
	function ( $col, $post_id ) {
		if ( 'krm_order' === $col ) {
			echo esc_html( get_post_field( 'menu_order', $post_id ) . ' / ' . get_post_meta( $post_id, '_krm_home_order', true ) );
		}
	},
	10,
	2
);
add_action(
	'pre_get_posts',
	function ( $q ) {
		if ( is_admin() && $q->is_main_query() && 'ministry' === $q->get( 'post_type' ) && ! $q->get( 'orderby' ) ) {
			$q->set( 'orderby', 'menu_order' );
			$q->set( 'order', 'ASC' );
		}
	}
);

/**
 * The card fields as the editor shows them: the post's saved value, else the data file's default.
 *
 * @param WP_Post $post Ministry.
 * @return array{tag:string,summary:string,raised:string,goal:string,stats:string,sections:string,camps:string,churches:string,gifts:string}
 */
function krm_ministry_card_values( $post ) {
	$data = krm_ministry_data();
	$d    = isset( $data['items'][ $post->post_name ] ) ? $data['items'][ $post->post_name ] : array();
	$out  = array();
	foreach ( array( 'tag', 'summary', 'raised', 'goal', 'stats', 'sections', 'camps', 'churches', 'gifts' ) as $key ) {
		if ( metadata_exists( 'post', $post->ID, '_krm_' . $key ) ) {
			$value = get_post_meta( $post->ID, '_krm_' . $key, true );
		} else {
			$value = isset( $d[ $key ] ) ? $d[ $key ] : '';
		}
		if ( 'sections' === $key && is_array( $value ) ) {
			$value = krm_sections_text( $value );
		} elseif ( is_array( $value ) ) {
			$value = implode(
				"\n",
				array_map(
					function ( $pair ) {
						return implode( ' | ', (array) $pair );
					},
					$value
				)
			);
		}
		$out[ $key ] = (string) $value;
	}
	if ( $out['summary'] === $post->post_excerpt ) {
		$out['summary'] = ''; // Same as the Excerpt: show it empty so editing the Excerpt keeps updating the card.
	}
	return $out;
}

/**
 * The ministry being viewed, as an item of krm_ministries() (a draft opened in preview is built from its post and
 * the data file, as krm_ministries() would).
 *
 * @return array
 */
function krm_current_ministry() {
	$id = get_the_ID();
	foreach ( krm_ministries() as $m ) {
		if ( (int) $m['id'] === $id ) {
			return $m;
		}
	}
	$post = get_post( $id );
	$data = krm_ministry_data();
	$d    = isset( $data['items'][ $post->post_name ] ) ? $data['items'][ $post->post_name ] : array();
	$card = krm_ministry_card_values( $post );
	$grp  = isset( $d['group'], $data['groups'][ $d['group'] ] ) ? $data['groups'][ $d['group'] ] : array();
	return array(
		'id'          => $post->ID,
		'slug'        => $post->post_name,
		'group'       => isset( $grp['name'] ) ? $grp['name'] : '',
		'group_short' => isset( $grp['short'] ) ? $grp['short'] : '',
		'group_slug'  => isset( $d['group'] ) ? $d['group'] : '',
		'title'       => wp_specialchars_decode( get_the_title( $post ), ENT_QUOTES ),
		'desc'        => $post->post_excerpt,
		'summary'     => '' !== $card['summary'] ? $card['summary'] : $post->post_excerpt,
		'tag'         => $card['tag'],
		'raised'      => $card['raised'],
		'goal'        => $card['goal'],
		'stats'       => krm_parse_stats( $card['stats'] ),
		'sections'    => krm_parse_sections( $card['sections'] ),
		'camps'       => krm_parse_rows( $card['camps'], 3 ),
		'churches'    => krm_parse_rows( $card['churches'], 3 ),
		'gifts'       => krm_parse_rows( $card['gifts'], 3 ),
		'layout'      => isset( $d['layout'] ) ? $d['layout'] : '',
		'url'         => get_permalink( $post ),
		'image'       => get_post_meta( $post->ID, '_krm_explorer_image', true ),
		'menu_order'  => (int) $post->menu_order,
		'home_order'  => 0,
	);
}

/**
 * Rows of a ministry list field with every cell empty left out.
 *
 * @param array $rows Rows.
 * @return array[]
 */
function krm_filled_rows( $rows ) {
	return array_values(
		array_filter(
			(array) $rows,
			function ( $row ) {
				return '' !== trim( implode( '', (array) $row ) );
			}
		)
	);
}

/**
 * "How your gift helps" cards of a ministry: up to 4. Visitors get only cards with a real amount (none = the section
 * is not shown); administrators get them all. Each: amount, title, text, url
 * (Donate with the ministry, and the amount when it is a number), real (false for a placeholder card).
 *
 * @param array $m A ministry from krm_ministries().
 * @return array[]
 */
function krm_ministry_gifts( $m ) {
	$rows = array_slice( krm_visible( krm_filled_rows( $m['gifts'] ), 0 ), 0, 4 );
	$out = array();
	foreach ( $rows as $row ) {
		$row    = array_pad( array_values( (array) $row ), 3, '' );
		$amount = preg_replace( '/[^0-9.]/', '', (string) $row[0] );
		$args   = array();
		if ( '' !== $amount && (float) $amount > 0 ) {
			$args['amount'] = rawurlencode( $amount );
		}
		$out[] = array(
			'amount' => (string) $row[0],
			'title'  => (string) $row[1],
			'text'   => (string) $row[2],
			'url'    => krm_ministry_donate_url( $m['slug'], $args ),
			'real'   => ! krm_is_placeholder( $row[0] ),
		);
	}
	return $out;
}

/**
 * Impact counters of a ministry: up to 4 [ value, label ] pairs. Visitors get only those with a real value (none =
 * the strip is not shown); administrators get them all, or three "[00]" placeholders when none are set.
 *
 * @param array $m A ministry from krm_ministries().
 * @return array[]
 */
function krm_ministry_counters( $m ) {
	$stats = array_slice( krm_visible( krm_filled_rows( $m['stats'] ), 0 ), 0, 4 );
	if ( ! $stats && krm_show_placeholders() ) {
		$stats = array_fill( 0, 3, array( '[00]', '[Impact number — to be supplied by the client]' ) );
	}
	return $stats;
}

/**
 * Three other published ministries of the same group, the ones after this one in menu order (wrapping round).
 * A group with fewer than three others is filled up from the other group.
 *
 * @param array $m A ministry from krm_ministries().
 * @return array[]
 */
function krm_related_ministries( $m ) {
	$all    = krm_ministries();
	$same   = array();
	$others = array();
	$after  = array();
	foreach ( $all as $item ) {
		if ( (int) $item['id'] === (int) $m['id'] && $item['slug'] === $m['slug'] ) {
			continue;
		}
		if ( $item['group_slug'] === $m['group_slug'] ) {
			if ( $item['menu_order'] > $m['menu_order'] ) {
				$after[] = $item;
			} else {
				$same[] = $item;
			}
		} else {
			$others[] = $item;
		}
	}
	return array_slice( array_merge( $after, $same, $others ), 0, 3 );
}

/**
 * The ministry ID a Testimonial is linked to (its "Related ministry" holds a slug, older entries an ID), or 0.
 *
 * @param int $post_id Testimonial.
 * @return int
 */
function krm_testimonial_ministry_id( $post_id ) {
	$value = (string) get_post_meta( $post_id, '_krm_ministry', true );
	if ( '' === $value || ctype_digit( $value ) ) {
		return (int) $value;
	}
	foreach ( krm_ministries() as $m ) {
		if ( $m['slug'] === $value ) {
			return (int) $m['id'];
		}
	}
	return 0;
}

/**
 * The first published Testimonial linked to a ministry (by slug, as the editor saves it, or by ID), or null.
 *
 * @param array $m A ministry from krm_ministries().
 * @return WP_Post|null
 */
function krm_ministry_testimonial( $m ) {
	if ( ! $m['id'] ) {
		return null;
	}
	$posts = get_posts(
		array(
			'post_type'      => 'testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_krm_ministry',
					'value'   => array( (string) $m['id'], $m['slug'] ), // The editor saves the slug; older entries an ID.
					'compare' => 'IN',
				),
			),
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'no_found_rows'  => true,
		)
	);
	return $posts ? $posts[0] : null;
}

/**
 * The ministry's latest published story (News & Updates post whose "Related ministry" is this ministry), or null.
 *
 * @param array $m Ministry (krm_current_ministry()).
 * @return WP_Post|null
 */
function krm_ministry_story_post( $m ) {
	if ( empty( $m['id'] ) ) {
		return null;
	}
	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => '_krm_ministry', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => (int) $m['id'], // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'no_found_rows'  => true,
		)
	);
	return $posts ? $posts[0] : null;
}

/**
 * The opening of a story in its own words: its first paragraphs of text (no images or headings), about $words long.
 *
 * @param WP_Post $post  Story.
 * @param int     $words Rough length.
 * @return string[] Plain-text paragraphs.
 */
function krm_story_opening( $post, $words = 90 ) {
	$html  = strip_shortcodes( (string) $post->post_content );
	$paras = array();
	if ( preg_match_all( '#<p[^>]*>(.*?)</p>#is', $html, $mm ) ) {
		$paras = $mm[1];
	} else {
		$paras = preg_split( "/\n\s*\n/", $html );
	}
	$out   = array();
	$count = 0;
	foreach ( $paras as $p ) {
		$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $p ) ) );
		if ( '' === $text ) {
			continue;
		}
		$n = str_word_count( $text );
		if ( $count && $count + $n > $words ) {
			break;
		}
		$out[]  = $count + $n > $words ? wp_trim_words( $text, $words, '…' ) : $text;
		$count += $n;
	}
	return $out;
}

/**
 * Caption for a ministry photo: its Media Library description (alt text), which says what the photo shows, else the
 * gallery caption.
 *
 * @param int|string $ref      Attachment ID or design URL.
 * @param string     $fallback Gallery caption.
 */
function krm_photo_caption( $ref, $fallback ) {
	$id  = is_numeric( $ref ) ? (int) $ref : krm_media_id( (string) $ref );
	$alt = $id ? trim( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) ) : '';
	return '' !== $alt ? $alt : (string) $fallback;
}
