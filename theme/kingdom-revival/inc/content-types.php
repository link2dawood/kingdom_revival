<?php
/**
 * Sermons, events and testimonials: content types with their fields, list columns and ordering.
 *
 * The approved design has no pages for these, so their templates (archive-*.php, single-*.php) are built only
 * from the design's existing components (page hero, story cards, story layout, give band). Nothing links to
 * them until they are added to a menu or a page.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		$common = array(
			'public'        => true,
			'show_in_rest'  => true,
			'menu_position' => 22,
		);

		// The series taxonomy is registered first so /sermons/series/… is matched before sermon addresses.
		register_taxonomy(
			'sermon_series',
			'sermon',
			array(
				'labels'            => array(
					'name'          => __( 'Series', 'kingdom-revival' ),
					'singular_name' => __( 'Series', 'kingdom-revival' ),
					'add_new_item'  => __( 'Add New Series', 'kingdom-revival' ),
					'search_items'  => __( 'Search Series', 'kingdom-revival' ),
				),
				'hierarchical'      => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => array(
					'slug'       => 'sermons/series',
					'with_front' => false,
				),
			)
		);

		register_post_type(
			'sermon',
			$common + array(
				'labels'      => krm_type_labels( __( 'Sermon', 'kingdom-revival' ), __( 'Sermons', 'kingdom-revival' ) ),
				'menu_icon'   => 'dashicons-microphone',
				'has_archive' => 'sermons',
				'rewrite'     => array(
					'slug'       => 'sermons',
					'with_front' => false,
				),
				'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
				'description' => __( 'Sermons and teaching: video or audio, speaker, Bible passage and notes.', 'kingdom-revival' ),
			)
		);

		register_post_type(
			'event',
			$common + array(
				'labels'      => krm_type_labels( __( 'Event', 'kingdom-revival' ), __( 'Events', 'kingdom-revival' ) ),
				'menu_icon'   => 'dashicons-calendar-alt',
				'has_archive' => 'events',
				'rewrite'     => array(
					'slug'       => 'events',
					'with_front' => false,
				),
				'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			)
		);

		register_post_type(
			'testimonial',
			$common + array(
				'labels'      => krm_type_labels( __( 'Testimonial', 'kingdom-revival' ), __( 'Testimonials', 'kingdom-revival' ) ),
				'menu_icon'   => 'dashicons-format-quote',
				'has_archive' => 'testimonials',
				'rewrite'     => array(
					'slug'       => 'testimonials',
					'with_front' => false,
				),
				'supports'    => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			)
		);
	}
);

/* Flush rewrite rules once after the theme's content types change (theme activation or update). */
add_action(
	'init',
	function () {
		if ( get_option( 'krm_rewrite_version' ) !== KRM_VERSION ) {
			flush_rewrite_rules( false );
			update_option( 'krm_rewrite_version', KRM_VERSION );
		}
	},
	100
);

/**
 * Admin labels for a content type.
 *
 * @param string $one  Singular name.
 * @param string $many Plural name.
 */
function krm_type_labels( $one, $many ) {
	return array(
		'name'               => $many,
		'singular_name'      => $one,
		/* translators: %s: content type, e.g. Sermon */
		'add_new_item'       => sprintf( __( 'Add New %s', 'kingdom-revival' ), $one ),
		/* translators: %s: content type, e.g. Sermon */
		'edit_item'          => sprintf( __( 'Edit %s', 'kingdom-revival' ), $one ),
		/* translators: %s: content type, e.g. Sermon */
		'new_item'           => sprintf( __( 'New %s', 'kingdom-revival' ), $one ),
		/* translators: %s: content type, e.g. Sermon */
		'view_item'          => sprintf( __( 'View %s', 'kingdom-revival' ), $one ),
		/* translators: %s: content type, e.g. Sermons */
		'search_items'       => sprintf( __( 'Search %s', 'kingdom-revival' ), $many ),
		/* translators: %s: content type, e.g. sermons */
		'not_found'          => sprintf( __( 'No %s found.', 'kingdom-revival' ), strtolower( $many ) ),
		/* translators: %s: content type, e.g. sermons */
		'not_found_in_trash' => sprintf( __( 'No %s found in Trash.', 'kingdom-revival' ), strtolower( $many ) ),
		'all_items'          => $many,
		'archives'           => $many,
	);
}

/* ---------- Fields ---------- */

/**
 * Fields per content type: meta key => [ label, type (text|url|datetime|ministry), help ].
 */
