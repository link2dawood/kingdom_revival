# Kingdom Revival Ministries: Phase 1 analysis (HTML → WordPress)

Source: `html.zip`, extracted to `html/` (8 HTML pages, 3 JS/CSS files, 1 logo).
Nothing in `html/` has been modified.

---

## 1. File inventory

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

## 2. Pages and content record

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

### Section outline per page

**Home** (`#top` … `#contact`): top bar (phone, email, 3 social icons, Donate Now) · header + nav · hero with background video `a.mp4` + donation card (5 amounts) · overlapping stats bar (5 stats) + "Our prayer" panel · About (`#about`, 2-image collage, 4 checks) · **12-ministry explorer** (`#all-ministries`, click a list to swap the image/number/title/desc) · 6 cause cards (`#ministries`) · Field stories (`#field-stories`, video + 3-photo gallery) · Pray/Give/Go · Founder (`#founder`) · Matthew 28:19 CTA banner · homepage-own floating donate panel · footer (`#contact`).

**About:** page hero · story + 3-image collage + "12 ministries" badge · Mission/Vision cards · "Word and deed" 4 cards · 5-stat band · Founder (`#founder`) · 3 ways (Pray/Give/Go with links) · 8-photo gallery · give band + give card.

**Ministry:** hero (first photo as background) · "Ministry focus" + "How this ministry serves" panels · photo gallery (0–12 photos, empty-state text) · give band with a ministry-specific give card.

**News & Blogs:** hero · featured (newest) post · toolbar with search · category chips (All, Scripture, Children, Church, Care, Prayer) + result count · post grid · empty state · 3-item impact strip · give band.

**Blog post:** progress bar · hero (cover bg, tag, meta, ministry link) · article body (lead paragraph, h2, quote, image+caption, check list, inline give box, gallery) · share (WhatsApp/Facebook/X/copy) · author box · prev/next · sidebar (give card, ministry card, prayer card) · give band · 3 related posts.

**Contact:** hero · 3 contact cards · form (name, email, phone, topic select, message) · side cards (prayer → preselects topic, donate, office location) · Google Maps iframe.

**Donate:** hero · step 1 gift form (frequency, 6 amounts + Other, fund select of 13) + live summary · step 2 tabs: PayPal (paypal.me/Givetotheneedy7/{amount}USD), Bank transfer (8 rows, copy buttons), Western Union, MoneyGram · step 3 "Let us know" (prefilled mailto, WhatsApp) · 4 impact cards · Giving FAQ (4 `<details>`).

**Partner:** hero · 3 "why" items · 4 option cards (Prayer / Monthly — featured / Sponsor / Church) · 3 steps · sign-up form with conditional amount/ministry/church fields + thank-you state · Partnership FAQ (5) · Philippians 1:3–5 verse band.

### Data sets embedded in JS (will become WordPress content)

- **12 ministries** (`all-ministries.html`): slug, title, description, focus, 3 points, photo list. Groups (from the nav): *Children & Families* (Children Ministry, Orphan & Children Care, Brick Kiln Children, Widows Ministry), *Freedom & Care* (Freedom from Slavery, Free Medical Camps, Clean Water Project), *Gospel & Church* (Outreach, Urdu Bibles, Baptisms, Deliverance & Healing, Leadership & Discipleship). The homepage explorer holds its own copy (short desc + image) in a **different order**.
- **7 blog posts** (`blog-data.js`): slug, title, category, ministry slug/name, date (2026-07-24 → 2026-10-14), readTime, cover, excerpt, body blocks (`p, h2, quote, img, gallery, list, give`).
- **6 homepage cause cards**, **5 stats**, **4 about checks**: in the homepage `Component` class.

---

## 3. Shared components

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

## 4. CSS architecture

- **Tokens** (`site.css :root`): `--navy #0C1C4F`, `--navy-deep #081030`, `--red #D4070B`, `--red-dark #B10609`, `--orange #F5A04A`, `--ink #141B33`, `--muted #5A6275`, `--soft #F4F6FB`, `--line #E3E7F0`, `--radius 12px`, `--wrap 1200px`. The homepage uses hard-coded hex values and a 1240px container, plus extra colours (`#E18426` orange button, `#08133A` footer, `#1A2550`).
- **Layering:** `site.css` (shared) → per-page inline `<style>` (30–97 lines each) → the homepage, which is ~95% inline `style=""` attributes plus 4 `<style>` blocks (field-stories classes, hover classes `.k-y/.k-o/.k-g/.k-card`, and duplicated nav/lightbox/newsletter CSS).
- **Breakpoints:** 900px (nav → hamburger; hover-open at ≥901px), plus page-specific 1000 / 960 / 820 / 760 / 680 / 640 / 560 / 520px. The homepage relies mostly on flex-wrap/`clamp()`/`auto-fit` rather than media queries.
- **Motion:** card hover lifts, `lb-in`, `lb-spin`, `float-in`, `nl-up`; `prefers-reduced-motion` is respected in `site.css`.
- **Typography:** Plus Jakarta Sans 400–800. Inner pages load it from Google Fonts CSS. The homepage inlines `@font-face` from fonts.gstatic.com, and the bundle carries one latin woff2 (27 KB).

---

## 5. JavaScript inventory

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

## 6. Assets

- **Local:** `krm-logo.png` only, plus the font.
- **Remote (hot-linked from the live site):** ~44 literal image URLs + ~70 more built in the ministry script, all under `kingdomrevivalministries.com/wp-content/uploads/2026/07|08/`, **and several under `/uploads/elementor/thumbs/`**. Those are Elementor-generated crops, and they can disappear if Elementor is deactivated or its cache is cleared.
- **Video:** `uploads/2026/08/a.mp4` (hero background + field-stories player).
- **Payment logos:** PayPal, Western Union and MoneyGram PNGs from uploads.
- **Icons:** all inline SVG (Feather-style). No icon font.
- **Third-party:** Google Fonts, Google Maps embed, paypal.me, wa.me, Facebook/X share intents.

---

## 7. Link map

- Internal links are all relative `.html` (131 × `all-ministries.html?ministry=…`, 28 × `index.html`, 17 × `index.html#all-ministries`, etc.). All of them get rewritten to WordPress permalinks.
- Hash targets in use: `#main #top #about #all-ministries #ministries #field-stories #founder #donate #contact #give #support #join #options #contact-form`. All of them stay.
- Query-string contracts that must keep working: donate `?amount&frequency&for&method`, partner `?type`, newsletter `?newsletter=1`.
- No broken internal links found. The only dead links are the homepage social icons (`#contact`).

---

## 8. HTML → WordPress mapping

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

## 9. Issues found in the source (kept as-is unless you say otherwise)

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

## 10. Decisions needed before Phase 2

1. **Homepage header/footer:** keep the homepage's own variant (social icons, 3-column footer), or use the inner-page header/footer everywhere?
2. **Forms:** send contact/partner/newsletter through WordPress (`wp_mail`, saved as entries, nonce + honeypot) with the same UI, or keep the current `mailto:` behaviour?
3. **Images:** import all remote images (including `elementor/thumbs` crops) into the new theme's media library during setup, or keep hot-linking?
4. **Is this replacing the live WordPress/Elementor install** at kingdomrevivalministries.com (same database), or going on a fresh install? This decides content import, redirects and the risk to existing uploads.
5. **Social links:** real Facebook/YouTube/Instagram URLs, or hide the icons until you have them?
6. **Source issues in §9 (items 2–5):** fix them, or keep them exactly as designed?
