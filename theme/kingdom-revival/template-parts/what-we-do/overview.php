<?php
/**
 * /what-we-do/ — the overview (Phase 21): inner hero; one large card per ministry group (photo under a navy overlay,
 * group name, intro, number of live ministries, "Explore →" to the group page); then a preview row of each group's
 * ministries. The closing banner is added by archive-ministry.php.
 *
 * Styles: .wwd-* in assets/css/pages/ministry.css.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_hero   = krm_c( 'ministries.image', 'site' );
$krm_groups = krm_groups();
?>
<?php krm_hero_preload( $krm_hero ); ?>
<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( $krm_hero ) ); ?>')<?php krm_bg_small( $krm_hero ); ?>">
	<div class="wrap">
	<?php krm_breadcrumb_html(); ?>
	<?php if ( '' !== trim( (string) krm_c( 'ministries.eyebrow', 'site' ) ) ) : ?>
	<p class="eyebrow"><?php krm_t( 'ministries.eyebrow', 'site' ); ?></p>
	<?php endif; ?>
	<h1><?php krm_h( 'ministries.heading', 'site' ); ?></h1>
	<?php if ( '' !== trim( (string) krm_c( 'ministries.subheading', 'site' ) ) ) : ?>
	<p class="hero-sub"><?php krm_h( 'ministries.subheading', 'site' ); ?></p>
	<?php endif; ?>
	<p class="lead"><?php krm_t( 'ministries.lead', 'site' ); ?></p>
	</div>
</section>

<section class="section wwd-groups" aria-labelledby="wwd-groups-heading">
	<div class="wrap">
	<h2 class="visually-hidden" id="wwd-groups-heading"><?php krm_t( 'ministries.list_heading', 'site' ); ?></h2>
	<?php if ( $krm_groups ) : ?>
	<ul class="wwd-group-grid">
		<?php foreach ( $krm_groups as $krm_g ) : ?>
		<li><?php get_template_part( 'template-parts/what-we-do/group-card', null, array( 'group' => $krm_g ) ); ?></li>
		<?php endforeach; ?>
	</ul>
	<?php else : ?>
	<p class="empty-gallery"><?php krm_t( 'ministries.empty', 'site' ); ?></p>
	<?php endif; ?>
	</div>
</section>

<?php if ( $krm_groups ) : ?>
<section class="section section-soft wwd-previews" aria-label="<?php echo esc_attr( wp_strip_all_tags( krm_fill( krm_c( 'ministries.heading', 'site' ) ) ) ); ?>">
	<div class="wrap">
	<?php foreach ( $krm_groups as $krm_g ) : ?>
	<div class="wwd-preview">
		<div class="ministry-head">
			<h2 id="<?php echo esc_attr( 'wwd-preview-' . $krm_g['slug'] ); ?>"><a href="<?php echo esc_url( $krm_g['url'] ); ?>"><?php echo esc_html( $krm_g['name'] ); ?></a></h2>
			<p><a href="<?php echo esc_url( $krm_g['url'] ); ?>"><?php krm_t( 'ministries.preview_more', 'site' ); ?><span class="visually-hidden"> <?php echo esc_html( $krm_g['name'] ); ?></span></a></p>
		</div>
		<ul class="wwd-preview-row" aria-labelledby="<?php echo esc_attr( 'wwd-preview-' . $krm_g['slug'] ); ?>">
			<?php foreach ( $krm_g['ministries'] as $krm_m ) : ?>
			<li>
				<a class="wwd-mini" href="<?php echo esc_url( $krm_m['url'] ); ?>">
					<span class="wwd-mini-media">
					<?php if ( $krm_m['image'] ) : ?>
					<img <?php krm_img_src( $krm_m['image'], '(max-width: 560px) 45vw, 200px', true ); ?> alt="" width="200" height="130" loading="lazy" decoding="async">
					<?php endif; ?>
					</span>
					<span class="wwd-mini-title"><?php echo esc_html( $krm_m['title'] ); ?></span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>
