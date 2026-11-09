<?php
/**
 * Project detail pages (/projects/{slug}/), one entry per page, all rendered by the single template
 * template-parts/project-page.php (krm_project_page() reads this file).
 *
 * Per page: ministry (slug from inc/data/ministries.php, preselected on Donate), eyebrow, title (H1, <span class="hl"> for the
 * highlighted words), seo_title, seo_desc, intro, image (hero; the matching card's photo), image_alt, need (text),
 * need_image [ ref, alt ], steps ([ title, text ] × 3), gallery ([ ref, alt ], real photos from the ministry's gallery,
 * none repeated on the page), verse, verse_cite, cta.
 * A project card links here when its 'page' (or a dashboard project's slug) matches a key below.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_up = 'https://kingdomrevivalministries.com/wp-content/uploads/';

return array(
	'urdu-bibles'               => array(
		'ministry'   => 'bible-distribution',
		'eyebrow'    => 'Bible Distribution',
		'title'      => 'Urdu Bibles for <span class="hl">New Believers</span>',
		'seo_title'  => 'Urdu Bibles for New Believers',
		'seo_desc'   => 'Help print and deliver Urdu Bibles to new believers and village churches around Faisalabad and Jhang, Pakistan.',
		'intro'      => 'We print and deliver Urdu Bibles to new believers and village churches around Faisalabad and Jhang.',
		'image'      => $krm_up . '2026/07/Urdu-Bible-Distribution-18-scaled.jpg',
		'image_alt'  => 'A young man in a red shirt and shawl holds his new Urdu Bible beside a member of our team',
		'need'       => 'Many new believers in Pakistan’s villages have never owned a Bible in their own language. Without God’s Word in their hands, it is harder to grow in faith, teach their families and stand firm in difficulty.',
		'need_image' => array( $krm_up . '2026/07/Urdu-Bible-Distribution-17-scaled.jpg', 'An elderly man in a white cap receives an Urdu Bible at the door of his home' ),
		'steps'      => array(
			array( 'Print', 'Urdu Bibles are prepared for new believers and village churches.' ),
			array( 'Deliver', 'Our team carries them to families and congregations around Faisalabad and Jhang.' ),
			array( 'Disciple', 'Believers are encouraged to read, grow and share God’s Word.' ),
		),
		'gallery'    => array(
			array( $krm_up . '2026/07/Urdu-Bible-Distribution-10-e1787225652778.jpg', 'A man receives an Urdu Bible at a brick kiln, with more Bibles ready on a table' ),
			array( $krm_up . '2026/07/Urdu-Bible-Distribution-14-e1784536098255.jpg', 'A woman in a yellow scarf holds her new Urdu Bible at the kiln' ),
			array( $krm_up . '2026/07/Urdu-Bible-Distribution-16-e1784536121378.jpg', 'A woman in a patterned shawl receives an Urdu Bible' ),
			array( $krm_up . '2026/07/Urdu-Bible-Distribution-7-e1784535997635.jpg', 'A boy receives his own Urdu Bible at a kiln village' ),
			array( $krm_up . '2026/07/Urdu-Bible-Distribution-8-e1784536009842.jpg', 'An elderly kiln worker is handed an Urdu Bible' ),
		),
		'verse'      => 'Thy word is a lamp unto my feet, and a light unto my path.',
		'verse_cite' => 'Psalm 119:105',
		'cta'        => 'Put God’s Word in waiting hands',
	),
	'freedom-for-kiln-families' => array(
		'ministry'   => 'slavery-freedom',
		'eyebrow'    => 'Slavery & Freedom Ministry',
		'title'      => 'Freedom for <span class="hl">Bonded Kiln Families</span>',
		'seo_title'  => 'Freedom for Bonded Kiln Families',
		'seo_desc'   => 'Help bonded brick kiln families in Pakistan become free from debt and start again with work, a home and Christian care.',
		'intro'      => 'We help bonded brick kiln families become free from debt and start again with work and a home.',
		'image'      => $krm_up . '2026/08/Bricks-kiln-slaves-children-38-scaled.jpg',
		'image_alt'  => 'Brick kiln children sit around a long cloth sharing a meal in a brick-walled courtyard',
		'need'       => 'Many kiln families are held by debts they cannot repay. Parents work long hours and children grow up beside the kilns, while the family’s freedom remains out of reach.',
		'need_image' => array( $krm_up . '2026/08/Bricks-kiln-slaves-children-29-scaled-e1787822836311.jpg', 'A young girl from a brick kiln family sits beside her plate of rice' ),
		'steps'      => array(
			array( 'Meet', 'We visit families at the kilns and listen to their situation.' ),
			array( 'Release', 'Gifts go toward clearing the family’s debt.' ),
			array( 'Rebuild', 'Each family is helped to begin again with work, a home and Christian care.' ),
		),
		'gallery'    => array(
			array( $krm_up . '2026/08/Bricks-kiln-slaves-children-31-scaled.jpg', 'Kiln children raise their hands in worship in a brick courtyard' ),
			array( $krm_up . '2026/08/Bricks-kiln-slaves-children-34-scaled-e1787822803466.jpg', 'A team member serves rice to kiln children' ),
			array( $krm_up . '2026/08/Bricks-kiln-slaves-children-36-scaled-e1787822780752.jpg', 'Kiln children share a meal together on a cloth' ),
			array( $krm_up . '2026/08/Bricks-kiln-slaves-children-39-scaled.jpg', 'Warm clothes laid out for kiln families while children gather to listen' ),
			array( $krm_up . '2026/08/Bricks-kiln-slaves-children-40-scaled.jpg', 'A team member with a boy wearing a new winter jacket at the kiln' ),
			array( $krm_up . '2026/08/Bricks-kiln-slaves-children-30-scaled-e1787822825532.jpg', 'A girl in a cream headscarf holds her plate of food' ),
		),
		'verse'      => 'If the Son therefore shall make you free, ye shall be free indeed.',
		'verse_cite' => 'John 8:36',
		'cta'        => 'Help a family walk free',
	),
	'orphanage-home'            => array(
		'ministry'   => 'children-orphan-care',
		'eyebrow'    => 'Children & Orphan Care',
		'title'      => 'Building a <span class="hl">Home</span> for Orphaned Children',
		'seo_title'  => 'Building a Home for Orphaned Children',
		'seo_desc'   => 'Help build an orphanage in Pakistan where orphaned and underprivileged children can be safe, cared for and raised in the love of Christ.',
		'intro'      => 'We are building an orphanage where orphaned and underprivileged children can be safe, cared for and raised in the love of Christ.',
		'image'      => $krm_up . '2026/08/Orphanages-care-20.jpg',
		'image_alt'  => 'A large group of children raise their hands and cheer together outdoors',
		'need'       => 'Children without family support are especially vulnerable to hunger, missed schooling and exploitation. They need more than a roof: they need safety, daily care and people who will stand beside them.',
		'need_image' => array( $krm_up . '2026/08/Orphanages-care-19.jpg', 'A group of children stand together with a member of our team' ),
		'steps'      => array(
			array( 'Build', 'Create a safe home for children in need.' ),
			array( 'Care', 'Provide shelter, meals and daily support.' ),
			array( 'Raise', 'Give children schooling, Christian teaching and hope for the future.' ),
		),
		'gallery'    => array(
			array( $krm_up . '2026/08/Orphanages-care-10-scaled-e1787818978151.jpg', 'Children sit in long rows sharing a meal on mats' ),
			array( $krm_up . '2026/08/Orphanages-care-11-scaled-e1787818995644.jpg', 'A team member serves rice from a large pot' ),
			array( $krm_up . '2026/08/Orphanages-care-12-scaled-e1787819009333.jpg', 'A girl in a blue dress sits with her plate of food' ),
			array( $krm_up . '2026/08/Orphanages-care-13-scaled-e1787819027587.jpg', 'Two girls enjoy a meal beside their wrapped gifts' ),
			array( $krm_up . '2026/08/Orphanages-care-15-scaled-e1787819059654.jpg', 'Boys sit together sharing a meal' ),
			array( $krm_up . '2026/08/Orphanages-care-9-scaled-e1787818960294.jpg', 'A team member stands beside a boy holding a wrapped gift' ),
		),
		'verse'      => 'A father of the fatherless, and a judge of the widows, is God in his holy habitation.',
		'verse_cite' => 'Psalm 68:5',
		'cta'        => 'Help build a home for children',
	),
);
