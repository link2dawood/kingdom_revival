<?php
/**
 * About Us page content (page-templates/about.php), in the order of the client's sitemap: Who we are (with the
 * ministry history), Founder testimony (timeline), Vision & Mission, What we believe (Statement of Faith), What we are
 * here to do (ministries and impact numbers), Meet the team, Shepherding the Flock, Join the mission.
 * Defaults are the approved design's wording; [bracketed] text waits for the client's approved wording (the ministry
 * history must not be copied from hmpak.org or gsministries.org without the client's approval).
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
			'title'         => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'A Gospel-Centred Ministry Serving Pakistan’s Forgotten' ),
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
			'badge_number' => krm_f( 'text', __( 'Badge number (empty: the number of published ministries)', 'kingdom-revival' ), '' ),
			'badge_text'   => krm_f( 'text', __( 'Badge text', 'kingdom-revival' ), 'ministries, one mission' ),
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Who we are' ),
			'heading'      => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Bringing the hope of Jesus to those the world overlooks' ),
			'text'         => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), "Kingdom Revival Ministries is based in Faisalabad, Pakistan. We serve unreached villages, marginalised communities and families who live and work beside the brick kilns — many of them trapped in poverty and debt bondage.\n\nOur mission is to glorify God by making disciples of Jesus Christ, proclaiming the Gospel among unreached communities, planting churches, equipping leaders, and serving vulnerable children and brick kiln families.", __( 'Leave a blank line between paragraphs.', 'kingdom-revival' ) ),
			'history_heading' => krm_f( 'text', __( 'Ministry history: heading', 'kingdom-revival' ), 'Our history' ),
			'history'      => krm_f( 'textarea', __( 'Ministry history: text', 'kingdom-revival' ), '[Ministry history — approved wording to be supplied by the client]', __( 'Leave a blank line between paragraphs.', 'kingdom-revival' ) ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Explore our ministries' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{ministries}' ),
		),
	),
	'mission' => array(
		'label'  => __( 'Vision & Mission', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'         => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Vision & Mission' ),
			'heading'         => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'What we are here to do' ),
			'mission_eyebrow' => krm_f( 'text', __( 'Mission: small heading', 'kingdom-revival' ), 'Our mission' ),
			'mission_heading' => krm_f( 'html', __( 'Mission: heading', 'kingdom-revival' ), 'Make disciples. Serve the vulnerable.' ),
			'mission_text'    => krm_f( 'textarea', __( 'Mission: text', 'kingdom-revival' ), 'To glorify God by proclaiming the Gospel, planting churches, equipping leaders and showing Christ’s love through practical care.' ),
			'vision_eyebrow'  => krm_f( 'text', __( 'Vision: small heading', 'kingdom-revival' ), 'Our vision' ),
			'vision_heading'  => krm_f( 'html', __( 'Vision: heading', 'kingdom-revival' ), 'Revival that reaches every village' ),
			'vision_text'     => krm_f( 'textarea', __( 'Vision: text', 'kingdom-revival' ), 'Communities across Pakistan transformed by the Gospel — with thriving local churches, freed families and children who grow up knowing Jesus.' ),
		),
	),
	'founder' => array(
		'label'  => __( 'Founder testimony', 'kingdom-revival' ),
		'fields' => array(
			'image'        => krm_f( 'image', __( 'Photo', 'kingdom-revival' ), $krm_up . '2026/08/file_00000000b46471f484befac1dc0ee291.png' ),
			'image_alt'    => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ), 'Pastor Adil Arif, founder of Kingdom Revival Ministries' ),
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Founder & Pastor' ),
			'heading'      => krm_f( 'html', __( 'Name', 'kingdom-revival' ), 'Pastor Adil Arif' ),
			'text'         => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Pastor Adil leads Kingdom Revival Ministries in evangelism, discipleship and church ministry, with a heart for children and youth — and for practical care among villages, marginalised communities and brick kiln families.' ),
			'quote'        => krm_f( 'textarea', __( 'Quote', 'kingdom-revival' ), '“Go therefore and make disciples of all nations.”' ),
			'cite'         => krm_f( 'text', __( 'Quote source', 'kingdom-revival' ), 'Matthew 28:19' ),
			'testimony_heading' => krm_f( 'text', __( 'Testimony: heading', 'kingdom-revival' ), 'From a vision to a mission' ),
			'testimony'    => krm_f( 'textarea', __( 'Testimony: text', 'kingdom-revival' ), '[Founder’s testimony — to be supplied by the client]', __( 'Leave a blank line between paragraphs.', 'kingdom-revival' ) ),
			'timeline'     => krm_list_f(
				__( 'Timeline', 'kingdom-revival' ),
				__( 'Milestone', 'kingdom-revival' ),
				array(
					'year'  => krm_f( 'text', __( 'Year', 'kingdom-revival' ) ),
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
				),
				array(
					array(
						'year'  => '[Year]',
						'title' => '[Milestone — to be supplied by the client]',
						'text'  => '[Short description]',
					),
					array(
						'year'  => '[Year]',
						'title' => '[Milestone — to be supplied by the client]',
						'text'  => '[Short description]',
					),
					array(
						'year'  => '[Year]',
						'title' => '[Milestone — to be supplied by the client]',
						'text'  => '[Short description]',
					),
				)
			),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Contact Pastor Adil' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{email}' ),
		),
	),
	'beliefs' => array_merge(
		krm_faq_section( 'What we believe', 'Statement of Faith', array(
			array( 'The Bible is the inspired Word of God and our final authority.', '[Explanation and Scripture references — to be supplied by the client]' ),
			array( 'One God, eternally existing as Father, Son and Holy Spirit.', '[Explanation and Scripture references — to be supplied by the client]' ),
			array( 'Jesus Christ is fully God and fully man; He died for our sins and rose again.', '[Explanation and Scripture references — to be supplied by the client]' ),
			array( 'Salvation is by grace alone, through faith in Jesus Christ alone.', '[Explanation and Scripture references — to be supplied by the client]' ),
			array( 'The Holy Spirit lives in every believer and empowers the Church.', '[Explanation and Scripture references — to be supplied by the client]' ),
			array( 'Jesus Christ will return; we are called to make disciples of all nations.', '[Explanation and Scripture references — to be supplied by the client]' ),
		) ),
		array( 'label' => __( 'What we believe (Statement of Faith)', 'kingdom-revival' ) )
	),
	'accountability' => array(
		'label'  => __( 'Registration & accountability', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Registration & accountability' ),
			'heading'      => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Serving with integrity' ),
			'intro'        => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'We are committed to responsible leadership, clear governance and faithful stewardship.' ),
			'reg_number'   => krm_f( 'text', __( 'Registration number', 'kingdom-revival' ), '[Registration number — to be supplied by the client]' ),
			'board'        => krm_list_f(
				__( 'Board members', 'kingdom-revival' ),
				__( 'Board member', 'kingdom-revival' ),
				array( 'name' => krm_f( 'text', __( 'Name', 'kingdom-revival' ) ), 'role' => krm_f( 'text', __( 'Role', 'kingdom-revival' ) ) ),
				array()
			),
			'partners'     => krm_list_f(
				__( 'Partner churches and organisations', 'kingdom-revival' ),
				__( 'Partner', 'kingdom-revival' ),
				array( 'name' => krm_f( 'text', __( 'Name', 'kingdom-revival' ) ), 'logo' => krm_f( 'image', __( 'Logo', 'kingdom-revival' ) ), 'url' => krm_f( 'url', __( 'Website', 'kingdom-revival' ) ) ),
				array()
			),
		),
	),
	'work'    => array(
		'label'  => __( 'What we are here to do', 'kingdom-revival' ),
		'help'   => __( 'The two group cards list the published ministries (Ministries); the numbers are the impact numbers of Kingdom Revival → Settings.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'     => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'What we do' ),
			'heading'     => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Word and deed, hand in hand' ),
			'intro'       => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Everything we do flows from the Gospel and is expressed through practical love.' ),
			'group_more'  => krm_f( 'text', __( 'Group card link text', 'kingdom-revival' ), 'See these ministries →' ),
			'impact_more' => krm_f( 'text', __( 'Link under the numbers', 'kingdom-revival' ), 'See our impact →' ),
		),
	),
	'team'    => array(
		'label'  => __( 'Meet the team and Shepherding the Flock', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Meet the team' ),
			'heading'       => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Our staff' ),
			'main'          => krm_list_f(
				__( 'Main team', 'kingdom-revival' ),
				__( 'Person', 'kingdom-revival' ),
				array(
					'photo' => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'name'  => krm_f( 'text', __( 'Name', 'kingdom-revival' ) ),
					'role'  => krm_f( 'text', __( 'Role', 'kingdom-revival' ) ),
					'bio'   => krm_f( 'textarea', __( 'Short bio', 'kingdom-revival' ) ),
				),
				array(
					array(
						'photo' => $krm_up . '2026/08/file_00000000b46471f484befac1dc0ee291.png',
						'name'  => 'Pastor Adil Arif',
						'role'  => 'Founder & Director',
						'bio'   => 'Pastor Adil leads Kingdom Revival Ministries in evangelism, discipleship and church ministry, with a heart for children and youth — and for practical care among villages, marginalised communities and brick kiln families.',
					),
					array(
						'photo' => '',
						'name'  => '[Name]',
						'role'  => 'Projects Coordinator',
						'bio'   => '[Short bio — to be supplied by the client]',
					),
					array(
						'photo' => '',
						'name'  => '[Name]',
						'role'  => 'International Relations Coordinator',
						'bio'   => '[Short bio — to be supplied by the client]',
					),
					array(
						'photo' => '',
						'name'  => '[Name]',
						'role'  => 'Co-Founder',
						'bio'   => '[Short bio — to be supplied by the client]',
					),
				)
			),
			'flock_heading' => krm_f( 'html', __( 'Pastors: heading', 'kingdom-revival' ), 'Shepherding the Flock' ),
			'flock_intro'   => krm_f( 'textarea', __( 'Pastors: introduction', 'kingdom-revival' ), '[Introduction — to be supplied by the client]' ),
			'flock'         => krm_list_f(
				__( 'Pastors', 'kingdom-revival' ),
				__( 'Pastor', 'kingdom-revival' ),
				array(
					'photo' => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'name'  => krm_f( 'text', __( 'Name', 'kingdom-revival' ) ),
					'bio'   => krm_f( 'textarea', __( 'Description', 'kingdom-revival' ) ),
				),
				array_fill(
					0,
					5,
					array(
						'photo' => '',
						'name'  => '[Pastor’s name]',
						'bio'   => '[Description — to be supplied by the client]',
					)
				)
			),
		),
	),
	'join'    => array(
		'label'  => __( 'Join the mission', 'kingdom-revival' ),
		'fields' => array(
			'heading'       => krm_f( 'text', __( 'Heading', 'kingdom-revival' ), 'Join the mission' ),
			'text'          => krm_f( 'textarea', __( 'Description', 'kingdom-revival' ), 'Pray with us, give, or serve alongside us as the Gospel reaches villages, kilns and homes across Pakistan.', __( 'DRAFT — CLIENT TO APPROVE: proposed wording, written only from text already on the site or in the client’s sitemap.', 'kingdom-revival' ) ),
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'Partner With Us' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '{partner}' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'Contact Us' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{contact}' ),
		),
	),
);
