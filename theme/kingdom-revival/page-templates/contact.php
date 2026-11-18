<?php
/**
 * Template Name: Contact
 * Contact page.
 *
 * Markup from contact.html. Text, images and buttons are edited in the page's "Page content" box
 * (inc/content/contact.php); phone, email and address come from Kingdom Revival → Settings.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="main">
	<?php krm_hero_preload( krm_c( 'hero.image' ) ); ?>
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( krm_c( 'hero.image' ) ) ); ?>')<?php krm_bg_small( krm_c( 'hero.image' ) ); ?>">
		<div class="wrap" style="padding-bottom: clamp(104px, 12vw, 140px)">
		<?php krm_breadcrumb_html(); ?>
		<p class="eyebrow"><?php krm_t( 'hero.eyebrow' ); ?></p>
		<h1><?php krm_h( 'hero.title' ); ?></h1>
		<p class="lead"><?php krm_t( 'hero.lead' ); ?></p>
		</div>
	</section>

	<?php
	// Office hours: visitors see the card only once hours are filled in (an empty or [placeholder] value is ''), and the grid closes up.
	$krm_hours = '' !== trim( (string) krm_c( 'cards.hours_text' ) );
	?>
	<div class="wrap contact-cards<?php echo $krm_hours ? '' : ' is-4'; ?>">
		<a class="contact-card" href="<?php echo esc_url( krm_tel() ); ?>"><span class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"></path></svg></span><span><h2><?php krm_t( 'cards.call_title' ); ?></h2><p><strong class="contact-card__nowrap"><?php echo esc_html( krm_opt( 'phone' ) ); ?></strong><br><?php krm_t( 'cards.call_text' ); ?></p></span></a>
		<a class="contact-card" href="<?php echo esc_url( krm_mailto() ); ?>"><span class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M22 6l-10 7L2 6"></path></svg></span><span><h2><?php krm_t( 'cards.email_title' ); ?></h2><p><strong class="contact-card__email"><?php echo str_replace( array( '@', '.' ), array( '@<wbr>', '<wbr>.' ), esc_html( krm_opt( 'email' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped; <wbr> lets it break after the @ and before a dot. ?></strong><br><?php krm_t( 'cards.email_text' ); ?></p></span></a>
		<div class="contact-card"><span class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></span><span><h2><?php krm_t( 'cards.visit_title' ); ?></h2><p><?php echo esc_html( krm_opt( 'address' ) ); ?></p></span></div>
		<a class="contact-card" href="<?php echo esc_url( krm_whatsapp_url() ); ?>" target="_blank" rel="noopener"><span class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.1-5.4A8.4 8.4 0 1 1 21 11.5z"></path></svg></span><span><h2><?php krm_t( 'cards.whatsapp_title' ); ?></h2><p><strong class="contact-card__nowrap"><?php echo esc_html( krm_opt( 'phone' ) ); ?></strong><br><?php krm_t( 'cards.whatsapp_text' ); ?></p></span></a>
		<?php if ( $krm_hours ) : ?>
		<div class="contact-card"><span class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg></span><span><h2><?php krm_t( 'cards.hours_title' ); ?></h2><p><strong><?php krm_t( 'cards.hours_text' ); ?></strong><br><?php krm_t( 'cards.hours_note' ); ?></p></span></div>
		<?php endif; ?>
	</div>

	<section class="section">
		<div class="wrap">
		<div class="contact-layout">
			<form class="form-card" id="contact-form" novalidate>
			<p class="krm-hp" aria-hidden="true" style="position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden"><label>Leave this field empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
			<p class="eyebrow"><?php krm_t( 'form.eyebrow' ); ?></p>
			<h2><?php krm_h( 'form.heading' ); ?></h2>
			<p><?php krm_t( 'form.intro' ); ?></p>
			<div class="form-grid">
				<div class="field">
				<label for="name">Full name</label>
				<input id="name" name="name" autocomplete="name" required>
				<span class="error" hidden>Please enter your name.</span>
				</div>
				<div class="field">
				<label for="email">Email address</label>
				<input id="email" name="email" type="email" autocomplete="email" required>
				<span class="error" hidden>Please enter a valid email address.</span>
				</div>
				<div class="field">
				<label for="phone">Phone (optional)</label>
				<input id="phone" name="phone" type="tel" autocomplete="tel">
				</div>
				<div class="field">
				<label for="topic">I’m reaching out about</label>
				<select id="topic" name="topic">
					<option>General question</option>
					<option>Partnership &amp; giving</option>
					<option>Prayer request</option>
					<option>Visiting or volunteering</option>
					<option>Sponsoring a ministry</option>
				</select>
				</div>
				<div class="field full">
				<label for="message">Your message</label>
				<textarea id="message" name="message" required></textarea>
				<span class="error" hidden>Please write a short message.</span>
				</div>
			</div>
			<div class="form-actions">
				<button class="button button-primary" type="submit"><?php krm_t( 'form.button' ); ?> <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg></button>
				<small><?php krm_t( 'form.note' ); ?></small>
			</div>
			<p class="form-status" id="form-status" role="status" hidden><?php krm_t( 'form.success' ); ?></p>
			<p class="form-status" id="form-error" role="alert" hidden><?php krm_t( 'form.error' ); ?></p>
			</form>

			<div class="side-stack">
			<div class="side-card prayer">
				<h3><?php krm_t( 'side.prayer_heading' ); ?></h3>
				<p><?php krm_t( 'side.prayer_text' ); ?></p>
				<a class="button button-light" href="#contact-form" id="prayer-link"><?php krm_t( 'side.prayer_button' ); ?></a>
			</div>
			<div class="side-card">
				<h3><?php krm_t( 'side.give_heading' ); ?></h3>
				<p><?php krm_t( 'side.give_text' ); ?></p>
				<a class="button button-primary" href="<?php krm_u( 'side.give_url' ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg><?php krm_t( 'side.give_button' ); ?></a>
			</div>
			<div class="side-card">
				<h3><?php krm_t( 'side.office_heading' ); ?></h3>
				<ul class="hours">
				<?php foreach ( (array) krm_c( 'side.office_rows' ) as $krm_row ) : ?>
				<li><span><?php echo krm_text( $krm_row['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><strong><?php echo krm_text( $krm_row['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong></li>
				<?php endforeach; ?>
				</ul>
			</div>
			</div>
		</div>

		<?php if ( '' !== trim( krm_c( 'map.query' ) ) ) : ?>
		<div class="map">
			<iframe title="<?php echo esc_attr( krm_fill( krm_c( 'map.title' ) ) ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?php echo esc_url( 'https://www.google.com/maps?q=' . urlencode( krm_fill( krm_c( 'map.query' ) ) ) . '&output=embed' ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.urlencode_urlencode -- "+" for spaces, as in the design's map link. ?>"></iframe>
		</div>
		<?php endif; ?>
		<?php $krm_socials = array_filter( array( 'Facebook' => krm_opt( 'facebook' ), 'Instagram' => krm_opt( 'instagram' ), 'YouTube' => krm_opt( 'youtube' ) ) ); ?>
		<?php if ( $krm_socials || krm_show_placeholders() ) : // Visitors: no section without links. ?>
		<section class="contact-social" aria-labelledby="contact-social-title"><div><p class="eyebrow">Follow the ministry</p><h2 id="contact-social-title">See what God is doing</h2></div><?php if ( $krm_socials ) : ?><div><?php foreach ( $krm_socials as $krm_name => $krm_url ) : ?><a class="button button-secondary" href="<?php echo esc_url( $krm_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $krm_name ); ?></a><?php endforeach; ?></div><?php else : ?><p class="krm-admin-only">Only administrators see this section: add the Facebook, YouTube or Instagram links in Kingdom Revival → Settings.</p><?php endif; ?></section>
		<?php endif; ?>
		</div>
	</section>
	</main>
<?php
get_footer();
