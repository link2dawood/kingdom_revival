<?php
/**
 * A sermon or event (single-sermon.php, single-event.php), built from the story page layout:
 * hero, article with a details panel, and the giving sidebar.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_id      = get_the_ID();
$krm_is_evt  = 'event' === get_post_type();
$krm_key     = $krm_is_evt ? 'events' : 'sermons';
$krm_image   = get_post_thumbnail_id();
$krm_image   = $krm_image ? $krm_image : krm_c( 'types.image', 'site' );
$krm_archive = get_post_type_archive_link( get_post_type() );
$krm_label   = krm_fill( krm_c( 'types.' . $krm_key . '_heading', 'site' ) );

if ( $krm_is_evt ) {
	$krm_tag = krm_c( krm_event_is_past( $krm_id ) ? 'types.events_past_tag' : 'types.events_tag', 'site' );
} else {
	$krm_series = get_the_terms( $krm_id, 'sermon_series' );
	$krm_tag    = $krm_series && ! is_wp_error( $krm_series ) ? $krm_series[0]->name : krm_c( 'types.sermons_tag', 'site' );
}
?>
	<main id="main">
	<?php krm_hero_preload( $krm_image ); ?>
	<section class="post-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( $krm_image ) ); ?>')<?php krm_bg_small( $krm_image ); ?>">
		<div class="wrap">
		<?php krm_breadcrumb_html(); ?>
		<?php if ( $krm_tag ) : ?>
		<span class="tag"><?php echo krm_text( $krm_tag ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<?php endif; ?>
		<h1><?php the_title(); ?></h1>
		<div class="post-meta">
			<?php if ( $krm_is_evt ) : ?>
			<span><?php echo krm_icon( 'cal' ) . esc_html( krm_event_when( $krm_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<?php else : ?>
			<span><?php echo krm_icon( 'cal' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></time></span>
				<?php if ( get_post_meta( $krm_id, '_krm_speaker', true ) ) : ?>
			<span><?php echo esc_html( get_post_meta( $krm_id, '_krm_speaker', true ) ); ?></span>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		</div>
	</section>

	<div class="wrap post-layout">
		<article class="article" id="article">
		<?php
		if ( $krm_is_evt ) :
			$krm_venue   = (string) get_post_meta( $krm_id, '_krm_event_venue', true );
			$krm_address = (string) get_post_meta( $krm_id, '_krm_event_address', true );
			$krm_link    = (string) get_post_meta( $krm_id, '_krm_event_link', true );
			$krm_button  = (string) get_post_meta( $krm_id, '_krm_event_button', true );
			?>
		<dl class="type-details">
			<div><dt><?php esc_html_e( 'When', 'kingdom-revival' ); ?></dt><dd><?php echo esc_html( krm_event_when( $krm_id ) ); ?></dd></div>
			<?php if ( $krm_venue || $krm_address ) : ?>
			<div><dt><?php esc_html_e( 'Where', 'kingdom-revival' ); ?></dt><dd><?php echo esc_html( $krm_venue ); ?><?php echo $krm_venue && $krm_address ? '<br>' : ''; ?><?php echo esc_html( $krm_address ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $krm_link && ! krm_event_is_past( $krm_id ) ) : ?>
			<div class="type-details-action"><a class="button button-primary" href="<?php echo esc_url( $krm_link ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $krm_button ? $krm_button : __( 'Register', 'kingdom-revival' ) ); ?></a></div>
			<?php endif; ?>
		</dl>
			<?php
		else :
			$krm_scripture = (string) get_post_meta( $krm_id, '_krm_scripture', true );
			$krm_video     = (string) get_post_meta( $krm_id, '_krm_video', true );
			$krm_audio     = (string) get_post_meta( $krm_id, '_krm_audio', true );
			$krm_embed     = krm_sermon_video( $krm_id )['html'];
			if ( $krm_embed ) :
				?>
		<div class="type-video"><?php echo $krm_embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- oEmbed markup from a WordPress-approved provider. ?></div>
			<?php elseif ( $krm_video ) : ?>
		<p><a class="button button-primary" href="<?php echo esc_url( $krm_video ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Watch the sermon', 'kingdom-revival' ); ?></a></p>
			<?php endif; ?>
			<?php if ( $krm_audio ) : ?>
		<div class="type-audio"><?php echo wp_audio_shortcode( array( 'src' => esc_url_raw( $krm_audio ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core media markup. ?></div>
			<?php endif; ?>
			<?php if ( $krm_scripture ) : ?>
		<p class="type-scripture"><strong><?php esc_html_e( 'Bible passage:', 'kingdom-revival' ); ?></strong> <?php echo esc_html( $krm_scripture ); ?></p>
			<?php endif; ?>
		<?php endif; ?>
		<div data-gallery><?php the_content(); ?></div>
		<div class="article-footer">
			<a class="button button-secondary" href="<?php echo esc_url( $krm_archive ); ?>">← <?php echo esc_html( $krm_label ); ?></a>
		</div>
		</article>

		<aside class="sidebar" aria-label="<?php esc_attr_e( 'Support the ministry', 'kingdom-revival' ); ?>">
		<form class="give-card" data-short="true" data-give="Kingdom Revival Ministries" data-heading="Give to the mission"></form>
		<a class="ministry-card" href="<?php krm_u( 'story.side_pray_url', 'site' ); ?>">
			<?php krm_logo_img( 64, '52px', '', ' style="object-fit: contain; background: var(--soft); padding: 6px" loading="lazy"' ); ?>
			<span><small><?php krm_t( 'story.side_pray_label', 'site' ); ?></small><strong><?php krm_t( 'story.side_pray_title', 'site' ); ?></strong><em><?php krm_t( 'story.side_pray_link', 'site' ); ?></em></span>
		</a>
		</aside>
	</div>
	</main>
