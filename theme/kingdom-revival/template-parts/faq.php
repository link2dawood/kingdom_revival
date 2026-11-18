<?php
/**
 * FAQ list (donate and partner pages) from the current page's "faq" content section.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
		<div class="faq">
			<?php foreach ( (array) krm_c( 'faq.items' ) as $krm_item ) : ?>
			<details><summary><?php echo krm_text( $krm_item['question'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in krm_text(). ?></summary><p><?php echo krm_text( $krm_item['answer'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p></details>
			<?php endforeach; ?>
		</div>
