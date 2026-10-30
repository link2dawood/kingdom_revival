<?php
/**
 * Appearance → Customize: contact details, social links and impact numbers, with live preview.
 *
 * The controls edit the same krm_settings option as Kingdom Revival → Settings, so both screens always agree.
 * The logo is set under Site Identity (custom-logo theme support).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Customizer sections and the setting keys each one shows.
 */
function krm_customizer_sections() {
	return array(
		'krm_contact' => array(
			'title' => __( 'Contact details', 'kingdom-revival' ),
			'keys'  => array( 'phone', 'phone_tel', 'email', 'address', 'whatsapp' ),
		),
		'krm_social'  => array(
			'title' => __( 'Social media', 'kingdom-revival' ),
			'keys'  => array( 'facebook', 'youtube', 'instagram' ),
		),
		'krm_stats'   => array(
			'title' => __( 'Impact numbers', 'kingdom-revival' ),
			'keys'  => array( 'stat_1_value', 'stat_1_label', 'stat_2_value', 'stat_2_label', 'stat_3_value', 'stat_3_label', 'stat_4_value', 'stat_4_label', 'stat_5_value', 'stat_5_label', 'home_stat_1_label', 'home_stat_2_label', 'home_stat_3_label', 'home_stat_4_label', 'home_stat_5_label' ),
		),
	);
}

add_action(
	'customize_register',
	function ( WP_Customize_Manager $wp_customize ) {
		$fields   = krm_settings_fields();
		$defaults = krm_defaults();

		$wp_customize->add_panel(
			'krm_panel',
			array(
				'title'       => __( 'Kingdom Revival', 'kingdom-revival' ),
				'description' => __( 'Bank, PayPal and form settings are under Kingdom Revival → Settings in the dashboard.', 'kingdom-revival' ),
				'priority'    => 30,
			)
		);

		foreach ( krm_customizer_sections() as $section_id => $section ) {
			$wp_customize->add_section(
				$section_id,
				array(
					'title' => $section['title'],
					'panel' => 'krm_panel',
				)
			);

			foreach ( $section['keys'] as $key ) {
				if ( ! isset( $fields[ $key ] ) ) {
					continue;
				}
				list( $label, $type, $help ) = $fields[ $key ];
				$setting_id                  = 'krm_settings[' . $key . ']';

				$wp_customize->add_setting(
					$setting_id,
					array(
						'type'              => 'option',
						'capability'        => 'manage_options',
						'default'           => isset( $defaults[ $key ] ) ? $defaults[ $key ] : '',
						'sanitize_callback' => function ( $value ) use ( $type ) {
							return krm_sanitize_setting_value( $value, $type );
						},
					)
				);
				$wp_customize->add_control(
					$setting_id,
					array(
						'label'       => $label,
						'description' => $help,
						'section'     => $section_id,
						'type'        => $type,
					)
				);
			}
		}
	}
);
