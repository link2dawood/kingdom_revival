<?php
/**
 * News & Blogs page content (home.php). Defaults are the approved design's content.
 * The stories themselves are Posts; the impact numbers come from Kingdom Revival → Settings.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	'hero'   => array(
		'label'  => __( 'Hero', 'kingdom-revival' ),
		'fields' => array(
			'image'   => krm_f( 'image', __( 'Background image', 'kingdom-revival' ), 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-38-scaled.jpg' ),
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'News & Blogs' ),
			'title'   => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Stories of hope <em>from the field</em>' ),
			'lead'    => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Real moments from the villages, kilns and churches we serve — and the people whose lives are being changed by the love of Jesus.' ),
		),
	),
	'list'   => array(
		'label'  => __( 'Story list', 'kingdom-revival' ),
		'help'   => __( 'The newest story is featured at the top. The category buttons are your story categories.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'     => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Latest stories' ),
			'heading'     => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Read, pray and give' ),
			'search'      => krm_f( 'text', __( 'Search box placeholder', 'kingdom-revival' ), 'Search stories…' ),
			'empty_title' => krm_f( 'text', __( 'No results: heading', 'kingdom-revival' ), 'No stories found' ),
			'empty_text'  => krm_f( 'text', __( 'No results: text', 'kingdom-revival' ), 'Try a different word or category.' ),
		),
	),
	'impact' => array(
		'label'  => __( 'Impact strip', 'kingdom-revival' ),
		'help'   => __( 'The numbers are stats 1, 3 and 4 from Kingdom Revival → Settings.', 'kingdom-revival' ),
		'fields' => array(
			'label1' => krm_f( 'text', __( 'First label', 'kingdom-revival' ), 'communities reached' ),
			'label2' => krm_f( 'text', __( 'Second label', 'kingdom-revival' ), 'children supported' ),
			'label3' => krm_f( 'text', __( 'Third label', 'kingdom-revival' ), 'Bibles distributed' ),
		),
	),
	'give'   => krm_give_band_section(
		array(
			'image'   => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-19.jpg',
			'eyebrow' => 'Be part of the next story',
			'heading' => 'Every story here began with someone who gave',
			'text'    => 'Your generosity puts Bibles in hands, children in classrooms and hope in homes across Pakistan.',
			'points'  => "Bibles for families and new believers\nEducation and care for children\nPractical help for widows and the vulnerable",
			'card'    => 'Give to the mission',
		)
	),
);
