<?php
/**
 * Our Impact: two link cards to its subpages, Testimonies and Photo & Video Gallery (Phase 21), above the closing
 * banner. The Testimonies card shows the opening of the first published testimony once there is one.
 *
 * Text: Pages → Our Impact → Page content → Testimonies and gallery links. Images: the two pages' hero images.
 * Styles: .impact-links in assets/css/pages/impact.css.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_quote = get_posts(
	array(
		'post_type'      => 'testimonial',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'no_found_rows'  => true,
	)
);
$krm_quote = $krm_quote ? $krm_quote[0] : null;
$krm_cards = array(
	array(
		'id'    => 'testimonies',
		'title' => krm_c( 'more.testimonies_title', 'impact' ),
		'text'  => krm_c( 'more.testimonies_text', 'impact' ),
		'image' => krm_c( 'hero.image', 'testimonies' ),
		'url'   => krm_page_url( 'testimonies' ),
	),
	array(
		'id'    => 'gallery',
		'title' => krm_c( 'more.gallery_title', 'impact' ),
		'text'  => krm_c( 'more.gallery_text', 'impact' ),
		'image' => krm_c( 'hero.image', 'gallery' ),
		'url'   => krm_page_url( 'gallery' ),
	),
);
?>
<section class="impact-section impact-links" aria-labelledby="impact-more-heading"><div class="wrap">
	<div class="impact-heading"><div><p class="eyebrow"><?php krm_t( 'more.eyebrow', 'impact' ); ?></p><h2 id="impact-more-heading"><?php krm_h( 'more.heading', 'impact' ); ?></h2></div></div>
	<ul class="impact-links__grid">
		<?php foreach ( $krm_cards as $krm_card ) : ?>
		<li>
			<article class="impact-link-card">
				<div class="impact-link-card__media">
					<?php if ( $krm_card['image'] ) : ?>
					<img <?php krm_img_src( $krm_card['image'], '(max-width: 760px) calc(100vw - 32px), 560px', true ); ?> alt="" loading="lazy" decoding="async">
					<?php endif; ?>
				</div>
				<div class="impact-link-card__body">
					<h3><a href="<?php echo esc_url( $krm_card['url'] ); ?>"><?php echo esc_html( krm_fill( $krm_card['title'] ) ); ?></a></h3>
					<?php if ( 'testimonies' === $krm_card['id'] && $krm_quote ) : ?>
					<blockquote class="impact-link-card__quote"><p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $krm_quote->post_content ), 26, '…' ) ); ?></p><cite><?php echo esc_html( get_the_title( $krm_quote ) ); ?></cite></blockquote>
					<?php elseif ( '' !== trim( (string) $krm_card['text'] ) ) : ?>
					<p><?php echo esc_html( krm_fill( $krm_card['text'] ) ); ?></p>
					<?php endif; ?>
					<span class="impact-link-card__more" aria-hidden="true"><?php krm_t( 'more.link', 'impact' ); ?></span>
				</div>
			</article>
		</li>
		<?php endforeach; ?>
	</ul>
</div></section>
