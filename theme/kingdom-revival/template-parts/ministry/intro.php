<?php
/**
 * Ministry page 2: "How this ministry serves" (redesigned in theme 1.26.0 in the style of the home Who We Are
 * section): small red capitals and the serif heading with the ministry's focus text beside it as the lead; a
 * three-photo collage (one tall photo and two stacked, caption pills from the photos' descriptions, the orange
 * rotated tag with the ministry and its group); then the ministry's points as numbered cards. After it, the
 * ministry's extra sections as panels.
 *
 * The collage uses the 2nd, 3rd and 4th photos (the 1st is the hero); with fewer than four it uses the first three
 * (1 photo = one wide photo, none = no collage). Styles: .ministry-serves in assets/css/pages/ministry.css.
 *
 * @param array $args { ministry, photos }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_m      = $args['ministry'];
$krm_photos = $args['photos'];
$krm_n      = count( $krm_photos );
$krm_pick   = array_slice( $krm_photos, $krm_n >= 4 ? 1 : 0, 3 ); // Skip the hero photo when there are enough others.
$krm_focus  = $krm_m['id'] ? (string) get_post_meta( $krm_m['id'], '_krm_focus', true ) : '';
$krm_points = $krm_m['id'] ? krm_lines( (string) get_post_meta( $krm_m['id'], '_krm_points', true ) ) : array();
$krm_group  = trim( (string) ( $krm_m['group'] ?? '' ) );
// The tall photo is cropped from a wide one (object-fit: cover), so it needs about twice its box width.
$krm_sizes  = array( '(max-width: 600px) 120vw, (max-width: 1000px) 80vw, 720px', '(max-width: 600px) 50vw, (max-width: 1000px) 45vw, 360px', '(max-width: 600px) 50vw, (max-width: 1000px) 45vw, 360px' );
?>
<section class="ministry-intro ministry-serves" aria-labelledby="intro-heading">
	<div class="ministry-serves__head" data-reveal>
		<div>
			<p class="ministry-serves__kicker"><?php krm_t( 'ministry.intro_eyebrow', 'site' ); ?></p>
			<h2 id="intro-heading" class="ministry-serves__title"><?php krm_t( 'ministry.serves_heading', 'site' ); ?></h2>
		</div>
		<?php if ( '' !== trim( $krm_focus ) ) : ?>
		<p class="ministry-serves__lead" id="ministry-focus"><?php echo esc_html( $krm_focus ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( $krm_pick ) : ?>
	<div class="ministry-serves__collage ministry-serves__collage--<?php echo (int) count( $krm_pick ); ?>" data-reveal>
		<?php $krm_seen = array(); ?>
		<?php foreach ( $krm_pick as $krm_i => $krm_photo ) : ?>
		<figure class="ministry-serves__photo">
			<img <?php krm_img_src( $krm_photo[0], $krm_sizes[ $krm_i ] ); ?> alt="<?php echo esc_attr( $krm_photo[1] ); ?>" data-zoom="ministry-intro" loading="lazy" decoding="async">
			<?php
			$krm_cap = trim( (string) $krm_photo[1] );
			if ( '' !== $krm_cap && ! in_array( $krm_cap, $krm_seen, true ) ) : // The same description twice is shown once.
				$krm_seen[] = $krm_cap;
				?>
			<figcaption aria-hidden="true"><?php echo esc_html( $krm_cap ); ?></figcaption>
			<?php endif; ?>
		</figure>
		<?php endforeach; ?>
		<p class="ministry-serves__badge" aria-hidden="true"><strong><?php echo esc_html( $krm_m['title'] ); ?></strong><?php if ( '' !== $krm_group ) : ?><span><?php echo esc_html( $krm_group ); ?></span><?php endif; ?></p>
	</div>
	<?php endif; ?>

	<?php if ( $krm_points ) : ?>
	<ol class="ministry-serves__points" id="ministry-points" data-reveal-stagger>
		<?php foreach ( $krm_points as $krm_i => $krm_point ) : ?>
		<li class="ministry-serves__point">
			<span class="ministry-serves__num" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $krm_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
			<svg class="ministry-serves__check" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
			<p><?php echo esc_html( $krm_point ); ?></p>
		</li>
		<?php endforeach; ?>
	</ol>
	<?php endif; ?>
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
