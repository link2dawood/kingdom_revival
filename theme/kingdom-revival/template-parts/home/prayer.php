<?php
/**
 * Prayer request form (home page, and the Prayer Request page with screen => 'home'). Phase 24: form_type
 * "prayer_request", sent by assets/js/forms.js to krm_form_submit() (inc/form-handler.php); without JavaScript it posts
 * to admin-post.php and the page shows the outcome.
 *
 * @param array $args { screen: content screen to read; id: section id (default "prayer"). }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_screen = isset( $args['screen'] ) ? (string) $args['screen'] : '';
$krm_id     = isset( $args['id'] ) ? (string) $args['id'] : 'prayer';
$krm_full   = 'request' === $krm_id;
$krm_form   = $krm_id . '-form';
$krm_state  = krm_form_state( $krm_form );
$krm_done   = $krm_state && $krm_state['ok'];
$krm_hide   = $krm_done ? ' hidden' : '';
/* Error attributes + note after a failed post without JavaScript. */
$krm_err = function ( $key ) use ( $krm_form ) {
	return krm_field_error( $krm_form, $key );
};

$krm_input = "font: 400 16px var(--font-body); padding: 12px 14px; border: 1px solid var(--color-input-border); border-radius: 8px; background: var(--color-surface); color: var(--color-text); min-height: 46px; box-sizing: border-box";
?>
<!-- Prayer request -->
<section id="<?php echo esc_attr( $krm_id ); ?>" style="max-width: 1240px; margin: 0 auto; padding: 0 clamp(16px, 3vw, 32px) clamp(72px, 8vw, 110px)">
<div style="border-radius: 16px; background: var(--color-bg-soft); padding: clamp(28px, 4vw, 52px); display: flex; flex-wrap: wrap; gap: 32px 56px">
<div style="flex: 1 1 320px; min-width: 0">
<p style="margin: 0 0 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--color-secondary-text)"><?php krm_t( 'prayer.eyebrow', $krm_screen ); ?></p>
<h2 style="margin: 0; font-size: clamp(30px, 3.4vw, 40px); font-weight: 800; line-height: 1.15; color: var(--color-heading)"><?php krm_h( 'prayer.heading', $krm_screen ); ?></h2>
<p style="margin: 14px 0 0; color: var(--color-text-muted)"><?php krm_t( 'prayer.text', $krm_screen ); ?></p>
<?php if ( '' !== trim( (string) krm_c( 'prayer.whatsapp_label', $krm_screen ) ) ) : ?>
<a href="<?php echo esc_url( krm_whatsapp_url() ); ?>" target="_blank" rel="noopener" style="margin-top: 16px; display: inline-flex; gap: 8px; align-items: center; font-weight: 700; min-height: 44px"><?php krm_t( 'prayer.whatsapp_label', $krm_screen ); ?></a>
<?php endif; ?>
</div>
<form data-krm-prayer <?php krm_form_attrs( 'prayer_request', $krm_form ); ?> style="flex: 1.3 1 380px; min-width: 0; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px">
<?php krm_form_hidden( 'prayer_request', $krm_form ); ?>
<?php list( $krm_ea, $krm_en ) = $krm_err( 'name' ); ?>
<label class="krm-prayer__half" style="display: flex; flex-direction: column; gap: 6px; font-size: 14px; font-weight: 600"<?php echo $krm_hide; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>Your name<input type="text" name="name" autocomplete="name" required maxlength="100" value="<?php echo esc_attr( krm_fv( $krm_form, 'name' ) ); ?>" style="<?php echo esc_attr( $krm_input ); ?>"<?php echo $krm_ea; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo $krm_en; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
<?php list( $krm_ea, $krm_en ) = $krm_err( 'contact' ); ?>
<label class="krm-prayer__half" style="display: flex; flex-direction: column; gap: 6px; font-size: 14px; font-weight: 600"<?php echo $krm_hide; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>Email or WhatsApp<input type="text" name="contact" autocomplete="email" required maxlength="200" data-validate="email-or-phone" value="<?php echo esc_attr( krm_fv( $krm_form, 'contact' ) ); ?>" style="<?php echo esc_attr( $krm_input ); ?>"<?php echo $krm_ea; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo $krm_en; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
<?php if ( $krm_full ) : ?>
<label class="krm-prayer__half" style="display:flex;flex-direction:column;gap:6px;font-size:14px;font-weight:600"<?php echo $krm_hide; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>Country<input type="text" name="country" autocomplete="country-name" maxlength="200" value="<?php echo esc_attr( krm_fv( $krm_form, 'country' ) ); ?>" style="<?php echo esc_attr( $krm_input ); ?>"></label>
<label class="krm-prayer__half" style="display:flex;flex-direction:column;gap:6px;font-size:14px;font-weight:600"<?php echo $krm_hide; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>Request type<select name="type" style="<?php echo esc_attr( $krm_input ); ?>"><?php foreach ( krm_prayer_types() as $krm_pt ) : ?><option<?php selected( krm_fv( $krm_form, 'type' ), $krm_pt ); ?>><?php echo esc_html( $krm_pt ); ?></option><?php endforeach; ?></select></label>
<?php endif; ?>
<?php list( $krm_ea, $krm_en ) = $krm_err( 'request' ); ?>
<label style="grid-column: span 2; display: flex; flex-direction: column; gap: 6px; font-size: 14px; font-weight: 600"<?php echo $krm_hide; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>Message<textarea name="request" rows="<?php echo $krm_full ? '7' : '4'; ?>" required maxlength="5000" style="font: 400 16px var(--font-body); padding: 12px 14px; border: 1px solid var(--color-input-border); border-radius: 8px; background: var(--color-surface); resize: vertical"<?php echo $krm_ea; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_textarea( krm_fv( $krm_form, 'request' ) ); ?></textarea><?php echo $krm_en; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
<?php if ( $krm_full ) : ?><label class="krm-prayer-private" style="grid-column:span 2;display:flex;gap:10px;align-items:flex-start;font-size:14px"<?php echo $krm_hide; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><input type="checkbox" name="private" value="Yes"<?php checked( krm_fv( $krm_form, 'private' ), 'Yes' ); ?> style="width:20px;height:20px;accent-color:var(--color-heading)"><span><strong>Keep my request private</strong><br><small style="color:var(--color-text-muted)">Only the ministry's designated prayer team will see it.</small></span></label><?php endif; ?>
<button type="submit" class="k-g" style="grid-column: span 2; justify-self: start; font: 700 15px var(--font-body); background: var(--color-button-navy); color: var(--color-white); border: 0; border-radius: 8px; padding: 0 26px; min-height: 48px; cursor: pointer"<?php echo $krm_hide; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php krm_t( 'prayer.button', $krm_screen ); ?></button>
<?php krm_form_messages( $krm_form, krm_fill( krm_c( 'prayer.success', $krm_screen ) ), krm_fill( krm_c( 'prayer.error', $krm_screen ) ), 'grid-column: 1 / -1' ); ?>
</form>
</div>
</section>
