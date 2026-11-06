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
			'more'         => krm_f( 'text', __( '“Show more” button under the testimonies (18 at a time; {n} = how many it shows next)', 'kingdom-revival' ), 'Show {n} more' ),
			'empty'        => krm_f( 'textarea', __( 'Text while there are no testimonies', 'kingdom-revival' ), 'Testimonies from the families and churches we serve will appear here.' ),
		),
	),
	'share' => array(
		'label'  => __( 'Share your testimony form', 'kingdom-revival' ),
		'help'   => __( 'Every testimony sent with this form is saved as a pending Testimonial (dashboard → Testimonials) and emailed to the form recipient (Kingdom Revival → Settings). Nothing is published until you open it and press Publish. The form’s fields are fixed.', 'kingdom-revival' ),
		'fields' => array(
			'heading' => krm_f( 'text', __( 'Heading', 'kingdom-revival' ), 'Share your testimony' ),
			'text'    => krm_f( 'textarea', __( 'Text under the heading', 'kingdom-revival' ), 'Has God worked in your life, your family or your church through this ministry? Tell us your story. We read every testimony before anything is published.' ),
			'consent' => krm_f( 'text', __( 'Consent checkbox', 'kingdom-revival' ), 'I agree this may be published' ),
			'button'  => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Send my testimony' ),
			'success' => krm_f( 'textarea', __( 'Message after sending', 'kingdom-revival' ), 'Thank you — your testimony has been sent. We will read it before anything is published.' ),
			'error'   => krm_f( 'textarea', __( 'Message when sending failed', 'kingdom-revival' ), 'Sorry, your testimony could not be sent. Please check the form and try again, or contact us.' ),
		),
	),
);
