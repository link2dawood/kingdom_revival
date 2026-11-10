<?php
/**
 * The ministries ("What We Do"), their two groups, photo galleries and card data — the client's sitemap (KRM PAK.pdf).
 *
 * The one source of ministry content. Setup creates the ministry posts from it, krm_ministries() falls back to it for
 * any field a ministry post has not set, and krm_upgrade_to_sitemap() (inc/upgrade.php) moves older sites to it.
 *
 * Per ministry: title, status (live | hidden: a hidden ministry is created as a draft and shown nowhere until it is
 * published; switching it to live publishes the draft on the next theme version, krm_sync_ministry_status()),
 * coming_soon (optional, Phase 22: its page shows only the hero with "Details about this ministry are coming soon.",
 * Donate and Contact us, and the group's other ministries; untick "Details coming soon" in the editor once the
 * client's text is in), group, tag (label on the card photo), excerpt (short description), summary (card text; empty = the
 * excerpt), focus, points ("How this ministry serves"), sections (extra panels: heading, text, points), photos
 * ([ URL, alt text ]), explorer_image, raised / goal (whole dollars, '' = not set), stats ([ value, label ]),
 * was (the slugs this ministry had before theme 1.10.0, redirected here), order (position on the site — menus, group
 * pages, cards, Donate — in the client's sitemap order; seeds the post's menu_order), home_order; optional: camps
 * ([ date, location, people served ] rows for the camps / projects timeline, hidden when empty), churches ([ name,
 * place, planted ] rows, hidden when empty), gifts ([ amount, title, text ] "How your gift helps" cards), layout
 * ('freedom': the home page's freedom steps and before/after slider) and drafts (fields holding
 * proposed wording marked "DRAFT — CLIENT TO APPROVE", shown as a notice in the ministry editor).
 *
 * Groups (Phase 21): name, short (tab label), order, path (the group page's address, /what-we-do/{path}/; the
 * group's ministries live under it, /what-we-do/{path}/{ministry}/), image (group card and page hero) and intro
 * (the group's short introduction; a [bracketed] value is shown to administrators only, visitors see nothing).
 * Both are edited in Kingdom Revival → Site text → What We Do groups.
 *
 * Text in [square brackets] is a placeholder waiting for the client's wording.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	'groups' => array(
		'gospel-spiritual' => array(
			'name' => 'Gospel & Spiritual Ministries',
			'short' => 'Gospel & Spiritual',
			'order' => 1,
			'path'  => 'gospel-spiritual-ministries',
			'image' => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-10-scaled.jpg',
			'intro' => '[Short introduction to the Gospel & Spiritual Ministries — to be supplied by the client]',
		),
		'compassion-humanitarian' => array(
			'name' => 'Compassion & Humanitarian Ministries',
			'short' => 'Compassion & Humanitarian',
			'order' => 2,
			'path'  => 'compassion-humanitarian-ministries',
			'image' => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-20.jpg',
			'intro' => '[Short introduction to the Compassion & Humanitarian Ministries — to be supplied by the client]',
		),
	),
	'items' => array(
		'evangelism-outreach' => array(
			'title' => 'Evangelism & Outreach',
			'status' => 'live',
			'group' => 'gospel-spiritual',
			'tag' => 'EVANGELISM',
			'excerpt' => 'We take the Gospel to villages and unreached communities across Pakistan. Through open-air gatherings and personal visits, we share the hope of Jesus with people who have rarely heard His name.',
			'summary' => '',
			'focus' => 'Taking the Gospel to villages and unreached communities across Pakistan.',
			'points' => array(
				'Village outreach',
				'Serving unreached communities',
				'Sharing the Gospel across Pakistan',
			),
			'sections' => array(),
			'photos' => array(
				array(
					'krm-theme:images/content/outreach-ministry-1.jpg',
					'A pastor speaking to families at a brick kiln, with bags of food and supplies laid out in front',
				),
				array(
					'krm-theme:images/content/outreach-ministry-2.jpg',
					'Rows of food and supply bags laid out for families at a brick kiln',
				),
				array(
					'krm-theme:images/content/outreach-ministry-3.jpg',
					'A pastor with families at a brick kiln holding their food and supply bags',
				),
				array(
					'krm-theme:images/content/outreach-ministry-4.jpg',
					'Families at a brick kiln sitting with the food and supplies they received',
				),
				array(
					'krm-theme:images/content/outreach-ministry-5.jpg',
					'A woman sitting with bags of food and supplies at a brick kiln',
				),
				array(
					'krm-theme:images/content/outreach-ministry-6.jpg',
					'A pastor handing food bags to a family with a young girl',
				),
				array(
					'krm-theme:images/content/outreach-ministry-7.jpg',
					'A pastor with a family holding their food and supply bags',
				),
				array(
					'krm-theme:images/content/outreach-ministry-8.jpg',
					'A pastor with children holding bags of food and supplies',
				),
				array(
					'krm-theme:images/content/outreach-ministry-9.jpg',
					'A pastor with an elderly man and a woman receiving food bags',
				),
				array(
					'krm-theme:images/content/outreach-ministry-10.jpg',
					'A pastor with a family and their children holding food bags',
				),
				array(
					'krm-theme:images/content/outreach-ministry-11.jpg',
					'Women sitting with the food and supply bags they received',
				),
				array(
					'krm-theme:images/content/outreach-ministry-12.jpg',
					'A woman at a brick kiln with her bags of food and supplies',
				),
			),
			'explorer_image' => 'krm-theme:images/content/outreach-ministry-1.jpg',
			'raised' => '2400',
			'goal' => '8000',
			'gifts' => array(
				array( '$25', 'Gospel tracts for a village', 'Printed Urdu tracts for one outreach day.' ),
				array( '$60', 'A village outreach meeting', 'Sound, transport and materials for one evening meeting.' ),
			),
			'stats' => array(
				array( '80', 'Communities reached' ),
				array( '150', 'Outreach meetings a year' ),
				array( '1200', 'People who heard the Gospel' ),
			),
			'was' => array(
				'outreach-ministry',
			),
			'order' => 1,
			'home_order' => 1,
		),
		'church-planting' => array(
			'title' => 'Church Planting',
			'status' => 'live',
			'group' => 'gospel-spiritual',
			'tag' => 'CHURCH',
			'excerpt' => 'We plant churches and house churches where believers have no place to gather. Each new fellowship becomes a local witness for Christ and a family for its community.',
			'summary' => '',
			'focus' => 'Planting local congregations and house churches in the villages we reach.',
			'points' => array(
				'New congregations and house churches',
				'Pastors trained and walked alongside',
				'A church in every village we reach',
			),
			'sections' => array(),
			'photos' => array(
				array(
					'krm-theme:images/content/church-planting-1.jpg',
					'A pastor preaching to a large gathering seated on mats in a village field',
				),
				array(
					'krm-theme:images/content/church-planting-2.jpg',
					'Villagers seated on mats in an open field, listening to a preacher',
				),
				array(
					'krm-theme:images/content/church-planting-3.jpg',
					'An open-air gathering on mats beside the fields at sunset',
				),
				array(
					'krm-theme:images/content/church-planting-4.jpg',
					'Families seated together outdoors for an evening gathering',
				),
				array(
					'krm-theme:images/content/church-planting-5.jpg',
					'Children and families seated close together at an outdoor gathering',
				),
				array(
					'krm-theme:images/content/church-planting-6.jpg',
					'A preacher standing before families seated beside stacked bricks at a brick kiln',
				),
				array(
					'krm-theme:images/content/church-planting-7.jpg',
					'Families gathered on mats beside rows of bricks at a kiln',
				),
				array(
					'krm-theme:images/content/church-planting-8.jpg',
					'A pastor speaking to a large crowd seated at a brick kiln, the kiln chimney behind',
				),
				array(
					'krm-theme:images/content/church-planting-9.jpg',
					'A large gathering at a brick kiln listening to a pastor',
				),
			),
			'explorer_image' => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-10-scaled.jpg',
			'raised' => '3500',
			'goal' => '12000',
			'gifts' => array(
				array( '$50', 'A month of house-church support', 'Bibles, hymn books and a meeting place for one month.' ),
				array( '$150', 'Seat a new congregation', 'Mats, a sound system and Bibles for a new church.' ),
			),
			'stats' => array(
				array( '200', 'Churches planted incl. house churches' ),
				array( '45', 'Pastors leading new churches' ),
				array( '30', 'Villages with a church' ),
			),
			// Churches and house churches planted: [ name, place, planted ]. Empty = the list is not shown at all.
			'churches' => array(
				array( 'Grace House Church', 'Faisalabad district', '2021' ),
				array( 'New Life Fellowship', 'Jhang district', '2022' ),
				array( 'Living Hope Church', 'Faisalabad district', '2024' ),
			),
			'was' => array(
				'church-planting',
			),
			'order' => 2,
			'home_order' => 2,
		),
		'bible-distribution' => array(
			'title' => 'Bible Distribution',
			'status' => 'live',
			'group' => 'gospel-spiritual',
			'tag' => 'SCRIPTURE',
			'excerpt' => 'We place Urdu Bibles in the hands of families and new believers. God\'s Word gives them a foundation to grow in faith and share it with others.',
			'summary' => '',
			'focus' => 'Sharing Urdu Bibles with families and new believers.',
			'points' => array(
				'Urdu Bible distribution',
				'Families',
				'New believers',
			),
			'sections' => array(),
			'photos' => array(
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-18-scaled.jpg',
					'Urdu Bible distribution',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-17-scaled.jpg',
					'Urdu Bible distribution',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-16-e1784536121378.jpg',
					'Urdu Bible distribution',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-15-e1784536109482.jpg',
					'Urdu Bible distribution',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-14-e1784536098255.jpg',
					'Urdu Bible distribution',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-13-e1784536079985.jpg',
					'Urdu Bible distribution',
				),
				array(
					'krm-theme:images/content/urdu-bible-distribution-12.jpg',
					'Urdu Bible distribution',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-11-e1784536054126.jpg',
					'Urdu Bible distribution',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-10-e1787225652778.jpg',
					'Urdu Bible distribution',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-9-e1784536026463.jpg',
					'Urdu Bible distribution',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-8-e1784536009842.jpg',
					'Urdu Bible distribution',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-7-e1784535997635.jpg',
					'Urdu Bible distribution',
				),
			),
			'explorer_image' => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-18-scaled.jpg',
			'raised' => '1800',
			'goal' => '6500',
			'stats' => array(
				array( '650', 'Bibles distributed' ),
				array( '120', 'New believers given a Bible' ),
				array( '35', 'Villages reached' ),
			),
			// How your gift helps: [ amount, what it gives, text ]. The home page's "Your gift at work" card for this ministry.
			'gifts' => array(
				array( '$10', 'One Urdu Bible', 'Scripture for a family or new believer.' ),
			),
			'was' => array(
				'urdu-bibles',
			),
			'order' => 3,
			'home_order' => 3,
		),
		'leadership-training' => array(
			'title' => 'Leadership Training & Equipping',
			'status' => 'live',
			'group' => 'gospel-spiritual',
			'tag' => 'LEADERSHIP',
			'excerpt' => 'We train and encourage local pastors and leaders to serve their communities faithfully. Equipped with sound teaching, they can shepherd churches and raise up others.',
			'summary' => '',
			'focus' => 'Equipping pastors and leaders to serve local and house churches.',
			'points' => array(
				'Leadership development',
				'Discipleship',
				'Supporting local and house churches',
			),
			'sections' => array(),
			'photos' => array(
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-11-scaled.jpg',
					'Leadership and discipleship gathering',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-10-scaled.jpg',
					'Leadership and discipleship gathering',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-9-scaled.jpg',
					'Leadership and discipleship gathering',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-8.jpg',
					'Leadership and discipleship gathering',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-7-1.jpg',
					'Leadership and discipleship gathering',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-6-1-scaled.jpg',
					'Leadership and discipleship gathering',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-5-1-scaled.jpg',
					'Leadership and discipleship gathering',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-4-1-scaled.jpg',
					'Leadership and discipleship gathering',
				),
			),
			'explorer_image' => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-11-scaled.jpg',
			'raised' => '1500',
			'goal' => '5000',
			'gifts' => array(
				array( '$35', 'Train one leader for a day', 'Teaching, meals and notes for one pastor.' ),
				array( '$120', 'A full training seminar', 'Venue and materials for a group of village pastors.' ),
			),
			'stats' => array(
				array( '45', 'Pastors and leaders trained' ),
				array( '12', 'Training seminars held' ),
				array( '6', 'Districts represented' ),
			),
			'was' => array(
				'leadership-and-discipleship',
			),
			'order' => 4,
			'home_order' => 4,
		),
		'baptisms-discipleship' => array(
			'title' => 'Baptisms & Discipleship',
			'status' => 'live',
			'group' => 'gospel-spiritual',
			'tag' => 'BAPTISM',
			// DRAFT — CLIENT TO APPROVE (excerpt): adds discipleship, from wording already on the site.
			'excerpt' => 'We walk with new believers as they publicly follow Christ through baptism. Ongoing teaching and fellowship help them grow into mature, faithful disciples.',
			'drafts' => array(
				'excerpt',
			),
			'summary' => '',
			'focus' => 'Celebrating new believers as they publicly follow Jesus Christ in baptism.',
			'points' => array(
				'New believers',
				'Public profession of faith',
				'Baptism',
			),
			'sections' => array(),
			'photos' => array(
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-29.jpg',
					'Baptism in a local community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-31.jpg',
					'Baptism in a local community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-30.jpg',
					'Baptism in a local community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-28.jpg',
					'Baptism in a local community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-27.jpg',
					'Baptism in a local community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-26.jpg',
					'Baptism in a local community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-25.jpg',
					'Baptism in a local community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-24.jpg',
					'Baptism in a local community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-23.jpg',
					'Baptism in a local community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-22.jpg',
					'Baptism in a local community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-21-e1784293966685.jpg',
					'Baptism in a local community',
				),
			),
			'explorer_image' => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-29.jpg',
			'raised' => '900',
			'goal' => '3000',
			'gifts' => array(
				array( '$15', 'Discipleship booklet', 'Study material for a new believer.' ),
				array( '$40', 'A baptism service', 'Arrangements for a village baptism day.' ),
			),
			'stats' => array(
				array( '400', 'Baptisms' ),
				array( '60', 'Discipleship groups' ),
				array( '300', 'New believers discipled' ),
			),
			'was' => array(
				'baptisms',
			),
			'order' => 5,
			'home_order' => 5,
		),
		'prayer-deliverance' => array(
			'title' => 'Prayer & Deliverance Ministry',
			'status' => 'live',
			'group' => 'gospel-spiritual',
			'tag' => 'PRAYER',
			'excerpt' => 'We pray with those carrying heavy burdens, fear and spiritual oppression. Standing on God\'s Word, we seek His healing, peace and freedom for every life.',
			'summary' => '',
			'focus' => 'Prayer and compassionate support for people seeking spiritual restoration, freedom and healing.',
			'points' => array(
				'Prayer for the sick',
				'Prayer for the oppressed',
				'Compassionate spiritual support',
			),
			'sections' => array(),
			'photos' => array(
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-10.jpg',
					'Deliverance and healing ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-9.jpg',
					'Deliverance and healing ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-8-scaled.jpg',
					'Deliverance and healing ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-7-scaled.jpg',
					'Deliverance and healing ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-6-scaled-e1787828407656.jpg',
					'Deliverance and healing ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-5-scaled-e1787828305580.jpg',
					'Deliverance and healing ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-4-scaled-e1787828361817.jpg',
					'Deliverance and healing ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-3-scaled-e1787828373981.jpg',
					'Deliverance and healing ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-2-scaled-e1787828386736.jpg',
					'Deliverance and healing ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-1-scaled-e1787828396674.jpg',
					'Deliverance and healing ministry',
				),
			),
			'explorer_image' => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-10.jpg',
			'raised' => '700',
			'goal' => '2500',
			'gifts' => array(
				array( '$20', 'A village prayer meeting', 'Transport and refreshments for one gathering.' ),
				array( '$75', 'A night of worship and prayer', 'Sound and lighting for an all-night prayer meeting.' ),
			),
			'stats' => array(
				array( '100', 'Prayer meetings a year' ),
				array( '25', 'Villages with weekly prayer' ),
				array( '500', 'Prayer requests answered by our team' ),
			),
			'was' => array(
				'deliverance-and-healing',
			),
			'order' => 6,
			'home_order' => 6,
		),
		'youth-or-women' => array(
			'title' => 'Youth Ministry / Women Empowerment',
			// The client has not chosen yet. To show it: change 'hidden' to 'live' (then bump the theme version), or publish
			// the ministry in Ministries. It opens as a "coming soon" page until its text is written.
			'status' => 'hidden',
			'coming_soon' => true,
			'group' => 'gospel-spiritual',
			'tag' => 'YOUTH / WOMEN',
			'excerpt' => 'Bible studies, camps and mentoring for young people, and skills training and fellowship for women in the villages.',
			'summary' => '',
			'focus' => 'Raising up young people and women in faith, skills and leadership.',
			'points' => array(
				'Youth Bible studies, camps and mentoring',
				'Sewing and skills classes for women',
				'Fellowship and prayer groups',
			),
			'sections' => array(),
			'photos' => array(),
			'explorer_image' => '',
			'raised' => '600',
			'goal' => '4000',
			'gifts' => array(
				array( '$30', 'A sewing class place', 'One month of skills training for a woman.' ),
				array( '$50', 'A youth camp place', 'Meals, Bible study and travel for one young person.' ),
			),
			'stats' => array(
				array( '150', 'Young people reached' ),
				array( '40', 'Women in skills classes' ),
				array( '8', 'Youth camps held' ),
			),
			'was' => array(),
			'order' => 7,
			'home_order' => 7,
		),
		'slavery-freedom' => array(
			'title' => 'Slavery & Freedom Ministry',
			'status' => 'live',
			'group' => 'compassion-humanitarian',
			'tag' => 'FREEDOM',
			'excerpt' => 'We stand with families trapped in bonded labour and crushing debt. Your support helps clear what they owe, so they can begin again in freedom and dignity.',
			'summary' => 'Helping families held in debt bondage at brick kilns walk free.',
			'focus' => 'Helping families held in debt bondage at brick kilns find a path to freedom.',
			'points' => array(
				'Support for families in debt bondage',
				'Outreach in brick kiln communities',
				'Help families walk free',
			),
			'sections' => array(),
			'photos' => array(
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-40-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-39-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-38-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-37-scaled-e1787822770768.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-36-scaled-e1787822780752.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-35-scaled-e1787822792740.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-34-scaled-e1787822803466.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-33-scaled-e1787822814979.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-32-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-31-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-30-scaled-e1787822825532.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-29-scaled-e1787822836311.jpg',
					'Children and families in a brick kiln community',
				),
			),
			'explorer_image' => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-38-scaled.jpg',
			'raised' => '4200',
			'goal' => '15000',
			'stats' => array(
				array( '35', 'Families freed from bonded labour' ),
				array( '12', 'Brick kilns visited' ),
				array( '150', 'People given a new start' ),
			),
			// How your gift helps: [ amount, what it gives, text ]. The home page's "Your gift at work" card for this ministry.
			'gifts' => array(
				array( 'Any amount', 'Frees a family', 'Every family’s debt is different — your gift goes toward clearing one and helping them start again.' ),
			),
			// The home page's five freedom steps and before/after slider (Pages → Home → Flagship) are shown on this page.
			'layout' => 'freedom',
			'was' => array(
				'freedom-from-slavery',
			),
			'order' => 8,
			'home_order' => 8,
		),
		'brick-kiln-family-care' => array(
			'title' => 'Brick Kiln Family Care',
			'status' => 'live',
			'group' => 'compassion-humanitarian',
			'tag' => 'FAMILY CARE',
			// DRAFT — CLIENT TO APPROVE (excerpt, summary): widened to the whole family, from wording already on the site.
			'excerpt' => 'We serve families who live and labour beside the brick kilns. Through the Gospel, practical care and regular visits, we remind them that they are seen and loved.',
			'summary' => 'Schooling, Sunday school and care for children, and practical care for families who live and work beside the kilns.',
			'drafts' => array(
				'excerpt',
				'summary',
			),
			'focus' => 'Education, care and the Gospel for children in brick kiln communities.',
			'points' => array(
				'Education for children',
				'Practical care',
				'Gospel outreach in brick kiln communities',
			),
			'sections' => array(),
			'photos' => array(
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-40-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-39-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-38-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-37-scaled-e1787822770768.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-36-scaled-e1787822780752.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-35-scaled-e1787822792740.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-34-scaled-e1787822803466.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-33-scaled-e1787822814979.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-32-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-31-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-30-scaled-e1787822825532.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-29-scaled-e1787822836311.jpg',
					'Children and families in a brick kiln community',
				),
			),
			'explorer_image' => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-39-scaled.jpg',
			'raised' => '2100',
			'goal' => '7000',
			'stats' => array(
				array( '120', 'Kiln families supported' ),
				array( '90', 'Children in school' ),
				array( '12', 'Kilns served' ),
			),
			// How your gift helps: [ amount, what it gives, text ]. The home page's "Your gift at work" card for this ministry.
			'gifts' => array(
				array( '$40', 'A child’s schooling', 'Fees, books and uniform for a kiln child.' ),
			),
			'was' => array(
				'brick-kiln-children',
			),
			'order' => 9,
			'home_order' => 9,
		),
		'children-orphan-care' => array(
			'title' => 'Children & Orphan Care',
			'status' => 'live',
			'group' => 'compassion-humanitarian',
			'tag' => 'CHILDREN',
			'excerpt' => 'We care for orphans and children growing up in hardship. Sunday school, education and loving support help them learn, grow in faith and build a brighter future.',
			'summary' => 'Shelter, food and daily care for orphaned and underprivileged children.',
			'focus' => 'Practical care and support for orphaned and underprivileged children.',
			'points' => array(
				'Shelter and daily care',
				'Food and schooling',
				'Support for orphaned and underprivileged children',
			),
			// DRAFT — CLIENT TO APPROVE (sections): the Sunday School text is the earlier Children Ministry wording.
			'drafts' => array(
				'sections',
			),
			'sections' => array(
				array(
					'heading' => 'Sunday School',
					'text' => 'Sunday School and Bible teaching for children in village and brick kiln communities.',
					'points' => array(
						'Sunday School',
						'Bible teaching for children',
						'Care for children in villages and brick kiln communities',
					),
				),
			),
			'photos' => array(
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-20.jpg',
					'Children receiving care at an orphanage',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-19.jpg',
					'Children receiving care at an orphanage',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-18.jpg',
					'Children receiving care at an orphanage',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-17-scaled-e1787819100112.jpg',
					'Children receiving care at an orphanage',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-16-scaled-e1787819076312.jpg',
					'Children receiving care at an orphanage',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-15-scaled-e1787819059654.jpg',
					'Children receiving care at an orphanage',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-14-scaled-e1787819044976.jpg',
					'Children receiving care at an orphanage',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-13-scaled-e1787819027587.jpg',
					'Children receiving care at an orphanage',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-12-scaled-e1787819009333.jpg',
					'Children receiving care at an orphanage',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-11-scaled-e1787818995644.jpg',
					'Children receiving care at an orphanage',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-10-scaled-e1787818978151.jpg',
					'Children receiving care at an orphanage',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-9-scaled-e1787818960294.jpg',
					'Children receiving care at an orphanage',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-40-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-39-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-38-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-37-scaled-e1787822770768.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-36-scaled-e1787822780752.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-35-scaled-e1787822792740.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-34-scaled-e1787822803466.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-33-scaled-e1787822814979.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-32-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-31-scaled.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-30-scaled-e1787822825532.jpg',
					'Children and families in a brick kiln community',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-29-scaled-e1787822836311.jpg',
					'Children and families in a brick kiln community',
				),
			),
			'explorer_image' => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-20.jpg',
			'raised' => '3800',
			'goal' => '10000',
			'gifts' => array(
				array( '$30', 'A month of meals', 'Daily meals for one child for a month.' ),
				array( '$40', 'A child’s schooling', 'Fees, books and uniform for one child.' ),
				array( '$100', 'A month of care', 'Shelter, food and care for one child for a month.' ),
			),
			'stats' => array(
				array( '500', 'Children supported' ),
				array( '40', 'Children in daily care' ),
				array( '3', 'Care homes and centres' ),
			),
			'was' => array(
				'orphan-and-children-care',
				'children-ministry',
			),
			'order' => 10,
			'home_order' => 10,
		),
		'clean-water' => array(
			'title' => 'Clean Water Projects',
			'status' => 'live',
			'group' => 'compassion-humanitarian',
			'tag' => 'WATER',
			'excerpt' => 'We install hand pumps at brick kilns and in communities in need, so families can reach clean water close to home. Each pump serves daily needs and brings relief to the whole community.',
			'summary' => '',
			'focus' => 'Access to safe drinking water for village and brick kiln communities.',
			'points' => array(
				'Safe drinking water',
				'Village communities',
				'Brick kiln communities',
			),
			'sections' => array(),
			'photos' => array(
				array(
					'krm-theme:images/content/clean-water-project-06.jpg',
					'Families at a brick kiln raising their hands to celebrate a new hand pump',
				),
				array(
					'krm-theme:images/content/clean-water-project-02.jpg',
					'A boy at a brick kiln washing under water from a new hand pump',
				),
				array(
					'krm-theme:images/content/clean-water-project-04.jpg',
					'A man drawing water from a new hand pump beside the "Clean Water, Pure Hope" banner',
				),
				array(
					'krm-theme:images/content/clean-water-project-18.jpg',
					'A man pumping water into a bucket at a newly installed hand pump',
				),
				array(
					'krm-theme:images/content/clean-water-project-24.jpg',
					'Children and families celebrating around a new hand pump with the Clean Water banner',
				),
				array(
					'krm-theme:images/content/clean-water-project-15.jpg',
					'A woman washing a dish under a new hand pump beside the Clean Water banner',
				),
				array(
					'krm-theme:images/content/clean-water-project-20.jpg',
					'A team member standing beside a new hand pump and the Clean Water banner',
				),
				array(
					'krm-theme:images/content/clean-water-project-09.jpg',
					'A crowd of brick kiln families and children gathered at their new hand pump',
				),
				array(
					'krm-theme:images/content/clean-water-project-14.jpg',
					'A woman filling a bucket with clean water at a hand pump in a village lane',
				),
				array(
					'krm-theme:images/content/clean-water-project-01.jpg',
					'A pastor working a new hand pump at a brick kiln while children watch',
				),
				array(
					'krm-theme:images/content/clean-water-project-03.jpg',
					'A smiling boy splashing clean water from a hand pump on his face',
				),
				array(
					'krm-theme:images/content/clean-water-project-05.jpg',
					'A woman pumping clean water at a brick kiln beside the Clean Water banner',
				),
				array(
					'krm-theme:images/content/clean-water-project-07.jpg',
					'Children beside a new hand pump and the Clean Water banner at a brick kiln',
				),
				array(
					'krm-theme:images/content/clean-water-project-08.jpg',
					'A team member standing at a newly built hand pump at a brick kiln',
				),
				array(
					'krm-theme:images/content/clean-water-project-10.jpg',
					'Men, women and children of a brick kiln community at the new hand pump',
				),
				array(
					'krm-theme:images/content/clean-water-project-11.jpg',
					'Brick kiln families waving together beside their new hand pump',
				),
				array(
					'krm-theme:images/content/clean-water-project-12.jpg',
					'Children and adults raising their hands in thanks at a new hand pump',
				),
				array(
					'krm-theme:images/content/clean-water-project-13.jpg',
					'Children cheering with a team member next to the Clean Water banner',
				),
				array(
					'krm-theme:images/content/clean-water-project-16.jpg',
					'A woman drinking from a new hand pump in a narrow village lane',
				),
				array(
					'krm-theme:images/content/clean-water-project-17.jpg',
					'A new hand pump on a concrete base in a village lane, beside the Clean Water banner',
				),
				array(
					'krm-theme:images/content/clean-water-project-19.jpg',
					'A newly installed hand pump on a fresh concrete base in an open field',
				),
				array(
					'krm-theme:images/content/clean-water-project-21.jpg',
					'A team member waving beside a newly installed hand pump in a field',
				),
				array(
					'krm-theme:images/content/clean-water-project-22.jpg',
					'Families from a brick kiln sitting together on mats for the hand pump dedication',
				),
				array(
					'krm-theme:images/content/clean-water-project-23.jpg',
					'Women and children of a brick kiln community gathered for the new hand pump',
				),
				array(
					'krm-theme:images/content/clean-water-project-25.jpg',
					'A community and the team cheering at a new hand pump in a field',
				),
				array(
					'krm-theme:images/content/clean-water-project-26.jpg',
					'A boy raising his arms beside a new hand pump and the Clean Water banner',
				),
				array(
					'krm-theme:images/content/clean-water-project-27.jpg',
					'A child cheering beside a new hand pump and the Clean Water banner',
				),
			),
			'explorer_image' => 'krm-theme:images/content/clean-water-project-06.jpg',
			'raised' => '2600',
			'goal' => '9000',
			'gifts' => array(
				array( '$50', 'Repair a hand pump', 'Parts and labour to repair a village pump.' ),
				array( '$400', 'A new hand pump', 'Clean water for a whole street of families.' ),
			),
			'stats' => array(
				array( '18', 'Hand pumps and wells installed' ),
				array( '2500', 'People with clean water' ),
				array( '15', 'Villages served' ),
			),
			// Projects completed: [ date, location, people served ]. Empty = the timeline is not shown at all.
			'camps' => array(
				array( 'February 2026', 'Hand pump, village near Jhang', '120 families' ),
				array( 'May 2026', 'Well, village near Faisalabad', '200 families' ),
			),
			'was' => array(
				'clean-water-project',
			),
			'order' => 11,
			'home_order' => 11,
		),
		'persecuted-christians' => array(
			'title' => 'Persecuted Christian Support',
			'status' => 'live', // Listed since Phase 22 (client's request); its page says "coming soon" until the client sends text.
			'coming_soon' => true,
			'group' => 'compassion-humanitarian',
			'tag' => 'PERSECUTED CHURCH',
			'excerpt' => 'Practical, legal and prayer support for Christians facing pressure or violence for their faith.',
			'summary' => '',
			'focus' => 'Standing with believers who are persecuted for following Jesus.',
			'points' => array(
				'Emergency food and shelter',
				'Help with legal cases',
				'Prayer and pastoral visits',
			),
			'sections' => array(),
			// Theme 1.22.4: from the client's data folder (no folder for this ministry: photos chosen to fit it).
			'photos' => array(
				array(
					'krm-theme:images/content/persecuted-christians-1.jpg',
					'A pastor visiting an elderly believer at his village home and giving him an Urdu Bible',
				),
				array(
					'krm-theme:images/content/persecuted-christians-2.jpg',
					'A pastor standing with an elderly believer outside his home',
				),
				array(
					'krm-theme:images/content/persecuted-christians-3.jpg',
					'A pastor meeting an elderly believer in his village and giving him a Bible',
				),
			),
			'explorer_image' => 'krm-theme:images/content/persecuted-christians-1.jpg',
			'raised' => '1200',
			'goal' => '5000',
			'gifts' => array(
				array( '$40', 'Emergency food parcel', 'A month of food for a family under pressure.' ),
				array( '$150', 'Legal help', 'Support towards a lawyer for a persecuted believer.' ),
			),
			'stats' => array(
				array( '60', 'Families supported' ),
				array( '25', 'Legal cases helped' ),
				array( '10', 'Emergency relocations' ),
			),
			'was' => array(),
			'order' => 12,
			'home_order' => 12,
		),
		'widows-support' => array(
			'title' => 'Widows Support',
			'status' => 'live',
			'group' => 'compassion-humanitarian',
			'tag' => 'WIDOWS',
			'excerpt' => 'We stand beside widows who often face loss and hardship alone. Through prayer, practical help and Christian fellowship, we help them find hope and belonging.',
			'summary' => 'Support and fellowship for widows in the church and the village.',
			'focus' => 'Support, fellowship and practical help for widows.',
			'points' => array(
				'Support for widows',
				'Fellowship',
				'Practical help in the church and village',
			),
			'sections' => array(),
			'photos' => array(
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Widows-ministry-5-scaled.jpg',
					'Widows ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Widows-ministry-4-scaled.jpg',
					'Widows ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Widows-ministry-3-scaled.jpg',
					'Widows ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Widows-ministry-2-scaled.jpg',
					'Widows ministry',
				),
				array(
					'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Widows-ministry-1-scaled.jpg',
					'Widows ministry',
				),
				array(
					'krm-theme:images/content/widows-ministry-6.jpg',
					'A pastor giving a gift to a widow outside her home',
				),
				array(
					'krm-theme:images/content/widows-ministry-7.jpg',
					'A pastor standing with a widow who holds a gift from the ministry',
				),
			),
			'explorer_image' => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Widows-ministry-5-scaled.jpg',
			'raised' => '1600',
			'goal' => '6000',
			'gifts' => array(
				array( '$25', 'A month of groceries', 'Food essentials for one widow.' ),
				array( '$60', 'A sewing machine share', 'Towards a sewing machine so a widow can earn a living.' ),
			),
			'stats' => array(
				array( '85', 'Widows supported' ),
				array( '30', 'Widows in skills training' ),
				array( '20', 'Villages reached' ),
			),
			'was' => array(
				'widows-ministry',
			),
			'order' => 13,
			'home_order' => 13,
		),
		'medical-health' => array(
			'title' => 'Medical & Health Outreach',
			'status' => 'live',
			'group' => 'compassion-humanitarian',
			'tag' => 'MEDICAL',
			'excerpt' => 'We hold medical camps in brick kiln and village communities with limited access to care. Families receive check-ups and medicine, along with prayer and compassion.',
			'summary' => '',
			'focus' => 'Free medical support for brick kiln workers and village families.',
			'points' => array(
				'Free check-ups',
				'Medicine',
				'Support for brick kiln workers and village families',
			),
			'sections' => array(),
			// Theme 1.22.4: from the client's data folder (no folder for this ministry: photos chosen to fit it).
			'photos' => array(
				array(
					'krm-theme:images/content/medical-health-1.jpg',
					'A pastor visiting a sick woman at her home and praying with her',
				),
				array(
					'krm-theme:images/content/medical-health-2.jpg',
					'Praying beside a sick woman resting on her bed',
				),
				array(
					'krm-theme:images/content/medical-health-3.jpg',
					'An elderly woman in a wheelchair receiving prayer and care',
				),
				array(
					'krm-theme:images/content/medical-health-4.jpg',
					'A pastor praying for an elderly woman who cannot walk',
				),
			),
			'explorer_image' => 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg',
			'raised' => '2900',
			'goal' => '9500',
			'stats' => array(
				array( '14', 'Medical camps held' ),
				array( '3200', 'Patients treated' ),
				array( '20', 'Villages reached' ),
			),
			// How your gift helps: [ amount, what it gives, text ]. The home page's "Your gift at work" card for this ministry.
			'gifts' => array(
				array( '$45', 'Medical camp for a family', 'Check-up and medicine at a kiln or village camp.' ),
			),
			// Medical camps: [ date, location, people served ]. Empty = the camps timeline is not shown at all.
			'camps' => array(
				array( 'March 2026', 'Village near Faisalabad', '350 people' ),
				array( 'June 2026', 'Jhang district', '420 people' ),
				array( 'September 2026', 'Brick kiln community, Faisalabad', '280 people' ),
			),
			'was' => array(
				'free-medical-camps',
			),
			'order' => 14,
			'home_order' => 14,
		),
		'food-relief' => array(
			'title' => 'Food Relief',
			'status' => 'live', // Listed since Phase 22 (client's request); its page says "coming soon" until the client sends text.
			'coming_soon' => true,
			'group' => 'compassion-humanitarian',
			'tag' => 'FOOD',
			'excerpt' => 'Monthly food parcels for widows, kiln families and households in need.',
			'summary' => '',
			'focus' => 'Making sure no family we serve goes hungry.',
			'points' => array(
				'Monthly food parcels',
				'Ramadan and Christmas food drives',
				'Emergency food after a crisis',
			),
			'sections' => array(),
			// Theme 1.22.4: from the client's data folder (no folder for this ministry: photos chosen to fit it).
			'photos' => array(
				array(
					'krm-theme:images/content/food-relief-1.jpg',
					'Rows of food parcels — sacks of flour and bags of groceries — ready for brick kiln families',
				),
				array(
					'krm-theme:images/content/food-relief-2.jpg',
					'A pastor serving a hot meal to children sitting together at a brick kiln',
				),
				array(
					'krm-theme:images/content/food-relief-3.jpg',
					'A mother and her children with their food parcel at the kiln',
				),
				array(
					'krm-theme:images/content/food-relief-4.jpg',
					'A little girl with her plate of rice and a juice box',
				),
				array(
					'krm-theme:images/content/food-relief-5.jpg',
					'Children sharing a meal on mats laid out on a rooftop',
				),
				array(
					'krm-theme:images/content/food-relief-6.jpg',
					'A ministry worker handing out bread to children at a brick kiln',
				),
			),
			'explorer_image' => 'krm-theme:images/content/food-relief-1.jpg',
			'raised' => '1400',
			'goal' => '6000',
			'gifts' => array(
				array( '$25', 'A family food parcel', 'Flour, rice, lentils and oil for a month.' ),
				array( '$100', 'Four food parcels', 'Feed four families for a month.' ),
			),
			'stats' => array(
				array( '900', 'Food parcels given' ),
				array( '300', 'Families fed' ),
				array( '25', 'Villages reached' ),
			),
			'was' => array(),
			'order' => 15,
			'home_order' => 15,
		),
		'disaster-relief' => array(
			'title' => 'Disaster Relief & Recovery Ministry',
			'status' => 'live', // Listed since Phase 22 (client's request); its page says "coming soon" until the client sends text.
			'coming_soon' => true,
			'group' => 'compassion-humanitarian',
			'tag' => 'RELIEF',
			'excerpt' => 'Emergency help and rebuilding for families hit by floods and other disasters.',
			'summary' => '',
			'focus' => 'Bringing relief and hope when disaster strikes.',
			'points' => array(
				'Emergency food, water and shelter',
				'Medical help after floods',
				'Rebuilding homes and livelihoods',
			),
			'sections' => array(),
			// Theme 1.22.4: from the client's data folder (no folder for this ministry: photos chosen to fit it).
			'photos' => array(
				array(
					'krm-theme:images/content/disaster-relief-5.jpg',
					'A ministry worker with a family receiving a relief sack outside their mud-brick home',
				),
				array(
					'krm-theme:images/content/disaster-relief-1.jpg',
					'Warm clothes and blankets laid out, ready to give to brick kiln families',
				),
				array(
					'krm-theme:images/content/disaster-relief-2.jpg',
					'A pastor with children beside piles of donated clothes and blankets',
				),
				array(
					'krm-theme:images/content/disaster-relief-3.jpg',
					'Children at a brick kiln wearing new winter jackets and hats',
				),
				array(
					'krm-theme:images/content/disaster-relief-4.jpg',
					'A crowd of brick kiln children in warm clothes, waving, in front of the kiln chimney',
				),
			),
			'explorer_image' => 'krm-theme:images/content/disaster-relief-5.jpg',
			'raised' => '1100',
			'goal' => '8000',
			'gifts' => array(
				array( '$35', 'Emergency kit', 'Food, water and hygiene items for a family.' ),
				array( '$120', 'Rebuild support', 'Towards repairing a flood-damaged home.' ),
			),
			'stats' => array(
				array( '450', 'Families helped after floods' ),
				array( '3', 'Relief operations' ),
				array( '15', 'Villages reached' ),
			),
			'was' => array(),
			'order' => 16,
			'home_order' => 16,
		),
	),
);
