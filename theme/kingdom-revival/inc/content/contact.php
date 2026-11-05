<?php
/**
 * Contact page content (page-templates/contact.php). Defaults are the approved design's content.
 * Phone, email and address come from Kingdom Revival → Settings.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	'hero'  => array(
		'label'  => __( 'Hero', 'kingdom-revival' ),
		'fields' => array(
			'image'   => krm_f( 'image', __( 'Background image', 'kingdom-revival' ), 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-17-scaled.jpg' ),
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Contact Us' ),
			'title'   => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'We would love to <em>hear from you</em>' ),
			'lead'    => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Whether you want to partner with us, send a prayer request, plan a visit or ask a question — our team will get back to you.' ),
		),
	),
	'cards' => array(
		'label'  => __( 'Contact cards', 'kingdom-revival' ),
		'help'   => __( 'The phone number, email and address shown on the cards are set in Kingdom Revival → Settings.', 'kingdom-revival' ),
		'fields' => array(
			'call_title'  => krm_f( 'text', __( 'Phone card: heading', 'kingdom-revival' ), 'Call us' ),
			'call_text'   => krm_f( 'text', __( 'Phone card: text', 'kingdom-revival' ), 'Call or send us a message' ),
			'email_title' => krm_f( 'text', __( 'Email card: heading', 'kingdom-revival' ), 'Email us' ),
			'email_text'  => krm_f( 'text', __( 'Email card: text', 'kingdom-revival' ), 'Write to us about anything' ),
			'visit_title' => krm_f( 'text', __( 'Address card: heading', 'kingdom-revival' ), 'Visit us' ),
			'whatsapp_title' => krm_f( 'text', __( 'WhatsApp card: heading', 'kingdom-revival' ), 'WhatsApp', __( 'The number is the phone number in Kingdom Revival → Settings.', 'kingdom-revival' ) ),
			'whatsapp_text'  => krm_f( 'text', __( 'WhatsApp card: text', 'kingdom-revival' ), 'Message our ministry team' ),
			'hours_title'    => krm_f( 'text', __( 'Office hours card: heading', 'kingdom-revival' ), 'Office hours' ),
			'hours_text'     => krm_f( 'text', __( 'Office hours card: hours', 'kingdom-revival' ), 'Mon–Sat, 9am–5pm (Pakistan time)', __( 'e.g. Mon–Sat, 9am–5pm (Pakistan time). Visitors don’t see this card until the hours are filled in.', 'kingdom-revival' ) ),
			'hours_note'     => krm_f( 'text', __( 'Office hours card: note', 'kingdom-revival' ), 'Call or WhatsApp before visiting' ),
		),
	),
	'form'  => array(
		'label'  => __( 'Message form', 'kingdom-revival' ),
		'help'   => __( 'The form fields themselves (name, email, topic…) are fixed so that messages arrive complete.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Send a message' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'How can we help?' ),
			'intro'   => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Fill in the form and your message will go straight to our team.' ),
			'button'  => krm_f( 'text', __( 'Send button text', 'kingdom-revival' ), 'Send message' ),
			'note'    => krm_f( 'text', __( 'Note beside the button', 'kingdom-revival' ), 'We never share your details.' ),
			'success' => krm_f( 'textarea', __( 'Message after sending', 'kingdom-revival' ), 'Thank you — your message has been sent. Our team will get back to you soon.' ),
			'error'   => krm_f( 'textarea', __( 'Message if sending fails', 'kingdom-revival' ), 'Sorry, something went wrong. Please try again, or write to us at {email}.' ),
		),
	),
	'side'  => array(
		'label'  => __( 'Side cards', 'kingdom-revival' ),
		'fields' => array(
			'prayer_heading' => krm_f( 'text', __( 'Prayer card: heading', 'kingdom-revival' ), 'Need prayer?' ),
			'prayer_text'    => krm_f( 'textarea', __( 'Prayer card: text', 'kingdom-revival' ), 'Share what is on your heart and our team will stand with you in prayer.' ),
			'prayer_button'  => krm_f( 'text', __( 'Prayer card: button text', 'kingdom-revival' ), 'Send a prayer request', __( 'The button selects “Prayer request” in the form.', 'kingdom-revival' ) ),
			'give_heading'   => krm_f( 'text', __( 'Giving card: heading', 'kingdom-revival' ), 'Support a ministry' ),
			'give_text'      => krm_f( 'textarea', __( 'Giving card: text', 'kingdom-revival' ), 'Your gift brings Bibles, education, orphan care and practical help to families across Pakistan.' ),
			'give_button'    => krm_f( 'text', __( 'Giving card: button text', 'kingdom-revival' ), 'Donate now' ),
			'give_url'       => krm_f( 'url', __( 'Giving card: button link', 'kingdom-revival' ), '{donate}' ),
			'office_heading' => krm_f( 'text', __( 'Office card: heading', 'kingdom-revival' ), 'Office location' ),
			'office_rows'    => krm_list_f(
				__( 'Office card rows', 'kingdom-revival' ),
				__( 'Row', 'kingdom-revival' ),
				array(
					'label' => krm_f( 'text', __( 'Label', 'kingdom-revival' ) ),
					'value' => krm_f( 'text', __( 'Value', 'kingdom-revival' ) ),
				),
				array(
					array(
						'label' => 'City',
						'value' => 'Faisalabad',
					),
					array(
						'label' => 'Province',
						'value' => 'Punjab',
					),
					array(
						'label' => 'Country',
						'value' => 'Pakistan',
					),
				)
			),
		),
	),
	'map'   => array(
		'label'  => __( 'Map', 'kingdom-revival' ),
		'fields' => array(
			'query' => krm_f( 'text', __( 'Place shown on the map', 'kingdom-revival' ), 'Chak No 220, RB Nasrat Colony, Street No. 3, Shadab Puli, Jhang Road, Faisalabad', __( 'An address or place name, as you would type it into Google Maps. Leave empty to hide the map.', 'kingdom-revival' ) ),
			'title' => krm_f( 'text', __( 'Map description (for screen readers)', 'kingdom-revival' ), 'Map showing Kingdom Revival Ministries in Faisalabad' ),
		),
	),
);
