<?php
/**
 * Home page content (front-page.php and template-parts/home/*). Defaults are the approved design's content.
 * The impact numbers come from Kingdom Revival → Settings; the ministry explorer lists the published ministries.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_up     = 'https://kingdomrevivalministries.com/wp-content/uploads/';
$krm_poster = $krm_up . 'elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg';
$krm_video  = $krm_up . '2026/08/a.mp4';
$krm_hero_v = 'krm-theme:video/hero.mp4'; // The client's hero video, shipped with the theme (assets/video/hero.mp4: 15 s, 1280px, no audio, H.264).
$krm_hero_w = 'krm-theme:video/hero.webm'; // The same clip as WebM (VP9), for browsers without H.264.
$krm_hl     = __( 'Wrap highlighted words in <span style="color: #F5A04A">…</span>.', 'kingdom-revival' );

return array(
	'hero'     => array(
		'label'  => __( 'Hero and donation card', 'kingdom-revival' ),
		'fields' => array(
			'image'          => krm_f( 'image', __( 'Background image (also shown before the video plays)', 'kingdom-revival' ), $krm_poster ),
			'video'          => krm_f( 'url', __( 'Background video (MP4 link)', 'kingdom-revival' ), $krm_hero_v, __( 'Upload the video in Media → Add New and paste its link here. Leave empty to show only the image. It plays on screens 768px and wider only (not with data saver or reduced motion); phones always see the background image. Keep it short (about 15 s), without sound, under 4 MB.', 'kingdom-revival' ) ),
			'video_webm'     => krm_f( 'url', __( 'Background video, WebM version (optional)', 'kingdom-revival' ), $krm_hero_w, __( 'The same clip as WebM, for browsers that cannot play the MP4. Leave empty if you only have the MP4.', 'kingdom-revival' ) ),
			'eyebrow'        => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Bringing Hope to the Forgotten' ),
			'title'          => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Sharing the Hope of <span style="color: #F5A04A">Jesus Christ</span> with the Unreached & <span style="color: #F5A04A">Vulnerable</span>', $krm_hl ),
			'lead'           => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'We preach the Gospel, plant churches and care for brick kiln families, orphans and widows across Pakistan and beyond.' ),
			'button1_label'  => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Explore Ministries' ),
			'button1_url'    => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '#ministries' ),
			'button2_label'  => krm_f( 'text', __( 'Video link text', 'kingdom-revival' ), 'Watch Our Story' ),
			'button2_url'    => krm_f( 'url', __( 'Video link', 'kingdom-revival' ), '#field-stories' ),
			'card_badge'     => krm_f( 'text', __( 'Donation card: badge', 'kingdom-revival' ), 'MAKE AN IMPACT' ),
			'card_heading'   => krm_f( 'text', __( 'Donation card: heading', 'kingdom-revival' ), 'Help Brick Kiln Children Go to School & Know Christ' ),
			'card_text'      => krm_f( 'textarea', __( 'Donation card: text', 'kingdom-revival' ), 'Your gift provides Sunday school, education and care for children growing up beside the kilns.' ),
			'card_ministry'  => krm_f( 'text', __( 'Donation card: ministry the gift goes to', 'kingdom-revival' ), 'children-orphan-care', __( 'The ministry’s slug (Ministries → edit → permalink), e.g. children-orphan-care. The card’s Donate button opens Donate with this ministry chosen. Leave empty for “Where it is needed most”.', 'kingdom-revival' ) ),
			'card_note'      => krm_f( 'textarea', __( 'Donation card: small print', 'kingdom-revival' ), 'Every gift helps children learn, grow in faith and build a brighter future.' ),
			'amounts'        => krm_f( 'text', __( 'Amounts (US dollars)', 'kingdom-revival' ), '25, 50, 100, 250', __( 'Used by the donation card and the floating “Donate” panel. Numbers separated by commas; “Other” is always added.', 'kingdom-revival' ) ),
			'default_amount' => krm_f( 'text', __( 'Amount selected at first', 'kingdom-revival' ), '50' ),
		),
	),
	'gift'     => array(
		'label'  => __( 'Your gift at work', 'kingdom-revival' ),
		'fields' => array(
			'heading' => krm_f( 'text', __( 'Heading', 'kingdom-revival' ), 'Your gift at work' ),
			'text'    => krm_f( 'text', __( 'Text beside the heading', 'kingdom-revival' ), 'Every amount has a name and a face.' ),
			'cards'   => krm_list_f(
				__( 'Cards', 'kingdom-revival' ),
				__( 'Card', 'kingdom-revival' ),
				array(
					'amount' => krm_f( 'text', __( 'Amount', 'kingdom-revival' ) ),
					'title'  => krm_f( 'text', __( 'What it gives', 'kingdom-revival' ) ),
					'text'   => krm_f( 'text', __( 'Text', 'kingdom-revival' ) ),
					'url'    => krm_f( 'url', __( 'Link', 'kingdom-revival' ) ),
				),
				array(
					array(
						'amount' => '$10',
						'title'  => 'One Urdu Bible',
						'text'   => 'Scripture for a family or new believer.',
						'url'    => '{donate}?ministry=bible-distribution&amount=10#give',
					),
					array(
						'amount' => '$45',
						'title'  => 'Medical camp for a family',
						'text'   => 'Check-up and medicine at a kiln or village camp.',
						'url'    => '{donate}?ministry=medical-health&amount=45#give',
					),
					array(
						'amount' => '[$ ]',
						'title'  => 'A child’s schooling',
						'text'   => 'Fees, books and uniform for a kiln child.',
						'url'    => '{donate}?ministry=brick-kiln-family-care#give',
					),
					array(
						'amount' => '[$ ]',
						'title'  => 'Frees a family',
						'text'   => 'Clears a bonded family’s debt and helps them start again.',
						'url'    => '{donate}?ministry=slavery-freedom#give',
					),
				)
			),
		),
	),
	'about'    => array(
		'label'  => __( 'About', 'kingdom-revival' ),
		'fields' => array(
			'image1'       => krm_f( 'image', __( 'Large photo', 'kingdom-revival' ), $krm_up . 'elementor/thumbs/Leadership-and-Discipleship-9-scaled-rsiplqicgeaowcet38ec7199etzmpejk72qt9zqxco.jpg' ),
			'image1_alt'   => krm_f( 'text', __( 'Large photo description (alt text)', 'kingdom-revival' ), 'Leadership and discipleship ministry' ),
			'image2'       => krm_f( 'image', __( 'Small photo', 'kingdom-revival' ), $krm_up . 'elementor/thumbs/Orphanages-care-16-scaled-e1787819076312-rsij8jj20ojpz9btf5v77vd2yjnnuanqoo6mn3kqjc.jpg' ),
			'image2_alt'   => krm_f( 'text', __( 'Small photo description (alt text)', 'kingdom-revival' ), 'Orphanage care ministry' ),
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'About Us' ),
			'heading'      => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'A Gospel-Centred Ministry Serving Pakistan’s Forgotten' ),
			'checks'       => krm_f( 'textarea', __( 'Check list', 'kingdom-revival' ), "Proclaiming the Gospel to unreached communities\nPlanting churches and house churches\nCaring for orphans and brick kiln children\nDistributing Urdu Bibles to families", __( 'One point per line.', 'kingdom-revival' ) ),
			'text'         => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Our mission is to glorify God by making disciples of Jesus Christ, proclaiming the Gospel among unreached communities, planting churches, equipping leaders, and serving vulnerable children and brick kiln families.' ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'About Us' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{about}' ),
		),
	),
	'explorer' => array(
		'label'  => __( 'Ministry explorer', 'kingdom-revival' ),
		'help'   => __( 'The list shows your published ministries. Each ministry’s explorer photo and position are set on the ministry (Ministries → edit).', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'What We Do' ),
			'heading'       => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'One Gospel, shared in word and deed.' ),
			'intro'         => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Choose a ministry to see what it does and how your support reaches people.' ),
			'support_label' => krm_f( 'text', __( 'Support button text', 'kingdom-revival' ), 'Support this ministry' ),
			'support_url'   => krm_f( 'url', __( 'Support button link', 'kingdom-revival' ), '{donate}' ),
			'more_label'    => krm_f( 'text', __( 'Ministry page button text', 'kingdom-revival' ), 'Learn more' ),
			'all_label'     => krm_f( 'text', __( 'All Ministries button text', 'kingdom-revival' ), 'View All Ministries' ),
			'all_url'       => krm_f( 'url', __( 'All Ministries button link', 'kingdom-revival' ), '{ministries}' ),
		),
	),
	'projects' => array(
		'label'  => __( 'Current projects', 'kingdom-revival' ),
		'help'   => __( 'The first three current projects (Projects in the dashboard). Until a project is added, the placeholder projects show.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'  => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Our projects' ),
			'heading'  => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Current projects' ),
			'all_label' => krm_f( 'text', __( 'Link to all projects', 'kingdom-revival' ), 'See all projects →' ),
		),
	),
	'causes'   => array(
		'label'  => __( 'Giving cards', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Our Ministries' ),
			'heading'      => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'You Can Bring Hope to Many by Giving a Little' ),
			'note'         => krm_f( 'textarea', __( 'Small print on every card', 'kingdom-revival' ), 'Your gift brings practical care and lasting hope through this ministry.' ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Donate Now' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{donate}' ),
			'cards'        => krm_list_f(
				__( 'Cards', 'kingdom-revival' ),
				__( 'Card', 'kingdom-revival' ),
				array(
					'tag'   => krm_f( 'text', __( 'Label on the photo', 'kingdom-revival' ) ),
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'image' => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
				),
				array_map(
					function ( $c ) {
						return array(
							'tag'   => $c[0],
							'title' => $c[1],
							'image' => $c[2],
							'text'  => $c[3],
						);
					},
					krm_home_causes()
				)
			),
		),
	),
	'freedom'  => array(
		'label'  => __( 'Flagship: Slavery & Freedom Ministry', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Flagship ministry · Slavery & Freedom' ),
			'heading'       => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Behind every brick, a family waiting to be free.' ),
			'text'          => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Whole families labour at brick kilns under debts passed down for generations. Here is exactly how your gift brings a family out.' ),
			'steps'         => krm_list_f(
				__( 'Steps', 'kingdom-revival' ),
				__( 'Step', 'kingdom-revival' ),
				array(
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'  => krm_f( 'text', __( 'Text', 'kingdom-revival' ) ),
				),
				array(
					array(
						'title' => 'Pay the debt',
						'text'  => 'We settle the family’s debt with the kiln owner.',
					),
					array(
						'title' => 'Relocate',
						'text'  => 'The family moves to a safe home away from the kiln.',
					),
					array(
						'title' => 'Feed',
						'text'  => 'Food support while they settle into new life.',
					),
					array(
						'title' => 'Trade & skill',
						'text'  => 'Tools or training for an honest income.',
					),
					array(
						'title' => 'School',
						'text'  => 'Children enrolled in school, many for the first time.',
					),
				),
				__( 'The steps are numbered 01, 02… in this order.', 'kingdom-revival' )
			),
			'before'        => krm_f( 'image', __( 'Before photo', 'kingdom-revival' ), $krm_up . '2026/08/Bricks-kiln-slaves-children-38-scaled.jpg' ),
			'before_alt'    => krm_f( 'text', __( 'Before photo description (alt text)', 'kingdom-revival' ), 'Children of brick kiln workers at the kiln' ),
			'before_label'  => krm_f( 'text', __( 'Before photo label', 'kingdom-revival' ), 'Before · Bonded at the kiln' ),
			'after'         => krm_f( 'image', __( 'After photo', 'kingdom-revival' ), $krm_up . '2026/08/Bricks-kiln-slaves-children-39-scaled.jpg' ),
			'after_alt'     => krm_f( 'text', __( 'After photo description (alt text)', 'kingdom-revival' ), 'Brick kiln families receiving warm clothes from the ministry' ),
			'after_label'   => krm_f( 'text', __( 'After photo label', 'kingdom-revival' ), 'After · Free' ),
			'compare_label' => krm_f( 'text', __( 'Slider label', 'kingdom-revival' ), 'Drag to compare' ),
			'button_label'  => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Help free a family · [Amount]' ),
			'button_url'    => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{donate}?ministry=slavery-freedom#give' ),
		),
	),
	'flagship' => array(
		'label'  => __( 'Flagship cards (Bible Distribution, Church Planting)', 'kingdom-revival' ),
		'fields' => array(
			'cards' => krm_list_f(
				__( 'Cards', 'kingdom-revival' ),
				__( 'Card', 'kingdom-revival' ),
				array(
					'image'        => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'image_alt'    => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ) ),
					'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ) ),
					'title'        => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'         => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
					'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ) ),
					'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ) ),
				),
				array(
					array(
						'image'        => $krm_up . 'elementor/thumbs/Urdu-Bible-Distribution-6-e1787296463599-rs8073jb4tpz51knz3wuum8c11mkotg8ytctdb8ink.jpg',
						'image_alt'    => 'Urdu Bible distribution',
						'eyebrow'      => 'Flagship · Bible Distribution',
						'title'        => 'Hands that never held a book are holding this one.',
						'text'         => 'We place Urdu Bibles with families and new believers, then follow up so they can read and understand.',
						'button_label' => '$10 places one Urdu Bible',
						'button_url'   => '{donate}?ministry=bible-distribution#give',
					),
					array(
						'image'        => $krm_up . '2026/08/Leadership-and-Discipleship-10-scaled.jpg',
						'image_alt'    => 'A church gathering in Pakistan',
						'eyebrow'      => 'Flagship · Church Planting',
						'title'        => 'A church in every village we reach.',
						'text'         => 'Local congregations and house churches, led by pastors we train and walk alongside.',
						'button_label' => 'See the churches',
						'button_url'   => '{ministries}church-planting/#churches',
					),
				),
				__( 'The first card has the red button, the second the dark blue one.', 'kingdom-revival' )
			),
		),
	),
	'where'    => array(
		'label'  => __( 'Where we work', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'   => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Where we work' ),
			'heading'   => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Villages, kilns and house churches across Punjab.' ),
			'places'    => krm_list_f(
				__( 'Places', 'kingdom-revival' ),
				__( 'Place', 'kingdom-revival' ),
				array(
					'name'  => krm_f( 'text', __( 'Place', 'kingdom-revival' ) ),
					'count' => krm_f( 'text', __( 'Villages / kilns', 'kingdom-revival' ) ),
				),
				array(
					array(
						'name'  => 'Faisalabad',
						'count' => '[00] villages · [00] kilns',
					),
					array(
						'name'  => 'Jhang',
						'count' => '[00] villages · [00] kilns',
					),
					array(
						'name'  => '[District]',
						'count' => '[00] villages',
					),
					array(
						'name'  => '[District]',
						'count' => '[00] villages',
					),
				)
			),
			'map'       => krm_f( 'image', __( 'Map image', 'kingdom-revival' ), '', __( 'A map of Punjab. The pins below are placed on top of it.', 'kingdom-revival' ) ),
			'map_alt'   => krm_f( 'text', __( 'Map description (alt text)', 'kingdom-revival' ), 'Map of Punjab showing where Kingdom Revival Ministries works' ),
			'map_note'  => krm_f( 'text', __( 'Note shown on the map area while there is no map image', 'kingdom-revival' ), 'Map of Punjab · locations to be pinned' ),
			'pins'      => krm_list_f(
				__( 'Map pins', 'kingdom-revival' ),
				__( 'Pin', 'kingdom-revival' ),
				array(
					'label' => krm_f( 'text', __( 'Label', 'kingdom-revival' ) ),
					'left'  => krm_f( 'text', __( 'From the left (%)', 'kingdom-revival' ) ),
					'top'   => krm_f( 'text', __( 'From the top (%)', 'kingdom-revival' ) ),
				),
				array(
					array(
						'label' => 'Faisalabad (HQ)',
						'left'  => '48',
						'top'   => '46',
					),
					array(
						'label' => 'Jhang',
						'left'  => '34',
						'top'   => '56',
					),
				),
				__( 'The first pin is the large red one (headquarters); the others are orange.', 'kingdom-revival' )
			),
		),
	),
	'field'    => array(
		'label'  => __( 'Ministry in action (video and photos)', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'         => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Ministry programs' ),
			'heading'         => krm_f( 'text', __( 'Heading', 'kingdom-revival' ), 'See the ministry in action' ),
			'text'            => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'From evangelism and discipleship to children’s education, Bible distribution and practical care, Kingdom Revival Ministries serves families across Pakistan and beyond.' ),
			'video'           => krm_f( 'url', __( 'Video (MP4 link)', 'kingdom-revival' ), $krm_video ),
			'poster'          => krm_f( 'image', __( 'Image shown before the video plays', 'kingdom-revival' ), $krm_poster ),
			'video_title'     => krm_f( 'text', __( 'Video description (for screen readers)', 'kingdom-revival' ), 'Kingdom Revival Ministries featured video' ),
			'feature_eyebrow' => krm_f( 'text', __( 'Video: small heading', 'kingdom-revival' ), 'Featured video' ),
			'feature_heading' => krm_f( 'text', __( 'Video: heading', 'kingdom-revival' ), 'Faith, service and hope' ),
			'feature_text'    => krm_f( 'textarea', __( 'Video: text', 'kingdom-revival' ), 'Watch the ministry’s official video and explore real images from its work in Pakistan.' ),
			'feature_link'    => krm_f( 'text', __( 'Video: link text', 'kingdom-revival' ), 'Open video in a new tab' ),
			'photos'          => krm_list_f(
				__( 'Photos', 'kingdom-revival' ),
				__( 'Photo', 'kingdom-revival' ),
				array(
					'image'     => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'image_alt' => krm_f( 'text', __( 'Description (alt text)', 'kingdom-revival' ) ),
					'caption'   => krm_f( 'text', __( 'Caption', 'kingdom-revival' ) ),
				),
				array(
					array(
						'image'     => $krm_up . 'elementor/thumbs/Deliverance-and-Healing-ministry-5-scaled-e1787225773575-rs6kxdo5ygbat93txitcpifrbjmk10tf6j9kjqh6qg.jpg',
						'image_alt' => 'Deliverance and healing ministry',
						'caption'   => 'Prayer and healing ministry',
					),
					array(
						'image'     => $krm_up . 'elementor/thumbs/Bible-Distribution-rqhhvb1bt4op61pyxtdlg5smtcfpsj957ob37u8700.png',
						'image_alt' => 'Bible distribution',
						'caption'   => 'Sharing Scripture',
					),
					array(
						'image'     => $krm_up . 'elementor/thumbs/Urdu-Bible-Distribution-6-e1787296463599-rs8073jb4tpz51knz3wuum8c11mkotg8ytctdb8ink.jpg',
						'image_alt' => 'Urdu Bible distribution',
						'caption'   => 'Urdu Bible distribution',
					),
				)
			),
		),
	),
	'stories'  => array(
		'label'  => __( 'Stories from the field', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Lives changed' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Stories from the field' ),
			'items'   => krm_list_f(
				__( 'Stories', 'kingdom-revival' ),
				__( 'Story', 'kingdom-revival' ),
				array(
					'quote' => krm_f( 'textarea', __( 'Story (in their own words)', 'kingdom-revival' ) ),
					'name'  => krm_f( 'text', __( 'Name', 'kingdom-revival' ) ),
					'place' => krm_f( 'text', __( 'Who / where', 'kingdom-revival' ) ),
					'photo' => krm_f( 'image', __( 'Photo (optional)', 'kingdom-revival' ) ),
				),
				array(
					array(
						'quote' => '[Story of a freed brick kiln family, in their own words.]',
						'name'  => '[Name]',
						'place' => 'Freed family · [Village]',
						'photo' => '',
					),
					array(
						'quote' => '[Story of healing or answered prayer.]',
						'name'  => '[Name]',
						'place' => 'Healed · [Village]',
						'photo' => '',
					),
					array(
						'quote' => '[A pastor on how the church in his village began.]',
						'name'  => 'Pastor [Name]',
						'place' => 'House church · [Village]',
						'photo' => '',
					),
				)
			),
		),
	),
	'involved' => array(
		'label'  => __( 'Get involved', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Get Involved' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Three Ways to Stand With Us' ),
			'items'   => krm_list_f(
				__( 'Ways', 'kingdom-revival' ),
				__( 'Way', 'kingdom-revival' ),
				array(
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
				),
				array(
					array(
						'title' => 'Pray',
						'text'  => 'Intercede for the ministry, the families we serve, and the power of the Gospel.',
					),
					array(
						'title' => 'Give',
						'text'  => 'Provide Bibles, education, orphan care and emergency aid for families in crisis.',
					),
					array(
						'title' => 'Go',
						'text'  => 'Support outreach, church planting and humanitarian work on the ground.',
					),
				),
				__( 'The second card is the dark one. Icons follow the order: cross, heart, globe.', 'kingdom-revival' )
			),
		),
	),
	'prayer'   => array(
		'label'  => __( 'Prayer request', 'kingdom-revival' ),
		'help'   => __( 'Requests are saved under Kingdom Revival → Submissions and emailed like the contact form. The form fields are fixed.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'        => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Prayer request' ),
			'heading'        => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Let us pray with you.' ),
			'text'           => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Our pastors and prayer team pray over every request we receive. You can also message us on WhatsApp.' ),
			'whatsapp_label' => krm_f( 'text', __( 'WhatsApp link text', 'kingdom-revival' ), 'Pray with us on WhatsApp →', __( 'Links to the WhatsApp number in Settings.', 'kingdom-revival' ) ),
			'button'         => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Send prayer request' ),
			'success'        => krm_f( 'text', __( 'Message after sending', 'kingdom-revival' ), 'Thank you. Your prayer request has been sent and our team will pray with you.' ),
			'error'          => krm_f( 'text', __( 'Message if sending fails', 'kingdom-revival' ), 'Sorry, your request could not be sent. Please try again or message us on WhatsApp.' ),
		),
	),
	'founder'  => array(
		'label'  => __( 'Founder', 'kingdom-revival' ),
		'fields' => array(
			'image'          => krm_f( 'image', __( 'Photo', 'kingdom-revival' ), $krm_up . '2026/08/file_00000000b46471f484befac1dc0ee291.png' ),
			'image_alt'      => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ), 'Pastor Adil Arif, founder of Kingdom Revival Ministries' ),
			'eyebrow'        => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Founder & Pastor' ),
			'heading'        => krm_f( 'html', __( 'Name', 'kingdom-revival' ), 'Pastor Adil Arif' ),
			'text'           => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Pastor Adil leads Kingdom Revival Ministries in evangelism, discipleship and church ministry, with a heart for children and youth — and for practical care among villages, marginalised communities and brick kiln families.' ),
			'quote'          => krm_f( 'textarea', __( 'Quote', 'kingdom-revival' ), '“[A short quote from Pastor Adil on why this work matters.]”', __( 'Leave empty to hide the quote.', 'kingdom-revival' ) ),
			'button_label'   => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Contact Pastor Adil' ),
			'button_url'     => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '#prayer' ),
			'whatsapp_label' => krm_f( 'text', __( 'WhatsApp button text', 'kingdom-revival' ), 'WhatsApp', __( 'Links to the WhatsApp number in Settings. Leave empty to hide the button.', 'kingdom-revival' ) ),
			'story_label'    => krm_f( 'text', __( 'Story link text', 'kingdom-revival' ), 'Read his story →', __( 'Leave empty to hide the link.', 'kingdom-revival' ) ),
			'story_url'      => krm_f( 'url', __( 'Story link', 'kingdom-revival' ), '{about}#founder-story' ),
		),
	),
	'beliefs'  => array(
		'label'  => __( 'What we believe and our team', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'What we believe' ),
			'heading'      => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Our statement of faith' ),
			'points'       => krm_f( 'textarea', __( 'Beliefs', 'kingdom-revival' ), "The Bible is the inspired Word of God and our final authority.\nOne God, eternally existing as Father, Son and Holy Spirit.\nJesus Christ is fully God and fully man; He died for our sins and rose again.\nSalvation is by grace alone, through faith in Jesus Christ alone.\nThe Holy Spirit lives in every believer and empowers the Church.\nJesus Christ will return; we are called to make disciples of all nations.", __( 'One point per line.', 'kingdom-revival' ) ),
			'link_label'   => krm_f( 'text', __( 'Link text', 'kingdom-revival' ), 'Read the full statement →', __( 'Leave empty to hide the link.', 'kingdom-revival' ) ),
			'link_url'     => krm_f( 'url', __( 'Link', 'kingdom-revival' ), '{about}#beliefs' ),
			'team_eyebrow' => krm_f( 'text', __( 'Team: small heading', 'kingdom-revival' ), 'Our team' ),
			'team_heading' => krm_f( 'html', __( 'Team: heading', 'kingdom-revival' ), 'The people on the ground' ),
			'team'         => krm_list_f(
				__( 'Team members', 'kingdom-revival' ),
				__( 'Person', 'kingdom-revival' ),
				array(
					'photo' => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'name'  => krm_f( 'text', __( 'Name', 'kingdom-revival' ) ),
					'role'  => krm_f( 'text', __( 'Role', 'kingdom-revival' ) ),
				),
				array(
					array(
						'photo' => $krm_up . '2026/08/file_00000000b46471f484befac1dc0ee291.png',
						'name'  => 'Pastor Adil Arif',
						'role'  => 'Founder & Pastor',
					),
					array(
						'photo' => '',
						'name'  => '[Name]',
						'role'  => '[Role]',
					),
					array(
						'photo' => '',
						'name'  => '[Name]',
						'role'  => '[Role]',
					),
					array(
						'photo' => '',
						'name'  => '[Name]',
						'role'  => '[Role]',
					),
				)
			),
		),
	),
	'news'     => array(
		'label'  => __( 'Latest news & blogs', 'kingdom-revival' ),
		'help'   => __( 'Shows your three newest posts (their featured image, first category and date). The section is hidden while there are no posts.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'    => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'From the field' ),
			'heading'    => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Latest news & blogs' ),
			'all_label'  => krm_f( 'text', __( 'All posts link text', 'kingdom-revival' ), 'View all posts →' ),
			'all_url'    => krm_f( 'url', __( 'All posts link', 'kingdom-revival' ), '{blog}' ),
			'more_label' => krm_f( 'text', __( 'Card link text', 'kingdom-revival' ), 'Read more →' ),
		),
	),
	'whatsapp' => array(
		'label'  => __( 'Floating WhatsApp button', 'kingdom-revival' ),
		'fields' => array(
			'label' => krm_f( 'text', __( 'Button description (for screen readers)', 'kingdom-revival' ), 'Chat with us on WhatsApp', __( 'Opens a chat with the WhatsApp number in Settings. Leave empty to hide the button.', 'kingdom-revival' ) ),
		),
	),
	'cta'      => array(
		'label'  => __( 'Closing verse', 'kingdom-revival' ),
		'fields' => array(
			'quote'        => krm_f( 'textarea', __( 'Verse', 'kingdom-revival' ), '“Go therefore and make disciples of all nations.”' ),
			'cite'         => krm_f( 'text', __( 'Reference', 'kingdom-revival' ), 'Matthew 28:19' ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Become a Partner' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{partner}' ),
		),
	),
	'float'    => array(
		'label'  => __( 'Floating “Donate” panel', 'kingdom-revival' ),
		'help'   => __( 'Uses the amounts set under “Hero and donation card”.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'SUPPORT THE MISSION' ),
			'heading' => krm_f( 'text', __( 'Heading', 'kingdom-revival' ), 'Choose an amount' ),
			'text'    => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Your gift helps share the Gospel and care for vulnerable families.' ),
			'button'  => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Donate' ),
		),
	),
);
