<?php
/**
 * Projects ("Our Projects" on Donate, and the current ones on the home page).
 *
 * Added in the dashboard under Projects: title, Excerpt (the card text), Featured image, and the "Project details"
 * box (ministry, current or upcoming, goal, raised, planned date). Until one project is published, the placeholder
 * entries of inc/data/projects.php are shown so the layout can be reviewed.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		register_post_type(
			'krm_project',
			array(
				'labels'        => array(
					'name'          => __( 'Projects', 'kingdom-revival' ),
					'singular_name' => __( 'Project', 'kingdom-revival' ),
					'add_new_item'  => __( 'Add New Project', 'kingdom-revival' ),
					'edit_item'     => __( 'Edit Project', 'kingdom-revival' ),
					'all_items'     => __( 'All Projects', 'kingdom-revival' ),
				),
				'public'        => false,
				'show_ui'       => true,
				'show_in_rest'  => true,
				'menu_icon'     => 'dashicons-portfolio',
				'menu_position' => 22,
				'supports'      => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
				'rewrite'       => false,
				'query_var'     => false,
			)
		);
	}
);

/**
 * The project detail fields: meta key => [ label, type ].
 */
function krm_project_fields() {
	return array(
		'_krm_ministry' => array( __( 'Ministry', 'kingdom-revival' ), 'ministry' ),
		'_krm_status'   => array( __( 'Status', 'kingdom-revival' ), 'status' ),
		'_krm_goal'     => array( __( 'Goal ($, whole dollars)', 'kingdom-revival' ), 'amount' ),
		'_krm_raised'   => array( __( 'Raised so far ($, current projects)', 'kingdom-revival' ), 'amount' ),
		'_krm_date'     => array( __( 'Planned date (upcoming projects, e.g. “March 2027”)', 'kingdom-revival' ), 'text' ),
	);
}

add_action(
	'add_meta_boxes_krm_project',
	function () {
		add_meta_box( 'krm-project', __( 'Project details', 'kingdom-revival' ), 'krm_project_box', 'krm_project', 'normal', 'high' );
	}
);

/**
 * Project details meta box.
 *
 * @param WP_Post $post Project.
 */
function krm_project_box( $post ) {
	wp_nonce_field( 'krm_project_save', 'krm_project_nonce' );
	echo '<p class="description">' . esc_html__( 'The Excerpt is the card text and the Featured image its photo. Current projects show a progress bar and “Give to this project”; upcoming ones their goal, planned date and “Pledge / Get notified”.', 'kingdom-revival' ) . '</p>';
	foreach ( krm_project_fields() as $key => $field ) {
		$value = (string) get_post_meta( $post->ID, $key, true );
		$id    = 'krm' . str_replace( '_', '-', $key );
		echo '<p><label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $field[0] ) . '</strong></label><br>';
		if ( 'ministry' === $field[1] ) {
			echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '"><option value="">' . esc_html__( '— None —', 'kingdom-revival' ) . '</option>';
			foreach ( krm_ministries() as $m ) {
				echo '<option value="' . esc_attr( $m['slug'] ) . '"' . selected( $value, $m['slug'], false ) . '>' . esc_html( $m['title'] ) . '</option>';
			}
			echo '</select>';
		} elseif ( 'status' === $field[1] ) {
			$value = $value ? $value : 'current';
			foreach ( array(
				'current'  => __( 'Current', 'kingdom-revival' ),
				'upcoming' => __( 'Upcoming', 'kingdom-revival' ),
			) as $opt => $label ) {
				echo '<label style="margin-right:16px"><input type="radio" name="' . esc_attr( $key ) . '" value="' . esc_attr( $opt ) . '"' . checked( $value, $opt, false ) . '> ' . esc_html( $label ) . '</label>';
			}
		} else {
			$type = 'amount' === $field[1] ? 'number" min="0" step="1' : 'text';
			echo '<input type="' . $type . '" class="regular-text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed strings.
		}
		echo '</p>';
	}
}

add_action(
	'save_post_krm_project',
	function ( $post_id ) {
		if ( ! isset( $_POST['krm_project_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['krm_project_nonce'] ), 'krm_project_save' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		foreach ( krm_project_fields() as $key => $field ) {
			$value = sanitize_text_field( wp_unslash( $_POST[ $key ] ?? '' ) );
			if ( 'amount' === $field[1] ) {
				$value = preg_replace( '/[^0-9]/', '', $value );
			} elseif ( 'status' === $field[1] ) {
				$value = 'upcoming' === $value ? 'upcoming' : 'current';
			} elseif ( 'ministry' === $field[1] ) {
				$value = sanitize_title( $value );
			}
			update_post_meta( $post_id, $key, $value );
		}
	}
);

/**
 * Projects in a normalised shape: title, text, image, status, goal, raised, date, ministry (slug), ministry_title,
 * ministry_url, placeholder (true for the data file's sample entries, which only administrators get).
 *
 * @param string $status 'current', 'upcoming' or '' for both.
 * @param int    $limit  At most this many (0 = all).
 * @return array[]
 */
function krm_projects( $status = '', $limit = 0 ) {
	static $all = null;
	if ( null === $all ) {
		$all   = array();
		$posts = get_posts(
			array(
				'post_type'      => 'krm_project',
				'post_status'    => 'publish',
				'posts_per_page' => 100,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				),
				'no_found_rows'  => true,
			)
		);
		if ( $posts ) {
			foreach ( $posts as $post ) {
				$all[] = array(
					'title'       => get_the_title( $post ),
					'text'        => $post->post_excerpt,
					'image'       => get_post_thumbnail_id( $post ),
					'status'      => 'upcoming' === get_post_meta( $post->ID, '_krm_status', true ) ? 'upcoming' : 'current',
					'goal'        => (string) get_post_meta( $post->ID, '_krm_goal', true ),
					'raised'      => (string) get_post_meta( $post->ID, '_krm_raised', true ),
					'date'        => (string) get_post_meta( $post->ID, '_krm_date', true ),
					'ministry'    => (string) get_post_meta( $post->ID, '_krm_ministry', true ),
					'placeholder' => false,
				);
			}
		} else {
			// No project published yet: everyone sees the data file's dummy projects.
			foreach ( include KRM_DIR . '/inc/data/projects.php' as $p ) {
				$p['placeholder'] = false;
				$all[]            = $p;
			}
		}
		$ministries = array();
		foreach ( krm_ministries() as $m ) {
			$ministries[ $m['slug'] ] = $m;
		}
		foreach ( $all as $i => $p ) {
			$m                         = isset( $ministries[ $p['ministry'] ] ) ? $ministries[ $p['ministry'] ] : null;
			$all[ $i ]['ministry_title'] = $m ? $m['title'] : '';
			$all[ $i ]['ministry_url']   = $m ? $m['url'] : '';
			if ( ! $p['image'] && $m ) {
				$all[ $i ]['image'] = $m['image']; // The ministry's photo until the project has its own.
			}
		}
	}
	$out = array_values(
		array_filter(
			$all,
			function ( $p ) use ( $status ) {
				return '' === $status || $p['status'] === $status;
			}
		)
	);
	return $limit ? array_slice( $out, 0, $limit ) : $out;
}
