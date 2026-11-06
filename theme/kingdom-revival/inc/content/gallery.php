<?php
/**
 * Photo & Video Gallery page content (page-templates/gallery.php, /our-impact/gallery/). Photos are every published
 * ministry's photo gallery (Ministries → edit) plus the "General photos" below; videos are listed here.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	'hero'   => array(
		'label'  => __( 'Hero', 'kingdom-revival' ),
		'fields' => array(
			'image'   => krm_f( 'image', __( 'Background image', 'kingdom-revival' ), 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg' ),
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Our Impact' ),
			'title'   => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Photo & Video Gallery' ),
			'lead'    => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Moments from our ministry — photos and videos from our ministries across Pakistan.', __( 'DRAFT — CLIENT TO APPROVE: proposed wording, written only from text already on the site or in the client’s sitemap.', 'kingdom-revival' ) ),
		),
	),
	'labels' => array(
		'label'  => __( 'Tabs and filter', 'kingdom-revival' ),
		'fields' => array(
			'photos'       => krm_f( 'text', __( 'Photos tab', 'kingdom-revival' ), 'Photos' ),
			'videos'       => krm_f( 'text', __( 'Videos tab', 'kingdom-revival' ), 'Videos' ),
			'filter_label' => krm_f( 'text', __( 'Ministry filter label', 'kingdom-revival' ), 'Ministry' ),
			'all_label'    => krm_f( 'text', __( 'Ministry filter: all', 'kingdom-revival' ), 'All ministries' ),
			'general'      => krm_f( 'text', __( 'Ministry filter: general photos', 'kingdom-revival' ), 'General' ),
			'tip'          => krm_f( 'text', __( 'Tip above the photos', 'kingdom-revival' ), 'Tap any photo to view it full screen and zoom in.' ),
			'count_text'   => krm_f( 'text', __( 'Announced after filtering ({n} = number)', 'kingdom-revival' ), 'Showing {n} items' ),
			'empty'        => krm_f( 'text', __( 'Text when the filter matches nothing', 'kingdom-revival' ), 'Nothing here yet for this ministry.' ),
			'more'         => krm_f( 'text', __( '“Show more” button under the photos and videos (18 at a time; {n} = how many it shows next)', 'kingdom-revival' ), 'Show {n} more' ),
			'no_videos'    => krm_f( 'textarea', __( 'Text while there are no videos', 'kingdom-revival' ), 'New videos from the field are coming soon. Follow us on social media to see the latest.' ),
		),
	),
	'videos' => array(
		'label'  => __( 'Videos', 'kingdom-revival' ),
		'fields' => array(
			'items' => krm_list_f(
				__( 'Videos', 'kingdom-revival' ),
				__( 'Video', 'kingdom-revival' ),
				array(
					'title'    => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'url'      => krm_f( 'url', __( 'YouTube link or local video', 'kingdom-revival' ) ),
					'ministry' => krm_f( 'text', __( 'Ministry (its name as on What We Do; optional)', 'kingdom-revival' ) ),
				),
				array(
					array(
						'title'    => 'Ministry field update',
						'url'      => 'krm-theme:video/gallery/ministry-field-update.mp4',
						'ministry' => '',
					),
					array(
						'title'    => 'Kingdom Revival ministry gathering',
						'url'      => 'krm-theme:video/gallery/ministry-gathering.mp4',
						'ministry' => '',
					),
					array(
						'title'    => 'Ministry highlights',
						'url'      => 'krm-theme:video/gallery/ministry-highlights.mp4',
						'ministry' => '',
					),
				)
			),
		),
	),
	'photos' => array(
		'label'  => __( 'General photos', 'kingdom-revival' ),
		'help'   => __( 'Photos that belong to no single ministry (shown under “General”). Each ministry’s photos come from its own gallery, so they are not added here.', 'kingdom-revival' ),
		'fields' => array(
			'items' => krm_list_f(
				__( 'Photos', 'kingdom-revival' ),
				__( 'Photo', 'kingdom-revival' ),
				array(
					'image'     => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'image_alt' => krm_f( 'text', __( 'Description (alt text)', 'kingdom-revival' ) ),
				),
				array()
			),
		),
	),
);
