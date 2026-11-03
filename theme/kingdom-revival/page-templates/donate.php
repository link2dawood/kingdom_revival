<?php
/**
 * Template Name: Donate
 * Donate page.
 *
 * Markup from donate.html. Text, images, buttons and amounts are edited in the page's "Page content" box
 * (inc/content/donate.php); bank, PayPal and receiver details come from Kingdom Revival → Settings.
 * A donor returning from PayPal (?thanks=1) sees the thank-you view in place of the hero and giving steps
 * (template-parts/donate-thanks.php, inc/donations.php).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
$krm_gift = krm_gift_return();
?>
	<main id="main">
	<?php if ( $krm_gift ) : ?>
		<?php get_template_part( 'template-parts/donate-thanks', null, $krm_gift ); ?>
	<?php else : ?>
	<?php krm_hero_preload( krm_c( 'hero.image' ) ); ?>
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( krm_c( 'hero.image' ) ) ); ?>')<?php krm_bg_small( krm_c( 'hero.image' ) ); ?>">
		<div class="wrap">
		<?php krm_breadcrumb_html(); ?>
		<p class="eyebrow"><?php krm_t( 'hero.eyebrow' ); ?></p>
		<h1><?php krm_h( 'hero.title' ); ?></h1>
		<p class="lead"><?php krm_t( 'hero.lead' ); ?></p>
		<div class="hero-actions">
			<a class="button button-primary" href="<?php krm_u( 'hero.button1_url' ); ?>"><?php krm_t( 'hero.button1_label' ); ?></a>
			<a class="button button-light" href="<?php krm_u( 'hero.button2_url' ); ?>"><?php krm_t( 'hero.button2_label' ); ?></a>
		</div>
		</div>
	</section>

	<section class="section section-soft" id="give">
		<div class="wrap give-layout">
		<div>
			<p class="step-label"><span>1</span><?php krm_t( 'gift.step' ); ?></p>
			<form class="card gift-card" id="gift" onsubmit="return false">
			<fieldset>
				<legend><?php krm_t( 'gift.frequency' ); ?></legend>
				<div class="seg">
				<label><input type="radio" name="frequency" value="One-time" checked><span>One-time</span></label>
				<label><input type="radio" name="frequency" value="Monthly"><span>Monthly</span></label>
				</div>
			</fieldset>
			<fieldset>
				<legend><?php krm_t( 'gift.amount_label' ); ?></legend>
				<div class="amts">
				<?php
				foreach ( krm_donate_amounts() as $krm_a => $krm_on ) :
					?>
					<label><input type="radio" name="amount" value="<?php echo esc_attr( $krm_a ); ?>"<?php echo $krm_on ? ' checked' : ''; ?>><span><?php echo esc_html( $krm_a ); ?></span></label><?php endforeach; ?></div>
				<div class="custom" id="custom-wrap" hidden>
				<label class="visually-hidden" for="custom">Other amount in US dollars</label>
				<input id="custom" name="custom" type="number" min="1" step="1" inputmode="numeric" placeholder="Enter amount">
				</div>
			</fieldset>
			<fieldset>
				<label class="label" for="fund"><?php krm_t( 'gift.fund_label' ); ?></label>
				<select id="fund" name="fund">
				<option value="Where it is needed most">Where it is needed most</option>
				<?php foreach ( krm_ministries() as $krm_m ) : ?>
				<option value="<?php echo esc_attr( $krm_m['title'] ); ?>"><?php echo esc_html( $krm_m['title'] ); ?></option>
				<?php endforeach; ?>
				</select>
			</fieldset>
			<div class="summary" aria-live="polite">
				<span class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg></span>
				<div><small>Your gift</small><strong id="summary-amount"></strong><small id="summary-fund"></small></div>
			</div>
			<ul class="trust">
				<?php foreach ( krm_lines( krm_c( 'gift.trust' ) ) as $krm_line ) : ?>
				<li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"></path></svg><?php echo krm_text( $krm_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
				<?php endforeach; ?>
			</ul>
			</form>
		</div>

		<div>
			<p class="step-label"><span>2</span><?php krm_t( 'methods.step' ); ?></p>
			<div class="card">
			<div class="tabs" role="tablist" aria-label="Payment methods">
				<button type="button" class="tab" role="tab" id="tab-paypal" aria-controls="panel-paypal" data-method="PayPal"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/paypal-logo-png_seeklogo-249214-300x300.png', '48px' ); ?> alt="" width="300" height="300">PayPal</button>
				<button type="button" class="tab" role="tab" id="tab-bank" aria-controls="panel-bank" data-method="Bank transfer"><span class="tab-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"></path></svg></span>Bank transfer</button>
				<button type="button" class="tab" role="tab" id="tab-wu" aria-controls="panel-wu" data-method="Western Union"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/western-union-new-20232644.logowik.com_.webp', '48px' ); ?> alt="" width="866" height="650">Western Union</button>
				<button type="button" class="tab" role="tab" id="tab-mg" aria-controls="panel-mg" data-method="MoneyGram"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/money-gram-1024x642.png', '48px' ); ?> alt="" width="1024" height="642">MoneyGram</button>
			</div>

			<div class="panel" role="tabpanel" id="panel-paypal" aria-labelledby="tab-paypal">
				<h2><?php krm_t( 'methods.paypal_heading' ); ?></h2>
				<p><?php echo krm_gift_text( krm_c( 'methods.paypal_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in krm_gift_text(). ?></p>
				<a class="button button-primary" id="paypal-link" href="<?php echo esc_url( krm_opt( 'paypal_me' ) ); ?>" target="_blank" rel="noopener"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg><span><?php echo krm_gift_text( krm_c( 'methods.paypal_button' ), 'span' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></a>
				<p class="note" id="paypal-monthly" hidden><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4M12 8h.01"></path></svg><span><?php krm_t( 'methods.paypal_monthly' ); ?></span></p>
			</div>

			<div class="panel" role="tabpanel" id="panel-bank" aria-labelledby="tab-bank" hidden>
				<h2><?php krm_t( 'methods.bank_heading' ); ?></h2>
				<p><?php echo krm_gift_text( krm_c( 'methods.bank_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<dl class="bank">
				<div class="bank-row">
					<dt>Account name</dt>
					<dd><span><?php echo esc_html( krm_opt( 'bank_account_name' ) ); ?></span><button type="button" class="copy" data-copy="<?php echo esc_attr( krm_opt( 'bank_account_name' ) ); ?>" aria-label="Copy Account name"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg><span>Copy</span></button></dd>
				</div>
				<div class="bank-row">
					<dt>CNIC number</dt>
					<dd><span><?php echo esc_html( krm_opt( 'bank_cnic' ) ); ?></span><button type="button" class="copy" data-copy="<?php echo esc_attr( krm_opt( 'bank_cnic' ) ); ?>" aria-label="Copy CNIC number"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg><span>Copy</span></button></dd>
				</div>
				<div class="bank-row">
					<dt>Bank</dt>
					<dd><span><?php echo esc_html( krm_opt( 'bank_name' ) ); ?></span></dd>
				</div>
				<div class="bank-row">
					<dt>IBAN</dt>
					<dd><span><?php echo esc_html( krm_opt( 'bank_iban' ) ); ?></span><button type="button" class="copy" data-copy="<?php echo esc_attr( krm_opt( 'bank_iban' ) ); ?>" aria-label="Copy IBAN"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg><span>Copy</span></button></dd>
				</div>
				<div class="bank-row">
					<dt>SWIFT code</dt>
					<dd><span><?php echo esc_html( krm_opt( 'bank_swift' ) ); ?></span><button type="button" class="copy" data-copy="<?php echo esc_attr( krm_opt( 'bank_swift' ) ); ?>" aria-label="Copy SWIFT code"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg><span>Copy</span></button></dd>
				</div>
				<div class="bank-row">
					<dt>Branch code</dt>
					<dd><span><?php echo esc_html( krm_opt( 'bank_branch_code' ) ); ?></span><button type="button" class="copy" data-copy="<?php echo esc_attr( krm_opt( 'bank_branch_code' ) ); ?>" aria-label="Copy Branch code"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg><span>Copy</span></button></dd>
				</div>
				<div class="bank-row">
					<dt>Branch address</dt>
					<dd><span><?php echo esc_html( krm_opt( 'bank_branch_address' ) ); ?></span></dd>
				</div>
				<div class="bank-row">
					<dt>Phone</dt>
					<dd><span><?php echo esc_html( krm_opt( 'bank_phone' ) ); ?></span></dd>
				</div>
				</dl>
				<p class="note"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4M12 8h.01"></path></svg><span><?php krm_t( 'methods.bank_note' ); ?></span></p>
			</div>

			<div class="panel" role="tabpanel" id="panel-wu" aria-labelledby="tab-wu" hidden>
				<h2><?php krm_t( 'methods.wu_heading' ); ?></h2>
				<p><?php echo krm_gift_text( krm_c( 'methods.wu_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<ol class="steps-list">
					<?php foreach ( krm_lines( krm_c( 'methods.wu_steps' ) ) as $krm_line ) : ?>
					<li><?php echo krm_text( $krm_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
					<?php endforeach; ?>
				</ol>
				<div class="receiver"><p>Receiver: <strong><?php echo esc_html( krm_opt( 'bank_account_name' ) ); ?></strong> · CNIC <strong><?php echo esc_html( krm_opt( 'bank_cnic' ) ); ?></strong> · <?php krm_t( 'methods.receiver_place' ); ?> · <strong><?php echo esc_html( krm_opt( 'phone' ) ); ?></strong></p></div>
				<a class="button button-secondary" href="<?php krm_u( 'methods.wu_url' ); ?>" target="_blank" rel="noopener"><?php krm_t( 'methods.wu_button' ); ?></a>
			</div>

			<div class="panel" role="tabpanel" id="panel-mg" aria-labelledby="tab-mg" hidden>
				<h2><?php krm_t( 'methods.mg_heading' ); ?></h2>
				<p><?php echo krm_gift_text( krm_c( 'methods.mg_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<ol class="steps-list">
					<?php foreach ( krm_lines( krm_c( 'methods.mg_steps' ) ) as $krm_line ) : ?>
					<li><?php echo krm_text( $krm_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
					<?php endforeach; ?>
				</ol>
				<div class="receiver"><p>Receiver: <strong><?php echo esc_html( krm_opt( 'bank_account_name' ) ); ?></strong> · CNIC <strong><?php echo esc_html( krm_opt( 'bank_cnic' ) ); ?></strong> · <?php krm_t( 'methods.receiver_place' ); ?> · <strong><?php echo esc_html( krm_opt( 'phone' ) ); ?></strong></p></div>
				<a class="button button-secondary" href="<?php krm_u( 'methods.mg_url' ); ?>" target="_blank" rel="noopener"><?php krm_t( 'methods.mg_button' ); ?></a>
			</div>
			</div>

			<div class="notify">
			<p class="step-label"><span>3</span><?php krm_t( 'notify.step' ); ?></p>
			<div class="card">
				<p style="margin: 0"><?php krm_t( 'notify.text' ); ?></p>
				<div class="hero-actions">
				<a class="button button-primary" id="notify-link" href="<?php echo esc_url( krm_mailto() ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M22 6l-10 7L2 6"></path></svg><?php krm_t( 'notify.email_button' ); ?></a>
				<a class="button button-secondary" href="<?php echo esc_url( krm_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php krm_t( 'notify.whatsapp_button' ); ?></a>
				</div>
			</div>
			</div>
		</div>
		</div>
	</section>
	<?php endif; ?>

	<section class="section">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow"><?php krm_t( 'impact.eyebrow' ); ?></p>
			<h2><?php krm_h( 'impact.heading' ); ?></h2>
			<p><?php krm_t( 'impact.intro' ); ?></p>
		</div>
		<div class="impact">
			<?php foreach ( (array) krm_c( 'impact.cards' ) as $krm_card ) : ?>
			<article class="impact-card"><img <?php krm_img_src( $krm_card['image'], '(max-width: 560px) 100vw, (max-width: 1000px) 50vw, 290px', true ); ?> alt="<?php echo krm_alt( $krm_card['image'], $krm_card['image_alt'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" loading="lazy" decoding="async"><div><h3><?php echo krm_text( $krm_card['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><p><?php echo krm_text( $krm_card['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p></div></article>
			<?php endforeach; ?>
		</div>
		</div>
	</section>

	<section class="section section-soft">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow"><?php krm_t( 'faq.eyebrow' ); ?></p>
			<h2><?php krm_h( 'faq.heading' ); ?></h2>
		</div>
		<?php get_template_part( 'template-parts/faq' ); ?>
		</div>
	</section>
	</main>
<?php
get_footer();
