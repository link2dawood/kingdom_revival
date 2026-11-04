<?php
/**
 * Prayer Request page content (page-templates/prayer.php, /partner-with-us/prayer-request/). The form and the text
 * beside it are the home page's "Prayer request" section (Pages → Home → Page content), edited in one place.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	'hero' => array(
		'label'  => __( 'Hero', 'kingdom-revival' ),
		'help'   => __( 'The form and its text are the home page’s “Prayer request” section: edit them in Pages → Home → Page content.', 'kingdom-revival' ),
		'fields' => array(
			'image'   => krm_f( 'image', __( 'Background image', 'kingdom-revival' ), 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-10.jpg' ),
			'eyebrow' => krm_f( 'text', __( 'Small heading', 'kingdom-revival' ), 'Partner With Us' ),
			'title'   => krm_f( 'html', __( 'Heading', 'kingdom-revival' ), 'Prayer Request' ),
			'lead'    => krm_f( 'textarea', __( 'Introduction', 'kingdom-revival' ), 'Our pastors and prayer team pray over every request we receive. You can also message us on WhatsApp.' ),
		),
	),
);
