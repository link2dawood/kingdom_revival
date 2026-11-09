<?php
/**
 * Home: "Bringing Christ to the brick kilns of Pakistan" — mission statement with Luke 4:18, after the ministry
 * explorer. Text: Pages → Home → Page content → Mission. Styles: .krm-mission in assets/css/front.css.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_b1 = trim( (string) krm_c( 'mission.button1_label' ) );
$krm_b2 = trim( (string) krm_c( 'mission.button2_label' ) );
$krm_gw = trim( (string) krm_c( 'mission.ghost' ) );
?>
<!-- Mission: brick kilns of Pakistan -->
<section id="mission" class="krm-mission" aria-labelledby="krm-mission-title">
<div class="krm-mission__inner" data-reveal>
<p class="krm-mission__eyebrow"><?php krm_t( 'mission.eyebrow' ); ?></p>
<h2 id="krm-mission-title" class="krm-mission__title"><?php krm_t( 'mission.heading1' ); ?> <em><?php krm_t( 'mission.heading2' ); ?></em></h2>
<p class="krm-mission__text"><?php krm_t( 'mission.text' ); ?></p>
<blockquote class="krm-mission__verse">
<p><?php krm_t( 'mission.quote' ); ?></p>
<cite><?php krm_t( 'mission.cite' ); ?></cite>
</blockquote>
<?php if ( '' !== $krm_b1 || '' !== $krm_b2 ) : ?>
<div class="krm-mission__actions">
<?php if ( '' !== $krm_b1 ) : ?>
<a class="button button-primary" href="<?php krm_u( 'mission.button1_url' ); ?>"><?php krm_t( 'mission.button1_label' ); ?></a>
<?php endif; ?>
<?php if ( '' !== $krm_b2 ) : ?>
<a class="button button-light" href="<?php krm_u( 'mission.button2_url' ); ?>"><?php krm_t( 'mission.button2_label' ); ?></a>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
<?php if ( '' !== $krm_gw ) : ?>
<span class="krm-mission__ghost" aria-hidden="true"><?php echo esc_html( $krm_gw ); ?></span>
<?php endif; ?>
</section>
