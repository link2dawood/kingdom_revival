<?php
/**
 * Home: 10. Partner With Us — four boxes (churches, Christian organisations, donors, prayer partners), each with its own
 * button. Text: Pages → Home → Page content → 10. Partner With Us. Styles: .krm-tile in assets/css/front.css.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_items = array_values(
	array_filter(
		(array) krm_c( 'partner.items' ),
		function ( $i ) {
			return is_array( $i ) && '' !== trim( (string) ( $i['title'] ?? '' ) );
		}
	)
);
if ( ! $krm_items ) {
	return;
}
// Icons follow the box's position: church, people, heart, praying hands.
$krm_icons = array(
	'<path d="M12 2v5"></path><path d="M9.5 4.5h5"></path><path d="M5 21V12l7-5 7 5v9"></path><path d="M10 21v-4a2 2 0 0 1 4 0v4"></path><path d="M3 21h18"></path>',
	'<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.9"></path><path d="M16 3.1a4 4 0 0 1 0 7.8"></path>',
	'<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path>',
	'<path d="M12 21l-3.5-3.5a3 3 0 0 1-.9-2.1V8.5a1.5 1.5 0 0 1 3 0V12"></path><path d="M12 21l3.5-3.5a3 3 0 0 0 .9-2.1V8.5a1.5 1.5 0 0 0-3 0V12"></path><path d="M12 12V4"></path>',
);
$krm_tones = array( 'navy', 'orange', 'red', 'navy' );
?>
<!-- Partner With Us -->
<section id="partner" class="krm-sec krm-sec--soft" aria-labelledby="krm-partner-title">
<div class="krm-sec__inner">
<div class="krm-sec__head krm-sec__head--center" data-reveal>
<p class="krm-sec__eyebrow"><?php krm_t( 'partner.eyebrow' ); ?></p>
<h2 id="krm-partner-title" class="krm-sec__title"><?php krm_h( 'partner.heading' ); ?></h2>
<?php if ( '' !== trim( (string) krm_c( 'partner.intro' ) ) ) : ?>
<p class="krm-sec__intro"><?php krm_t( 'partner.intro' ); ?></p>
<?php endif; ?>
</div>
<ul class="krm-tiles krm-tiles--4" data-reveal-stagger>
<?php foreach ( $krm_items as $krm_i => $krm_item ) : ?>
	<?php $krm_label = trim( (string) ( $krm_item['button'] ?? '' ) ); ?>
<li class="krm-tile krm-tile--<?php echo esc_attr( $krm_tones[ $krm_i % 4 ] ); ?>">
<span class="krm-tile__icon" aria-hidden="true"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?php echo $krm_icons[ $krm_i % 4 ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup above. ?></svg></span>
<h3><?php echo krm_text( $krm_item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<p><?php echo krm_text( $krm_item['text'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php if ( '' !== $krm_label ) : ?>
<a class="krm-tile__btn<?php echo 2 === $krm_i % 4 ? ' is-red' : ''; ?>" href="<?php echo esc_url( krm_resolve_url( (string) ( $krm_item['url'] ?? '' ) ?: '{partner}' ) ); ?>"><?php echo krm_text( $krm_label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
</div>
</section>
