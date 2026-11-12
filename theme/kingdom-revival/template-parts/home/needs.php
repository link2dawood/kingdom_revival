<?php
/**
 * Home: 5. Why Pakistan Needs Hope — a large photo beside short blocks of text (poverty, brick kiln bonded labour, clean
 * water, vulnerable children, the persecuted Christian minority). Text: Pages → Home → Page content → 5. Why Pakistan
 * Needs Hope. Styles: .krm-needs in assets/css/front.css.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_items = array_values(
	array_filter(
		(array) krm_c( 'needs.items' ),
		function ( $i ) {
			return is_array( $i ) && '' !== trim( (string) ( $i['title'] ?? '' ) );
		}
	)
);
if ( ! $krm_items ) {
	return;
}
$krm_img = trim( (string) krm_c( 'needs.image' ) );
// Icons follow the block's position: coins, bricks, water drop, child, cross.
$krm_icons = array(
	'<circle cx="9" cy="9" r="6"></circle><path d="M15.4 9.6A6 6 0 1 1 9.6 15.4"></path><path d="M9 7v4M7.5 9h3"></path>',
	'<rect x="3" y="4" width="18" height="16" rx="1"></rect><path d="M3 9.3h18M3 14.6h18M9 4v5.3M15 9.3v5.3M9 14.6V20"></path>',
	'<path d="M12 3s6 6.4 6 11a6 6 0 0 1-12 0c0-4.6 6-11 6-11z"></path><path d="M9.5 15a2.5 2.5 0 0 0 2.5 2.5"></path>',
	'<circle cx="12" cy="6" r="3"></circle><path d="M12 9v6M8 12h8M10 21l2-6 2 6"></path>',
	'<path d="M12 3v18"></path><path d="M7 8h10"></path>',
);
?>
<!-- Why Pakistan Needs Hope -->
<section id="why-pakistan" class="krm-sec krm-sec--soft krm-needs" aria-labelledby="krm-needs-title">
<div class="krm-sec__inner krm-needs__grid">
<?php if ( '' !== $krm_img ) : ?>
<figure class="krm-needs__photo" data-reveal>
<img <?php krm_img_src( $krm_img, '(max-width: 900px) calc(100vw - 32px), 520px' ); ?> alt="<?php echo krm_alt( $krm_img, krm_c( 'needs.image_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" data-zoom="needs" loading="lazy" decoding="async">
	<?php if ( '' !== trim( (string) krm_c( 'needs.image_label' ) ) ) : ?>
<figcaption><?php krm_t( 'needs.image_label' ); ?></figcaption>
	<?php endif; ?>
</figure>
<?php endif; ?>
<div class="krm-needs__copy">
<div class="krm-sec__head" data-reveal>
<p class="krm-sec__eyebrow"><?php krm_t( 'needs.eyebrow' ); ?></p>
<h2 id="krm-needs-title" class="krm-sec__title"><?php krm_h( 'needs.heading' ); ?></h2>
<?php if ( '' !== trim( (string) krm_c( 'needs.intro' ) ) ) : ?>
<p class="krm-sec__intro"><?php krm_t( 'needs.intro' ); ?></p>
<?php endif; ?>
</div>
<ul class="krm-needs__list" data-reveal-stagger>
<?php foreach ( $krm_items as $krm_i => $krm_item ) : ?>
<li class="krm-needs__item">
<span class="krm-needs__icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><?php echo $krm_icons[ $krm_i % 5 ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup above. ?></svg></span>
<div><h3><?php echo krm_text( $krm_item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<p><?php echo krm_text( $krm_item['text'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p></div>
</li>
<?php endforeach; ?>
</ul>
<?php if ( '' !== trim( (string) krm_c( 'needs.button_label' ) ) ) : ?>
<p class="krm-needs__action"><a class="krm-about__btn" href="<?php krm_u( 'needs.button_url' ); ?>"><?php krm_t( 'needs.button_label' ); ?> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></a></p>
<?php endif; ?>
</div>
</div>
</section>
