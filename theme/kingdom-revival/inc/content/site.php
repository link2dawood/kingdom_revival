<?php
/**
 * Site text (Kingdom Revival → Site text): header, footers, ministry and story pages, the ministries overview
 * and the "page not found" page. Defaults are the approved design's content.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_up       = 'https://kingdomrevivalministries.com/wp-content/uploads/';
$krm_ministry = __( 'Write {ministry} for the ministry’s name.', 'kingdom-revival' );

$krm_band                              = krm_give_band_section(
	array(
		'image'   => $krm_up . '2026/08/Orphanages-care-19.jpg',
		'eyebrow' => 'Be part of the story',
		'heading' => 'Stand with {ministry}',
		'text'    => 'Every gift — one-time or monthly — goes to the work you have just read about, bringing the hope of Jesus and practical care to families who need it most.',
		'points'  => "Choose any amount, one-time or monthly\nYour gift goes to this ministry’s work in Pakistan\nYou will be part of the next story we share",
		'card'    => false,
	)
);
$krm_band['fields']['heading']['help'] = $krm_ministry;

$krm_ministry_band                              = krm_give_band_section(
	array(
		'image'   => false,
		'eyebrow' => 'Support this ministry',
		'heading' => 'Give to {ministry}',
		'text'    => 'Your gift keeps this work going and reaches the people it serves.',
		'points'  => "Give once or every month\nYour gift goes to this ministry’s work in Pakistan",
		'card'    => false,
	)
);
$krm_ministry_band['label']                     = __( 'Ministry pages: give band', 'kingdom-revival' );
$krm_ministry_band['fields']['heading']['help'] = $krm_ministry;
$krm_ministry_band['fields']['text']['label']   = __( 'Text after the ministry focus', 'kingdom-revival' );
$krm_ministry_band['fields']['text']['help']    = __( 'Shown after the ministry’s “focus” text.', 'kingdom-revival' );
$krm_band['label']                              = __( 'Story pages: give band', 'kingdom-revival' );

return array(
	'header'        => array(
		'label'  => __( 'Header', 'kingdom-revival' ),
		'help'   => __( 'The logo is set in Appearance → Customize → Site Identity; the menu in Appearance → Menus.', 'kingdom-revival' ),
		'fields' => array(
			'brand_name'   => krm_f( 'text', __( 'Name beside the logo', 'kingdom-revival' ), 'Kingdom Revival' ),
			'brand_sub'    => krm_f( 'text', __( 'Small line under the name', 'kingdom-revival' ), 'MINISTRIES' ),
			'logo_alt'     => krm_f( 'text', __( 'Logo description (alt text)', 'kingdom-revival' ), 'Kingdom Revival Ministries logo' ),
			'donate_label' => krm_f( 'text', __( 'Top bar button text', 'kingdom-revival' ), 'Donate Now' ),
		),
	),
	'footer'        => array(
		'label'  => __( 'Footer', 'kingdom-revival' ),
		'help'   => __( 'Footer links are set in Appearance → Menus; the address, phone and email in Kingdom Revival → Settings.', 'kingdom-revival' ),
		'fields' => array(
			'name'               => krm_f( 'text', __( 'Name', 'kingdom-revival' ), 'Kingdom Revival Ministries' ),
			'tagline'            => krm_f( 'textarea', __( 'Tagline (inner pages)', 'kingdom-revival' ), 'Bringing the hope of Jesus Christ to the forgotten, the unreached, and the vulnerable across Pakistan.' ),
			'tagline_home'       => krm_f( 'textarea', __( 'Tagline (home page)', 'kingdom-revival' ), 'Bringing the hope of Jesus Christ to the forgotten, the unreached, and the vulnerable.' ),
			'quick_heading'      => krm_f( 'text', __( 'Links heading', 'kingdom-revival' ), 'Quick Links' ),
			'ministries_heading' => krm_f( 'text', __( 'Ministries heading (inner pages)', 'kingdom-revival' ), 'Ministries' ),
			'contact_heading'    => krm_f( 'text', __( 'Contact heading', 'kingdom-revival' ), 'Contact' ),
			'copyright'          => krm_f( 'text', __( 'Copyright line', 'kingdom-revival' ), '© {year} Kingdom Revival Ministries', __( '{year} is the current year.', 'kingdom-revival' ) ),
			'location'           => krm_f( 'text', __( 'Line beside the copyright', 'kingdom-revival' ), 'Faisalabad, Pakistan' ),
		),
	),
	'ministry'      => array(
		'label'  => __( 'Ministry pages', 'kingdom-revival' ),
		'help'   => __( 'Each ministry’s own title, description, focus, points and photos are edited on the ministry (Ministries → edit).', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'        => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Kingdom Revival Ministries' ),
			'donate_button'  => krm_f( 'text', __( 'Hero button text', 'kingdom-revival' ), 'Donate to this ministry' ),
			'focus_heading'  => krm_f( 'text', __( 'Focus heading', 'kingdom-revival' ), 'Ministry focus' ),
			'serves_heading' => krm_f( 'text', __( 'Points heading', 'kingdom-revival' ), 'How this ministry serves' ),
			'photos_heading' => krm_f( 'text', __( 'Photos heading', 'kingdom-revival' ), '{ministry} photos', $krm_ministry ),
			'photos_tip'     => krm_f( 'text', __( 'Photos tip', 'kingdom-revival' ), 'Tap a photo to view it full screen and zoom in.' ),
			'photos_empty'   => krm_f( 'text', __( 'Text when there are no photos', 'kingdom-revival' ), 'No photos are available for this ministry yet.' ),
			'hero_image'     => krm_f( 'image', __( 'Hero image for ministries without photos', 'kingdom-revival' ), $krm_up . 'elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg' ),
		),
	),
	'ministry_give' => $krm_ministry_band,
	'story'         => array(
		'label'  => __( 'Story pages', 'kingdom-revival' ),
		'fields' => array(
			'share'           => krm_f( 'text', __( 'Share heading', 'kingdom-revival' ), 'Share this story' ),
			'back'            => krm_f( 'text', __( 'Back button text', 'kingdom-revival' ), '← All stories' ),
			'author_name'     => krm_f( 'text', __( 'Author box: name', 'kingdom-revival' ), 'Kingdom Revival Ministries Team' ),
			'author_text'     => krm_f( 'text', __( 'Author box: text', 'kingdom-revival' ), 'Sharing stories from the villages, kilns and churches we serve in Pakistan.' ),
			'previous'        => krm_f( 'text', __( 'Previous story label', 'kingdom-revival' ), '← Previous story' ),
			'next'            => krm_f( 'text', __( 'Next story label', 'kingdom-revival' ), 'Next story →' ),
			'side_give_intro' => krm_f( 'text', __( 'Sidebar give card: text', 'kingdom-revival' ), 'Your gift helps this story continue.' ),
			'side_ministry'   => krm_f( 'text', __( 'Sidebar ministry card: label', 'kingdom-revival' ), 'About this ministry' ),
			'side_more'       => krm_f( 'text', __( 'Sidebar ministry card: link text', 'kingdom-revival' ), 'Learn more →' ),
			'side_pray_label' => krm_f( 'text', __( 'Sidebar prayer card: label', 'kingdom-revival' ), 'Pray with us' ),
			'side_pray_title' => krm_f( 'text', __( 'Sidebar prayer card: title', 'kingdom-revival' ), 'Send a prayer request' ),
			'side_pray_link'  => krm_f( 'text', __( 'Sidebar prayer card: link text', 'kingdom-revival' ), 'Contact us →' ),
			'side_pray_url'   => krm_f( 'url', __( 'Sidebar prayer card: link', 'kingdom-revival' ), '{contact}' ),
			'related_eyebrow' => krm_f( 'text', __( 'More stories: small heading', 'kingdom-revival' ), 'Keep reading' ),
			'related_heading' => krm_f( 'text', __( 'More stories: heading', 'kingdom-revival' ), 'More stories of hope' ),
			'related_button'  => krm_f( 'text', __( 'More stories: button text', 'kingdom-revival' ), 'View all stories' ),
		),
	),
	'story_give'    => $krm_band,
	'overview'      => array(
		'label'  => __( 'Ministries overview (/ministries/)', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Ministry details' ),
			'heading'      => krm_f( 'text', __( 'Heading', 'kingdom-revival' ), 'Choose a ministry to explore' ),
			'text'         => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Pick a ministry from the menu above, or browse them all on the home page.' ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Browse ministries' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{home}#all-ministries' ),
		),
	),
	'types'         => array(
		'label'  => __( 'Sermons, events and testimonials pages', 'kingdom-revival' ),
		'help'   => __( 'Headings of the /sermons/, /events/ and /testimonials/ pages and the labels on their cards. These pages are not in the menu until you add them in Appearance → Menus.', 'kingdom-revival' ),
		'fields' => array(
			'image'                => krm_f( 'image', __( 'Header image (and image for items without a featured image)', 'kingdom-revival' ), 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-38-scaled.jpg' ),
			'sermons_heading'      => krm_f( 'text', __( 'Sermons: heading', 'kingdom-revival' ), 'Sermons' ),
			'sermons_intro'        => krm_f( 'textarea', __( 'Sermons: introduction', 'kingdom-revival' ), '' ),
			'sermons_tag'          => krm_f( 'text', __( 'Sermons: label on cards without a series', 'kingdom-revival' ), 'Sermon' ),
			'sermons_more'         => krm_f( 'text', __( 'Sermons: card link text', 'kingdom-revival' ), 'Watch or read' ),
			'sermons_empty'        => krm_f( 'text', __( 'Sermons: text when there are none', 'kingdom-revival' ), 'No sermons have been published yet.' ),
			'events_heading'       => krm_f( 'text', __( 'Events: heading', 'kingdom-revival' ), 'Events' ),
			'events_intro'         => krm_f( 'textarea', __( 'Events: introduction', 'kingdom-revival' ), '' ),
			'events_tag'           => krm_f( 'text', __( 'Events: label on upcoming events', 'kingdom-revival' ), 'Upcoming' ),
			'events_past_tag'      => krm_f( 'text', __( 'Events: label on past events', 'kingdom-revival' ), 'Past event' ),
			'events_past_heading'  => krm_f( 'text', __( 'Events: past events heading', 'kingdom-revival' ), 'Past events' ),
			'events_more'          => krm_f( 'text', __( 'Events: card link text', 'kingdom-revival' ), 'Event details' ),
			'events_empty'         => krm_f( 'text', __( 'Events: text when none are coming up', 'kingdom-revival' ), 'There are no upcoming events at the moment.' ),
			'testimonials_heading' => krm_f( 'text', __( 'Testimonials: heading', 'kingdom-revival' ), 'Testimonials' ),
			'testimonials_intro'   => krm_f( 'textarea', __( 'Testimonials: introduction', 'kingdom-revival' ), '' ),
			'testimonials_empty'   => krm_f( 'text', __( 'Testimonials: text when there are none', 'kingdom-revival' ), 'No testimonials have been published yet.' ),
		),
	),
	'notfound'      => array(
		'label'  => __( 'Page not found (404)', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Page not found' ),
			'heading'       => krm_f( 'text', __( 'Heading', 'kingdom-revival' ), 'We couldn’t find that page' ),
			'text'          => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'It may have moved. Explore our ministries or read the latest stories from the field.' ),
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'Browse ministries' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '{home}#all-ministries' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'See all stories' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{blog}' ),
		),
	),
);
