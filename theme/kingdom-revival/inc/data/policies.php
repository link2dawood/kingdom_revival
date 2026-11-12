<?php
/**
 * The policy pages linked in the footer (theme 1.23.0): slug => [ title, footer link text, content ].
 * Setup creates them, and the theme's 1.23.0 update step adds any that are missing; after that they are ordinary pages
 * (Pages → edit), so nothing here overwrites a page once it exists. {email}, {phone} and {address} become the contact
 * details from Kingdom Revival → Settings when the page is created.
 *
 * DUMMY TEXT: a plain-language starting point written for this site (PayPal's hosted checkout, forms saved in WordPress and
 * emailed to the ministry). It is not legal advice: the ministry should review every page, and have it checked for the
 * laws that apply to it, before launch.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	'privacy-policy'           => array(
		'Privacy Policy',
		'Privacy Policy',
		'<p><em>Last updated: November 2026</em></p>
<p>Kingdom Revival Ministries (“we”, “us”) respects your privacy. This page explains what information we collect through this website, why we collect it and how we look after it.</p>
<h2>What we collect</h2>
<ul>
<li><strong>Forms.</strong> When you send a contact message, prayer request, partnership sign-up or donor details, we receive the information you type in, such as your name, email address, phone number, country and message.</li>
<li><strong>Donations.</strong> Gifts are made on PayPal’s secure checkout. We never see or store your card or bank details. PayPal tells us your name, email address, the amount and the date so that we can send a receipt and keep accurate records.</li>
<li><strong>Newsletter.</strong> If you sign up, we keep your email address to send you ministry news. Every email has a way to unsubscribe.</li>
<li><strong>Basic website data.</strong> Like most websites, our server keeps short technical logs (such as your IP address and browser type) to keep the site secure. We remember your light or dark display choice in your own browser.</li>
</ul>
<h2>How we use it</h2>
<p>We use your information only to answer you, pray for you, process and receipt your gifts, send updates you asked for, and keep our records. We do not sell, rent or trade your information.</p>
<h2>Prayer requests</h2>
<p>Prayer requests are read by our pastors and prayer team. If you tick “Keep my request private”, it is shared only with our leadership.</p>
<h2>Who we share it with</h2>
<p>We share information only with the services that help us run this website and receive gifts (our web host, our email service and PayPal), and only as far as they need it, or when the law requires it.</p>
<h2>How long we keep it</h2>
<p>We keep form messages and donation records for as long as we need them for the purposes above and for our accounts.</p>
<h2>Your choices</h2>
<p>You can ask to see, correct or delete the information we hold about you, or to stop receiving emails, by writing to <a href="mailto:{email}">{email}</a>.</p>
<h2>Contact</h2>
<p>Kingdom Revival Ministries, {address}. Email <a href="mailto:{email}">{email}</a> · Phone {phone}.</p>',
	),
	'terms-and-conditions'     => array(
		'Terms & Conditions',
		'Terms & Conditions',
		'<p><em>Last updated: November 2026</em></p>
<p>By using this website you agree to these terms. If you do not agree, please do not use the site.</p>
<h2>About this website</h2>
<p>This website is run by Kingdom Revival Ministries, a Christian ministry based in {address}. It tells the story of our work and lets you contact us, pray with us, partner with us and give.</p>
<h2>Content</h2>
<p>The text, photos and videos on this site belong to Kingdom Revival Ministries or are used with permission. You may share our pages and stories for non-commercial purposes with a link back to this site. Please ask us before using our photos elsewhere: many show families and children whose dignity and safety we protect.</p>
<h2>Accuracy</h2>
<p>We work to keep the information here accurate and up to date, but we cannot promise that every detail is complete or current. Figures and stories are shared to show the work God is doing and may be rounded or summarised.</p>
<h2>Giving</h2>
<p>Donations are made through PayPal and are also covered by our <a href="/donation-refund-policy">Donation &amp; Refund Policy</a>.</p>
<h2>Links to other websites</h2>
<p>We are not responsible for the content of other websites we link to, such as PayPal, Facebook or YouTube.</p>
<h2>Acceptable use</h2>
<p>Please do not misuse the site, send unlawful or abusive content through our forms, or try to interfere with how the site works.</p>
<h2>Changes</h2>
<p>We may update these terms from time to time. The date at the top shows the latest version.</p>
<h2>Contact</h2>
<p>Questions about these terms: <a href="mailto:{email}">{email}</a>.</p>',
	),
	'donation-refund-policy'   => array(
		'Donation & Refund Policy',
		'Donation/Refund Policy',
		'<p><em>Last updated: November 2026</em></p>
<p>Thank you for giving to Kingdom Revival Ministries. This policy explains how your gifts are received and used, and what to do if something goes wrong.</p>
<h2>How gifts are made</h2>
<p>All online gifts are made on PayPal’s secure checkout, as a one-time or a monthly gift. We never see or store your card details. You receive a confirmation from PayPal and a receipt from us by email.</p>
<h2>How gifts are used</h2>
<p>When you give to a particular ministry or project, we use your gift for that work. If a project is fully funded, or can no longer go ahead, we use the gift where the need is greatest in a similar ministry. Gifts to “Where it is needed most” support the whole ministry.</p>
<h2>Monthly gifts</h2>
<p>You can change or cancel a monthly gift at any time in your PayPal account (Settings → Payments → Automatic payments), or by writing to us and we will help.</p>
<h2>Refunds</h2>
<p>If you made a mistake, for example the wrong amount or a gift made twice, contact us within 30 days of the gift at <a href="mailto:{email}">{email}</a> or {phone}, with the date, amount and the name or email on the gift. We will refund it through PayPal. Gifts that have already been sent to a project may not be refundable after 30 days.</p>
<h2>Tax receipts</h2>
<p>Kingdom Revival Ministries is based in Pakistan. Whether your gift is tax-deductible depends on the laws of your own country; please ask your tax adviser.</p>
<h2>Questions</h2>
<p>Email <a href="mailto:{email}">{email}</a> · Phone {phone}.</p>',
	),
	'financial-accountability' => array(
		'Financial Accountability',
		'Financial Accountability',
		'<p><em>Last updated: November 2026</em></p>
<p>Every gift entrusted to Kingdom Revival Ministries is a responsibility before God and before the people who give. These are the commitments we keep.</p>
<h2>Our commitments</h2>
<ul>
<li><strong>Gifts go where you choose.</strong> A gift for a ministry or project is used for that work, as set out in our <a href="/donation-refund-policy">Donation &amp; Refund Policy</a>.</li>
<li><strong>Careful records.</strong> Every gift is recorded and receipted, and every payment is recorded with its purpose.</li>
<li><strong>More than one pair of eyes.</strong> Spending is approved by the ministry’s leadership, not by one person alone.</li>
<li><strong>Help that reaches people.</strong> We buy locally where we can, pay families’ debts directly to the kiln owner, and document projects with photos and reports.</li>
<li><strong>Safe online giving.</strong> Gifts are made through PayPal; we never handle card details.</li>
</ul>
<h2>Reporting</h2>
<p>We share stories and figures from the field on our <a href="/our-impact">Our Impact</a> page and in our updates to partners. Partners and donors may ask for a report on how their gifts have been used.</p>
<h2>Questions or concerns</h2>
<p>If you have a question about how a gift was used, or a concern about our finances, please write to <a href="mailto:{email}">{email}</a>. We will answer openly.</p>',
	),
);
