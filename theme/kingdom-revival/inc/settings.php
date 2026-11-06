<?php
/**
 * Dashboard → Kingdom Revival → Settings. Contact details, social links and impact numbers are also editable in
 * Appearance → Customize (inc/customizer.php); both write to the same krm_settings option.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Settings screen layout: section => [ key => [ label, type, help ] ].
 */
function krm_settings_schema() {
	return array(
		__( 'Contact details', 'kingdom-revival' )    => array(
			'phone'     => array( __( 'Phone (as displayed)', 'kingdom-revival' ), 'text', '' ),
			'phone_tel' => array( __( 'Phone (for tap-to-call)', 'kingdom-revival' ), 'text', __( 'Digits with country code, e.g. +923020692993', 'kingdom-revival' ) ),
			'email'     => array( __( 'Email', 'kingdom-revival' ), 'email', '' ),
			'address'   => array( __( 'Address', 'kingdom-revival' ), 'textarea', '' ),
			'whatsapp'  => array( __( 'WhatsApp number', 'kingdom-revival' ), 'text', __( 'Digits with country code, no +, e.g. 923020692993', 'kingdom-revival' ) ),
		),
		__( 'Social media (top bar)', 'kingdom-revival' ) => array(
			'facebook'  => array( __( 'Facebook URL', 'kingdom-revival' ), 'url', __( 'Each network with a saved URL gets an icon in the top bar of every page, next to the Donate button. With no URL saved, no icon is shown.', 'kingdom-revival' ) ),
			'youtube'   => array( __( 'YouTube URL', 'kingdom-revival' ), 'url', '' ),
			'instagram' => array( __( 'Instagram URL', 'kingdom-revival' ), 'url', '' ),
		),
		__( 'Online giving', 'kingdom-revival' )      => array(
			'paypal_email'      => array( __( 'PayPal account email', 'kingdom-revival' ), 'email', __( 'Recommended. The email of the ministry’s PayPal Business account (a sandbox business account while testing). Enables PayPal’s secure checkout for one-time AND monthly (recurring) gifts with the amount and ministry filled in, saved Donations and thank-you receipts. Leave empty to keep using the PayPal.me link below (one-time only, no records or receipts).', 'kingdom-revival' ) ),
			'paypal_mode'       => array(
				__( 'PayPal mode', 'kingdom-revival' ),
				'select',
				__( 'Sandbox sends donors to PayPal’s test site (sandbox.paypal.com), where no real money moves; use it with a sandbox business email above to try the whole flow. Switch to Live before the site goes public.', 'kingdom-revival' ),
				array(
					'live'    => __( 'Live', 'kingdom-revival' ),
					'sandbox' => __( 'Sandbox (testing)', 'kingdom-revival' ),
				),
			),
			'paypal_me'         => array( __( 'PayPal.me link', 'kingdom-revival' ), 'url', '' ),
			'donation_receipts' => array(
				__( 'Thank-you receipts', 'kingdom-revival' ),
				'select',
				__( 'When PayPal confirms a gift, email the donor the receipt below (PayPal also sends its own payment receipt). The ministry is always notified at the form notification addresses.', 'kingdom-revival' ),
				array(
					'on'  => __( 'Email donors a thank-you receipt', 'kingdom-revival' ),
					'off' => __( 'Do not email donors', 'kingdom-revival' ),
				),
			),
			'receipt_subject'   => array( __( 'Receipt email subject', 'kingdom-revival' ), 'text', '' ),
			'receipt_message'   => array( __( 'Receipt email message', 'kingdom-revival' ), 'textarea', __( 'Plain text. Placeholders: {name} (first name), {full_name}, {amount}, {frequency} (one-time/monthly), {fund}, {date}, {transaction} (PayPal transaction ID), {email}, {phone}.', 'kingdom-revival' ) ),
		),
		__( 'Bank transfer, Western Union & MoneyGram', 'kingdom-revival' ) => array(
			'bank_account_name'   => array( __( 'Account name / receiver', 'kingdom-revival' ), 'text', '' ),
			'bank_cnic'           => array( __( 'CNIC number', 'kingdom-revival' ), 'text', '' ),
			'bank_name'           => array( __( 'Bank', 'kingdom-revival' ), 'text', '' ),
			'bank_iban'           => array( __( 'IBAN', 'kingdom-revival' ), 'text', '' ),
			'bank_swift'          => array( __( 'SWIFT code', 'kingdom-revival' ), 'text', '' ),
			'bank_branch_code'    => array( __( 'Branch code', 'kingdom-revival' ), 'text', '' ),
			'bank_branch_address' => array( __( 'Branch address', 'kingdom-revival' ), 'textarea', '' ),
			'bank_phone'          => array( __( 'Phone', 'kingdom-revival' ), 'text', '' ),
		),
		__( 'Forms & newsletter', 'kingdom-revival' ) => array(
			'form_recipient'      => array( __( 'Send form notifications to', 'kingdom-revival' ), 'text', __( 'One or more emails, comma separated.', 'kingdom-revival' ) ),
			'newsletter_endpoint' => array( __( 'Newsletter service form URL (optional)', 'kingdom-revival' ), 'url', __( 'Sign-ups are always saved under Submissions. If you add a Mailchimp/Brevo/Formspree form URL, each sign-up is also forwarded there.', 'kingdom-revival' ) ),
			'newsletter_delay'    => array( __( 'Newsletter popup delay (seconds)', 'kingdom-revival' ), 'number', '' ),
		),
		__( 'Structured data (search results)', 'kingdom-revival' ) => array(
			'schema_org_type'  => array(
				__( 'Organisation type', 'kingdom-revival' ),
				'select',
				__( 'How search engines are told what the ministry is. Choose NGO only if the ministry is registered as a non-governmental organisation.', 'kingdom-revival' ),
				array(
					'Organization' => __( 'Organization', 'kingdom-revival' ),
					'NGO'          => __( 'NGO (registered non-governmental organisation)', 'kingdom-revival' ),
				),
			),
			'address_locality' => array( __( 'City', 'kingdom-revival' ), 'text', __( 'The city of the Address above.', 'kingdom-revival' ) ),
			'address_region'   => array( __( 'Province / region', 'kingdom-revival' ), 'text', '' ),
			'address_country'  => array( __( 'Country code', 'kingdom-revival' ), 'text', __( 'Two letters, e.g. PK.', 'kingdom-revival' ) ),
		),
		__( 'Images', 'kingdom-revival' )             => array(
			'image_format' => array(
				__( 'Image format', 'kingdom-revival' ),
				'select',
				__( 'Format of the resized copies WordPress makes of uploaded JPEG and PNG images, which the pages show. WebP files are much smaller and load faster at the same visible quality; the uploaded original is always kept. Applies to images uploaded or imported after the change (a plugin such as Regenerate Thumbnails updates existing ones).', 'kingdom-revival' ),
				array(
					'webp'     => __( 'WebP (recommended)', 'kingdom-revival' ),
					'original' => __( 'Same as the upload (JPEG or PNG)', 'kingdom-revival' ),
				),
			),
			'trim_assets'  => array(
				__( 'Unused plugin styles', 'kingdom-revival' ),
				'select',
				__( 'Some plugins load WordPress’s editor styles and the Dashicons icon font on every public page, although the theme’s pages never use them. “Remove” leaves them out for visitors who are not logged in (logged-in users, the admin bar and the dashboard are not affected). A style a plugin really needs is still loaded, because WordPress adds it back as that plugin’s dependency.', 'kingdom-revival' ),
				array(
					'on'  => __( 'Remove for visitors (recommended)', 'kingdom-revival' ),
					'off' => __( 'Keep', 'kingdom-revival' ),
				),
			),
		),
		__( 'Redirects', 'kingdom-revival' )          => array(
			'redirects' => array( __( 'Old address → new address', 'kingdom-revival' ), 'textarea', __( 'One per line: the old address, a space, then where it should go (a /path, a full URL or {donate}, {blog}, {about}…). These are permanent (301) redirects from the previous site’s pages, so search engines and old links reach the new pages. Lines starting with # are ignored.', 'kingdom-revival' ) ),
		),
		__( 'Impact numbers (home & about pages)', 'kingdom-revival' ) => array(
			'stat_1_value'      => array( __( 'Stat 1 number', 'kingdom-revival' ), 'text', __( 'Number only; “+” is added automatically.', 'kingdom-revival' ) ),
			'stat_1_label'      => array( __( 'Stat 1 label', 'kingdom-revival' ), 'text', '' ),
			'stat_2_value'      => array( __( 'Stat 2 number', 'kingdom-revival' ), 'text', '' ),
			'stat_2_label'      => array( __( 'Stat 2 label', 'kingdom-revival' ), 'text', '' ),
			'stat_3_value'      => array( __( 'Stat 3 number', 'kingdom-revival' ), 'text', '' ),
			'stat_3_label'      => array( __( 'Stat 3 label', 'kingdom-revival' ), 'text', '' ),
			'stat_4_value'      => array( __( 'Stat 4 number', 'kingdom-revival' ), 'text', '' ),
			'stat_4_label'      => array( __( 'Stat 4 label', 'kingdom-revival' ), 'text', '' ),
			'stat_5_value'      => array( __( 'Stat 5 number', 'kingdom-revival' ), 'text', '' ),
			'stat_5_label'      => array( __( 'Stat 5 label', 'kingdom-revival' ), 'text', '' ),
			'home_stat_1_label' => array( __( 'Stat 1 label (home page)', 'kingdom-revival' ), 'text', __( 'The home page uses its own wording of each label.', 'kingdom-revival' ) ),
			'home_stat_2_label' => array( __( 'Stat 2 label (home page)', 'kingdom-revival' ), 'text', '' ),
			'home_stat_3_label' => array( __( 'Stat 3 label (home page)', 'kingdom-revival' ), 'text', '' ),
			'home_stat_4_label' => array( __( 'Stat 4 label (home page)', 'kingdom-revival' ), 'text', '' ),
			'home_stat_5_label' => array( __( 'Stat 5 label (home page)', 'kingdom-revival' ), 'text', '' ),
		),
	);
}

