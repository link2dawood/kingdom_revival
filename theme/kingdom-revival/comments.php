<?php
/**
 * Comments (only shown where comments are open).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="entry-content" style="margin-top: 48px">
	<?php if ( have_comments() ) : ?>
		<h2><?php echo esc_html( sprintf( /* translators: %d: number of comments */ _n( '%d comment', '%d comments', get_comments_number(), 'kingdom-revival' ), get_comments_number() ) ); ?></h2>
		<ol class="comment-list">
		<?php
		wp_list_comments(
			array(
				'style'      => 'ol',
				'short_ping' => true,
			)
		);
		?>
									</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php comment_form(); ?>
</section>
