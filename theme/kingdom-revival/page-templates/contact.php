<?php
/**
 * Template Name: Contact
 * Contact page.
 *
 * Markup copied from contact.html; only links, images and contact details are dynamic.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="main">
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-17-scaled.jpg' ) ); ?>')">
		<div class="wrap" style="padding-bottom: clamp(104px, 12vw, 140px)">
		<ol class="breadcrumb"><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li><li aria-current="page">Contact</li></ol>
		<p class="eyebrow">Contact Us</p>
		<h1>We would love to <em>hear from you</em></h1>
		<p class="lead">Whether you want to partner with us, send a prayer request, plan a visit or ask a question — our team will get back to you.</p>
		</div>
	</section>

	<div class="wrap contact-cards">
		<a class="contact-card" href="<?php echo esc_url( krm_tel() ); ?>"><span class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"></path></svg></span><span><h2>Call us</h2><p><strong><?php echo esc_html( krm_opt( 'phone' ) ); ?></strong><br>Call or send us a message</p></span></a>
		<a class="contact-card" href="<?php echo esc_url( krm_mailto() ); ?>"><span class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M22 6l-10 7L2 6"></path></svg></span><span><h2>Email us</h2><p><strong><?php echo esc_html( krm_opt( 'email' ) ); ?></strong><br>Write to us about anything</p></span></a>
		<div class="contact-card"><span class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></span><span><h2>Visit us</h2><p><?php echo esc_html( krm_opt( 'address' ) ); ?></p></span></div>
	</div>

	<section class="section">
		<div class="wrap">
		<div class="contact-layout">
			<form class="form-card" id="contact-form" novalidate>
			<p class="krm-hp" aria-hidden="true" style="position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden"><label>Leave this field empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
			<p class="eyebrow">Send a message</p>
			<h2>How can we help?</h2>
			<p>Fill in the form and your message will go straight to our team.</p>
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
				<button class="button button-primary" type="submit">Send message <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg></button>
				<small>We never share your details.</small>
			</div>
			<p class="form-status" id="form-status" role="status" hidden>Thank you — your message has been sent. Our team will get back to you soon.</p>
			<p class="form-status" id="form-error" role="alert" hidden>Sorry, something went wrong. Please try again, or write to us at <?php echo esc_html( krm_opt( 'email' ) ); ?>.</p>
			</form>

			<div class="side-stack">
			<div class="side-card prayer">
				<h3>Need prayer?</h3>
				<p>Share what is on your heart and our team will stand with you in prayer.</p>
				<a class="button button-light" href="#contact-form" id="prayer-link">Send a prayer request</a>
			</div>
			<div class="side-card">
				<h3>Support a ministry</h3>
				<p>Your gift brings Bibles, education, orphan care and practical help to families across Pakistan.</p>
				<a class="button button-primary" href="<?php echo esc_url( krm_page_url( 'donate' ) ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg>Donate now</a>
			</div>
			<div class="side-card">
				<h3>Office location</h3>
				<ul class="hours">
				<li><span>City</span><strong>Faisalabad</strong></li>
				<li><span>Province</span><strong>Punjab</strong></li>
				<li><span>Country</span><strong>Pakistan</strong></li>
				</ul>
			</div>
			</div>
		</div>

		<div class="map">
			<iframe title="Map showing Kingdom Revival Ministries in Faisalabad" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=Chak+No+220%2C+RB+Nasrat+Colony%2C+Street+No.+3%2C+Shadab+Puli%2C+Jhang+Road%2C+Faisalabad&amp;output=embed"></iframe>
		</div>
		</div>
	</section>
	</main>
<?php
get_footer();
