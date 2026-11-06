<?php
/**
 * The site's pages, per the client's sitemap: slug => [ title, design template ('' = none), parent slug ].
 * Setup creates any that are missing; krm_upgrade_to_sitemap() renames the earlier ones (blog → news,
 * partner → partner-with-us) and adds the new ones on older sites. Links find design pages by template
 * (krm_page_url()), so a page can be renamed without breaking them.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	'about'           => array( 'About Us', 'about', '' ),
	'our-impact'      => array( 'Our Impact', 'impact', '' ),
	'testimonies'     => array( 'Testimonials', 'testimonies', 'our-impact' ),
	'gallery'         => array( 'Photo & Video Gallery', 'gallery', 'our-impact' ),
	'news'            => array( 'News & Updates', '', '' ),
	'partner-with-us' => array( 'Partner With Us', 'partner', '' ),
	'prayer-request'  => array( 'Prayer Request', 'prayer', '' ),
	'contact'         => array( 'Contact', 'contact', '' ),
	'donate'          => array( 'Donate / Give', 'donate', '' ),
);
