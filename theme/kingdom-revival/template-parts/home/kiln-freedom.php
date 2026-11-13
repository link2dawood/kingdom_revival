<?php
/**
 * Home: 10. Brick Kiln Family Freedom (theme 1.25.0) — soft grey-blue band (site colours since 1.25.2) with a faint kiln photo; heading and text; a
 * before/after photo slider of one family (drag with mouse or touch, arrow keys on the handle; dots switch families
 * when there are two or more) and the six numbered steps with a button to the project page.
 * Styles: .krm-kf in assets/css/front.css. Script: assets/js/kiln-freedom.js (slider, hint nudge, family dots).
 * Without JavaScript the slider shows the first family split in the middle.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

// Families with both photos. Visitors never see [placeholders] (krm_visible).
$krm_families = array_values(
	array_filter(
		krm_visible( krm_c( 'kiln.families' ), 'before' ),
		function ( $f ) {
			return ! empty( $f['before'] ) && ! empty( $f['after'] );
		}
	)
);
$krm_steps = krm_visible( krm_c( 'kiln.steps' ), 'title' );
if ( ! $krm_families && ! $krm_steps ) {
	return;
}

// {freed} = the "Families freed" counter of 7. Our Impact, so the two numbers can never disagree.
$krm_freed = '';
foreach ( (array) krm_c( 'impact.counters' ) as $krm_counter ) {
	if ( isset( $krm_counter['label'], $krm_counter['value'] ) && preg_match( '/famil.*free/i', $krm_counter['label'] ) ) {
		$krm_freed = trim( (string) $krm_counter['value'] );
		break;
	}
}
$krm_kf_title = function ( $title ) use ( $krm_freed ) {
	if ( false === strpos( (string) $title, '{freed}' ) ) {
		return $title;
	}
	return '' !== $krm_freed ? str_replace( '{freed}', $krm_freed, $title ) : __( 'One family set free', 'kingdom-revival' );
};
$krm_bg    = krm_c( 'kiln.bg_photo' );
$krm_label = krm_c( 'kiln.compare_label' );
?>
<!-- 9. Brick Kiln Family Freedom -->
<section id="kiln-freedom" class="krm-kf" aria-labelledby="krm-kf-title">
<?php if ( $krm_bg ) : ?>
<img class="krm-kf__bg" loading="lazy" decoding="async" <?php krm_img_src( $krm_bg, '100vw' ); ?> alt="" aria-hidden="true">
<?php endif; ?>
<div class="krm-kf__in">
<div class="krm-kf__head">
<div data-reveal>
<?php if ( '' !== trim( (string) krm_c( 'kiln.eyebrow' ) ) ) : ?>
<p class="krm-kf__script"><?php krm_t( 'kiln.eyebrow' ); ?></p>
<?php endif; ?>
<h2 id="krm-kf-title" class="krm-kf__title" data-no-hl><?php krm_t( 'kiln.heading' ); ?><?php if ( '' !== trim( (string) krm_c( 'kiln.heading_2' ) ) ) : ?><br><em><?php krm_t( 'kiln.heading_2' ); ?></em><?php endif; ?></h2>
</div>
<?php if ( '' !== trim( (string) krm_c( 'kiln.intro' ) ) ) : ?>
<p class="krm-kf__intro" data-reveal><?php krm_t( 'kiln.intro' ); ?></p>
<?php endif; ?>
</div>
<div class="krm-kf__main">
<?php if ( $krm_families ) : ?>
<div class="krm-kf__media" data-reveal data-krm-kf>
<div class="krm-kf__frame" data-krm-kf-frame style="--kf-pos: 50%">
<?php foreach ( $krm_families as $krm_i => $krm_f ) : ?>
<div class="krm-kf__pair" data-krm-kf-pair<?php echo $krm_i ? ' hidden' : ''; ?>>
<img class="krm-kf__img" loading="lazy" decoding="async" <?php krm_img_src( $krm_f['after'], '(max-width: 960px) 100vw, 600px' ); ?> alt="<?php echo krm_alt( $krm_f['after'], isset( $krm_f['after_alt'] ) ? $krm_f['after_alt'] : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
<div class="krm-kf__before">
<img class="krm-kf__img" loading="lazy" decoding="async" <?php krm_img_src( $krm_f['before'], '(max-width: 960px) 100vw, 600px' ); ?> alt="<?php echo krm_alt( $krm_f['before'], isset( $krm_f['before_alt'] ) ? $krm_f['before_alt'] : '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
</div>
</div>
<?php endforeach; ?>
<?php if ( '' !== trim( (string) krm_c( 'kiln.before_label' ) ) ) : ?>
<span class="krm-kf__tag is-before" aria-hidden="true"><?php krm_t( 'kiln.before_label' ); ?></span>
<?php endif; ?>
<?php if ( '' !== trim( (string) krm_c( 'kiln.after_label' ) ) ) : ?>
<span class="krm-kf__tag is-after" aria-hidden="true"><?php krm_t( 'kiln.after_label' ); ?></span>
<?php endif; ?>
<span class="krm-kf__line" aria-hidden="true"></span>
<span class="krm-kf__handle" data-krm-kf-handle role="slider" tabindex="0" aria-label="<?php echo esc_attr( '' !== trim( (string) $krm_label ) ? krm_fill( $krm_label ) : __( 'Compare before and after', 'kingdom-revival' ) ); ?>" aria-valuemin="0" aria-valuemax="100" aria-valuenow="50" aria-valuetext="<?php esc_attr_e( '50% before photo', 'kingdom-revival' ); ?>"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 8h15l-4-4M20 16H5l4 4"></path></svg></span>
<?php if ( '' !== trim( (string) $krm_label ) ) : ?>
<span class="krm-kf__tag is-hint" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 8h15l-4-4M20 16H5l4 4"></path></svg><?php echo krm_text( $krm_label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<?php endif; ?>
</div>
<?php foreach ( $krm_families as $krm_i => $krm_f ) : ?>
<div class="krm-kf__cap" data-krm-kf-cap<?php echo $krm_i ? ' hidden' : ''; ?>>
<?php if ( '' !== trim( (string) $krm_f['title'] ) ) : ?>
<h3><?php echo krm_text( $krm_kf_title( $krm_f['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
<?php endif; ?>
<?php if ( '' !== trim( (string) $krm_f['text'] ) ) : ?>
<p><?php echo krm_text( $krm_f['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<?php endif; ?>
</div>
<?php endforeach; ?>
<?php if ( count( $krm_families ) > 1 ) : ?>
<div class="krm-kf__dots" data-krm-kf-dots hidden>
<?php foreach ( $krm_families as $krm_i => $krm_f ) : ?>
<button type="button" class="krm-kf__dot" data-krm-kf-dot="<?php echo (int) $krm_i; ?>" aria-pressed="<?php echo $krm_i ? 'false' : 'true'; ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: family number, 2: number of families */ __( 'Family %1$d of %2$d', 'kingdom-revival' ), $krm_i + 1, count( $krm_families ) ) ); ?>"></button>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div>
<?php endif; ?>
<?php if ( $krm_steps ) : ?>
<div class="krm-kf__list">
<ol class="krm-kf__steps" data-reveal-stagger>
<?php foreach ( $krm_steps as $krm_i => $krm_step ) : ?>
<li>
<span class="krm-kf__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $krm_i + 1 ) ); ?></span>
<p class="krm-kf__step"><strong><?php echo krm_text( $krm_step['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong><?php if ( '' !== trim( (string) $krm_step['text'] ) ) : ?><span class="krm-kf__dash" aria-hidden="true"> — </span><span class="krm-kf__desc"><?php echo krm_text( $krm_step['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php endif; ?></p>
</li>
<?php endforeach; ?>
</ol>
<?php if ( '' !== trim( (string) krm_c( 'kiln.button_label' ) ) ) : ?>
<a class="krm-kf__btn" href="<?php krm_u( 'kiln.button_url' ); ?>"><?php krm_t( 'kiln.button_label' ); ?><span aria-hidden="true">→</span></a>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
</div>
</section>
