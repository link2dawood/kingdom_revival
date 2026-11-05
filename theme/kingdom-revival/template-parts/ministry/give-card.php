<?php
/**
 * Ministry page 7: the home hero's donation card, giving to this ministry — badge, heading and text from Site text →
 * Ministry pages: side donation card, the home card's amounts, this ministry's raised / goal bar, and a Donate button
 * that opens Donate with the ministry and amount chosen (components.js updates it when an amount is picked).
 * It sits beside sections 2–6 and stays in view while they scroll (CSS position: sticky).
 *
 * @param array $args { ministry }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m       = $args['ministry'];
$krm_band    = krm_site_give_band_args( 'ministry_give', $krm_m['title'] );
$krm_amounts = krm_home_amounts();
$krm_sel     = krm_home_selected_amount();
?>
<div class="give-card" id="support" data-krm-give-card data-for="<?php echo esc_attr( $krm_m['title'] ); ?>" data-ministry="<?php echo esc_attr( $krm_m['slug'] ); ?>" data-donate="<?php echo esc_url( krm_donate_url() ); ?>">
	<?php if ( '' !== $krm_band['eyebrow'] ) : ?>
	<span class="give-card-badge"><?php echo esc_html( $krm_band['eyebrow'] ); ?></span>
	<?php endif; ?>
	<h2 id="support-heading"><?php echo esc_html( $krm_band['heading'] ); ?></h2>
	<?php if ( '' !== $krm_band['text'] ) : ?>
	<p class="give-card-text"><?php echo esc_html( $krm_band['text'] ); ?></p>
	<?php endif; ?>
	<div class="give-card-amounts" role="group" aria-label="<?php esc_attr_e( 'Donation amount', 'kingdom-revival' ); ?>" style="grid-template-columns: repeat(<?php echo (int) count( $krm_amounts ); ?>, minmax(0, 1fr))">
		<?php foreach ( $krm_amounts as $krm_a => $krm_on ) : ?>
		<button type="button" data-give-amount="<?php echo esc_attr( $krm_a ); ?>" aria-pressed="<?php echo $krm_on ? 'true' : 'false'; ?>"><?php echo esc_html( $krm_a ); ?></button>
		<?php endforeach; ?>
	</div>
	<a class="give-card-button k-y" data-give-link href="<?php echo esc_url( krm_home_donate_href( $krm_sel, $krm_m['title'], $krm_m['slug'] ) ); ?>"><?php krm_t( 'ministry.card_button', 'site' ); ?> <span><?php echo esc_html( 'Other' === $krm_sel ? 'Now' : $krm_sel ); ?></span></a>
	<?php if ( $krm_band['points'] ) : ?>
	<p class="give-card-note"><?php echo esc_html( implode( ' · ', $krm_band['points'] ) ); ?></p>
	<?php endif; ?>
</div>
