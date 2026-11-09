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
			'title'   => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Our <span class="hl">Gallery</span>', __( 'Wrap highlighted words in <span class="hl">…</span>.', 'kingdom-revival' ) ),
			'lead'    => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Moments of hope, faith and service across Pakistan and beyond.' ),
			'button1_label' => krm_f( 'text', __( 'First button text (empty = hidden)', 'kingdom-revival' ), 'Give Now' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '{donate}#give' ),
			'button2_label' => krm_f( 'text', __( 'Second button text (empty = hidden)', 'kingdom-revival' ), 'Contact' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{contact}' ),
		),
	),
	'labels' => array(
		'label'  => __( 'Tabs and filter', 'kingdom-revival' ),
		'fields' => array(
			'photos'       => krm_f( 'text', __( 'Photos tab', 'kingdom-revival' ), 'Photos' ),
			'videos'       => krm_f( 'text', __( 'Videos tab', 'kingdom-revival' ), 'Videos' ),
			'filter_label' => krm_f( 'text', __( 'Ministry filter label', 'kingdom-revival' ), 'Ministry' ),
			'all_label'    => krm_f( 'text', __( 'Filter: first chip (everything)', 'kingdom-revival' ), 'Everything' ),
			'general'      => krm_f( 'text', __( 'Ministry filter: general photos', 'kingdom-revival' ), 'General' ),
			'tip'          => krm_f( 'text', __( 'Tip above the photos', 'kingdom-revival' ), 'Tap any photo to view it full screen and zoom in.' ),
			'verse1'       => krm_f( 'textarea', __( 'Scripture card 1 (between the photos, “Everything” only)', 'kingdom-revival' ), '“The LORD hath done great things for us; whereof we are glad.”' ),
			'cite1'        => krm_f( 'text', __( 'Scripture card 1: reference', 'kingdom-revival' ), 'Psalm 126:3' ),
			'verse2'       => krm_f( 'textarea', __( 'Scripture card 2 (alternates with card 1, after about every 10 photos)', 'kingdom-revival' ), '“Let your light so shine before men, that they may see your good works, and glorify your Father which is in heaven.”' ),
			'cite2'        => krm_f( 'text', __( 'Scripture card 2: reference', 'kingdom-revival' ), 'Matthew 5:16' ),
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
