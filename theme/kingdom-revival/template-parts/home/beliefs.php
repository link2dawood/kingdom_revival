<?php
/**
 * Home: "What we believe" statement of faith and "Our team".
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_eyebrow = 'margin: 0 0 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--color-secondary-text)';
$krm_heading = 'margin: 0; font-size: clamp(30px, 3.4vw, 40px); font-weight: 800; line-height: 1.15; color: var(--color-heading)';
$krm_team = krm_visible( krm_c( 'beliefs.team' ), 'name' ); // Visitors: only people with a real name (none: no team column).
$krm_none = ! array_filter(
	$krm_team,
	function ( $p ) {
		return ! krm_is_placeholder( $p['name'] );
	}
);
?>
<!-- Beliefs + Team -->
<section id="beliefs" style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px) 0; display: flex; flex-wrap: wrap; gap: 48px 64px">
<div style="flex: 1 1 400px; min-width: 0">
<p style="<?php echo esc_attr( $krm_eyebrow ); ?>"><?php krm_t( 'beliefs.eyebrow' ); ?></p>
<h2 style="<?php echo esc_attr( $krm_heading ); ?>"><?php krm_h( 'beliefs.heading' ); ?></h2>
<ul style="list-style: none; margin: 24px 0 0; padding: 0; display: flex; flex-direction: column; gap: 12px">
<?php foreach ( krm_lines( krm_c( 'beliefs.points' ) ) as $krm_line ) : ?>
<li style="display: flex; gap: 12px; align-items: flex-start"><span aria-hidden="true" style="flex: 0 0 22px; width: 22px; height: 22px; margin-top: 2px; border-radius: 50%; background: var(--color-band); display: flex; align-items: center; justify-content: center"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="3" style="stroke: var(--color-white)"><path d="M5 12l5 5L20 7"></path></svg></span><span><?php echo krm_text( $krm_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></li>
<?php endforeach; ?>
</ul>
<?php if ( '' !== trim( (string) krm_c( 'beliefs.link_label' ) ) ) : ?>
<a href="<?php krm_u( 'beliefs.link_url' ); ?>" style="margin-top: 20px; display: inline-flex; font-weight: 700; min-height: 44px; align-items: center"><?php krm_t( 'beliefs.link_label' ); ?></a>
<?php endif; ?>
</div>
<?php if ( $krm_team ) : ?>
<div id="team" style="flex: 1.2 1 440px; min-width: 0">
<?php krm_admin_only_note( $krm_none ); ?>
<p style="<?php echo esc_attr( $krm_eyebrow ); ?>"><?php krm_t( 'beliefs.team_eyebrow' ); ?></p>
<h2 style="<?php echo esc_attr( $krm_heading ); ?>"><?php krm_h( 'beliefs.team_heading' ); ?></h2>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 18px; margin-top: 24px">
<?php foreach ( $krm_team as $krm_person ) : ?>
<div>
<div style="position: relative; aspect-ratio: 1 / 1; border-radius: 12px; overflow: hidden; background: var(--color-placeholder)">
	<?php if ( $krm_person['photo'] ) : ?>
<img loading="lazy" decoding="async" <?php krm_img_src( $krm_person['photo'], '(max-width: 760px) 50vw, 240px' ); ?> alt="<?php echo krm_alt( $krm_person['photo'], $krm_person['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center 18%">
	<?php endif; ?>
</div>
<p style="margin: 10px 0 0; font-weight: 700"><?php echo krm_text( $krm_person['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<p style="margin: 0; font-size: 14px; color: var(--color-text-muted)"><?php echo krm_text( $krm_person['role'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
</div>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>
</section>
