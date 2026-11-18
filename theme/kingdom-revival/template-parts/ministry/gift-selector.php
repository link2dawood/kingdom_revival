<?php
/**
 * Ministry page: "How Your Gift Helps" interactive gift selector.
 *
 * Shows a one-time/monthly toggle, three amount buttons ($25, $50, $100), a single card with
 * the selected amount's description, and a Donate Now button that pre-fills the donate page.
 *
 * Since 1.27.0: moved into the sidebar (ministry-aside) for all ministry pages, with sticky positioning
 * and card styling matching the old give-card.
 *
 * @param array $args { ministry }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m    = $args['ministry'];
$krm_data = krm_ministry_gift_help_data();

// Skip if this ministry has no gift help data — will show nothing in the sidebar.
if ( ! isset( $krm_data[ $krm_m['slug'] ] ) ) {
	return;
}

$krm_gifts = $krm_data[ $krm_m['slug'] ];
$krm_amounts = array( 25, 50, 100 );
$krm_default = 50;
?>
<div class="gift-selector gift-selector--sidebar" aria-labelledby="gift-help-heading" data-ministry="<?php echo esc_attr( $krm_m['slug'] ); ?>">
	<h2 id="gift-help-heading"><?php esc_html_e( 'How Your Gift Helps', 'kingdom-revival' ); ?></h2>

	<div class="gift-selector__controls">
		<fieldset class="gift-selector__frequency" role="radiogroup" aria-label="<?php esc_attr_e( 'Donation frequency', 'kingdom-revival' ); ?>">
			<label class="gift-selector__freq-btn">
				<input type="radio" name="gift-frequency" value="one-time" checked>
				<span><?php esc_html_e( 'One-time', 'kingdom-revival' ); ?></span>
			</label>
			<label class="gift-selector__freq-btn">
				<input type="radio" name="gift-frequency" value="monthly">
				<span><?php esc_html_e( 'Monthly', 'kingdom-revival' ); ?></span>
			</label>
		</fieldset>

		<fieldset class="gift-selector__amounts" role="radiogroup" aria-label="<?php esc_attr_e( 'Gift amount', 'kingdom-revival' ); ?>">
			<?php foreach ( $krm_amounts as $krm_amt ) : ?>
			<label class="gift-selector__amount-btn">
				<input type="radio" name="gift-amount" value="<?php echo esc_attr( $krm_amt ); ?>"<?php checked( $krm_amt, $krm_default ); ?>>
				<span>$<?php echo esc_html( $krm_amt ); ?></span>
			</label>
			<?php endforeach; ?>
		</fieldset>
	</div>

	<div class="gift-selector__card">
		<div class="gift-selector__card-amount">
			<span class="gift-selector__card-value">$<?php echo esc_html( $krm_default ); ?></span>
			<span class="gift-selector__card-suffix"></span>
		</div>
		<p class="gift-selector__card-desc"><?php echo esc_html( $krm_gifts[ $krm_default ] ); ?></p>
		<?php foreach ( $krm_amounts as $krm_amt ) : ?>
		<p class="gift-selector__card-desc-hidden" data-amount="<?php echo esc_attr( $krm_amt ); ?>" hidden><?php echo esc_html( $krm_gifts[ $krm_amt ] ); ?></p>
		<?php endforeach; ?>
	</div>

	<a href="<?php echo esc_url( krm_ministry_donate_url( $krm_m['slug'], array( 'amount' => $krm_default ) ) ); ?>" class="btn btn-primary gift-selector__donate" data-base-url="<?php echo esc_url( krm_ministry_donate_url( $krm_m['slug'] ) ); ?>">
		<?php esc_html_e( 'Donate Now', 'kingdom-revival' ); ?>
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
	</a>
</div>
