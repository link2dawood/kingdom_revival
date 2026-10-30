<?php
/**
 * Kingdom Revival Ministries theme bootstrap.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

define( 'KRM_VERSION', '1.1.0' );
define( 'KRM_DIR', get_template_directory() );

require KRM_DIR . '/inc/template-functions.php';
require KRM_DIR . '/inc/home.php';
require KRM_DIR . '/inc/setup.php';
require KRM_DIR . '/inc/settings.php';
require KRM_DIR . '/inc/customizer.php';
require KRM_DIR . '/inc/enqueue.php';
require KRM_DIR . '/inc/ministry.php';
require KRM_DIR . '/inc/post-meta.php';
require KRM_DIR . '/inc/nav.php';
require KRM_DIR . '/inc/content.php';
require KRM_DIR . '/inc/forms.php';
require KRM_DIR . '/inc/seo.php';

if ( is_admin() ) {
	require KRM_DIR . '/inc/importer.php';
}
