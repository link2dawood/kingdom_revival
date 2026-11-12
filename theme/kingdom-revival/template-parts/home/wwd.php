<?php
/**
 * Home: 4. What We Do — three cards (Gospel & Evangelism, Humanitarian Outreach, Church & Leadership Development), each
 * with an icon, a short text and a "Learn More" link. Text: Pages → Home → Page content → 4. What We Do.
 * Styles: .krm-sec (section head) and .krm-tile (cards) in assets/css/front.css.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_cards = array_values(
	array_filter(
		(array) krm_c( 'wwd.cards' ),
		function ( $c ) {
			return is_array( $c ) && '' !== trim( (string) ( $c['title'] ?? '' ) );
		}
	)
);
if ( ! $krm_cards ) {
	return;
}
// Icons and colours follow the card's position: cross (Gospel), helping hands (humanitarian), church (leadership).
$krm_icons = array(
	'<path d="M12 3v18"></path><path d="M6 9h12"></path>',
	'<path d="M11 14h2a2 2 0 0 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16"></path><path d="M7 20l1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"></path><path d="M2 15l6 6"></path><path d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12z"></path>',
	'<path d="M12 2v5"></path><path d="M9.5 4.5h5"></path><path d="M5 21V12l7-5 7 5v9"></path><path d="M10 21v-4a2 2 0 0 1 4 0v4"></path><path d="M3 21h18"></path>',
);
$krm_tones = array( 'red', 'orange', 'navy' );
$krm_more  = trim( (string) krm_c( 'wwd.more_label' ) );
?>
<!-- What We Do -->
<section id="what-we-do" class="krm-sec" aria-labelledby="krm-wwd-title">
<div class="krm-sec__inner">
<div class="krm-sec__head krm-sec__head--center" data-reveal>
<p class="krm-sec__eyebrow"><?php krm_t( 'wwd.eyebrow' ); ?></p>
<h2 id="krm-wwd-title" class="krm-sec__title"><?php krm_h( 'wwd.heading' ); ?></h2>
<?php if ( '' !== trim( (string) krm_c( 'wwd.intro' ) ) ) : ?>
<p class="krm-sec__intro"><?php krm_t( 'wwd.intro' ); ?></p>
<?php endif; ?>
</div>
<ul class="krm-tiles krm-tiles--3" data-reveal-stagger>
<?php foreach ( $krm_cards as $krm_i => $krm_card ) : ?>
	<?php $krm_url = trim( (string) ( $krm_card['url'] ?? '' ) ); ?>
<li class="krm-tile krm-tile--<?php echo esc_attr( $krm_tones[ $krm_i % 3 ] ); ?>">
<span class="krm-tile__icon" aria-hidden="true"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?php echo $krm_icons[ $krm_i % 3 ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup above. ?></svg></span>
<h3><?php echo krm_text( $krm_card['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<p><?php echo krm_text( $krm_card['text'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php if ( '' !== $krm_url && '' !== $krm_more ) : ?>
<a class="krm-tile__link" href="<?php echo esc_url( krm_resolve_url( $krm_url ) ); ?>"><?php echo krm_text( $krm_more ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="visually-hidden">: <?php echo esc_html( wp_strip_all_tags( krm_fill( $krm_card['title'] ) ) ); ?></span> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></a>
	<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
<?php if ( '' !== trim( (string) krm_c( 'wwd.all_label' ) ) ) : ?>
<p class="krm-sec__more"><a href="<?php krm_u( 'wwd.all_url' ); ?>"><?php krm_t( 'wwd.all_label' ); ?></a></p>
<?php endif; ?>
</div>
</section>
