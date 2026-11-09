<?php
/**
 * Home: About — "Reaching the unreached. Serving the forgotten." Text, Matthew 28:19, Our Story button and the founder
 * link on the left; a three-photo collage with the "Since 2018" badge on the right; four focus cards below.
 * Text and photos: Pages → Home → Page content → About. Styles: .krm-about in assets/css/front.css.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_photos = array_values(
	array_filter(
		(array) krm_c( 'about.photos' ),
		function ( $p ) {
			return is_array( $p ) && '' !== trim( (string) ( $p['image'] ?? '' ) );
		}
	)
);
$krm_photos = array_slice( $krm_photos, 0, 3 );
$krm_cards  = array_values(
	array_filter(
		(array) krm_c( 'about.cards' ),
		function ( $c ) {
			return is_array( $c ) && '' !== trim( (string) ( $c['title'] ?? '' ) );
		}
	)
);
$krm_t2     = trim( (string) krm_c( 'about.title2' ) );
$krm_verse  = trim( (string) krm_c( 'about.verse' ) );
$krm_btn    = trim( (string) krm_c( 'about.button_label' ) );
$krm_fname  = trim( (string) krm_c( 'about.founder_name' ) );
$krm_fphoto = trim( (string) krm_c( 'about.founder_photo' ) );
$krm_badge  = trim( (string) krm_c( 'about.badge_title' ) );

// Card icons and colours follow the card's position.
$krm_icons = array(
	'<circle cx="12" cy="12" r="9"></circle><path d="M3 12h18"></path><path d="M12 3a14 14 0 0 1 0 18a14 14 0 0 1 0-18z"></path>',
	'<path d="M12 2v5"></path><path d="M9.5 4.5h5"></path><path d="M5 21V12l7-5 7 5v9"></path><path d="M10 21v-4a2 2 0 0 1 4 0v4"></path><path d="M3 21h18"></path>',
	'<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path>',
	'<path d="M4 19.5V5a2 2 0 0 1 2-2h14v15H6.5A2.5 2.5 0 0 0 4 20.5 2.5 2.5 0 0 0 6.5 23H20v-5"></path><path d="M12 7v6"></path><path d="M9.5 9.5h5"></path>',
);
$krm_tones = array( 'navy', 'red', 'orange', 'navy' );
?>
<!-- About -->
<section id="about" class="krm-about" aria-labelledby="krm-about-title">
<div class="krm-about__inner">
<div class="krm-about__intro">
<div class="krm-about__copy" data-reveal>
<p class="krm-about__eyebrow"><?php krm_t( 'about.eyebrow' ); ?></p>
<h2 id="krm-about-title" class="krm-about__title"><?php krm_t( 'about.title1' ); ?><?php if ( '' !== $krm_t2 ) : ?> <em class="krm-about__accent"><?php krm_t( 'about.title2' ); ?></em><?php endif; ?></h2>
<p class="krm-about__lead"><?php krm_t( 'about.lead' ); ?></p>
<?php if ( '' !== $krm_verse ) : ?>
<blockquote class="krm-about__verse">
<p><?php krm_t( 'about.verse' ); ?></p>
<cite><?php krm_t( 'about.verse_cite' ); ?></cite>
</blockquote>
<?php endif; ?>
<?php if ( '' !== $krm_btn || '' !== $krm_fname ) : ?>
<div class="krm-about__actions">
<?php if ( '' !== $krm_btn ) : ?>
<a class="krm-about__btn" href="<?php krm_u( 'about.button_url' ); ?>"><?php krm_t( 'about.button_label' ); ?> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></a>
<?php endif; ?>
<?php if ( '' !== $krm_fname ) : ?>
<a class="krm-about__founder" href="<?php krm_u( 'about.founder_url' ); ?>">
<?php if ( '' !== $krm_fphoto ) : ?>
<img <?php krm_img_src( $krm_fphoto, '52px' ); ?> alt="" width="52" height="52" loading="lazy" decoding="async">
<?php endif; ?>
<span><strong><?php krm_t( 'about.founder_name' ); ?></strong><small><?php krm_t( 'about.founder_role' ); ?></small></span>
</a>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
<?php if ( $krm_photos ) : ?>
<div class="krm-about__collage krm-about__collage--<?php echo (int) count( $krm_photos ); ?>" data-reveal>
<?php foreach ( $krm_photos as $krm_i => $krm_p ) : ?>
<figure class="krm-about__photo">
<img <?php krm_img_src( $krm_p['image'], '(max-width: 900px) 50vw, 300px' ); ?> alt="<?php echo krm_alt( $krm_p['image'], $krm_p['alt'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" data-zoom="about" loading="lazy" decoding="async">
<?php if ( '' !== trim( (string) ( $krm_p['caption'] ?? '' ) ) ) : ?>
<figcaption><?php echo krm_text( $krm_p['caption'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></figcaption>
<?php endif; ?>
</figure>
<?php endforeach; ?>
<?php if ( '' !== $krm_badge ) : ?>
<p class="krm-about__badge"><strong><?php krm_t( 'about.badge_title' ); ?></strong><span><?php krm_t( 'about.badge_text' ); ?></span></p>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
<?php if ( $krm_cards ) : ?>
<ul class="krm-about__cards" data-reveal-stagger>
<?php foreach ( $krm_cards as $krm_i => $krm_card ) : ?>
<?php $krm_url = trim( (string) ( $krm_card['url'] ?? '' ) ); ?>
<li class="krm-about__card krm-about__card--<?php echo esc_attr( $krm_tones[ $krm_i % 4 ] ); ?>">
<span class="krm-about__icon" aria-hidden="true"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?php echo $krm_icons[ $krm_i % 4 ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup above. ?></svg></span>
<h3><?php if ( '' !== $krm_url ) : ?><a href="<?php echo esc_url( krm_resolve_url( $krm_url ) ); ?>"><?php endif; ?><?php echo krm_text( $krm_card['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php if ( '' !== $krm_url ) : ?></a><?php endif; ?></h3>
<p><?php echo krm_text( $krm_card['text'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</div>
</section>
