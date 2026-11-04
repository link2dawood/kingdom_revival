<?php
/**
 * The ministries ("What We Do"), their two groups, photo galleries and card data — the client's sitemap (KRM PAK.pdf).
 *
 * The one source of ministry content. Setup creates the ministry posts from it, krm_ministries() falls back to it for
 * any field a ministry post has not set, and krm_upgrade_to_sitemap() (inc/upgrade.php) moves older sites to it.
 *
 * Per ministry: title, status (live | hidden: a hidden ministry is created as a draft and shown nowhere until it is
 * published), group, tag (label on the card photo), excerpt (short description), summary (card text; empty = the
 * excerpt), focus, points ("How this ministry serves"), sections (extra panels: heading, text, points), photos
 * ([ URL, alt text ]), explorer_image, raised / goal (whole dollars, '' = not set), stats ([ value, label ]),
 * was (the slugs this ministry had before theme 1.10.0, redirected here), menu_order, home_order; optional: camps
 * ([ date, location, people served ] rows for the camps / projects timeline, hidden when empty), churches ([ name,
 * place, planted ] rows, hidden when empty), gifts ([ amount, title, text ] "How your gift helps" cards), layout
 * ('freedom': the home page's freedom steps and before/after slider) and drafts (fields holding
 * proposed wording marked "DRAFT — CLIENT TO APPROVE", shown as a notice in the ministry editor).
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
		),
		'compassion-humanitarian' => array(
			'name' => 'Compassion & Humanitarian Ministries',
			'short' => 'Compassion & Humanitarian',
			'order' => 2,
		),
	),
	'items' => array(
		'evangelism-outreach' => array(
			'title' => 'Evangelism & Outreach',
			'status' => 'live',
			'group' => 'gospel-spiritual',
			'tag' => 'EVANGELISM',
			'excerpt' => 'Taking the Gospel to villages and unreached communities across Pakistan.',
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
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			'was' => array(
				'outreach-ministry',
			),
			'menu_order' => 1,
			'home_order' => 1,
		),
		'church-planting' => array(
			'title' => 'Church Planting',
			'status' => 'live',
			'group' => 'gospel-spiritual',
			'tag' => 'CHURCH',
			'excerpt' => 'Local congregations and house churches, led by pastors we train and walk alongside.',
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
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			// Churches and house churches planted: [ name, place, planted ]. Empty = the list is not shown at all.
			'churches' => array(),
			'was' => array(
				'church-planting',
			),
			'menu_order' => 2,
			'home_order' => 2,
		),
		'bible-distribution' => array(
			'title' => 'Bible Distribution',
			'status' => 'live',
			'group' => 'gospel-spiritual',
			'tag' => 'SCRIPTURE',
			'excerpt' => 'Placing Urdu Bibles in the hands of families and new believers.',
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
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			// How your gift helps: [ amount, what it gives, text ]. The home page's "Your gift at work" card for this ministry.
			'gifts' => array(
				array( '$10', 'One Urdu Bible', 'Scripture for a family or new believer.' ),
			),
			'was' => array(
				'urdu-bibles',
			),
			'menu_order' => 3,
			'home_order' => 3,
		),
		'leadership-training' => array(
			'title' => 'Leadership Training & Equipping',
			'status' => 'live',
			'group' => 'gospel-spiritual',
			'tag' => 'LEADERSHIP',
			'excerpt' => 'Equipping pastors and leaders to shepherd local and house churches.',
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
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			'was' => array(
				'leadership-and-discipleship',
			),
			'menu_order' => 4,
			'home_order' => 4,
		),
		'baptisms-discipleship' => array(
			'title' => 'Baptisms & Discipleship',
			'status' => 'live',
			'group' => 'gospel-spiritual',
			'tag' => 'BAPTISM',
			// DRAFT — CLIENT TO APPROVE (excerpt): adds discipleship, from wording already on the site.
			'excerpt' => 'New believers publicly following Jesus Christ in baptism, and discipled to grow in their faith.',
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
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			'was' => array(
				'baptisms',
			),
			'menu_order' => 5,
			'home_order' => 5,
		),
		'prayer-deliverance' => array(
			'title' => 'Prayer & Deliverance Ministry',
			'status' => 'live',
			'group' => 'gospel-spiritual',
			'tag' => 'PRAYER',
			'excerpt' => 'Prayer for the sick and oppressed in the power of Jesus Christ.',
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
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			'was' => array(
				'deliverance-and-healing',
			),
			'menu_order' => 6,
			'home_order' => 6,
		),
		'youth-or-women' => array(
			'title' => 'Youth Ministry / Women Empowerment',
			'status' => 'hidden',
			'group' => 'gospel-spiritual',
			'tag' => 'YOUTH / WOMEN',
			'excerpt' => '[Short description — to be supplied by the client]',
			'summary' => '',
			'focus' => '[Ministry focus — to be supplied by the client]',
			'points' => array(
				'[How this ministry serves — to be supplied by the client]',
			),
			'sections' => array(),
			'photos' => array(),
			'explorer_image' => '',
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			'was' => array(),
			'menu_order' => 7,
			'home_order' => 7,
		),
		'slavery-freedom' => array(
			'title' => 'Slavery & Freedom Ministry',
			'status' => 'live',
			'group' => 'compassion-humanitarian',
			'tag' => 'FREEDOM',
			'excerpt' => 'Missions that help families held in debt bondage at brick kilns walk free.',
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
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			// How your gift helps: [ amount, what it gives, text ]. The home page's "Your gift at work" card for this ministry.
			'gifts' => array(
				array( '[$ ]', 'Frees a family', 'Clears a bonded family’s debt and helps them start again.' ),
			),
			// The home page's five freedom steps and before/after slider (Pages → Home → Flagship) are shown on this page.
			'layout' => 'freedom',
			'was' => array(
				'freedom-from-slavery',
			),
			'menu_order' => 8,
			'home_order' => 8,
		),
		'brick-kiln-family-care' => array(
			'title' => 'Brick Kiln Family Care',
			'status' => 'live',
			'group' => 'compassion-humanitarian',
			'tag' => 'FAMILY CARE',
			// DRAFT — CLIENT TO APPROVE (excerpt, summary): widened to the whole family, from wording already on the site.
			'excerpt' => 'Care, education and the Gospel for families who live and work beside the brick kilns.',
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
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			// How your gift helps: [ amount, what it gives, text ]. The home page's "Your gift at work" card for this ministry.
			'gifts' => array(
				array( '[$ ]', 'A child’s schooling', 'Fees, books and uniform for a kiln child.' ),
			),
			'was' => array(
				'brick-kiln-children',
			),
			'menu_order' => 9,
			'home_order' => 9,
		),
		'children-orphan-care' => array(
			'title' => 'Children & Orphan Care',
			'status' => 'live',
			'group' => 'compassion-humanitarian',
			'tag' => 'CHILDREN',
			'excerpt' => 'Shelter, food, schooling and daily care for orphaned and underprivileged children.',
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
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			'was' => array(
				'orphan-and-children-care',
				'children-ministry',
			),
			'menu_order' => 10,
			'home_order' => 10,
		),
		'clean-water' => array(
			'title' => 'Clean Water Projects',
			'status' => 'live',
			'group' => 'compassion-humanitarian',
			'tag' => 'WATER',
			'excerpt' => 'Safe drinking water for villages and kiln communities that go without.',
			'summary' => '',
			'focus' => 'Access to safe drinking water for village and brick kiln communities.',
			'points' => array(
				'Safe drinking water',
				'Village communities',
				'Brick kiln communities',
			),
			'sections' => array(),
			'photos' => array(),
			'explorer_image' => 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg',
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			// Projects completed: [ date, location, people served ]. Empty = the timeline is not shown at all.
			'camps' => array(),
			'was' => array(
				'clean-water-project',
			),
			'menu_order' => 11,
			'home_order' => 11,
		),
		'persecuted-christians' => array(
			'title' => 'Persecuted Christian Support',
			'status' => 'hidden', // Hidden until the client supplies the description (Phase 19 follow-up).
			'group' => 'compassion-humanitarian',
			'tag' => 'PERSECUTED CHURCH',
			'excerpt' => '[Short description — to be supplied by the client]',
			'summary' => '',
			'focus' => '[Ministry focus — to be supplied by the client]',
			'points' => array(
				'[How this ministry serves — to be supplied by the client]',
			),
			'sections' => array(),
			'photos' => array(),
			'explorer_image' => '',
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			'was' => array(),
			'menu_order' => 12,
			'home_order' => 12,
		),
		'widows-support' => array(
			'title' => 'Widows Support',
			'status' => 'live',
			'group' => 'compassion-humanitarian',
			'tag' => 'WIDOWS',
			'excerpt' => 'Support, fellowship and practical help for widows in the church and village.',
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
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			'was' => array(
				'widows-ministry',
			),
			'menu_order' => 13,
			'home_order' => 13,
		),
		'medical-health' => array(
			'title' => 'Medical & Health Outreach',
			'status' => 'live',
			'group' => 'compassion-humanitarian',
			'tag' => 'MEDICAL',
			'excerpt' => 'Free check-ups and medicine for brick kiln workers and village families.',
			'summary' => '',
			'focus' => 'Free medical support for brick kiln workers and village families.',
			'points' => array(
				'Free check-ups',
				'Medicine',
				'Support for brick kiln workers and village families',
			),
			'sections' => array(),
			'photos' => array(),
			'explorer_image' => 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg',
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			// How your gift helps: [ amount, what it gives, text ]. The home page's "Your gift at work" card for this ministry.
			'gifts' => array(
				array( '$45', 'Medical camp for a family', 'Check-up and medicine at a kiln or village camp.' ),
			),
			// Medical camps: [ date, location, people served ]. Empty = the camps timeline is not shown at all.
			'camps' => array(),
			'was' => array(
				'free-medical-camps',
			),
			'menu_order' => 14,
			'home_order' => 14,
		),
		'food-relief' => array(
			'title' => 'Food Relief',
			'status' => 'hidden', // Hidden until the client supplies the description (Phase 19 follow-up).
			'group' => 'compassion-humanitarian',
			'tag' => 'FOOD',
			'excerpt' => '[Short description — to be supplied by the client]',
			'summary' => '',
			'focus' => '[Ministry focus — to be supplied by the client]',
			'points' => array(
				'[How this ministry serves — to be supplied by the client]',
			),
			'sections' => array(),
			'photos' => array(),
			'explorer_image' => '',
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			'was' => array(),
			'menu_order' => 15,
			'home_order' => 15,
		),
		'disaster-relief' => array(
			'title' => 'Disaster Relief & Recovery Ministry',
			'status' => 'hidden', // Hidden until the client supplies the description (Phase 19 follow-up).
			'group' => 'compassion-humanitarian',
			'tag' => 'RELIEF',
			'excerpt' => '[Short description — to be supplied by the client]',
			'summary' => '',
			'focus' => '[Ministry focus — to be supplied by the client]',
			'points' => array(
				'[How this ministry serves — to be supplied by the client]',
			),
			'sections' => array(),
			'photos' => array(),
			'explorer_image' => '',
			'raised' => '',
			'goal' => '',
			'stats' => array(),
			'was' => array(),
			'menu_order' => 16,
			'home_order' => 16,
		),
	),
);
