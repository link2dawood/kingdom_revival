<?php
/**
 * Site text (Kingdom Revival → Site text): header, footer, ministry and story pages, the All Ministries page
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
$krm_ministry_band['label']                     = __( 'Ministry pages: side donation card', 'kingdom-revival' );
$krm_ministry_band['help']                      = __( 'The donation card beside each ministry page: badge (small heading), heading, text, and the points as a note under it. The amounts are the home page donation card’s.', 'kingdom-revival' );
$krm_ministry_band['fields']['heading']['help'] = $krm_ministry;
$krm_ministry_band['fields']['text']['label']   = __( 'Text', 'kingdom-revival' );
$krm_band['label']                              = __( 'Story pages: give band', 'kingdom-revival' );

$krm_groups = array();
foreach ( krm_ministry_data()['groups'] as $krm_gslug => $krm_g ) {
	/* translators: %s: group name */
	$krm_groups[ $krm_gslug . '_image' ] = krm_f( 'image', sprintf( __( '%s: image', 'kingdom-revival' ), $krm_g['name'] ), $krm_g['image'] );
	/* translators: %s: group name */
	$krm_groups[ $krm_gslug . '_intro' ] = krm_f( 'textarea', sprintf( __( '%s: introduction', 'kingdom-revival' ), $krm_g['name'] ), $krm_g['intro'] );
}

