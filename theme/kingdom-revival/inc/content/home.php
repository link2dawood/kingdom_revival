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
$krm_hl     = __( 'Wrap highlighted words in <span class="hl">…</span>.', 'kingdom-revival' );

return array(
	'hero'     => array(
		'label'  => __( 'Hero and donation card', 'kingdom-revival' ),
		'fields' => array(
			'image'          => krm_f( 'image', __( 'Background image (also shown before the video plays)', 'kingdom-revival' ), $krm_poster ),
			'video'          => krm_f( 'url', __( 'Background video (MP4 link)', 'kingdom-revival' ), $krm_hero_v, __( 'Upload the video in Media → Add New and paste its link here. Leave empty to show only the image. It plays on screens 768px and wider only (not with data saver or reduced motion); phones always see the background image. Keep it short (about 15 s), without sound, under 4 MB.', 'kingdom-revival' ) ),
			'video_webm'     => krm_f( 'url', __( 'Background video, WebM version (optional)', 'kingdom-revival' ), $krm_hero_w, __( 'The same clip as WebM, for browsers that cannot play the MP4. Leave empty if you only have the MP4.', 'kingdom-revival' ) ),
			'eyebrow'        => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Bringing Hope to the Forgotten' ),
			'title'          => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Sharing the Hope of <span class="hl">Jesus Christ</span> with the Unreached & <span class="hl">Vulnerable</span>', $krm_hl ),
			'lead'           => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'We preach the Gospel, plant churches and care for brick kiln families, orphans and widows across Pakistan and beyond.' ),
			'button1_label'  => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Explore What We Do' ),
			'button1_url'    => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '#ministries' ),
			'button2_label'  => krm_f( 'text', __( 'Video link text', 'kingdom-revival' ), 'Watch Our Story' ),
			'button2_url'    => krm_f( 'url', __( 'Video link', 'kingdom-revival' ), '#field-stories' ),
			'card_badge'     => krm_f( 'text', __( 'Donation card: badge', 'kingdom-revival' ), 'MAKE AN IMPACT' ),
			'card_heading'   => krm_f( 'text', __( 'Donation card: heading', 'kingdom-revival' ), 'Bring Hope to Pakistan’s Forgotten' ),
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
						'amount' => '$40',
						'title'  => 'A child’s schooling',
						'text'   => 'Fees, books and uniform for a kiln child.',
						'url'    => '{donate}?ministry=brick-kiln-family-care&amount=40#give',
					),
					array(
						'amount' => 'Any amount',
						'title'  => 'Frees a family',
						'text'   => 'Every family’s debt is different — your gift goes toward clearing one and helping them start again.',
						'url'    => '{donate}?ministry=slavery-freedom#give',
					),
				)
			),
		),
	),
	'calc'     => array(
		'label'  => __( 'Gift calculator', 'kingdom-revival' ),
		'help'   => __( 'The home page’s “Your gift at work” calculator (slider, quick amounts and what the gift could provide). The amounts and the rows’ costs are fixed: $10 a Bible, $45 a medical camp visit, $40 a child’s schooling. “Give” opens Donate with the chosen amount, for where it is needed most. The “Your gift at work” cards above are still shown on ministry and What We Do pages.', 'kingdom-revival' ),
		'fields' => array(
			'heading'    => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Your <span class="hl">gift</span> at work', $krm_hl ),
			'text'       => krm_f( 'text', __( 'Text under the heading', 'kingdom-revival' ), 'Drag the slider and see what your gift could do.' ),
			'list_title' => krm_f( 'text', __( 'List heading', 'kingdom-revival' ), 'Your gift could provide' ),
			'note'       => krm_f( 'textarea', __( 'Small print under the list', 'kingdom-revival' ), 'Each line shows what your full gift could provide on its own. Gifts are used where the need is greatest.' ),
		),
	),
	'about'    => array(
		'label'  => __( 'About', 'kingdom-revival' ),
		'help'   => __( 'Text and photos of the About section after the gift band. Leave a field empty to hide it.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'About Us' ),
			'title1'        => krm_f( 'text', __( 'Heading: first part', 'kingdom-revival' ), 'Reaching the unreached.' ),
			'title2'        => krm_f( 'text', __( 'Heading: second part (red)', 'kingdom-revival' ), 'Serving the forgotten.' ),
			'lead'          => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Kingdom Revival Ministries exists to glorify God by making disciples of Jesus Christ — proclaiming the Gospel among unreached communities, planting churches, equipping leaders, and serving vulnerable children and brick kiln families across Pakistan.' ),
			'verse'         => krm_f( 'textarea', __( 'Scripture', 'kingdom-revival' ), '“Go therefore and make disciples of all nations.”' ),
			'verse_cite'    => krm_f( 'text', __( 'Scripture reference', 'kingdom-revival' ), 'Matthew 28:19' ),
			'button_label'  => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Our Story' ),
			'button_url'    => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{about}' ),
			'founder_photo' => krm_f( 'image', __( 'Founder: photo', 'kingdom-revival' ), 'krm-theme:images/content/team-adil-arif.jpg' ),
			'founder_name'  => krm_f( 'text', __( 'Founder: link text', 'kingdom-revival' ), 'Meet Pastor Adil Arif' ),
			'founder_role'  => krm_f( 'text', __( 'Founder: role', 'kingdom-revival' ), 'Founder & Pastor' ),
			'founder_url'   => krm_f( 'url', __( 'Founder: link', 'kingdom-revival' ), '{about}#founder-story' ),
			'badge_title'   => krm_f( 'text', __( 'Badge on the photos: title', 'kingdom-revival' ), 'Since 2018' ),
			'badge_text'    => krm_f( 'text', __( 'Badge on the photos: text', 'kingdom-revival' ), 'Serving in Faisalabad & beyond' ),
			'photos'        => krm_list_f(
				__( 'Photos', 'kingdom-revival' ),
				__( 'Photo', 'kingdom-revival' ),
				array(
					'image'   => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'alt'     => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ) ),
					'caption' => krm_f( 'text', __( 'Label on the photo', 'kingdom-revival' ) ),
				),
				array(
					array( 'image' => 'krm-theme:images/content/leadership-and-discipleship-9-crop.jpg', 'alt' => 'A Kingdom Revival pastor preaching to children and families at a worship gathering', 'caption' => 'Sunday worship gathering' ),
					array( 'image' => 'krm-theme:images/content/orphanages-care-16-crop.jpg', 'alt' => 'Children sitting in rows sharing a meal provided by the ministry', 'caption' => 'A meal for the children' ),
					array( 'image' => 'krm-theme:images/content/church-planting-8.jpg', 'alt' => 'Families from a brick kiln gathered on mats to hear the Gospel', 'caption' => 'Church at the brick kilns' ),
				),
				__( 'The first photo is the tall one on the left; the next two sit beside it.', 'kingdom-revival' )
			),
			'cards'         => krm_list_f(
				__( 'Cards', 'kingdom-revival' ),
				__( 'Card', 'kingdom-revival' ),
				array(
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
					'url'   => krm_f( 'url', __( 'Link (optional)', 'kingdom-revival' ) ),
				),
				array(
					array( 'title' => 'Gospel to the unreached', 'text' => 'Taking Christ to villages and communities who have never heard.', 'url' => '' ),
					array( 'title' => 'Churches planted', 'text' => 'Local congregations and house churches led by trained pastors.', 'url' => '' ),
					array( 'title' => 'Orphans & kiln children', 'text' => 'Care, meals and schooling for the most vulnerable children.', 'url' => '' ),
					array( 'title' => 'Urdu Bibles', 'text' => 'Scripture in the heart language of families and new believers.', 'url' => '' ),
				),
				__( 'The icon and colour follow the card’s position: globe, church, heart, book.', 'kingdom-revival' )
			),
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
			'all_label'     => krm_f( 'text', __( 'What We Do button text', 'kingdom-revival' ), 'Explore What We Do' ),
			'all_url'       => krm_f( 'url', __( 'What We Do button link', 'kingdom-revival' ), '{ministries}' ),
		),
	),
	'mission'  => array(
		'label'  => __( 'Mission: brick kilns of Pakistan', 'kingdom-revival' ),
		'help'   => __( 'The section after the ministry explorer. Leave a button text empty to hide that button.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Pakistan and Beyond' ),
			'heading1'      => krm_f( 'text', __( 'Heading: first line', 'kingdom-revival' ), 'Bringing Christ' ),
			'heading2'      => krm_f( 'text', __( 'Heading: second line (italic accent)', 'kingdom-revival' ), 'to the brick kilns of Pakistan.' ),
			'text'          => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'We preach the Gospel and plant churches among the unreached. We provide Sunday school and education for children growing up beside the kilns. We hold medical camps, place Urdu Bibles in the hands of new believers, and care for orphans and widows.' ),
			'quote'         => krm_f( 'textarea', __( 'Scripture', 'kingdom-revival' ), '“The Spirit of the Lord is upon me, because he hath anointed me to preach the gospel to the poor.”' ),
			'cite'          => krm_f( 'text', __( 'Scripture reference', 'kingdom-revival' ), 'Luke 4:18' ),
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'Partner With Us' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '{partner}' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'See Our Impact' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{impact}' ),
			'ghost'         => krm_f( 'text', __( 'Large faint word behind the section (decoration)', 'kingdom-revival' ), 'Hope' ),
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
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'What We Do' ),
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
			'band_photo'    => krm_f( 'image', __( 'Background photo (seen through the navy overlay; empty = plain colour)', 'kingdom-revival' ), 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-32-scaled.jpg' ),
			'heading'       => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Behind every brick, a family <span class="hl">waiting to be free</span>.', $krm_hl ),
			'text'          => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Whole families labour at brick kilns under debts that can pass from one generation to the next. This is how they become trapped, and how your gift can bring them out.' ),
			'trap_heading'  => krm_f( 'html', __( 'How a family becomes trapped: heading', 'kingdom-revival' ), 'How a family becomes <span class="hl">trapped</span>', $krm_hl ),
			'trap'          => krm_list_f(
				__( 'How a family becomes trapped', 'kingdom-revival' ),
				__( 'Stage', 'kingdom-revival' ),
				array(
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'  => krm_f( 'text', __( 'Text', 'kingdom-revival' ) ),
				),
				array(
					array(
						'title' => 'A small loan',
						'text'  => 'Facing hunger, illness or a daughter’s wedding, a family borrows from the kiln owner.',
					),
					array(
						'title' => 'The debt grows',
						'text'  => 'Interest, wage deductions and unclear accounts make repayment nearly impossible.',
					),
					array(
						'title' => 'The whole family works',
						'text'  => 'Parents and children make bricks for long hours and very little pay.',
					),
					array(
						'title' => 'The debt is passed down',
						'text'  => 'When parents cannot repay, the burden can fall on their children.',
					),
				),
				__( 'Numbered 01, 02… in this order, joined by arrows (a timeline on phones).', 'kingdom-revival' )
			),
			'transition'    => krm_f( 'text', __( 'Line between the two lists', 'kingdom-revival' ), 'Bonded labour is illegal in Pakistan, yet many families remain trapped.' ),
			'steps_heading' => krm_f( 'html', __( 'The way to freedom: heading', 'kingdom-revival' ), 'The way to <span class="hl">freedom</span>', $krm_hl ),
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
			'before'        => krm_f( 'image', __( 'Before photo', 'kingdom-revival' ), 'krm-theme:images/content/freedom-before-1.jpg' ),
			'before_alt'    => krm_f( 'text', __( 'Before photo description (alt text)', 'kingdom-revival' ), 'A mother and her children with a ministry worker outside their brick home at the kiln' ),
			'before_label'  => krm_f( 'text', __( 'Before photo label', 'kingdom-revival' ), 'Before · Bonded at the kiln' ),
			'after'         => krm_f( 'image', __( 'After photo', 'kingdom-revival' ), 'krm-theme:images/content/freedom-after-1.jpg' ),
			'after_alt'     => krm_f( 'text', __( 'After photo description (alt text)', 'kingdom-revival' ), 'A freed family with a ministry worker in their new home, giving a thumbs-up' ),
			'after_label'   => krm_f( 'text', __( 'After photo label', 'kingdom-revival' ), 'After · Free' ),
			'photos_heading' => krm_f( 'text', __( 'More before and after photos: heading', 'kingdom-revival' ), 'More families, before and after' ),
			'photos'        => krm_list_f(
				__( 'More before and after photos', 'kingdom-revival' ),
				__( 'Photo', 'kingdom-revival' ),
				array(
					'photo' => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'stage' => krm_f( 'text', __( 'Label ("Before" or "After")', 'kingdom-revival' ) ),
					'alt'   => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ) ),
				),
				array(
					array( 'photo' => 'krm-theme:images/content/freedom-before-2.jpg', 'stage' => 'Before', 'alt' => 'A mother and her children with a ministry worker beside stacked bricks at a kiln' ),
					array( 'photo' => 'krm-theme:images/content/freedom-before-3.jpg', 'stage' => 'Before', 'alt' => 'A family of brick kiln workers and their children on the dusty ground at a kiln' ),
					array( 'photo' => 'krm-theme:images/content/freedom-after-2.jpg', 'stage' => 'After', 'alt' => 'A family holding an “I am free now” sign with a ministry worker' ),
					array( 'photo' => 'krm-theme:images/content/freedom-after-3.jpg', 'stage' => 'After', 'alt' => 'A freed mother at her new sewing machine with her children and a ministry worker' ),
					array( 'photo' => 'krm-theme:images/content/freedom-after-4.jpg', 'stage' => 'After', 'alt' => 'A freed family with a ministry worker outside their new home' ),
				),
				__( 'Shown as a row of photos under the slider; click a photo to see it full size. A label starting with "Before" gets the red badge.', 'kingdom-revival' )
			),
			'cta_label'     => krm_f( 'text', __( 'Button under the steps: text', 'kingdom-revival' ), 'Help a family walk free' ),
			'cta_url'       => krm_f( 'url', __( 'Button under the steps: link', 'kingdom-revival' ), '{home}projects/freedom-for-kiln-families/' ),
			'compare_label' => krm_f( 'text', __( 'Slider label', 'kingdom-revival' ), 'Drag to compare' ),
			'button_label'  => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Help free a family' ),
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
		'label'  => __( 'Where we serve (map)', 'kingdom-revival' ),
		'help'   => __( 'A map of Pakistan (provinces from geoBoundaries, public domain; built into the theme, assets/images/pakistan-map.svg) over a darkened photo. Punjab is highlighted, and a place in the list below gets a marker when it is one of the towns the map knows: Faisalabad (HQ), Jhang, Chiniot, Toba Tek Singh. The highlight under the text is the first impact number (Kingdom Revival → Settings → Impact numbers).', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'    => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Our reach' ),
			'heading'    => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Where we <span class="hl">serve</span>', $krm_hl ),
			'text'       => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'From brick kilns to village churches, we are carrying the hope of Christ to communities across Pakistan and beyond.' ),
			'band_photo' => krm_f( 'image', __( 'Background photo (darkened behind the map; empty = plain colour)', 'kingdom-revival' ), $krm_poster ),
			'region'     => krm_f( 'text', __( 'Heading over the places', 'kingdom-revival' ), 'Punjab, Pakistan' ),
			'places'     => krm_list_f(
				__( 'Places', 'kingdom-revival' ),
				__( 'Place', 'kingdom-revival' ),
				array(
					'name'  => krm_f( 'text', __( 'Place', 'kingdom-revival' ) ),
					'count' => krm_f( 'text', __( 'Villages / kilns', 'kingdom-revival' ) ),
				),
				array(
					array(
						'name'  => 'Faisalabad',
						'count' => '40 villages · 8 kilns',
					),
					array(
						'name'  => 'Jhang',
						'count' => '25 villages · 4 kilns',
					),
					array(
						'name'  => 'Toba Tek Singh',
						'count' => '9 villages',
					),
					array(
						'name'  => 'Chiniot',
						'count' => '6 villages',
					),
				)
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
					'label'     => krm_f( 'text', __( 'Small label above the caption (e.g. the ministry name)', 'kingdom-revival' ) ),
					'url'       => krm_f( 'url', __( 'Link (the card opens it)', 'kingdom-revival' ) ),
				),
				array(
					array(
						'image'     => $krm_up . 'elementor/thumbs/Deliverance-and-Healing-ministry-5-scaled-e1787225773575-rs6kxdo5ygbat93txitcpifrbjmk10tf6j9kjqh6qg.jpg',
						'image_alt' => 'Deliverance and healing ministry',
						'caption'   => 'Prayer and healing ministry',
						'label'     => 'Prayer & Deliverance',
						'url'       => '{ministries}prayer-deliverance/',
					),
					array(
						'image'     => $krm_up . 'elementor/thumbs/Bible-Distribution-rqhhvb1bt4op61pyxtdlg5smtcfpsj957ob37u8700.png',
						'image_alt' => 'Bible distribution',
						'caption'   => 'Sharing Scripture',
						'label'     => 'Evangelism & Outreach',
						'url'       => '{ministries}evangelism-outreach/',
					),
					array(
						'image'     => $krm_up . 'elementor/thumbs/Urdu-Bible-Distribution-6-e1787296463599-rs8073jb4tpz51knz3wuum8c11mkotg8ytctdb8ink.jpg',
						'image_alt' => 'Urdu Bible distribution',
						'caption'   => 'Urdu Bible distribution',
						'label'     => 'Bible Distribution',
						'url'       => '{ministries}bible-distribution/',
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
						'quote' => 'For eleven years our whole family worked at the kiln to pay a debt that never got smaller. When the ministry cleared it, my children went to school for the first time. Now we work for ourselves.',
						'name'  => 'Yousaf Masih',
						'place' => 'Freed family · Faisalabad district',
						'photo' => '',
					),
					array(
						'quote' => 'My daughter had a fever for weeks and we had no money for a doctor. At the medical camp she was treated, and the team prayed with us. She is well and back at school.',
						'name'  => 'Saima Bibi',
						'place' => 'Answered prayer · Jhang district',
						'photo' => '',
					),
					array(
						'quote' => 'We started with four families praying in my courtyard. Today more than forty people worship together every Sunday, and the village has its own church.',
						'name'  => 'Pastor Javed Masih',
						'place' => 'House church · Faisalabad district',
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
					'title'  => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'   => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
					'button' => krm_f( 'text', __( 'Button text (empty = no button)', 'kingdom-revival' ) ),
					'url'    => krm_f( 'url', __( 'Button link', 'kingdom-revival' ) ),
				),
				array(
					array(
						'title'  => 'Pray',
						'text'   => 'Intercede for the ministry, the families we serve, and the power of the Gospel.',
						'button' => 'Become a Prayer Partner',
						'url'    => '{partner}#prayer-partners',
					),
					array(
						'title'  => 'Give',
						'text'   => 'Provide Bibles, education, orphan care and emergency aid for families in crisis.',
						'button' => 'Give Now',
						'url'    => '{donate}#give',
					),
					array(
						'title'  => 'Go',
						'text'   => 'Support outreach, church planting and humanitarian work on the ground.',
						'button' => 'Plan a Visit',
						'url'    => '{contact}?subject=Plan%20a%20Visit#contact-form',
					),
				),
				__( 'The second card is the dark one, with a red button; the others have outline buttons. Icons follow the order: cross, heart, globe. A Contact link may add ?subject=… to choose the form\'s topic.', 'kingdom-revival' )
			),
		),
	),
	'prayer'   => array(
		'label'  => __( 'Prayer request', 'kingdom-revival' ),
		'help'   => __( 'Requests are emailed to the address in KRM_FORMS_TO_EMAIL and saved under Kingdom Revival → Form Submissions (Prayer Request; “PRIVATE” when the visitor ticks “Keep my request private”). The form fields are fixed. {name} in the messages becomes the visitor’s first name.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'        => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Prayer request' ),
			'heading'        => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Let us pray with you.' ),
			'text'           => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Our pastors and prayer team pray over every request we receive. You can also message us on WhatsApp.' ),
			'whatsapp_label' => krm_f( 'text', __( 'WhatsApp link text', 'kingdom-revival' ), 'Pray with us on WhatsApp →', __( 'Links to the WhatsApp number in Settings.', 'kingdom-revival' ) ),
			'button'         => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Send prayer request' ),
			'success'        => krm_f( 'text', __( 'Message after sending', 'kingdom-revival' ), 'Thank you, {name}! Your prayer request has reached our team, and we will pray with you.' ),
			'error'          => krm_f( 'text', __( 'Message if sending fails', 'kingdom-revival' ), 'Sorry, your message couldn’t be sent. Please try again or WhatsApp us at {phone}.' ),
		),
	),
	'founder'  => array(
		'label'  => __( 'Founder', 'kingdom-revival' ),
		'fields' => array(
			'image'          => krm_f( 'image', __( 'Photo', 'kingdom-revival' ), 'krm-theme:images/content/founder-adil-arif.jpg' ),
			'image_alt'      => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ), 'Pastor Adil Arif, founder of Kingdom Revival Ministries' ),
			'eyebrow'        => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Founder & Pastor' ),
			'heading'        => krm_f( 'html', __( 'Name', 'kingdom-revival' ), 'Pastor Adil Arif' ),
			'text'           => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Pastor Adil leads Kingdom Revival Ministries in evangelism, discipleship and church ministry, with a heart for children and youth — and for practical care among villages, marginalised communities and brick kiln families.' ),
			'quote'          => krm_f( 'textarea', __( 'Quote', 'kingdom-revival' ), '“Every child taught, every family set free from the kilns, every village that hears the Gospel — that is God’s Kingdom breaking in. That is why we keep going.”', __( 'Leave empty to hide the quote.', 'kingdom-revival' ) ),
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
			'band_photo'   => krm_f( 'image', __( 'Background photo (seen through the navy overlay; empty = plain colour)', 'kingdom-revival' ), 'krm-theme:images/content/church-planting-2.jpg' ),
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
