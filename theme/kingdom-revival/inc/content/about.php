<?php
/**
 * About Us page content (page-templates/about.php), in the order of the client's sitemap: Who we are (with the
 * ministry history), Our story, Our journey (timeline), The journey continues, Vision & Mission, What drives us (What we are
 * here to do, 1.26.0), What we believe (Statement of Faith), Founder testimony, (unused: ministries and impact numbers), Meet the team, Shepherding the Flock, Join the mission.
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
	'ourstory' => array(
		'label'  => __( 'Our story', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'    => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Our story' ),
			'heading'    => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Born from a Calling. Driven by a Mission. Sustained by Faith.' ),
			'lead'       => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Since 2018, carrying the hope of Jesus Christ to the forgotten, the oppressed, and the lost.' ),
			'text'       => krm_f( 'textarea', __( 'Story', 'kingdom-revival' ), "Every great mission begins with a burden. For Kingdom Revival Ministries, that burden was to see people encounter the transforming love of Jesus Christ, hear the Gospel, and experience the hope that only He can give.\n\nFounded in Pakistan in 2018, Kingdom Revival Ministries began with a simple conviction: the Gospel must reach beyond church walls and touch the lives of people who are often overlooked, forgotten, and suffering.\n\nWhat began as a step of faith became a commitment to serve God’s Kingdom through evangelism, prayer, discipleship, and compassion. Our heart has always been to reach people wherever they are — in villages, brick-kiln communities, struggling families, and places where the message of hope is desperately needed.\n\nOver the years, our mission has embraced both the spiritual and practical needs of people. We have carried the Gospel to communities, stood alongside persecuted Christians, extended support to vulnerable families, shared food with those in need, and developed a growing burden for children and families trapped in the bondage of slavery.\n\nWe believe that every soul matters to God. Behind every struggling family is a story. Behind every persecuted believer is a testimony of faith. Behind every child longing for a better future is a life worthy of love, dignity, and opportunity.\n\nOur calling is not simply to establish a ministry, but to serve people, proclaim Christ, strengthen His Church, and bring hope to those living in difficult circumstances.\n\nThe journey has not been without challenges. There have been seasons of limited resources, difficult circumstances, and sacrifices. Yet, through every season, we have continued to trust God’s faithfulness and remain committed to the mission He has placed before us.\n\nToday, Kingdom Revival Ministries continues to serve with an unwavering vision: to preach Christ, reach the unreached, stand with the persecuted, help the oppressed find freedom, and demonstrate the love of God through meaningful action.\n\nWe are not finished. The mission continues, the harvest is plentiful, and there are still lives waiting to hear the Good News.", __( 'Leave a blank line between paragraphs. The first paragraph is shown slightly larger.', 'kingdom-revival' ) ),
			'closing'    => krm_f( 'text', __( 'Closing line', 'kingdom-revival' ), 'This is our story. This is our calling. And by God’s grace, this is only the beginning.' ),
			'verse'      => krm_f( 'textarea', __( 'Scripture', 'kingdom-revival' ), '“For we are God’s handiwork, created in Christ Jesus to do good works, which God prepared in advance for us to do.”' ),
			'verse_cite' => krm_f( 'text', __( 'Scripture reference', 'kingdom-revival' ), 'Ephesians 2:10' ),
		),
	),
	'journey' => array(
		'label'  => __( 'Our journey (timeline)', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'  => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Our journey' ),
			'heading'  => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Eight Years of Faith and Service' ),
			'years'    => krm_f( 'text', __( 'Years (shown beside the heading)', 'kingdom-revival' ), '2018–2026' ),
			'intro'    => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Every year represents another chapter in a journey of faith. Through changing seasons and new opportunities, our commitment to the Gospel and the people we serve remains unchanged.' ),
			'timeline' => krm_list_f(
				__( 'Timeline', 'kingdom-revival' ),
				__( 'Milestone', 'kingdom-revival' ),
				array(
					'year'  => krm_f( 'text', __( 'Year', 'kingdom-revival' ) ),
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
				),
				array(
					array( 'year' => '2018', 'title' => 'The Beginning of a Calling', 'text' => 'A vision was born in Pakistan. Kingdom Revival Ministries began with a passion for souls, a burden for evangelism, and a desire to make Jesus Christ known.' ),
					array( 'year' => '2019', 'title' => 'Rooted in Faith', 'text' => 'Building upon the original vision, the ministry continued to embrace prayer, faith, and the calling to share the Gospel and encourage people to walk with Christ.' ),
					array( 'year' => '2020', 'title' => 'Hope in Difficult Seasons', 'text' => 'Even in uncertain and challenging times, the message of Jesus remained our hope. Our commitment to prayer, faith, and the Great Commission continued.' ),
					array( 'year' => '2021', 'title' => 'A Heart for the Forgotten', 'text' => 'Our vision continued to focus on people often overlooked by society — vulnerable families, struggling communities, and those longing for hope and encouragement.' ),
					array( 'year' => '2022', 'title' => 'Standing with the Persecuted', 'text' => 'The burden to encourage and stand alongside persecuted believers remained an important part of our calling, remembering those who suffer for the name of Jesus.' ),
					array( 'year' => '2023', 'title' => 'Love Beyond Words', 'text' => 'Our commitment to demonstrate the love of Christ through compassion and practical support continued, with a growing heart for families and communities facing hardship.' ),
					array( 'year' => '2024', 'title' => 'Strengthening God’s People', 'text' => 'Our vision for a strengthened Church continued through spiritual encouragement, fellowship, biblical teaching, and a commitment to equipping believers for service.' ),
					array( 'year' => '2025', 'title' => 'A Growing Vision of Freedom and Compassion', 'text' => 'The mission continued to embrace the needs of oppressed and vulnerable communities, including brick-kiln families, persecuted Christians, and children in need of care and support.' ),
					array( 'year' => '2026', 'title' => 'Moving Forward with Greater Purpose', 'text' => 'Today, we continue to pursue the calling with renewed faith and determination. Through Gospel outreach, baptisms, leadership gatherings, humanitarian assistance, and our commitment to freedom for enslaved families, we seek to make the love of Jesus visible in people’s lives.' ),
				)
			),
		),
	),
	'continues' => array(
		'label'  => __( 'The journey continues', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'The journey continues' ),
			'heading'       => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'The Best Is Still Ahead' ),
			'text'          => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), "Eight years have passed, but our burden for souls has not changed.\n\nWe dream of a Pakistan where the Gospel reaches every community, persecuted believers are strengthened, enslaved families experience freedom, vulnerable children find hope, and local churches become places of transformation.\n\nWe cannot do this alone. We believe God calls His people to stand together in faith, love, and service.", __( 'Leave a blank line between paragraphs.', 'kingdom-revival' ) ),
			'call'          => krm_f( 'text', __( 'Call to action', 'kingdom-revival' ), 'Be part of the story. Stand with the mission. Help us carry the hope of Jesus Christ to those who need it most.' ),
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'Partner with us' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '{partner}' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'Support the mission' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{donate}' ),
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
			'history'      => krm_f( 'textarea', __( 'Ministry history: text', 'kingdom-revival' ), "Kingdom Revival Ministries began in 2018 in Faisalabad with a simple calling: to take the Gospel to villages and families that others had passed by.\n\nWhat started as prayer meetings and village outreach grew into a ministry of church planting, Bible distribution and leadership training — and into practical care for brick kiln families, children, widows and the sick.\n\nToday we serve communities across Faisalabad, Jhang and the surrounding districts, working alongside local pastors and partners who share the same vision of revival.", __( 'Leave a blank line between paragraphs.', 'kingdom-revival' ) ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Explore our ministries' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{ministries}' ),
		),
	),
	'mission' => array(
		'label'  => __( 'Vision & Mission', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'         => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Vision & Mission' ),
			'heading'         => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Why we exist' ),
			'mission_eyebrow' => krm_f( 'text', __( 'Mission: small heading', 'kingdom-revival' ), 'Our mission' ),
			'mission_heading' => krm_f( 'html', __( 'Mission: heading', 'kingdom-revival' ), 'Make disciples. Serve the vulnerable.' ),
			'mission_text'    => krm_f( 'textarea', __( 'Mission: text', 'kingdom-revival' ), 'To glorify God by proclaiming the Gospel, planting churches, equipping leaders and showing Christ’s love through practical care.' ),
			'vision_eyebrow'  => krm_f( 'text', __( 'Vision: small heading', 'kingdom-revival' ), 'Our vision' ),
			'vision_heading'  => krm_f( 'html', __( 'Vision: heading', 'kingdom-revival' ), 'Revival that reaches every village' ),
			'vision_text'     => krm_f( 'textarea', __( 'Vision: text', 'kingdom-revival' ), 'Communities across Pakistan transformed by the Gospel — with thriving local churches, freed families and children who grow up knowing Jesus.' ),
		),
	),
	'drives'  => array(
		'label'  => __( 'What drives us (What we are here to do)', 'kingdom-revival' ),
		'help'   => __( 'Shown after Vision & Mission: one row per action, a one-word heading and a short line. Delete every row to hide the section.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'What drives us' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'What we are here to do' ),
			'lead'    => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Not just plans on paper. Each of these is work KRM is doing on the ground in Pakistan today.' ),
			'items'   => krm_list_f(
				__( 'Rows', 'kingdom-revival' ),
				__( 'Row', 'kingdom-revival' ),
				array(
					'title' => krm_f( 'text', __( 'Heading (one word works best)', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
				),
				array(
					array( 'title' => 'Preach', 'text' => 'Sharing the Gospel of Jesus Christ in villages where His name has rarely been heard.' ),
					array( 'title' => 'Reach', 'text' => 'Going to unreached communities across Punjab and Sindh, even the most remote ones.' ),
					array( 'title' => 'Rescue', 'text' => 'Setting brick kiln families free from bonded labour and debt.' ),
					array( 'title' => 'Provide', 'text' => 'Bringing clean, safe drinking water through hand pumps and filters.' ),
					array( 'title' => 'Feed', 'text' => 'Supporting hungry families, widows and households with no income.' ),
					array( 'title' => 'Show', 'text' => 'Sharing the life of Jesus through film screenings in villages.' ),
					array( 'title' => 'Empower', 'text' => 'Helping women and young people learn a skill and earn their own living.' ),
					array( 'title' => 'Distribute', 'text' => 'Placing Urdu Bibles and Christian literature, printed and audio, in the hands of new believers and children.' ),
					array( 'title' => 'Heal', 'text' => 'Holding medical camps and providing health care where there is no clinic.' ),
				)
			),
		),
	),
	'founder' => array(
		'label'  => __( 'Founder testimony', 'kingdom-revival' ),
		'fields' => array(
			'image'        => krm_f( 'image', __( 'Photo', 'kingdom-revival' ), 'krm-theme:images/content/pastor-adil-arif-portrait.jpg' ),
			'image_alt'    => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ), 'Pastor Adil Arif, founder of Kingdom Revival Ministries' ),
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Founder & Pastor' ),
			'heading'      => krm_f( 'html', __( 'Name', 'kingdom-revival' ), 'Pastor Adil Arif' ),
			'text'         => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Pastor Adil leads Kingdom Revival Ministries in evangelism, discipleship and church ministry, with a heart for children and youth — and for practical care among villages, marginalised communities and brick kiln families.' ),
			'quote'        => krm_f( 'textarea', __( 'Quote', 'kingdom-revival' ), '“Go therefore and make disciples of all nations.”' ),
			'cite'         => krm_f( 'text', __( 'Quote source', 'kingdom-revival' ), 'Matthew 28:19' ),
			'testimony_heading' => krm_f( 'text', __( 'Testimony: heading', 'kingdom-revival' ), 'From a vision to a mission' ),
			'testimony'    => krm_f( 'textarea', __( 'Testimony: text', 'kingdom-revival' ), "Pastor Adil’s calling began in the villages of Punjab. Seeing the poverty and spiritual hunger in the villages around him, he began sharing the Gospel from house to house and praying with families at the brick kilns.\n\nThat burden became Kingdom Revival Ministries. Today he leads a growing team of pastors and volunteers, and still spends much of his week in the villages — preaching, praying and making sure practical help reaches the families who need it most.", __( 'Leave a blank line between paragraphs.', 'kingdom-revival' ) ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Contact Pastor Adil' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{email}' ),
		),
	),
	'beliefs' => array_merge(
		krm_faq_section( 'What we believe', 'Statement of Faith', array(
			array( 'The Bible is the inspired Word of God and our final authority.', 'Scripture is God-breathed, true and sufficient for faith and life, and it is the standard by which we test all teaching. (2 Timothy 3:16–17; Psalm 119:105)' ),
			array( 'One God, eternally existing as Father, Son and Holy Spirit.', 'There is one true God, who has revealed Himself as Father, Son and Holy Spirit — equal in nature, power and glory. (Deuteronomy 6:4; Matthew 28:19)' ),
			array( 'Jesus Christ is fully God and fully man; He died for our sins and rose again.', 'Jesus was born of the virgin Mary, lived a sinless life, died on the cross for our sins and rose bodily from the dead. (John 1:1–14; 1 Corinthians 15:3–4)' ),
			array( 'Salvation is by grace alone, through faith in Jesus Christ alone.', 'We are saved not by our own works but by God’s grace, received through faith in Jesus Christ. (Ephesians 2:8–9; Romans 10:9)' ),
			array( 'The Holy Spirit lives in every believer and empowers the Church.', 'The Holy Spirit makes us new, lives in every believer and gives power and gifts to serve and witness. (John 14:16–17; Acts 1:8)' ),
			array( 'Jesus Christ will return; we are called to make disciples of all nations.', 'Jesus will come again in glory; until then the Church is sent to make disciples in every nation. (Acts 1:11; Matthew 28:19–20)' ),
		) ),
		array( 'label' => __( 'What we believe (Statement of Faith)', 'kingdom-revival' ) )
	),
	'accountability' => array(
		'label'  => __( 'Registration & accountability', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Registration & accountability' ),
			'heading'      => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Serving with integrity' ),
			'intro'        => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'We are committed to responsible leadership, clear governance and faithful stewardship.' ),
			'reg_number'   => krm_f( 'text', __( 'Registration number', 'kingdom-revival' ), '' ),
			'board'        => krm_list_f(
				__( 'Board members', 'kingdom-revival' ),
				__( 'Board member', 'kingdom-revival' ),
				array( 'name' => krm_f( 'text', __( 'Name', 'kingdom-revival' ) ), 'role' => krm_f( 'text', __( 'Role', 'kingdom-revival' ) ) ),
				array(
					array( 'name' => 'Pastor Adil Arif', 'role' => 'Chairman' ),
					array( 'name' => 'Imran Bhatti', 'role' => 'Secretary' ),
					array( 'name' => 'Sarah Masih', 'role' => 'Treasurer' ),
				)
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
					'email' => krm_f( 'text', __( 'Email (empty = "Email the team" to the site email)', 'kingdom-revival' ) ),
					'bio'   => krm_f( 'textarea', __( 'Short bio', 'kingdom-revival' ) ),
				),
				array(
					array(
						'photo' => 'krm-theme:images/content/team-sajid-arif.jpg',
						'name'  => 'Sajid Arif',
						'role'  => 'Co-founder',
					),
					array(
						'photo' => 'krm-theme:images/content/team-aber-sarwar.jpg',
						'name'  => 'Aber Sarwar',
						'role'  => 'Administrator',
					),
					array(
						'photo' => 'krm-theme:images/content/team-samia-arif.jpg',
						'name'  => 'Samia Arif',
						'role'  => 'International Relations Coordinator',
					),
					array(
						'photo' => 'krm-theme:images/content/team-sehrish-gill.jpg',
						'name'  => 'Sehrish Gill',
						'role'  => 'Projects Coordinator',
					),
				)
			),
			'flock_heading' => krm_f( 'html', __( 'Pastors: heading', 'kingdom-revival' ), 'Shepherding the Flock' ),
			'flock_intro'   => krm_f( 'textarea', __( 'Pastors: introduction', 'kingdom-revival' ), 'Our village pastors are the heart of the ministry. Each one leads a local or house church, cares for families week by week and is trained and supported by Kingdom Revival.' ),
			'flock'         => krm_list_f(
				__( 'Pastors', 'kingdom-revival' ),
				__( 'Pastor', 'kingdom-revival' ),
				array(
					'photo' => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'name'  => krm_f( 'text', __( 'Name', 'kingdom-revival' ) ),
					'bio'   => krm_f( 'textarea', __( 'Description', 'kingdom-revival' ) ),
				),
				array(
					array( 'photo' => '', 'name' => 'Pastor Javed Masih', 'bio' => 'Leads a house church near Faisalabad that began with four families and now gathers more than forty people.' ),
					array( 'photo' => '', 'name' => 'Pastor Arshad Gill', 'bio' => 'Serves brick kiln families in Jhang district and runs a weekly children’s Bible club.' ),
					array( 'photo' => '', 'name' => 'Pastor Naveed Sadiq', 'bio' => 'Planted two village churches and helps train new leaders in discipleship.' ),
					array( 'photo' => '', 'name' => 'Pastor Shahid Yousaf', 'bio' => 'Cares for widows and the sick in his village and organises our medical camp visits there.' ),
					array( 'photo' => '', 'name' => 'Pastor Emmanuel Bashir', 'bio' => 'Leads youth outreach and Sunday worship in a growing congregation near Toba Tek Singh.' ),
				)
			),
		),
	),
	'join'    => array(
		'label'  => __( 'Join the mission', 'kingdom-revival' ),
		'fields' => array(
			'heading'       => krm_f( 'text', __( 'Heading', 'kingdom-revival' ), 'Join the mission' ),
			'band_photo'    => krm_f( 'image', __( 'Background photo (seen through the navy overlay; empty = plain colour)', 'kingdom-revival' ), 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-9-scaled.jpg' ),
			'text'          => krm_f( 'textarea', __( 'Description', 'kingdom-revival' ), 'Pray with us, give, or serve alongside us as the Gospel reaches villages, kilns and homes across Pakistan.', __( 'DRAFT — CLIENT TO APPROVE: proposed wording, written only from text already on the site or in the client’s sitemap.', 'kingdom-revival' ) ),
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'Partner With Us' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '{partner}' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'Contact Us' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{contact}' ),
		),
	),
);
