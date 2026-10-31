<?php
/**
 * Styles, scripts and the self-hosted font.
 *
 * The home page loads only its own stylesheet (front.css), exactly as the design did; every other page loads the
 * shared site.css plus a small page stylesheet that holds that page's original inline styles.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Which page stylesheet/script belongs to the current view.
 */
function krm_view() {
	if ( is_front_page() ) {
		return 'front';
	}
	$design = krm_design_template();
	if ( $design ) {
		return $design;
	}
	if ( is_singular( 'ministry' ) || is_post_type_archive( 'ministry' ) || is_tax( 'ministry_group' ) ) {
		return 'ministry';
	}
	if ( is_singular( array( 'post', 'sermon', 'event' ) ) ) {
		return 'post';
	}
	if ( is_home() || is_archive() || is_search() ) {
		return 'blog';
	}
	return 'page';
}

/*
 * Plus Jakarta Sans (latin, variable 400–800), served from the theme. The @font-face rule is declared in theme.json
 * and printed by WordPress on the site and in the editor; this preloads the file so text renders in it first time.
 */
add_action(
	'wp_head',
	function () {
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( krm_asset( 'fonts/plus-jakarta-sans-latin.woff2' ) ) );
	},
	2
);

add_action(
	'wp_enqueue_scripts',
	function () {
		$view = krm_view();
		$ver  = KRM_VERSION;

		if ( 'front' === $view ) {
			wp_enqueue_style( 'krm-front', krm_asset( 'css/front.css' ), array(), $ver );
		} else {
			wp_enqueue_style( 'krm-site', krm_asset( 'css/site.css' ), array(), $ver );
			wp_enqueue_style( 'krm-' . $view, krm_asset( 'css/pages/' . $view . '.css' ), array( 'krm-site' ), $ver );
			if ( 'page' === $view ) {
				// Story cards for fallback listings.
				wp_enqueue_style( 'krm-blog', krm_asset( 'css/pages/blog.css' ), array( 'krm-site' ), $ver );
			}
		}
		wp_add_inline_style( 'front' === $view ? 'krm-front' : 'krm-site', krm_bg_small_css() );

		// Sermons, events and testimonials (also used by the [krm_testimonials] shortcode).
		wp_register_style( 'krm-extras', krm_asset( 'css/pages/extras.css' ), array( 'krm-site' ), $ver );
		if ( is_singular( array( 'sermon', 'event' ) ) || is_post_type_archive( array( 'sermon', 'event', 'testimonial' ) ) || is_tax( 'sermon_series' ) ) {
			wp_enqueue_style( 'krm-extras' );
		}

		wp_enqueue_script( 'krm-site', krm_asset( 'js/site.js' ), array(), $ver, array( 'in_footer' => true ) );
		wp_localize_script(
			'krm-site',
			'KRM_CONFIG',
			array(
				'donateUrl'       => krm_donate_url(),
				'isDonatePage'    => 'donate' === $view,
				'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
				'nonce'           => is_user_logged_in() ? wp_create_nonce( 'krm_form' ) : '',
				'formToken'       => krm_form_token(),
				'email'           => krm_opt( 'email' ),
				'newsletterDelay' => (int) krm_opt( 'newsletter_delay' ) * 1000,
				// No newsletter popup over the thank-you view right after a gift.
				'noNewsletter'    => (bool) krm_gift_return(),
				'newsletterImage' => krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-40-scaled.jpg', '1536x1536' ),
			)
		);

		$scripts = array( 'front', 'blog', 'post', 'contact', 'donate', 'partner' );
		if ( in_array( $view, $scripts, true ) ) {
			wp_enqueue_script( 'krm-' . $view, krm_asset( 'js/' . $view . '.js' ), array( 'krm-site' ), $ver, array( 'in_footer' => true ) );
		}
		if ( 'donate' === $view ) {
			wp_localize_script(
				'krm-donate',
				'KRM_DONATE',
				array(
					'paypalMe'    => krm_opt( 'paypal_me' ),
					'paypalEmail' => krm_opt( 'paypal_email' ),
					'email'       => krm_opt( 'email' ),
					'paypalHost'  => krm_paypal_host(),
					'returnUrl'   => add_query_arg( 'thanks', '1', krm_donate_url() ),
					'cancelUrl'   => krm_donate_url(),
					'notifyUrl'   => krm_paypal_notify_url(),
				)
			);
		}

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
);
