<?php
/**
 * Home: 3. Who We Are — "Reaching the unreached. Serving the forgotten." Text, "Founded in 2018", the Vision and Mission
 * boxes, Our Story button and the founder link on the left; a three-photo collage with the "Founded in 2018" badge on the
 * right (theme 1.23.0: the verse and the four focus cards left; What We Do follows this section).
 * Text and photos: Pages → Home → Page content → 3. Who We Are. Styles: .krm-about in assets/css/front.css.
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
$krm_t2     = trim( (string) krm_c( 'about.title2' ) );
$krm_vm     = array(); // Vision, then Mission: [ heading, text ]; a box with no text is left out.
foreach ( array( 'vision', 'mission' ) as $krm_k ) {
	if ( '' !== trim( (string) krm_c( "about.{$krm_k}_text" ) ) ) {
		$krm_vm[ $krm_k ] = array( krm_c( "about.{$krm_k}_title" ), krm_c( "about.{$krm_k}_text" ) );
	}
}
$krm_btn    = trim( (string) krm_c( 'about.button_label' ) );
$krm_fname  = trim( (string) krm_c( 'about.founder_name' ) );
$krm_fphoto = trim( (string) krm_c( 'about.founder_photo' ) );
$krm_badge  = trim( (string) krm_c( 'about.badge_title' ) );

?>
<!-- About -->
<section id="about" class="krm-about" aria-labelledby="krm-about-title">
<div class="krm-about__inner">
<div class="krm-about__intro">
<div class="krm-about__copy" data-reveal>
<p class="krm-about__eyebrow"><?php krm_t( 'about.eyebrow' ); ?></p>
<h2 id="krm-about-title" class="krm-about__title"><?php krm_t( 'about.title1' ); ?><?php if ( '' !== $krm_t2 ) : ?> <em class="krm-about__accent"><?php krm_t( 'about.title2' ); ?></em><?php endif; ?></h2>
<?php if ( '' !== trim( (string) krm_c( 'about.founded' ) ) ) : ?>
<p class="krm-about__founded"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg><?php krm_t( 'about.founded' ); ?></p>
<?php endif; ?>
<p class="krm-about__lead"><?php krm_t( 'about.lead' ); ?></p>
<?php if ( $krm_vm ) : ?>
<div class="krm-about__vm">
<?php foreach ( $krm_vm as $krm_k => $krm_box ) : ?>
<div class="krm-about__vm-box krm-about__vm-box--<?php echo esc_attr( $krm_k ); ?>">
<span class="krm-about__vm-icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo 'vision' === $krm_k ? '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"></path><circle cx="12" cy="12" r="3"></circle>' : '<circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="5"></circle><circle cx="12" cy="12" r="1"></circle>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed icons. ?></svg></span>
<h3><?php echo krm_text( $krm_box[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<p><?php echo krm_text( $krm_box[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
</div>
<?php endforeach; ?>
</div>
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
</div>
</section>
