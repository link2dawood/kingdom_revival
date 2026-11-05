<?php
/**
 * Testimonies page content (page-templates/testimonies.php, /our-impact/testimonies/). The testimonies themselves are
 * the Testimonials in the dashboard (name, words, photo, "Role or place", "Related ministry" for the filter).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	'hero' => array(
		'label'  => __( 'Hero', 'kingdom-revival' ),
		'fields' => array(
			'image'   => krm_f( 'image', __( 'Background image', 'kingdom-revival' ), 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-29.jpg' ),
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Our Impact' ),
			'title'   => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Testimonies' ),
			'lead'    => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Stories of hope from the field — lives changed through the Gospel and practical care across Pakistan.', __( 'DRAFT — CLIENT TO APPROVE: proposed wording, written only from text already on the site or in the client’s sitemap.', 'kingdom-revival' ) ),
		),
	),
	'list' => array(
		'label'  => __( 'Testimonies', 'kingdom-revival' ),
		'fields' => array(
			'filter_label' => krm_f( 'text', __( 'Filter: name for screen readers', 'kingdom-revival' ), 'Filter testimonies by ministry' ),
			'all_label'    => krm_f( 'text', __( 'Filter: first tab', 'kingdom-revival' ), 'All' ),
			'count_text'   => krm_f( 'text', __( 'Filter: announced after filtering ({n} = number)', 'kingdom-revival' ), 'Showing {n} testimonies' ),
			'empty'        => krm_f( 'textarea', __( 'Text while there are no testimonies', 'kingdom-revival' ), 'New testimonies are coming soon.' ),
		),
	),
);
