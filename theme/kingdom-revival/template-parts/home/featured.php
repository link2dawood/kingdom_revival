<?php
/**
 * Home: 7. Featured Projects — three project cards (Brick Kiln Family Freedom, Clean Water, Vulnerable Children), each
 * with a photo, a short text and a "Support This Project" button. The giving-card style (.cause-card, theme-global.css).
 * Text: Pages → Home → Page content → 7. Featured Projects. The full list of projects is on Donate → Our Projects.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_cards = array_values(
	array_filter(
		(array) krm_c( 'featured.cards' ),
		function ( $c ) {
			return is_array( $c ) && '' !== trim( (string) ( $c['title'] ?? '' ) );
		}
	)
);
if ( ! $krm_cards ) {
	return;
}
$krm_button = trim( (string) krm_c( 'featured.button_label' ) );
?>
<!-- Featured Projects -->
<section id="projects" class="krm-sec" aria-labelledby="krm-featured-title">
<div class="krm-sec__inner">
<div class="krm-sec__head krm-sec__head--row" data-reveal>
<div>
<p class="krm-sec__eyebrow"><?php krm_t( 'featured.eyebrow' ); ?></p>
<h2 id="krm-featured-title" class="krm-sec__title"><?php krm_h( 'featured.heading' ); ?></h2>
</div>
<?php if ( '' !== trim( (string) krm_c( 'featured.all_label' ) ) ) : ?>
<a class="krm-sec__link" href="<?php krm_u( 'featured.all_url' ); ?>"><?php krm_t( 'featured.all_label' ); ?></a>
<?php endif; ?>
</div>
<ul class="ministry-grid krm-featured" data-reveal-stagger>
<?php foreach ( $krm_cards as $krm_i => $krm_card ) : ?>
	<?php
	$krm_img   = trim( (string) ( $krm_card['image'] ?? '' ) );
	$krm_title = wp_strip_all_tags( krm_fill( $krm_card['title'] ) );
	?>
<li>
<article class="cause-card<?php echo $krm_i % 2 ? ' is-alt' : ''; ?>">
<div class="cause-card-media">
	<?php if ( '' !== $krm_img ) : ?>
<img <?php krm_img_src( $krm_img, '(max-width: 680px) calc(100vw - 32px), (max-width: 1040px) 50vw, 400px', true ); ?> alt="<?php echo krm_alt( $krm_img, $krm_card['alt'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" width="400" height="210" loading="lazy" decoding="async">
	<?php endif; ?>
	<?php if ( '' !== trim( (string) ( $krm_card['tag'] ?? '' ) ) ) : ?>
<span class="cause-card-tag"><?php echo krm_text( $krm_card['tag'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	<?php endif; ?>
</div>
<div class="cause-card-body">
<h3 class="cause-card-title"><?php echo krm_text( $krm_card['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
	<?php if ( '' !== trim( (string) ( $krm_card['text'] ?? '' ) ) ) : ?>
<p class="cause-card-text"><?php echo krm_text( $krm_card['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php endif; ?>
	<?php if ( '' !== $krm_button ) : ?>
<div class="cause-card-actions"><a class="cause-card-donate" href="<?php echo esc_url( krm_resolve_url( (string) ( $krm_card['url'] ?? '' ) ?: '{donate}#give' ) ); ?>"><?php echo krm_text( $krm_button ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="visually-hidden">: <?php echo esc_html( $krm_title ); ?></span></a></div>
	<?php endif; ?>
</div>
</article>
</li>
<?php endforeach; ?>
</ul>
</div>
</section>