function krm_type_fields() {
	return array(
		'sermon'      => array(
			'_krm_speaker'   => array( __( 'Speaker', 'kingdom-revival' ), 'text', '' ),
			'_krm_scripture' => array( __( 'Bible passage', 'kingdom-revival' ), 'text', __( 'e.g. John 3:16–21', 'kingdom-revival' ) ),
			'_krm_video'     => array( __( 'Video link', 'kingdom-revival' ), 'url', __( 'A YouTube, Vimeo or Facebook video link. The video is shown on the sermon page.', 'kingdom-revival' ) ),
			'_krm_audio'     => array( __( 'Audio file link', 'kingdom-revival' ), 'url', __( 'An MP3 uploaded to the Media Library (Media → Add New, then copy its link).', 'kingdom-revival' ) ),
		),
		'event'       => array(
			'_krm_event_start'   => array( __( 'Starts', 'kingdom-revival' ), 'datetime', '' ),
			'_krm_event_end'     => array( __( 'Ends', 'kingdom-revival' ), 'datetime', __( 'Optional.', 'kingdom-revival' ) ),
			'_krm_event_venue'   => array( __( 'Venue', 'kingdom-revival' ), 'text', __( 'e.g. Nasrat Colony church hall', 'kingdom-revival' ) ),
			'_krm_event_address' => array( __( 'Address', 'kingdom-revival' ), 'text', '' ),
			'_krm_event_link'    => array( __( 'Registration or details link', 'kingdom-revival' ), 'url', __( 'Optional.', 'kingdom-revival' ) ),
			'_krm_event_button'  => array( __( 'Link button text', 'kingdom-revival' ), 'text', __( 'Default: “Register”.', 'kingdom-revival' ) ),
		),
		'testimonial' => array(
			'_krm_role'     => array( __( 'Role or place', 'kingdom-revival' ), 'text', __( 'e.g. Monthly partner, Canada', 'kingdom-revival' ) ),
			'_krm_ministry' => array( __( 'Related ministry', 'kingdom-revival' ), 'ministry', __( 'Optional. Lets [krm_testimonials ministry="…"] show only these.', 'kingdom-revival' ) ),
		),
	);
}

add_action(
	'add_meta_boxes',
	function ( $post_type ) {
		$titles = array(
			'sermon'      => __( 'Sermon details', 'kingdom-revival' ),
			'event'       => __( 'Event details', 'kingdom-revival' ),
			'testimonial' => __( 'Testimonial details', 'kingdom-revival' ),
		);
		if ( isset( $titles[ $post_type ] ) ) {
			add_meta_box( 'krm-type-fields', $titles[ $post_type ], 'krm_type_fields_box', $post_type, 'normal', 'high' );
		}
	}
);

/**
 * Details meta box for sermons, events and testimonials.
 *
 * @param WP_Post $post Post.
 */
function krm_type_fields_box( $post ) {
	wp_nonce_field( 'krm_type_fields', 'krm_type_fields_nonce' );
	$notes = array(
		'sermon'      => __( 'Write the sermon notes or transcript in the editor, a short summary in the Excerpt and choose a Featured image for the sermon list. The date preached is the publish date.', 'kingdom-revival' ),
		'event'       => __( 'Describe the event in the editor, add a short summary in the Excerpt and a Featured image. Events are listed by start date; past events move to “Past events”.', 'kingdom-revival' ),
		'testimonial' => __( 'The title is the person’s name and the editor holds their words. The Featured image is their photo (optional).', 'kingdom-revival' ),
	);
	echo '<p class="description">' . esc_html( $notes[ $post->post_type ] ) . '</p><table class="form-table" role="presentation">';
	foreach ( krm_type_fields()[ $post->post_type ] as $key => $field ) {
		list( $label, $type, $help ) = $field;
		$id                          = 'krm' . str_replace( '_', '-', $key );
		$value                       = (string) get_post_meta( $post->ID, $key, true );
		echo '<tr><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label></th><td>';
		if ( 'ministry' === $type ) {
			echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '"><option value="">' . esc_html__( '— None —', 'kingdom-revival' ) . '</option>';
			foreach ( krm_ministries() as $m ) {
				echo '<option value="' . esc_attr( $m['slug'] ) . '"' . selected( $value, $m['slug'], false ) . '>' . esc_html( $m['title'] ) . '</option>';
			}
			echo '</select>';
		} else {
			$input = array(
				'datetime' => 'datetime-local',
				'url'      => 'url',
			);
			echo '<input class="regular-text" type="' . esc_attr( isset( $input[ $type ] ) ? $input[ $type ] : 'text' ) . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( 'datetime' === $type ? str_replace( ' ', 'T', $value ) : $value ) . '">';
		}
		if ( $help ) {
			echo '<p class="description">' . esc_html( $help ) . '</p>';
		}
		echo '</td></tr>';
	}
	echo '</table>';
}

