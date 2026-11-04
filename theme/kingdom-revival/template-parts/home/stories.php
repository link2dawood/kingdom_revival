<?php
/**
 * Home: "Lives changed — Stories from the field" quotes. Visitors see only stories with real words (none: no section).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_items = krm_visible( krm_c( 'stories.items' ), 'quote' ); // Visitors: only stories with real words.
if ( ! $krm_items ) {
	return;
}
$krm_none = ! array_filter(
	$krm_items,
	function ( $s ) {
		return ! krm_is_placeholder( $s['quote'] );
	}
);
?>
<!-- Stories -->
<section id="stories" style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px)">
<div style="text-align: center; max-width: 680px; margin: 0 auto 44px">
<?php krm_admin_only_note( $krm_none ); ?>
<p style="margin: 0 0 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--color-secondary)"><?php krm_t( 'stories.eyebrow' ); ?></p>
<h2 style="margin: 0; font-size: clamp(32px, 3.8vw, 46px); font-weight: 800; line-height: 1.12; color: var(--color-primary)"><?php krm_h( 'stories.heading' ); ?></h2>
</div>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 22px">
<?php foreach ( $krm_items as $krm_story ) : ?>
<figure style="margin: 0; border: 1px solid #E3E7F0; border-radius: 14px; padding: 28px; display: flex; flex-direction: column; gap: 18px">
<svg width="34" height="34" viewBox="0 0 24 24" fill="#E18426" aria-hidden="true"><path d="M7 7h4v4c0 3-1.5 5-4 6l-.7-1.2C8 15 8.6 13.8 8.7 12H7zM14 7h4v4c0 3-1.5 5-4 6l-.7-1.2C15 15 15.6 13.8 15.7 12H14z"></path></svg>
<blockquote style="margin: 0; font-size: 17px; line-height: 1.6; color: var(--color-text)"><?php echo krm_text( $krm_story['quote'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></blockquote>
<figcaption style="margin-top: auto; display: flex; gap: 12px; align-items: center">
	<?php if ( $krm_story['photo'] ) : ?>
<img loading="lazy" decoding="async" src="<?php echo esc_url( krm_ref_url( $krm_story['photo'], 'thumbnail' ) ); ?>" alt="" width="48" height="48" style="flex: 0 0 48px; width: 48px; height: 48px; border-radius: 50%; object-fit: cover">
	<?php else : ?>
<span aria-hidden="true" style="flex: 0 0 48px; width: 48px; height: 48px; border-radius: 50%; background: #DDE3F0"></span>
	<?php endif; ?>
<span style="display: flex; flex-direction: column; line-height: 1.3"><strong><?php echo krm_text( $krm_story['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong><span style="font-size: 14px; color: var(--color-text-muted)"><?php echo krm_text( $krm_story['place'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></span>
</figcaption>
</figure>
<?php endforeach; ?>
</div>
</section>
