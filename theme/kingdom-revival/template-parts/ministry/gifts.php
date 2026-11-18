<?php
/**
 * Ministry page 4: "How your gift helps" — the home "Your gift at work" cards for this ministry (its "How your gift
 * helps" field). Each card opens Donate with the ministry (and amount) chosen. Visitors see only cards with a real
 * amount, and no section when there are none.
 *
 * NOTE: If the ministry has data in the new interactive gift-selector (inc/data/ministry-gift-help.php), that section
 * is shown instead (template-parts/ministry/gift-selector.php), and this one is skipped to avoid duplicate headings.
 *
 * @param array $args { ministry }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m = $args['ministry'];

// Skip if the ministry has the new interactive gift-selector data (shown in gift-selector.php instead).
$krm_gift_help = krm_ministry_gift_help_data();
if ( isset( $krm_gift_help[ $krm_m['slug'] ] ) ) {
	return;
}

$krm_gifts = krm_ministry_gifts( $krm_m );
if ( ! $krm_gifts ) {
	return; // Visitors: no card has a real amount yet.
}
?>
<section class="ministry-gifts" aria-labelledby="gifts-heading">
	<?php krm_admin_only_note( ! in_array( true, wp_list_pluck( $krm_gifts, 'real' ), true ) ); ?>
	<div class="ministry-head">
		<h2 id="gifts-heading"><?php krm_t( 'ministry.gifts_heading', 'site' ); ?></h2>
		<p><?php krm_t( 'ministry.gifts_text', 'site' ); ?></p>
	</div>
	<ul class="gift-grid">
		<?php foreach ( $krm_gifts as $krm_gift ) : ?>
		<li><a class="gift-card" href="<?php echo esc_url( $krm_gift['url'] ); ?>">
			<span class="gift-card-amount"><?php echo esc_html( $krm_gift['amount'] ); ?></span>
			<span class="gift-card-title"><?php echo esc_html( $krm_gift['title'] ); ?></span>
			<span class="gift-card-text"><?php echo esc_html( $krm_gift['text'] ); ?></span>
		</a></li>
		<?php endforeach; ?>
	</ul>
</section>
