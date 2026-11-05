<?php
/**
 * Kingdom Revival Ministries theme bootstrap.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

// The theme version has one source: the "Version:" header in style.css. It versions enqueued assets and triggers the
// rewrite-rule flush on update. get_template() names this (parent) theme even when a child theme is active.
define( 'KRM_VERSION', (string) wp_get_theme( get_template() )->get( 'Version' ) );
define( 'KRM_DIR', get_template_directory() );

require KRM_DIR . '/inc/template-functions.php';
require KRM_DIR . '/inc/home.php';
require KRM_DIR . '/inc/setup.php';
require KRM_DIR . '/inc/settings.php';
require KRM_DIR . '/inc/customizer.php';
require KRM_DIR . '/inc/content-fields.php';
require KRM_DIR . '/inc/enqueue.php';
require KRM_DIR . '/inc/ministry.php';
require KRM_DIR . '/inc/projects.php';
require KRM_DIR . '/inc/upgrade.php';
require KRM_DIR . '/inc/content-types.php';
require KRM_DIR . '/inc/post-meta.php';
require KRM_DIR . '/inc/nav.php';
require KRM_DIR . '/inc/content.php';
require KRM_DIR . '/inc/forms.php';
require KRM_DIR . '/inc/donations.php';
require KRM_DIR . '/inc/seo.php';
require KRM_DIR . '/inc/schema.php';
require KRM_DIR . '/inc/redirects.php';
require KRM_DIR . '/inc/performance.php';
require KRM_DIR . '/inc/security.php';
require KRM_DIR . '/inc/arrows.php';

if ( is_admin() ) {
	require KRM_DIR . '/inc/importer.php';
}
