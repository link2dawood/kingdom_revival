<?php
/**
 * Ministry page 2: two-column intro — the home About section's overlapping photo pair, "What we do", the ministry's
 * points ("How this ministry serves") as a check list and its focus text; then the ministry's extra sections as panels.
 *
 * The pair uses the 2nd and 3rd photos (the 1st is the hero); with fewer photos it reuses what there is, and with none
 * it shows the design's empty tinted boxes.
 *
 * @param array $args { ministry, photos }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m      = $args['ministry'];
$krm_photos = $args['photos'];
$krm_n      = count( $krm_photos );
$krm_pair   = array(
	$krm_n > 1 ? $krm_photos[1] : ( $krm_n ? $krm_photos[0] : null ),
	$krm_n > 2 ? $krm_photos[2] : ( $krm_n > 1 ? $krm_photos[0] : null ),
);
$krm_focus  = $krm_m['id'] ? (string) get_post_meta( $krm_m['id'], '_krm_focus', true ) : '';
$krm_points = $krm_m['id'] ? krm_lines( (string) get_post_meta( $krm_m['id'], '_krm_points', true ) ) : array();
?>
<section class="ministry-intro" aria-labelledby="intro-heading">
	<div class="ministry-pair">
		<?php foreach ( $krm_pair as $krm_i => $krm_photo ) : ?>
		<div class="ministry-pair-img is-<?php echo 0 === $krm_i ? 'back' : 'front'; ?>">
			<?php if ( $krm_photo ) : ?>
			<img <?php krm_img_src( $krm_photo[0], 0 === $krm_i ? '(max-width: 760px) 75vw, 360px' : '(max-width: 760px) 50vw, 240px' ); ?> alt="<?php echo esc_attr( $krm_photo[1] ); ?>" data-zoom="ministry-intro" loading="lazy" decoding="async">
			<?php endif; ?>
		</div>
		<?php endforeach; ?>
		<span class="ministry-pair-badge" aria-hidden="true"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#141B33" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg></span>
	</div>
	<div class="ministry-intro-text">
		<p class="ministry-kicker"><?php krm_t( 'ministry.intro_eyebrow', 'site' ); ?></p>
		<h2 id="intro-heading"><?php krm_t( 'ministry.serves_heading', 'site' ); ?></h2>
		<?php if ( $krm_points ) : ?>
		<ul class="check-list" id="ministry-points">
			<?php foreach ( $krm_points as $krm_point ) : ?>
			<li><?php echo esc_html( $krm_point ); ?></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
		<?php if ( '' !== trim( $krm_focus ) ) : ?>
		<p class="ministry-focus" id="ministry-focus"><?php echo esc_html( $krm_focus ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php if ( $krm_m['sections'] ) : ?>
<div class="details ministry-extra">
	<?php foreach ( $krm_m['sections'] as $krm_section ) : ?>
	<section class="panel">
		<?php if ( '' !== $krm_section['heading'] ) : ?>
		<h2><?php echo esc_html( $krm_section['heading'] ); ?></h2>
		<?php endif; ?>
		<?php if ( '' !== $krm_section['text'] ) : ?>
		<p><?php echo esc_html( $krm_section['text'] ); ?></p>
		<?php endif; ?>
		<?php if ( $krm_section['points'] ) : ?>
		<ul class="check-list">
			<?php foreach ( $krm_section['points'] as $krm_point ) : ?>
			<li><?php echo esc_html( $krm_point ); ?></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</section>
	<?php endforeach; ?>
</div>
<?php endif; ?>
