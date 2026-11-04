<?php
/**
 * Ministry page 1: inner hero — the first photo (else Site text → Ministry pages → hero image), breadcrumb
 * (Home / What We Do / name), the group as the eyebrow, the title and the Excerpt.
 *
 * @param array $args { ministry, photos }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m    = $args['ministry'];
$krm_hero = $args['photos'] ? $args['photos'][0][0] : krm_c( 'ministry.hero_image', 'site' );
krm_hero_preload( $krm_hero );
?>
<section class="page-hero" id="ministry-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( $krm_hero ) ); ?>')<?php krm_bg_small( $krm_hero ); ?>">
<div class="wrap">
	<?php krm_breadcrumb_html( 'crumb-title' ); ?>
	<p class="eyebrow"><?php echo '' !== $krm_m['group'] ? esc_html( $krm_m['group'] ) : krm_text( krm_c( 'ministry.eyebrow', 'site' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- krm_text() escapes. ?></p>
	<h1 id="ministry-title"><?php echo esc_html( $krm_m['title'] ); ?></h1>
	<p class="lead" id="ministry-description"><?php echo esc_html( $krm_m['desc'] ); ?></p>
	<div class="hero-actions">
	<a class="button button-primary" href="#support"><?php echo krm_icon( 'heart18' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php krm_t( 'ministry.donate_button', 'site' ); ?></a>
	</div>
</div>
</section>
