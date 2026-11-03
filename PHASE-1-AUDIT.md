# Kingdom Revival Ministries: Phase 1 audit (unification, All Ministries page, performance, cleanup)

**Result: the theme itself is small and in good shape. The slow mobile score comes mainly from plugins on the
live site, not from the theme.** On the live home page (measured 2026-11-03) the browser downloads **1.6 MB of
CSS, and 29 KB of it is the theme's**. The rest comes from Elementor, Elementor Pro, Header Footer Elementor,
the WordPress block-editor bundles, 18 Google Font weights of Roboto and Dashicons. The theme does not use any of
these. The live site also has three problems that are configuration, not theme code:
- its menus are the old Elementor site's menus (old URLs that only work through 301 redirects, one entry listed
  twice, no Partner or Donate);
- the logo is a 500×500, 171 KB PNG shown at 64 px;
- every page prints two canonical links and two meta descriptions.

The requested header/footer unification and the new All Ministries page **conflict with the project's
pixel-parity rule** (every WordPress page must match the approved HTML design). They need your decisions before
Phase 2 starts (see [Decisions for you](#9-decisions-for-you)).

**Nothing was changed, deleted or committed in this phase.** This file is the only new file.

## Contents

1. [Scope and method](#1-scope-and-method)
2. [Header and footer](#2-header-and-footer)
3. [All Ministries page](#3-all-ministries-page)
4. [Logos and images](#4-logos-and-images)
5. [Mobile performance](#5-mobile-performance)
6. [Unused CSS, JavaScript, assets and files](#6-unused-css-javascript-assets-and-files)
7. [Responsive layout, photo viewer, icons, navigation, SEO](#7-responsive-layout-photo-viewer-icons-navigation-seo)
8. [Dependency map](#8-dependency-map)
9. [Decisions for you](#9-decisions-for-you)
10. [Proposed plan for the next phases](#10-proposed-plan-for-the-next-phases)

---

## 1. Scope and method

**What was inspected**
- **Theme code** (`theme/kingdom-revival/`, version 1.7.4, 133 files): every PHP template, `inc/*.php`, the
  CSS and JS files, icons, images, fonts, `theme.json`, custom post types, menus, the importer and SEO/schema code.
- **Original design** (`html/`, reference only).
- **Data folder** (`Kingdom Revival ministries-Data_/`, 704 MB). It holds 243 files: 160 JPG, 63 HEIC, 16 MP4,
  3 PNG and 1 JPEG. It was already inventoried in [DATA-ASSET-INVENTORY.md](DATA-ASSET-INVENTORY.md) (Phase 14,
  task 4), so this audit reuses that inventory.
- **Live site** https://kingdomrevivalministries.com/: 8 pages downloaded. 5 pages (Home, About, Children
  Ministry, Blog, Donate) were loaded in Chrome emulating a phone (390×844, 3× pixel density). For each page the
  audit recorded CSS coverage, bytes per source and Largest Contentful Paint (LCP). Every internal link was
  requested to check its status. The script is in `F:/krm-work/audit/coverage.js` (outside the repo).

**Not measured:** a Lighthouse/PageSpeed score. LCP was measured on a fast connection without CPU throttling
(2.6–3.1 s). PageSpeed's mobile test slows the network and CPU, so its LCP will be higher. Use the byte counts
below to see what is slow, not to predict the score.

**Live site = theme 1.7.4.** The live pages load `theme-global.css?ver=1.7.4`, so they run the same theme code
as this repo. The remaining differences come from plugins, menus and settings in the live database.

---

## 2. Header and footer

### What exists

All pages already share one menu: both headers call `krm_primary_menu()` (`inc/nav.php`). The header and footer
markup itself comes in **two versions**, and both copy the approved design:

| | Home page | All other pages |
|---|---|---|
| Files | [header-home.php](theme/kingdom-revival/header-home.php), [footer-home.php](theme/kingdom-revival/footer-home.php) | [header.php](theme/kingdom-revival/header.php), [footer.php](theme/kingdom-revival/footer.php) |
| Origin in the design | `html/index.html` (React bundle) | `html/assets/site.css` + each inner page |
| Styling | 37 inline `style=""` attributes | CSS classes in `theme-global.css` |
| Top bar | Phone, email, **Facebook / YouTube / Instagram icons**, Donate | Phone, email, Donate (no social icons; email hidden ≤620 px) |
| Header | Not sticky; logo links to `#top`, Donate links to `#donate` | **Sticky** with blur; logo links to `/` |
| Footer columns | 3: brand (logo in a 76 px white circle), Quick links, Contact | 4: brand, Quick links, **Ministries**, Contact |
| Footer menu used | `footer_home` | `footer_quick` + `footer_ministries` |
| Footer tagline | `footer.tagline_home` | `footer.tagline` |
| Headings | `<h3>` | `<h2>` |
| Phone icon | Slightly different SVG path than inner pages | (see [§7](#icons)) |
| Wrapper | Whole page inside an inline-styled `<div>` closed in `footer-home.php` | none |

**Duplicated code:** the `<head>` block, skip link, top bar contacts, brand block and nav are repeated in both
headers. The address/phone/email block and the copyright row are repeated in both footers.

### What unification means

Merging the two versions into one `template-parts/site-header.php` / `site-footer.php` is about a day of work and
removes the duplicates above. **Whichever version wins, the other pages stop matching their approved design**:
- inner-page version everywhere: the home page header, top bar and footer change;
- home version everywhere: all inner pages change.

The pixel-parity rule (Contact is the only page allowed to differ) then has to be relaxed for these two areas.
Decision 1 asks which version becomes the standard.

---

## 3. All Ministries page

### What exists

- **12 ministries** are stored as the `ministry` post type ([inc/ministry.php](theme/kingdom-revival/inc/ministry.php)).
  Each has a title, excerpt, focus, points, gallery and explorer image, and its own page at `/ministries/{slug}/`.
  The data is complete. `krm_ministries()` in `inc/template-functions.php` already returns all 12 with title,
  description, URL and image.
- **`/ministries/` is not a listing.** [archive-ministry.php](theme/kingdom-revival/archive-ministry.php) shows the
  design's "Choose a ministry to explore" box: a heading, a paragraph and one button back to the home page. The
  live page is the same.
- **Every "All ministries" link points to the home page explorer** `/#all-ministries`. You chose this on
  2026-11-02 (Phase 14, task 3); this request reverses that decision. The link is set in 9 places:
  `inc/nav.php:126` and `:242`, `inc/template-functions.php:435`, `inc/importer.php:622` and `:683`,
  `inc/schema.php:36` (breadcrumb), `inc/redirects.php:45` (`/services/`), and the default content of
  `inc/content/about.php:41` and `inc/content/site.php:111, 143`.
- **The approved design has no 12-ministry grid page.** `html/all-ministries.html` only shows one ministry (chosen
  by `?ministry=`) or the "choose a ministry" box.

### What the new page needs

The simplest version without new design work:
- rebuild `archive-ministry.php` as the page;
- use the inner-page hero (`template-parts/page-hero.php`) on top;
- below it, a 12-card grid using the card the home page already uses for causes (`template-parts/home/causes.php`,
  `.k-card`: photo, tag, title, text, button), filled by `krm_ministries()`;
- point the 9 links above to `/ministries/`;
- add an editable intro (Site text);
- keep the archive in the sitemap;
- add `CollectionPage` / `ItemList` schema.

Decision 2 covers the layout and order. Decision 3 covers what happens to the home page explorer.

---

## 4. Logos and images

| Item | Now | Problem | Fix |
|---|---|---|---|
| **Live header/footer logo** | `cropped-R-BG-Logo.png`, Customizer logo, **500×500, 171 KB**, shown at 64 px | `krm_logo_url()` asks for size `full` ([inc/template-functions.php:499](theme/kingdom-revival/inc/template-functions.php#L499); header and footer call it without a size). About 12× more data than needed | Request a 192 px copy (64 px × 3 for phone screens), or register a `krm-logo` size and serve WebP. Expected size 8–15 KB |
| Bundled logo (fallback) | `assets/images/krm-logo.png`, 99×99, 13 KB | Slightly soft on 2×/3× screens (99 < 128/192 px) | Make a 192 px version from the 1254 px church logo in the data folder |
| Schema logo | `krm-logo-large.png`, 512×512, **289 KB** | Only read by Google (`inc/schema.php`), never by visitors. Larger than it needs to be | Optional: recompress (lossless PNG optimisation typically halves it) |
| Theme content photos | 13 JPEGs in `assets/images/content/`, 900–1600 px, 119–316 KB each, **3.5 MB total** | They are 90% of the theme zip (3.8 MB). Setup copies them to the Media Library, which makes WebP sizes, so visitors already get smaller copies | Optional: recompress at quality 80 (about 40% smaller zip). No visible change |
| Home explorer image (live) | `src` is the 1440 px `-scaled.jpg` (486 KB) | `srcset` is present, so phones pick a smaller copy. Not a real problem | none |
| Ministry page gallery (live, phone) | **4.1 MB of images** on Children Ministry | The first 3 photos load eagerly, and `sizes="(max-width: 640px) 100vw…"` at 3× density selects the 1152–1536 px copies | Lazy-load photos 2 and 3. Cap density with a smaller `sizes` value or a 768 px size for the grid |
| Cookie banner (plugin) | 52 KB inline SVG with base64 PNG, on every page | Inflates every HTML document (home HTML is 164 KB) | Plugin setting / different cookie plugin |

The data folder has hi-res originals (1254 px logo, 2560 px+ photos). It is the right source for new logo
versions. Inventory decisions 1, 2, 3, 5 and 6 were already applied in 1.7.4.

---

## 5. Mobile performance

### Live measurements (phone emulation, unthrottled)

| Page | Requests | CSS | of which theme | JS | of which theme | Images | LCP | LCP element |
|---|---|---|---|---|---|---|---|---|
| Home | 68 | 1,608 KB | 29 KB | 423 KB | 43 KB | 501 KB | 2.9 s | hero background (WebP, 116 KB) |
| About | 58 | 1,546 KB | 39 KB | 275 KB | 36 KB | 1,207 KB | 3.1 s | hero background |
| Children Ministry | 52 | 1,465 KB | 35 KB | 170 KB | 36 KB | 4,133 KB | 2.6 s | hero background |
| Blog | 52 | 1,466 KB | 36 KB | 172 KB | 38 KB | 2,514 KB | 3.1 s | hero background |
| Donate | 51 | 1,469 KB | 40 KB | 176 KB | 42 KB | 964 KB | 2.7 s | hero background |

These are decoded sizes; the server compresses text (Brotli), so the download is smaller. The number of
render-blocking files is still what counts: **33–46 stylesheets in `<head>` on every page.** All of them must
load before the first paint, and only 1–2% of their CSS is used.

### Where the CSS and JS come from (home page)

| Source | Bytes | Used by the theme? |
|---|---|---|
| Elementor Pro (4 files) | 510 KB CSS | No |
| Elementor (21 files + 2 generated `post-30/38.css`) | 335 KB CSS, 229 KB JS | No. Pages 30 (Home) and 38 still have Elementor data, so Elementor loads its CSS, but the theme never prints that content |
| WordPress `editor`, `block-editor`, `components`, `media-utils`, `patterns`, `preferences`, `reusable-blocks` | 435 KB CSS | No. Editor styles on the public site, loaded by a plugin (likely SureForms or FormLayer). The theme already removes core block CSS (`inc/setup.php:54`) |
| Google Fonts (Roboto + Roboto Slab, 18 weights each) | 121 KB CSS + 37 KB fonts | No. The theme self-hosts Plus Jakarta Sans. Elementor adds Roboto |
| jQuery + jQuery Migrate + jQuery UI core | 117 KB JS | No. Theme JS is plain JavaScript |
| Header Footer Elementor + Font Awesome | 82 KB CSS | No. The header and footer come from the theme |
| Dashicons | 58 KB CSS | No (admin only in the theme) |
| CookieAdmin, FormLayer, SureForms, Robo Gallery, Astra Sites (starter-template preview script), WPForms Lite, Zeffy | ~60 KB | Theme forms/donations do not need FormLayer, SureForms, WPForms or Zeffy. Robo Gallery and Astra Sites are not used |
| **Theme** (`theme-global.css`, `front.css`/`site.css` + page CSS, `site.js`, page JS, font) | 29–40 KB CSS, 36–43 KB JS, 27 KB font | Yes |

### What it will take to reach 90+

1. **Remove what the theme does not use (site admin, biggest effect).** Deactivate Elementor, Elementor Pro,
   Header Footer Elementor and Astra Sites; that takes away about 1.2 MB of CSS, Roboto, jQuery for Elementor,
   and 30+ requests. Also remove whichever of FormLayer, SureForms, WPForms Lite and Zeffy is not in use.
   - **Check first** that no page is still built with Elementor. In particular, the live Home page (ID 30) and
     page 38 have Elementor CSS.
   - **Back up first.** Deactivating can be undone; deleting Elementor data cannot.
   - From what was measured, this step is what takes the score to 90+. Theme changes on their own cannot.
2. **Theme-side tuning (Phase 2+):**
   - serve the logo at 192 px;
   - lazy-load gallery photos 2–3;
   - minify `site.js` (37 KB → about 20 KB) and the CSS (about 25% smaller);
   - inline the small above-the-fold CSS (top bar, header, hero) and load `theme-global.css` without blocking.
     This one needs care because the CSS cascade order matters;
   - optionally dequeue Dashicons and editor styles for logged-out visitors as a safety net, if a plugin keeps
     adding them.
3. **Caching:** the server is LiteSpeed. Static files already carry 1-year cache headers, and HTML has
   `max-age=0`. A page cache (LiteSpeed Cache plugin, using its CSS/JS tools sparingly so it does not break the
   pixel match) would cut server time.

---

## 6. Unused CSS, JavaScript, assets and files

### Theme CSS and JS

- **CSS is lean.** All stylesheets together are 82 KB, and a page loads 29–40 KB of them.
  - Coverage on the live pages: `front.css` 60% used, `site.css` 38–71%, page CSS (`about`, `blog`, `ministry`,
    `donate`) 62–86%.
  - `theme-global.css` is 17–46% used per page. That is expected: it holds the components shared by all pages, so
    any single page uses only part of it. It does not mean the rest is dead.
  - Only 31 of 686 selectors appear in more than one file, and most of those are deliberate media-query overrides.
  - **Not yet done:** coverage merged over *all* pages, which is the only way to find CSS that no page uses. That
    needs the local test site and is planned for Phase 2.
- **JS:**
  - `site.js` (37 KB, unminified) loads on every page and holds the menu, image viewer, popups and forms.
  - Page scripts (1–7 KB each) load only on their own page.
  - No library dependencies.
  - The design's script is deliberately kept close to the original (see CLAUDE.md).

### Theme files

| File | Status |
|---|---|
| `assets/icons/shield24.svg` | **Not used.** The home stats draw their shield inline (`inc/home.php:96`). Safe to delete |
| `assets/icons/*` (other 17) | All used (1–7 references each). The size variants (`heart`/`heart18`/`heart24`, `check`/`check16`, `arrow`/`arrow16`, `clock`/`clock24`) differ in size and stroke, so they are not duplicates |
| `krm-logo-large.png` | Used by schema only (see §4) |
| `screenshot.png` | Required by WordPress (theme preview) |
| All templates and template parts | Used. `archive-event.php`, `archive-sermon.php`, `single-event.php`, `single-sermon.php` and `taxonomy-sermon_series.php` are small wrappers for the Sermon/Event post types. Keep them unless those types are dropped |
| `assets/css/pages/page.css` + `blog.css` | Both load on generic pages (`inc/enqueue.php:62`) for fallback story cards. Fine |

### Project folder (outside the theme)

| Item | Size | Status |
|---|---|---|
| `Kingdom Revival ministries-Data -….zip` | 737 MB | Git-ignored. Duplicate of the extracted data folder. Can be moved off C: (C: has 43 MB free) |
| `Kingdom Revival ministries-Data_/` | 704 MB | Git-ignored. Source folder, keep |
| `tools/visual-qa/cache/` | 46 MB | Git-ignored QA image cache. Can be cleared any time |
| `tools/visual-qa/__pycache__/` | 9 KB | Git-ignored |
| `html.zip` | 218 KB | Same content as `html/`. Keep as the original delivery |

**Disk warning:** C: has **43 MB free**. A full visual-QA run writes about 900 MB. QA output must go to F:
(as in Phase 14), or space must be freed before Phase 2's checks.

---

## 7. Responsive layout, photo viewer, icons, navigation, SEO

### Responsive layout and photo viewer

- **Phase 14 (2026-11-02) found no defects on the local test site:**
  - 29 pages × 9 widths = 261 checks: no horizontal scroll and no stretched images;
  - photo viewer 54/54 checks (fit, pinch/scroll zoom, drag, rotate left/right, reset, next/previous,
    orientation change).
- **This audit found no new layout defect in the code.** The live pages were not re-tested at every width in
  this phase.
- The sideways Urdu Bibles photo (inventory decision 1) was fixed in 1.7.4.
- If you have seen a specific responsive, zoom or rotation problem, please send the page, the device or width,
  and what happens. Nothing in the code points to one.

### Icons

- **Two icon systems:**
  - 18 SVG files loaded with `krm_icon()`;
  - **46 inline SVGs** written directly in 13 templates (`donate.php` 12, `partner.php` 6, `contact.php` 5,
    `header-home.php` 5, …).
- Inline copies can drift apart. Example: **the phone icon has different path data in `header.php` and
  `header-home.php`**.
- The social icons (Facebook, YouTube, Instagram) exist only in the home header.
- Fix: move the repeated inline icons into `assets/icons/` and use `krm_icon()` everywhere. No visible change,
  except where two versions already differ.

### Navigation and internal links

- **The theme's routing is correct.** Phase 14 task 3 tested every route, and `/contact/` has no `#contact`.
- **The live site's menus are wrong.** The primary menu (`#site-menu`) and the footer "Quick links" are the old
  Elementor site's WordPress menus, not the ones the theme's Setup creates.
- Live primary menu today:
  - Home, About (with sub-items Orphanages Care and Bricks kiln slaves children);
  - Baptisms, Urdu Bibles Distribution, **Deliverance and Healing Ministry (listed twice)**, Widows Ministry,
    Outreach Ministry, Orphanage and Underprivileged Children Care, Leadership and Discipleship, Freedom from
    Slavery Missions, Free Medical camp for Brick Kiln Slaves and Village People, Clean Water Project;
  - Latest News & Blogs, Contact;
  - **no Partner, no Donate, no All ministries**, and not the design's grouped ministry menu.
- **All 14 old URLs work only through a 301 redirect** (for example `/baptisms/` → `/ministries/baptisms/`,
  `/donate-us/` → `/donate/`, `/latest-news-blogs/` → `/blog/`).
- Live footer quick links: Home, About, Latest News & Blogs (`/latest-news-blogs/`), Donate us (`/donate-us/`),
  Contact.
- **Fix (no code):** under Appearance → Menus, assign the menus Setup created (or re-run the Setup menu step) to
  the Primary and footer locations. Then delete the old menus.
- The theme's 301 redirects should stay for old links from other websites.

### SEO

- The live site uses **SureRank**, and the theme correctly hands metadata over to it (`inc/seo.php`,
  `krm_seo_plugin()`).
- **Every page prints two canonical links, two `og:title` tags and (on most pages) two meta descriptions.**
  - The second set is SureRank's.
  - The first set comes just before it, together with `<meta name="google" content="nositelinkssearchbox">`. It
    is **not printed by the theme**: the markup differs from `inc/seo.php`, and the theme prints nothing when
    SureRank is active.
- A second JSON-LD block, an `Organization` with **an empty `"name": ""`** and a logo without a URL, also comes
  from another plugin. Google treats this as invalid structured data.
- The body classes show `angie` (an Elementor add-on) and Header Footer Elementor. The duplicate tags very likely
  come from one of these, or from WordPress's own canonical tag plus a plugin.
- Deactivating the Elementor family (§5) should clear them. Re-check with `tools/visual-qa/seo_check.py` against
  the live URL afterwards.

### Centralized global theme system

This already exists:
- tokens and shared components in `assets/css/theme-global.css`;
- settings in `krm_opt()` (Settings + Customizer);
- global text in Site text (`krm_site_text`);
- per-page editable content.

The gap is the home page: **its header, footer and section templates carry 157 inline `style=""`
attributes**, copied from the React design so the page matches it pixel for pixel. Those values do not follow
the tokens when a colour or spacing changes. Moving them into classes is possible without a visible change, but
it is optional work.

---

## 8. Dependency map

All views load: `header(-home).php` → `wp_head` (font preload, `theme.json` @font-face, hero preload) →
`theme-global.css` → page CSS → … → `footer(-home).php` → `site.js` (+ `KRM_CONFIG`) → page JS.
Each page loads the following on top of that:

| View | Templates | CSS (after `theme-global.css`) | JS (after `site.js`) | Data |
|---|---|---|---|---|
| Home `/` | `front-page.php`, `header-home.php`, `footer-home.php`, `template-parts/home/*` (10) | `front.css` | `front.js` | `inc/content/home.php`, `krm_ministries('home')`, posts (field stories) |
| About | `page-templates/about.php`, `page-hero.php`, `faq.php` | `site.css`, `pages/about.css` | none | `inc/content/about.php` |
| Contact | `page-templates/contact.php`, `page-hero.php`, `faq.php` | `site.css`, `pages/contact.css` | `contact.js` | `inc/content/contact.php`, `inc/forms.php` |
| Donate | `page-templates/donate.php`, `donate-thanks.php`, `give-band.php`, `faq.php` | `site.css`, `pages/donate.css` | `donate.js` (+ `KRM_DONATE`) | `inc/content/donate.php`, `inc/donations.php` |
| Partner | `page-templates/partner.php`, `page-hero.php`, `faq.php` | `site.css`, `pages/partner.css` | `partner.js` | `inc/content/partner.php`, `inc/forms.php` |
| Blog `/blog/`, archives, search | `home.php`, `archive.php`, `search.php`, `post-list.php` | `site.css`, `pages/blog.css` | `blog.js` | `inc/content/blog.php` |
| Story (post) | `single.php`, `comments.php` | `site.css`, `pages/post.css` | `post.js` | `inc/post-meta.php` |
| Ministry `/ministries/{slug}/` | `single-ministry.php`, `give-band.php` | `site.css`, `pages/ministry.css` | none | `inc/ministry.php` meta, Site text `ministry.*` |
| Ministries `/ministries/` | `archive-ministry.php` | `site.css`, `pages/ministry.css` | none | Site text `overview.*` |
| Sermon, Event, Testimonial | `single-*.php`, `archive-*.php`, `type-single.php`, `type-archive.php` | `site.css`, `pages/post.css` or `blog.css`, `pages/extras.css` | `post.js` / `blog.js` | `inc/content-types.php` |
| Generic page, 404 | `page.php`, `404.php`, `index.php` | `site.css`, `pages/page.css`, `pages/blog.css` | none | none |

**Shared components:**

| Component | Files |
|---|---|
| Menu | `inc/nav.php` → markup in both headers, styles in `theme-global.css`, behaviour in `site.js` |
| Image viewer | `site.js` + `theme-global.css` (`data-gallery`, `data-zoom`) |
| Newsletter popup | `site.js` + `inc/forms.php` |
| Give band | `template-parts/give-band.php` |
| Logo | `krm_logo_url()` |
| Images | `krm_media()` / `krm_img_src()` + `inc/importer.php` media map |
| Icons | `krm_icon()` + `assets/icons/` |

**Back-end only:**
- `inc/settings.php`, `customizer.php`, `content-fields.php` (+ `admin-content.css/js`);
- `importer.php`, `data/*`;
- `admin.js` (ministry gallery);
- `security.php`, `redirects.php`, `seo.php`, `schema.php`, `performance.php`.

---

## 9. Decisions for you

1. **Which header and footer become the standard?**
   - (a) **Recommended:** the inner-page version everywhere. It is class-based, sticky, and has the 4-column
     footer with the Ministries list. Optionally add the home page's social icons to its top bar.
   - (b) The home version everywhere.

   Either way, the pages that change will no longer match their approved design in those areas, and the
   pixel-parity rule is relaxed for header/footer.
2. **All Ministries page layout.** Recommended: page hero + a grid of 12 cards in the style of the home
   page's cause cards (photo, title, short description, "Learn more" → ministry page), at `/ministries/`, in the
   menu order. Alternative: reuse the home page's explorer (list + panel) on its own page.
3. **Home page explorer after that:**
   - keep it as it is, with its "Learn more" going to the ministry page and its menu link going to `/ministries/`;
   - or replace it with a link to the new page.
4. **Plugins on the live site.** May I write a step-by-step deactivation list (Elementor, Elementor Pro, Header
   Footer Elementor, Astra Sites, and whichever form/donation plugins are unused)? You or the site admin would
   carry it out after a backup. I have no access to the live admin, and this is the step that reaches 90+.
5. **Live menus.** Fix them in Appearance → Menus (no code), or should the next theme version replace them
   automatically on update?
6. **Logo.** Use the 1254 px church logo from the data folder to make one 192 px header logo (sharp on phones,
   about 10 KB), replacing both the 500 px Customizer logo and the 99 px bundled one?

---

## 10. Proposed plan for the next phases

| Phase | Work | Changes pixels? |
|---|---|---|
| 2 | Logo at 192 px; lazy-load gallery photos 2–3; delete `shield24.svg`; move inline icons to `krm_icon()`; full-site CSS coverage on the local site | No (logo: tiny resampling difference only) |
| 3 | One header + one footer (template parts, one style set), per decision 1 | Yes, home or inner pages, by design |
| 4 | All Ministries page at `/ministries/` + link updates + schema, per decisions 2–3 | New page; home only if decision 3 changes it |
| 5 | Minified CSS/JS, critical CSS, optional safety-net dequeues; deactivation checklist for plugins; menu fix | No |
| 6 | Re-run visual QA (pages, interactions, audit, responsive, viewer, SEO, a11y) + PageSpeed on the live site after plugins are removed; rebuild `kingdom-revival.zip`; report | none |
