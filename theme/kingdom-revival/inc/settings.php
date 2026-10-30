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
			'phone_tel' => array( __( 'Phone (for tap-to-call)', 'kingdom-revival' ), 'text', __( 'Digits with country code, e.g. +923415418733', 'kingdom-revival' ) ),
			'email'     => array( __( 'Email', 'kingdom-revival' ), 'email', '' ),
			'address'   => array( __( 'Address', 'kingdom-revival' ), 'textarea', '' ),
			'whatsapp'  => array( __( 'WhatsApp number', 'kingdom-revival' ), 'text', __( 'Digits with country code, no +, e.g. 923415418733', 'kingdom-revival' ) ),
		),
		__( 'Social media (home page top bar)', 'kingdom-revival' ) => array(
			'facebook'  => array( __( 'Facebook URL', 'kingdom-revival' ), 'url', __( 'Leave empty until the page exists: the icon stays visible but is not a link.', 'kingdom-revival' ) ),
			'youtube'   => array( __( 'YouTube URL', 'kingdom-revival' ), 'url', '' ),
			'instagram' => array( __( 'Instagram URL', 'kingdom-revival' ), 'url', '' ),
		),
		__( 'Online giving', 'kingdom-revival' )      => array(
			'paypal_email' => array( __( 'PayPal account email', 'kingdom-revival' ), 'email', __( 'Recommended. Enables PayPal’s secure checkout for one-time AND monthly (recurring) gifts with the amount and ministry filled in. Leave empty to keep using the PayPal.me link below (one-time only).', 'kingdom-revival' ) ),
			'paypal_me'    => array( __( 'PayPal.me link', 'kingdom-revival' ), 'url', '' ),
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
		__( 'Impact numbers (home & about pages)', 'kingdom-revival' ) => array(
			'stat_1_value' => array( __( 'Stat 1 number', 'kingdom-revival' ), 'text', __( 'Number only; “+” is added automatically.', 'kingdom-revival' ) ),
			'stat_1_label' => array( __( 'Stat 1 label', 'kingdom-revival' ), 'text', '' ),
			'stat_2_value' => array( __( 'Stat 2 number', 'kingdom-revival' ), 'text', '' ),
			'stat_2_label' => array( __( 'Stat 2 label', 'kingdom-revival' ), 'text', '' ),
			'stat_3_value' => array( __( 'Stat 3 number', 'kingdom-revival' ), 'text', '' ),
			'stat_3_label' => array( __( 'Stat 3 label', 'kingdom-revival' ), 'text', '' ),
			'stat_4_value' => array( __( 'Stat 4 number', 'kingdom-revival' ), 'text', '' ),
			'stat_4_label' => array( __( 'Stat 4 label', 'kingdom-revival' ), 'text', '' ),
			'stat_5_value' => array( __( 'Stat 5 number', 'kingdom-revival' ), 'text', '' ),
			'stat_5_label' => array( __( 'Stat 5 label', 'kingdom-revival' ), 'text', '' ),
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
									<textarea class="large-text" rows="2" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
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
