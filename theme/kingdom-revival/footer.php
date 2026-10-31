<?php
/**
 * Footer for inner pages.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
	<footer class="site-footer">
	<div class="wrap footer-grid">
		<div>
		<div class="footer-brand"><img src="<?php echo esc_url( krm_logo_url() ); ?>" alt="" width="64" height="64" loading="lazy"><?php krm_t( 'footer.name', 'site' ); ?></div>
		<p><?php krm_t( 'footer.tagline', 'site' ); ?></p>
		</div>
		<div>
		<h2><?php krm_t( 'footer.quick_heading', 'site' ); ?></h2>
		<ul class="footer-links">
			<?php foreach ( krm_footer_links( 'footer_quick' ) as $krm_link ) : ?>
			<li><a href="<?php echo esc_url( $krm_link['url'] ); ?>"><?php echo esc_html( $krm_link['title'] ); ?></a></li>
			<?php endforeach; ?>
		</ul>
		</div>
		<div>
		<h2><?php krm_t( 'footer.ministries_heading', 'site' ); ?></h2>
		<ul class="footer-links">
			<?php foreach ( krm_footer_links( 'footer_ministries' ) as $krm_link ) : ?>
			<li><a href="<?php echo esc_url( $krm_link['url'] ); ?>"><?php echo esc_html( $krm_link['title'] ); ?></a></li>
			<?php endforeach; ?>
		</ul>
		</div>
		<div>
		<h2><?php krm_t( 'footer.contact_heading', 'site' ); ?></h2>
		<address>
			<span><?php echo esc_html( krm_opt( 'address' ) ); ?></span>
			<a href="<?php echo esc_url( krm_tel() ); ?>"><?php echo esc_html( krm_opt( 'phone' ) ); ?></a>
			<a href="<?php echo esc_url( krm_mailto() ); ?>"><?php echo esc_html( krm_opt( 'email' ) ); ?></a>
		</address>
		</div>
	</div>
	<div class="wrap footer-bottom">
		<span><?php krm_t( 'footer.copyright', 'site' ); ?></span>
		<span><?php krm_t( 'footer.location', 'site' ); ?></span>
	</div>
	</footer>
	<?php wp_footer(); ?>
</body>
</html>