return array(
	'header'        => array(
		'label'  => __( 'Header', 'kingdom-revival' ),
		'help'   => __( 'The logo is set in Appearance → Customize → Site Identity; the menu in Appearance → Menus.', 'kingdom-revival' ),
		'fields' => array(
			'brand_name'   => krm_f( 'text', __( 'Name beside the logo', 'kingdom-revival' ), 'Kingdom Revival' ),
			'brand_sub'    => krm_f( 'text', __( 'Small line under the name', 'kingdom-revival' ), 'MINISTRIES' ),
			'logo_alt'     => krm_f( 'text', __( 'Logo description (alt text)', 'kingdom-revival' ), 'Kingdom Revival Ministries logo' ),
			'donate_label' => krm_f( 'text', __( 'Top bar button text', 'kingdom-revival' ), 'Donate' ),
			'verse_short'  => krm_f( 'text', __( 'Top bar verse (short; empty hides it)', 'kingdom-revival' ), 'For God so loved the world, that he gave his only begotten Son...' ),
			'verse_full'   => krm_f( 'textarea', __( 'Top bar verse (full, shown on hover or tap)', 'kingdom-revival' ), 'For God so loved the world, that he gave his only begotten Son, that whosoever believeth in him should not perish, but have everlasting life.' ),
			'verse_ref'    => krm_f( 'text', __( 'Top bar verse reference', 'kingdom-revival' ), 'John 3:16' ),
		),
	),
	'footer'        => array(
		'label'  => __( 'Footer', 'kingdom-revival' ),
		'help'   => __( 'Footer links are set in Appearance → Menus; the address, phone and email in Kingdom Revival → Settings.', 'kingdom-revival' ),
		'fields' => array(
			'name'               => krm_f( 'text', __( 'Name', 'kingdom-revival' ), 'Kingdom Revival Ministries' ),
			'tagline'            => krm_f( 'textarea', __( 'Tagline', 'kingdom-revival' ), 'Bringing the hope of Jesus Christ to the forgotten, the unreached, and the vulnerable across Pakistan.' ),
			'quick_heading'      => krm_f( 'text', __( 'Links heading', 'kingdom-revival' ), 'Quick Links' ),
			'ministries_heading' => krm_f( 'text', __( 'What We Do heading', 'kingdom-revival' ), 'What We Do' ),
			'contact_heading'    => krm_f( 'text', __( 'Contact heading', 'kingdom-revival' ), 'Contact' ),
			'copyright'          => krm_f( 'text', __( 'Copyright line', 'kingdom-revival' ), '© {year} Kingdom Revival Ministries', __( '{year} is the current year.', 'kingdom-revival' ) ),
			'location'           => krm_f( 'text', __( 'Line beside the copyright', 'kingdom-revival' ), 'Faisalabad, Pakistan' ),
		),
	),
	'ministry'      => array(
		'label'  => __( 'Ministry pages', 'kingdom-revival' ),
		'help'   => __( 'Each ministry’s own title, description, focus, points and photos are edited on the ministry (Ministries → edit).', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'         => krm_f( 'text', __( 'Hero small heading (only for a ministry without a group; otherwise the group name is shown)', 'kingdom-revival' ), 'Kingdom Revival Ministries' ),
			'donate_button'   => krm_f( 'text', __( 'Hero button text', 'kingdom-revival' ), 'Donate to this ministry' ),
			'intro_eyebrow'   => krm_f( 'text', __( 'Intro: small heading', 'kingdom-revival' ), 'What we do' ),
			'serves_heading'  => krm_f( 'text', __( 'Intro: heading (above the ministry’s points)', 'kingdom-revival' ), 'How this ministry serves' ),
			'stats_label'     => krm_f( 'text', __( 'Impact strip: label for screen readers', 'kingdom-revival' ), '{ministry} impact', $krm_ministry ),
			'stats_note'      => krm_f( 'text', __( 'Impact strip: text in the navy panel', 'kingdom-revival' ), 'Our prayer: lives transformed by Christ' ),
			'stats_note_sub'  => krm_f( 'text', __( 'Impact strip: small line in the navy panel', 'kingdom-revival' ), 'Across Pakistan and beyond' ),
			'gifts_heading'   => krm_f( 'text', __( 'How your gift helps: heading', 'kingdom-revival' ), 'How your gift helps' ),
			'gifts_text'      => krm_f( 'text', __( 'How your gift helps: text beside the heading', 'kingdom-revival' ), 'Every amount has a name and a face.' ),
			'photos_heading'  => krm_f( 'text', __( 'Photos heading', 'kingdom-revival' ), '{ministry} photos', $krm_ministry ),
			'photos_tip'      => krm_f( 'text', __( 'Photos tip', 'kingdom-revival' ), 'Tap a photo to view it full screen and zoom in.' ),
			'photos_empty'    => krm_f( 'text', __( 'Text when there are no photos', 'kingdom-revival' ), 'No photos are available for this ministry yet.' ),
			'photos_more'     => krm_f( 'text', __( 'Link to the Gallery (when a ministry has more than 9 photos)', 'kingdom-revival' ), 'See all {ministry} photos →', $krm_ministry ),
			'impact_eyebrow'  => krm_f( 'text', __( 'Ministry in action: small heading', 'kingdom-revival' ), 'The ministry in action', __( 'Section after the intro: the ministry’s latest story (News & Updates → story → Related ministry) and three photos with their Media Library descriptions as captions. Hidden when a ministry has neither.', 'kingdom-revival' ) ),
			'impact_heading'  => krm_f( 'text', __( 'Ministry in action: heading', 'kingdom-revival' ), 'What this work looks like', $krm_ministry ),
			'impact_story'    => krm_f( 'text', __( 'Ministry in action: story label', 'kingdom-revival' ), 'From the field' ),
			'impact_read'     => krm_f( 'text', __( 'Ministry in action: story link', 'kingdom-revival' ), 'Read the full story →' ),
			'impact_photos'   => krm_f( 'text', __( 'Ministry in action: photos label', 'kingdom-revival' ), 'In pictures' ),
			'story_eyebrow'   => krm_f( 'text', __( 'Story card: small heading', 'kingdom-revival' ), 'Lives changed' ),
			'story_heading'   => krm_f( 'text', __( 'Story card: heading', 'kingdom-revival' ), 'A story from the field' ),
			'story_more'      => krm_f( 'text', __( 'Story card: link to all testimonials', 'kingdom-revival' ), 'More testimonials →' ),
			'card_button'     => krm_f( 'text', __( 'Side donation card: button text (the amount follows)', 'kingdom-revival' ), 'Donate' ),
			'camps_heading'   => krm_f( 'text', __( 'Camps / projects timeline heading (shown only when a ministry has rows)', 'kingdom-revival' ), 'Camps / projects completed' ),
			'camps_people'    => krm_f( 'text', __( 'Camps / projects timeline: label before the number of people', 'kingdom-revival' ), 'People served:' ),
			'churches_heading' => krm_f( 'text', __( 'Churches list heading (shown only when a ministry has churches)', 'kingdom-revival' ), 'Churches & house churches planted' ),
			'churches_planted' => krm_f( 'text', __( 'Churches list: label before the year / type', 'kingdom-revival' ), 'Planted:' ),
			'other_eyebrow'   => krm_f( 'text', __( 'Other ministries: small heading', 'kingdom-revival' ), 'Keep exploring' ),
			'other_heading'   => krm_f( 'text', __( 'Other ministries: heading', 'kingdom-revival' ), 'Other ministries' ),
			'other_more'      => krm_f( 'text', __( 'Other ministries: link to What We Do', 'kingdom-revival' ), 'Explore What We Do →' ),
			'hero_image'      => krm_f( 'image', __( 'Hero image for ministries without photos', 'kingdom-revival' ), $krm_up . 'elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg' ),
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
	'ministries'    => array(
		'label'  => __( 'What We Do page (/what-we-do/)', 'kingdom-revival' ),
		'help'   => __( 'What We Do (/what-we-do/) shows one large card per ministry group, then a preview row of each group’s published ministries, then the home page’s closing banner. Each group has its own page (/what-we-do/{group}/) with its intro, its ministry cards (in their Order: Ministries → edit → Page Attributes), the home page’s “Your gift at work” cards, a card to the other group and the closing banner. Group images and intros: “What We Do groups” below. The labels here are shared by both kinds of page.', 'kingdom-revival' ),
		'fields' => array(
			'image'        => krm_f( 'image', __( 'Header image', 'kingdom-revival' ), $krm_up . '2026/08/Orphanages-care-19.jpg' ),
			'eyebrow'      => krm_f( 'text', __( 'Small heading (empty: none)', 'kingdom-revival' ), '' ),
			'heading'      => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'What We Do' ),
			'subheading'   => krm_f( 'html', __( 'Subheading', 'kingdom-revival' ), 'One Gospel, <em>shared in word and deed.</em>' ),
			'lead'         => krm_f( 'textarea', __( 'Introduction (also the page’s meta description)', 'kingdom-revival' ), 'Everything we do flows from the Gospel and is expressed through practical love — through our Gospel & Spiritual Ministries and our Compassion & Humanitarian Ministries, among children, families and communities across Pakistan.', __( 'DRAFT — CLIENT TO APPROVE: proposed wording, written only from text already on the site or in the client’s sitemap.', 'kingdom-revival' ) ),
			'list_heading' => krm_f( 'text', __( 'Heading above the group cards (for screen readers and search engines)', 'kingdom-revival' ), 'Our ministry groups' ),
			'count_one'    => krm_f( 'text', __( 'Group card: number of ministries, one', 'kingdom-revival' ), '{n} ministry' ),
			'count_many'   => krm_f( 'text', __( 'Group card: number of ministries ({n} = number)', 'kingdom-revival' ), '{n} ministries' ),
			'explore'      => krm_f( 'text', __( 'Group card: link text', 'kingdom-revival' ), 'Explore →' ),
			'preview_more' => krm_f( 'text', __( 'Preview row: link to the group page', 'kingdom-revival' ), 'View all →' ),
			'group_eyebrow' => krm_f( 'text', __( 'Group page: small heading above the group name', 'kingdom-revival' ), 'What We Do' ),
			'other_eyebrow' => krm_f( 'text', __( 'Group page: small heading of the card to the other group', 'kingdom-revival' ), 'Also explore' ),
			'card_button'  => krm_f( 'text', __( 'Card link text', 'kingdom-revival' ), 'Learn more →' ),
			'donate_label' => krm_f( 'text', __( 'Card button text', 'kingdom-revival' ), 'Donate Now' ),
			'raised_label' => krm_f( 'text', __( 'Card: “Raised” label', 'kingdom-revival' ), 'Raised' ),
			'goal_label'   => krm_f( 'text', __( 'Card: “Goal” label', 'kingdom-revival' ), 'Goal' ),
			'empty'        => krm_f( 'text', __( 'Text when no ministry is published', 'kingdom-revival' ), 'No ministries have been published yet.' ),
		),
	),
	'groups'        => array(
		'label'  => __( 'What We Do groups (/what-we-do/{group}/)', 'kingdom-revival' ),
		'help'   => __( 'The image and short introduction of each ministry group: its card on What We Do and its own page. An introduction in [square brackets] is a placeholder: only administrators see it; visitors see no introduction until it is replaced. The group names are edited in Ministries → Ministry Groups.', 'kingdom-revival' ),
		'fields' => $krm_groups,
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
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'Explore What We Do' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '{ministries}' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'See all stories' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{blog}' ),
		),
	),
);
