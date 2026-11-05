<?php
/**
 * Partner With Us page content (page-templates/partner.php), per the client's sitemap: an overview of five ways to
 * partner, then one section per partner type with what it involves and a sign-up form. Prayer Request has its own
 * page (/partner-with-us/prayer-request/) and Financial Partner leads to Donate.
 *
 * Descriptions and check lists are the approved design's wording where it had one (Prayer Partner, Monthly Partner
 * → Financial Partner, Church / Group Partner → Church & Ministry Partners); [bracketed] text waits for the client.
 * The partner type names are sent with each sign-up and saved with it.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_up = 'https://kingdomrevivalministries.com/wp-content/uploads/';

/**
 * Fields of one partner type section.
 *
 * @param string $label Section label in the editor.
 * @param array  $d     Defaults: title, text, points, form (show the sign-up form: yes/no), button.
 */
$krm_type = function ( $label, $d ) {
	return array(
		'label'  => $label,
		'fields' => array(
			'title'        => krm_f( 'text', __( 'Heading (also the partner type saved with each sign-up)', 'kingdom-revival' ), $d['title'] ),
			'text'         => krm_f( 'textarea', __( 'Short description', 'kingdom-revival' ), $d['text'] ),
			'involves'     => krm_f( 'text', __( 'Small heading above the check list', 'kingdom-revival' ), 'What it involves' ),
			'points'       => krm_f( 'textarea', __( 'What it involves (one point per line)', 'kingdom-revival' ), $d['points'] ),
			'form_heading' => krm_f( 'text', __( 'Form heading', 'kingdom-revival' ), $d['form_heading'] ),
			'button'       => krm_f( 'text', __( 'Form button text', 'kingdom-revival' ), $d['button'] ),
		),
	);
};

