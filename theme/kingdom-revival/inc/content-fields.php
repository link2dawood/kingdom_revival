<?php
/**
 * Editable design content: the text, images, buttons and lists of the design's pages, edited in the dashboard.
 *
 * Each "screen" (home, about, contact, donate, partner, blog, site) has a schema in inc/content/{screen}.php:
 * sections of fields whose defaults are the approved design's content, so a page shows the design until someone
 * edits it. Page screens are edited in a "Page content" box on the page's edit screen and stored in post meta
 * (_krm_content_{screen}); the "site" screen (footer, top bar, ministry and story page wording) is edited under
 * Kingdom Revival → Site text and stored in the krm_site_text option.
 *
 * Field types: text, textarea, html (inline formatting: em, strong, br, span, a), url, image (attachment ID or
 * design image URL) and list (repeatable items of the other types). Text and URLs accept placeholders, see
 * krm_fill() and krm_resolve_url().
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Screens with editable content: screen => label.
 */
function krm_content_screens() {
	return array(
		'home'    => __( 'Home page', 'kingdom-revival' ),
		'about'   => __( 'About page', 'kingdom-revival' ),
		'contact' => __( 'Contact page', 'kingdom-revival' ),
		'donate'  => __( 'Donate page', 'kingdom-revival' ),
		'partner' => __( 'Become a Partner page', 'kingdom-revival' ),
		'blog'    => __( 'News & Blogs page', 'kingdom-revival' ),
		'site'    => __( 'Site text', 'kingdom-revival' ),
	);
}

/**
 * Schema of a screen: section => [ label, fields => key => [ type, label, default, help?, fields? ] ].
 *
 * @param string $screen Screen.
 * @return array
 */
function krm_content_schema( $screen ) {
	static $cache = array();
	if ( ! isset( $cache[ $screen ] ) ) {
		$file             = KRM_DIR . '/inc/content/' . sanitize_key( $screen ) . '.php';
		$cache[ $screen ] = isset( krm_content_screens()[ $screen ] ) && is_readable( $file ) ? (array) include $file : array();
	}
	return $cache[ $screen ];
}

/**
 * The page whose content a screen uses on the front end (0 for the site screen or when there is none).
 *
 * @param string $screen Screen.
 */
function krm_content_post_id( $screen ) {
	if ( 'site' === $screen ) {
		return 0;
	}
	if ( 'home' === $screen ) {
		return (int) get_option( 'page_on_front' );
	}
	if ( 'blog' === $screen ) {
		return (int) get_option( 'page_for_posts' );
	}
	if ( is_page() && krm_design_template() === $screen ) {
		return get_queried_object_id();
	}
	$url  = krm_page_url( $screen );
	$page = url_to_postid( $url );
	return (int) $page;
}

/**
 * Which screen a page is edited with: the static front page, the posts page or a design page template.
 *
 * @param WP_Post|int $post Page.
 */
function krm_screen_for_post( $post ) {
	$post = get_post( $post );
	if ( ! $post || 'page' !== $post->post_type ) {
		return '';
	}
	if ( (int) get_option( 'page_on_front' ) === $post->ID && 'page' === get_option( 'show_on_front' ) ) {
		return 'home';
	}
	if ( (int) get_option( 'page_for_posts' ) === $post->ID ) {
		return 'blog';
	}
	$template = get_page_template_slug( $post );
	if ( $template && preg_match( '#^page-templates/([a-z]+)\.php$#', $template, $m ) ) {
		return in_array( $m[1], krm_design_pages(), true ) ? $m[1] : '';
	}
	if ( ! $template && in_array( $post->post_name, krm_design_pages(), true ) ) {
		return $post->post_name;
	}
	return '';
}

/**
 * Screen of the current front-end view ('' when the view has no page content).
 */
function krm_current_screen() {
	if ( is_front_page() && 'page' === get_option( 'show_on_front' ) ) {
		return 'home';
	}
	if ( is_home() ) {
		return 'blog';
	}
	return krm_design_template();
}

