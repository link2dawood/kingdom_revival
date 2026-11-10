<?php
/**
 * Home: "From the field — Latest news & blogs", the three newest posts. Hidden while there are none.
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
<section id="news" style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px) 0">
<div style="display: flex; flex-wrap: wrap; gap: 12px 48px; justify-content: space-between; align-items: flex-end; margin-bottom: 32px">
<div>
<p style="margin: 0 0 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--color-secondary-text)"><?php krm_t( 'news.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: clamp(30px, 3.4vw, 40px); font-weight: 800; line-height: 1.15; color: var(--color-heading)"><?php krm_h( 'news.heading' ); ?></h2>
</div>
<?php if ( '' !== trim( (string) krm_c( 'news.all_label' ) ) ) : ?>
<a href="<?php krm_u( 'news.all_url' ); ?>" style="font-weight: 700; min-height: 44px; display: inline-flex; align-items: center"><?php krm_t( 'news.all_label' ); ?></a>
<?php endif; ?>
</div>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 22px">
<?php
foreach ( $krm_posts as $krm_i => $krm_post ) :
	$krm_cats = get_the_category( $krm_post->ID );
	$krm_meta = ( $krm_cats ? $krm_cats[0]->name . ' · ' : '' ) . get_the_date( '', $krm_post );
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
<div style="padding: 20px; display: flex; flex-direction: column; gap: 8px">
<span style="font-size: 13px; font-weight: 700; color: var(--color-secondary-text)"><?php echo esc_html( $krm_meta ); ?></span>
<strong style="font-size: 18px; line-height: 1.35; color: var(--color-text)"><?php echo esc_html( get_the_title( $krm_post ) ); ?></strong>
<span style="font-size: 14px; color: var(--color-text-muted)"><?php krm_t( 'news.more_label' ); ?></span>
</div>
</a>
<?php endforeach; ?>
</div>
</section>
