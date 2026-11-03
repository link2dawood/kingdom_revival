<?php
/**
 * /ministries/ — All Ministries: the inner-page hero and every published ministry as a card (the home page's
 * giving-card design), in menu order. Text is edited in Kingdom Revival → Site text → All Ministries page.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();

$krm_hero = krm_c( 'ministries.image', 'site' );
$krm_list = krm_ministries();
$krm_more = krm_fill( krm_c( 'ministries.card_button', 'site' ) );
?>
	<main id="main">
	<?php krm_hero_preload( $krm_hero ); ?>
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( $krm_hero ) ); ?>')<?php krm_bg_small( $krm_hero ); ?>">
		<div class="wrap">
		<?php krm_breadcrumb_html(); ?>
		<p class="eyebrow"><?php krm_t( 'ministries.eyebrow', 'site' ); ?></p>
		<h1><?php krm_h( 'ministries.heading', 'site' ); ?></h1>
		<p class="lead"><?php krm_t( 'ministries.lead', 'site' ); ?></p>
		</div>
	</section>

	<section class="section section-soft" aria-labelledby="ministries-heading">
		<div class="wrap">
		<h2 class="visually-hidden" id="ministries-heading"><?php krm_t( 'ministries.list_heading', 'site' ); ?></h2>
		<?php if ( $krm_list ) : ?>
		<ul class="ministries-grid">
			<?php foreach ( $krm_list as $krm_m ) : ?>
			<li class="ministries-card">
				<a class="ministries-card-media" href="<?php echo esc_url( $krm_m['url'] ); ?>" tabindex="-1" aria-hidden="true">
					<?php if ( $krm_m['image'] ) : ?>
					<img <?php krm_img_src( $krm_m['image'], '(max-width: 639px) calc(100vw - 32px), (max-width: 999px) 50vw, 400px', true ); ?> alt="" width="400" height="210" loading="lazy" decoding="async">
					<?php endif; ?>
					<?php if ( $krm_m['group'] ) : ?>
					<span class="ministries-card-tag"><?php echo esc_html( $krm_m['group'] ); ?></span>
					<?php endif; ?>
				</a>
				<div class="ministries-card-body">
					<h3><a href="<?php echo esc_url( $krm_m['url'] ); ?>"><?php echo esc_html( $krm_m['title'] ); ?></a></h3>
					<p><?php echo esc_html( $krm_m['desc'] ); ?></p>
					<a class="ministries-card-button" href="<?php echo esc_url( $krm_m['url'] ); ?>"><?php echo esc_html( $krm_more ); ?><span class="visually-hidden"> <?php echo esc_html( sprintf( /* translators: %s: ministry name */ __( 'about %s', 'kingdom-revival' ), $krm_m['title'] ) ); ?></span><?php echo krm_icon( 'arrow16' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php else : ?>
		<p class="empty-gallery"><?php krm_t( 'ministries.empty', 'site' ); ?></p>
		<?php endif; ?>
		</div>
	</section>
	</main>
<?php
get_footer();