/**
 * Saved values of a screen merged over the schema defaults.
 *
 * @param string $screen  Screen.
 * @param int    $post_id Page to read (default: the page the screen uses).
 * @return array section => field => value
 */
function krm_content_values( $screen, $post_id = null ) {
	static $cache = array();
	if ( null === $post_id ) {
		$post_id = krm_content_post_id( $screen );
	}
	$key = $screen . ':' . $post_id;
	if ( isset( $cache[ $key ] ) && ! is_customize_preview() ) {
		return $cache[ $key ];
	}
	$saved = 'site' === $screen ? get_option( 'krm_site_text', array() ) : ( $post_id ? get_post_meta( $post_id, '_krm_content_' . $screen, true ) : array() );
	$saved = is_array( $saved ) ? $saved : array();
	$out   = array();
	foreach ( krm_content_schema( $screen ) as $section => $def ) {
		foreach ( $def['fields'] as $field => $f ) {
			$out[ $section ][ $field ] = isset( $saved[ $section ] ) && is_array( $saved[ $section ] ) && array_key_exists( $field, $saved[ $section ] ) ? $saved[ $section ][ $field ] : $f['default'];
		}
	}
	$cache[ $key ] = $out;
	return $out;
}

/**
 * One content value: krm_c( 'hero.title' ) on the current page, or krm_c( 'footer.tagline', 'site' ).
 *
 * @param string $path   section.field.
 * @param string $screen Screen (default: the current view's).
 * @return mixed
 */
function krm_c( $path, $screen = '' ) {
	$screen                  = $screen ? $screen : krm_current_screen();
	list( $section, $field ) = array_pad( explode( '.', $path, 2 ), 2, '' );
	$values                  = krm_content_values( $screen );
	return isset( $values[ $section ][ $field ] ) ? $values[ $section ][ $field ] : '';
}

/* ---------- Schema helpers (used by inc/content/*.php) ---------- */

/**
 * A field definition.
 *
 * @param string $type    text|textarea|html|url|image.
 * @param string $label   Label.
 * @param string $design  Design value.
 * @param string $help    Help text.
 */
function krm_f( $type, $label, $design = '', $help = '' ) {
	return array(
		'type'    => $type,
		'label'   => $label,
		'default' => $design,
		'help'    => $help,
	);
}

/**
 * A list field definition.
 *
 * @param string $label   Label.
 * @param string $item    Label of one item.
 * @param array  $fields  Item fields.
 * @param array  $design  Design items.
 * @param string $help    Help text.
 */
function krm_list_f( $label, $item, $fields, $design, $help = '' ) {
	return array(
		'type'    => 'list',
		'label'   => $label,
		'item'    => $item,
		/* translators: %s: item name, e.g. "card" */
		'add'     => sprintf( __( 'Add %s', 'kingdom-revival' ), strtolower( $item ) ),
		'fields'  => $fields,
		'default' => $design,
		'help'    => $help,
	);
}

/**
 * Section definition for a give band (template-parts/give-band.php).
 *
 * @param array $d Design values: image, eyebrow, heading, text, points (one per line), card.
 */
function krm_give_band_section( $d ) {
	$d     += array(
		'image'   => '',
		'eyebrow' => '',
		'heading' => '',
		'text'    => '',
		'points'  => '',
		'card'    => '',
	);
	$fields = array();
	if ( false !== $d['image'] ) {
		$fields['image'] = krm_f( 'image', __( 'Background image', 'kingdom-revival' ), $d['image'] );
	}
	$fields += array(
		'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), $d['eyebrow'] ),
		'heading' => krm_f( 'text', __( 'Heading', 'kingdom-revival' ), $d['heading'] ),
		'text'    => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), $d['text'] ),
		'points'  => krm_f( 'textarea', __( 'Check list', 'kingdom-revival' ), $d['points'], __( 'One point per line.', 'kingdom-revival' ) ),
	);
	if ( false !== $d['card'] ) {
		$fields['card'] = krm_f( 'text', __( 'Give card heading', 'kingdom-revival' ), $d['card'], __( 'Leave empty for “Make a gift”.', 'kingdom-revival' ) );
	}
	return array(
		'label'  => __( 'Give band', 'kingdom-revival' ),
		'help'   => __( 'The amount picker beside this text is the same on every page.', 'kingdom-revival' ),
		'fields' => $fields,
	);
}

