<?php
/**
 * Theme supports, menus and lightweight front-end cleanup.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'kingdom-revival', KRM_DIR . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support( 'responsive-embeds' );
		add_theme_support(
			'custom-logo',
			array(
				'height'               => 128,
				'width'                => 128,
				'flex-height'          => true,
				'flex-width'           => true,
				'unlink-homepage-logo' => false,
			)
		);

		register_nav_menus(
			array(
				'primary'           => __( 'Main navigation', 'kingdom-revival' ),
				'footer_quick'      => __( 'Footer: Quick Links (inner pages)', 'kingdom-revival' ),
				'footer_ministries' => __( 'Footer: Ministries (inner pages)', 'kingdom-revival' ),
				'footer_home'       => __( 'Footer: Quick Links (home page)', 'kingdom-revival' ),
			)
		);
	}
);

$GLOBALS['content_width'] = 740;

/* Emoji scripts are not used by the design. */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Block styles: the design's templates render their own markup (stories are styled by post.css), so block and
 * global (theme.json) CSS is only loaded on generic pages. This keeps WordPress defaults from altering the design.
 *
 * With separate block assets, classic themes get their global styles enqueued at wp_footer priority 1 (WordPress
 * then moves them into <head>), so the styles are dequeued there as well as at wp_enqueue_scripts.
 */
function krm_dequeue_block_styles() {
	if ( is_front_page() || is_page_template() || krm_is_design_page() || is_singular( 'ministry' ) || is_post_type_archive( 'ministry' ) || is_home() || is_archive() || is_search() || is_404() || is_singular( 'post' ) ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'krm_dequeue_block_styles', 100 );
add_action( 'wp_footer', 'krm_dequeue_block_styles', 2 );
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Is the current request one of the design's fixed pages (about, contact, donate, partner)?
 */
function krm_is_design_page() {
	return '' !== krm_design_template();
}

/*
 * The design pages live in page-templates/. A page with one of their slugs and no template chosen in
 * Page Attributes still gets its design template, as page-{slug}.php would.
 */
add_filter(
	'page_template_hierarchy',
	function ( $templates ) {
		$page = get_queried_object();
		if ( ! $page instanceof WP_Post || get_page_template_slug( $page ) ) {
			return $templates;
		}
		$slug = urldecode( $page->post_name );
		if ( in_array( $slug, krm_design_pages(), true ) ) {
			array_unshift( $templates, 'page-templates/' . $slug . '.php' );
		}
		return $templates;
	}
);

/* After activation, point admins to the setup screen. */
add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'manage_options' ) || get_option( 'krm_setup_done' ) ) {
			return;
		}
		if ( isset( $_GET['page'] ) && 'krm-setup' === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		printf(
			'<div class="notice notice-info"><p><strong>%1$s</strong> %2$s <a href="%3$s">%4$s</a></p></div>',
			esc_html__( 'Kingdom Revival Ministries theme:', 'kingdom-revival' ),
			esc_html__( 'import the ministries, stories, pages, menus and images to finish setting up.', 'kingdom-revival' ),
			esc_url( admin_url( 'admin.php?page=krm-setup' ) ),
			esc_html__( 'Open the setup screen', 'kingdom-revival' )
		);
	}
);
