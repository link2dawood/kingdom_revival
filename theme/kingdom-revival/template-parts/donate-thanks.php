<?php
/**
 * Donate page: thank-you view for a donor returning from PayPal's checkout (?thanks=1).
 * Built from the Donate page's own hero, card and gift summary styles; text from the "Thank-you view" section.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_gift = wp_parse_args(
	$args,
	array(
		'amount'  => 0,
		'monthly' => false,
		'fund'    => '',
	)
);
?>
	<?php krm_hero_preload( krm_c( 'hero.image' ) ); ?>
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( krm_c( 'hero.image' ) ) ); ?>')<?php krm_bg_small( krm_c( 'hero.image' ) ); ?>">
		<div class="wrap">
		<?php krm_breadcrumb_html(); ?>
		<p class="eyebrow"><?php krm_t( 'thanks.eyebrow' ); ?></p>
		<h1><?php krm_h( 'thanks.title' ); ?></h1>
		<p class="lead"><?php krm_t( 'thanks.lead' ); ?></p>
		</div>
	</section>

	<section class="section section-soft">
		<div class="wrap">
		<div class="card thanks-card" role="status">
			<?php if ( $krm_gift['amount'] ) : ?>
			<div class="summary">
				<span class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg></span>
				<div><small>Your gift</small><strong><?php echo esc_html( krm_money( $krm_gift['amount'] ) . ( $krm_gift['monthly'] ? ' / month' : '' ) ); ?></strong><?php if ( $krm_gift['fund'] ) : ?><small>for <?php echo esc_html( 'Where it is needed most' === $krm_gift['fund'] ? 'the mission, where it is needed most' : $krm_gift['fund'] ); ?></small><?php endif; ?></div>
			</div>
			<?php endif; ?>
			<?php foreach ( krm_lines( krm_c( 'thanks.text' ) ) as $krm_line ) : ?>
			<p><?php echo krm_text( $krm_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endforeach; ?>
			<?php if ( $krm_gift['monthly'] ) : ?>
			<p class="note"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4M12 8h.01"></path></svg><span><?php krm_t( 'thanks.monthly_text' ); ?></span></p>
			<?php endif; ?>
			<div class="hero-actions">
				<a class="button button-primary" href="<?php krm_u( 'thanks.button1_url' ); ?>"><?php krm_t( 'thanks.button1_label' ); ?></a>
				<a class="button button-secondary" href="<?php krm_u( 'thanks.button2_url' ); ?>"><?php krm_t( 'thanks.button2_label' ); ?></a>
			</div>
		</div>
		</div>
	</section>