/**
 * Section definition for a FAQ (question and answer list).
 *
 * @param string $eyebrow Design small heading.
 * @param string $heading Design heading.
 * @param array  $items   Design items: [ question, answer ].
 */
function krm_faq_section( $eyebrow, $heading, $items ) {
	return array(
		'label'  => __( 'Questions (FAQ)', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), $eyebrow ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), $heading ),
			'items'   => krm_list_f(
				__( 'Questions', 'kingdom-revival' ),
				__( 'Question', 'kingdom-revival' ),
				array(
					'question' => krm_f( 'text', __( 'Question', 'kingdom-revival' ) ),
					'answer'   => krm_f( 'textarea', __( 'Answer', 'kingdom-revival' ) ),
				),
				array_map(
					function ( $item ) {
						return array(
							'question' => $item[0],
							'answer'   => $item[1],
						);
					},
					$items
				)
			),
		),
	);
}

/**
 * Inline formatted text in which {amount} becomes the element the donate script fills with the chosen amount.
 *
 * @param string $value Value.
 * @param string $tag   Element for the amount (strong or span).
 */
function krm_gift_text( $value, $tag = 'strong' ) {
	return str_replace( '{amount}', '<' . $tag . ' data-gift-label></' . $tag . '>', krm_inline( $value ) );
}

/**
 * Arguments for template-parts/give-band.php from a give band section.
 *
 * @param string $section Section key.
 * @param string $screen  Screen.
 * @param array  $args    Extra or overriding arguments (id, heading_id, text_id, form).
 */
function krm_give_band_args( $section, $screen = '', $args = array() ) {
	$v    = krm_content_values( $screen ? $screen : krm_current_screen() );
	$v    = isset( $v[ $section ] ) ? $v[ $section ] : array();
	$form = isset( $args['form'] ) ? $args['form'] : array();
	if ( ! empty( $v['card'] ) ) {
		$form['heading'] = krm_fill( $v['card'] );
	}
	return array_merge(
		array(
			'image'   => isset( $v['image'] ) ? $v['image'] : '',
			'eyebrow' => krm_fill( isset( $v['eyebrow'] ) ? $v['eyebrow'] : '' ),
			'heading' => krm_fill( isset( $v['heading'] ) ? $v['heading'] : '' ),
			'text'    => krm_fill( isset( $v['text'] ) ? $v['text'] : '' ),
			'points'  => array_map( 'krm_fill', krm_lines( isset( $v['points'] ) ? $v['points'] : '' ) ),
		),
		$args,
		array( 'form' => $form )
	);
}

/**
 * Donate page amounts: "$25" => selected?, then "Other" (the values donate.js reads).
 *
 * @return array<string,bool>
 */
function krm_donate_amounts() {
	$default = (float) preg_replace( '/[^0-9.]/', '', krm_c( 'gift.default_amount', 'donate' ) );
	$out     = array();
	foreach ( explode( ',', krm_c( 'gift.amounts', 'donate' ) ) as $amount ) {
		$amount = (float) preg_replace( '/[^0-9.]/', '', $amount );
		if ( $amount > 0 ) {
			$out[ '$' . ( floor( $amount ) === $amount ? (int) $amount : $amount ) ] = $amount === $default;
		}
	}
	$out['Other'] = ! in_array( true, $out, true );
	return $out;
}

/**
 * Lines of a "one per line" field.
 *
 * @param string $value Value.
 * @return string[]
 */
