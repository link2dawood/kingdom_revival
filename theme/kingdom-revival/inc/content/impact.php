<?php
/**
 * Our Impact page content (page-templates/impact.php, /our-impact/). The numbers are the impact numbers of
 * Kingdom Revival → Settings (shared with About and News & Updates); each ministry's own numbers are its
 * "Impact stats" field (Ministries → edit → What We Do card).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	'hero'       => array(
		'label'  => __( 'Hero', 'kingdom-revival' ),
		'fields' => array(
			'image'   => krm_f( 'image', __( 'Background image', 'kingdom-revival' ), 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-38-scaled.jpg' ),
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Our Impact' ),
			'title'   => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Our Impact' ),
			'lead'    => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'See what God is doing through our ministries across Pakistan — in numbers, in testimonies and in pictures.', __( 'DRAFT — CLIENT TO APPROVE: proposed wording, written only from text already on the site or in the client’s sitemap.', 'kingdom-revival' ) ),
		),
	),
	'numbers'    => array(
		'label'  => __( 'The story in numbers', 'kingdom-revival' ),
		'help'   => __( 'The numbers themselves are the impact numbers of Kingdom Revival → Settings.', 'kingdom-revival' ),
		'fields' => array(
			'band_photo' => krm_f( 'image', __( 'Background photo (seen through the navy overlay; empty = plain colour)', 'kingdom-revival' ), 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-22.jpg' ),
		),
	),
	'ministries' => array(
		'label'  => __( 'Impact by ministry', 'kingdom-revival' ),
		'help'   => __( 'One card per published ministry, in their two groups. A ministry without impact stats shows the “no numbers yet” text.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'By ministry' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Impact by ministry' ),
			'empty'   => krm_f( 'text', __( 'Text on a ministry without numbers', 'kingdom-revival' ), 'Impact numbers for this ministry are coming soon.' ),
			'more'    => krm_f( 'text', __( 'Card link text', 'kingdom-revival' ), 'View ministry →' ),
		),
	),
	'more'       => array(
		'label'  => __( 'Testimonies and gallery links', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'           => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Lives changed' ),
			'heading'           => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Stories from the field' ),
			'testimonies_title' => krm_f( 'text', __( 'Testimonies card: title', 'kingdom-revival' ), 'Testimonies' ),
			'testimonies_text'  => krm_f( 'textarea', __( 'Testimonies card: text', 'kingdom-revival' ), 'Lives changed through the Gospel and practical care, in their own words.', __( 'DRAFT — CLIENT TO APPROVE: proposed wording, written only from text already on the site or in the client’s sitemap.', 'kingdom-revival' ) ),
			'gallery_title'     => krm_f( 'text', __( 'Gallery card: title', 'kingdom-revival' ), 'Photo & Video Gallery' ),
			'gallery_text'      => krm_f( 'textarea', __( 'Gallery card: text', 'kingdom-revival' ), 'Moments from our ministry, in photos and videos.', __( 'DRAFT — CLIENT TO APPROVE: proposed wording, written only from text already on the site or in the client’s sitemap.', 'kingdom-revival' ) ),
			'link'              => krm_f( 'text', __( 'Card link text', 'kingdom-revival' ), 'Find out more →' ),
		),
	),
);
