<?php
/**
 * Home page content (front-page.php and template-parts/home/*), numbered in the page's order (theme 1.23.0, 1.23.2: header,
 * hero, Who We Are, Taking the Gospel to the villages, What We Do, Why Pakistan Needs Hope, Our Impact + map, Featured Projects,
 * Stories, Our Journey, Our Ministries explorer, Partner With Us, Your gift at work, Latest News, final call to action, footer). The last sections are home content shown on other pages.
 * The hero's impact numbers bar comes from Kingdom Revival → Settings.
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
		'label'  => __( '1–2. Hero, donation card and impact numbers bar', 'kingdom-revival' ),
		'help'   => __( 'The impact numbers bar under the hero shows Kingdom Revival → Settings → Impact numbers (labels are put in the plural automatically when the number is more than 1).', 'kingdom-revival' ),
		'fields' => array(
			'image'          => krm_f( 'image', __( 'Background image (also shown before the video plays)', 'kingdom-revival' ), $krm_poster ),
			'video'          => krm_f( 'url', __( 'Background video (MP4 link)', 'kingdom-revival' ), $krm_hero_v, __( 'Upload the video in Media → Add New and paste its link here. Leave empty to show only the image. It plays on screens 768px and wider only (not with data saver or reduced motion); phones always see the background image. Keep it short (about 15 s), without sound, under 4 MB.', 'kingdom-revival' ) ),
			'video_webm'     => krm_f( 'url', __( 'Background video, WebM version (optional)', 'kingdom-revival' ), $krm_hero_w, __( 'The same clip as WebM, for browsers that cannot play the MP4. Leave empty if you only have the MP4.', 'kingdom-revival' ) ),
			'eyebrow'        => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Bringing Hope to the Forgotten' ),
			'title'          => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Sharing the Hope of <span class="hl">Jesus Christ</span> with the Unreached & <span class="hl">Vulnerable</span>', $krm_hl ),
			'lead'           => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'We preach the Gospel, plant churches and care for brick kiln families, orphans and widows across Pakistan and beyond.' ),
			'button1_label'  => krm_f( 'text', __( 'First button text (red)', 'kingdom-revival' ), 'Donate Now' ),
			'button1_url'    => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '{donate}#give' ),
			'button2_label'  => krm_f( 'text', __( 'Second button text (outline)', 'kingdom-revival' ), 'Partner With Us' ),
			'button2_url'    => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{partner}' ),
			'card_badge'     => krm_f( 'text', __( 'Donation card: badge', 'kingdom-revival' ), 'MAKE AN IMPACT' ),
			'card_heading'   => krm_f( 'text', __( 'Donation card: heading', 'kingdom-revival' ), 'Bring Hope to Pakistan’s Forgotten' ),
			'card_text'      => krm_f( 'textarea', __( 'Donation card: text', 'kingdom-revival' ), 'Your gift provides Sunday school, education and care for children growing up beside the kilns.' ),
			'card_ministry'  => krm_f( 'text', __( 'Donation card: ministry the gift goes to', 'kingdom-revival' ), 'children-orphan-care', __( 'The ministry’s slug (Ministries → edit → permalink), e.g. children-orphan-care. The card’s Donate button opens Donate with this ministry chosen. Leave empty for “Where it is needed most”.', 'kingdom-revival' ) ),
			'card_note'      => krm_f( 'textarea', __( 'Donation card: small print', 'kingdom-revival' ), 'Every gift helps children learn, grow in faith and build a brighter future.' ),
			'amounts'        => krm_f( 'text', __( 'Amounts (US dollars)', 'kingdom-revival' ), '25, 50, 100, 250', __( 'Used by the donation card and the floating “Donate” panel. Numbers separated by commas; “Other” is always added.', 'kingdom-revival' ) ),
			'default_amount' => krm_f( 'text', __( 'Amount selected at first', 'kingdom-revival' ), '50' ),
		),
	),
	'about'    => array(
		'label'  => __( '3. Who We Are', 'kingdom-revival' ),
		'help'   => __( 'Introduction, vision, mission and the founding year, with three photos. Leave a field empty to hide it.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'        => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Who We Are' ),
			'title1'         => krm_f( 'text', __( 'Heading: first part', 'kingdom-revival' ), 'Reaching the unreached.' ),
			'title2'         => krm_f( 'text', __( 'Heading: second part (red)', 'kingdom-revival' ), 'Serving the forgotten.' ),
			'lead'           => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Kingdom Revival Ministries is a Christian ministry based in Faisalabad, Pakistan. We take the Gospel to unreached villages, plant churches, equip local leaders and care for vulnerable children and brick kiln families.' ),
			'founded'        => krm_f( 'text', __( 'Founded (shown under the heading)', 'kingdom-revival' ), 'Founded in 2018', __( 'Leave empty to hide it.', 'kingdom-revival' ) ),
			'vision_title'   => krm_f( 'text', __( 'Vision: heading', 'kingdom-revival' ), 'Our Vision' ),
			'vision_text'    => krm_f( 'textarea', __( 'Vision: text', 'kingdom-revival' ), 'Communities across Pakistan transformed by the Gospel — with thriving local churches, freed families and children who grow up knowing Jesus.' ),
			'mission_title'  => krm_f( 'text', __( 'Mission: heading', 'kingdom-revival' ), 'Our Mission' ),
			'mission_text'   => krm_f( 'textarea', __( 'Mission: text', 'kingdom-revival' ), 'To glorify God by proclaiming the Gospel, planting churches, equipping leaders and showing Christ’s love through practical care.' ),
			'button_label'   => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Our Story' ),
			'button_url'     => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{about}' ),
			'founder_photo'  => krm_f( 'image', __( 'Founder: photo', 'kingdom-revival' ), 'krm-theme:images/content/pastor-adil-arif.jpg' ),
			'founder_name'   => krm_f( 'text', __( 'Founder: link text', 'kingdom-revival' ), 'Meet Pastor Adil Arif' ),
			'founder_role'   => krm_f( 'text', __( 'Founder: role', 'kingdom-revival' ), 'Founder & Pastor' ),
			'founder_url'    => krm_f( 'url', __( 'Founder: link', 'kingdom-revival' ), '{about}#founder-story' ),
			'badge_title'    => krm_f( 'text', __( 'Badge on the photos: title', 'kingdom-revival' ), 'Faisalabad' ),
			'badge_text'     => krm_f( 'text', __( 'Badge on the photos: text', 'kingdom-revival' ), 'Ministry headquarters, Pakistan' ),
			'photos'         => krm_list_f(
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
		),
	),
	'mission'  => array(
		'label'  => __( '4. Taking the Gospel to the villages of Pakistan', 'kingdom-revival' ),
		'help'   => __( 'Between Who We Are and What We Do. Leave a button text empty to hide that button.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Pakistan and Beyond' ),
			'heading1'      => krm_f( 'text', __( 'Heading: first line', 'kingdom-revival' ), 'Taking the Gospel' ),
			'heading2'      => krm_f( 'text', __( 'Heading: second line (italic accent)', 'kingdom-revival' ), 'to the villages of Pakistan.' ),
			'text'          => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'We preach the Gospel and plant churches among the unreached. We provide Sunday school and education for children growing up beside the kilns. We hold medical camps, install clean water hand pumps at brick kilns, place Urdu Bibles in the hands of new believers, and care for orphans and widows.' ),
			'quote'         => krm_f( 'textarea', __( 'Scripture', 'kingdom-revival' ), '“The Spirit of the Lord is upon me, because he hath anointed me to preach the gospel to the poor.”' ),
			'cite'          => krm_f( 'text', __( 'Scripture reference', 'kingdom-revival' ), 'Luke 4:18' ),
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'Partner With Us' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '{partner}' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'See Our Impact' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{impact}' ),
			'ghost'         => krm_f( 'text', __( 'Large faint word behind the section (decoration)', 'kingdom-revival' ), 'Hope' ),
			'slides'        => krm_list_f(
				__( 'Photo slider', 'kingdom-revival' ),
				__( 'Slide', 'kingdom-revival' ),
				array(
					'image' => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'alt'   => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ) ),
					'title' => krm_f( 'text', __( 'Caption: title (accent colour)', 'kingdom-revival' ) ),
					'meta'  => krm_f( 'text', __( 'Caption: place / year', 'kingdom-revival' ) ),
				),
				array(
					array( 'image' => 'krm-theme:images/content/church-planting-8.jpg', 'alt' => 'A pastor preaching to brick kiln families gathered on mats', 'title' => 'Church at the brick kilns', 'meta' => 'Faisalabad' ),
					array( 'image' => 'krm-theme:images/content/clean-water-2.jpg', 'alt' => 'A ministry worker pumping clean water for children at a new hand pump', 'title' => 'Clean water hand pump', 'meta' => 'Punjab' ),
					array( 'image' => 'krm-theme:images/content/outreach-ministry-7.jpg', 'alt' => 'The ministry team handing out food bags to a brick kiln family', 'title' => 'Food for kiln families', 'meta' => 'Punjab' ),
					array( 'image' => 'krm-theme:images/content/church-planting-6.jpg', 'alt' => 'Families listening to the Gospel beside stacks of bricks and a wooden cross', 'title' => 'Gospel gathering', 'meta' => 'Brick kiln, Punjab' ),
					array( 'image' => 'krm-theme:images/content/outreach-ministry-12.jpg', 'alt' => 'A widow at a brick kiln with the food bags she received', 'title' => 'Caring for widows', 'meta' => 'Punjab' ),
				),
				__( 'Photos beside the text, one at a time; they change every few seconds and can be moved with the arrows. The caption shows the title in the accent colour, then the place / year. Delete every slide to hide the slider.', 'kingdom-revival' )
			),
		),
	),
	'wwd'      => array(
		'label'  => __( '5. What We Do', 'kingdom-revival' ),
		'help'   => __( 'Three cards. The icons follow the card’s position: cross, helping hands, church. “{ministries}gospel-spiritual-ministries” is a ministry group page; “{ministries}leadership-training” a ministry.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'    => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'What We Do' ),
			'heading'    => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'One Gospel, shared in <span class="hl">word and deed</span>', $krm_hl ),
			'intro'      => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Our work has three parts, each carrying the love of Jesus to people who need it most.' ),
			'more_label' => krm_f( 'text', __( 'Link text on every card', 'kingdom-revival' ), 'Learn More' ),
			'all_label'  => krm_f( 'text', __( 'Link under the cards (empty = none)', 'kingdom-revival' ), 'See all our ministries →' ),
			'all_url'    => krm_f( 'url', __( 'Link under the cards: address', 'kingdom-revival' ), '{ministries}' ),
			'cards'      => krm_list_f(
				__( 'Cards', 'kingdom-revival' ),
				__( 'Card', 'kingdom-revival' ),
				array(
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
					'url'   => krm_f( 'url', __( '“Learn More” link', 'kingdom-revival' ) ),
				),
				array(
					array(
						'title' => 'Gospel & Evangelism',
						'text'  => 'Preaching the Gospel in unreached villages and brick kiln communities, baptising new believers and placing Urdu Bibles in their hands.',
						'url'   => '{ministries}gospel-spiritual-ministries',
					),
					array(
						'title' => 'Humanitarian Outreach',
						'text'  => 'Freedom for bonded kiln families, clean water, food and medical care, and support for widows, orphans and persecuted Christians.',
						'url'   => '{ministries}compassion-humanitarian-ministries',
					),
					array(
						'title' => 'Church & Leadership Development',
						'text'  => 'Planting local churches and training pastors and leaders to teach the Bible faithfully and shepherd their communities.',
						'url'   => '{ministries}leadership-training',
					),
				)
			),
		),
	),
	'needs'    => array(
		'label'  => __( '6. Why Pakistan Needs Hope', 'kingdom-revival' ),
		'help'   => __( 'A large photo beside short blocks of text. The icons follow the block’s position: coins, bricks, water drop, child, cross.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Why Pakistan Needs Hope' ),
			'heading'      => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'The people we serve are <span class="hl">often forgotten</span>', $krm_hl ),
			'intro'        => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'In the villages and brick kilns of Punjab, many families face hardship every day — and many have never heard the hope of the Gospel.' ),
			'image'        => krm_f( 'image', __( 'Photo', 'kingdom-revival' ), 'krm-theme:images/content/bricks-kiln-slaves-children-2-1-crop.jpg' ),
			'image_alt'    => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ), 'Children of brick kiln families raising their hands in front of a kiln chimney' ),
			'image_label'  => krm_f( 'text', __( 'Label on the photo', 'kingdom-revival' ), 'Children at a brick kiln, Punjab' ),
			'button_label' => krm_f( 'text', __( 'Button text (empty = no button)', 'kingdom-revival' ), 'Help Bring Hope' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{donate}#give' ),
			'items'        => krm_list_f(
				__( 'Needs', 'kingdom-revival' ),
				__( 'Need', 'kingdom-revival' ),
				array(
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
				),
				array(
					array( 'title' => 'Poverty', 'text' => 'Many rural families live on daily wages that cannot cover food, rent and medicine.' ),
					array( 'title' => 'Brick kiln bonded labour', 'text' => 'Whole families make bricks to repay loans that keep growing — a debt that can pass to their children.' ),
					array( 'title' => 'No clean water', 'text' => 'Kiln and village families often have no safe water close to home, and children fall ill from what they drink.' ),
					array( 'title' => 'Vulnerable children', 'text' => 'Children work beside their parents instead of going to school, and orphans have no one to care for them.' ),
					array( 'title' => 'A persecuted Christian minority', 'text' => 'Christians are a small, mostly poor minority who can face discrimination and pressure because of their faith.' ),
				)
			),
		),
	),
	'impact'   => array(
		'label'  => __( '7. Our Impact', 'kingdom-revival' ),
		'help'   => __( 'Counters (they count up when scrolled into view), key achievements, then the map below (“Where we serve”). A label is put in the plural automatically when its number is more than 1.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Our Impact' ),
			'heading'      => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'What God has done <span class="hl">through your giving</span>', $krm_hl ),
			'intro'        => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Every number is a person, a family or a village that has received help and heard the hope of Jesus.' ),
			'counters'     => krm_list_f(
				__( 'Counters', 'kingdom-revival' ),
				__( 'Counter', 'kingdom-revival' ),
				array(
					'value' => krm_f( 'text', __( 'Number (digits only; “+” is added)', 'kingdom-revival' ) ),
					'label' => krm_f( 'text', __( 'Label', 'kingdom-revival' ) ),
				),
				array(
					array( 'value' => '80', 'label' => 'Villages reached' ),
					array( 'value' => '200', 'label' => 'Churches planted' ),
					array( 'value' => '35', 'label' => 'Families freed' ),
					array( 'value' => '500', 'label' => 'Children supported' ),
					array( 'value' => '650', 'label' => 'Urdu Bibles distributed' ),
					array( 'value' => '18', 'label' => 'Hand pumps installed' ),
					array( 'value' => '60', 'label' => 'Pastors trained' ),
					array( 'value' => '3200', 'label' => 'Lives impacted' ),
				),
				__( 'Up to 8 look best (4 per row). The same figures as Our Impact and Settings → Impact numbers: keep them in step.', 'kingdom-revival' )
			),
			'achievements_title' => krm_f( 'text', __( 'Key achievements: heading', 'kingdom-revival' ), 'Key achievements' ),
			'achievements' => krm_f( 'textarea', __( 'Key achievements', 'kingdom-revival' ), "Ministry founded in Faisalabad in 2018\nBonded brick kiln families freed and helped to start again\nClean water hand pumps installed at brick kilns and villages\nUrdu Bibles placed with new believers and village churches\nLocal pastors and leaders trained to shepherd their churches\nMinistry now reaching Lahore and Karachi", __( 'One achievement per line.', 'kingdom-revival' ) ),
			'link_label'   => krm_f( 'text', __( 'Link text (empty = none)', 'kingdom-revival' ), 'See our full impact →' ),
			'link_url'     => krm_f( 'url', __( 'Link', 'kingdom-revival' ), '{impact}' ),
		),
	),
	'where'    => array(
		'label'  => __( '7. Our Impact: the map (Where we serve)', 'kingdom-revival' ),
		'help'   => __( 'A map of Pakistan (provinces from geoBoundaries, public domain; built into the theme, assets/images/pakistan-map.svg). Punjab is highlighted, and a place in the list below gets a marker when it is one of the towns the map knows: Faisalabad (HQ), Jhang, Chiniot, Toba Tek Singh, Lahore, Karachi. Around a town, one dot per village and one square per kiln from its text (“40 villages · 8 kilns”); Lahore and Karachi show three ministry-site dots and a shaded area. The text is also shown when a visitor points at (or taps) the town.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'    => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Our reach' ),
			'heading'    => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Where we <span class="hl">serve</span>', $krm_hl ),
			'text'       => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'From brick kilns to village churches, we are carrying the hope of Christ to communities across Pakistan and beyond.' ),
			'band_photo' => krm_f( 'image', __( 'Background photo of the whole Our Impact section (darkened; empty = plain colour)', 'kingdom-revival' ), $krm_poster ),
			'region'     => krm_f( 'text', __( 'Heading over the places', 'kingdom-revival' ), 'Punjab & Sindh, Pakistan' ),
			'places'     => krm_list_f(
				__( 'Places', 'kingdom-revival' ),
				__( 'Place', 'kingdom-revival' ),
				array(
					'name'  => krm_f( 'text', __( 'Place', 'kingdom-revival' ) ),
					'count' => krm_f( 'text', __( 'Villages / kilns, or a short note', 'kingdom-revival' ) ),
				),
				array(
					array( 'name' => 'Faisalabad', 'count' => '40 villages · 8 kilns' ),
					array( 'name' => 'Jhang', 'count' => '25 villages · 4 kilns' ),
					array( 'name' => 'Toba Tek Singh', 'count' => '9 villages' ),
					array( 'name' => 'Chiniot', 'count' => '6 villages' ),
					array( 'name' => 'Lahore', 'count' => 'Church & Outreach Work' ),
					array( 'name' => 'Karachi', 'count' => 'Humanitarian Outreach' ),
				)
			),
		),
	),
	'featured' => array(
		'label'  => __( '8. Featured Projects', 'kingdom-revival' ),
		'help'   => __( 'Three project cards. “{donate}?ministry=slavery-freedom#give” opens Donate with that ministry chosen.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'      => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Featured Projects' ),
			'heading'      => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Projects you can <span class="hl">support today</span>', $krm_hl ),
			'button_label' => krm_f( 'text', __( 'Button text on every card', 'kingdom-revival' ), 'Support This Project' ),
			'all_label'    => krm_f( 'text', __( 'Link beside the heading (empty = none)', 'kingdom-revival' ), 'See all projects →' ),
			'all_url'      => krm_f( 'url', __( 'Link beside the heading: address', 'kingdom-revival' ), '{donate}#projects' ),
			'cards'        => krm_list_f(
				__( 'Projects', 'kingdom-revival' ),
				__( 'Project', 'kingdom-revival' ),
				array(
					'tag'   => krm_f( 'text', __( 'Label on the photo', 'kingdom-revival' ) ),
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'image' => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'alt'   => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
					'url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ) ),
				),
				array(
					array(
						'tag'   => 'Freedom',
						'title' => 'Brick Kiln Family Freedom',
						'image' => 'krm-theme:images/content/freedom-after-1.jpg',
						'alt'   => 'A freed brick kiln family with a ministry worker in their new home',
						'text'  => 'Pay off a bonded family’s debt to the kiln owner, move them to a safe home and help them start again with work and school.',
						'url'   => '{donate}?ministry=slavery-freedom#give',
					),
					array(
						'tag'   => 'Clean Water',
						'title' => 'Clean Water',
						'image' => 'krm-theme:images/content/clean-water-1.jpg',
						'alt'   => 'A boy rinsing off under a new hand pump at a brick kiln while other children wait for water',
						'text'  => 'Install hand pumps at brick kilns and villages so families have safe water close to where they live and work.',
						'url'   => '{donate}?ministry=clean-water#give',
					),
					array(
						'tag'   => 'Children',
						'title' => 'Vulnerable Children',
						'image' => 'krm-theme:images/content/orphanages-care-16-crop.jpg',
						'alt'   => 'Children sitting in rows sharing a meal provided by the ministry',
						'text'  => 'Meals, schooling, Sunday school and loving care for orphans and children growing up beside the kilns.',
						'url'   => '{donate}?ministry=children-orphan-care#give',
					),
				)
			),
		),
	),
	'stories'  => array(
		'label'  => __( '9. Stories from the Field', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'    => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Lives changed' ),
			'heading'    => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Stories from the field' ),
			'more_label' => krm_f( 'text', __( 'Link text on every story', 'kingdom-revival' ), 'Read Full Story' ),
			'all_label'  => krm_f( 'text', __( 'Link under the stories (empty = none)', 'kingdom-revival' ), 'More testimonies →' ),
			'all_url'    => krm_f( 'url', __( 'Link under the stories: address', 'kingdom-revival' ), '{testimonies}' ),
			'items'      => krm_list_f(
				__( 'Stories', 'kingdom-revival' ),
				__( 'Story', 'kingdom-revival' ),
				array(
					'quote' => krm_f( 'textarea', __( 'Story (in their own words)', 'kingdom-revival' ) ),
					'name'  => krm_f( 'text', __( 'Name', 'kingdom-revival' ) ),
					'place' => krm_f( 'text', __( 'Who / where', 'kingdom-revival' ) ),
					'image' => krm_f( 'image', __( 'Photo at the top of the card', 'kingdom-revival' ) ),
					'alt'   => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ) ),
					'photo' => krm_f( 'image', __( 'Small round photo of the person (optional; else their initials)', 'kingdom-revival' ) ),
					'url'   => krm_f( 'url', __( '“Read Full Story” link (empty = their testimony on the Testimonials page)', 'kingdom-revival' ) ),
				),
				array(
					array(
						'quote' => 'For eleven years our whole family worked at the kiln to pay a debt that never got smaller. When the ministry cleared it, my children went to school for the first time. Now we work for ourselves.',
						'name'  => 'Yousaf Masih',
						'place' => 'Freed family · Faisalabad district',
						'image' => 'krm-theme:images/content/freedom-after-2.jpg',
						'alt'   => 'A freed family holding an “I am free now” sign with a ministry worker',
						'photo' => '',
						'url'   => '',
					),
					array(
						'quote' => 'My daughter had a fever for weeks and we had no money for a doctor. At the medical camp she was treated, and the team prayed with us. She is well and back at school.',
						'name'  => 'Saima Bibi',
						'place' => 'Answered prayer · Jhang district',
						'image' => 'krm-theme:images/content/medical-health-1.jpg',
						'alt'   => 'A ministry worker visiting and praying with a sick woman at home',
						'photo' => '',
						'url'   => '',
					),
					array(
						'quote' => 'We started with four families praying in my courtyard. Today more than forty people worship together every Sunday, and the village has its own church.',
						'name'  => 'Pastor Javed Masih',
						'place' => 'House church · Faisalabad district',
						'image' => 'krm-theme:images/content/church-planting-6.jpg',
						'alt'   => 'Families gathered on mats for worship beside a wooden cross at a brick kiln',
						'photo' => '',
						'url'   => '',
					),
				),
				__( 'Three look best (a row on computers, a slider on phones).', 'kingdom-revival' )
			),
		),
	),
	'journey'  => array(
		'label'  => __( '10. Our Journey', 'kingdom-revival' ),
		'help'   => __( 'Milestones from 2018 to today, oldest first (a line across on computers, down the page on phones). The full timeline is on the About page.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'    => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Our Journey' ),
			'heading'    => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'From a vision <span class="hl">to a mission</span>', $krm_hl ),
			'intro'      => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Every year is another chapter of God’s faithfulness.' ),
			'link_label' => krm_f( 'text', __( 'Link text (empty = none)', 'kingdom-revival' ), 'Read our full story →' ),
			'link_url'   => krm_f( 'url', __( 'Link', 'kingdom-revival' ), '{about}' ),
			'items'      => krm_list_f(
				__( 'Milestones', 'kingdom-revival' ),
				__( 'Milestone', 'kingdom-revival' ),
				array(
					'year'  => krm_f( 'text', __( 'Year', 'kingdom-revival' ) ),
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
				),
				array(
					array( 'year' => '2018', 'title' => 'The beginning', 'text' => 'Kingdom Revival Ministries is founded in Faisalabad with a burden for evangelism.' ),
					array( 'year' => '2019', 'title' => 'Rooted in prayer', 'text' => 'Prayer meetings and village outreach grow into the first house churches.' ),
					array( 'year' => '2021', 'title' => 'A heart for the forgotten', 'text' => 'Care begins for brick kiln families, widows and children in need.' ),
					array( 'year' => '2022', 'title' => 'Standing with the persecuted', 'text' => 'Encouraging believers who suffer for the name of Jesus.' ),
					array( 'year' => '2025', 'title' => 'Freedom and compassion', 'text' => 'Kiln families freed from debt, clean water hand pumps and Urdu Bibles for new believers.' ),
					array( 'year' => '2026', 'title' => 'Moving forward', 'text' => 'Ministry reaching Lahore and Karachi, with more leaders trained to serve.' ),
				),
				__( 'Up to 6 fit on one line on computers.', 'kingdom-revival' )
			),
		),
	),
	'explorer' => array(
		'label'  => __( '11. Our Ministries (ministry explorer)', 'kingdom-revival' ),
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
	'partner'  => array(
		'label'  => __( '12. Partner With Us', 'kingdom-revival' ),
		'help'   => __( 'Four boxes, each with its own button. The icons follow the box’s position: church, people, heart, praying hands.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Partner With Us' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Stand with us in <span class="hl">the mission</span>', $krm_hl ),
			'intro'   => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Churches, Christian organisations, donors and prayer partners all carry this work with us. Choose the way that fits you.' ),
			'items'   => krm_list_f(
				__( 'Boxes', 'kingdom-revival' ),
				__( 'Box', 'kingdom-revival' ),
				array(
					'title'  => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'   => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
					'button' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ) ),
					'url'    => krm_f( 'url', __( 'Button link', 'kingdom-revival' ) ),
				),
				array(
					array(
						'title'  => 'Churches',
						'text'   => 'Partner as a church family: pray, send teams and support a village church or pastor.',
						'button' => 'Partner as a Church',
						'url'    => '{partner}#church-ministry-partners',
					),
					array(
						'title'  => 'Christian Organisations',
						'text'   => 'Work alongside us on outreach, relief and freedom projects in Pakistan.',
						'button' => 'Partner as an Organisation',
						'url'    => '{partner}#church-ministry-partners',
					),
					array(
						'title'  => 'Donors',
						'text'   => 'Give once or monthly to bring the Gospel, freedom and care to families in need.',
						'button' => 'Become a Monthly Donor',
						'url'    => '{partner}#financial-partner',
					),
					array(
						'title'  => 'Prayer Partners',
						'text'   => 'Receive prayer needs and stand with the ministry and the families we serve.',
						'button' => 'Become a Prayer Partner',
						'url'    => '{partner}#prayer-partners',
					),
				)
			),
		),
	),
	'calc'     => array(
		'label'  => __( '13. Your gift at work (gift calculator)', 'kingdom-revival' ),
		'help'   => __( 'The home page’s “Your gift at work” calculator (slider, quick amounts and what the gift could provide). The amounts and the rows’ costs are fixed: $10 a Bible, $45 a medical camp visit, $40 a child’s schooling. “Give” opens Donate with the chosen amount, for where it is needed most. The “Your gift at work” cards (section below) are shown on ministry and What We Do pages.', 'kingdom-revival' ),
		'fields' => array(
			'heading'    => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Your <span class="hl">gift</span> at work', $krm_hl ),
			'text'       => krm_f( 'text', __( 'Text under the heading', 'kingdom-revival' ), 'Drag the slider and see what your gift could do.' ),
			'list_title' => krm_f( 'text', __( 'List heading', 'kingdom-revival' ), 'Your gift could provide' ),
			'note'       => krm_f( 'textarea', __( 'Small print under the list', 'kingdom-revival' ), 'Each line shows what your full gift could provide on its own. Gifts are used where the need is greatest.' ),
		),
	),
	'news'     => array(
		'label'  => __( '14. Latest News & Updates', 'kingdom-revival' ),
		'help'   => __( 'Shows your three newest posts (their featured image, date, title and excerpt). The section is hidden while there are no posts.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'    => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'News & Updates' ),
			'heading'    => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Latest <span class="hl">News & Updates</span>', $krm_hl ),
			'all_label'  => krm_f( 'text', __( 'All posts link text', 'kingdom-revival' ), 'View all news →' ),
			'all_url'    => krm_f( 'url', __( 'All posts link', 'kingdom-revival' ), '{blog}' ),
			'more_label' => krm_f( 'text', __( 'Card link text', 'kingdom-revival' ), 'Read More' ),
		),
	),
	'final'    => array(
		'label'  => __( '15. Final call to action', 'kingdom-revival' ),
		'help'   => __( 'A full-width banner with three buttons. Leave a button text empty to hide that button.', 'kingdom-revival' ),
		'fields' => array(
			'heading'       => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Together, We Can <span class="hl">Bring Hope</span>', $krm_hl ),
			'text'          => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Pray with us, partner with us and give, so that more families in Pakistan can hear the Gospel and walk in freedom.' ),
			'band_photo'    => krm_f( 'image', __( 'Background photo (seen through the navy overlay; empty = plain colour)', 'kingdom-revival' ), 'krm-theme:images/content/church-planting-2.jpg' ),
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'Pray' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '{partner}#prayer-partners' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'Partner' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{partner}' ),
			'button3_label' => krm_f( 'text', __( 'Third button text (red)', 'kingdom-revival' ), 'Donate' ),
			'button3_url'   => krm_f( 'url', __( 'Third button link', 'kingdom-revival' ), '{donate}#give' ),
		),
	),
	'gift'     => array(
		'label'  => __( 'Your gift at work (Used on the What We Do group pages, not the home page)', 'kingdom-revival' ),
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
	'freedom'  => array(
		'label'  => __( 'Flagship: Slavery & Freedom Ministry (Used on the Slavery & Freedom ministry page and Our Impact, not the home page)', 'kingdom-revival' ),
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
	'cta'      => array(
		'label'  => __( 'Closing verse (Used on ministry pages, not the home page)', 'kingdom-revival' ),
		'fields' => array(
			'quote'        => krm_f( 'textarea', __( 'Verse', 'kingdom-revival' ), '“Go therefore and make disciples of all nations.”' ),
			'cite'         => krm_f( 'text', __( 'Reference', 'kingdom-revival' ), 'Matthew 28:19' ),
			'band_photo'   => krm_f( 'image', __( 'Background photo (seen through the navy overlay; empty = plain colour)', 'kingdom-revival' ), 'krm-theme:images/content/church-planting-2.jpg' ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Become a Partner' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{partner}' ),
		),
	),
	'prayer'   => array(
		'label'  => __( 'Prayer request (Used on the Prayer Request page, not the home page)', 'kingdom-revival' ),
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
	'whatsapp' => array(
		'label'  => __( 'Floating WhatsApp button', 'kingdom-revival' ),
		'fields' => array(
			'label' => krm_f( 'text', __( 'Button description (for screen readers)', 'kingdom-revival' ), 'Chat with us on WhatsApp', __( 'Opens a chat with the WhatsApp number in Settings. Leave empty to hide the button.', 'kingdom-revival' ) ),
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