add_action(
	'save_post',
	function ( $post_id, $post ) {
		if ( ! isset( krm_type_fields()[ $post->post_type ] ) || ! isset( $_POST['krm_type_fields_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['krm_type_fields_nonce'] ), 'krm_type_fields' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		foreach ( krm_type_fields()[ $post->post_type ] as $key => $field ) {
			$raw = isset( $_POST[ $key ] ) ? trim( sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) ) : '';
			switch ( $field[1] ) {
				case 'url':
					$value = esc_url_raw( $raw, array( 'http', 'https' ) );
					break;
				case 'datetime':
					// Stored as "Y-m-d H:i" in the site's time zone, so it sorts as text.
					$value = preg_match( '/^(\d{4}-\d{2}-\d{2})[T ](\d{2}:\d{2})/', $raw, $m ) ? $m[1] . ' ' . $m[2] : '';
					break;
				case 'ministry':
					$value = sanitize_title( $raw );
					break;
				default:
					$value = $raw;
			}
			if ( '' === $value ) {
				delete_post_meta( $post_id, $key );
			} else {
				update_post_meta( $post_id, $key, $value );
			}
		}
	},
	10,
	2
);

/* ---------- Ordering ---------- */

/**
 * Current local time in the format event dates are stored in.
 */
function krm_now() {
	return wp_date( 'Y-m-d H:i' );
}

add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}
		if ( $query->is_post_type_archive( 'event' ) ) {
			// Upcoming and ongoing events, soonest first; the template lists past events below.
			$query->set( 'meta_key', '_krm_event_start' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'order', 'ASC' );
			$query->set(
				'meta_query',
				array(
					'relation' => 'OR',
					array(
						'key'     => '_krm_event_start',
						'value'   => krm_now(),
						'compare' => '>=',
					),
					array(
						'key'     => '_krm_event_end',
						'value'   => krm_now(),
						'compare' => '>=',
					),
				)
			);
		}
		if ( $query->is_post_type_archive( 'testimonial' ) ) {
			$query->set(
				'orderby',
				array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				)
			);
			$query->set( 'posts_per_page', 24 );
		}
	}
);

/* Testimonials are shown in lists only; a single testimonial address leads to the list. */
add_action(
	'template_redirect',
	function () {
		if ( is_singular( 'testimonial' ) ) {
			wp_safe_redirect( get_post_type_archive_link( 'testimonial' ), 301 );
			exit;
		}
	}
);

/* ---------- Admin list columns ---------- */

add_filter(
	'manage_event_posts_columns',
	function ( $cols ) {
		return array_slice( $cols, 0, 2, true ) + array( 'krm_when' => __( 'Starts', 'kingdom-revival' ) ) + array_slice( $cols, 2, null, true );
	}
);
add_action(
	'manage_event_posts_custom_column',
	function ( $col, $post_id ) {
		if ( 'krm_when' === $col ) {
			echo esc_html( krm_event_when( $post_id ) );
		}
	},
	10,
	2
);
add_filter(
	'manage_sermon_posts_columns',
	function ( $cols ) {
		return array_slice( $cols, 0, 2, true ) + array( 'krm_speaker' => __( 'Speaker', 'kingdom-revival' ) ) + array_slice( $cols, 2, null, true );
	}
);
add_action(
	'manage_sermon_posts_custom_column',
	function ( $col, $post_id ) {
		if ( 'krm_speaker' === $col ) {
			echo esc_html( get_post_meta( $post_id, '_krm_speaker', true ) );
		}
	},
	10,
	2
);

/* ---------- Template helpers ---------- */

/**
 * Human date and time of an event, e.g. "Saturday, March 14, 2027 · 10:00 am – 1:00 pm".
 *
 * @param int $post_id Event ID.
 */
function krm_event_when( $post_id ) {
	$start = (string) get_post_meta( $post_id, '_krm_event_start', true );
	$end   = (string) get_post_meta( $post_id, '_krm_event_end', true );
	if ( '' === $start ) {
		return '';
	}
	$tz = wp_timezone();
	$s  = date_create_immutable( $start, $tz );
	if ( ! $s ) {
		return '';
	}
	$out = wp_date( 'l, F j, Y · ' . get_option( 'time_format' ), $s->getTimestamp() );
	$e   = $end ? date_create_immutable( $end, $tz ) : false;
	if ( $e ) {
		$out .= ' – ' . ( $e->format( 'Y-m-d' ) === $s->format( 'Y-m-d' ) ? wp_date( get_option( 'time_format' ), $e->getTimestamp() ) : wp_date( 'F j, Y · ' . get_option( 'time_format' ), $e->getTimestamp() ) );
	}
	return $out;
}