return array(
	'hero'      => array(
		'label'  => __( 'Hero', 'kingdom-revival' ),
		'fields' => array(
			'image'         => krm_f( 'image', __( 'Background image', 'kingdom-revival' ), $krm_up . 'elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg' ),
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Partner With Us' ),
			'title'         => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Join the family carrying hope to <em>Pakistan’s forgotten</em>' ),
			'lead'          => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Partners are the people who make this ministry possible — praying faithfully, giving regularly and walking with us as the Gospel reaches villages, kilns and homes.' ),
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'See the ways to partner' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '#ways' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'Support our mission' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{donate}' ),
		),
	),
	'ways'      => array(
		'label'  => __( 'Ways to partner (the five cards)', 'kingdom-revival' ),
		'help'   => __( 'Each card links to its section below, Financial Partner to Donate and Prayer Request to its own page. The card titles are the section headings.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'        => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Ways to partner' ),
			'heading'        => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Choose how you would like to partner' ),
			'intro'          => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Every kind of partnership matters. Pick the one that fits you best — you can always change it later.' ),
			'more'           => krm_f( 'text', __( 'Card link text', 'kingdom-revival' ), 'Find out more →' ),
			'prayer_request' => krm_f( 'text', __( 'Prayer Request card: title', 'kingdom-revival' ), 'Prayer Request' ),
			'prayer_text'    => krm_f( 'textarea', __( 'Prayer Request card: text', 'kingdom-revival' ), 'Our pastors and prayer team pray over every request we receive.' ),
		),
	),
	'prayer'    => $krm_type(
		__( 'Prayer Partners', 'kingdom-revival' ),
		array(
			'title'        => 'Prayer Partners',
			'text'         => 'Stand with us in prayer for the families, children and churches we serve.',
			'points'       => "Prayer updates by email\nAnswered prayers & needs\nJoin our prayer family",
			'form_heading' => 'Become a prayer partner',
			'button'       => 'Join our prayer family',
		)
	),
	'financial' => array(
		'label'  => __( 'Financial Partner', 'kingdom-revival' ),
		'help'   => __( 'Its button opens the Donate page. The form is for pledges: Donate → Upcoming Projects → “Pledge / Get notified” opens it with the project filled in.', 'kingdom-revival' ),
		'fields' => array(
			'title'        => krm_f( 'text', __( 'Heading', 'kingdom-revival' ), 'Financial Partner' ),
			'text'         => krm_f( 'textarea', __( 'Short description', 'kingdom-revival' ), 'A faithful monthly gift lets us plan ahead and keep the work steady.' ),
			'involves'     => krm_f( 'text', __( 'Small heading above the check list', 'kingdom-revival' ), 'What it involves' ),
			'points'       => krm_f( 'textarea', __( 'What it involves (one point per line)', 'kingdom-revival' ), "Supports all our ministries\nStories and updates from the field\nChange or stop any time" ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Support our mission' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '{donate}' ),
			'form_heading' => krm_f( 'text', __( 'Form heading', 'kingdom-revival' ), 'Pledge or get notified about a project' ),
			'button'       => krm_f( 'text', __( 'Form button text', 'kingdom-revival' ), 'Send' ),
		),
	),
	'church'    => $krm_type(
		__( 'Church & Ministry Partners', 'kingdom-revival' ),
		array(
			'title'        => 'Church & Ministry Partners',
			'text'         => 'Partner as a church, small group, school or business and serve together.',
			'points'       => "Updates to share with your group\nPrayer points for your church\nPartner as a community",
			'form_heading' => 'Partner as a church or ministry',
			'button'       => 'Send',
		)
	),
	'volunteer' => $krm_type(
		__( 'Volunteer With Us', 'kingdom-revival' ),
		array(
			'title'        => 'Volunteer With Us',
			'text'         => 'Give your time and skills to serve alongside our team — at medical camps, children’s programmes, outreach events or from home.',
			'points'       => "Helping at medical camps and food distributions\nTeaching and mentoring at children’s programmes\nPrayer, translation, design and online support from anywhere",
			'form_heading' => 'Volunteer with us',
			'button'       => 'Send',
		)
	),
	'form'      => array(
		'label'  => __( 'Sign-up forms', 'kingdom-revival' ),
		'help'   => __( 'Sign-ups are saved under Kingdom Revival → Submissions (“Partner With Us sign-up”) and emailed like the contact form. The fields are fixed.', 'kingdom-revival' ),
		'fields' => array(
			'success' => krm_f( 'textarea', __( 'Message after sending', 'kingdom-revival' ), 'Thank you! Our team will be in touch to welcome you.' ),
			'error'   => krm_f( 'textarea', __( 'Message if sending fails', 'kingdom-revival' ), 'Sorry, something went wrong. Please try again, or email us at {email}.' ),
			'foot'    => krm_f( 'text', __( 'Note under the forms', 'kingdom-revival' ), 'We will never share your details with anyone.' ),
		),
	),
	'faq'       => krm_faq_section(
		'Questions',
		'Partnership FAQ',
		array(
			array( 'Can I change or stop my partnership?', 'Yes. You can change your amount, switch to a different ministry or stop at any time — just contact us and we will take care of it.' ),
			array( 'Do I have to give money to be a partner?', 'Not at all. Prayer Partners are just as important to us. Prayer is the foundation of everything we do.' ),
			array( 'Can my church or small group partner together?', 'Yes. Choose “Church & Ministry Partners” and tell us about your church or group. We would love to serve alongside you.' ),
			array( 'How will I hear from you?', 'We will keep in touch by email with stories, prayer points and news from the field. You can unsubscribe any time.' ),
		)
	),
	'verse'     => array(
		'label'  => __( 'Closing verse', 'kingdom-revival' ),
		'fields' => array(
			'image'        => krm_f( 'image', __( 'Background image', 'kingdom-revival' ), $krm_up . '2026/07/Baptisms-29.jpg' ),
			'quote'        => krm_f( 'textarea', __( 'Verse', 'kingdom-revival' ), '“I thank my God in all my remembrance of you … because of your partnership in the gospel from the first day until now.”' ),
			'cite'         => krm_f( 'text', __( 'Reference', 'kingdom-revival' ), 'Philippians 1:3–5' ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Become a partner' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '#ways' ),
		),
	),
);
