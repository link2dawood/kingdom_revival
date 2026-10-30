<?php
/**
 * Story (post) fields: the ministry it belongs to and its reading time.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		register_post_meta(
			'post',
			'_krm_ministry',
			array(
				'type'          => 'integer',
				'single'        => true,
				'auth_callback' => 'krm_can_edit_meta',
			)
		);
		register_post_meta(
			'post',
			'_krm_read_time',
			array(
				'type'          => 'integer',
				'single'        => true,
				'auth_callback' => 'krm_can_edit_meta',
			)
		);
	}
);

add_action(
	'add_meta_boxes_post',
	function () {
		add_meta_box( 'krm-story', __( 'Story details', 'kingdom-revival' ), 'krm_story_box', 'post', 'side', 'high' );
	}
);

/**
 * Story fields meta box.
 *
 * @param WP_Post $post Post.
 */
function krm_story_box( $post ) {
	wp_nonce_field( 'krm_story_save', 'krm_story_nonce' );
	$current = (int) get_post_meta( $post->ID, '_krm_ministry', true );
	$minutes = get_post_meta( $post->ID, '_krm_read_time', true );
	$list    = get_posts(
		array(
			'post_type'      => 'ministry',
			'posts_per_page' => 100,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);
	?>
	<p><label for="krm-ministry"><strong><?php esc_html_e( 'Ministry', 'kingdom-revival' ); ?></strong></label>
		<select class="widefat" id="krm-ministry" name="krm_ministry">
			<option value="0"><?php esc_html_e( '— None —', 'kingdom-revival' ); ?></option>
			<?php foreach ( $list as $m ) : ?>
				<option value="<?php echo esc_attr( $m->ID ); ?>" <?php selected( $current, $m->ID ); ?>><?php echo esc_html( get_the_title( $m ) ); ?></option>
			<?php endforeach; ?>
		</select></p>
	<p class="description"><?php esc_html_e( 'Gifts from this story go to this ministry, and the story links to it.', 'kingdom-revival' ); ?></p>
	<p><label for="krm-read-time"><strong><?php esc_html_e( 'Reading time (minutes)', 'kingdom-revival' ); ?></strong></label>
		<input type="number" min="1" class="small-text" id="krm-read-time" name="krm_read_time" value="<?php echo esc_attr( $minutes ); ?>">
		<br><span class="description"><?php esc_html_e( 'Leave empty to calculate automatically.', 'kingdom-revival' ); ?></span></p>
	<?php
}

add_action(
	'save_post_post',
	function ( $post_id ) {
		if ( ! isset( $_POST['krm_story_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['krm_story_nonce'] ), 'krm_story_save' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		update_post_meta( $post_id, '_krm_ministry', absint( $_POST['krm_ministry'] ?? 0 ) );
		$minutes = absint( $_POST['krm_read_time'] ?? 0 );
		if ( $minutes ) {
			update_post_meta( $post_id, '_krm_read_time', $minutes );
		} else {
			delete_post_meta( $post_id, '_krm_read_time' );
		}
	}
);
