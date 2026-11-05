<?php
/**
 * Home: the ministry explorer (every published ministry whose explorer position is not 0). The list switches the panel via assets/js/front.js.
 *
 * @param array $args { ministries: ministries in home order, from krm_ministries( 'home' ). }
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_list   = isset( $args['ministries'] ) ? (array) $args['ministries'] : krm_ministries( 'home' );
$krm_active = $krm_list ? $krm_list[0] : array(
	'image' => '',
	'title' => '',
	'desc'  => '',
	'url'   => '',
	'slug'  => '',
);
?>
<!-- Ministry explorer -->
<section id="all-ministries" style="max-width: 1240px; margin: 0 auto; padding: clamp(24px, 3vw, 40px) clamp(16px, 3vw, 32px) clamp(72px, 8vw, 110px)">
<div style="display: flex; flex-wrap: wrap; gap: 20px 64px; justify-content: space-between; align-items: flex-end; margin-bottom: 44px">
<div style="flex: 1 1 520px; min-width: 0">
<p style="margin: 0 0 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--color-secondary-text)"><?php krm_t( 'explorer.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: clamp(36px, 4.6vw, 56px); font-weight: 800; line-height: 1.08; letter-spacing: -0.025em; color: var(--color-heading)"><?php krm_h( 'explorer.heading' ); ?></h2>
</div>
<p style="margin: 0; flex: 0 1 380px; color: var(--color-text-muted)"><?php krm_t( 'explorer.intro' ); ?></p>
</div>
<div style="display: flex; flex-wrap: wrap; gap: 28px">
<?php krm_explorer_data( $krm_list ); ?>
<div role="group" aria-label="Ministries" style="flex: 1 1 280px; max-width: 100%; display: flex; flex-direction: column; gap: 8px">
<?php
foreach ( $krm_list as $krm_i => $krm_m ) :
	$krm_on = 0 === $krm_i;
	?>
<button type="button" data-index="<?php echo esc_attr( $krm_i ); ?>" aria-pressed="<?php echo $krm_on ? 'true' : 'false'; ?>" style="font: 600 15px 'Plus Jakarta Sans', sans-serif; text-align: left; display: flex; gap: 18px; align-items: center; padding: 13px 18px; min-height: 46px; border: 0; border-radius: 8px; cursor: pointer; background: <?php echo $krm_on ? 'var(--color-band)' : 'var(--color-bg-soft)'; ?>; color: <?php echo $krm_on ? 'var(--color-white)' : 'var(--color-text)'; ?>"><span style="font-size: 13px; font-weight: 700; color: <?php echo $krm_on ? 'var(--color-accent)' : 'var(--color-secondary-text)'; ?>"><?php echo esc_html( sprintf( '%02d', $krm_i + 1 ) ); ?></span><?php echo esc_html( $krm_m['title'] ); ?></button>
<?php endforeach; ?>
</div>
<div style="flex: 999 1 560px; min-width: 0; display: flex; flex-wrap: wrap; border-radius: 20px; overflow: hidden; background: var(--color-band)">
<div style="position: relative; flex: 1 1 320px; min-height: 340px; background: var(--color-band-2); overflow: hidden">
<img data-krm-explorer="img" <?php krm_img_src( $krm_active['image'], '(max-width: 760px) 100vw, (max-width: 1240px) 66vw, 430px' ); ?> alt="<?php echo esc_attr( $krm_active['title'] ); ?>" data-zoom="explorer" loading="lazy" decoding="async" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center 20%">
</div>
<div style="flex: 1 1 320px; min-width: 0; padding: clamp(32px, 4vw, 48px) clamp(28px, 3.4vw, 40px); display: flex; flex-direction: column; justify-content: center; color: var(--color-white)">
<p style="margin: 0; font-size: clamp(56px, 6vw, 72px); font-weight: 800; line-height: 1; letter-spacing: -0.03em; color: var(--color-accent)" data-krm-explorer="num">01</p>
<h3 style="margin: 22px 0 0; font-size: clamp(26px, 2.6vw, 32px); font-weight: 800; line-height: 1.2" data-krm-explorer="title"><?php echo esc_html( $krm_active['title'] ); ?></h3>
<p style="margin: 16px 0 0; font-size: 16px; line-height: 1.7; color: var(--color-text-on-dark)" data-krm-explorer="desc"><?php echo esc_html( $krm_active['desc'] ); ?></p>
<a class="k-o" data-krm-explorer="support" href="<?php echo esc_url( add_query_arg( 'ministry', $krm_active['slug'], krm_donate_url() ) ); ?>#give" style="margin-top: 26px; align-self: flex-start; text-decoration: none; background: var(--color-orange); color: var(--color-on-accent); font-weight: 700; padding: 14px 24px; border-radius: 999px; min-height: 44px; box-sizing: border-box; display: inline-flex; align-items: center"><?php krm_t( 'explorer.support_label' ); ?></a>
<a data-krm-explorer="link" href="<?php echo esc_url( $krm_active['url'] ); ?>" style="margin-top: 12px; align-self: flex-start; display: inline-flex; align-items: center; justify-content: center; min-height: 44px; padding: 10px 24px; border: 2px solid var(--color-surface); border-radius: 999px; color: var(--color-white); font-weight: 700; text-decoration: none; box-sizing: border-box"><?php krm_t( 'explorer.more_label' ); ?></a>
</div>
</div>
</div>
<?php if ( '' !== trim( (string) krm_c( 'explorer.all_label' ) ) ) : ?>
<div style="margin-top: 40px; display: flex; justify-content: center">
<a class="k-g" href="<?php krm_u( 'explorer.all_url' ); ?>" style="display: inline-flex; gap: 10px; align-items: center; min-height: 48px; padding: 14px 28px; border-radius: 999px; background: var(--color-button-navy); color: var(--color-white); font-weight: 700; text-decoration: none; box-sizing: border-box"><?php krm_t( 'explorer.all_label' ); ?><?php echo krm_icon( 'arrow16' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
</div>
<?php endif; ?>
</section>
