<?php
/**
 * Single ministry — markup from all-ministries.html, rendered on the server.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$krm_title   = get_the_title();
	$krm_focus   = (string) get_post_meta( get_the_ID(), '_krm_focus', true );
	$krm_points  = array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( get_the_ID(), '_krm_points', true ) ) ) );
	$krm_gallery = array_values( array_filter( (array) get_post_meta( get_the_ID(), '_krm_gallery', true ) ) );
	$krm_alts    = (array) get_post_meta( get_the_ID(), '_krm_photo_alts', true );
	$krm_hero    = $krm_gallery ? krm_ref_url( $krm_gallery[0] ) : krm_ref_url( krm_c( 'ministry.hero_image', 'site' ) );
	?>
	<main id="main">
	<div id="ministry-content">
		<section class="page-hero" id="ministry-hero" style="background-image: url('<?php echo esc_url( $krm_hero ); ?>')">
		<div class="wrap">
			<ol class="breadcrumb"><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li><li><a href="<?php echo esc_url( home_url( '/#all-ministries' ) ); ?>">Ministries</a></li><li aria-current="page" id="crumb-title"><?php echo esc_html( $krm_title ); ?></li></ol>
			<p class="eyebrow"><?php krm_t( 'ministry.eyebrow', 'site' ); ?></p>
			<h1 id="ministry-title"><?php echo esc_html( $krm_title ); ?></h1>
			<p class="lead" id="ministry-description"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<div class="hero-actions">
			<a class="button button-primary" href="#support"><?php echo krm_icon( 'heart18' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php krm_t( 'ministry.donate_button', 'site' ); ?></a>
			</div>
		</div>
		</section>

		<div class="wrap section">
		<div class="details">
			<section class="panel">
			<h2><?php krm_t( 'ministry.focus_heading', 'site' ); ?></h2>
			<p id="ministry-focus"><?php echo esc_html( $krm_focus ); ?></p>
			</section>
			<section class="panel">
			<h2><?php krm_t( 'ministry.serves_heading', 'site' ); ?></h2>
			<ul class="focus-list" id="ministry-points">
			<?php
			foreach ( $krm_points as $krm_point ) :
				?>
				<li><?php echo krm_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $krm_point ); ?></span></li><?php endforeach; ?></ul>
			</section>
		</div>

		<section aria-labelledby="gallery-heading">
			<div class="gallery-head">
			<h2 id="gallery-heading"><?php echo krm_site_ministry_text( 'ministry.photos_heading', $krm_title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in krm_site_ministry_text(). ?></h2>
			<p id="gallery-tip"<?php echo $krm_gallery ? '' : ' hidden'; ?>><?php echo krm_icon( 'zoom' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php krm_t( 'ministry.photos_tip', 'site' ); ?></p>
			</div>
			<?php if ( $krm_gallery ) : ?>
			<div class="gallery" id="ministry-gallery" data-gallery>
				<?php
				foreach ( $krm_gallery as $krm_i => $krm_ref ) :
					$krm_alt = ! empty( $krm_alts[ (string) $krm_ref ] ) ? $krm_alts[ (string) $krm_ref ] : krm_ref_alt( $krm_ref, $krm_title );
					?>
			<figure class="photo"><img <?php krm_img_src( $krm_ref, '(max-width: 640px) 100vw, (max-width: 1000px) 50vw, 400px' ); ?> alt="<?php echo esc_attr( $krm_alt ); ?>" loading="<?php echo $krm_i < 3 ? 'eager' : 'lazy'; ?>" decoding="async"><figcaption><?php echo esc_html( $krm_alt ); ?></figcaption></figure>
				<?php endforeach; ?>
			</div>
			<?php else : ?>
			<p class="empty-gallery"><?php krm_t( 'ministry.photos_empty', 'site' ); ?></p>
			<?php endif; ?>
		</section>

		<div class="give-wrap">
			<?php
			get_template_part(
				'template-parts/give-band',
				null,
				krm_ministry_give_band_args(
					'ministry_give',
					$krm_title,
					array(
						'id'         => 'support',
						'heading_id' => 'support-heading',
						'text_id'    => 'support-text',
						'form'       => array(
							'id'   => 'support-form',
							'give' => $krm_title,
						),
					)
				)
			);
			?>
		</div>
		</div>
	</div>
	</main>
	<?php
endwhile;

get_footer();
