<?php
/**
 * Home: "Where we work" — places list and map with pins.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_map = krm_c( 'where.map' );
?>
<!-- Where we work -->
<section id="where" style="background: var(--color-bg-soft)">
<div style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px); display: flex; flex-wrap: wrap; gap: 40px 64px; align-items: center">
<div style="flex: 1 1 380px; min-width: 0">
<p style="margin: 0 0 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--color-secondary)"><?php krm_t( 'where.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: clamp(32px, 3.8vw, 46px); font-weight: 800; line-height: 1.12; color: var(--color-primary)"><?php krm_h( 'where.heading' ); ?></h2>
<div style="display: flex; flex-direction: column; margin-top: 28px">
<?php foreach ( krm_visible( krm_c( 'where.places' ), 'name' ) as $krm_place ) : // Visitors: no [District] rows; counts only once set. ?>
<div style="display: flex; justify-content: space-between; gap: 16px; padding: 14px 0; border-bottom: 1px solid #DDE3F0">
<span style="display: flex; gap: 10px; align-items: center; font-weight: 700"><span aria-hidden="true" style="width: 10px; height: 10px; border-radius: 50%; background: var(--color-secondary)"></span><?php echo krm_text( $krm_place['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<?php if ( '' !== trim( (string) $krm_place['count'] ) ) : ?>
<span style="color: var(--color-text-muted)"><?php echo krm_text( $krm_place['count'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
</div>
<div style="flex: 1.2 1 440px; min-width: 0; height: 440px; border-radius: 14px; overflow: hidden; background: #DDE3F0; position: relative">
<?php if ( $krm_map ) : ?>
<img loading="lazy" decoding="async" <?php krm_img_src( $krm_map, '(max-width: 960px) 100vw, 640px' ); ?> alt="<?php echo krm_alt( $krm_map, krm_c( 'where.map_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover">
<?php elseif ( '' !== trim( (string) krm_c( 'where.map_note' ) ) ) : ?>
<span style="position: absolute; left: 12px; top: 12px; font-size: 12px; color: var(--color-text); background: var(--color-white); padding: 3px 8px; border-radius: 4px"><?php krm_t( 'where.map_note' ); ?></span>
<?php endif; ?>
<?php
foreach ( krm_visible( krm_c( 'where.pins' ), 'label' ) as $krm_i => $krm_pin ) :
	$krm_left = (float) $krm_pin['left'];
	$krm_top  = (float) $krm_pin['top'];
	$krm_size = $krm_i ? 12 : 16;
	?>
<span aria-hidden="true" style="position: absolute; left: <?php echo esc_attr( $krm_left ); ?>%; top: <?php echo esc_attr( $krm_top ); ?>%; width: <?php echo (int) $krm_size; ?>px; height: <?php echo (int) $krm_size; ?>px; border-radius: 50%; background: <?php echo $krm_i ? '#E18426' : 'var(--color-secondary)'; ?>; border: 3px solid var(--color-white)"></span>
<span style="position: absolute; left: calc(<?php echo esc_attr( $krm_left ); ?>% + <?php echo $krm_i ? 18 : 22; ?>px); top: calc(<?php echo esc_attr( $krm_top ); ?>% - <?php echo $krm_i ? 3 : 2; ?>px); font-size: 13px; font-weight: 700; color: var(--color-primary)"><?php echo krm_text( $krm_pin['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<?php endforeach; ?>
</div>
</div>
</section>
