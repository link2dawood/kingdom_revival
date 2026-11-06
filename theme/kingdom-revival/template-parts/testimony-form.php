<?php
/**
 * "Share your testimony" form (Testimonies page, Phase 21): name, village / city, ministry, story, an optional photo
 * and the publishing consent. Posted by assets/js/components.js like the other forms (form "testimony" in
 * inc/forms.php), which saves it as a pending Testimonial — never published automatically.
 *
 * Text: Pages → Testimonies → Page content → Share your testimony form.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section testimony-share" id="share" aria-labelledby="share-heading">
	<div class="wrap testimony-share__wrap">
		<div class="testimony-share__intro">
			<h2 id="share-heading"><?php krm_t( 'share.heading' ); ?></h2>
			<p><?php krm_t( 'share.text' ); ?></p>
		</div>
		<form class="signup-card" data-krm-form="testimony" novalidate aria-labelledby="share-heading">
			<p class="krm-hp" aria-hidden="true" style="position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden"><label>Leave this field empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
			<div class="form-grid">
				<label class="field"><span><?php esc_html_e( 'Your name', 'kingdom-revival' ); ?></span><input type="text" name="name" autocomplete="name" required maxlength="200"></label>
				<label class="field"><span><?php esc_html_e( 'Village / city', 'kingdom-revival' ); ?></span><input type="text" name="place" autocomplete="address-level2" maxlength="200"></label>
				<label class="field is-wide"><span><?php esc_html_e( 'Ministry', 'kingdom-revival' ); ?></span>
					<select name="ministry">
						<option value=""><?php esc_html_e( 'Not sure / several', 'kingdom-revival' ); ?></option>
						<?php foreach ( krm_ministries() as $krm_m ) : ?>
						<option value="<?php echo esc_attr( $krm_m['slug'] ); ?>"><?php echo esc_html( $krm_m['title'] ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label class="field is-wide"><span><?php esc_html_e( 'Your story', 'kingdom-revival' ); ?></span><textarea name="story" rows="7" required maxlength="5000"></textarea></label>
				<label class="field is-wide"><span><?php esc_html_e( 'Photo (optional)', 'kingdom-revival' ); ?></span><input type="file" name="photo" accept="image/jpeg,image/png,image/webp" data-max-size="5242880" aria-describedby="share-photo-help"><small id="share-photo-help" class="field-help"><?php esc_html_e( 'JPG, PNG or WebP, up to 5 MB.', 'kingdom-revival' ); ?></small></label>
				<label class="field is-wide testimony-share__consent"><input type="checkbox" name="consent" value="Yes" required data-error-required="<?php esc_attr_e( 'Please tick this box so we may publish your testimony.', 'kingdom-revival' ); ?>"><span><?php krm_t( 'share.consent' ); ?></span></label>
			</div>
			<button type="submit" class="button button-primary"><?php krm_t( 'share.button' ); ?></button>
			<p class="form-status" data-krm-form-status role="status" hidden><?php krm_t( 'share.success' ); ?></p>
			<p class="form-error" data-krm-form-error role="alert" hidden><?php krm_t( 'share.error' ); ?></p>
		</form>
	</div>
</section>
