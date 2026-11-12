<?php
/**
 * Home: 11. Latest News & Updates — the three newest posts: photo, date (and section), title, a short excerpt and "Read
 * More" (theme 1.23.0 added the excerpt). Hidden while there are none.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_posts = get_posts(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 3,
		'no_found_rows'  => true,
	)
);
if ( ! $krm_posts ) {
	return;
}
?>
<!-- From the field -->
<section id="news" aria-labelledby="krm-news-title" style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px)">
<div style="display: flex; flex-wrap: wrap; gap: 12px 48px; justify-content: space-between; align-items: flex-end; margin-bottom: 32px">
<div>
<p style="margin: 0 0 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--color-secondary-text)"><?php krm_t( 'news.eyebrow' ); ?></p>
<h2 id="krm-news-title" style="margin: 0; font-size: clamp(30px, 3.4vw, 40px); font-weight: 800; line-height: 1.15; color: var(--color-heading)"><?php krm_h( 'news.heading' ); ?></h2>
</div>
<?php if ( '' !== trim( (string) krm_c( 'news.all_label' ) ) ) : ?>
<a href="<?php krm_u( 'news.all_url' ); ?>" style="font-weight: 700; min-height: 44px; display: inline-flex; align-items: center"><?php krm_t( 'news.all_label' ); ?></a>
<?php endif; ?>
</div>
<div class="krm-news__grid">
<?php
foreach ( $krm_posts as $krm_i => $krm_post ) :
	$krm_cats = get_the_category( $krm_post->ID );
	$krm_meta = ( $krm_cats ? $krm_cats[0]->name . ' · ' : '' ) . get_the_date( '', $krm_post );
	$krm_ex   = wp_trim_words( wp_strip_all_tags( has_excerpt( $krm_post ) ? $krm_post->post_excerpt : strip_shortcodes( $krm_post->post_content ) ), 22, '…' );
	?>
<a class="k-card" href="<?php echo esc_url( get_permalink( $krm_post ) ); ?>" style="text-decoration: none; border-radius: 12px; overflow: hidden; border: 1px solid var(--color-border); display: flex; flex-direction: column">
<div style="position: relative; height: 190px; background: <?php echo esc_attr( $krm_i % 2 ? 'var(--color-placeholder-2)' : 'var(--color-placeholder)' ); ?>">
	<?php
	if ( has_post_thumbnail( $krm_post ) ) {
		echo get_the_post_thumbnail( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$krm_post,
			'medium_large',
			array(
				'alt'      => '',
				'loading'  => 'lazy',
				'decoding' => 'async',
				'sizes'    => '(max-width: 760px) 100vw, 400px',
				'style'    => 'position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover',
			)
		);
	}
	?>
</div>
<div style="padding: 20px; display: flex; flex: 1 1 auto; flex-direction: column; gap: 8px">
<span style="font-size: 13px; font-weight: 700; color: var(--color-secondary-text)"><time datetime="<?php echo esc_attr( get_the_date( 'c', $krm_post ) ); ?>"><?php echo esc_html( $krm_meta ); ?></time></span>
<strong style="font-size: 18px; line-height: 1.35; color: var(--color-text)"><?php echo esc_html( get_the_title( $krm_post ) ); ?></strong>
<?php if ( '' !== $krm_ex ) : ?>
<span style="font-size: 14px; line-height: 1.6; color: var(--color-text-muted)"><?php echo esc_html( $krm_ex ); ?></span>
<?php endif; ?>
<span class="krm-news__more"><?php krm_t( 'news.more_label' ); ?> <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span>
</div>
</a>
<?php endforeach; ?>
</div>
</section>
