<?php
/**
 * Partner With Us sign-up form (form "signup" in inc/forms.php, sent by assets/js/components.js).
 *
 * @param array $args {
 *     id:      unique id prefix.
 *     type:    partner type saved with the sign-up.
 *     heading: form heading.
 *     button:  button text.
 *     church:  ask for the church / organisation (church partners).
 *     project: add the "Project" field, filled from ?project= (pledges from Donate → Upcoming Projects).
 * }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_id = sanitize_html_class( $args['id'] );
?>
<form class="signup-card" data-krm-form="signup" novalidate aria-labelledby="<?php echo esc_attr( $krm_id ); ?>-heading">
	<h3 id="<?php echo esc_attr( $krm_id ); ?>-heading" style="margin: 0 0 18px; font-size: 21px"><?php echo esc_html( $args['heading'] ); ?></h3>
	<p class="krm-hp" aria-hidden="true" style="position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden"><label>Leave this field empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
	<input type="hidden" name="type" value="<?php echo esc_attr( $args['type'] ); ?>">
	<div class="form-grid">
		<label class="field"><span>Your name</span><input type="text" name="name" autocomplete="name" required maxlength="200"></label>
		<label class="field"><span>Email or WhatsApp</span><input type="text" name="contact" autocomplete="email" required maxlength="200"></label>
		<label class="field"><span>Country</span><input type="text" name="country" autocomplete="country-name" required maxlength="200"></label>
		<?php if ( ! empty( $args['church'] ) ) : ?>
		<label class="field"><span>Church / organisation</span><input type="text" name="church" autocomplete="organization" maxlength="200"></label>
		<?php endif; ?>
		<?php if ( ! empty( $args['project'] ) ) : ?>
		<label class="field"><span>Project (optional)</span><input type="text" name="project" data-prefill="project" maxlength="200"></label>
		<?php endif; ?>
		<label class="field is-wide"><span>Message (optional)</span><textarea name="message" rows="4" maxlength="5000"></textarea></label>
	</div>
	<button type="submit" class="button button-primary"><?php echo esc_html( $args['button'] ); ?></button>
	<p class="form-status" data-krm-form-status role="status" hidden><?php krm_t( 'form.success', 'partner' ); ?></p>
	<p class="form-error" data-krm-form-error role="alert" hidden><?php krm_t( 'form.error', 'partner' ); ?></p>
</form>
