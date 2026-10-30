<?php
/**
 * Template Name: Donate
 * Donate page.
 *
 * Markup copied from donate.html; only links, images and contact details are dynamic.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="main">
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-40-scaled.jpg' ) ); ?>')">
		<div class="wrap">
		<ol class="breadcrumb"><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li><li aria-current="page">Donate</li></ol>
		<p class="eyebrow">Donate</p>
		<h1>Your gift brings <em>hope</em> to Pakistan’s forgotten</h1>
		<p class="lead">Every gift helps us preach the Gospel, care for orphans and widows, educate brick kiln children and put Bibles in waiting hands.</p>
		<div class="hero-actions">
			<a class="button button-primary" href="#give">Give now</a>
			<a class="button button-light" href="<?php echo esc_url( krm_page_url( 'partner' ) ); ?>">Become a monthly partner</a>
		</div>
		</div>
	</section>

	<section class="section section-soft" id="give">
		<div class="wrap give-layout">
		<div>
			<p class="step-label"><span>1</span>Choose your gift</p>
			<form class="card gift-card" id="gift" onsubmit="return false">
			<fieldset>
				<legend>How often?</legend>
				<div class="seg">
				<label><input type="radio" name="frequency" value="One-time" checked><span>One-time</span></label>
				<label><input type="radio" name="frequency" value="Monthly"><span>Monthly</span></label>
				</div>
			</fieldset>
			<fieldset>
				<legend>Amount (USD)</legend>
				<div class="amts"><label><input type="radio" name="amount" value="$25"><span>$25</span></label><label><input type="radio" name="amount" value="$50" checked><span>$50</span></label><label><input type="radio" name="amount" value="$100"><span>$100</span></label><label><input type="radio" name="amount" value="$250"><span>$250</span></label><label><input type="radio" name="amount" value="$500"><span>$500</span></label><label><input type="radio" name="amount" value="Other"><span>Other</span></label></div>
				<div class="custom" id="custom-wrap" hidden>
				<label class="visually-hidden" for="custom">Other amount in US dollars</label>
				<input id="custom" name="custom" type="number" min="1" step="1" inputmode="numeric" placeholder="Enter amount">
				</div>
			</fieldset>
			<fieldset>
				<label class="label" for="fund">Where should your gift go?</label>
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
				<li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"></path></svg>Goes directly to the ministry in Pakistan</li>
				<li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"></path></svg>Give from anywhere in the world</li>
				<li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"></path></svg>Questions? Call <?php echo esc_html( krm_opt( 'phone' ) ); ?></li>
			</ul>
			</form>
		</div>

		<div>
			<p class="step-label"><span>2</span>Choose how to give</p>
			<div class="card">
			<div class="tabs" role="tablist" aria-label="Payment methods">
				<button type="button" class="tab" role="tab" id="tab-paypal" aria-controls="panel-paypal" data-method="PayPal"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/paypal-logo-png_seeklogo-249214-300x300.png' ); ?> alt="">PayPal</button>
				<button type="button" class="tab" role="tab" id="tab-bank" aria-controls="panel-bank" data-method="Bank transfer"><span class="tab-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"></path></svg></span>Bank transfer</button>
				<button type="button" class="tab" role="tab" id="tab-wu" aria-controls="panel-wu" data-method="Western Union"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/western-union-new-20232644.logowik.com_.webp' ); ?> alt="">Western Union</button>
				<button type="button" class="tab" role="tab" id="tab-mg" aria-controls="panel-mg" data-method="MoneyGram"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/money-gram-1024x642.png' ); ?> alt="">MoneyGram</button>
			</div>

			<div class="panel" role="tabpanel" id="panel-paypal" aria-labelledby="tab-paypal">
				<h3>Give with PayPal</h3>
				<p>The fastest way to give from anywhere. Pay with your PayPal balance or a debit or credit card. Your amount of <strong data-gift-label></strong> is filled in for you.</p>
				<a class="button button-primary" id="paypal-link" href="<?php echo esc_url( krm_opt( 'paypal_me' ) ); ?>" target="_blank" rel="noopener"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg><span>Give <span data-gift-label></span> with PayPal</span></a>
				<p class="note" id="paypal-monthly" hidden><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4M12 8h.01"></path></svg><span>PayPal.me sends one-time payments. For a monthly gift, please give through PayPal each month or set up a bank standing order.</span></p>
			</div>

			<div class="panel" role="tabpanel" id="panel-bank" aria-labelledby="tab-bank" hidden>
				<h3>Give by bank transfer</h3>
				<p>Send <strong data-gift-label></strong> from your bank using the details below. Tap <strong>Copy</strong> to copy a detail.</p>
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
				<p class="note"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4M12 8h.01"></path></svg><span>International transfers may take a few working days. Please add the ministry name in your payment reference.</span></p>
			</div>

			<div class="panel" role="tabpanel" id="panel-wu" aria-labelledby="tab-wu" hidden>
				<h3>Send with Western Union</h3>
				<p>Send <strong data-gift-label></strong> from a Western Union agent near you or online.</p>
				<ol class="steps-list">
				<li>Send to the receiver below, payable in Faisalabad, Pakistan.</li>
				<li>Keep your tracking number (MTCN).</li>
				<li>Email us the MTCN with “Let us know about your gift” below so we can collect it.</li>
				</ol>
				<div class="receiver"><p>Receiver: <strong><?php echo esc_html( krm_opt( 'bank_account_name' ) ); ?></strong> · CNIC <strong><?php echo esc_html( krm_opt( 'bank_cnic' ) ); ?></strong> · Faisalabad, Punjab, Pakistan · <strong><?php echo esc_html( krm_opt( 'phone' ) ); ?></strong></p></div>
				<a class="button button-secondary" href="https://www.westernunion.com/" target="_blank" rel="noopener">Go to Western Union</a>
			</div>

			<div class="panel" role="tabpanel" id="panel-mg" aria-labelledby="tab-mg" hidden>
				<h3>Send with MoneyGram</h3>
				<p>Send <strong data-gift-label></strong> from a MoneyGram location or online.</p>
				<ol class="steps-list">
				<li>Send to the receiver below, payable in Faisalabad, Pakistan.</li>
				<li>Keep your reference number.</li>
				<li>Email us the reference number with “Let us know about your gift” below so we can collect it.</li>
				</ol>
				<div class="receiver"><p>Receiver: <strong><?php echo esc_html( krm_opt( 'bank_account_name' ) ); ?></strong> · CNIC <strong><?php echo esc_html( krm_opt( 'bank_cnic' ) ); ?></strong> · Faisalabad, Punjab, Pakistan · <strong><?php echo esc_html( krm_opt( 'phone' ) ); ?></strong></p></div>
				<a class="button button-secondary" href="https://www.moneygram.com/" target="_blank" rel="noopener">Go to MoneyGram</a>
			</div>
			</div>

			<div class="notify">
			<p class="step-label"><span>3</span>Let us know</p>
			<div class="card">
				<p style="margin: 0">After you give, send us a quick email so we can thank you, confirm your gift and use it exactly where you asked. Your gift details are filled in for you.</p>
				<div class="hero-actions">
				<a class="button button-primary" id="notify-link" href="<?php echo esc_url( krm_mailto() ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M22 6l-10 7L2 6"></path></svg>Let us know about your gift</a>
				<a class="button button-secondary" href="<?php echo esc_url( krm_whatsapp_url() ); ?>" target="_blank" rel="noopener">WhatsApp us</a>
				</div>
			</div>
			</div>
		</div>
		</div>
	</section>

	<section class="section">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow">Your gift at work</p>
			<h2>Where your generosity goes</h2>
			<p>Gifts given “where it is needed most” are used across all twelve ministries.</p>
		</div>
		<div class="impact">
			<article class="impact-card"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-18-scaled.jpg' ); ?> alt="Bibles in Urdu" loading="lazy" decoding="async"><div><h3>Bibles in Urdu</h3><p>Scripture for new believers, families and house churches.</p></div></article>
			<article class="impact-card"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-39-scaled.jpg' ); ?> alt="Brick kiln children" loading="lazy" decoding="async"><div><h3>Brick kiln children</h3><p>Education, care and the Gospel for children beside the kilns.</p></div></article>
			<article class="impact-card"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-20.jpg' ); ?> alt="Orphan care" loading="lazy" decoding="async"><div><h3>Orphan care</h3><p>Shelter, meals and schooling for orphaned children.</p></div></article>
			<article class="impact-card"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Widows-ministry-5-scaled.jpg' ); ?> alt="Widows &amp; families" loading="lazy" decoding="async"><div><h3>Widows &amp; families</h3><p>Practical help and fellowship for those in need.</p></div></article>
		</div>
		</div>
	</section>

	<section class="section section-soft">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow">Questions</p>
			<h2>Giving FAQ</h2>
		</div>
		<div class="faq">
			<details><summary>Which payment method should I use?</summary><p>PayPal is the quickest way to give from anywhere in the world. Bank transfer works well for larger or regular gifts. Western Union and MoneyGram are helpful if you prefer to send cash transfers.</p></details>
			<details><summary>How do I give monthly?</summary><p>Set up a standing order or recurring transfer with your bank using the account details above, or send your gift through PayPal each month. Let us know so we can thank you and keep you updated.</p></details>
			<details><summary>How do I tell you what my gift is for?</summary><p>Choose the ministry in the gift form, then use “Let us know about your gift” to email us. Adding the ministry name in your bank or PayPal reference also helps.</p></details>
			<details><summary>Who can I contact with questions?</summary><p>Call or WhatsApp us on <?php echo esc_html( krm_opt( 'phone' ) ); ?>, or email <?php echo esc_html( krm_opt( 'email' ) ); ?>. We are happy to help.</p></details>
		</div>
		</div>
	</section>
	</main>
<?php
get_footer();
