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
	echo '<p class="description">' . esc_html__( 'The Excerpt is the card text and the Featured image its photo. Current projects show “Give to this project” (no raised / goal bar); upcoming ones their goal, planned date and “Pledge / Get notified”.', 'kingdom-revival' ) . '</p>';
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
					'page'        => $post->post_name, // Linked to /projects/{slug}/ when a detail page has that key.
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
			$page                      = isset( $p['page'] ) ? (string) $p['page'] : '';
			$all[ $i ]['page']         = krm_project_page( $page ) ? $page : '';
			$all[ $i ]['page_url']     = $all[ $i ]['page'] ? krm_project_page_url( $page ) : '';
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

/* ---------- Project detail pages (/projects/{slug}/), one template filled from inc/data/project-pages.php ---------- */

/**
 * The project base in addresses ("projects").
 */
function krm_project_base() {
	return 'projects';
}

add_action(
	'init',
	function () {
		add_rewrite_rule( '^' . krm_project_base() . '/([^/]+)/?$', 'index.php?krm_project_page=$matches[1]', 'top' );
	}
);

add_filter(
	'query_vars',
	function ( $vars ) {
		$vars[] = 'krm_project_page';
		return $vars;
	}
);

/**
 * A project detail page's data by key, or null.
 *
 * @param string $key Key in inc/data/project-pages.php ('urdu-bibles').
 * @return array|null
 */
function krm_project_page( $key ) {
	static $pages = null;
	if ( null === $pages ) {
		$pages = include KRM_DIR . '/inc/data/project-pages.php';
	}
	$key = (string) $key;
	return ( '' !== $key && isset( $pages[ $key ] ) ) ? $pages[ $key ] + array( 'key' => $key ) : null;
}

/**
 * Address of a project detail page.
 *
 * @param string $key Key.
 */
function krm_project_page_url( $key ) {
	return home_url( user_trailingslashit( krm_project_base() . '/' . $key ) );
}

/**
 * The project detail page being viewed, or null (also null for an unknown /projects/{slug}/, which is a 404).
 */
function krm_current_project_page() {
	$key = get_query_var( 'krm_project_page' );
	return $key ? krm_project_page( sanitize_title( $key ) ) : null;
}

/*
 * A /projects/{slug}/ request has no posts of its own: it is not the blog (no main posts query, not is_home), and an
 * unknown slug is a 404.
 */
add_action(
	'parse_query',
	function ( $q ) {
		if ( ! $q->is_main_query() || '' === (string) $q->get( 'krm_project_page' ) ) {
			return;
		}
		$q->is_home = false; // An unknown slug then gets WordPress's own 404 (no posts); a known one: pre_handle_404 below.
	}
);
add_filter(
	'posts_pre_query',
	function ( $posts, $q ) {
		return ( $q->is_main_query() && '' !== (string) $q->get( 'krm_project_page' ) ) ? array() : $posts;
	},
	10,
	2
);
add_filter(
	'pre_handle_404',
	function ( $handled ) {
		return krm_current_project_page() ? true : $handled; // A known project page is found, though it has no posts.
	}
);
add_filter(
	'template_include',
	function ( $template ) {
		return krm_current_project_page() ? KRM_DIR . '/single-project.php' : $template;
	}
);

/**
 * Highlighted words (<span class="hl">) on a light card or section: the site's highlighter variant (.hl--on-light),
 * since the plain orange .hl is for dark backgrounds.
 *
 * @param string $html Escaped inline HTML.
 */
function krm_hl_on_light( $html ) {
	return str_replace( 'class="hl"', 'class="hl hl--on-light"', $html );
}

/**
 * One key per image whether it is given as an attachment ID or a design URL, to spot the same photo twice.
 *
 * @param int|string $ref Image reference.
 */
function krm_project_ref_key( $ref ) {
	if ( is_numeric( $ref ) ) {
		return 'id:' . (int) $ref;
	}
	$id = krm_media_id( (string) $ref );
	return $id ? 'id:' . $id : (string) $ref;
}
