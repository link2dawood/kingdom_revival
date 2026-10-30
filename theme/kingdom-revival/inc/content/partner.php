<?php
/**
 * Become a Partner page content (page-templates/partner.php). Defaults are the approved design's content.
 *
 * The four partnership names (Prayer Partner, Monthly Partner, Ministry Sponsor, Church / Group Partner) are
 * fixed: the sign-up form and the saved submissions use them.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_up = 'https://kingdomrevivalministries.com/wp-content/uploads/';

/**
 * Fields for one partnership option card.
 *
 * @param string $name    Partnership name (fixed).
 * @param array  $d       Design values: price, text, points, button, badge.
 */
$krm_option = function ( $name, $d ) {
	$fields = array();
	if ( isset( $d['badge'] ) ) {
		/* translators: %s: partnership name */
		$fields['badge'] = krm_f( 'text', sprintf( __( '%s: badge', 'kingdom-revival' ), $name ), $d['badge'] );
	}
	return $fields + array(
		/* translators: %s: partnership name */
		'price'  => krm_f( 'text', sprintf( __( '%s: price line', 'kingdom-revival' ), $name ), $d['price'], __( 'Also shown in the sign-up form.', 'kingdom-revival' ) ),
		/* translators: %s: partnership name */
		'text'   => krm_f( 'textarea', sprintf( __( '%s: text', 'kingdom-revival' ), $name ), $d['text'] ),
		/* translators: %s: partnership name */
		'points' => krm_f( 'textarea', sprintf( __( '%s: check list', 'kingdom-revival' ), $name ), $d['points'], __( 'One point per line.', 'kingdom-revival' ) ),
		/* translators: %s: partnership name */
		'button' => krm_f( 'text', sprintf( __( '%s: button text', 'kingdom-revival' ), $name ), $d['button'] ),
	);
};

$krm_prefixed = function ( $prefix, $fields ) {
	$out = array();
	foreach ( $fields as $key => $f ) {
		$out[ $prefix . '_' . $key ] = $f;
	}
	return $out;
};

