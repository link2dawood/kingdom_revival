<?php
/**
 * Partner With Us sign-up form (Phase 24: sent by assets/js/forms.js to krm_form_submit() in inc/form-handler.php;
 * without JavaScript it posts to admin-post.php and the page shows the outcome).
 *
 * @param array $args {
 *     id:        unique id prefix.
 *     form_type: prayer_partner | financial_partner | church_partner | volunteer.
 *     heading:   form heading.
 *     button:    button text.
 *     church:    ask for the church / organisation (church partners).
 *     project:   add the "Project" field, filled from ?project= (pledges from Donate → Upcoming Projects).
 * }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_id   = sanitize_html_class( $args['id'] );
$krm_done = krm_form_state( $krm_id ) && krm_form_state( $krm_id )['ok'];
$krm_in   = function ( $key, $label, $attrs, $wide = false ) use ( $krm_id ) {
	list( $err_attrs, $err_note ) = krm_field_error( $krm_id, $key );
	printf(
		'<label class="field%1$s"><span>%2$s</span>%3$s%4$s</label>',
		$wide ? ' is-wide' : '',
		esc_html( $label ),
		'textarea' === $attrs
			? '<textarea name="' . esc_attr( $key ) . '" rows="4" maxlength="5000"' . $err_attrs . '>' . esc_textarea( krm_fv( $krm_id, $key ) ) . '</textarea>'
			: '<input ' . $attrs . ' name="' . esc_attr( $key ) . '" value="' . esc_attr( krm_fv( $krm_id, $key ) ) . '"' . $err_attrs . '>',
		$err_note // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in krm_field_error().
	);
};
?>
<form class="signup-card" <?php krm_form_attrs( $args['form_type'], $krm_id ); ?> aria-labelledby="<?php echo esc_attr( $krm_id ); ?>-heading">
	<h3 id="<?php echo esc_attr( $krm_id ); ?>-heading" style="margin: 0 0 18px; font-size: 21px"<?php echo $krm_done ? ' hidden' : ''; ?>><?php echo esc_html( $args['heading'] ); ?></h3>
	<?php krm_form_hidden( $args['form_type'], $krm_id ); ?>
	<div class="form-grid"<?php echo $krm_done ? ' hidden' : ''; ?>>
		<?php
		$krm_in( 'name', 'Your name', 'type="text" autocomplete="name" required maxlength="100"' );
		$krm_in( 'contact', 'Email or WhatsApp', 'type="text" autocomplete="email" required maxlength="200" data-validate="email-or-phone"' );
		$krm_in( 'country', 'Country', 'type="text" autocomplete="country-name" required maxlength="200"' );
		if ( ! empty( $args['church'] ) ) {
			$krm_in( 'church', 'Church / organisation', 'type="text" autocomplete="organization" maxlength="200"' );
		}
		if ( ! empty( $args['project'] ) ) {
			$krm_in( 'project', 'Project (optional)', 'type="text" data-prefill="project" maxlength="200"' );
		}
		$krm_in( 'message', 'Message (optional)', 'textarea', true );
		?>
	</div>
	<button type="submit" class="button button-primary"<?php echo $krm_done ? ' hidden' : ''; ?>><?php echo esc_html( $args['button'] ); ?></button>
	<?php krm_form_messages( $krm_id, krm_fill( krm_c( 'form.success', 'partner' ) ), krm_fill( krm_c( 'form.error', 'partner' ) ) ); ?>
</form>
