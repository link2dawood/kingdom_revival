<?php
/**
 * Give band: a heading, short text and check list beside a give card (built by assets/js/site.js).
 * Used on the about page, News & Blogs, stories and ministries.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_args = wp_parse_args(
	$args,
	array(
		'id'         => 'give',
		'heading_id' => 'give-heading',
		'image'      => '',
		'eyebrow'    => '',
		'heading'    => '',
		'text'       => '',
		'text_id'    => '',
		'points'     => array(),
		'form'       => array(),
	)
);
$krm_form = wp_parse_args(
	$krm_args['form'],
	array(
		'id'      => '',
		'give'    => 'Kingdom Revival Ministries',
		'short'   => false,
		'heading' => '',
		'intro'   => '',
	)
);
?>
<section class="give-band" id="<?php echo esc_attr( $krm_args['id'] ); ?>"<?php if ( $krm_args['image'] ) : ?> style="background-image: url('<?php echo esc_url( krm_ref_url( $krm_args['image'] ) ); ?>')<?php krm_bg_small( $krm_args['image'] ); ?>"<?php endif; ?> aria-labelledby="<?php echo esc_attr( $krm_args['heading_id'] ); ?>">
	<div>
		<?php if ( $krm_args['eyebrow'] ) : ?>
		<p class="eyebrow"><?php echo esc_html( $krm_args['eyebrow'] ); ?></p>
		<?php endif; ?>
		<h2 id="<?php echo esc_attr( $krm_args['heading_id'] ); ?>"><?php echo esc_html( $krm_args['heading'] ); ?></h2>
		<p<?php echo $krm_args['text_id'] ? ' id="' . esc_attr( $krm_args['text_id'] ) . '"' : ''; ?>><?php echo esc_html( $krm_args['text'] ); ?></p>
		<?php if ( $krm_args['points'] ) : ?>
		<ul>
			<?php foreach ( $krm_args['points'] as $krm_point ) : ?>
			<li><?php echo krm_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme SVG file. ?><span><?php echo esc_html( $krm_point ); ?></span></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
	<form class="give-card"<?php echo $krm_form['id'] ? ' id="' . esc_attr( $krm_form['id'] ) . '"' : ''; ?> data-give="<?php echo esc_attr( $krm_form['give'] ); ?>"<?php echo $krm_form['short'] ? ' data-short="true"' : ''; ?><?php echo $krm_form['heading'] ? ' data-heading="' . esc_attr( $krm_form['heading'] ) . '"' : ''; ?><?php echo $krm_form['intro'] ? ' data-intro="' . esc_attr( $krm_form['intro'] ) . '"' : ''; ?>></form>
</section>
