<?php
/**
 * Template Name: Partner With Us
 * Partner With Us (/partner-with-us/), per the client's sitemap: hero, five ways to partner (Prayer Partners,
 * Financial Partner, Church & Ministry Partners, Volunteer With Us, Prayer Request), one section per partner type
 * with what it involves and a sign-up form, the FAQ and the closing verse.
 *
 * Text: the page's "Page content" box (inc/content/partner.php). Forms: template-parts/signup-form.php.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();

$krm_types = array(
	'prayer'    => array(
		'id'     => 'prayer-partners',
		'icon'   => 'pray24',
		'church' => false,
	),
	'financial' => array(
		'id'     => 'financial-partner',
		'icon'   => 'heart24',
		'church' => false,
	),
	'church'    => array(
		'id'     => 'church-ministry-partners',
		'icon'   => 'church24',
		'church' => true,
	),
	'volunteer' => array(
		'id'     => 'volunteer',
		'icon'   => 'people24',
		'church' => false,
	),
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
		<div class="hero-actions">
			<a class="button button-primary" href="<?php krm_u( 'hero.button1_url' ); ?>"><?php krm_t( 'hero.button1_label' ); ?></a>
			<a class="button button-light" href="<?php krm_u( 'hero.button2_url' ); ?>"><?php krm_t( 'hero.button2_label' ); ?></a>
		</div>
		</div>
	</section>

	<section class="section section-soft" id="ways" aria-labelledby="ways-heading">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow"><?php krm_t( 'ways.eyebrow' ); ?></p>
			<h2 id="ways-heading"><?php krm_h( 'ways.heading' ); ?></h2>
			<p><?php krm_t( 'ways.intro' ); ?></p>
		</div>
		<ul class="work-grid is-3" style="margin: 0; padding: 0; list-style: none">
			<?php foreach ( $krm_types as $krm_key => $krm_type ) : ?>
			<li style="display: flex"><a class="work-card" href="#<?php echo esc_attr( $krm_type['id'] ); ?>" style="flex: 1">
				<span class="work-icon"><?php echo krm_icon( $krm_type['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme SVG file. ?></span>
				<h3><?php krm_t( $krm_key . '.title' ); ?></h3>
				<p><?php krm_t( $krm_key . '.text' ); ?></p>
				<span class="card-more" aria-hidden="true"><?php krm_t( 'ways.more' ); ?></span>
			</a></li>
			<?php endforeach; ?>
			<li style="display: flex"><a class="work-card" href="<?php echo esc_url( krm_page_url( 'prayer' ) ); ?>" style="flex: 1">
				<span class="work-icon"><?php echo krm_icon( 'globe24' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<h3><?php krm_t( 'ways.prayer_request' ); ?></h3>
				<p><?php krm_t( 'ways.prayer_text' ); ?></p>
				<span class="card-more" aria-hidden="true"><?php krm_t( 'ways.more' ); ?></span>
			</a></li>
		</ul>
		</div>
	</section>

	<section class="section">
		<div class="wrap">
		<?php foreach ( $krm_types as $krm_key => $krm_type ) : ?>
		<article class="partner-type" id="<?php echo esc_attr( $krm_type['id'] ); ?>" aria-labelledby="<?php echo esc_attr( $krm_type['id'] ); ?>-title">
			<div>
			<h2 id="<?php echo esc_attr( $krm_type['id'] ); ?>-title"><?php krm_t( $krm_key . '.title' ); ?></h2>
			<p class="lead-text"><?php krm_t( $krm_key . '.text' ); ?></p>
			<h3><?php krm_t( $krm_key . '.involves' ); ?></h3>
			<ul class="check-list">
				<?php foreach ( krm_lines( krm_c( $krm_key . '.points' ) ) as $krm_point ) : ?>
				<li><?php echo krm_text( $krm_point ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
				<?php endforeach; ?>
			</ul>
			<?php if ( 'financial' === $krm_key ) : ?>
			<div class="hero-actions"><a class="button button-primary" href="<?php krm_u( 'financial.button_url' ); ?>"><?php krm_t( 'financial.button_label' ); ?></a></div>
			<?php endif; ?>
			</div>
			<?php
			get_template_part(
				'template-parts/signup-form',
				null,
				array(
					'id'      => $krm_type['id'] . '-form',
					'type'    => wp_strip_all_tags( krm_fill( krm_c( $krm_key . '.title' ) ) ) . ( 'financial' === $krm_key ? ' (pledge)' : '' ),
					'heading' => krm_fill( krm_c( $krm_key . '.form_heading' ) ),
					'button'  => krm_fill( krm_c( $krm_key . '.button' ) ),
					'church'  => $krm_type['church'],
					'project' => 'financial' === $krm_key,
				)
			);
			?>
		</article>
		<?php endforeach; ?>
		<p class="form-foot" style="margin-top: 32px"><?php krm_t( 'form.foot' ); ?></p>
		</div>
	</section>

	<section class="section section-soft">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow"><?php krm_t( 'faq.eyebrow' ); ?></p>
			<h2><?php krm_h( 'faq.heading' ); ?></h2>
		</div>
		<?php get_template_part( 'template-parts/faq' ); ?>
		</div>
	</section>

	<section class="verse-band" style="background-image: url('<?php echo esc_url( krm_ref_url( krm_c( 'verse.image' ) ) ); ?>')">
		<div class="wrap">
		<blockquote><?php krm_t( 'verse.quote' ); ?><cite><?php krm_t( 'verse.cite' ); ?></cite></blockquote>
		<div class="hero-actions"><a class="button button-primary" href="<?php krm_u( 'verse.button_url' ); ?>"><?php krm_t( 'verse.button_label' ); ?></a></div>
		</div>
	</section>
	</main>
<?php
get_footer();