function krm_lines( $value ) {
	return array_values( array_filter( array_map( 'trim', explode( "\n", (string) $value ) ), 'strlen' ) );
}

/**
 * Paragraphs of a text field (separated by a blank line), as escaped <p> elements.
 *
 * @param string $value Value.
 * @param string $attrs Attributes for each <p>.
 */
function krm_paras( $value, $attrs = '' ) {
	$out = '';
	foreach ( preg_split( '/\n\s*\n/', trim( (string) $value ) ) as $para ) {
		if ( '' !== trim( $para ) ) {
			$out .= '<p' . $attrs . '>' . nl2br( krm_text( trim( $para ) ), false ) . '</p>';
		}
	}
	return $out;
}

/* ---------- Output ---------- */

/**
 * Replace text placeholders: {phone}, {email}, {address}, {whatsapp}, {year}.
 *
 * @param string $text Text.
 */
function krm_fill( $text ) {
	$text = (string) $text;
	if ( false === strpos( $text, '{' ) ) {
		return $text;
	}
	return strtr(
		$text,
		array(
			'{phone}'    => krm_opt( 'phone' ),
			'{email}'    => krm_opt( 'email' ),
			'{address}'  => krm_opt( 'address' ),
			'{whatsapp}' => krm_opt( 'whatsapp' ),
			'{year}'     => wp_date( 'Y' ),
		)
	);
}

/**
 * Resolve a link value. Page placeholders: {home}, {about}, {contact}, {donate}, {partner}, {blog}; contact
 * placeholders: {email} (mailto), {phone} (tel), {whatsapp} (WhatsApp chat), {paypal} (PayPal.me link).
 * Anything after a placeholder is kept, e.g. {home}#all-ministries.
 *
 * @param string $url Stored link.
 */
function krm_resolve_url( $url ) {
	$url = trim( (string) $url );
	if ( ! preg_match( '/^\{([a-z]+)\}(.*)$/', $url, $m ) ) {
		return $url;
	}
	switch ( $m[1] ) {
		case 'home':
			$base = home_url( '/' );
			break;
		case 'blog':
			$base = krm_blog_url();
			break;
		case 'email':
			$base = krm_mailto();
			break;
		case 'phone':
			$base = krm_tel();
			break;
		case 'whatsapp':
			$base = krm_whatsapp_url();
			break;
		case 'paypal':
			$base = krm_opt( 'paypal_me' );
			break;
		default:
			$base = in_array( $m[1], krm_design_pages(), true ) ? krm_page_url( $m[1] ) : '';
	}
	return $base . $m[2];
}

/**
 * Allowed tags for inline formatted fields.
 */
function krm_inline_tags() {
	return array(
		'em'     => array(),
		'strong' => array(),
		'b'      => array(),
		'i'      => array(),
		'br'     => array(),
		'span'   => array(
			'class' => true,
			'style' => true,
		),
		'a'      => array(
			'href'   => true,
			'target' => true,
			'rel'    => true,
		),
	);
}

/**
 * Escaped plain text of a value (placeholders filled).
 *
 * @param string $value Value.
 */
function krm_text( $value ) {
	return esc_html( krm_fill( $value ) );
}

/**
 * Escaped inline formatted text of a value (placeholders filled).
 *
 * @param string $value Value.
 */
function krm_inline( $value ) {
	return krm_link_rel( wp_kses( krm_fill( $value ), krm_inline_tags() ) );
}

/**
 * Escaped URL of a link value.
 *
 * @param string $value Value.
 */
function krm_url( $value ) {
	return esc_url( krm_resolve_url( $value ) );
}

/**
 * A site text value with {ministry} replaced by a ministry name, escaped.
 *
 * @param string $path     section.field on the site screen.
 * @param string $ministry Ministry name.
 */
function krm_site_ministry_text( $path, $ministry ) {
	return esc_html( str_replace( '{ministry}', $ministry, krm_fill( krm_c( $path, 'site' ) ) ) );
}

