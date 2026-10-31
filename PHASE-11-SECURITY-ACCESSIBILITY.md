# Kingdom Revival Ministries: Phase 11 security and accessibility

**Result: an audit of every input, output, form and admin action found the theme already sound. It found two real
security gaps, both now closed: any Contributor could download every form submission and every donor's details as
CSV, and bots could post to the forms without loading a page. An automated accessibility audit (axe-core, WCAG 2.1
A/AA plus best practices) now finds nothing on any WordPress page, at desktop or phone width, except three decorative
numbers on About that are left as designed (see Decisions). The original design had 30–40 findings across the same
pages. Every change is invisible. The page and interaction diffs are the same as after Phase 10, and none of the
remaining differences come from this phase's changes.**

Theme version 1.7.0. Code: [inc/security.php](theme/kingdom-revival/inc/security.php) (new), plus small changes in
[inc/forms.php](theme/kingdom-revival/inc/forms.php), [inc/donations.php](theme/kingdom-revival/inc/donations.php),
[inc/enqueue.php](theme/kingdom-revival/inc/enqueue.php), [assets/js/site.js](theme/kingdom-revival/assets/js/site.js),
[assets/js/contact.js](theme/kingdom-revival/assets/js/contact.js), [assets/js/partner.js](theme/kingdom-revival/assets/js/partner.js)
and six templates. New QA tool: [tools/visual-qa/a11y_check.js](tools/visual-qa/a11y_check.js).

(Phase 10, performance, was done in another session: `inc/performance.php`, theme 1.6.0. It has no report file.)

## Security

### What the audit found already in place

| Area | State |
|---|---|
| Input sanitization | Every `$_GET`/`$_POST`/`$_SERVER` read is unslashed and sanitized by type (`sanitize_text_field`, `sanitize_email`, `absint`, `esc_url_raw` with allowed schemes, `wp_kses` with a short inline-tag list). Settings and page content are sanitized against their schemas, so unknown keys are dropped. |
| Output escaping | Templates print through `esc_html`/`esc_attr`/`esc_url` or the `krm_t/h/u` helpers, which escape. The few raw `echo`s are annotated and print fixed markup, escaped helper output or core oEmbed HTML. |
| Nonces | Every admin save (page content, Site text, ministry/story/sermon/event fields, Settings via `options.php`, Setup AJAX, both CSV exports) checks a nonce. |
| Capability checks | Settings, Site text and Setup need `manage_options`. Meta box saves check `edit_post`. |
| Forms | Honeypot, minimum time on form, 5 posts per visitor per 10 minutes, link-stuffing goes to "possible spam" without email, nonce for logged-in users, field length limits, email header injection blocked (Reply-To name stripped of line breaks, address validated), CSV formula injection prefixed. |
| Donations | Each PayPal notification is verified with PayPal and the receiving account. No card data. |
| Front-end JS | No user or URL input reaches `innerHTML`. |

### Fixed

1. **Contributors and Authors could read donor and visitor records.** Both CSV exports checked only `edit_posts`,
   which Contributors have. Spam-flagged submissions (drafts) also showed their titles, which include the person's
   name, in the list. The Submissions and Donations types now map `edit_posts` to `edit_others_posts`, and both
   exports check that capability. Verified with a temporary user of each role on the local site:

   | Role | Submissions list | Open one | Export | Donations list |
   |---|---|---|---|---|
   | Contributor | no | no | no | no |
   | Author | no | no | no | no |
   | Editor | yes | yes | yes | yes |
   | Administrator | yes | yes | yes | yes |

2. **Bots could skip the page.** The time-on-form check used a number the browser sends, so a script posting straight
   to `admin-ajax.php` could just send a large one. Each page now carries a token signed with the site's secret keys
   (`KRM_CONFIG.formToken`). Contact, partner and newsletter posts are refused without a valid one. The token does not
   expire, so pages served from a cache keep working. If someone does see "Please reload the page and try again", the
   page is from a cache made before this update; clearing the cache fixes it.

### Added hardening (`inc/security.php`)

- Headers on front-end pages: `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`,
  `Referrer-Policy: strict-origin-when-cross-origin`,
  `Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()`. To change or remove them, use the filter
  `krm_security_headers`. PayPal checkout is unaffected, because it is a normal page change, not an embedded frame.
- No user-name lookup for visitors: `/?author=1` now redirects to the home page instead of to `/author/<login>/`,
  and the REST users list needs a login.
- Links that open in a new tab from formatted content fields get `rel="noopener"`.

### Verified (local site, curl)

- Headers present on `/about/`.
- `/?author=1` → 301 to `/`. REST users list returns `rest_no_route`.
- Form post without a token → 400. Forged token → 400. Token from `/contact/` → 200 and saved. Honeypot filled → fake success, nothing saved.
- `krm_inline()`: `target="_blank"` links gain `rel="noopener"`, existing `rel` is kept, `javascript:` and `onclick`
  are stripped, and `<script>` is removed.
