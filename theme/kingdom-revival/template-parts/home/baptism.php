<?php
/**
 * Home: 7b. New life in Christ — the baptism verse banner (theme 1.26.0), straight after the Urdu Bibles band so the
 * two read as one story: from receiving God's Word to baptism. KRM's baptism photo fills the whole section (1.26.1; it slides
 * a little slower than the page: parallax, none with reduced motion) under a navy tint, darkest on the left behind the gold
 * label, the verse in large serif italics (each line fades up in turn), the reference, a short paragraph, an
 * optional number line and an outlined button. On phones and small tablets the tint is even, slightly darker at the foot.
 * Text: Pages → Home → Page content → 7b. Styles: .krm-baptism in assets/css/front.css; line timing via
 * data-reveal-delay (assets/js/effects.js).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_photo  = krm_c( 'baptism.photo' );
$krm_lines  = array_values( array_filter( array_map( 'trim', preg_split( '/\R/', (string) krm_c( 'baptism.verse' ) ) ), 'strlen' ) );
$krm_label  = trim( (string) krm_c( 'baptism.label' ) );
$krm_ref    = trim( (string) krm_c( 'baptism.reference' ) );
$krm_text   = trim( (string) krm_c( 'baptism.text' ) );
$krm_stat   = trim( (string) krm_c( 'baptism.stat' ) );
$krm_btn    = trim( (string) krm_c( 'baptism.btn_label' ) );
$krm_btn_to = trim( (string) krm_c( 'baptism.btn_url' ) );
$krm_btn_to = '' !== $krm_btn_to ? krm_resolve_url( $krm_btn_to ) : '';
$krm_def    = 'krm-theme:images/content/baptism-new-life.jpg';

if ( ! $krm_lines && '' === $krm_label ) {
	return;
}

// Each part fades up after the one before it: the verse lines 180 ms apart, then the reference and the rest 120 ms apart.
$krm_delay = 0;
$krm_next  = function ( $step = 120 ) use ( &$krm_delay ) {
	$d          = $krm_delay;
	$krm_delay += $step;
	return ' data-reveal data-reveal-delay="' . (int) $d . '"';
};
?>
<!-- New life in Christ (baptism verse banner) -->
<section id="new-life" class="krm-baptism<?php echo $krm_photo ? ' krm-baptism--photo' : ''; ?>"<?php echo '' !== $krm_label ? ' aria-labelledby="krm-baptism-title"' : ' aria-label="' . esc_attr__( 'New life in Christ', 'kingdom-revival' ) . '"'; ?>>
<?php if ( $krm_photo ) : ?>
<div class="krm-baptism__media">
<img class="krm-baptism__img" data-parallax
	<?php
	if ( $krm_def === $krm_photo ) {
		// The theme's own copy: a 1000px version for phones and small tablets.
		printf(
			'src="%1$s" srcset="%2$s 1000w, %1$s 2000w" sizes="100vw"',
			esc_url( krm_media( $krm_def ) ),
			esc_url( krm_media( 'krm-theme:images/content/baptism-new-life-1000.jpg' ) )
		);
	} else {
		krm_img_src( $krm_photo, '100vw' );
	}
	?>
	alt="<?php echo krm_alt( $krm_photo, krm_c( 'baptism.photo_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" width="2000" height="1125" loading="lazy" decoding="async">
</div>
<?php endif; ?>
<div class="krm-baptism__inner">
<div class="krm-baptism__content">
<?php if ( '' !== $krm_label ) : ?>
<h2 id="krm-baptism-title" class="krm-baptism__label" data-no-hl<?php echo $krm_next( 180 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo krm_text( $krm_label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
<?php endif; ?>
<?php if ( $krm_lines ) : ?>
<blockquote class="krm-baptism__verse">
<p>
	<?php foreach ( $krm_lines as $krm_line ) : ?>
<span class="krm-baptism__line"<?php echo $krm_next( 180 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo krm_text( $krm_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	<?php endforeach; ?>
</p>
	<?php if ( '' !== $krm_ref ) : ?>
<footer class="krm-baptism__ref"<?php echo $krm_next(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><cite><?php echo krm_text( $krm_ref ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></cite></footer>
	<?php endif; ?>
</blockquote>
<?php endif; ?>
<?php if ( '' !== $krm_text ) : ?>
<p class="krm-baptism__text"<?php echo $krm_next(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo krm_text( $krm_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<?php endif; ?>
<?php if ( '' !== $krm_stat ) : ?>
<p class="krm-baptism__stat"<?php echo $krm_next(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo krm_text( $krm_stat ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<?php endif; ?>
<?php if ( '' !== $krm_btn && '' !== $krm_btn_to ) : ?>
<p class="krm-baptism__more"<?php echo $krm_next(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><a class="krm-baptism__btn" href="<?php echo esc_url( $krm_btn_to ); ?>"><?php echo krm_text( $krm_btn ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></a></p>
<?php endif; ?>
</div>
</div>
</section>