/**
 * Give band arguments from a site text section, with {ministry} replaced.
 *
 * @param string $section  Section (story_give, ministry_give).
 * @param string $ministry Ministry name.
 * @param array  $args     Extra arguments.
 */
function krm_site_give_band_args( $section, $ministry, $args = array() ) {
	$band = krm_give_band_args( $section, 'site', $args );
	foreach ( array( 'eyebrow', 'heading', 'text' ) as $key ) {
		$band[ $key ] = str_replace( '{ministry}', $ministry, $band[ $key ] );
	}
	$band['points'] = str_replace( '{ministry}', $ministry, $band['points'] );
	return $band;
}

/**
 * Give band arguments for a ministry page: the ministry's focus followed by the shared text.
 *
 * @param string $section  Section (ministry_give).
 * @param string $ministry Ministry name.
 * @param array  $args     Extra arguments.
 */
function krm_ministry_give_band_args( $section, $ministry, $args = array() ) {
	$band         = krm_site_give_band_args( $section, $ministry, $args );
	$focus        = (string) get_post_meta( get_the_ID(), '_krm_focus', true );
	$band['text'] = trim( $focus . ' ' . $band['text'] );
	return $band;
}

/**
 * Echo a text field.
 *
 * @param string $path   section.field.
 * @param string $screen Screen.
 */
