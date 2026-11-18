<?php
/**
 * Sermon and event lists (archive-sermon.php, archive-event.php, taxonomy-sermon_series.php), built from the
 * News & Blogs page hero and story cards.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_type  = isset( $args['type'] ) ? $args['type'] : 'sermon';
$krm_key   = 'event' === $krm_type ? 'events' : 'sermons';
$krm_title = is_tax() ? single_term_title( '', false ) : krm_fill( krm_c( 'types.' . $krm_key . '_heading', 'site' ) );
$krm_lead  = is_tax() ? wp_strip_all_tags( term_description() ) : krm_fill( krm_c( 'types.' . $krm_key . '_intro', 'site' ) );

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'title' => $krm_title,
		'lead'  => $krm_lead,
		'image' => krm_c( 'types.image', 'site' ),
	)
);
?>
	<section class="section" style="padding-top: clamp(48px, 6vw, 72px)">
		<div class="wrap">
		<?php if ( have_posts() ) : ?>
		<div class="post-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				krm_type_card( get_post() );
			endwhile;
			?>
		</div>
			<?php krm_pagination(); ?>
		<?php else : ?>
		<div class="empty"><strong><?php echo krm_text( krm_c( 'types.' . $krm_key . '_empty', 'site' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong></div>
		<?php endif; ?>

		<?php
		if ( 'event' === $krm_type && ! is_paged() ) :
			$krm_past = get_posts(
				array(
					'post_type'      => 'event',
					'posts_per_page' => 6,
					'meta_key'       => '_krm_event_start', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
					'orderby'        => 'meta_value',
					'order'          => 'DESC',
					'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => '_krm_event_start',
							'value'   => krm_now(),
							'compare' => '<',
						),
						array(
							'relation' => 'OR',
							array(
								'key'     => '_krm_event_end',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_krm_event_end',
								'value'   => krm_now(),
								'compare' => '<',
							),
						),
					),
					'no_found_rows'  => true,
				)
			);
			if ( $krm_past ) :
				?>
		<div class="section-past">
			<h2><?php echo krm_text( krm_c( 'types.events_past_heading', 'site' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<div class="post-grid">
				<?php
				foreach ( $krm_past as $krm_post ) {
					krm_type_card( $krm_post );
				}
				?>
			</div>
		</div>
				<?php
			endif;
		endif;
		?>
		</div>
	</section>
