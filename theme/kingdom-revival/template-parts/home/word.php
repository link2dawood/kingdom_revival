<?php
/**
 * Home: 7. God's Word in their hands — the Urdu Bibles band (theme 1.25.0), between Why Pakistan Needs Hope and Our
 * Impact (moved there in 1.25.1). A navy band over a blurred Bible-distribution photo: italic line with the number placed so far,
 * three-line heading (last line gold italics), a short text beside it; six photos pinned like prints (they drop in
 * one by one, lift and tilt on hover, show the place); three step cards (Gospel of John → Urdu Bible / Audio Bible)
 * with a faded item in the corner; a "Read the full story" button; the faint outline words "The Word" behind them.
 * Text: Pages → Home → Page content → 7. Styles: .krm-word in assets/css/front.css (animations: scroll reveal in
 * assets/js/effects.js; none with reduced motion).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_photos = array_values(
	array_filter(
		(array) krm_c( 'word.photos' ),
		function ( $p ) {
			return is_array( $p ) && '' !== trim( (string) ( $p['image'] ?? '' ) );
		}
	)
);
$krm_steps  = array_values(
	array_filter(
		(array) krm_c( 'word.steps' ),
		function ( $s ) {
			return is_array( $s ) && '' !== trim( (string) ( $s['title'] ?? '' ) );
		}
	)
);
$krm_btn    = trim( (string) krm_c( 'word.btn_label' ) );
$krm_btn_to = trim( (string) krm_c( 'word.btn_url' ) );
$krm_btn_to = '' !== $krm_btn_to ? krm_resolve_url( $krm_btn_to ) : krm_project_page_url( 'urdu-bibles' );
$krm_ghost  = trim( (string) krm_c( 'word.ghost' ) );
$krm_script = trim( (string) krm_c( 'word.script' ) );
$krm_text   = trim( (string) krm_c( 'word.text' ) );
?>
<!-- God's Word in their hands (Urdu Bibles) -->
<section id="urdu-bibles" class="krm-word" aria-labelledby="krm-word-title">
<div class="krm-word__bg" aria-hidden="true"<?php echo krm_photo_band_attrs( krm_c( 'word.band_photo' ), 'word' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></div>
<?php if ( '' !== $krm_ghost ) : ?>
<span class="krm-word__ghost" aria-hidden="true"><?php echo esc_html( $krm_ghost ); ?></span>
<?php endif; ?>
<div class="krm-word__inner">
<div class="krm-word__head">
<div class="krm-word__heading" data-reveal>
<?php if ( '' !== $krm_script ) : ?>
<p class="krm-word__script"><?php krm_t( 'word.script' ); ?></p>
<?php endif; ?>
<h2 id="krm-word-title" class="krm-word__title" data-no-hl><?php krm_h( 'word.heading' ); ?></h2>
</div>
<?php if ( '' !== $krm_text ) : ?>
<p class="krm-word__text" data-reveal><?php krm_t( 'word.text' ); ?></p>
<?php endif; ?>
</div>
<?php if ( $krm_photos ) : ?>
<ul class="krm-word__wall" data-reveal-stagger>
	<?php foreach ( $krm_photos as $krm_i => $krm_photo ) : ?>
		<?php
		$krm_cap   = trim( (string) ( $krm_photo['caption'] ?? '' ) );
		$krm_focus = krm_focal( $krm_photo['image'] ); // Crop anchored on the faces (no zoom, so heads are never cut).
		?>
<li class="krm-word__print" style="--tilt: <?php echo esc_attr( array( '-1.5deg', '1.2deg', '-1deg', '1.6deg', '-1.3deg', '1deg' )[ $krm_i % 6 ] ); ?>">
<figure>
<span class="krm-word__photo"><img<?php echo '' !== $krm_focus ? ' style="object-position: ' . esc_attr( $krm_focus ) . '"' : ''; ?> <?php krm_img_src( $krm_photo['image'], '(max-width: 599px) 62vw, (max-width: 1023px) 42vw, 280px' ); ?> alt="<?php echo krm_alt( $krm_photo['image'], $krm_photo['alt'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" loading="lazy" decoding="async"></span>
		<?php if ( '' !== $krm_cap ) : ?>
<figcaption><?php echo krm_text( $krm_cap ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></figcaption>
		<?php endif; ?>
</figure>
</li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php if ( $krm_steps ) : ?>
<ol class="krm-word__steps" data-reveal-stagger>
	<?php foreach ( $krm_steps as $krm_i => $krm_step ) : ?>
		<?php $krm_img = trim( (string) ( $krm_step['image'] ?? '' ) ); ?>
<li class="krm-word__step">
		<?php if ( '' !== trim( (string) ( $krm_step['label'] ?? '' ) ) ) : ?>
<p class="krm-word__label"><?php echo krm_text( $krm_step['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>
<h3><?php echo krm_text( $krm_step['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
		<?php if ( '' !== trim( (string) ( $krm_step['text'] ?? '' ) ) ) : ?>
<p class="krm-word__desc"><?php echo krm_text( $krm_step['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>
		<?php if ( '' !== $krm_img ) : ?>
<img class="krm-word__item" <?php krm_img_src( $krm_img, '200px' ); ?> alt="" loading="lazy" decoding="async">
		<?php elseif ( 2 === $krm_i ) : ?>
<svg class="krm-word__item krm-word__item--drawn" viewBox="0 0 120 100" aria-hidden="true" focusable="false"><path d="M22 62V54a38 38 0 0 1 76 0v8"></path><rect x="12" y="58" width="22" height="34" rx="8"></rect><rect x="86" y="58" width="22" height="34" rx="8"></rect><path d="M46 74c4-6 8-6 12 0s8 6 12 0"></path></svg>
		<?php else : ?>
<svg class="krm-word__item krm-word__item--drawn" viewBox="0 0 120 100" aria-hidden="true" focusable="false"><path d="M60 30C48 22 30 20 12 24v58c18-4 36-2 48 6 12-8 30-10 48-6V24c-18-4-36-2-48 6z"></path><path d="M60 30v58"></path><path d="M22 38c10-2 22-1 30 3M22 50c10-2 22-1 30 3M22 62c10-2 22-1 30 3M68 41c8-4 20-5 30-3M68 53c8-4 20-5 30-3M68 65c8-4 20-5 30-3"></path></svg>
		<?php endif; ?>
</li>
	<?php endforeach; ?>
</ol>
<?php endif; ?>
<?php if ( '' !== $krm_btn && '' !== $krm_btn_to ) : ?>
<p class="krm-word__more" data-reveal><a class="krm-word__btn" href="<?php echo esc_url( $krm_btn_to ); ?>"><?php echo krm_text( $krm_btn ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></a></p>
<?php endif; ?>
</div>
</section>
