<?php
/**
 * Template Name: Become a Partner
 * Partner page.
 *
 * Markup from partner.html. Text, images and buttons are edited in the page's "Page content" box
 * (inc/content/partner.php).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="main">
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( krm_c( 'hero.image' ) ) ); ?>')">
		<div class="wrap">
		<ol class="breadcrumb"><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li><li aria-current="page"><?php echo esc_html( get_the_title() ); ?></li></ol>
		<p class="eyebrow"><?php krm_t( 'hero.eyebrow' ); ?></p>
		<h1><?php krm_h( 'hero.title' ); ?></h1>
		<p class="lead"><?php krm_t( 'hero.lead' ); ?></p>
		<div class="hero-actions">
			<a class="button button-primary" href="<?php krm_u( 'hero.button1_url' ); ?>"><?php krm_t( 'hero.button1_label' ); ?></a>
			<a class="button button-light" href="<?php krm_u( 'hero.button2_url' ); ?>"><?php krm_t( 'hero.button2_label' ); ?></a>
		</div>
		</div>
	</section>

	<section class="section">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow"><?php krm_t( 'why.eyebrow' ); ?></p>
			<h2><?php krm_h( 'why.heading' ); ?></h2>
			<p><?php krm_t( 'why.intro' ); ?></p>
		</div>
		<div class="why">
			<?php
			$krm_icons = array( 'clock24', 'mail24', 'people24' );
			foreach ( (array) krm_c( 'why.items' ) as $krm_i => $krm_item ) :
				?>
			<article class="why-item"><span class="why-icon"><?php echo krm_icon( $krm_icons[ $krm_i % 3 ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme SVG file. ?></span><h3><?php echo krm_text( $krm_item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><p><?php echo krm_text( $krm_item['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p></article>
			<?php endforeach; ?>
		</div>
		</div>
	</section>

	<section class="section section-soft" id="options">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow"><?php krm_t( 'options.eyebrow' ); ?></p>
			<h2><?php krm_h( 'options.heading' ); ?></h2>
			<p><?php krm_t( 'options.intro' ); ?></p>
		</div>
		<div class="options">
			<article class="option">
			
			<span class="why-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v6M9 6h6M5 21V11l7-4 7 4v10M9 21v-5h6v5"></path></svg></span>
			<h3>Prayer Partner</h3>
			<p class="price"><?php krm_t( 'options.prayer_price' ); ?></p>
			<p><?php krm_t( 'options.prayer_text' ); ?></p>
			<ul>
			<?php
			foreach ( krm_lines( krm_c( 'options.prayer_points' ) ) as $krm_line ) :
				?>
				<li><?php echo krm_icon( 'check16' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo krm_text( $krm_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></li><?php endforeach; ?></ul>
			<a class="button button-secondary" href="#join" data-choose="Prayer Partner"><?php krm_t( 'options.prayer_button' ); ?></a>
			</article>
			<article class="option featured">
			<span class="option-badge"><?php krm_t( 'options.monthly_badge' ); ?></span>
			<span class="why-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg></span>
			<h3>Monthly Partner</h3>
			<p class="price"><?php krm_t( 'options.monthly_price' ); ?></p>
			<p><?php krm_t( 'options.monthly_text' ); ?></p>
			<ul>
			<?php
			foreach ( krm_lines( krm_c( 'options.monthly_points' ) ) as $krm_line ) :
				?>
				<li><?php echo krm_icon( 'check16' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo krm_text( $krm_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></li><?php endforeach; ?></ul>
			<a class="button button-primary" href="#join" data-choose="Monthly Partner"><?php krm_t( 'options.monthly_button' ); ?></a>
			</article>
			<article class="option">
			
			<span class="why-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11zM9 12l2 2 4-4"></path></svg></span>
			<h3>Ministry Sponsor</h3>
			<p class="price"><?php krm_t( 'options.sponsor_price' ); ?></p>
			<p><?php krm_t( 'options.sponsor_text' ); ?></p>
			<ul>
			<?php
			foreach ( krm_lines( krm_c( 'options.sponsor_points' ) ) as $krm_line ) :
				?>
				<li><?php echo krm_icon( 'check16' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo krm_text( $krm_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></li><?php endforeach; ?></ul>
			<a class="button button-secondary" href="#join" data-choose="Ministry Sponsor"><?php krm_t( 'options.sponsor_button' ); ?></a>
			</article>
			<article class="option">
			
			<span class="why-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 10h18M5 10V6l7-4 7 4v4M5 10v10h14V10M9 20v-6h6v6"></path></svg></span>
			<h3>Church / Group Partner</h3>
			<p class="price"><?php krm_t( 'options.church_price' ); ?></p>
			<p><?php krm_t( 'options.church_text' ); ?></p>
			<ul>
			<?php
			foreach ( krm_lines( krm_c( 'options.church_points' ) ) as $krm_line ) :
				?>
				<li><?php echo krm_icon( 'check16' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo krm_text( $krm_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></li><?php endforeach; ?></ul>
			<a class="button button-secondary" href="#join" data-choose="Church / Group Partner"><?php krm_t( 'options.church_button' ); ?></a>
			</article>
		</div>
		</div>
	</section>

	<section class="section">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow"><?php krm_t( 'steps.eyebrow' ); ?></p>
			<h2><?php krm_h( 'steps.heading' ); ?></h2>
		</div>
		<div class="steps">
			<?php foreach ( (array) krm_c( 'steps.items' ) as $krm_item ) : ?>
			<div class="step"><h3><?php echo krm_text( $krm_item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><p><?php echo krm_text( $krm_item['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p></div>
			<?php endforeach; ?>
		</div>
		</div>
	</section>

	<section class="section section-soft" id="join">
		<div class="wrap signup">
		<div class="signup-intro">
			<p class="eyebrow"><?php krm_t( 'signup.eyebrow' ); ?></p>
			<h2><?php krm_h( 'signup.heading' ); ?></h2>
			<p><?php krm_t( 'signup.text' ); ?></p>
			<div class="signup-photo"><img <?php krm_img_src( krm_c( 'signup.image' ) ); ?> alt="<?php echo krm_alt( krm_c( 'signup.image' ), krm_c( 'signup.image_alt' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" loading="lazy" decoding="async"></div>
		</div>

		<div>
			<form class="partner-form" id="partner-form" novalidate>
			<p class="krm-hp" aria-hidden="true" style="position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden"><label>Leave this field empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
			<fieldset>
				<legend>1. Choose your partnership</legend>
				<div class="type-grid">
				<label class="type-card"><input type="radio" name="type" value="Prayer Partner"><span><strong>Prayer Partner</strong><small><?php krm_t( 'options.prayer_price' ); ?></small></span></label>
				<label class="type-card"><input type="radio" name="type" value="Monthly Partner" checked><span><strong>Monthly Partner</strong><small><?php krm_t( 'options.monthly_price' ); ?></small></span></label>
				<label class="type-card"><input type="radio" name="type" value="Ministry Sponsor"><span><strong>Ministry Sponsor</strong><small><?php krm_t( 'options.sponsor_price' ); ?></small></span></label>
				<label class="type-card"><input type="radio" name="type" value="Church / Group Partner"><span><strong>Church / Group Partner</strong><small><?php krm_t( 'options.church_price' ); ?></small></span></label>
				</div>
			</fieldset>

			<fieldset id="amount-set">
				<legend>2. Monthly amount</legend>
				<div class="amount-grid"><label><input type="radio" name="amount" value="$25"><span>$25<small>Friend</small></span></label><label><input type="radio" name="amount" value="$50" checked><span>$50<small>Supporter</small></span></label><label><input type="radio" name="amount" value="$100"><span>$100<small>Builder</small></span></label><label><input type="radio" name="amount" value="Other"><span>Other<small>Any amount</small></span></label></div>
				<div class="field" id="custom-field" hidden style="margin-top: 12px">
				<label for="custom">Your monthly amount (USD)</label>
				<input id="custom" name="custom" type="number" min="1" step="1" inputmode="numeric" placeholder="e.g. 75">
				<span class="error" hidden>Please enter an amount.</span>
				</div>
				<div class="field" id="ministry-field" hidden style="margin-top: 16px">
				<label for="ministry">Ministry you would like to sponsor</label>
				<select id="ministry" name="ministry">
					<?php foreach ( krm_ministries() as $krm_m ) : ?>
					<option value="<?php echo esc_attr( $krm_m['title'] ); ?>"><?php echo esc_html( $krm_m['title'] ); ?></option>
					<?php endforeach; ?>
				</select>
				</div>
			</fieldset>

			<fieldset>
				<legend>Your details</legend>
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
					<label for="phone">Phone / WhatsApp (optional)</label>
					<input id="phone" name="phone" type="tel" autocomplete="tel">
				</div>
				<div class="field">
					<label for="country">Country</label>
					<input id="country" name="country" autocomplete="country-name" required>
					<span class="error" hidden>Please enter your country.</span>
				</div>
				<div class="field full" id="church-field" hidden>
					<label for="church">Church or group name</label>
					<input id="church" name="church" autocomplete="organization">
				</div>
				<div class="field full">
					<label for="message">Anything you would like to share? (optional)</label>
					<textarea id="message" name="message" placeholder="Why you want to partner, prayer requests, questions…"></textarea>
				</div>
				</div>
				<label class="check"><input type="checkbox" name="updates" checked> <?php krm_t( 'signup.updates' ); ?></label>
			</fieldset>

			<button class="button button-primary form-submit" type="submit" id="partner-submit"><?php krm_t( 'signup.button' ); ?></button>
			<p class="form-error" id="form-error" role="alert" hidden><?php krm_t( 'signup.error' ); ?></p>
			<p class="form-foot"><?php krm_t( 'signup.foot' ); ?></p>
			</form>

			<div class="thanks" id="thanks" hidden>
			<span class="tick"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"></path></svg></span>
			<h2 tabindex="-1"><?php echo str_replace( '{name}', '<span id="thanks-name"></span>', krm_text( krm_c( 'signup.thanks_title' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in krm_text(). ?></h2>
			<p><?php echo str_replace( '{type}', '<strong id="thanks-type"></strong>', krm_text( krm_c( 'signup.thanks_text' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<p id="thanks-mail">Your email app should now be open with your details — just press <strong>send</strong> to complete your sign-up.</p>
			<div class="hero-actions">
				<a class="button button-primary" id="thanks-give" href="<?php krm_u( 'signup.thanks_give_url' ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg><?php krm_t( 'signup.thanks_give' ); ?></a>
				<a class="button button-secondary" href="<?php krm_u( 'signup.thanks_read_url' ); ?>"><?php krm_t( 'signup.thanks_read' ); ?></a>
			</div>
			</div>
		</div>
		</div>
	</section>

	<section class="section">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow"><?php krm_t( 'faq.eyebrow' ); ?></p>
			<h2><?php krm_h( 'faq.heading' ); ?></h2>
		</div>
		<?php get_template_part( 'template-parts/faq' ); ?>
		</div>
	</section>

	<section class="verse-band" style="background-image: url('<?php echo esc_url( krm_ref_url( krm_c( 'verse.image' ) ) ); ?>')">
		<div class="wrap">
		<blockquote><?php krm_t( 'verse.quote' ); ?><cite><?php krm_t( 'verse.cite' ); ?></cite></blockquote>
		<div class="hero-actions"><a class="button button-primary" href="<?php krm_u( 'verse.button_url' ); ?>"><?php krm_t( 'verse.button_label' ); ?></a></div>
		</div>
	</section>
	</main>
<?php
get_footer();
