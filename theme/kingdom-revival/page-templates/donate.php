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
 * Phase 24: the gift card is the "donation" form (inc/form-handler.php). Step 3's button sends the donor details,
 * amount, frequency, ministry and chosen payment method as a Donation Intent email; it is not a payment and never asks
 * for card or bank-login details (PayPal / Stripe take those on their own pages).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
$krm_gift = krm_gift_return();
$krm_gs   = krm_form_state( 'gift' );
$krm_gok  = $krm_gs && $krm_gs['ok'];
/* A field's value after a failed post without JavaScript, its error attributes and note. */
$krm_dv = function ( $key ) {
	return esc_attr( krm_fv( 'gift', $key ) );
};
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
			<form class="card gift-card" <?php krm_form_attrs( 'donation', 'gift' ); ?>>
			<?php krm_form_hidden( 'donation', 'gift' ); ?>
			<input type="hidden" name="method" value="<?php echo $krm_dv( 'method' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" data-krm-method>
			<fieldset>
				<legend><?php krm_t( 'gift.frequency' ); ?></legend>
				<div class="seg">
				<?php $krm_mon = 'Monthly' === krm_fv( 'gift', 'frequency' ); // After a failed post without JavaScript. ?>
				<label><input type="radio" name="frequency" value="One-time"<?php echo $krm_mon ? '' : ' checked'; ?>><span>One-time</span></label>
				<label><input type="radio" name="frequency" value="Monthly"<?php echo $krm_mon ? ' checked' : ''; ?>><span>Monthly</span></label>
				</div>
			</fieldset>
			<fieldset>
				<legend><?php krm_t( 'gift.amount_label' ); ?></legend>
				<div class="amts">
				<?php
				$krm_amts = array( '$25' => false, '$50' => true, '$100' => false, '$250' => false, 'Other' => false );
				$krm_back = krm_fv( 'gift', 'amount' ); // After a failed post without JavaScript.
				if ( '' !== $krm_back ) {
					$krm_amts = array_fill_keys( array_keys( $krm_amts ), false );
					$krm_amts[ isset( $krm_amts[ $krm_back ] ) ? $krm_back : 'Other' ] = true;
				}
				foreach ( $krm_amts as $krm_a => $krm_on ) :
					?>
					<label><input type="radio" name="amount" value="<?php echo esc_attr( $krm_a ); ?>"<?php echo $krm_on ? ' checked' : ''; ?>><span><?php echo esc_html( $krm_a ); ?></span></label><?php endforeach; ?></div>
				<div class="custom" id="custom-wrap" hidden>
				<label class="visually-hidden" for="custom">Other amount in US dollars</label>
				<input id="custom" name="custom" type="number" min="1" step="1" inputmode="numeric" placeholder="Enter amount"<?php echo '' !== $krm_back && $krm_amts['Other'] ? ' value="' . esc_attr( ltrim( $krm_back, '$' ) ) . '"' : ''; ?>>
				</div>
			</fieldset>
			<fieldset>
				<label class="label" for="fund"><?php krm_t( 'gift.fund_label' ); ?></label>
				<?php
				// ?ministry=slug (every "Support this ministry" link) or ?for=Name preselects the fund; donate.js does the same.
				// phpcs:disable WordPress.Security.NonceVerification.Recommended
				$krm_pre_slug = isset( $_GET['ministry'] ) ? sanitize_title( wp_unslash( $_GET['ministry'] ) ) : '';
				$krm_pre_for  = isset( $_GET['for'] ) ? sanitize_text_field( wp_unslash( $_GET['for'] ) ) : '';
				if ( '' !== krm_fv( 'gift', 'fund' ) ) {
					$krm_pre_slug = '';
					$krm_pre_for  = krm_fv( 'gift', 'fund' );
				}
				// phpcs:enable
				$krm_funds = array( array( 'Where it is needed most', 'where-most-needed', 'Where most needed' ) );
				foreach ( krm_ministries() as $krm_m ) {
					$krm_funds[] = array( $krm_m['title'], $krm_m['slug'], $krm_m['title'] );
				}
				// Hidden ministries (drafts, e.g. Youth Ministry / Women Empowerment until the client chooses) are not
				// offered: krm_ministries() lists published ones only (Phase 21: hidden ministries appear nowhere).
				$krm_pick    = 0;
				foreach ( $krm_funds as $krm_i => $krm_f ) {
					if ( ( '' !== $krm_pre_slug && $krm_pre_slug === $krm_f[1] ) || ( '' === $krm_pre_slug && '' !== $krm_pre_for && $krm_pre_for === $krm_f[0] ) ) {
						$krm_pick = $krm_i;
					}
				}
				?>
				<select id="fund" name="fund">
				<?php foreach ( $krm_funds as $krm_i => $krm_f ) : ?>
				<option value="<?php echo esc_attr( $krm_f[0] ); ?>" data-slug="<?php echo esc_attr( $krm_f[1] ); ?>"<?php selected( $krm_i, $krm_pick ); ?>><?php echo esc_html( $krm_f[2] ); ?></option>
				<?php endforeach; ?>
				</select>
			</fieldset>
			<fieldset class="donor-details"><legend>Donor details</legend><div class="donor-grid">
				<?php
				foreach ( array(
					'name'    => array( 'Full name', 'type="text" autocomplete="name" required maxlength="100"' ),
					'contact' => array( 'Email or WhatsApp', 'type="text" autocomplete="email" required maxlength="200" data-validate="email-or-phone"' ),
					'country' => array( 'Country', 'type="text" autocomplete="country-name" maxlength="200"' ),
					'message' => array( 'Message (optional)', '' ),
				) as $krm_k => $krm_d ) :
					list( $krm_ea, $krm_en ) = krm_field_error( 'gift', $krm_k );
					?>
				<label><?php echo esc_html( $krm_d[0] ); ?><?php if ( 'message' === $krm_k ) : ?><textarea name="message" rows="3" maxlength="2000"<?php echo $krm_ea; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_textarea( krm_fv( 'gift', 'message' ) ); ?></textarea><?php else : ?><input <?php echo $krm_d[1]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attributes. ?> name="<?php echo esc_attr( $krm_k ); ?>" value="<?php echo $krm_dv( $krm_k ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"<?php echo $krm_ea; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php endif; ?><?php echo $krm_en; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
				<?php endforeach; ?>
				</div><p class="donor-note"><?php esc_html_e( 'Never type card numbers, CVV codes or bank passwords here. Payments are made only on PayPal’s (or your bank’s) own secure pages.', 'kingdom-revival' ); ?></p></fieldset>
			<div class="summary" aria-live="polite">
				<span class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg></span>
				<div><small>Your gift</small><strong id="summary-amount"></strong><small id="summary-fund"></small></div>
			</div>
			<ul class="trust">
				<?php foreach ( krm_lines( krm_c( 'gift.trust' ) ) as $krm_line ) : ?>
				<li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"></path></svg><?php echo krm_text( $krm_line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
				<?php endforeach; ?>
			</ul>
			<?php $krm_reg = trim( (string) krm_c( 'accountability.reg_number', 'about' ) ); // About → Accountability; visitors see it once filled in. ?>
			<div class="donate-trust-row"><?php if ( '' !== $krm_reg ) : ?><span>Reg. No. <strong><?php echo esc_html( $krm_reg ); ?></strong></span><?php endif; ?><a href="<?php echo esc_url( krm_page_url( 'impact' ) ); ?>">Financial transparency →</a></div>
			</form>
		</div>

		<div>
			<p class="step-label"><span>2</span><?php krm_t( 'methods.step' ); ?></p>
			<div class="card">
			<?php $krm_stripe = krm_show_placeholders(); // Stripe is not connected yet: administrators only, so visitors never see a disabled option. ?>
			<div class="tabs<?php echo $krm_stripe ? '' : ' is-4'; ?>" role="tablist" aria-label="Payment methods">
				<button type="button" class="tab" role="tab" id="tab-paypal" aria-controls="panel-paypal" data-method="PayPal"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/paypal-logo-png_seeklogo-249214-300x300.png', '48px' ); ?> alt="" width="300" height="300">PayPal</button>
				<button type="button" class="tab" role="tab" id="tab-bank" aria-controls="panel-bank" data-method="Bank transfer"><span class="tab-icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"></path></svg></span>Bank transfer</button>
				<?php if ( $krm_stripe ) : ?><button type="button" class="tab" role="tab" id="tab-stripe" aria-controls="panel-stripe" data-method="Stripe"><span class="tab-icon">S</span>Stripe</button><?php endif; ?>
				<button type="button" class="tab" role="tab" id="tab-wu" aria-controls="panel-wu" data-method="Local - Western Union"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/western-union-new-20232644.logowik.com_.webp', '48px' ); ?> alt="" width="866" height="650">Local transfer</button>
				<button type="button" class="tab" role="tab" id="tab-mg" aria-controls="panel-mg" data-method="Local - MoneyGram"><img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/money-gram-1024x642.png', '48px' ); ?> alt="" width="1024" height="642">MoneyGram</button>
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
			<?php if ( $krm_stripe ) : ?>
			<div class="panel" role="tabpanel" id="panel-stripe" aria-labelledby="tab-stripe" hidden><p class="krm-admin-only">Only administrators see this option until Stripe is connected.</p><h2>Give securely with Stripe</h2><p>Stripe checkout is not yet connected. Add the verified Stripe account configuration before enabling this option.</p><p class="note"><span>No payment details were supplied in the source PDF, so this option is intentionally unavailable.</span></p><button class="button button-primary" type="button" disabled>Stripe setup required</button></div>
			<?php endif; ?>

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
				<div data-krm-for="gift">
				<p style="margin: 0"<?php echo $krm_gok ? ' hidden' : ''; ?>><?php krm_t( 'notify.text' ); ?></p>
				<div class="hero-actions"<?php echo $krm_gok ? ' hidden' : ''; ?>>
				<button type="submit" form="gift" class="button button-primary" id="notify-link"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M22 6l-10 7L2 6"></path></svg><?php krm_t( 'notify.email_button' ); ?></button>
				<a class="button button-secondary" href="<?php echo esc_url( krm_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php krm_t( 'notify.whatsapp_button' ); ?></a>
				</div>
				<?php krm_form_messages( 'gift', krm_fill( krm_c( 'notify.success' ) ), krm_fill( krm_c( 'notify.error' ) ) ); ?>
				</div>
			</div>
			</div>
		</div>
		</div>
	</section>
	<?php endif; ?>

	<?php
	$krm_proj = array(
		'current'  => krm_projects( 'current' ),
		'upcoming' => krm_projects( 'upcoming' ),
	);
	$krm_none = ! array_filter( array_merge( $krm_proj['current'], $krm_proj['upcoming'] ), function ( $p ) { return empty( $p['placeholder'] ); } ); // phpcs:ignore Generic.Formatting.MultipleStatementAlignment
	if ( ! krm_show_placeholders() ) {
		$krm_proj = array_filter( $krm_proj ); // Visitors: no empty tab; no section when there are no projects.
	}
	$krm_tabs = count( $krm_proj ) > 1;
	if ( $krm_proj ) :
		?>
	<section class="section" id="projects" aria-labelledby="projects-heading">
		<div class="wrap">
		<div class="section-head center">
			<?php krm_admin_only_note( $krm_none ); ?>
			<p class="eyebrow"><?php krm_t( 'projects.eyebrow' ); ?></p>
			<h2 id="projects-heading"><?php krm_h( 'projects.heading' ); ?></h2>
			<?php if ( krm_show_placeholders() || ! krm_is_placeholder( krm_c( 'projects.intro' ) ) ) : ?>
			<p><?php krm_t( 'projects.intro' ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $krm_tabs ) : ?>
		<div class="ministry-filter" role="tablist" aria-label="<?php echo esc_attr( wp_strip_all_tags( krm_fill( krm_c( 'projects.heading' ) ) ) ); ?>" data-krm-tabs data-param="projects">
			<?php foreach ( $krm_proj as $krm_key => $krm_items ) : ?>
			<button type="button" role="tab" id="tab-<?php echo esc_attr( $krm_key ); ?>-projects" aria-controls="panel-<?php echo esc_attr( $krm_key ); ?>-projects" aria-selected="<?php echo 'current' === $krm_key ? 'true' : 'false'; ?>" data-tab="<?php echo esc_attr( $krm_key ); ?>"<?php echo 'current' === $krm_key ? '' : ' tabindex="-1"'; ?>><?php krm_t( 'projects.' . $krm_key . '_tab' ); ?></button>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		<?php foreach ( $krm_proj as $krm_key => $krm_items ) : ?>
		<div id="panel-<?php echo esc_attr( $krm_key ); ?>-projects"<?php if ( $krm_tabs ) : ?> role="tabpanel" aria-labelledby="tab-<?php echo esc_attr( $krm_key ); ?>-projects"<?php endif; ?>>
			<?php if ( ! $krm_tabs ) : ?>
			<h3 class="visually-hidden"><?php krm_t( 'projects.' . $krm_key . '_tab' ); ?></h3>
			<?php endif; ?>
			<?php if ( $krm_items ) : ?>
			<ul class="ministry-grid">
				<?php foreach ( $krm_items as $krm_i => $krm_p ) : ?>
				<li><?php get_template_part( 'template-parts/project-card', null, array( 'project' => $krm_p, 'index' => $krm_i ) ); ?></li>
				<?php endforeach; ?>
			</ul>
			<?php else : ?>
			<p class="krm-note"><?php krm_t( 'projects.empty_' . $krm_key ); ?></p>
			<?php endif; ?>
		</div>
		<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<section class="section section-soft">
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

	<section class="section">
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
