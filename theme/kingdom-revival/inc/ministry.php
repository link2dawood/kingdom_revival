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
 *  - _krm_home_order    → position in the homepage explorer (01–12).
 *  - menu_order         → order in menus, the donate and partner forms.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		register_post_type(
			'ministry',
			array(
				'labels'        => array(
					'name'          => __( 'Ministries', 'kingdom-revival' ),
					'singular_name' => __( 'Ministry', 'kingdom-revival' ),
					'add_new_item'  => __( 'Add New Ministry', 'kingdom-revival' ),
					'edit_item'     => __( 'Edit Ministry', 'kingdom-revival' ),
					'all_items'     => __( 'All Ministries', 'kingdom-revival' ),
					'search_items'  => __( 'Search Ministries', 'kingdom-revival' ),
				),
				'public'        => true,
				'has_archive'   => 'ministries',
				'rewrite'       => array(
					'slug'       => 'ministries',
					'with_front' => false,
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
	?>
	<p class="description"><?php esc_html_e( 'The short description (shown in the page hero and on the home page) is the Excerpt field below the editor.', 'kingdom-revival' ); ?></p>
	<p><label for="krm-focus"><strong><?php esc_html_e( 'Ministry focus', 'kingdom-revival' ); ?></strong></label>
		<textarea class="widefat" rows="2" id="krm-focus" name="krm_focus"><?php echo esc_textarea( $focus ); ?></textarea></p>
	<p><label for="krm-points"><strong><?php esc_html_e( 'How this ministry serves (one point per line)', 'kingdom-revival' ); ?></strong></label>
		<textarea class="widefat" rows="4" id="krm-points" name="krm_points"><?php echo esc_textarea( $points ); ?></textarea></p>

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
	<p><strong><?php esc_html_e( 'Home page explorer', 'kingdom-revival' ); ?></strong></p>
	<div class="krm-single-image" data-krm-image>
		<img src="<?php echo esc_url( $explorer ? krm_ref_url( $explorer, 'medium' ) : '' ); ?>" alt="" style="max-width:220px;<?php echo $explorer ? '' : 'display:none'; ?>">
		<input type="hidden" name="krm_explorer_image" value="<?php echo esc_attr( $explorer ); ?>">
		<p><button type="button" class="button krm-pick"><?php esc_html_e( 'Choose explorer image', 'kingdom-revival' ); ?></button>
			<button type="button" class="button-link krm-clear"><?php esc_html_e( 'Remove', 'kingdom-revival' ); ?></button></p>
	</div>
	<p><label for="krm-home-order"><?php esc_html_e( 'Position in the home page explorer (1 = first)', 'kingdom-revival' ); ?></label>
		<input type="number" min="1" class="small-text" id="krm-home-order" name="krm_home_order" value="<?php echo esc_attr( $order ); ?>"></p>
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
		$order = absint( $_POST['krm_home_order'] ?? 0 );
		update_post_meta( $post_id, '_krm_home_order', $order ? $order : '' );
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