function krm_t( $path, $screen = '' ) {
	echo krm_text( krm_c( $path, $screen ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in krm_text().
}

/**
 * Echo an inline formatted field.
 *
 * @param string $path   section.field.
 * @param string $screen Screen.
 */
function krm_h( $path, $screen = '' ) {
	echo krm_inline( krm_c( $path, $screen ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in krm_inline().
}

/**
 * Echo a link field's URL.
 *
 * @param string $path   section.field.
 * @param string $screen Screen.
 */
function krm_u( $path, $screen = '' ) {
	echo krm_url( krm_c( $path, $screen ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in krm_url().
}

/**
 * Alt text for an image value: the alt typed beside it, else the image's own alt text.
 *
 * @param int|string $image Image value.
 * @param string     $alt   Alt text from the content field.
 */
function krm_alt( $image, $alt ) {
	return esc_attr( '' !== trim( (string) $alt ) ? krm_fill( $alt ) : krm_ref_alt( $image ) );
}

/* ---------- Sanitizing ---------- */

/**
 * Sanitize one field value by type.
 *
 * @param mixed $value Value.
 * @param array $f     Field definition.
 */
function krm_sanitize_field( $value, $f ) {
	if ( 'list' === $f['type'] ) {
		$items = array();
		foreach ( is_array( $value ) ? $value : array() as $item ) {
			$clean = array();
			$empty = true;
			foreach ( $f['fields'] as $key => $sub ) {
				$clean[ $key ] = krm_sanitize_field( isset( $item[ $key ] ) ? $item[ $key ] : '', $sub );
				$empty         = $empty && '' === (string) $clean[ $key ];
			}
			if ( ! $empty ) {
				$items[] = $clean;
			}
		}
		return $items;
	}
	$value = is_scalar( $value ) ? (string) $value : '';
	switch ( $f['type'] ) {
		case 'textarea':
			return sanitize_textarea_field( $value );
		case 'html':
			return trim( wp_kses( $value, krm_inline_tags() ) );
		case 'url':
			$value = trim( $value );
			if ( '' === $value || '#' === $value[0] || '{' === $value[0] ) {
				return sanitize_text_field( $value );
			}
			return esc_url_raw( $value, array( 'http', 'https', 'mailto', 'tel' ) );
		case 'image':
			$value = trim( $value );
			return ctype_digit( $value ) ? (string) absint( $value ) : esc_url_raw( $value, array( 'http', 'https' ) );
		default:
			return sanitize_text_field( $value );
	}
}

/**
 * Sanitize a submitted screen. Sections ticked "restore" are dropped so they fall back to the design.
 *
 * @param string $screen Screen.
 * @param array  $input  Submitted values.
 * @param array  $reset  Sections to restore.
 */
function krm_sanitize_content( $screen, $input, $reset = array() ) {
	$clean = array();
	foreach ( krm_content_schema( $screen ) as $section => $def ) {
		if ( ! empty( $reset[ $section ] ) || ! isset( $input[ $section ] ) || ! is_array( $input[ $section ] ) ) {
			continue;
		}
		foreach ( $def['fields'] as $field => $f ) {
			$clean[ $section ][ $field ] = krm_sanitize_field( isset( $input[ $section ][ $field ] ) ? $input[ $section ][ $field ] : ( 'list' === $f['type'] ? array() : '' ), $f );
		}
	}
	return $clean;
}

/* ---------- Admin: page edit screen ---------- */

/* Design pages are edited in the Page content box, so they use the classic screen without the unused editor. */
add_filter(
	'use_block_editor_for_post',
	function ( $use_block_editor, $post ) {
		return krm_screen_for_post( $post ) ? false : $use_block_editor;
	},
	10,
	2
);
add_action(
	'load-post.php',
	function () {
		$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $post_id && krm_screen_for_post( $post_id ) ) {
			remove_post_type_support( 'page', 'editor' );
		}
	}
);

add_action(
	'add_meta_boxes_page',
	function ( $post ) {
		$screen = krm_screen_for_post( $post );
		if ( $screen ) {
			/* translators: %s: page name, e.g. "About page" */
			add_meta_box( 'krm-content', sprintf( __( 'Page content — %s', 'kingdom-revival' ), krm_content_screens()[ $screen ] ), 'krm_content_box', 'page', 'normal', 'high', array( 'screen' => $screen ) );
		}
	}
);

/**
 * Page content meta box.
 *
 * @param WP_Post $post Page.
 * @param array   $box  Meta box arguments.
 */
function krm_content_box( $post, $box ) {
	$screen = $box['args']['screen'];
	wp_nonce_field( 'krm_content_save', 'krm_content_nonce' );
	echo '<input type="hidden" name="krm_content_screen" value="' . esc_attr( $screen ) . '">';
	krm_content_help();
	krm_render_content_fields( $screen, krm_content_values( $screen, $post->ID ) );
}

add_action(
	'save_post_page',
	function ( $post_id ) {
		if ( ! isset( $_POST['krm_content_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['krm_content_nonce'] ), 'krm_content_save' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$screen = isset( $_POST['krm_content_screen'] ) ? sanitize_key( $_POST['krm_content_screen'] ) : '';
		if ( ! $screen || 'site' === $screen || ! isset( krm_content_screens()[ $screen ] ) ) {
			return;
		}
		$input = isset( $_POST['krm_content'] ) ? wp_unslash( $_POST['krm_content'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized per field in krm_sanitize_content().
		$reset = isset( $_POST['krm_content_reset'] ) ? (array) wp_unslash( $_POST['krm_content_reset'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- only keys are used.
		update_post_meta( $post_id, '_krm_content_' . $screen, krm_sanitize_content( $screen, (array) $input, $reset ) );
	}
);

/* ---------- Admin: Kingdom Revival → Site text ---------- */

add_action(
	'admin_menu',
	function () {
		add_submenu_page( 'krm-settings', __( 'Site text', 'kingdom-revival' ), __( 'Site text', 'kingdom-revival' ), 'manage_options', 'krm-site-text', 'krm_site_text_page' );
	},
	20
);

/**
 * Render Kingdom Revival → Site text.
 */
function krm_site_text_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Site text', 'kingdom-revival' ); ?></h1>
		<p><?php esc_html_e( 'Wording shared across the site: header, footer, ministry and story pages, the ministries overview and the “page not found” page. Each page’s own content is edited on that page (Pages → edit → Page content).', 'kingdom-revival' ); ?></p>
		<?php if ( isset( $_GET['updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Site text saved.', 'kingdom-revival' ); ?></p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="krm-content">
			<input type="hidden" name="action" value="krm_site_text">
			<?php wp_nonce_field( 'krm_site_text', 'krm_site_text_nonce' ); ?>
			<?php krm_content_help(); ?>
			<?php krm_render_content_fields( 'site', krm_content_values( 'site' ) ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

add_action(
	'admin_post_krm_site_text',
	function () {
		if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['krm_site_text_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['krm_site_text_nonce'] ), 'krm_site_text' ) ) {
			wp_die( esc_html__( 'You are not allowed to do that.', 'kingdom-revival' ), 403 );
		}
		$input = isset( $_POST['krm_content'] ) ? wp_unslash( $_POST['krm_content'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized per field in krm_sanitize_content().
		$reset = isset( $_POST['krm_content_reset'] ) ? (array) wp_unslash( $_POST['krm_content_reset'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- only keys are used.
		update_option( 'krm_site_text', krm_sanitize_content( 'site', (array) $input, $reset ), false );
		wp_safe_redirect( add_query_arg( 'updated', '1', admin_url( 'admin.php?page=krm-site-text' ) ) );
		exit;
	}
);

/* ---------- Admin: field rendering ---------- */

/**
 * Short guide shown above the fields.
 */
function krm_content_help() {
	?>
	<p class="description krm-content-help">
		<?php esc_html_e( 'Fields start with the text and photos of the approved design. Headings marked “formatting allowed” accept <em>…</em> for the highlighted words.', 'kingdom-revival' ); ?>
		<?php esc_html_e( 'In text you can write {phone}, {email} or {address} to show the details from Kingdom Revival → Settings.', 'kingdom-revival' ); ?>
		<?php esc_html_e( 'Links accept a full address, a section of the page (#give) or {home}, {about}, {contact}, {donate}, {partner}, {blog}, {email}, {phone}, {whatsapp}, {paypal}.', 'kingdom-revival' ); ?>
	</p>
	<?php
}

/**
 * Render all sections of a screen.
 *
 * @param string $screen Screen.
 * @param array  $values Values.
 */
function krm_render_content_fields( $screen, $values ) {
	echo '<div class="krm-content">';
	foreach ( krm_content_schema( $screen ) as $section => $def ) {
		echo '<details class="krm-section"><summary>' . esc_html( $def['label'] ) . '</summary><div class="krm-section-body">';
		if ( ! empty( $def['help'] ) ) {
			echo '<p class="description">' . esc_html( $def['help'] ) . '</p>';
		}
		foreach ( $def['fields'] as $field => $f ) {
			krm_render_field( 'krm_content[' . $section . '][' . $field . ']', $f, $values[ $section ][ $field ] );
		}
		echo '<p class="krm-reset"><label><input type="checkbox" name="krm_content_reset[' . esc_attr( $section ) . ']" value="1"> ' . esc_html__( 'Restore this section to the original design when saving', 'kingdom-revival' ) . '</label></p>';
		echo '</div></details>';
	}
	echo '</div>';
}

/**
 * Render one field.
 *
 * @param string $name  Input name.
 * @param array  $f     Field definition.
 * @param mixed  $value Value.
 */
function krm_render_field( $name, $f, $value ) {
	$id    = 'krm-' . md5( $name );
	$label = $f['label'] . ( 'html' === $f['type'] ? ' ' . __( '(formatting allowed)', 'kingdom-revival' ) : '' );
	echo '<div class="krm-field krm-field-' . esc_attr( $f['type'] ) . '">';
	if ( 'list' === $f['type'] ) {
		echo '<p class="krm-label"><strong>' . esc_html( $label ) . '</strong></p>';
		if ( ! empty( $f['help'] ) ) {
			echo '<p class="description">' . esc_html( $f['help'] ) . '</p>';
		}
		echo '<div class="krm-list" data-krm-list>';
		foreach ( (array) $value as $i => $item ) {
			krm_render_list_item( $name, $f, $i, (array) $item );
		}
		echo '</div><template>';
		krm_render_list_item( $name, $f, '__i__', array() );
		echo '</template><p><button type="button" class="button krm-list-add">' . esc_html( isset( $f['add'] ) ? $f['add'] : __( 'Add item', 'kingdom-revival' ) ) . '</button></p>';
		echo '</div>';
		return;
	}
	echo '<label class="krm-label" for="' . esc_attr( $id ) . '"><strong>' . esc_html( $label ) . '</strong></label>';
	switch ( $f['type'] ) {
		case 'textarea':
		case 'html':
			echo '<textarea class="widefat" rows="' . ( 'html' === $f['type'] ? 2 : 3 ) . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">' . esc_textarea( $value ) . '</textarea>';
			break;
		case 'image':
			$src = '' !== (string) $value ? krm_ref_url( $value, 'medium' ) : '';
			echo '<div class="krm-image" data-krm-content-image data-default="' . esc_attr( $f['default'] ) . '" data-default-src="' . esc_url( '' !== (string) $f['default'] ? krm_ref_url( $f['default'], 'medium' ) : '' ) . '">';
			echo '<img src="' . esc_url( $src ) . '" alt=""' . ( $src ? '' : ' hidden' ) . '>';
			echo '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
			echo '<span><button type="button" class="button krm-image-pick">' . esc_html__( 'Choose image', 'kingdom-revival' ) . '</button> ';
			if ( '' !== (string) $f['default'] ) {
				echo '<button type="button" class="button-link krm-image-default">' . esc_html__( 'Use the design’s image', 'kingdom-revival' ) . '</button>';
			}
			echo '</span></div>';
			break;
		default:
			echo '<input class="widefat" type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
	}
	if ( ! empty( $f['help'] ) ) {
		echo '<p class="description">' . esc_html( $f['help'] ) . '</p>';
	}
	echo '</div>';
}

/**
 * Render one item of a list field.
 *
 * @param string     $name Input name of the list.
 * @param array      $f    List field definition.
 * @param int|string $i    Item index.
 * @param array      $item Item values.
 */
function krm_render_list_item( $name, $f, $i, $item ) {
	echo '<fieldset class="krm-item"><legend><span class="krm-item-title">' . esc_html( isset( $f['item'] ) ? $f['item'] : __( 'Item', 'kingdom-revival' ) ) . '</span>';
	echo ' <button type="button" class="button-link krm-item-up" aria-label="' . esc_attr__( 'Move up', 'kingdom-revival' ) . '">↑</button>';
	echo ' <button type="button" class="button-link krm-item-down" aria-label="' . esc_attr__( 'Move down', 'kingdom-revival' ) . '">↓</button>';
	echo ' <button type="button" class="button-link krm-item-remove">' . esc_html__( 'Remove', 'kingdom-revival' ) . '</button></legend>';
	foreach ( $f['fields'] as $key => $sub ) {
		krm_render_field( $name . '[' . $i . '][' . $key . ']', $sub + array( 'default' => '' ), isset( $item[ $key ] ) ? $item[ $key ] : '' );
	}
	echo '</fieldset>';
}

add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		$screen  = get_current_screen();
		$is_page = $screen && 'page' === $screen->post_type && in_array( $hook, array( 'post.php', 'post-new.php' ), true );
		if ( ! $is_page && ( ! $screen || 'kingdom-revival_page_krm-site-text' !== $screen->id ) ) {
			return;
		}
		wp_enqueue_media();
		wp_enqueue_script( 'krm-admin-content', krm_asset( 'js/admin-content.js' ), array( 'jquery' ), KRM_VERSION, true );
		wp_enqueue_style( 'krm-admin-content', krm_asset( 'css/admin-content.css' ), array(), KRM_VERSION );
	}
);