add_action(
	'admin_menu',
	function () {
		add_menu_page(
			__( 'Kingdom Revival', 'kingdom-revival' ),
			__( 'Kingdom Revival', 'kingdom-revival' ),
			'manage_options',
			'krm-settings',
			'krm_settings_page',
			'dashicons-heart',
			59
		);
		add_submenu_page( 'krm-settings', __( 'Settings', 'kingdom-revival' ), __( 'Settings', 'kingdom-revival' ), 'manage_options', 'krm-settings', 'krm_settings_page' );
	}
);

/* Registered on init (not admin_init) so every save path, including the Customizer, runs the sanitizer. */
add_action(
	'init',
	function () {
		register_setting(
			'krm_settings',
			'krm_settings',
			array(
				'type'              => 'array',
				'sanitize_callback' => 'krm_sanitize_settings',
				'default'           => array(),
			)
		);
	}
);

/**
 * Sanitize one setting value by its field type (shared by the settings screen and the Customizer).
 *
 * @param mixed  $value Submitted value.
 * @param string $type  text|textarea|email|url|number.
 * @return string|int
 */
function krm_sanitize_setting_value( $value, $type ) {
	$value = is_scalar( $value ) ? (string) $value : '';
	switch ( $type ) {
		case 'url':
			return esc_url_raw( trim( $value ), array( 'http', 'https' ) );
		case 'email':
			return sanitize_email( $value );
		case 'number':
			return absint( $value );
		case 'textarea':
			return sanitize_textarea_field( $value );
		default:
			return sanitize_text_field( $value );
	}
}