return array(
	'hero'    => array(
		'label'  => __( 'Hero', 'kingdom-revival' ),
		'fields' => array(
			'image'         => krm_f( 'image', __( 'Background image', 'kingdom-revival' ), $krm_up . 'elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg' ),
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Become a Partner' ),
			'title'         => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Join the family carrying hope to <em>Pakistan’s forgotten</em>' ),
			'lead'          => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Partners are the people who make this ministry possible — praying faithfully, giving regularly and walking with us as the Gospel reaches villages, kilns and homes.' ),
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'Become a partner today' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '#join' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'See partnership options' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '#options' ),
		),
	),
	'why'     => array(
		'label'  => __( 'Why partner with us', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Why partner with us' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'More than a gift — a relationship' ),
			'intro'   => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'When you partner with us, you become part of the work itself.' ),
			'items'   => krm_list_f(
				__( 'Reasons', 'kingdom-revival' ),
				__( 'Reason', 'kingdom-revival' ),
				array(
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
				),
				array(
					array(
						'title' => 'Faithful, steady support',
						'text'  => 'Regular partnership lets us plan ahead, so children, widows and families can count on care every month.',
					),
					array(
						'title' => 'Stay close to the field',
						'text'  => 'Receive stories, photos and prayer points so you can see what God is doing through your partnership.',
					),
					array(
						'title' => 'Pray and serve together',
						'text'  => 'Join a family of believers standing together for the unreached and the vulnerable in Pakistan.',
					),
				),
				__( 'Icons follow the order: clock, envelope, people.', 'kingdom-revival' )
			),
		),
	),
	'options' => array(
		'label'  => __( 'Partnership options', 'kingdom-revival' ),
		'help'   => __( 'The partnership names are fixed because the sign-up form uses them.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Partnership options' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Choose how you would like to partner' ),
			'intro'   => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Every kind of partnership matters. Pick the one that fits you best — you can always change it later.' ),
		)
		+ $krm_prefixed(
			'prayer',
			$krm_option(
				'Prayer Partner',
				array(
					'price'  => 'Free',
					'text'   => 'Stand with us in prayer for the families, children and churches we serve.',
					'points' => "Prayer updates by email\nAnswered prayers & needs\nJoin our prayer family",
					'button' => 'Choose this',
				)
			)
		)
		+ $krm_prefixed(
			'monthly',
			$krm_option(
				'Monthly Partner',
				array(
					'badge'  => 'Most impact',
					'price'  => 'From $25 / month',
					'text'   => 'A faithful monthly gift lets us plan ahead and keep the work steady.',
					'points' => "Supports all 12 ministries\nStories and updates from the field\nChange or stop any time",
					'button' => 'Choose this',
				)
			)
		)
		+ $krm_prefixed(
			'sponsor',
			$krm_option(
				'Ministry Sponsor',
				array(
					'price'  => 'Choose your amount',
					'text'   => 'Sponsor one ministry you care about most — children, Bibles, widows and more.',
					'points' => "Pick the ministry you support\nFocused updates from that ministry\nSee where your gift goes",
					'button' => 'Choose this',
				)
			)
		)
		+ $krm_prefixed(
			'church',
			$krm_option(
				'Church / Group Partner',
				array(
					'price'  => 'For churches & groups',
					'text'   => 'Partner as a church, small group, school or business and serve together.',
					'points' => "Updates to share with your group\nPrayer points for your church\nPartner as a community",
					'button' => 'Choose this',
				)
			)
		),
	),
	'steps'   => array(
		'label'  => __( 'How it works', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'How it works' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Three simple steps' ),
			'items'   => krm_list_f(
				__( 'Steps', 'kingdom-revival' ),
				__( 'Step', 'kingdom-revival' ),
				array(
					'title' => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'  => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
				),
				array(
					array(
						'title' => 'Choose your partnership',
						'text'  => 'Pray, give monthly, sponsor a ministry or partner as a church.',
					),
					array(
						'title' => 'Sign up below',
						'text'  => 'Tell us a little about yourself so we can welcome you properly.',
					),
					array(
						'title' => 'Stay connected',
						'text'  => 'Receive updates and prayer points, and see the difference you make.',
					),
				),
				__( 'Step numbers are added automatically.', 'kingdom-revival' )
			),
		),
	),
	'signup'  => array(
		'label'  => __( 'Partner sign-up', 'kingdom-revival' ),
		'help'   => __( 'The form fields themselves are fixed so that sign-ups arrive complete.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'         => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Partner sign-up' ),
			'heading'         => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Become a partner today' ),
			'text'            => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'Fill in the form and our team will welcome you as a partner. If you choose a giving partnership, you can set up your monthly gift right after signing up.' ),
			'image'           => krm_f( 'image', __( 'Photo', 'kingdom-revival' ), $krm_up . '2026/08/Orphanages-care-20.jpg' ),
			'image_alt'       => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ), 'Children in our orphan care ministry' ),
			'updates'         => krm_f( 'text', __( 'Updates checkbox text', 'kingdom-revival' ), 'Send me prayer updates and stories from the field by email.' ),
			'button'          => krm_f( 'text', __( 'Sign-up button text', 'kingdom-revival' ), 'Become a partner' ),
			'error'           => krm_f( 'textarea', __( 'Message if sending fails', 'kingdom-revival' ), 'Sorry, something went wrong. Please try again, or email us at {email}.' ),
			'foot'            => krm_f( 'text', __( 'Note under the form', 'kingdom-revival' ), 'We will never share your details with anyone.' ),
			'thanks_title'    => krm_f( 'text', __( 'Thank-you heading', 'kingdom-revival' ), 'Welcome to the family, {name}!', __( '{name} is the partner’s first name.', 'kingdom-revival' ) ),
			'thanks_text'     => krm_f( 'textarea', __( 'Thank-you text', 'kingdom-revival' ), 'Thank you for joining us as a {type}. Our team will be in touch to welcome you and share how your partnership is making a difference.', __( '{type} is the partnership they chose.', 'kingdom-revival' ) ),
			'thanks_give'     => krm_f( 'text', __( 'Thank-you: giving button text', 'kingdom-revival' ), 'Set up your monthly gift' ),
			'thanks_give_url' => krm_f( 'url', __( 'Thank-you: giving button link', 'kingdom-revival' ), '{donate}' ),
			'thanks_read'     => krm_f( 'text', __( 'Thank-you: stories button text', 'kingdom-revival' ), 'Read stories from the field' ),
			'thanks_read_url' => krm_f( 'url', __( 'Thank-you: stories button link', 'kingdom-revival' ), '{blog}' ),
		),
	),
	'faq'     => krm_faq_section(
		'Questions',
		'Partnership FAQ',
		array(
			array( 'Can I change or stop my partnership?', 'Yes. You can change your amount, switch to a different ministry or stop at any time — just contact us and we will take care of it.' ),
			array( 'How is my monthly gift set up?', 'After you sign up, use the “Set up your monthly gift” button to complete your gift through our secure giving page. Choose the monthly option there.' ),
			array( 'Do I have to give money to be a partner?', 'Not at all. Prayer Partners are just as important to us. Prayer is the foundation of everything we do.' ),
			array( 'Can my church or small group partner together?', 'Yes. Choose “Church / Group Partner” and tell us about your church or group. We would love to serve alongside you.' ),
			array( 'How will I hear from you?', 'We will keep in touch by email with stories, prayer points and news from the field. You can unsubscribe any time.' ),
		)
	),
	'verse'   => array(
		'label'  => __( 'Closing verse', 'kingdom-revival' ),
		'fields' => array(
			'image'        => krm_f( 'image', __( 'Background image', 'kingdom-revival' ), $krm_up . '2026/07/Baptisms-29.jpg' ),
			'quote'        => krm_f( 'textarea', __( 'Verse', 'kingdom-revival' ), '“I thank my God in all my remembrance of you … because of your partnership in the gospel from the first day until now.”' ),
			'cite'         => krm_f( 'text', __( 'Reference', 'kingdom-revival' ), 'Philippians 1:3–5' ),
			'button_label' => krm_f( 'text', __( 'Button text', 'kingdom-revival' ), 'Become a partner' ),
			'button_url'   => krm_f( 'url', __( 'Button link', 'kingdom-revival' ), '#join' ),
		),
	),
);
