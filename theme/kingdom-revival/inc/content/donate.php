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
			'amounts'        => krm_f( 'text', __( 'Amounts (US dollars)', 'kingdom-revival' ), '25, 50, 100, 250', __( 'Numbers separated by commas. “Other” is always added.', 'kingdom-revival' ) ),
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
			'text'            => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), 'After you give, send us your gift details so we can thank you, confirm your gift and use it exactly where you asked. Your gift details are filled in for you.' ),
			'email_button'    => krm_f( 'text', __( 'Send button text', 'kingdom-revival' ), 'Let us know about your gift', __( 'This button sends the donor details, amount, ministry and payment method to the ministry (KRM_FORMS_TO_EMAIL) as a “Donation Intent” and saves it under Form Submissions. It is not a payment: card details are never asked for.', 'kingdom-revival' ) ),
			'success'         => krm_f( 'textarea', __( 'Message after sending', 'kingdom-revival' ), 'Thank you for your gift intention. Our team will contact you with the next steps.' ),
			'error'           => krm_f( 'textarea', __( 'Message if sending fails', 'kingdom-revival' ), 'Sorry, your message couldn’t be sent. Please try again or WhatsApp us at {phone}.' ),
			'whatsapp_button' => krm_f( 'text', __( 'WhatsApp button text', 'kingdom-revival' ), 'WhatsApp us' ),
		),
	),
	'thanks'  => array(
		'label'  => __( 'Thank-you view (after a PayPal gift)', 'kingdom-revival' ),
		'help'   => __( 'Shown instead of the hero and the giving steps when a donor returns from PayPal’s checkout. The gift summary (amount, monthly, ministry) is filled in automatically.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'       => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Thank you' ),
			'title'         => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Thank you for your <em>gift</em>' ),
			'lead'          => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Your generosity brings the hope of Jesus to Pakistan’s forgotten.' ),
			'text'          => krm_f( 'textarea', __( 'Text', 'kingdom-revival' ), "PayPal has emailed you a receipt for your payment. As soon as PayPal confirms your gift to us, we will email you our own thank-you and receipt.
We pray for everyone who gives, and your gift will be used exactly where you asked.", __( 'One paragraph per line.', 'kingdom-revival' ) ),
			'monthly_text'  => krm_f( 'textarea', __( 'Extra text for monthly gifts', 'kingdom-revival' ), 'Your monthly gift will be taken automatically each month. You can change or cancel it at any time in your PayPal account, under automatic payments.' ),
			'button1_label' => krm_f( 'text', __( 'First button text', 'kingdom-revival' ), 'Read stories from the field' ),
			'button1_url'   => krm_f( 'url', __( 'First button link', 'kingdom-revival' ), '{blog}' ),
			'button2_label' => krm_f( 'text', __( 'Second button text', 'kingdom-revival' ), 'Back to the home page' ),
			'button2_url'   => krm_f( 'url', __( 'Second button link', 'kingdom-revival' ), '{home}' ),
		),
	),
	'projects' => array(
		'label'  => __( 'Our Projects', 'kingdom-revival' ),
		'help'   => __( 'The projects are added under Projects in the dashboard (current or upcoming). Until one is published, the placeholder projects of the theme show. The first three current projects also show on the home page.', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow'        => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Our projects' ),
			'heading'        => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Give to a project' ),
			'intro'          => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Every gift — large or small — goes to work in the villages: Bibles for new believers, freedom for kiln families, care for children and widows, and clean water and medical help.' ),
			'current_tab'    => krm_f( 'text', __( 'Current tab', 'kingdom-revival' ), 'Current Projects' ),
			'upcoming_tab'   => krm_f( 'text', __( 'Upcoming tab', 'kingdom-revival' ), 'Upcoming Projects' ),
			'current_tag'    => krm_f( 'text', __( 'Label on current project photos', 'kingdom-revival' ), 'CURRENT' ),
			'upcoming_tag'   => krm_f( 'text', __( 'Label on upcoming project photos', 'kingdom-revival' ), 'UPCOMING' ),
			'give_label'     => krm_f( 'text', __( 'Current: button text', 'kingdom-revival' ), 'Give to this project' ),
			'more_label'     => krm_f( 'text', __( 'Current: detail page button text', 'kingdom-revival' ), 'Learn more' ),
			'pledge_label'   => krm_f( 'text', __( 'Upcoming: button text', 'kingdom-revival' ), 'Pledge / Get notified' ),
			'target_label'   => krm_f( 'text', __( 'Upcoming: “Target” label', 'kingdom-revival' ), 'Target' ),
			'date_label'     => krm_f( 'text', __( 'Upcoming: “Planned” label', 'kingdom-revival' ), 'Planned' ),
			'empty_current'  => krm_f( 'text', __( 'Text when there are no current projects', 'kingdom-revival' ), 'There are no current projects at the moment.' ),
			'empty_upcoming' => krm_f( 'text', __( 'Text when there are no upcoming projects', 'kingdom-revival' ), 'There are no upcoming projects at the moment.' ),
		),
	),
	'impact'  => array(
		'label'  => __( 'Where your generosity goes', 'kingdom-revival' ),
		'fields' => array(
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Your gift at work' ),
			'heading' => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Where your generosity goes' ),
			'intro'   => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Gifts given “where it is needed most” are used across all our ministries.' ),
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
