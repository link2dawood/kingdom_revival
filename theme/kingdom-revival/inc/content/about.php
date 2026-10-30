<?php
/**
 * About page content (page-templates/about.php). Defaults are the approved design's content.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_up = 'https://kingdomrevivalministries.com/wp-content/uploads/';

return array(
	'hero'    => array(
		'label'  => __( 'Hero', 'kingdom-revival' ),
		'fields' => array(
			'image'         => krm_f( 'image', __( 'Background image', 'kingdom-revival' ), $krm_up . '2026/08/Leadership-and-Discipleship-11-scaled.jpg' ),
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'About Us' ),
			'title'         => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'A Gospel-centred ministry serving <em>Pakistan’s forgotten</em>' ),
			'lead'          => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'We preach Jesus Christ, plant churches, equip leaders and care for orphans, widows and brick kiln families — so that the forgotten know they are loved by God.' ),
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'Partner with us' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '{partner}' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'Meet our founder' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '#founder' ),
		),
	),
	'story'   => array(
		'label'  => __( 'Who we are', 'kingdom-revival' ),
		'fields' => array(
			'image1'       => krm_f( 'image', __( 'Photo 1 (large)', 'kingdom-revival' ), $krm_up . '2026/08/Orphanages-care-16-scaled-e1787819076312.jpg' ),
			'image1_alt'   => krm_f( 'text', __( 'Photo 1 description (alt text)', 'kingdom-revival' ), 'Children in our orphan care ministry' ),
			'image2'       => krm_f( 'image', __( 'Photo 2', 'kingdom-revival' ), $krm_up . '2026/08/Leadership-and-Discipleship-9-scaled.jpg' ),
			'image2_alt'   => krm_f( 'text', __( 'Photo 2 description (alt text)', 'kingdom-revival' ), 'Leadership and discipleship gathering' ),
			'image3'       => krm_f( 'image', __( 'Photo 3', 'kingdom-revival' ), $krm_up . '2026/07/Baptisms-31.jpg' ),
			'image3_alt'   => krm_f( 'text', __( 'Photo 3 description (alt text)', 'kingdom-revival' ), 'New believers at a baptism' ),
			'badge_number' => krm_f( 'text', __( 'Badge number', 'kingdom-revival' ), '12' ),
			'badge_text'   => krm_f( 'text', __( 'Badge text', 'kingdom-revival' ), 'ministries, one mission' ),
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Who we are' ),
			'heading'      => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Bringing the hope of Jesus to those the world overlooks' ),
			'text'         => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), "Kingdom Revival Ministries is based in Faisalabad, Pakistan. We serve unreached villages, marginalised communities and families who live and work beside the brick kilns — many of them trapped in poverty and debt bondage.\n\nOur mission is to glorify God by making disciples of Jesus Christ, proclaiming the Gospel among unreached communities, planting churches, equipping leaders, and serving vulnerable children and brick kiln families.", __( 'Leave a blank line between paragraphs.', 'kingdom-revival' ) ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Explore our ministries' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{home}#all-ministries' ),
		),
	),
	'mission' => array(
		'label'  => __( 'Mission and vision', 'kingdom-revival' ),
		'fields' => array(
			'mission_eyebrow' => krm_f( 'text', __( 'Mission: small heading', 'kingdom-revival' ), 'Our mission' ),
			'mission_heading' => krm_f( 'html', __( 'Mission: heading', 'kingdom-revival' ), 'Make disciples. Serve the vulnerable.' ),
			'mission_text'    => krm_f( 'textarea', __( 'Mission: text', 'kingdom-revival' ), 'To glorify God by proclaiming the Gospel, planting churches, equipping leaders and showing Christ’s love through practical care.' ),
			'vision_eyebrow'  => krm_f( 'text', __( 'Vision: small heading', 'kingdom-revival' ), 'Our vision' ),
			'vision_heading'  => krm_f( 'html', __( 'Vision: heading', 'kingdom-revival' ), 'Revival that reaches every village' ),
			'vision_text'     => krm_f( 'textarea', __( 'Vision: text', 'kingdom-revival' ), 'Communities across Pakistan transformed by the Gospel — with thriving local churches, freed families and children who grow up knowing Jesus.' ),
		),
	),
	'work'    => array(
		'label'  => __( 'What we do', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'What we do' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Word and deed, hand in hand' ),
			'intro'   => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Everything we do flows from the Gospel and is expressed through practical love.' ),
			'cards'   => krm_list_f(
				__( 'Cards', 'kingdom-revival' ),
				__( 'Card', 'kingdom-revival' ),
				array(
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
				),
				array(
					array(
						'title' => 'Proclaim the Gospel',
						'text'  => 'Taking the good news of Jesus Christ to villages and unreached communities across Pakistan.',
					),
					array(
						'title' => 'Plant churches',
						'text'  => 'Planting and strengthening local and house churches where believers can gather and grow.',
					),
					array(
						'title' => 'Equip leaders',
						'text'  => 'Discipling believers and training pastors and leaders to shepherd their communities.',
					),
					array(
						'title' => 'Care for the vulnerable',
						'text'  => 'Serving orphans, widows and brick kiln families with education, aid and love.',
					),
				),
				__( 'Icons follow the card order: globe, church, people, heart.', 'kingdom-revival' )
			),
		),
	),
	'founder' => array(
		'label'  => __( 'Founder', 'kingdom-revival' ),
		'fields' => array(
			'image'        => krm_f( 'image', __( 'Photo', 'kingdom-revival' ), $krm_up . '2026/08/file_00000000b46471f484befac1dc0ee291.png' ),
			'image_alt'    => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ), 'Pastor Adil Arif, founder of Kingdom Revival Ministries' ),
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Founder & Pastor' ),
			'heading'      => krm_f( 'html', __( 'Name', 'kingdom-revival' ), 'Pastor Adil Arif' ),
			'text'         => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Pastor Adil leads Kingdom Revival Ministries in evangelism, discipleship and church ministry, with a heart for children and youth — and for practical care among villages, marginalised communities and brick kiln families.' ),
			'quote'        => krm_f( 'textarea', __( 'Quote', 'kingdom-revival' ), '“Go therefore and make disciples of all nations.”' ),
			'cite'         => krm_f( 'text', __( 'Quote source', 'kingdom-revival' ), 'Matthew 28:19' ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Contact Pastor Adil' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{email}' ),
		),
	),
	'ways'    => array(
		'label'  => __( 'Get involved', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Get involved' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Three ways to stand with us' ),
			'items'   => krm_list_f(
				__( 'Ways', 'kingdom-revival' ),
				__( 'Way', 'kingdom-revival' ),
				array(
					'title'      => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'       => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
					'link_label' => krm_f( 'text', __( 'Link text', 'kingdom-revival' ) ),
					'link_url'   => krm_f( 'url', __( 'Link', 'kingdom-revival' ) ),
				),
				array(
					array(
						'title'      => 'Pray',
						'text'       => 'Intercede for the ministry, the families we serve, and the power of the Gospel.',
						'link_label' => 'Send a prayer request',
						'link_url'   => '{contact}',
					),
					array(
						'title'      => 'Give',
						'text'       => 'Provide Bibles, education, orphan care and emergency aid for families in crisis.',
						'link_label' => 'Become a partner',
						'link_url'   => '{partner}',
					),
					array(
						'title'      => 'Go',
						'text'       => 'Support outreach, church planting and humanitarian work on the ground.',
						'link_label' => 'Get in touch',
						'link_url'   => '{contact}',
					),
				),
				__( 'Numbers are added automatically. Icons follow the order: cross, heart, globe.', 'kingdom-revival' )
			),
		),
	),
	'gallery' => array(
		'label'  => __( 'Photo gallery', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'In the field' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Moments from our ministry' ),
			'tip'     => krm_f( 'text', __( 'Tip above the photos', 'kingdom-revival' ), 'Tap any photo to view it full screen and zoom in.' ),
			'photos'  => krm_list_f(
				__( 'Photos', 'kingdom-revival' ),
				__( 'Photo', 'kingdom-revival' ),
				array(
					'image'     => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'image_alt' => krm_f( 'text', __( 'Description (alt text)', 'kingdom-revival' ) ),
				),
				array(
					array(
						'image'     => $krm_up . '2026/08/Bricks-kiln-slaves-children-39-scaled.jpg',
						'image_alt' => 'Children from a brick kiln community',
					),
					array(
						'image'     => $krm_up . '2026/07/Baptisms-29.jpg',
						'image_alt' => 'A baptism service',
					),
					array(
						'image'     => $krm_up . '2026/08/Orphanages-care-20.jpg',
						'image_alt' => 'Children in orphan care',
					),
					array(
						'image'     => $krm_up . '2026/07/Urdu-Bible-Distribution-18-scaled.jpg',
						'image_alt' => 'Urdu Bible distribution',
					),
					array(
						'image'     => $krm_up . '2026/08/Widows-ministry-5-scaled.jpg',
						'image_alt' => 'Widows ministry gathering',
					),
					array(
						'image'     => $krm_up . '2026/08/Leadership-and-Discipleship-11-scaled.jpg',
						'image_alt' => 'Leadership and discipleship training',
					),
					array(
						'image'     => $krm_up . '2026/08/Deliverance-and-Healing-ministry-10.jpg',
						'image_alt' => 'Prayer for the sick',
					),
					array(
						'image'     => $krm_up . '2026/08/Bricks-kiln-slaves-children-40-scaled.jpg',
						'image_alt' => 'Brick kiln children',
					),
				)
			),
		),
	),
	'give'    => krm_give_band_section(
		array(
			'image'   => $krm_up . '2026/08/Bricks-kiln-slaves-children-40-scaled.jpg',
			'eyebrow' => 'Become a partner',
			'heading' => 'Your gift carries the Gospel further',
			'text'    => 'Every gift helps us preach Christ, plant churches and care for orphans, widows and brick kiln families across Pakistan.',
			'points'  => "Bibles for new believers and house churches\nEducation and care for vulnerable children\nPractical help for widows and families in crisis",
			'card'    => 'Give to the mission',
		)
	),
);
