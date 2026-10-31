# Kingdom Revival Ministries: Phase 9 donation system

**Result: the Donate page now takes real online gifts through PayPal's hosted checkout. Donors can give one-time or
monthly, choose a preset or custom amount, and pay by PayPal balance or card. Each payment is checked with PayPal
before it is saved as a Donation in WordPress. The donor is emailed a thank-you receipt, the ministry is notified,
and the donor returns to a thank-you view. No card data and no API keys touch WordPress. With default settings the
Donate page is pixel-identical to the design: 0% at desktop, tablet and mobile, and 0% in the three Donate
interaction states. Nothing is live yet: no PayPal account is connected (see Decisions).**

Theme version 1.5.0. Code: [inc/donations.php](theme/kingdom-revival/inc/donations.php) (new),
[template-parts/donate-thanks.php](theme/kingdom-revival/template-parts/donate-thanks.php) (new),
[assets/js/donate.js](theme/kingdom-revival/assets/js/donate.js), [page-templates/donate.php](theme/kingdom-revival/page-templates/donate.php),
[inc/content/donate.php](theme/kingdom-revival/inc/content/donate.php), [inc/settings.php](theme/kingdom-revival/inc/settings.php).

## The decision

Chosen with you: **PayPal hosted checkout**, account held by **the partner abroad** (PayPal and Stripe don't accept
Pakistan-based merchant accounts, as of October 2026), with **full** confirmation and receipts in WordPress.

Why hosted checkout: card entry happens on paypal.com, so the theme never handles card data. The design's own
"Give with PayPal" button stays exactly as it is, so nothing visible changes. Embedded card fields (PayPal's on-page
buttons, Stripe Elements) would have changed the page.

## What changed

| Feature | How |
|---|---|
| One-time gifts | `cmd=_donations` checkout with the amount, ministry and currency (USD) filled in |
| Monthly gifts | `cmd=_xclick-subscriptions`: a PayPal subscription charged monthly until cancelled |
| Preset / custom amounts, choice of ministry | Unchanged design form; its values go into the checkout link |
| Secure checkout | PayPal's own pages: PayPal balance, or debit/credit card without a PayPal account; mobile-friendly. Opens in the same tab so the donor comes back to the site. |
| Payment confirmation | PayPal Instant Payment Notification (IPN) to `/wp-admin/admin-post.php?action=krm_paypal_ipn`. Each message is sent back to PayPal (`_notify-validate`) and accepted only if PayPal answers VERIFIED and the receiving account is the one in Settings. If PayPal can't be reached, the handler answers 503 so PayPal resends later. |
| Records | Kingdom Revival → Donations (private): donor, email, amount, PayPal fee, one-time or monthly, ministry, country, status, transaction and subscription IDs, receipt sent. Read-only, with CSV export. Repeated notifications never create duplicates. Refunds and reversals change the status; a cancelled or ended monthly gift is marked. |
| Email receipts | Sent to the donor once the payment is Completed (a pending eCheck waits until it clears). Subject and text are editable in Settings → Online giving, with placeholders; can be turned off. PayPal also sends its own receipt. |
| Ministry notification | New donation, refund/reversal and cancelled monthly gift, sent to the form notification addresses |
| Thank-you view | `/donate/?thanks=1`: the Donate hero with thank-you wording, the design's gift summary ("$50 / month for …"), a note for monthly gifts and two buttons. All wording is editable (Page content → "Thank-you view"). Built only from the Donate page's existing styles; it carries noindex (core, Yoast and Rank Math filters) and the canonical is the Donate page. The newsletter popup is not shown over it. |
| Testing mode | Settings → PayPal mode: Sandbox sends checkout and verification to sandbox.paypal.com. Sandbox gifts are marked "sandbox test". |

Without a PayPal account email in Settings, the site behaves exactly as before: the PayPal.me link, one-time only,
nothing recorded.

## Verification

All on the local WordPress 7.1.4 site.

- **Pixels (default settings).** Donate is 0% at all three widths; Home and Partner are also 0% at all three widths.
  Contact shows its known one-sentence difference (0.04–0.08%). Interactions: donate-bank-tab, donate-prefill and
  donate-other are 0%, and the 7 menu interactions re-run after the `site.js` change are 0%.
  About (0–1.7%, different every run) and Ministry (2–12%, different every run) differ only where a remote photo
  hadn't loaded in one of the two screenshots. The layout is identical, and Phase 9 touches neither page.
  Blog and story diffs are the known ones from the local database's live-like content (see Phase 7).
- **Forged notification.** A POST to the endpoint made up to look like a $999 PayPal payment: PayPal's sandbox
  answered INVALID and nothing was saved.
- **Recording logic.** Run against the real code with email captured instead of sent:
  - one-time $50, Completed: saved, receipt sent, ministry notified;
  - the same notification again: no duplicate, no second receipt;
  - monthly $25, Pending then Completed: saved once, receipt only after Completed;
  - an unknown fund name: falls back to "Where it is needed most";
  - refund of the $50: status Refunded, ministry notified;
  - subscription cancel: monthly gift marked ended, ministry notified;
  - subscr_signup: ignored.
  The receipt text was checked for correct placeholders. Test data was deleted and settings restored afterwards.
- **PayPal accepts the links.** The one-time and monthly links the page builds in Sandbox mode were requested from
  sandbox.paypal.com. The one-time link redirects (307) to PayPal's donate checkout with every value kept, and the
  monthly link returns the subscription checkout (200).
- **Thank-you view.** Renders with noindex and canonical /donate/. Fund names that aren't real options are dropped,
  and only a number is shown as the amount. Screenshots checked at 390px and 1440px.

## Fixed along the way

- The ministry's email subjects read "Private: $50 — …", because WordPress adds that prefix to private titles. They now use the raw title.
- `donate.js` already sent donors back to `/donate/?thanks=1`, but nothing handled that address; it now shows the thank-you view.

## Not covered

- **No real or sandbox payment was completed end to end.** That needs a PayPal sandbox account, and PayPal's
  notifications can't reach a localhost site. Do one sandbox gift on the staging or live server before launch
  (steps in readme.txt → Online giving).
- PayPal's classic checkout and IPN are older PayPal products. PayPal still runs them and they need no API keys, but if
  PayPal ever retires them, the theme would need to move to PayPal's REST API (client ID and secret).
- SureRank has no noindex filter in the theme. The thank-you view still has a canonical pointing to /donate/.
- The design's Giving FAQ answer "How do I give monthly?" still says to use a bank standing order or to give through
  PayPal each month (see Decisions).
- `kingdom-revival.zip` was rebuilt during this phase, but another session's performance changes
  (`inc/performance.php` etc.) were landing at the same time. Rebuild the zip once that work is finished.

## Decisions for you

1. **Connect the account.** Who holds the PayPal Business account (the partner abroad), and its email. It must be a
   Business account that can receive payments. Then: test in Sandbox mode, switch to Live.
2. **Receipt wording.** The default receipt makes no tax-deductibility claim. If the partner entity is a registered
   charity (for example US 501(c)(3)), add its name and tax ID and the standard statement to the receipt text.
3. **FAQ update.** Change "How do I give monthly?" to point to the Monthly option and PayPal once live
   (an editable FAQ item; left as in the design).
4. **"Let us know" step.** For PayPal gifts it's no longer needed, since the gift is recorded automatically. It is still
   useful for bank, Western Union and MoneyGram, so it was left unchanged.

## Unchanged

The Donate page markup, the design's form field names, the "Where it is needed most" option, bank / Western Union /
MoneyGram details, and every other page.