/**
 * Has an event finished?
 *
 * @param int $post_id Event ID.
 */
function krm_event_is_past( $post_id ) {
	$start = (string) get_post_meta( $post_id, '_krm_event_start', true );
	$end   = (string) get_post_meta( $post_id, '_krm_event_end', true );
	$last  = $end ? $end : $start;
	return '' !== $last && $last < krm_now();
}

/**
 * A card in the design's story-card style for a sermon or event.
 *
 * @param WP_Post $post Post.
 */
function krm_type_card( $post ) {
	$image = get_post_thumbnail_id( $post );
	$image = $image ? $image : krm_c( 'types.image', 'site' );
	$meta  = array();
	if ( 'event' === $post->post_type ) {
		$tag    = krm_event_is_past( $post->ID ) ? krm_c( 'types.events_past_tag', 'site' ) : krm_c( 'types.events_tag', 'site' );
		$meta[] = krm_icon( 'cal' ) . esc_html( krm_event_when( $post->ID ) );
		$venue  = (string) get_post_meta( $post->ID, '_krm_event_venue', true );
		$more   = krm_c( 'types.events_more', 'site' );
	} else {
		$series = get_the_terms( $post, 'sermon_series' );
		$tag    = $series && ! is_wp_error( $series ) ? $series[0]->name : krm_c( 'types.sermons_tag', 'site' );
		$meta[] = krm_icon( 'cal' ) . esc_html( get_the_date( 'F j, Y', $post ) );
		$venue  = (string) get_post_meta( $post->ID, '_krm_speaker', true );
		$more   = krm_c( 'types.sermons_more', 'site' );
	}
	if ( $venue ) {
		$meta[] = esc_html( $venue );
	}
	?>
	<a class="post-card" href="<?php the_permalink( $post ); ?>"><div class="post-card-media"><img <?php krm_img_src( $image, '(max-width: 760px) 100vw, 400px' ); ?> alt="" loading="lazy" decoding="async">
	<?php
	if ( $tag ) :
		?>
		<span class="tag"><?php echo krm_text( $tag ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php endif; ?></div><div class="post-card-body"><div class="post-meta">
		<?php
		foreach ( $meta as $item ) :
			?>
		<span><?php echo $item; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></span><?php endforeach; ?></div><h3><?php echo esc_html( get_the_title( $post ) ); ?></h3><p><?php echo esc_html( get_the_excerpt( $post ) ); ?></p><span class="read-more"><?php echo krm_text( $more ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo krm_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></div></a>
	<?php
}

/**
 * Testimonial cards.
 *
 * @param WP_Post[] $posts Testimonials.
 */
function krm_testimonial_cards( $posts ) {
	echo '<div class="testimonial-grid">';
	foreach ( $posts as $post ) {
		$photo = get_post_thumbnail_id( $post );
		$role  = (string) get_post_meta( $post->ID, '_krm_role', true );
		echo '<figure class="testimonial"><blockquote>' . wp_kses_post( wpautop( $post->post_content ) ) . '</blockquote><figcaption>';
		if ( $photo ) {
			echo wp_get_attachment_image(
				$photo,
				'thumbnail',
				false,
				array(
					'alt'     => '',
					'loading' => 'lazy',
				)
			);
		}
		echo '<span><strong>' . esc_html( get_the_title( $post ) ) . '</strong>' . ( $role ? '<small>' . esc_html( $role ) . '</small>' : '' ) . '</span></figcaption></figure>';
	}
	echo '</div>';
}

/*
 * [krm_testimonials count="3" ministry="widows-ministry"] — testimonials inside a story or page.
 */
add_shortcode(
	'krm_testimonials',
	function ( $atts ) {
		$atts  = shortcode_atts(
			array(
				'count'    => 3,
				'ministry' => '',
			),
			$atts,
			'krm_testimonials'
		);
		$query = array(
			'post_type'      => 'testimonial',
			'posts_per_page' => max( 1, min( 24, absint( $atts['count'] ) ) ),
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'no_found_rows'  => true,
		);
		if ( $atts['ministry'] ) {
			$query['meta_key']   = '_krm_ministry'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			$query['meta_value'] = sanitize_title( $atts['ministry'] ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		}
		$posts = get_posts( $query );
		if ( ! $posts ) {
			return '';
		}
		wp_enqueue_style( 'krm-extras' );
		ob_start();
		krm_testimonial_cards( $posts );
		return ob_get_clean();
	}
);
