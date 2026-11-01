# Kingdom Revival Ministries: project report (all phases)

All phase reports in one file, in phase order. Each phase keeps its original text; headings are one level lower.

## Contents

- [Kingdom Revival Ministries: Phase 1 analysis (HTML → WordPress)](#kingdom-revival-ministries-phase-1-analysis-html--wordpress)
- [Kingdom Revival Ministries: Phase 3 visual QA (HTML design vs WordPress)](#kingdom-revival-ministries-phase-3-visual-qa-html-design-vs-wordpress)
- [Kingdom Revival Ministries: Phase 4 page conversion audit](#kingdom-revival-ministries-phase-4-page-conversion-audit)
- [Kingdom Revival Ministries: Phase 5 editable content](#kingdom-revival-ministries-phase-5-editable-content)
- [Kingdom Revival Ministries: Phase 6 technical SEO (URLs and metadata)](#kingdom-revival-ministries-phase-6-technical-seo-urls-and-metadata)
- [Kingdom Revival Ministries: Phase 7 structured data (schema markup)](#kingdom-revival-ministries-phase-7-structured-data-schema-markup)
- [Kingdom Revival Ministries: Phase 8 keyword grouping and topical authority](#kingdom-revival-ministries-phase-8-keyword-grouping-and-topical-authority)
- [Kingdom Revival Ministries: Phase 9 donation system](#kingdom-revival-ministries-phase-9-donation-system)
- [Kingdom Revival Ministries: Phase 11 security and accessibility](#kingdom-revival-ministries-phase-11-security-and-accessibility)
- [Kingdom Revival Ministries: Phase 13 final delivery](#kingdom-revival-ministries-phase-13-final-delivery)

---

## Kingdom Revival Ministries: Phase 1 analysis (HTML → WordPress)

Source: `html.zip`, extracted to `html/` (8 HTML pages, 3 JS/CSS files, 1 logo).
Nothing in `html/` has been modified.

---

### 1. File inventory

| File | Size | What it really is |
|---|---|---|
| `index.html` | 270 KB | **Not plain HTML.** A self-unpacking bundle: a gzip/base64 manifest holding React 18.3.1, React-DOM, a "dc-runtime" template engine (`app.js`), the logo and one Plus Jakarta Sans woff2. The real page is a JSON-encoded template (`{{placeholders}}`, `<sc-for>` loops) plus a `Component` class with the data. Shows "This page requires JavaScript" without JS. It was decoded for this analysis. |
| `about.html` | 28 KB | Static. Shared `site.css` + 68-line inline `<style>`. |
| `all-ministries.html` | 29 KB | **One template for all 12 ministries.** Empty shell filled by inline JS from `?ministry=<slug>`. With no slug it shows "Choose a ministry to explore". |
| `blog.html` | 23 KB | Empty shell. Featured post, category chips, live search and grid are rendered by JS from `assets/blog-data.js`. |
| `blog-post.html` | 32 KB | Empty shell filled by JS from `?post=<slug>`. Includes a reading-progress bar, share links, prev/next and related posts. |
| `contact.html` | 20 KB | Static. Form validates, then opens a `mailto:` (doesn't send from the server). |
| `donate.html` | 39 KB | Static + JS: gift builder, payment-method tabs, copy-to-clipboard bank details, prefill from `?amount=&frequency=&for=&method=`. |
| `partner.html` | 42 KB | Static + JS: partnership type picker, conditional fields, `mailto:` or an optional `PARTNER_ENDPOINT` (currently empty). |
| `assets/site.css` | 23 KB | Shared design system (tokens, header/nav, buttons, page hero, give card, give band, footer, lightbox, floating donate, newsletter). |
| `assets/site.js` | 30 KB | Shared behaviour (see §5). An identical copy is inlined into the homepage. |
| `assets/blog-data.js` | 18 KB | `window.KRM_POSTS`: 7 posts in a custom block format. |
| `assets/krm-logo.png` | 14 KB | Logo/favicon (byte-identical to the one in the homepage bundle). |

---

### 2. Pages and content record

| Page | `<title>` | Meta description | H1 |
|---|---|---|---|
| Home | Kingdom Revival Ministries — Home | *(none)* | Sharing the Hope of Jesus Christ with the Unreached & Vulnerable |
| About | About Us \| Kingdom Revival Ministries | Kingdom Revival Ministries is a Gospel-centred ministry serving Pakistan's forgotten — preaching Christ, planting churches and caring for orphans, widows and brick kiln families. | A Gospel-centred ministry serving *Pakistan's forgotten* |
| Ministry (×12) | `{Ministry} \| Kingdom Revival Ministries` (set by JS) | "Learn about this ministry…" (generic) | `{Ministry title}` |
| News & Blogs | News & Blogs \| Kingdom Revival Ministries | Stories of hope from the field — read how the Gospel and practical care are changing lives across Pakistan. | Stories of hope *from the field* |
| Blog post (×7) | `{Post title} \| Kingdom Revival Ministries` (set by JS) | post excerpt (set by JS) | `{Post title}` |
| Contact | Contact Us \| Kingdom Revival Ministries | Get in touch with Kingdom Revival Ministries in Faisalabad, Pakistan — for partnership, giving, prayer requests or visits. | We would love to *hear from you* |
| Donate | Donate \| Kingdom Revival Ministries | Give to Kingdom Revival Ministries by PayPal, bank transfer, Western Union or MoneyGram and bring the hope of Jesus to Pakistan's forgotten. | Your gift brings *hope* to Pakistan's forgotten |
| Partner | Become a Partner \| Kingdom Revival Ministries | Become a partner with Kingdom Revival Ministries — pray, give monthly, sponsor a ministry or partner as a church… | Join the family carrying hope to *Pakistan's forgotten* |

#### Section outline per page

**Home** (`#top` … `#contact`): top bar (phone, email, 3 social icons, Donate Now) · header + nav · hero with background video `a.mp4` + donation card (5 amounts) · overlapping stats bar (5 stats) + "Our prayer" panel · About (`#about`, 2-image collage, 4 checks) · **12-ministry explorer** (`#all-ministries`, click a list to swap the image/number/title/desc) · 6 cause cards (`#ministries`) · Field stories (`#field-stories`, video + 3-photo gallery) · Pray/Give/Go · Founder (`#founder`) · Matthew 28:19 CTA banner · homepage-own floating donate panel · footer (`#contact`).

**About:** page hero · story + 3-image collage + "12 ministries" badge · Mission/Vision cards · "Word and deed" 4 cards · 5-stat band · Founder (`#founder`) · 3 ways (Pray/Give/Go with links) · 8-photo gallery · give band + give card.

**Ministry:** hero (first photo as background) · "Ministry focus" + "How this ministry serves" panels · photo gallery (0–12 photos, empty-state text) · give band with a ministry-specific give card.

**News & Blogs:** hero · featured (newest) post · toolbar with search · category chips (All, Scripture, Children, Church, Care, Prayer) + result count · post grid · empty state · 3-item impact strip · give band.

**Blog post:** progress bar · hero (cover bg, tag, meta, ministry link) · article body (lead paragraph, h2, quote, image+caption, check list, inline give box, gallery) · share (WhatsApp/Facebook/X/copy) · author box · prev/next · sidebar (give card, ministry card, prayer card) · give band · 3 related posts.

**Contact:** hero · 3 contact cards · form (name, email, phone, topic select, message) · side cards (prayer → preselects topic, donate, office location) · Google Maps iframe.

**Donate:** hero · step 1 gift form (frequency, 6 amounts + Other, fund select of 13) + live summary · step 2 tabs: PayPal (paypal.me/Givetotheneedy7/{amount}USD), Bank transfer (8 rows, copy buttons), Western Union, MoneyGram · step 3 "Let us know" (prefilled mailto, WhatsApp) · 4 impact cards · Giving FAQ (4 `<details>`).

**Partner:** hero · 3 "why" items · 4 option cards (Prayer / Monthly — featured / Sponsor / Church) · 3 steps · sign-up form with conditional amount/ministry/church fields + thank-you state · Partnership FAQ (5) · Philippians 1:3–5 verse band.

#### Data sets embedded in JS (will become WordPress content)

- **12 ministries** (`all-ministries.html`): slug, title, description, focus, 3 points, photo list. Groups (from the nav): *Children & Families* (Children Ministry, Orphan & Children Care, Brick Kiln Children, Widows Ministry), *Freedom & Care* (Freedom from Slavery, Free Medical Camps, Clean Water Project), *Gospel & Church* (Outreach, Urdu Bibles, Baptisms, Deliverance & Healing, Leadership & Discipleship). The homepage explorer holds its own copy (short desc + image) in a **different order**.
- **7 blog posts** (`blog-data.js`): slug, title, category, ministry slug/name, date (2026-07-24 → 2026-10-14), readTime, cover, excerpt, body blocks (`p, h2, quote, img, gallery, list, give`).
- **6 homepage cause cards**, **5 stats**, **4 about checks**: in the homepage `Component` class.

---

### 3. Shared components

| Component | Inner pages | Homepage |
|---|---|---|
| Skip link | yes | **no** |
| Top bar | phone, email, Donate Now → donate page | phone, email, **Facebook/YouTube/Instagram icons (all link to `#contact`, i.e. placeholders)**, Donate Now → `#donate` |
| Header/brand | `.site-header` / `.brand` classes | same look, written as inline styles |
| Main nav | identical 3-level menu (Home, Ministries ▸ 3 groups ▸ 12, About, News & Blogs, Contact) | same markup, Home/All ministries use `#top`/`#all-ministries` |
| Footer | 4 columns: brand, Quick Links (6), **Ministries (5 + "All ministries →")**, Contact | 3 columns: brand (logo in white circle), Quick Links (**"Latest News & Blogs"**, different order), Contact. No Ministries column. Tagline lacks "across Pakistan". |
| Give card (`form[data-give]`) | generated by `KRM.initGive` (6 amounts incl. $500, one-time/monthly) | homepage hero card is different (5 amounts, no frequency) |
| Floating donate button | injected by `site.js` | homepage has its own React version (5 amounts) |
| Newsletter popup | `site.js`, after 3 s, snooze 7 days (localStorage) | same |
| Lightbox | `site.js`, for any `[data-gallery] img` / `img[data-zoom]` | same |
| Give band | `.give-band` section with bg image + give card | — |
| Page hero + breadcrumb | `.page-hero` | — |

Header and footer markup is byte-identical across all 6 inner pages (only `aria-current` / `is-current` differ, plus the progress bar on blog-post).

---

### 4. CSS architecture

- **Tokens** (`site.css :root`): `--navy #0C1C4F`, `--navy-deep #081030`, `--red #D4070B`, `--red-dark #B10609`, `--orange #F5A04A`, `--ink #141B33`, `--muted #5A6275`, `--soft #F4F6FB`, `--line #E3E7F0`, `--radius 12px`, `--wrap 1200px`. The homepage uses hard-coded hex values and a 1240px container, plus extra colours (`#E18426` orange button, `#08133A` footer, `#1A2550`).
- **Layering:** `site.css` (shared) → per-page inline `<style>` (30–97 lines each) → the homepage, which is ~95% inline `style=""` attributes plus 4 `<style>` blocks (field-stories classes, hover classes `.k-y/.k-o/.k-g/.k-card`, and duplicated nav/lightbox/newsletter CSS).
- **Breakpoints:** 900px (nav → hamburger; hover-open at ≥901px), plus page-specific 1000 / 960 / 820 / 760 / 680 / 640 / 560 / 520px. The homepage relies mostly on flex-wrap/`clamp()`/`auto-fit` rather than media queries.
- **Motion:** card hover lifts, `lb-in`, `lb-spin`, `float-in`, `nl-up`; `prefers-reduced-motion` is respected in `site.css`.
- **Typography:** Plus Jakarta Sans 400–800. Inner pages load it from Google Fonts CSS. The homepage inlines `@font-face` from fonts.gstatic.com, and the bundle carries one latin woff2 (27 KB).

---

### 5. JavaScript inventory

| Script | Behaviour | WordPress notes |
|---|---|---|
| `site.js` nav | click/hover submenus, edge-aware flyouts, Esc, mobile toggle | Keep. The menu walker must output the same `button.sub-toggle` / `ul.submenu` markup. |
| `site.js` give cards | builds the form, redirects to `donate.html?amount=…&frequency=…&for=…#give` | `DONATE_URL` is hard-coded `./donate.html` → pass the real URL via `wp_localize_script`. |
| `site.js` lightbox | zoom/pan/pinch/rotate/fullscreen/thumbs/keyboard | Keep as-is. |
| `site.js` floating donate | skipped when `location.pathname` ends in `donate.html` | **Breaks in WP** (`/donate/` won't match), so the donate page check needs fixing. |
| `site.js` newsletter | mailto fallback; `NEWSLETTER_ENDPOINT` empty | Point it at a WP handler (or keep mailto). |
| Homepage React runtime | explorer, amount picker, floating panel | **Drop React entirely**; render in PHP and replace the 3 interactions with ~60 lines of vanilla JS. |
| `all-ministries` inline | renders the ministry from the query string | Becomes server-rendered PHP (`single-ministry.php`). |
| `blog` inline | featured, chips, live search | Render server-side. Keep chips + instant search as progressive enhancement. |
| `blog-post` inline | renders post, share, prev/next, related, progress bar | Server-rendered. Keep the progress bar + copy-link JS. |
| `contact` / `partner` / `donate` inline | validation, mailto, tabs, copy, prefill | Keep the UI JS. Contact/partner submission moves to a server-side handler (see Q2). |

---

### 6. Assets

- **Local:** `krm-logo.png` only, plus the font.
- **Remote (hot-linked from the live site):** ~44 literal image URLs + ~70 more built in the ministry script, all under `kingdomrevivalministries.com/wp-content/uploads/2026/07|08/`, **and several under `/uploads/elementor/thumbs/`**. Those are Elementor-generated crops, and they can disappear if Elementor is deactivated or its cache is cleared.
- **Video:** `uploads/2026/08/a.mp4` (hero background + field-stories player).
- **Payment logos:** PayPal, Western Union and MoneyGram PNGs from uploads.
- **Icons:** all inline SVG (Feather-style). No icon font.
- **Third-party:** Google Fonts, Google Maps embed, paypal.me, wa.me, Facebook/X share intents.

---

### 7. Link map

- Internal links are all relative `.html` (131 × `all-ministries.html?ministry=…`, 28 × `index.html`, 17 × `index.html#all-ministries`, etc.). All of them get rewritten to WordPress permalinks.
- Hash targets in use: `#main #top #about #all-ministries #ministries #field-stories #founder #donate #contact #give #support #join #options #contact-form`. All of them stay.
- Query-string contracts that must keep working: donate `?amount&frequency&for&method`, partner `?type`, newsletter `?newsletter=1`.
- No broken internal links found. The only dead links are the homepage social icons (`#contact`).

---

### 8. HTML → WordPress mapping

| Existing HTML | WordPress template | WordPress object | URL |
|---|---|---|---|
| `index.html` | `front-page.php` + `template-parts/home/*.php` | Static front page | `/` |
| `about.html` | `page-about.php` | Page "About" | `/about/` |
| `all-ministries.html?ministry={slug}` | `single-ministry.php` | CPT `ministry` (12 posts) + taxonomy `ministry_group` (3 terms) + meta (description, focus, points, gallery, give heading) | `/ministries/{slug}/` |
| `all-ministries.html` (no slug) | `archive-ministry.php` (the "Choose a ministry" state) | CPT archive | `/ministries/` |
| `blog.html` | `home.php` | Posts page "News & Blogs" | `/blog/` |
| `blog-post.html?post={slug}` | `single.php` | Standard posts (7) + categories (5) + meta `_krm_ministry` | `/blog/{slug}/` |
| `contact.html` | `page-contact.php` | Page "Contact" | `/contact/` |
| `donate.html` | `page-donate.php` | Page "Donate" | `/donate/` |
| `partner.html` | `page-partner.php` | Page "Become a Partner" | `/partner/` |
| shared header | `header.php` (+ `template-parts/header-home.php` if the homepage variant is kept) | Menu locations `primary`, `footer-quick`, `footer-ministries` | — |
| shared footer | `footer.php` (+ home variant) | Customizer: phone, email, address, socials | — |
| give band / page hero / give card | `template-parts/give-band.php`, `page-hero.php` | — | — |
| *(not designed)* | `404.php`, `search.php`, `archive.php`, `page.php`, `index.php` | built from existing components, no new design language | — |

Each `page-*.php` will also carry a `Template Name:` header, so renaming a slug won't break the page.

**Proposed theme structure:**

```
kingdom-revival/
  style.css  functions.php  screenshot.png
  header.php  footer.php  front-page.php  home.php  single.php
  single-ministry.php  archive-ministry.php  page.php  page-about.php
  page-contact.php  page-donate.php  page-partner.php  404.php  search.php  archive.php  index.php
  inc/  setup.php  enqueue.php  cpt-ministry.php  meta-boxes.php  customizer.php
        nav-walker.php  forms.php  seo.php  template-tags.php  seed-content.php
  template-parts/  home/  header-home.php  page-hero.php  give-band.php  post-card.php ...
  assets/ css/site.css  css/pages/*.css  js/site.js  js/pages/*.js  fonts/  img/krm-logo.png
```

Per-page inline `<style>` blocks move into `assets/css/pages/{page}.css`, loaded only on that page. They are copied verbatim, so the visuals stay identical.

---

### 9. Issues found in the source (kept as-is unless you say otherwise)

1. **Homepage needs JS to show anything.** This is bad for SEO and first paint, and is the main reason to render it in PHP.
2. **Homepage stat labels differ from About:** "Church planted included house churches" / "Bible distributed" vs "Churches planted incl. house churches" / "Bibles distributed".
3. **Homepage cause card "Freedom from Slavery"** uses the Deliverance & Healing photo (looks like a copy-paste slip).
4. Free Medical Camps, Clean Water Project and Outreach all share one generic photo. Medical Camps and Clean Water have **no gallery photos** (empty state shown).
5. Homepage explorer "Support this ministry" goes to the donate page **without** pre-selecting that ministry (inner pages do pre-select).
6. Homepage has **no meta description**. Ministry pages share one generic description.
7. Founder image has two conflicting `object-position` values; the second (`8% center`) wins.
8. "© 2026" is hard-coded. In WP it becomes the current year.
9. Contact, partner and newsletter don't actually submit; they open the visitor's email app.
10. Bank details (personal account name, CNIC, IBAN) are published on the donate page. That's your decision; in WP they'll live in editable settings rather than template code.

---

### 10. Decisions needed before Phase 2

1. **Homepage header/footer:** keep the homepage's own variant (social icons, 3-column footer), or use the inner-page header/footer everywhere?
2. **Forms:** send contact/partner/newsletter through WordPress (`wp_mail`, saved as entries, nonce + honeypot) with the same UI, or keep the current `mailto:` behaviour?
3. **Images:** import all remote images (including `elementor/thumbs` crops) into the new theme's media library during setup, or keep hot-linking?
4. **Is this replacing the live WordPress/Elementor install** at kingdomrevivalministries.com (same database), or going on a fresh install? This decides content import, redirects and the risk to existing uploads.
5. **Social links:** real Facebook/YouTube/Instagram URLs, or hide the icons until you have them?
6. **Source issues in §9 (items 2–5):** fix them, or keep them exactly as designed?

---

## Kingdom Revival Ministries: Phase 3 visual QA (HTML design vs WordPress)

**Result: the WordPress theme renders pixel-identical to the original HTML pages**, at desktop, tablet and
mobile widths, in 32 of 33 page comparisons and all 31 interaction states. The one exception is one
deliberate sentence on the Contact page (see below).

Tested on WordPress 7.1.4, PHP 8.4, Chrome (headless), theme `kingdom-revival` 1.1.0, with the content
created by the theme's Setup screen. Tooling and how to re-run it: [tools/visual-qa/](tools/visual-qa/).

### Method

- Each original page in `html/` and its WordPress URL were captured as full-page screenshots at 1440×900
  (desktop), 768×1024 (tablet) and 390×844 (mobile), then compared pixel by pixel (pixelmatch, threshold 0.1).
- Remote images and Google Fonts came from one local cache, so both sides used identical files. Videos were
  hidden (their frame timing is not repeatable); the poster/background behind them was compared.
- Interactive states were driven the same way on both sides and the visible viewport compared.
- Captures used `prefers-reduced-motion`, so they compare end states. Animations and transitions are covered
  by the theme shipping the design's own CSS/JS: `assets/css/site.css` is the original file (plus
  `object-fit: contain` on the two logo images, a no-op for the square logo, so an uploaded logo is not
  stretched); `assets/js/site.js` differs only in WordPress wiring (URLs, form posting, donate-page check).

### Page comparisons (% of pixels that differ)

| Page (HTML → WordPress) | Desktop | Tablet | Mobile | Page heights |
|---|---|---|---|---|
| `index.html` → `/` | 0 | 0 | 0 | equal |
| `about.html` → `/about/` | 0 | 0 | 0 | equal |
| `contact.html` → `/contact/` | 0.049 | 0.069 | 0.107 | equal |
| `donate.html` → `/donate/` | 0 | 0 | 0 | equal |
| `partner.html` → `/partner/` | 0 | 0 | 0 | equal |
| `blog.html` → `/blog/` | 0 | 0 | 0 | equal |
| `blog-post.html?post=learning-beside-the-kilns` → `/learning-beside-the-kilns/` | 0 | 0 | 0 | equal |
| `blog-post.html?post=prayer-for-the-sick-and-oppressed` (oldest, no "previous") | 0 | 0 | 0 | equal |
| `all-ministries.html?ministry=brick-kiln-children` → `/ministries/brick-kiln-children/` | 0 | 0 | 0 | equal |
| `all-ministries.html?ministry=free-medical-camps` (empty gallery) | 0 | 0 | 0 | equal |
| `all-ministries.html` (no ministry) → `/ministries/` | 0 | 0 | 0 | equal |

**Contact (intentional):** the form intro reads "Fill in the form and your message will go straight to our
team." instead of "…it will open in your email app, ready to send to our team." The WordPress form sends
the message itself (saved under Submissions and emailed), so the original sentence would be untrue. Nothing
else on the page differs.

### Interaction states (all 0%)

Desktop dropdown and nested flyout (inner and home headers) · mobile menu open and submenu expanded (home,
inner, tablet) · button and card hover states · home ministry explorer switching · home hero amount picker ·
home floating donation panel · inner-page floating donate panel (desktop, mobile) · newsletter popup
(desktop, mobile) · photo lightbox · donate payment tabs · donate prefill from
`?amount=&frequency=&for=&method=` · "Other" amount · give card frequency/amount · blog category filter,
live search and empty state · partner type switch and `?type=` prefill · contact and partner validation
errors · story page scrolled (reading progress bar).

### Fixed in this phase

The comparison found three places where the theme had departed from the design. All now match:

1. **Home navigation:** "Ministries" was highlighted on the home page, because the "All ministries" link
   (`/#all-ministries`) counted as the current page. The design highlights nothing there.
2. **Home stat labels:** the theme had used the About page's wording. The home page again shows its own
   labels ("Church planted included house churches", "Bible distributed"). They are separate settings
   (Kingdom Revival → Settings / Customizer → Impact numbers → "label (home page)"); the numbers stay shared.
3. **"Freedom from Slavery" card image:** restored to the design's photo (the Deliverance & Healing photo,
   which the design uses for both cards; noted in Phase 1 §9 item 3).

Also found and fixed in Phase 2: on WordPress 6.9+ the original theme let WordPress's block and global CSS
load on the design pages. Those pages now load only the design's CSS.

### Not covered

- Browsers other than Chrome (Firefox/Safari render through the same CSS but were not captured).
- Video playback (hidden on both sides; the same file and markup are used).
- Images imported into the Media Library: the test site kept the live-site image URLs, as the design does.
  After Setup's media import, images are served from the Media Library at full size with `srcset`, so the
  same photo is displayed; only the file URL changes.

---

## Kingdom Revival Ministries: Phase 4 page conversion audit

**Result: every page in the Phase 1 inventory exists in WordPress, renders through a theme template, and
matches the original page's content, headings, sections, images, buttons and links.** The only content
change is one sentence on the Contact page, explained below.

Checked on WordPress 7.1.4 with the content created by the theme's Setup screen. Each page was compared
against the original HTML page after the HTML page's own JavaScript had rendered it. Tool:
[tools/visual-qa/audit.js](tools/visual-qa/audit.js). Pixel comparisons are in
[Kingdom Revival Ministries: Phase 3 visual QA (HTML design vs WordPress)](#kingdom-revival-ministries-phase-3-visual-qa-html-design-vs-wordpress).

### Page inventory → WordPress

| HTML page (Phase 1) | WordPress template | WordPress URL | Pages |
|---|---|---|---|
| `index.html` | `front-page.php` + `template-parts/home/*` | `/` | 1 |
| `about.html` | `page-templates/about.php` | `/about/` | 1 |
| `contact.html` | `page-templates/contact.php` | `/contact/` | 1 |
| `donate.html` | `page-templates/donate.php` | `/donate/` | 1 |
| `partner.html` | `page-templates/partner.php` | `/partner/` | 1 |
| `blog.html` | `home.php` (posts page) | `/blog/` | 1 |
| `all-ministries.html` (no ministry chosen) | `archive-ministry.php` | `/ministries/` | 1 |
| `all-ministries.html?ministry={slug}` | `single-ministry.php` | `/ministries/{slug}/` | 12 |
| `blog-post.html?post={slug}` | `single.php` | `/{slug}/` | 7 |

All 26 URLs return 200, and every one of them is reachable by following links on the site (menus, footer,
cards, explorer, story grid). All 28 internal URLs linked from these pages return 200.

### What was compared, per page

| Check | Result |
|---|---|
| Heading outline (h1–h6, order and text) | identical on all 26 pages |
| Visible text, line by line | identical, except Contact (below) |
| Section order and element IDs used as link targets (`#give`, `#support`, `#founder`, …) | identical |
| Images (file and alt text, in order) | identical; only the logo's path differs (`assets/krm-logo.png` → the theme's `assets/images/krm-logo.png`) |
| Buttons and calls to action (labels, order) | identical |
| Links | every HTML link points to the equivalent WordPress URL (table below); no `.html` links remain |
| Header / footer / main nav / `<main>` | exactly one of each on every page; no duplicated headers or footers |
| Page titles | identical |

#### Link conversion

| In the HTML design | In WordPress |
|---|---|
| `index.html`, `index.html#section` | `/`, `/#section` (on the home page itself: `#top`, `#section`) |
| `about.html`, `contact.html`, `donate.html`, `partner.html` | `/about/`, `/contact/`, `/donate/`, `/partner/` |
| `donate.html?amount=…&frequency=…&for=…#give` | `/donate/?amount=…&frequency=…&for=…#give` |
| `partner.html?type=…` | `/partner/?type=…` |
| `blog.html` | `/blog/` |
| `blog-post.html?post={slug}` | `/{slug}/` |
| `all-ministries.html?ministry={slug}` | `/ministries/{slug}/` |

The share links (WhatsApp, Facebook, X) on stories share the story's WordPress URL.

### Differences that remain

- **Contact form intro (visible):** "Fill in the form and your message will go straight to our team."
  The design says "…it will open in your email app, ready to send to our team." The WordPress form sends
  the message itself (saved and emailed), so the original sentence would be untrue.
- **Not visible:** a "Skip to content" link on the home page (the design's inner pages already have it),
  and a hidden "Leave this field empty" spam-trap field on the contact, partner and newsletter forms.
- **Hidden leftovers not carried over:** the HTML ministry and story pages contain a hidden "not found"
  block, and the HTML ministries page contains a hidden, empty ministry layout. They only exist because those
  HTML pages are filled by JavaScript; WordPress renders the right state on the server.

### Fixed in this phase

- **Home page social icons:** with no social URL saved, they again link to `#contact` (the footer
  contact details) as in the design, instead of being non-links. Saving a URL in Settings or the Customizer
  links them to the real page.
- **`/ministries/` title:** "Ministry Details | Kingdom Revival Ministries", as in the design (was
  "Ministries | …").
- **Home header brand text:** removed whitespace between "Kingdom Revival" and "MINISTRIES" so the markup
  matches the design (no visual change).

---

## Kingdom Revival Ministries: Phase 5 editable content

**Result: the site's text, headings, images, buttons, links, hero content, ministries, donation and contact
details and footer are all edited from the WordPress dashboard, using only WordPress's own features (custom
fields, custom post types, menus, the Customizer). No plugin is required.** Sermons, Events and Testimonials
have their own content types.

The front end is unchanged: with the default content, every page still matches the original HTML design
pixel for pixel (same results as Phase 3 and 4; see "Verified" below).

### Where each thing is edited

| What | Where in the dashboard |
|---|---|
| Page text and headings, images, buttons and links, hero content | **Pages → edit the page → "Page content" box** (Home, About, Contact, Donate, Become a Partner, News & Blogs) |
| Header, footer and shared wording (ministry pages, story pages, `/ministries/`, 404, sermons/events/testimonials headings) | **Kingdom Revival → Site text** |
| Ministry information | **Ministries** (title, short description, focus, points, photos, home explorer photo and position) |
| Stories | **Posts** |
| Sermons | **Sermons** (+ **Sermons → Series**) |
| Events | **Events** |
| Testimonials | **Testimonials** |
| Donation information | **Kingdom Revival → Settings** (PayPal, bank, Western Union/MoneyGram receiver) and the Donate page's "Page content" (amounts, texts, steps, impact cards, FAQ) |
| Contact information | **Kingdom Revival → Settings** or **Appearance → Customize → Kingdom Revival** (phone, email, address, WhatsApp, social links) |
| Impact numbers | **Kingdom Revival → Settings** or **Customize → Kingdom Revival → Impact numbers** |
| Footer links and main menu | **Appearance → Menus** |
| Logo | **Appearance → Customize → Site Identity** |

### How the "Page content" box works

- Each page's box is split into the page's sections (for example About: Hero · Who we are · Mission and vision ·
  What we do · Founder · Get involved · Photo gallery · Give band). Click a section to open it.
- Every field starts filled with the design's text or photo, so nothing changes until someone edits it.
- **Images:** "Choose image" opens the Media Library. "Use the design's image" goes back to the original
  photo. Picking an image also fills its description (alt text) from the Media Library.
- **Lists** (cards, photos, FAQ questions, office rows, check lists…): add, remove and reorder items with the
  ↑ ↓ buttons. Check lists are written one point per line.
- **Restore:** each section has "Restore this section to the original design when saving".
- **Highlighted words in headings:** wrap them in `<em>…</em>` (on the home page hero:
  `<span style="color: #F5A04A">…</span>`). Other HTML is removed when saving.
- **Placeholders:** text can use `{phone}`, `{email}`, `{address}`, `{year}`, so a change in Settings updates
  every page. Links can use `{home}`, `{about}`, `{contact}`, `{donate}`, `{partner}`, `{blog}`, `{email}`,
  `{phone}`, `{whatsapp}`, `{paypal}` or a section such as `#give`, and keep working if a page is renamed.
- These pages open in the classic edit screen, because their body comes from the Page content box (the
  normal editor area is hidden on them).

### What stays fixed, and why

| Fixed | Reason |
|---|---|
| Form fields (labels, the contact topics, validation messages) | Messages and sign-ups must arrive complete and match what the server checks. |
| The four partnership names | The sign-up form and saved submissions use them. Their prices, texts, check lists and buttons are editable. |
| "Where it is needed most" on the Donate page | The donation page's script recognises this option. |
| Icons, colours and layout | Part of the design; icons follow the position of each card. |

### Sermons, events and testimonials

Your design has no pages for these, so the theme provides the content structures and pages built **only from
existing design components** (page hero, story cards, story page layout, give card). No sample content was
added, and **nothing links to them yet**. The existing pages are unchanged until you decide where they should
appear.

| | Fields | Pages |
|---|---|---|
| **Sermons** | title, notes (editor), summary (excerpt), featured image, series, speaker, Bible passage, video link (YouTube/Vimeo/Facebook), audio file; date preached = publish date | `/sermons/`, `/sermons/{slug}/`, `/sermons/series/{slug}/` |
| **Events** | title, description, summary, featured image, start, end, venue, address, registration link and button text | `/events/` (upcoming first, then past events), `/events/{slug}/` |
| **Testimonials** | name (title), their words (editor), photo (featured image), role or place, related ministry; order via Page Attributes | `/testimonials/`, and `[krm_testimonials count="3"]` or `[krm_testimonials ministry="widows-ministry"]` inside any story or page |

To show them: add `/sermons/`, `/events/` or `/testimonials/` to a menu (Appearance → Menus → Custom Links).
Their headings and labels are under Kingdom Revival → Site text.

### Verified

Tested on WordPress 7.1.4, PHP 8.4, Chrome. Tools: [tools/visual-qa/](tools/visual-qa/).

- **Design unchanged:** 33 full-page screenshot comparisons (11 pages × desktop, tablet, mobile) are
  pixel-identical to the original HTML, except the intentional Contact sentence (Phase 3). All 31
  interaction states are identical. The Phase 4 content audit (26 pages, headings, text, links, images,
  buttons) is unchanged.
- **Editing works, in the real dashboard:**
  - Changing a heading shows on the site.
  - An added card appears, with `{phone}` filled in.
  - A removed FAQ question disappears.
  - A Site text footer change appears.
  - "Restore" brings back the design.
  - A Media Library image replaces a design photo (alt text from the library).
  - An event created in the block editor saves its date and venue.
  - The dashboard showed no JavaScript errors.
- **Safety:** `<script>` and event attributes typed into a heading are removed; every value is sanitized
  by type when saved and escaped when shown. The forms and Site text are protected by nonces and capability
  checks.
- **Code:** WordPress Coding Standards clean (except two notes: the short `krm` prefix, and an advisory
  about a menu query). PHP 7.4+ compatible.

### Not covered

- Visual design for sermons, events and testimonials beyond existing components: their pages are functional
  and consistent with the site, but were not part of the approved design. Review them before linking them.
- Popups and controls built by JavaScript (the newsletter popup, the give card's labels, the floating donate
  button on inner pages) keep their wording in `assets/js/site.js`.
- Revisions and Preview cover the normal editor, not the Page content box: content changes apply when the
  page is updated.

---

## Kingdom Revival Ministries: Phase 6 technical SEO (URLs and metadata)

**Result: every indexable page has exactly one SEO title, meta description, canonical URL, Open Graph title,
description, image and URL, and Twitter card, managed by an SEO plugin (Rank Math or Yoast SEO). The theme
prints none of these while a plugin is active. Every public address of the current live site keeps working,
either unchanged or through a single 301 redirect to its new page.**

**Indexing (6.4):** no page is accidentally `noindex`. Canonicals are valid, robots.txt blocks nothing important,
and every sitemap now lists only pages that answer 200. Old redirected addresses had been listed; they no longer
are. **Internal linking (6.5):** existing links are unchanged, and five link additions are proposed for approval.

Tested on WordPress 7.1.4 with Yoast SEO 28.8, Rank Math 1.0.280 and SureRank 1.10.3 (each on its own), and with
no SEO plugin. Tools: [tools/visual-qa/seo_check.py](tools/visual-qa/seo_check.py),
[tools/visual-qa/index_check.py](tools/visual-qa/index_check.py).

### 6.1 URLs

**Permalinks:** "Post name" (`/%postname%/`). The theme's addresses are short and readable:
`/about/`, `/contact/`, `/donate/`, `/partner/`, `/blog/`, stories at `/{story}/`, ministries at
`/ministries/{ministry}/`, and `/sermons/`, `/events/`, `/testimonials/`.

**Existing public URLs.** The live site (kingdomrevivalministries.com, WordPress + Elementor + the SureRank SEO
plugin) lists 27 public addresses in its sitemaps. All were checked on the new site:

| Old address (live site today) | On the new site |
|---|---|
| `/`, `/about/`, `/contact/` | unchanged (200) |
| `/donate-us/` | 301 → `/donate/` |
| `/latest-news-blogs/` | 301 → `/blog/` |
| `/baptisms/`, `/clean-water-project/`, `/leadership-and-discipleship/`, `/outreach-ministry/`, `/widows-ministry/` | 301 → the same name under `/ministries/` |
| `/bricks-kiln-slaves-children/` | 301 → `/ministries/brick-kiln-children/` |
| `/deliverance-and-healing-ministry/` | 301 → `/ministries/deliverance-and-healing/` |
| `/freedom-from-slavery-missions/` | 301 → `/ministries/freedom-from-slavery/` |
| `/free-medical-camp-for-brick-kiln-slaves-and-village-people/` | 301 → `/ministries/free-medical-camps/` |
| `/orphanage-and-underprivileged-children-care/`, `/orphanages-care/` | 301 → `/ministries/orphan-and-children-care/` |
| `/urdu-bibles-distribution/` | 301 → `/ministries/urdu-bibles/` |
| `/outreach-evangelism/` | 301 → `/ministries/outreach-ministry/` |
| `/sunday-school/` (placeholder text) | 301 → `/ministries/children-ministry/` |
| `/executive-message/` (founder's welcome) | 301 → `/about/#founder` |
| `/services/` (template placeholder) | 301 → `/#all-ministries` |
| `/form/`, `/elementor-2338/` (home page drafts), `/sample-page/` | 301 → `/` |
| `/hello-world/` (WordPress sample post), `/category/uncategorized/` | 301 → `/blog/` |
| `/category/ministries/` | 301 → `/ministries/` |

- Each redirect is a single hop to a page that returns 200. Matching ignores letter case and the trailing
  slash.
- Redirects take priority over an old page that still exists at that address. This matters when the theme
  is installed on the live site's database, where the old Elementor pages remain.
- **Editable:** Kingdom Revival → Settings → Redirects, one `old new` pair per line. Targets can be a
  `/path`, a full URL or `{donate}`, `{blog}`, `{about}`….
- The design's static-file addresses (`about.html`, `donate.html?amount=…`,
  `all-ministries.html?ministry=…`, `blog-post.html?post=…`) also redirect in one hop, keeping
  donate/partner pre-fill values.
- Address variants such as `/?ministry=…` declare the clean address as canonical, and WordPress already
  301s `?p=` links.

### 6.2 Metadata

**Managed by the SEO plugin.** While Rank Math, Yoast SEO, SureRank, All in One SEO, SEOPress, The SEO
Framework or Slim SEO is active, the theme prints no title format, description, canonical, Open Graph, Twitter
or JSON-LD tags. Every page's values are edited in the plugin's box on that page.

**Design defaults, only where the plugin's field is empty** (Rank Math, Yoast SEO, SureRank):

| | Default the theme offers |
|---|---|
| SEO title and social title | The design's titles: "Kingdom Revival Ministries — Home", "About Us \| Kingdom Revival Ministries", "Ministry Details \| …", "News & Blogs \| …" and the other design pages. Other pages use the plugin's title format. |
| Meta and social description | The design's description for each page; a ministry's or story's excerpt; list pages: their introduction. |
| Social image | The page's hero image (Page content → Hero), a story's cover, a ministry's first photo. |

Anything typed in the plugin wins. Tested: a custom title and description in Yoast and in Rank Math replace
the defaults, and the share title then follows the custom SEO title.

**Results** (30 pages: the 26 design pages + sermons, events, testimonials, a story category):

| Setup | Pages with every required tag exactly once | Theme tags printed |
|---|---|---|
| Yoast SEO | 30 / 30 | none |
| Rank Math (setup wizard done) | 30 / 30 | none |
| SureRank | 30 / 30 | none |
| No SEO plugin | 30 / 30 (theme's basic tags) | yes, as fallback |

**Two findings fixed during testing:**

1. **SureRank wasn't detected.** The live site uses SureRank, which the theme didn't recognise. Installing the
   theme on the live site would have printed every tag twice. It is now detected.
2. **Rank Math prints nothing until its setup wizard is completed or skipped.** Before the fix, a site with
   Rank Math installed but not set up had no meta tags at all. The theme now keeps its own tags until Rank
   Math is ready, and Kingdom Revival → Settings shows a notice saying so.

Also: single testimonials redirect to `/testimonials/`, so they are kept out of the core, Yoast, Rank Math and
SureRank sitemaps.

### 6.4 Indexing

Tested on WordPress 7.1.4 with no SEO plugin, Yoast SEO 28.8, Rank Math 1.0.280 and SureRank 1.10.3. The test
site ran on the live site's kind of database: the theme's content plus old published pages at `/donate-us/`,
`/latest-news-blogs/`, `/baptisms/`, `/executive-message/` and `/services/`, plus WordPress's "Hello world!" and
"Sample Page". Tool: [tools/visual-qa/index_check.py](tools/visual-qa/index_check.py), requesting pages as Googlebot.

| Check | Result |
|---|---|
| Accidental `noindex` | None. All 30 important pages are indexable (no robots meta `noindex`, no `X-Robots-Tag`) with every setup. The theme prints no robots directives of its own. |
| Intentional `noindex` | Kept: search results (`/?s=`), the login page and 404 pages stay `noindex`. These come from WordPress and the SEO plugin, and nothing was removed. |
| Search-engine visibility | "Discourage search engines" is off on the test site. If it's switched on, every page gets `noindex, nofollow` and the sitemap is switched off. The tool flags that on all 30 pages, and the theme's Setup screen also warns while it's on. The live site today is `index, follow`. |
| Canonical URLs | Exactly one canonical per page. It's absolute, on the site's own domain and self-referencing, and it answers 200. Same result with every setup. |
| Crawler access | All 30 pages answer 200 to Googlebot. robots.txt blocks only `/wp-admin/` (Yoast: nothing). Theme CSS, JS and uploads are allowed, and robots.txt names the sitemap. |
| XML sitemaps | **Two problems found and fixed (below).** Now 35 URLs with each setup. Every one answers 200, is indexable and has itself as canonical. |

**Fixed: sitemaps listed redirected addresses.** Old pages that still exist in the database (the Elementor
"Donate us", "Baptisms" and so on), "Hello world!", "Sample Page" and Uncategorized were listed in every sitemap,
even though each one 301s to its new page. Installed on the live database, that would submit about 25 redirecting
URLs. Now any address in Kingdom Revival → Settings → Redirects is left out of the core, Yoast, Rank Math and
SureRank sitemaps, so editing the Redirects list keeps the sitemaps in step.

**Fixed: list pages were missing or inconsistent.**
- Core sitemaps didn't list `/sermons/`, `/events/` or `/testimonials/`. A small archives sitemap now lists each
  one, but only while it has published items, so an empty list is never submitted.
- Yoast and SureRank had dropped `/testimonials/`, which is now listed.
- `/ministries/` (the design's short "Choose a ministry to explore" page) is now left out of every sitemap; the
  12 ministry pages are listed. It stays indexable.
- Yoast listed `/author/admin/`, which exposes the username. Author archives are now left out with Yoast, as they
  already were in core sitemaps.

| Sitemap | URLs listed | Redirecting |
|---|---|---|
| Before, no SEO plugin (core sitemaps) | 40 | 8 |
| Before, SureRank | 45 | 9 |
| After, each of no plugin / Yoast / Rank Math / SureRank | 35 (the same 35) | 0 |

Yoast and Rank Math were first tested after the redirect fix. Their remaining differences were the author
archive, `/ministries/`, `/testimonials/` and, with Rank Math, a single testimonial; all are fixed.

**Also found on the live site, now handled:**
- The live SureRank sitemap lists six Robo Gallery photo pages (`/gallery/widows-ministry/` and five others)
  that weren't in the 6.1 table. They now 301 to the matching ministry page in one hop, from the default
  Redirects list.
- It also lists Elementor templates (`/elementor-hf/header/`, `?elementor_library=…`) and a SureForms form
  (`/form/1270/`). These aren't real pages, and once Elementor and SureForms are deactivated they will 404,
  which is the right way for search engines to drop them.

### 6.5 Internal linking

All existing internal links are unchanged; no new link has been inserted. Every crawlable page is reached by
plain links in one or two clicks from the home page (17 in one click, 7 in two). Today's links:

| Path from the brief | Today |
|---|---|
| Home → About | Yes: About section button, main menu and footer |
| Home → Ministries | Yes: all 12 ministry pages from the menu, the ministry explorer and the footer |
| Home → Donate | Yes: header button, hero, give section and floating button |
| Home → Sermons | **No.** No page links to `/sermons/`, `/events/` or `/testimonials/`; they're found only through the sitemap |
| Ministry pages → sermons | **No.** Ministry pages link only to "All ministries" and their give form, and sermons have no "Related ministry" field |
| Blog articles → ministry | Yes: each story links to its ministry twice (the "Ministry:" line and the sidebar card) |
| Contact → calls to action | Partly: Donate (sidebar button) and prayer (form). Become a Partner only from the menu |

**Proposed for approval.** None of these is built; each one adds visible content.

1. **Sermons link, once sermons are published.** Add "Sermons" to the footer Quick Links menu (Appearance →
   Menus; no code) or as a text link in the home Get Involved section. Same for Events and Testimonials when
   they have content. Linking an empty list now would send visitors and crawlers to a blank page.
2. **"Stories from this ministry" on ministry pages.** A row of the existing story cards (the blog's design)
   above the give band, showing stories whose Ministry is set to that ministry. Today that covers 7 of the 12
   ministries; the row is hidden where there are none. This is the reverse of the story → ministry link that
   already exists.
3. **Sermons on ministry pages.** Add the "Related ministry" field that testimonials already have to sermons,
   then show that ministry's latest sermons in the same row as proposal 2.
4. **In-text ministry links in stories.** Each story names its ministry in the body ("Our widows ministry
   brings…"). Link that first mention to the ministry page. This is a content edit in the story, with no code.
5. **Contact → Become a Partner.** Add one line under the Contact sidebar's give button: "Want to stand with us
   every month? Become a partner →".

### Decisions for you

1. **Which plugin:** Rank Math or Yoast SEO, as the brief suggests. Keeping SureRank also works and keeps
   its existing settings, but the old per-page descriptions it generated are mostly unusable ("About About
   Kingdom Revival Ministries…").
2. **Title separator:** set the plugin's separator to "|" so story and ministry titles match the design
   ("Baptisms | Kingdom Revival Ministries"). Plugins default to "-".
3. **`/donate-us/` and `/latest-news-blogs/`:** the new pages are `/donate/` and `/blog/`, with 301s from the
   old addresses. To keep the old addresses instead, rename the two pages' slugs. Links across the theme find
   these pages by template, so nothing else changes. Then remove those two lines from Redirects.
4. **Internal links:** which of the five 6.5 proposals to build.
5. **Go-live steps for the live database:**
   - Untick Settings → Reading → "Discourage search engines" if it is on.
   - Move "Hello world!" to the trash. While it's published, News & Blogs and the related-stories rows show it,
     and it links to `/hello-world/`, which redirects to `/blog/`.
   - Deactivate Elementor, Robo Gallery and SureForms if they're no longer used.
   - Regenerate the SEO plugin's sitemap once after switching theme (SureRank and Rank Math cache it).
   - Resubmit the sitemap in Google Search Console.

### Unchanged

The site looks exactly as before. All 33 page comparisons still match the original HTML pixel for pixel
(Contact's intentional sentence aside), and the Phase 4 content audit, page titles included, is unchanged.

---

## Kingdom Revival Ministries: Phase 7 structured data (schema markup)

**Result: every page has valid JSON-LD describing the ministry, the site, the page and its breadcrumb. Stories,
events and sermon videos also get BlogPosting, Event and VideoObject. Nothing is described twice, and every value
matches what the page shows. With no SEO plugin, Yoast SEO or Rank Math, all 32 checked pages pass the theme's
check, and the Schema.org Validator reports 0 errors and 0 warnings on every page type. SureRank's own graph still
has faults the theme can't reach (see below).**

Tested on WordPress 7.1.4 with Yoast SEO 28.8, Rank Math 1.0.280 and SureRank 1.10.3 (each on its own), and with no
SEO plugin. New tool: [tools/visual-qa/schema_check.py](tools/visual-qa/schema_check.py). Code:
[theme/kingdom-revival/inc/schema.php](theme/kingdom-revival/inc/schema.php). Theme version 1.4.0.

### What each page says

| Type | Where | Taken from (what the page shows) |
|---|---|---|
| Organization | every page | Site name, logo, phone, email and address (footer); founder (About page, founder name); social links when set in Settings. Type set in Settings (see Decisions). |
| WebSite | every page | Site name, search |
| WebPage | every indexable page | AboutPage and ContactPage for those two pages; CollectionPage for News & Blogs, story categories, ministries, sermons, events and testimonials lists. Title and description as in the page's meta tags. |
| BreadcrumbList | every page with a visible breadcrumb (all but the home page and the "Choose a ministry" page) | The breadcrumb shown on the page: same names, same links, in the same order |
| BlogPosting | the 7 stories | Title (h1), cover image, published and modified dates, category, author box ("Kingdom Revival Ministries Team") |
| Event | an event with a start date and a venue or address | Title, When (start, end), Where (venue, address), featured image, excerpt; organiser = the ministry; attendance in person |
| VideoObject | a sermon whose video is embedded on the page | The video's title and thumbnail from YouTube/Vimeo, the embed address, the sermon date (shown on the page), excerpt |

**One source for the breadcrumb.** The visible breadcrumb on all nine kinds of page and the BreadcrumbList now come
from the same function (`krm_breadcrumbs()`), so they can't drift apart. The markup is unchanged. Before, they were
written separately, and five kinds of page (sermon and event pages and lists, story categories) showed a
breadcrumb with no BreadcrumbList.

**Not used, on purpose:**
- **FAQPage.** The Donate and Become a Partner FAQs are visible, but Google shows FAQ rich results only for
  well-known government and health sites, so this site isn't eligible.
- **eventStatus, offers, performer** on events. The site has no field for a cancelled or moved event, no ticket
  price and no speaker. Google's documentation treats a missing `eventStatus` as scheduled. Google lists these
  properties as recommended, not required.

### SEO plugins: no duplicates

With an SEO plugin active, the plugin's graph is kept and the theme prints no Organization, WebSite, WebPage,
BreadcrumbList or Article of its own. It only adds what the plugin lacks and corrects what doesn't match the page.

| | Yoast SEO | Rank Math | SureRank |
|---|---|---|---|
| Event, VideoObject | added to the plugin's graph | added to the plugin's graph | separate block with only these two |
| Organization | **Yoast printed none** (it needs a name and logo in its settings); the theme's is added | Rank Math's kept; the address, phone, email, logo, founder and URL it left out are filled in | SureRank's kept |
| BreadcrumbList | matched to the visible trail (it differed only on story categories) | **Rank Math printed none**; the visible trail is added | **8 of 10 page kinds differed** (e.g. "Home › Ministry › Gospel & Church › Baptisms"); now matched on all but the ministries list |
| Story author | **the WordPress user "admin"** → the author box ("Kingdom Revival Ministries Team") | same | unchanged: "admin" (see below) |
| Story headline | already the title | **was "Title - Kingdom Revival Ministries"** → the title | already the title |
| Pages that aren't stories | — | **Home, About, Contact, Donate and Partner were typed "Article"** (Rank Math's default for pages) → removed, unless a schema was chosen in the page's Rank Math Schema tab | — |
| Ministry, sermon and event pages | — | **printed no structured data at all** (no default schema type) → Rank Math now prints its own WebSite/WebPage there | — |

Anything set in the plugin's own schema settings is kept: a custom headline, a schema chosen for a page, the
plugin's organisation name and type.

### Verification

[schema_check.py](tools/visual-qa/schema_check.py) on 32 pages: the 30 from Phase 6 plus a sermon and an event.
It checks that the JSON-LD parses, nothing is described twice across all blocks, every `@id` reference resolves,
Google's required properties are present, and the values appear on the page: BreadcrumbList = the visible
breadcrumb, headline/name = the h1, dates, venue, author and embedded video, plus the contact details. `--validate`
sends one page of each kind (9 per setup) to the Schema.org Validator.

| Setup | Pages passing the theme check | Schema.org Validator (page kinds) | SEO tags check (Phase 6, 30 pages) |
|---|---|---|---|
| No SEO plugin (theme's graph) | 32 / 32 | 9 kinds: 0 errors, 0 warnings | 30 / 30 |
| Yoast SEO | 32 / 32 | 9 kinds: 0 errors, 0 warnings | 30 / 30 |
| Rank Math | 32 / 32 | 9 kinds: 0 errors, 0 warnings | 30 / 30 |
| SureRank | 0 / 32 (SureRank's own faults, below) | 2 of 9 kinds (home, About): 0 errors, 0 warnings; the validator then refused further requests (HTTP 429) | 30 / 30 |

Remaining warnings with every setup: the logo is 99×99, under Google's 112×112 minimum (see Decisions), and the
event has no `eventStatus` (above).

**Google Rich Results Test.** It can't reach a local test site, so it couldn't be run here. The theme check covers
the same required properties for Organization, BreadcrumbList, Article, Event and VideoObject. Run the test on the
live site after go-live (search.google.com/test/rich-results): one story, one event, one sermon with a video, and
the home page.

### Fixed along the way

1. **Organisation type.** The theme called the ministry an `NGO`, but nothing on the site says it is registered,
   and donations go to a personal bank account. It is now plain `Organization`, with NGO selectable in Settings.
2. **Address.** "Faisalabad" was in both the street address and the city. The street address is now the
   Address setting without the city. City, province and country are editable.
3. **Founder** is now read from the About page's founder name instead of being fixed in code.
4. **`</script>` in a title** (story, event…) would have ended the JSON-LD block early, breaking it and letting the
   rest of the title run as HTML. Values are now encoded so that can't happen.
5. **Sermon video lookup.** The sermon page asked YouTube/Vimeo for the embed on every view. The answer is now kept
   (refreshed when the link changes), and the same answer feeds VideoObject.

### Not covered

- **SureRank's own faults.** On every page SureRank's WebSite and WebPage refer to an author `#person` it never
  prints (125 broken references across the 32 pages). Its Article credits the WordPress user ("admin") rather than
  the author box, and its Organization lists "admin" as founder. These come from SureRank's schema settings
  (SureRank → Schema), and its filters give the theme no clean way to correct them. They are fixed by switching to
  Rank Math or Yoast (Phase 6, decision 1), or by editing those three schemas in SureRank. On the ministries list
  SureRank keeps a one-item "Home" BreadcrumbList; an empty one would be worse.
- **Rank Math encodes "&" as "&amp;"** in its JSON-LD text ("News &amp; Blogs"). That happens after every filter
  and applies to all Rank Math text, so the theme can't change it. The Schema.org Validator accepts it; how Google displays it is unverified.
- Real event and sermon content: the test site had one sample of each, with a public YouTube video.

### Decisions for you

1. **Organisation type.** Is Kingdom Revival Ministries registered, for example as an NGO, society or trust with
   the Pakistani authorities? If yes, choose NGO in Kingdom Revival → Settings → Structured data; if not, keep
   Organization. (schema.org has no type for a church-planting ministry, and `Church` means a church building.)
2. **Logo for search results.** Upload a square logo of at least 112×112 pixels (512×512 is ideal) under
   Appearance → Customize → Site Identity. The bundled logo is 99×99, below Google's minimum. Check the header
   after uploading; this wasn't tested with a larger logo.
3. **Go-live settings:**
   - Settings → General → Timezone: Karachi, so event times carry +05:00 (the test site is on UTC).
   - With Rank Math: Titles & Meta → Local SEO → "Organization" (Rank Math defaults to a person), the logo, and the
     name "Kingdom Revival Ministries".
   - With Yoast SEO: Settings → Site representation → Organization, the name and logo. Until then the theme's
     Organization is used.
   - Run the Rich Results Test on the live pages listed above.

### Unchanged

The visible pages are unchanged. On the 34 pages checked (the 32 above, search results and a second
category), the HTML outside the JSON-LD is byte-identical for 24. The other 10 differ only in whitespace and in
`&#038;` versus `&amp;`, which are the same character ("News & Blogs", "Deliverance & Healing").

Pixel comparison (`compare.js`, 33 comparisons) **could not re-confirm 0% here**. The local test database is the
Phase 6 copy of the live site's database, with "Hello world!" published and older content. On it, News & Blogs and
the stories differ from the design (up to 46%) with the Phase 6 theme (1.3.0) as much as with 1.4.0, and a few small
diffs (0–5%) change from run to run. Since the rendered HTML is the same, the screenshots are too. Re-run
`npm run pages` on a clean default-content site before release to record the 0% figures.

---

## Kingdom Revival Ministries: Phase 8 keyword grouping and topical authority

**Result: each of the site's 25 content pages (6 core pages, 12 ministries, 7 stories) is assigned one primary
search intent, and no two pages target the same one. The list and category pages are covered too. The site's realistic authority is *Christian ministry in Pakistan*: brick kiln families, Urdu Bibles,
orphans and widows, village outreach. Most of the brief's broad topics ("Christian sermons", "biblical teachings",
"spiritual growth", "Christian revival") can't be won with the site's current content, and they don't match what
it offers. Nine overlaps between pages were found. Most are resolved by the intent map alone, with no change.
Two (C2, C7) need a small content change, proposed below for approval. One (C9) is a gap, not an overlap. No page content, heading, title or meta
description was changed.**

This phase is a strategy only. No theme files changed, so the site looks exactly as before (Phase 7 state).
Everything under "Content optimization recommendations" is a proposal that needs your approval.

### Method and evidence

- **Inventory.** Every page's H1, H2s, introduction, meta title and meta description, taken from the theme's
  defaults: `inc/content/*.php`, `inc/data/ministries.php`, `inc/data/posts.php` and `krm_page_seo()` in
  `inc/seo.php`. This is exactly what the pages show today.
- **Search results.** Google results were checked by hand for the Pakistan-specific topics, to see what kind of
  page ranks and who competes (Sources at the end).
- **Not available: search volumes and current rankings.** No keyword tool or Search Console data was available.
  Every query below is a **candidate phrasing**, chosen for intent rather than measured volume. Check them in
  Google Search Console (after go-live) or Google Keyword Planner before treating any as a target (see Decisions).

**Who searches.** The site is written for donors and churches abroad: amounts are in US dollars, and giving is by
PayPal, Western Union or MoneyGram. Its searchers are therefore mostly English-speaking Christians in the US, UK
and Canada who are looking for a ministry in Pakistan to pray for, give to or partner with. People in Pakistan
looking for a church or help are a second, smaller audience, and Urdu queries aren't covered here (see Decisions).

**What ranks.** For Pakistan-specific queries ("brick kiln children Pakistan Christian ministry", "Urdu Bible
distribution Pakistan", "donate Christian orphans widows Pakistan", "debt bondage brick kiln Christian families"),
Google shows a mix of large charities (Barnabas Aid, Christian Aid Mission, Global Christian Relief), news reports
(Christian Post, Christian Today), small ministries' own sites and individual GoFundMe pages. Small ministries
already rank, so these queries are **winnable** for a site with first-hand photos and field stories. For the broad
topics, Google shows large publishers, Bible sites and megachurch sermon libraries. Nothing on this site matches
those searches.

### 8.1 The brief's candidate topics

| Candidate topic | Verdict | Why | Where it lives |
|---|---|---|---|
| Christian ministry | **Use, qualified:** "Christian ministry in Pakistan" | On its own the phrase is too broad and gets job and degree searches. With "Pakistan" it describes the whole site. | Home |
| Christian revival | **Don't target** | "Revival" is the ministry's name, not its subject. People searching it want revival history or theology (e.g. the Great Awakenings). The site has nothing on that, and the brand name already brings brand searches. | Brand only. Possible future article, see 8.5 cluster F |
| Prayer and spiritual growth | **Split into three intents the site actually serves** | "Spiritual growth" is devotional content the site doesn't have. Prayer is real here, in three forms: sending a prayer request, praying for the sick, becoming a prayer partner. | Contact (prayer request), Deliverance & Healing (healing prayer), Partner (prayer partner) |
| Biblical teachings | **Not now** | No teaching content exists. The closest real topic is Scripture *access* (Urdu Bibles). | Urdu Bibles now; Sermons later (8.5 cluster F) |
| Christian sermons | **Not now; later as "Urdu sermons" or the pastor's own** | `/sermons/` has no sermons yet. "Christian sermons" is dominated by very large libraries. A narrower angle could work once sermons are published. | `/sermons/` (empty, kept out of the menu) |
| Faith and discipleship | **Use, qualified:** "discipleship and leadership training in Pakistan" | The site has a specific discipleship ministry. | Leadership & Discipleship |
| Church outreach | **Use, qualified:** "village outreach / evangelism in Pakistan" | Matches the Outreach Ministry exactly. | Outreach Ministry |
| Ministry donations | **Use:** "donate to a Christian ministry in Pakistan" | The site's main conversion. | Donate (one-time), Partner (monthly) |
| Community ministry programs | **Use as an umbrella, through the programs themselves** | Nobody searches for "community ministry programs" with this site in mind. They search for the specific programs: medical camps, clean water, orphan care. | The 12 ministry pages |

**Topics the brief didn't list, but the site has real authority on.** These have the most potential:
brick kiln **debt bondage / bonded labour**, **brick kiln children's education**, **Urdu Bibles**,
**Christian widows and orphans in Pakistan**, and **free medical camps** for kiln workers.

### 8.2 One primary intent per page

Intent: **N** navigational, **I** informational, **C** commercial investigation ("which ministry should I
support"), **T** transactional (give, sign up, contact).

#### Core pages

| Page | Primary intent (candidate query) | Type | Secondary keywords | Owns, so no other page targets it |
|---|---|---|---|---|
| Home `/` | **Kingdom Revival Ministries** (brand) + **Christian ministry in Pakistan** | N / C | Gospel ministry Pakistan, church planting Pakistan, Christian charity Pakistan, brick kiln families | The brand and the broad "ministry in Pakistan" head term |
| About `/about/` | **About Kingdom Revival Ministries / Pastor Adil Arif** | N / I | founder, mission, beliefs, "who we are", Faisalabad ministry | Founder and mission questions |
| Contact `/contact/` | **Contact / prayer request** | T | send a prayer request, visit the ministry Pakistan, Faisalabad address | "Prayer request" (the form handles it) |
| Donate `/donate/` | **Donate to a Christian ministry in Pakistan** (one-time) | T | give by PayPal / bank transfer / Western Union / MoneyGram, support Christians in Pakistan | One-time giving and the payment-method searches |
| Become a Partner `/partner/` | **Monthly mission partnership in Pakistan** | T / C | monthly giving, prayer partner, sponsor a ministry, church mission partner | Recurring giving, prayer partners, church partnerships |
| News & Blogs `/blog/` | **Stories from Christian ministry in Pakistan** | I | field stories, ministry updates, testimonies | The "updates/news" intent. Individual stories own their own topics. |

#### Ministry pages (`/ministries/{slug}/`)

Rule for the 12: a ministry page answers **"what does this ministry do, and how can I support it"** (C / T).
Stories answer **"what is it like, why does it matter"** (I).

| Ministry | Primary intent (candidate query) | Secondary keywords and entities |
|---|---|---|
| Brick Kiln Children | **Education for brick kiln children in Pakistan** | sponsor a brick kiln child, kiln children school, child labour, Punjab |
| Freedom from Slavery | **Freeing Christian families from brick kiln debt bondage** | bonded labour Pakistan, *peshgi* (advance-loan) debt, modern slavery, 1992 Bonded Labour Abolition Act |
| Free Medical Camps | **Free medical camps for brick kiln workers in Pakistan** | medical mission Pakistan, village health camp, free medicine |
| Clean Water Project | **Clean water for villages in Pakistan** | water project Pakistan, safe drinking water, hand pump / filter (only if the project uses them) |
| Children Ministry | **Sunday school and Bible teaching for children in Pakistan** | children's ministry, kids Bible lessons, village Sunday school |
| Orphan & Children Care | **Christian orphan care in Pakistan** | orphanage support, underprivileged children, sponsor an orphan, James 1:27 |
| Widows Ministry | **Supporting Christian widows in Pakistan** | widow care, food support for widows, James 1:27 |
| Urdu Bibles | **Urdu Bible distribution in Pakistan** | give an Urdu Bible, Bibles for new believers, Scripture in heart language, Psalm 119:105 |
| Baptisms | **Baptisms of new believers in Pakistan** | believer's baptism, new believers, cost of following Christ |
| Deliverance & Healing | **Healing and deliverance prayer ministry** | prayer for the sick, spiritual oppression, prayer in Jesus' name |
| Outreach Ministry | **Village evangelism and outreach in Pakistan** | unreached communities, Gospel outreach, Great Commission (Matthew 28:19) |
| Leadership & Discipleship | **Pastor and leadership training for house churches in Pakistan** | discipleship training, house church leaders, church planting |

#### Stories (posts)

Each story supports its ministry page and targets an informational angle the ministry page doesn't.

| Story | Primary intent | Supports |
|---|---|---|
| God's Word in Every Home | Why Urdu Bibles matter to new believers | Urdu Bibles |
| Learning Beside the Kilns | Life and education for children in brick kilns | Brick Kiln Children |
| New Life in the Water | What baptism means for new believers in Pakistan | Baptisms |
| A Home for the Orphaned | What daily orphan care involves, and why monthly giving matters | Orphan & Children Care (and Partner) |
| Standing With Widows | The hardships widows face in village communities | Widows Ministry |
| Raising Up Shepherds | How house-church leaders are trained | Leadership & Discipleship |
| Prayer for the Sick and Oppressed | What prayer for the sick and oppressed looks like | Deliverance & Healing |

#### Other indexable addresses

| Address | Intent | Note |
|---|---|---|
| `/category/{scripture,children,church,care,prayer}/` | None of its own | Filtered lists of stories. They mustn't compete with ministry pages (see C7). |
| `/sermons/`, `/events/`, `/testimonials/` | Later: "sermons by Pastor Adil Arif", "ministry events in Faisalabad", "testimonies from Pakistan" | Kept out of the sitemap while empty (Phase 6). |
| `/ministries/` | None, navigation only | Already out of the sitemap (Phase 6). |

### 8.3 Entities

Search engines link pages through entities more than through exact phrases. Use these consistently, as names
and links, where the content truly involves them. Most already appear on the site.

- **Organisation and people:** Kingdom Revival Ministries; Pastor Adil Arif (founder). Already in the
  Organization schema (Phase 7).
- **Places:** Pakistan, Punjab, Faisalabad. Faisalabad appears on the site only in the contact details, the map
  and the schema. Naming it once in the About story would help local relevance. That is content, so it's proposed
  in R4.
- **Subjects:** brick kilns, bonded labour / debt bondage, Urdu (language), the Bible, house churches, baptism,
  orphans, widows.
- **Scripture already quoted:** Matthew 28:19 (home), Psalm 119:105 (Urdu Bibles story). Relevant for future
  orphan and widow content: James 1:27.

### 8.4 Cannibalization: what was found and how it's resolved

| # | Overlap | Severity | Resolution |
|---|---|---|---|
| C1 | **Home and About use the same heading.** Home's About section H2 "A Gospel-Centred Ministry Serving Pakistan's Forgotten" is the About page's H1 ("A Gospel-centred ministry serving *Pakistan's forgotten*"), and both descriptions use the same sentence. | Low | Intent map: Home owns the brand and "ministry in Pakistan", About owns founder and mission questions. Home will outrank for the brand anyway. Optional wording change: **R1**. |
| C2 | **Children Ministry looks like a brick kiln page.** All 12 of its gallery photos have the alt text "Children and families in a brick kiln community", and its excerpt says "brick kiln communities". The Brick Kiln Children ministry and the home donation card ("Help Brick Kiln Children Go to School…") are about the same thing. | **Medium** | Brick Kiln Children owns "brick kiln children education". Children Ministry owns "Sunday school / Bible teaching for children". Proposed alt-text change: **R2**. |
| C3 | **Four ministries mention brick kilns** (Brick Kiln Children, Freedom from Slavery, Free Medical Camps, Children Ministry), plus the home card and one story. | Medium | Each has a separate need in 8.2: education, debt freedom, medical care, Sunday school. Tie them together with an informational pillar article (8.5 cluster A), not a fifth competing page. |
| C4 | **The seven story–ministry pairs** (e.g. the Urdu Bibles story and the Urdu Bibles ministry). | Low | Already separated by intent: ministry page = support, story = narrative. The story titles are distinct. Each story links to its ministry twice. **The reverse link is missing**: Phase 6 proposal 2, "Stories from this ministry". Building it is the most useful structural step in this phase. |
| C5 | **Children Ministry, Orphan & Children Care and the "A Home for the Orphaned" story.** | Low | Orphan care is the Orphan & Children Care page. Children Ministry is Sunday school (C2). The story is the "what daily care looks like" angle. |
| C6 | **Donate, Partner, home giving cards and the give band on every ministry page.** | Low | Donate = one-time and payment methods. Partner = monthly, prayer and church partnership. The give bands and cards are calls to action, not search targets. |
| C7 | **Six addresses share one meta description.** The five story categories have no description, so they reuse the News & Blogs one ("Stories of hope from the field…"). `/sermons/`, `/events/` and `/testimonials/` have no introduction, so all three would use the footer tagline once they are published. | **Medium** | Give each category a unique description. The category page shows it as its visible introduction, so it's content: **R3**. Fill in the sermons, events and testimonials introductions before publishing those pages. |
| C8 | **Prayer is spread across five places:** Contact (prayer requests), Partner (prayer partner), the home and About "Pray" cards, Deliverance & Healing, and the Prayer story. | Low | Already divided in 8.1: request = Contact, prayer partner = Partner, healing prayer = Deliverance & Healing. The "Pray" cards are calls to action. |
| C9 | **"Church planting" has no page.** Home and About headline it ("Planting churches and house churches"), but no ministry covers it. It's split between Outreach and Leadership & Discipleship. | Gap, not overlap | Leadership & Discipleship gets it as a secondary term. A dedicated article is in cluster E. Don't add it to a second ministry page. |

### 8.5 Topic clusters and future content

Each cluster has a **pillar** (an existing page that owns the commercial intent) and **supporting articles**:
new stories, each with its own informational intent, linking up to the pillar. The pillar's ministry page links
back to them once "Stories from this ministry" (Phase 6 proposal 2) is built. Write future articles from real field
experience, with the ministry's own photos. That first-hand material is what large charities can't copy.

| Cluster | Pillar | Existing support | Proposed articles (informational intent) |
|---|---|---|---|
| **A. Brick kiln families** (strongest) | Brick Kiln Children + Freedom from Slavery | Learning Beside the Kilns | 1. *What is brick kiln debt bondage? How families become trapped* (overview linking all four kiln ministries; resolves C3) · 2. *A family's road to freedom* (Freedom from Slavery has no story yet) · 3. *A day at a free medical camp* (Medical Camps has no story) |
| **B. Scripture** | Urdu Bibles | God's Word in Every Home | 4. *What it costs to put a Bible in a new believer's hands* (explains a gift amount) · 5. *Teaching children the Bible in village Sunday schools* (gives Children Ministry its first story; resolves C2) |
| **C. Orphans and widows** | Orphan & Children Care, Widows Ministry | A Home for the Orphaned; Standing With Widows | 6. *Caring for orphans and widows: James 1:27 in Pakistan today* |
| **D. Practical care** | Clean Water, Medical Camps | none | 7. *Before and after a village gets clean water* (Clean Water has no story) |
| **E. Church growth** | Leadership & Discipleship, Outreach, Baptisms | Raising Up Shepherds; New Life in the Water | 8. *How house churches are planted in Pakistani villages* (resolves C9) · 9. *A village outreach day: from first visit to first fellowship* (Outreach has no story) |
| **F. Teaching and prayer** (later) | `/sermons/` once published | Prayer for the Sick and Oppressed | Sermons by Pastor Adil Arif (decide language first) · 10. *How to pray for Christians in Pakistan* (prayer guide; feeds the Partner prayer option) · revival testimonies, only if real ones exist |
| **G. Giving** | Donate, Partner | none | 11. *How to give to a ministry in Pakistan from abroad* (PayPal, bank transfer, Western Union and MoneyGram explained; answers the most common donor question) |

**Order:** A1, then the four ministries with no story at all (articles 2, 3, 7, 9; 5 also covers Children
Ministry), then 11, then the rest. Five of the 12 ministries (Freedom from Slavery, Medical Camps, Clean Water,
Children Ministry, Outreach) have no supporting story today.

**Internal linking for clusters.** Link each article to its pillar once in the text, with the pillar's topic as
the link words ("our Urdu Bibles ministry", not "click here"). Link it to at most one sibling article. Don't link
every article to Donate in the text; the story template's give band already does that.

### Content optimization recommendations (for approval; none applied)

Each of these changes something visitors see or the design's approved wording, so each needs your approval.
Any approved change would need the visual-QA run afterwards. Pixel parity then holds against the *edited*
content, not the original HTML.

| # | Recommendation | Where | Effort |
|---|---|---|---|
| **R1** | Change home's About-section heading so it no longer repeats the About page H1, e.g. "Preaching Christ and Caring for Pakistan's Forgotten". Optional (C1 is low). | Pages → Home → Page content → About → Heading | Field edit |
| **R2** | Give Children Ministry's gallery photos alt text that describes the photo and the ministry, e.g. "Children at a village Sunday school lesson". Only where the photo shows that; otherwise use the photo's actual scene. | Ministries → Children Ministry → photos | Content edit |
| **R3** | Write a one-sentence description for each story category (Scripture, Children, Church, Care, Prayer), e.g. Scripture: "Stories of Urdu Bibles reaching families and new believers across Pakistan." It becomes the category page's introduction and meta description. | Posts → Categories | 5 short texts |
| **R4** | Name Faisalabad, Punjab once in the About page's "Who we are" text, so the location is in the copy and not only in the contact details. | Pages → About → Page content | One phrase |
| **R5** | Ministry meta descriptions: today each is the ministry's one-line excerpt, e.g. "Placing Urdu Bibles in the hands of families and new believers." Extend each with the place and the action, e.g. "…across Pakistan. See how a gift of an Urdu Bible reaches a new believer." | The SEO plugin's box on each ministry, after the plugin decision (Phase 6) | 12 descriptions |
| **R6** | Build "Stories from this ministry" (Phase 6 proposal 2). It's the link that makes the clusters work. | Theme code | Small feature |
| **R7** | Before adding `/sermons/`, `/events/` or `/testimonials/` to the menu, write their introductions (C7). | Kingdom Revival → Site text | 3 short texts |

Not recommended: putting keywords into the design's H1s. The current headings describe the ministry well and
were approved. The meta title, meta description and body text carry the search terms.

### Not covered

- **Search volumes, difficulty and current rankings.** No keyword data source was available. All queries are
  candidates.
- **Urdu and Roman-Urdu queries** for searchers in Pakistan (see Decisions).
- **Competitor backlink analysis**, and off-site work such as listing the ministry in Christian mission
  directories or getting links from partner churches.
- The SERP checks were five manual US searches on one day, not a tracked study.

### Decisions for you

1. **Approve which recommendations to apply** (R1–R7). R2, R3 and R6 have the most effect.
2. **Approve the content plan** (8.5). Who will write the articles, and how often? One real story a month,
   starting with cluster A, is a realistic pace.
3. **Sermons language.** If Pastor Adil's sermons are in Urdu, an Urdu or bilingual sermons section serves a
   different audience: believers in Pakistan, not donors abroad. Should the site target that audience? It would
   change cluster F.
4. **Keyword validation.** After go-live, connect Google Search Console. After about three months, compare the
   queries pages actually get with the 8.2 map, and re-assign where real data disagrees.

### Sources (search results checked)

- [Christian Aid Mission: Enslaved and Overlooked, Pakistan's Brick Kiln Workers](https://www.christianaid.org/news/2024-enslaved-and-overlooked-pakistans-brick-kiln-workers/)
- [Christian Post: Ministry aims to free over 100 Christian families enslaved in Pakistan](https://christianpost.com/news/ministry-aims-to-free-over-100-christians-enslaved-in-pakistan.html)
- [Christian Today: Freedom for Christian children who labour in Punjab brick kilns](https://www.christiantoday.com/article/pakistan.freedom.for.christian.children.who.labour.in.punjab.brick.kilns/world)
- [Barnabas Aid: Free Christian brick kiln families in Pakistan](https://www.barnabasaid.org/us/latest-needs/free-christian-families-from-brick-kilns-in-pakistan/)
- [Global Christian Relief: 50 enslaved Pakistani Christian families start new lives](https://globalchristianrelief.org/stories/50-enslaved-pakistani-christian-families-start-new-lives/)
- [National Catholic Register: Pakistani Christians seek freedom from debt slavery](https://ncregister.com/news/pakistani-christians-seek-freedom-from-debt-slavery)
- [Door of Hope International: Pakistan Urdu New Testament appeal (PDF)](https://dohi.org/wp-content/uploads/2025/06/DOHI-AL2409-8-PAKISTAN-WEB-EN.pdf)
- [Climbing For Christ: Bibles distributed to children](https://www.climbingforchrist.org/Postings/bibles-distributed-to-children)
- [Barnabas Aid: Pakistani Christian widows need your help](https://www.barnabasaid.org/us/latest-needs/pakistani-christian-widows-need-your-help/)
- [Everlasting Life Mission Pakistan: Sunday school for brick kiln kids](https://pages.donately.com/everlastinglifemissionpakistan/campaign/building-faith-transforming-lives-sunday-school-for-brick-kiln-kids)
- [Pakistan Christian Post: BACA delivers God's Word in Cholistan](https://www.pakistanchristianpost.com/head-line-news-details/8230)

---

## Kingdom Revival Ministries: Phase 9 donation system

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

### The decision

Chosen with you: **PayPal hosted checkout**, account held by **the partner abroad** (PayPal and Stripe don't accept
Pakistan-based merchant accounts, as of October 2026), with **full** confirmation and receipts in WordPress.

Why hosted checkout: card entry happens on paypal.com, so the theme never handles card data. The design's own
"Give with PayPal" button stays exactly as it is, so nothing visible changes. Embedded card fields (PayPal's on-page
buttons, Stripe Elements) would have changed the page.

### What changed

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

### Verification

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

### Fixed along the way

- The ministry's email subjects read "Private: $50 — …", because WordPress adds that prefix to private titles. They now use the raw title.
- `donate.js` already sent donors back to `/donate/?thanks=1`, but nothing handled that address; it now shows the thank-you view.

### Not covered

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

### Decisions for you

1. **Connect the account.** Who holds the PayPal Business account (the partner abroad), and its email. It must be a
   Business account that can receive payments. Then: test in Sandbox mode, switch to Live.
2. **Receipt wording.** The default receipt makes no tax-deductibility claim. If the partner entity is a registered
   charity (for example US 501(c)(3)), add its name and tax ID and the standard statement to the receipt text.
3. **FAQ update.** Change "How do I give monthly?" to point to the Monthly option and PayPal once live
   (an editable FAQ item; left as in the design).
4. **"Let us know" step.** For PayPal gifts it's no longer needed, since the gift is recorded automatically. It is still
   useful for bank, Western Union and MoneyGram, so it was left unchanged.

### Unchanged

The Donate page markup, the design's form field names, the "Where it is needed most" option, bank / Western Union /
MoneyGram details, and every other page.

---

## Kingdom Revival Ministries: Phase 11 security and accessibility

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

### Security

#### What the audit found already in place

| Area | State |
|---|---|
| Input sanitization | Every `$_GET`/`$_POST`/`$_SERVER` read is unslashed and sanitized by type (`sanitize_text_field`, `sanitize_email`, `absint`, `esc_url_raw` with allowed schemes, `wp_kses` with a short inline-tag list). Settings and page content are sanitized against their schemas, so unknown keys are dropped. |
| Output escaping | Templates print through `esc_html`/`esc_attr`/`esc_url` or the `krm_t/h/u` helpers, which escape. The few raw `echo`s are annotated and print fixed markup, escaped helper output or core oEmbed HTML. |
| Nonces | Every admin save (page content, Site text, ministry/story/sermon/event fields, Settings via `options.php`, Setup AJAX, both CSV exports) checks a nonce. |
| Capability checks | Settings, Site text and Setup need `manage_options`. Meta box saves check `edit_post`. |
| Forms | Honeypot, minimum time on form, 5 posts per visitor per 10 minutes, link-stuffing goes to "possible spam" without email, nonce for logged-in users, field length limits, email header injection blocked (Reply-To name stripped of line breaks, address validated), CSV formula injection prefixed. |
| Donations | Each PayPal notification is verified with PayPal and the receiving account. No card data. |
| Front-end JS | No user or URL input reaches `innerHTML`. |

#### Fixed

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

#### Added hardening (`inc/security.php`)

- Headers on front-end pages: `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`,
  `Referrer-Policy: strict-origin-when-cross-origin`,
  `Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()`. To change or remove them, use the filter
  `krm_security_headers`. PayPal checkout is unaffected, because it is a normal page change, not an embedded frame.
- No user-name lookup for visitors: `/?author=1` now redirects to the home page instead of to `/author/<login>/`,
  and the REST users list needs a login.
- Links that open in a new tab from formatted content fields get `rel="noopener"`.

#### Verified (local site, curl)

- Headers present on `/about/`.
- `/?author=1` → 301 to `/`. REST users list returns `rest_no_route`.
- Form post without a token → 400. Forged token → 400. Token from `/contact/` → 200 and saved. Honeypot filled → fake success, nothing saved.
- `krm_inline()`: `target="_blank"` links gain `rel="noopener"`, existing `rel` is kept, `javascript:` and `onclick`
  are stripped, and `<script>` is removed.
- The test submission and the test users were deleted afterwards.

### Accessibility

#### Already in the design

Skip link to `#main`. Menu toggles are real buttons with `aria-expanded`, open by click, Enter or Space, close with
Esc, and return focus. Donation method tabs are a full tab widget (roving focus, arrow keys, Home/End). The photo
viewer and newsletter popup are modal dialogs that keep focus inside, close with Esc and return focus. Photos open
with Enter. `:focus-visible` outlines (3px). Labelled fields with `aria-invalid`. Live regions for form status and
the gift summary. `prefers-reduced-motion` handled. Alt text on every image (`image-alt` passes on all pages).

#### Fixed (no visible change)

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

#### Verified

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

### Pixel parity

`npm run pages` and `npm run interactions` after the changes, compared with the Phase 10 run:

- Ministry-empty, Ministries overview, Partner tablet/mobile: 0%. Contact: 0.04–0.08% (the known sentence). Partner
  desktop: 0.063%, the same as Phase 10.
- Home (0.004–0.09%), Donate (0.15–0.26%), About, Ministry, Partner photo areas and five interaction states: the
  differences are inside photos and payment logos only (Media Library resized/WebP copies from Phase 10, or a remote
  photo that hadn't loaded yet). They vary from run to run. Checked side by side for Donate, the mobile menu, the
  floating donate panel and partner-church: the headings, menus and panels are identical.
- Blog and story: the known differences from the local database's live-like content.
- 19 of 30 interaction states are 0%.

### Not covered

- **Server and login security is outside a theme**: login attempt limits, two-factor login, a firewall, XML-RPC
  blocking, file-change scans and backups. Use the host or one security plugin. A Content-Security-Policy header was
  not added, because the design's inline styles and scripts would need `unsafe-inline`, which makes it of little use.
- axe checks contrast in the default state only. Hover states and text over photos weren't measured. Screen-reader
  testing (NVDA/VoiceOver) by a person was not done.
- The original HTML in `html/` was not changed. Its findings in the table are for comparison only.

### Decisions for you

1. **About's step numbers (01, 02, 03).** They are pale watermark numbers (1.18:1). They are now hidden from screen
   readers as decoration, which WCAG allows, but automated checkers will keep flagging them. To pass the checkers, they
   would need a darker grey (#8D94A9 or darker for 3:1). That is a visible design change, so it was not made.
2. **Who sees Submissions and Donations.** Now Editors and Administrators. If Donations should be Administrators only,
   that is a one-line change.
3. **Security plugin.** Choose one for the items under Not covered. If it sends the same headers, remove the theme's
   with `krm_security_headers`.

### Unchanged

How every page looks, form field names, the partnership names, the "Where it is needed most" option, and all editable
content and settings.

---

## Kingdom Revival Ministries: Phase 13 final delivery

⟪RESULT⟫

Theme **Kingdom Revival Ministries 1.7.1** (`kingdom-revival`). Requires WordPress 6.6+ and PHP 7.4+. Tested on
WordPress 7.1.4 with PHP 8.4 and Chrome. Every result in this report was produced on the local test site, not on a
real server.

**How to read this report.** Each item carries one of these labels:

| Label | Meaning |
|---|---|
| **Done, verified** | Built and checked with the QA tools or by hand; numbers given |
| **Done, not verified end to end** | Built and partly tested; the last step needs a real server, account or person |
| **Recommendation** | Proposed, not built. Needs your approval |
| **Decision needed** | Waiting for information or a choice from you |

---

### 1. Theme source code — Done, verified

The complete source is in [theme/kingdom-revival/](theme/kingdom-revival/) (118 files: about 11,400 lines of PHP,
147 KB of CSS and JavaScript, the self-hosted font, the logo and 18 SVG icons). The git branch `dev` holds the full
history; the per-phase reports (this file) are the decision log.

| Area | Files |
|---|---|
| Bootstrap | `functions.php` (version constant, loads `inc/*.php`), `style.css` (theme header), `theme.json` (editor palette, font) |
| Page templates | `front-page.php`, `home.php`, `single.php`, `single-ministry.php`, `archive-ministry.php`, `page-templates/{about,contact,donate,partner}.php`, sermon/event/testimonial templates, `page.php`, `404.php`, `search.php`, `archive.php`, `index.php`, `comments.php` |
| Header and footer | `header.php` / `footer.php` (inner pages), `header-home.php` / `footer-home.php` (home page) |
| Template parts | `template-parts/home/*` (11 home sections), `page-hero`, `give-band`, `faq`, `post-list`, `type-archive`, `type-single`, `donate-thanks` |
| Editable content | `inc/content-fields.php` (Page content box, Site text), `inc/content/*.php` (one schema per page; defaults are the design's content) |
| Content types and data | `inc/ministry.php`, `inc/content-types.php` (sermons, events, testimonials), `inc/post-meta.php`, `inc/data/*` (12 ministries, 7 stories, media list) |
| Site functions | `inc/settings.php`, `inc/customizer.php`, `inc/nav.php`, `inc/forms.php`, `inc/donations.php`, `inc/seo.php`, `inc/schema.php`, `inc/redirects.php`, `inc/performance.php`, `inc/security.php`, `inc/setup.php`, `inc/enqueue.php`, `inc/importer.php` (Setup screen), `inc/template-functions.php` |
| Front-end assets | `assets/css/site.css` and `assets/js/site.js` (the design's shared files), `assets/css/front.css` (home), `assets/css/pages/*.css` and `assets/js/*.js` (per page) |

The QA tools used to verify it are in [tools/visual-qa/](tools/visual-qa/) (see its README).

### 2. Installable theme ZIP — Done, verified

[kingdom-revival.zip](kingdom-revival.zip): 322 KB, 118 files, top folder `kingdom-revival/`. Unpacked, it was compared
file by file with `theme/kingdom-revival/` and is **identical** (version 1.7.1). Upload it under Appearance → Themes →
Add New → Upload Theme.

### 3. Original HTML files → WordPress templates — Done, verified

| Original HTML | WordPress template | WordPress content | URL |
|---|---|---|---|
| `index.html` | `front-page.php` + `header-home.php`, `footer-home.php`, `template-parts/home/*` | Static front page "Home" | `/` |
| `about.html` | `page-templates/about.php` | Page "About" | `/about/` |
| `contact.html` | `page-templates/contact.php` | Page "Contact" | `/contact/` |
| `donate.html` | `page-templates/donate.php` (+ `template-parts/donate-thanks.php` for `?thanks=1`) | Page "Donate" | `/donate/` |
| `partner.html` | `page-templates/partner.php` | Page "Become a Partner" | `/partner/` |
| `blog.html` | `home.php` | Posts page "News & Blogs" | `/blog/` |
| `blog-post.html?post={slug}` | `single.php` | 7 posts (stories) + "Story details" meta | `/{slug}/` |
| `all-ministries.html?ministry={slug}` | `single-ministry.php` | 12 `ministry` posts | `/ministries/{slug}/` |
| `all-ministries.html` (no ministry chosen) | `archive-ministry.php` | `ministry` archive | `/ministries/` |
| shared header / footer | `header.php`, `footer.php` | Menus: Main navigation, Footer Quick Links, Footer Ministries, Home Page Footer Quick Links | — |
| `assets/site.css`, `assets/site.js` | `assets/css/site.css`, `assets/js/site.js` | — | — |
| `assets/blog-data.js` | `inc/data/posts.php` (imported once by Setup) | — | — |
| `assets/krm-logo.png` | `assets/images/krm-logo.png` (or the Customizer logo) | — | — |
| *(not in the design)* | `archive-sermon.php`, `single-sermon.php`, `taxonomy-sermon_series.php`, `archive-event.php`, `single-event.php`, `archive-testimonial.php`, `page.php`, `404.php`, `search.php`, `archive.php`, `index.php` | Sermons, Events, Testimonials, other pages | `/sermons/`, `/events/`, `/testimonials/` … |

The design pages find each other **by template, not by slug**, so a page can be renamed without breaking links. The
design's old static addresses (`about.html`, `donate.html?amount=…`, `all-ministries.html?ministry=…`,
`blog-post.html?post=…`) 301-redirect to these URLs and keep their pre-fill values.

### 4. Plugins

**Required: none.** Every page, form, donation, menu, SEO tag and schema works with the theme alone.

| Plugin | Status | Why |
|---|---|---|
| **An SEO plugin: Rank Math or Yoast SEO** | Recommended — tested (Rank Math 1.0.280, Yoast SEO 28.8) | Per-page SEO titles and descriptions, sitemaps, Search Console. The theme steps aside and feeds the design's titles, descriptions and images as defaults. SureRank 1.10.3 (the current site's) also works, but its own schema has faults (§9). Without any SEO plugin the theme prints basic tags itself. |
| **An SMTP plugin** (e.g. WP Mail SMTP, FluentSMTP) | Recommended — not tested | Most hosts don't deliver WordPress mail reliably. Form notifications and donation receipts depend on it. Submissions and donations are saved in WordPress either way. |
| **Page caching** (the host's cache, or WP Super Cache / LiteSpeed Cache) | Recommended — not tested | Pages are cache-safe (no per-visitor data; the form token doesn't expire). Avoid "delay JavaScript" and extra lazy-load options (readme → Speed). |
| **A security plugin** (e.g. Wordfence or Solid Security) | Recommended — not tested | Login limits, two-factor login, firewall, XML-RPC blocking, file scans. Not something a theme should do. |
| **Backups** (host backups or a backup plugin) | Recommended | Before installing on the live site, and ongoing. |
| Regenerate Thumbnails | Optional | Only to rebuild WebP copies of images uploaded before the theme was installed. |
| Elementor, Robo Gallery, SureForms (on the live site today) | **Deactivate after switching**, if nothing else uses them | The theme doesn't use them. Their old pages already redirect (§9). |

### 5. Installation — Done, verified (on a fresh test site and on a copy shaped like the live database)

1. **Back up** the live site (database and `wp-content`). The theme deletes nothing, but this is the safe first step.
2. Appearance → Themes → Add New → **Upload Theme** → `kingdom-revival.zip` → Install → **Activate**.
3. **Kingdom Revival → Setup → Run setup.** It imports the design's images and video into the Media Library, creates
   the 12 ministries, 7 stories, the About / Contact / Donate / Become a Partner / News & Blogs pages and the menus.
   Tick the two boxes to set the static front page + posts page and "Post name" permalinks. Anything that already
   exists with the same slug or name is skipped, and Setup can be run again safely.
4. Settings → Permalinks must be **Post name** (Setup can set it).
5. Settings → Reading: untick "Discourage search engines" when going live.
6. Install the SEO and SMTP plugins (§4).

**Going live on the existing database** (the current Elementor site), in addition:
- Move WordPress's "Hello world!" post to the trash (otherwise it appears in News & Blogs).
- Deactivate Elementor, Robo Gallery and SureForms if they're no longer used.
- Regenerate the SEO plugin's sitemap once, then resubmit it in Google Search Console.
- Settings → General → Timezone: **Karachi**.

### 6. Theme configuration — Done, verified

| Where | What |
|---|---|
| **Kingdom Revival → Settings** | Contact details (phone, email, address, WhatsApp), social links, impact numbers, form notification addresses, newsletter service URL, **Online giving** (PayPal email, Sandbox/Live, receipts), bank / Western Union / MoneyGram details, **Redirects** (old → new, one per line), Structured data (Organization or NGO, city/province/country), Images (WebP on/off) |
| **Appearance → Customize → Kingdom Revival** | The same contact details, social links and impact numbers, with live preview |
| **Appearance → Customize → Site Identity** | Logo (use one at least 112×112 px for search results; the bundled one is 99×99), site icon |
| **Appearance → Menus** | Main navigation (a custom link `#` with sub-items becomes a dropdown; give "All ministries" the CSS class `menu-all`), Footer Quick Links, Footer Ministries, Home Page Footer Quick Links |
| **Kingdom Revival → Site text** | Header name, top bar button, footer wording, ministry and story page wording, `/ministries/`, 404, sermons/events/testimonials headings |
| **SEO plugin** | Title separator "\|" (so titles read "Baptisms \| Kingdom Revival Ministries"). Rank Math: finish or skip its setup wizard; Titles & Meta → Local SEO → Organization + logo. Yoast: Site representation → Organization + name + logo |
| Filter `krm_security_headers` | Remove or change the theme's security headers if the host or a plugin already sends them |

Full details: [theme/kingdom-revival/readme.txt](theme/kingdom-revival/readme.txt) (also inside the ZIP).

### 7. Content editing — Done, verified

No page builder and no plugin. With nothing edited, every page shows the design's original content.

| To change | Go to |
|---|---|
| Text, headings, images, buttons and links on Home, About, Contact, Donate, Become a Partner, News & Blogs | **Pages → edit the page → "Page content" box**, one section per part of the page |
| Ministries (title, short description, focus, points, photos, home explorer photo, order) | **Ministries** |
| Stories | **Posts**: normal block editor. Ministry and reading time in "Story details". Use Paragraph (class `lead` for the opener), Heading, Quote, Image, List (class `checks`), Gallery, and `[krm_give]` for the give box |
| Sermons, Events, Testimonials | Their own menus. `[krm_testimonials count="3"]` shows testimonials in any page |
| Form messages and donation records | **Kingdom Revival → Submissions / Donations** (Editors and Administrators only; CSV export) |

Tips that apply everywhere:
- Each Page content section has **"Restore this section to the original design"**.
- Images: "Choose image" opens the Media Library; the alt text comes from the library.
- Lists (cards, photos, FAQ questions…) can be added to, removed and reordered.
- Highlighted heading words: `<em>…</em>` (home hero: `<span style="color: #F5A04A">…</span>`).
- Placeholders keep pages in step with Settings: `{phone}`, `{email}`, `{address}`, `{year}` in text; `{donate}`,
  `{about}`, `{contact}`, `{whatsapp}`, `{paypal}`, `#give` … in links.
- **Fixed on purpose:** form field names and contact topics, the four partnership names and the "Where it is needed
  most" gift option, because the forms and the donation script depend on them.

Verified in the real dashboard in Phase 5: heading edits, added and removed list items, Site text, Restore, Media
Library images, an event in the block editor. A repeatable dashboard test exists (`tools/visual-qa/admin_check.js`,
needs an admin password), but no saved result of it was found, so it is not counted here.

### 8. Donation integration setup

**Built — Done, verified up to PayPal's side:** the design's Donate page sends donors to **PayPal's hosted checkout**
(PayPal balance or card; card details never touch WordPress) for one-time gifts and monthly subscriptions, with the
amount and ministry filled in. PayPal's payment notification (IPN) is verified with PayPal and the receiving account,
saved as a Donation, and the donor gets a receipt email. The donor returns to a thank-you view. Bank transfer, Western
Union and MoneyGram stay as in the design (manual).

**Not yet live — Decision needed:** no PayPal account is connected, and no payment has been completed end to end,
because PayPal's notification can't reach a local test site.

Setup steps:
1. The **PayPal Business** account holder (the partner abroad; PayPal doesn't open merchant accounts for Pakistan)
   confirms the account can receive payments.
2. Create a free account at developer.paypal.com. Use its **sandbox Business** email in Kingdom Revival → Settings →
   Online giving → PayPal account email, with **PayPal mode: Sandbox**.
3. On the staging or live server (it must be reachable from the internet), give $1 from the sandbox **Personal**
   account, once one-time and once monthly. Check:
   Kingdom Revival → Donations shows both, marked "sandbox test"; the receipt email arrived; the ministry
   notification arrived; the browser returned to `/donate/?thanks=1`.
4. Enter the **real** PayPal Business email, switch to **Live**, give one real small gift, and refund it from PayPal
   (the Donation should change to Refunded).
5. Edit the receipt text (Settings → Online giving). Add the partner's charity name and tax ID **only** if it is a
   registered charity.
6. Update the Giving FAQ answer "How do I give monthly?" (Pages → Donate → Page content → FAQ) to point to the
   Monthly option.

Nothing needs to be set in PayPal's own settings: each checkout carries its own notification address
(`/wp-admin/admin-post.php?action=krm_paypal_ipn`) and return address.

### 9. SEO implementation report — Done, verified

Details: [Phase 6](#kingdom-revival-ministries-phase-6-technical-seo-urls-and-metadata), [Phase 7](#kingdom-revival-ministries-phase-7-structured-data-schema-markup), [Phase 8](#kingdom-revival-ministries-phase-8-keyword-grouping-and-topical-authority).

| Item | What's in place | Verified |
|---|---|---|
| URLs | Clean permalinks: `/about/`, `/donate/`, `/blog/`, `/ministries/{name}/`, `/{story}/` | All 26 design pages answer 200 |
| Old site's addresses | All 27 sitemap URLs of the current live site, plus 6 gallery pages, keep working: unchanged or **one 301** to the new page. Editable in Settings → Redirects | Phase 6 |
| Metadata | Exactly one title, description, canonical, Open Graph set and Twitter card per page, either from the SEO plugin (theme prints none) or from the theme | 30 / 30 pages with Yoast, Rank Math, SureRank and with no plugin (Phase 6); ⟪SEO13⟫ |
| Design defaults | Design titles/descriptions/hero images offered to the plugin where its field is empty; anything typed in the plugin wins | Phase 6 |
| Indexing | No accidental `noindex`; search, 404 and the donation thank-you view are `noindex` on purpose. robots.txt blocks only `/wp-admin/` | ⟪INDEX13⟫ |
| Sitemaps | 35 URLs, all 200 and indexable; redirected addresses and empty lists left out | Same 35 with each setup (Phase 6) |
| Structured data | Organization, WebSite, WebPage, BreadcrumbList (matches the visible breadcrumb), BlogPosting, Event, VideoObject. No duplicates when a plugin is active | Schema.org Validator 0 errors / 0 warnings on 9 page kinds × 3 setups (Phase 7); ⟪SCHEMA13⟫ |
| Keywords | One primary search intent per page, 25 pages, no two pages competing; strategy built on Pakistan-specific ministry topics | Strategy only (Phase 8); no content was changed |

**Recommendations (not built):** the five internal-link additions in Phase 6 §6.5 (e.g. "Stories from this ministry"
on ministry pages), and the content recommendations R1–R7 in Phase 8 (R2, R3 and R6 matter most), plus its
content plan (topic clusters A–F). **Decisions needed:** which SEO plugin; "|"
separator; whether to keep `/donate-us/` and `/latest-news-blogs/` as the addresses; Organization or NGO; a ≥112 px
logo. **Not verified:** Google's Rich Results Test (it can't reach a local site; run it after go-live); search
volumes (no keyword tool was available).

### 10. Performance test results

⟪PERF⟫

### 11. Final regression check (this phase) — Done, verified

⟪REGRESSION⟫

### 12. Remaining issues and limitations

⟪REMAINING⟫

### Decisions for you (all open items in one place)

⟪DECISIONS⟫
