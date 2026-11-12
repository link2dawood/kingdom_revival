<?php
/**
 * Home: 9. Our Journey — milestones from 2018 to today: a line across with a dot per year on computers, down the page
 * on tablets and phones. Text: Pages → Home → Page content → 9. Our Journey (the full timeline is on About Us).
 * Styles: .krm-journey in assets/css/front.css.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_items = array_values(
	array_filter(
		(array) krm_c( 'journey.items' ),
		function ( $i ) {
			return is_array( $i ) && ( '' !== trim( (string) ( $i['year'] ?? '' ) ) || '' !== trim( (string) ( $i['title'] ?? '' ) ) );
		}
	)
);
if ( ! $krm_items ) {
	return;
}
?>
<!-- Our Journey -->
<section id="journey" class="krm-sec krm-journey" aria-labelledby="krm-journey-title">
<div class="krm-sec__inner">
<div class="krm-sec__head krm-sec__head--row" data-reveal>
<div>
<p class="krm-sec__eyebrow"><?php krm_t( 'journey.eyebrow' ); ?></p>
<h2 id="krm-journey-title" class="krm-sec__title"><?php krm_h( 'journey.heading' ); ?></h2>
<?php if ( '' !== trim( (string) krm_c( 'journey.intro' ) ) ) : ?>
<p class="krm-sec__intro"><?php krm_t( 'journey.intro' ); ?></p>
<?php endif; ?>
</div>
<?php if ( '' !== trim( (string) krm_c( 'journey.link_label' ) ) ) : ?>
<a class="krm-sec__link" href="<?php krm_u( 'journey.link_url' ); ?>"><?php krm_t( 'journey.link_label' ); ?></a>
<?php endif; ?>
</div>
<ol class="krm-journey__list" style="--n: <?php echo (int) count( $krm_items ); ?>" data-reveal-stagger>
<?php foreach ( $krm_items as $krm_i => $krm_item ) : ?>
<li class="krm-journey__item<?php echo count( $krm_items ) - 1 === $krm_i ? ' is-now' : ''; ?>">
<span class="krm-journey__year"><?php echo krm_text( $krm_item['year'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<span class="krm-journey__dot" aria-hidden="true"></span>
<h3><?php echo krm_text( $krm_item['title'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
	<?php if ( '' !== trim( (string) ( $krm_item['text'] ?? '' ) ) ) : ?>
<p><?php echo krm_text( $krm_item['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php endif; ?>
</li>
<?php endforeach; ?>
</ol>
</div>
</section>
