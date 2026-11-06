<?php
/**
 * Template Name: Testimonies
 * Testimonies (/our-impact/testimonies/): every published Testimonial as a card in the home page's "Stories from
 * the field" style, with filter tabs by ministry (only ministries that have testimonies get a tab), then the "Share
 * your testimony" form (template-parts/testimony-form.php). While none is published (Phase 21): a short line instead
 * of the list; the page is always in the menu and the sitemap.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();

$krm_items = get_posts(
	array(
		'post_type'      => 'testimonial',
		'post_status'    => 'publish',
		'posts_per_page' => 200,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'no_found_rows'  => true,
	)
);
$krm_by_id = array();
foreach ( krm_ministries() as $krm_m ) {
	if ( $krm_m['id'] ) {
		$krm_by_id[ $krm_m['id'] ] = $krm_m;
	}
}
$krm_tabs = array();
foreach ( $krm_items as $krm_p ) {
	$krm_mid = krm_testimonial_ministry_id( $krm_p->ID );
	if ( isset( $krm_by_id[ $krm_mid ] ) ) {
		$krm_tabs[ $krm_by_id[ $krm_mid ]['slug'] ] = $krm_by_id[ $krm_mid ];
	}
}
uasort(
	$krm_tabs,
	function ( $a, $b ) {
		return $a['menu_order'] - $b['menu_order'];
	}
);
?>
	<main id="main">
	<?php krm_hero_preload( krm_c( 'hero.image' ) ); ?>
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( krm_c( 'hero.image' ) ) ); ?>')<?php krm_bg_small( krm_c( 'hero.image' ) ); ?>">
		<div class="wrap">
		<?php krm_breadcrumb_html(); ?>
		<p class="eyebrow"><?php krm_t( 'hero.eyebrow' ); ?></p>
		<h1><?php krm_h( 'hero.title' ); ?></h1>
		<p class="lead"><?php krm_t( 'hero.lead' ); ?></p>
		</div>
	</section>

	<section class="section section-soft" aria-labelledby="testimonies-heading">
		<div class="wrap">
		<h2 class="visually-hidden" id="testimonies-heading"><?php krm_t( 'hero.title' ); ?></h2>
		<?php if ( ! $krm_items ) : ?>
		<p class="testimony-empty"><?php krm_t( 'list.empty' ); ?></p>
		<?php else : ?>
			<?php if ( count( $krm_tabs ) > 1 ) : ?>
		<div class="ministry-filter krm-needs-js" role="group" aria-label="<?php echo esc_attr( krm_fill( krm_c( 'list.filter_label' ) ) ); ?>" data-krm-filter="testimonies" data-param="ministry">
			<button type="button" data-group="" aria-pressed="true"><?php krm_t( 'list.all_label' ); ?></button>
				<?php foreach ( $krm_tabs as $krm_slug => $krm_m ) : ?>
			<button type="button" data-group="<?php echo esc_attr( $krm_slug ); ?>" aria-pressed="false"><?php echo esc_html( $krm_m['title'] ); ?></button>
				<?php endforeach; ?>
		</div>
		<p class="visually-hidden" aria-live="polite" data-krm-filter-status="testimonies" data-text="<?php echo esc_attr( krm_fill( krm_c( 'list.count_text' ) ) ); ?>"></p>
			<?php endif; ?>
		<ul class="testimony-grid" data-krm-filter-list="testimonies" data-krm-more="18" data-more-text="<?php echo esc_attr( krm_fill( krm_c( 'list.more' ) ) ); ?>">
			<?php
			foreach ( $krm_items as $krm_p ) :
				$krm_mid  = krm_testimonial_ministry_id( $krm_p->ID );
				$krm_m    = isset( $krm_by_id[ $krm_mid ] ) ? $krm_by_id[ $krm_mid ] : null;
				$krm_role = (string) get_post_meta( $krm_p->ID, '_krm_role', true );
				$krm_img  = get_post_thumbnail_id( $krm_p );
				?>
			<li data-group="<?php echo esc_attr( $krm_m ? $krm_m['slug'] : '' ); ?>">
				<figure class="testimony">
				<?php if ( $krm_m ) : ?>
				<a class="tag" href="<?php echo esc_url( $krm_m['url'] ); ?>" style="text-decoration: none"><?php echo esc_html( $krm_m['title'] ); ?></a>
				<?php endif; ?>
				<svg width="34" height="34" viewBox="0 0 24 24" aria-hidden="true" style="fill: var(--color-orange)"><path d="M7 7h4v4c0 3-1.5 5-4 6l-.7-1.2C8 15 8.6 13.8 8.7 12H7zM14 7h4v4c0 3-1.5 5-4 6l-.7-1.2C15 15 15.6 13.8 15.7 12H14z"></path></svg>
				<blockquote><?php echo wp_kses_post( wpautop( $krm_p->post_content ) ); ?></blockquote>
				<figcaption>
					<?php if ( $krm_img ) : ?>
					<img src="<?php echo esc_url( (string) wp_get_attachment_image_url( $krm_img, 'thumbnail' ) ); ?>" alt="" width="48" height="48" loading="lazy" decoding="async">
					<?php else : ?>
					<span class="avatar" aria-hidden="true"></span>
					<?php endif; ?>
					<span><strong><?php echo esc_html( get_the_title( $krm_p ) ); ?></strong><?php if ( '' !== $krm_role ) : ?><small><?php echo esc_html( $krm_role ); ?></small><?php endif; ?></span>
				</figcaption>
				</figure>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
		</div>
	</section>
	<?php get_template_part( 'template-parts/testimony-form' ); ?>
	</main>
<?php
get_footer();