/**
 * Every setting field keyed by setting key: key => [ label, type, help ].
 */
function krm_settings_fields() {
	$fields = array();
	foreach ( krm_settings_schema() as $section ) {
		$fields += $section;
	}
	return $fields;
}

/**
 * Sanitize the settings array against the schema. options.php and the Customizer pass unslashed values.
 *
 * @param mixed $input Submitted values.
 */
function krm_sanitize_settings( $input ) {
	$input    = is_array( $input ) ? $input : array();
	$defaults = krm_defaults();
	$clean    = array();
	foreach ( krm_settings_fields() as $key => $field ) {
		// A key that was not submitted (e.g. a Customizer save on a fresh site) keeps its default, not an empty value.
		if ( array_key_exists( $key, $input ) ) {
			$value = $input[ $key ];
		} else {
			$value = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
		}
		if ( 'select' === $field[1] ) {
			$clean[ $key ] = is_scalar( $value ) && isset( $field[3][ (string) $value ] ) ? (string) $value : $defaults[ $key ];
			continue;
		}
		$clean[ $key ] = krm_sanitize_setting_value( $value, $field[1] );
	}
	if ( isset( $clean['form_recipient'] ) ) {
		$emails                  = array_filter( array_map( 'sanitize_email', explode( ',', $clean['form_recipient'] ) ) );
		$clean['form_recipient'] = implode( ', ', $emails );
	}
	return $clean;
}

/**
 * Render Kingdom Revival → Settings.
 */
function krm_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Kingdom Revival Ministries — Settings', 'kingdom-revival' ); ?></h1>
		<p><?php esc_html_e( 'These details appear across the site (top bar, footer, contact and donate pages). SEO titles and descriptions are managed in Rank Math or Yoast SEO when one of them is active.', 'kingdom-revival' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'krm_settings' ); ?>
			<?php foreach ( krm_settings_schema() as $section => $fields ) : ?>
				<h2 class="title"><?php echo esc_html( $section ); ?></h2>
				<table class="form-table" role="presentation">
					<?php
					foreach ( $fields as $key => $field ) :
						$id    = 'krm-' . $key;
						$name  = 'krm_settings[' . $key . ']';
						$value = krm_opt( $key );
						?>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field[0] ); ?></label></th>
							<td>
								<?php if ( 'textarea' === $field[1] ) : ?>
									<textarea class="large-text" rows="<?php echo 'redirects' === $key || 'receipt_message' === $key ? 14 : 2; ?>" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
								<?php elseif ( 'select' === $field[1] ) : ?>
									<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
										<?php foreach ( $field[3] as $option => $label ) : ?>
										<option value="<?php echo esc_attr( $option ); ?>"<?php selected( $value, $option ); ?>><?php echo esc_html( $label ); ?></option>
										<?php endforeach; ?>
									</select>
								<?php else : ?>
									<input class="<?php echo 'number' === $field[1] ? 'small-text' : 'regular-text'; ?>" type="<?php echo esc_attr( 'email' === $field[1] ? 'email' : ( 'number' === $field[1] ? 'number' : ( 'url' === $field[1] ? 'url' : 'text' ) ) ); ?>" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>">
								<?php endif; ?>
								<?php if ( $field[2] ) : ?>
									<p class="description"><?php echo esc_html( $field[2] ); ?></p>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endforeach; ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
