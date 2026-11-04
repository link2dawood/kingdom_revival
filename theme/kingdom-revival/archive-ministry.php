<?php
/**
 * /what-we-do/ — What We Do: the inner-page hero, filter tabs by ministry group (All · Gospel & Spiritual ·
 * Compassion & Humanitarian), every published ministry as a giving card (template-parts/ministry-card.php), then the home page's "Your gift at work", "Three Ways to Stand
 * With Us" and closing verse sections.
 *
 * Text: Kingdom Revival → Site text → What We Do page. Cards: each ministry's own fields (krm_ministries()).
 * The three closing sections read the home page's content, so they are edited once, in Pages → Home.
 * The tabs are buttons that assets/js/components.js reveals and drives; without JavaScript every card shows.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();

$krm_hero   = krm_c( 'ministries.image', 'site' );
$krm_list   = krm_ministries();
$krm_groups = krm_ministry_groups( $krm_list );
?>
	<main id="main">
	<?php krm_hero_preload( $krm_hero ); ?>
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( $krm_hero ) ); ?>')<?php krm_bg_small( $krm_hero ); ?>">
		<div class="wrap">
		<?php krm_breadcrumb_html(); ?>
		<p class="eyebrow"><?php krm_t( 'ministries.eyebrow', 'site' ); ?></p>
		<h1><?php krm_h( 'ministries.heading', 'site' ); ?></h1>
		<?php if ( '' !== trim( (string) krm_c( 'ministries.subheading', 'site' ) ) ) : ?>
		<p class="hero-sub"><?php krm_h( 'ministries.subheading', 'site' ); ?></p>
		<?php endif; ?>
		<p class="lead"><?php krm_t( 'ministries.lead', 'site' ); ?></p>
		</div>
	</section>

	<section class="krm-band krm-band-soft" aria-labelledby="ministries-heading">
		<div class="krm-container">
		<h2 class="visually-hidden" id="ministries-heading"><?php krm_t( 'ministries.list_heading', 'site' ); ?></h2>
		<?php if ( $krm_list ) : ?>
			<?php if ( count( $krm_groups ) > 1 ) : ?>
		<div class="ministry-filter" role="group" aria-label="<?php echo esc_attr( krm_fill( krm_c( 'ministries.filter_label', 'site' ) ) ); ?>" data-krm-filter="ministries" data-param="group" hidden>
			<button type="button" data-group="" aria-pressed="true"><?php krm_t( 'ministries.all_label', 'site' ); ?></button>
				<?php foreach ( $krm_groups as $krm_slug => $krm_name ) : ?>
			<button type="button" data-group="<?php echo esc_attr( $krm_slug ); ?>" aria-pressed="false"><?php echo esc_html( $krm_name ); ?></button>
				<?php endforeach; ?>
		</div>
		<p class="visually-hidden" aria-live="polite" data-krm-filter-status="ministries" data-text="<?php echo esc_attr( krm_fill( krm_c( 'ministries.count_text', 'site' ) ) ); ?>"></p>
			<?php endif; ?>
		<ul class="ministry-grid" data-krm-filter-list="ministries">
			<?php foreach ( $krm_list as $krm_i => $krm_m ) : ?>
			<li data-group="<?php echo esc_attr( $krm_m['group_slug'] ); ?>">
				<?php
				get_template_part(
					'template-parts/ministry-card',
					null,
					array(
						'ministry' => $krm_m,
						'index'    => $krm_i,
					)
				);
				?>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php else : ?>
		<p class="empty-gallery"><?php krm_t( 'ministries.empty', 'site' ); ?></p>
		<?php endif; ?>
		</div>
	</section>

	<div class="krm-design">
	<?php
	foreach ( array( 'gift', 'get-involved', 'cta' ) as $krm_part ) {
		get_template_part( 'template-parts/home/' . $krm_part, null, array( 'screen' => 'home' ) );
	}
	?>
	</div>
	</main>
<?php
get_footer();
