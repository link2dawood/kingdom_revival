<?php
/**
 * Donate page content (page-templates/donate.php). Defaults are the approved design's content.
 * Bank, PayPal and receiver details come from Kingdom Revival → Settings.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_up     = 'https://kingdomrevivalministries.com/wp-content/uploads/';
$krm_amount = __( 'Write {amount} where the chosen gift amount should appear.', 'kingdom-revival' );

return array(
	'hero'    => array(
		'label'  => __( 'Hero', 'kingdom-revival' ),
		'fields' => array(
			'image'         => krm_f( 'image', __( 'Background image', 'kingdom-revival' ), $krm_up . '2026/08/Bricks-kiln-slaves-children-40-scaled.jpg' ),
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Donate' ),
			'title'         => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Your gift brings <em>hope</em> to Pakistan’s forgotten' ),
			'lead'          => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Every gift helps us preach the Gospel, care for orphans and widows, educate brick kiln children and put Bibles in waiting hands.' ),
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'Give now' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '#give' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'Become a monthly partner' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{partner}' ),
		),
	),
	'gift'    => array(
		'label'  => __( 'Step 1: choose your gift', 'kingdom-revival' ),
		'help'   => __( 'The ministries in “Where should your gift go?” are your published ministries.', 'kingdom-revival' ),
		'fields' => array(
			'step'           => krm_f( 'text', __( 'Step heading', 'kingdom-revival' ), 'Choose your gift' ),
			'frequency'      => krm_f( 'text', __( 'Frequency label', 'kingdom-revival' ), 'How often?' ),
			'amount_label'   => krm_f( 'text', __( 'Amount label', 'kingdom-revival' ), 'Amount (USD)' ),
			'amounts'        => krm_f( 'text', __( 'Amounts (US dollars)', 'kingdom-revival' ), '25, 50, 100, 250, 500', __( 'Numbers separated by commas. “Other” is always added.', 'kingdom-revival' ) ),
			'default_amount' => krm_f( 'text', __( 'Amount selected at first', 'kingdom-revival' ), '50' ),
			'fund_label'     => krm_f( 'text', __( 'Ministry choice label', 'kingdom-revival' ), 'Where should your gift go?' ),
			'trust'          => krm_f( 'textarea', __( 'Check list under the form', 'kingdom-revival' ), "Goes directly to the ministry in Pakistan\nGive from anywhere in the world\nQuestions? Call {phone}", __( 'One point per line.', 'kingdom-revival' ) ),
		),
	),
	'methods' => array(
		'label'  => __( 'Step 2: ways to give', 'kingdom-revival' ),
		'help'   => __( 'Account numbers and the receiver’s name are set in Kingdom Revival → Settings.', 'kingdom-revival' ),
		'fields' => array(
			'step'           => krm_f( 'text', __( 'Step heading', 'kingdom-revival' ), 'Choose how to give' ),
			'paypal_heading' => krm_f( 'text', __( 'PayPal: heading', 'kingdom-revival' ), 'Give with PayPal' ),
			'paypal_text'    => krm_f( 'html', __( 'PayPal: text', 'kingdom-revival' ), 'The fastest way to give from anywhere. Pay with your PayPal balance or a debit or credit card. Your amount of {amount} is filled in for you.', $krm_amount ),
			'paypal_button'  => krm_f( 'text', __( 'PayPal: button text', 'kingdom-revival' ), 'Give {amount} with PayPal', $krm_amount ),
			'paypal_monthly' => krm_f( 'textarea', __( 'PayPal: note for monthly gifts', 'kingdom-revival' ), 'PayPal.me sends one-time payments. For a monthly gift, please give through PayPal each month or set up a bank standing order.', __( 'Shown when “Monthly” is chosen and no PayPal account email is set in Settings.', 'kingdom-revival' ) ),
			'bank_heading'   => krm_f( 'text', __( 'Bank: heading', 'kingdom-revival' ), 'Give by bank transfer' ),
			'bank_text'      => krm_f( 'html', __( 'Bank: text', 'kingdom-revival' ), 'Send {amount} from your bank using the details below. Tap <strong>Copy</strong> to copy a detail.', $krm_amount ),
			'bank_note'      => krm_f( 'textarea', __( 'Bank: note', 'kingdom-revival' ), 'International transfers may take a few working days. Please add the ministry name in your payment reference.' ),
			'receiver_place' => krm_f( 'text', __( 'Western Union / MoneyGram: receiver’s location', 'kingdom-revival' ), 'Faisalabad, Punjab, Pakistan' ),
			'wu_heading'     => krm_f( 'text', __( 'Western Union: heading', 'kingdom-revival' ), 'Send with Western Union' ),
			'wu_text'        => krm_f( 'html', __( 'Western Union: text', 'kingdom-revival' ), 'Send {amount} from a Western Union agent near you or online.', $krm_amount ),
			'wu_steps'       => krm_f( 'textarea', __( 'Western Union: steps', 'kingdom-revival' ), "Send to the receiver below, payable in Faisalabad, Pakistan.\nKeep your tracking number (MTCN).\nEmail us the MTCN with “Let us know about your gift” below so we can collect it.", __( 'One step per line.', 'kingdom-revival' ) ),
			'wu_button'      => krm_f( 'text', __( 'Western Union: button text', 'kingdom-revival' ), 'Go to Western Union' ),
			'wu_url'         => krm_f( 'url', __( 'Western Union: button link', 'kingdom-revival' ), 'https://www.westernunion.com/' ),
			'mg_heading'     => krm_f( 'text', __( 'MoneyGram: heading', 'kingdom-revival' ), 'Send with MoneyGram' ),
			'mg_text'        => krm_f( 'html', __( 'MoneyGram: text', 'kingdom-revival' ), 'Send {amount} from a MoneyGram location or online.', $krm_amount ),
			'mg_steps'       => krm_f( 'textarea', __( 'MoneyGram: steps', 'kingdom-revival' ), "Send to the receiver below, payable in Faisalabad, Pakistan.\nKeep your reference number.\nEmail us the reference number with “Let us know about your gift” below so we can collect it.", __( 'One step per line.', 'kingdom-revival' ) ),
			'mg_button'      => krm_f( 'text', __( 'MoneyGram: button text', 'kingdom-revival' ), 'Go to MoneyGram' ),
			'mg_url'         => krm_f( 'url', __( 'MoneyGram: button link', 'kingdom-revival' ), 'https://www.moneygram.com/' ),
		),
	),
	'notify'  => array(
		'label'  => __( 'Step 3: let us know', 'kingdom-revival' ),
		'fields' => array(
			'step'            => krm_f( 'text', __( 'Step heading', 'kingdom-revival' ), 'Let us know' ),
			'text'            => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'After you give, send us a quick email so we can thank you, confirm your gift and use it exactly where you asked. Your gift details are filled in for you.' ),
			'email_button'    => krm_f( 'text', __( 'Email button text', 'kingdom-revival' ), 'Let us know about your gift', __( 'This button opens an email to the ministry with the gift details filled in.', 'kingdom-revival' ) ),
			'whatsapp_button' => krm_f( 'text', __( 'WhatsApp button text', 'kingdom-revival' ), 'WhatsApp us' ),
		),
	),
	'impact'  => array(
		'label'  => __( 'Where your generosity goes', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Your gift at work' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Where your generosity goes' ),
			'intro'   => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Gifts given “where it is needed most” are used across all twelve ministries.' ),
			'cards'   => krm_list_f(
				__( 'Cards', 'kingdom-revival' ),
				__( 'Card', 'kingdom-revival' ),
				array(
					'image'     => krm_f( 'image', __( 'Photo', 'kingdom-revival' ) ),
					'image_alt' => krm_f( 'text', __( 'Photo description (alt text)', 'kingdom-revival' ) ),
					'title'     => krm_f( 'text', __( 'Title', 'kingdom-revival' ) ),
					'text'      => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ) ),
				),
				array(
					array(
						'image'     => $krm_up . '2026/07/Urdu-Bible-Distribution-18-scaled.jpg',
						'image_alt' => 'Bibles in Urdu',
						'title'     => 'Bibles in Urdu',
						'text'      => 'Scripture for new believers, families and house churches.',
					),
					array(
						'image'     => $krm_up . '2026/08/Bricks-kiln-slaves-children-39-scaled.jpg',
						'image_alt' => 'Brick kiln children',
						'title'     => 'Brick kiln children',
						'text'      => 'Education, care and the Gospel for children beside the kilns.',
					),
					array(
						'image'     => $krm_up . '2026/08/Orphanages-care-20.jpg',
						'image_alt' => 'Orphan care',
						'title'     => 'Orphan care',
						'text'      => 'Shelter, meals and schooling for orphaned children.',
					),
					array(
						'image'     => $krm_up . '2026/08/Widows-ministry-5-scaled.jpg',
						'image_alt' => 'Widows & families',
						'title'     => 'Widows & families',
						'text'      => 'Practical help and fellowship for those in need.',
					),
				)
			),
		),
	),
	'faq'     => krm_faq_section(
		'Questions',
		'Giving FAQ',
		array(
			array( 'Which payment method should I use?', 'PayPal is the quickest way to give from anywhere in the world. Bank transfer works well for larger or regular gifts. Western Union and MoneyGram are helpful if you prefer to send cash transfers.' ),
			array( 'How do I give monthly?', 'Set up a standing order or recurring transfer with your bank using the account details above, or send your gift through PayPal each month. Let us know so we can thank you and keep you updated.' ),
			array( 'How do I tell you what my gift is for?', 'Choose the ministry in the gift form, then use “Let us know about your gift” to email us. Adding the ministry name in your bank or PayPal reference also helps.' ),
			array( 'Who can I contact with questions?', 'Call or WhatsApp us on {phone}, or email {email}. We are happy to help.' ),
		)
	),
);