- The test submission and the test users were deleted afterwards.

## Accessibility

### Already in the design

Skip link to `#main`. Menu toggles are real buttons with `aria-expanded`, open by click, Enter or Space, close with
Esc, and return focus. Donation method tabs are a full tab widget (roving focus, arrow keys, Home/End). The photo
viewer and newsletter popup are modal dialogs that keep focus inside, close with Esc and return focus. Photos open
with Enter. `:focus-visible` outlines (3px). Labelled fields with `aria-invalid`. Live regions for form status and
the gift summary. `prefers-reduced-motion` handled. Alt text on every image (`image-alt` passes on all pages).

### Fixed (no visible change)

| Finding (axe rule) | Pages | Fix |
|---|---|---|
| Top bar outside any landmark (`region`) | all | Top bar is a labelled region ("Contact and giving") |
| Headings skip a level (`heading-order`) | Donate, 404, Ministries overview | Footer column headings and Donate's payment-method headings are `h2` (same CSS, selectors updated) |
| Box nested as a sidebar inside the article (`landmark-complementary-is-top-level`) | Stories with the give box | `[krm_give]` box is a `div role="note"` instead of `aside` |
| "Supporter" label 4.46:1 on red (`color-contrast`) | Partner | `#FFE1E1` → `#FFE3E3` (4.52:1). Below the pixel-diff threshold; Partner's diff is unchanged. |
| `aria-label` on a plain `div` | Home floating donate panel | `role="region"` added |
| Social icons without `aria-hidden` | Home top bar | `aria-hidden="true"` on the icons (the links carry the names) |
| Field errors not read with the field | Contact, Partner | While an error shows, the field points to it with `aria-describedby` |
| Decorative step numbers 01/02/03 read aloud | About | `aria-hidden="true"` |

### Verified

`node a11y_check.js` (new; axe-core 4.10.2 on both the original HTML and WordPress):

| Page | Original design (desktop) | WordPress before | WordPress after (1440 and 390) |
|---|---|---|---|
| Home | landmark-one-main 1, region 23 | region 1 | none |
| About | color-contrast 3, region 1 | color-contrast 3, region 1 | color-contrast 3 (decorative numbers, see Decisions) |
| Contact | region 1 | (timed out) | none |
| Donate | heading-order 1, region 1 | heading-order 1, region 1 | none |
| Partner | color-contrast 1, region 1 | color-contrast 1, region 1 | none |
| Blog | region 1 | region 1 | none |
| Story | landmark-complementary 1, region 1 | same | none |
| Ministry | region 1 | region 1 | none |
| Ministries overview | heading-order 1, region 1 | same | none |
| 404 | (no design page) | heading-order 1, region 1 | none |

On every page, the first Tab stop is the skip link, its target exists, and each of the first six Tab stops shows a
focus outline.

## Pixel parity

`npm run pages` and `npm run interactions` after the changes, compared with the Phase 10 run:

- Ministry-empty, Ministries overview, Partner tablet/mobile: 0%. Contact: 0.04–0.08% (the known sentence). Partner
  desktop: 0.063%, the same as Phase 10.
- Home (0.004–0.09%), Donate (0.15–0.26%), About, Ministry, Partner photo areas and five interaction states: the
  differences are inside photos and payment logos only (Media Library resized/WebP copies from Phase 10, or a remote
  photo that hadn't loaded yet). They vary from run to run. Checked side by side for Donate, the mobile menu, the
  floating donate panel and partner-church: the headings, menus and panels are identical.
- Blog and story: the known differences from the local database's live-like content.
- 19 of 30 interaction states are 0%.

## Not covered

- **Server and login security is outside a theme**: login attempt limits, two-factor login, a firewall, XML-RPC
  blocking, file-change scans and backups. Use the host or one security plugin. A Content-Security-Policy header was
  not added, because the design's inline styles and scripts would need `unsafe-inline`, which makes it of little use.
- axe checks contrast in the default state only. Hover states and text over photos weren't measured. Screen-reader
  testing (NVDA/VoiceOver) by a person was not done.
- The original HTML in `html/` was not changed. Its findings in the table are for comparison only.

## Decisions for you

1. **About's step numbers (01, 02, 03).** They are pale watermark numbers (1.18:1). They are now hidden from screen
   readers as decoration, which WCAG allows, but automated checkers will keep flagging them. To pass the checkers, they
   would need a darker grey (#8D94A9 or darker for 3:1). That is a visible design change, so it was not made.
2. **Who sees Submissions and Donations.** Now Editors and Administrators. If Donations should be Administrators only,
   that is a one-line change.
3. **Security plugin.** Choose one for the items under Not covered. If it sends the same headers, remove the theme's
   with `krm_security_headers`.

## Unchanged

How every page looks, form field names, the partnership names, the "Where it is needed most" option, and all editable
content and settings.
