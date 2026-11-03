# Kingdom Revival Ministries: go-live checklist for theme 1.8.0

This checklist is for whoever administers https://kingdomrevivalministries.com. **Do every step on a staging
copy first.** Change the live site only after staging passes step 8 **and the ministry has approved go-live**.
The measurements behind each recommendation are in [PHASE-REPORTS.md](PHASE-REPORTS.md), section "Phase 15".

What theme 1.8.0 changes:
- one header and one footer on every page, including the home page;
- a new All Ministries page at `/ministries/`;
- a "View All Ministries" button under the home page's ministry explorer;
- a 3–12 KB logo instead of the 171 KB one;
- lighter hero images on phones;
- unused plugin styles removed for visitors.

## 1. Before you start

- [ ] Take a full backup of the live site (files and database) with the host's backup tool. Download it and keep it.
- [ ] Create a staging copy of the live site: use the host's staging tool, or a plugin such as WP Staging. The
      staging copy must have the same plugins, settings and content as the live site.
- [ ] On staging: Settings → Reading → "Discourage search engines" is on, so the copy is not indexed.

## 2. Install theme 1.8.0 on staging

- [ ] Appearance → Themes → Add New → Upload Theme → `kingdom-revival.zip` → "Replace current with uploaded".
- [ ] Open the home page, `/about/`, `/ministries/`, one ministry and `/contact/`. Each page should have the
      same top bar, header and footer.
- [ ] On first load the theme updates saved links that still point at the home page section `/#all-ministries`
      so that they go to `/ministries/`:
  - About's "Explore our ministries" button;
  - the 404 page button;
  - the `/services/` redirect.

## 3. Menus (Appearance → Menus)

Today's live menus are the old site's menus. They link to old addresses that only work through redirects, list
"Deliverance and Healing Ministry" twice, and have no All Ministries, Partner or Donate link. **Build the menus
below on staging. Do not edit the live menus until go-live is approved.**

**Main navigation** (location "Main navigation"). Use "Custom Links" with URL `#` for the dropdown parents:

| Item | How to add it | Notes |
|---|---|---|
| Home | Custom link: your home address | |
| Ministries | Custom link `#` | Dropdown parent |
| — All ministries | **Ministries box → "View All" tab → "All Ministries"** (a post type archive item) | CSS class `menu-all` (enable "CSS Classes" under Screen Options) |
| — Children & Families | Custom link `#` | Sub-group |
| —— Children Ministry, Orphan & Children Care, Brick Kiln Children, Widows Ministry | Ministries box | In this order |
| — Freedom & Care | Custom link `#` | Sub-group |
| —— Freedom from Slavery, Free Medical Camps, Clean Water Project | Ministries box | |
| — Gospel & Church | Custom link `#` | Sub-group |
| —— Outreach Ministry, Urdu Bibles, Baptisms, Deliverance & Healing, Leadership & Discipleship | Ministries box | Deliverance & Healing **once** |
| About | Pages box | |
| News & Blogs | Pages box (the posts page) | |
| Contact | Pages box | Not `/#contact` |

Donate is the red button in the top bar of every page, and Partner is in the footer, so the main menu keeps the
approved design's five items. The phone menu shows the same items.

**Footer — Quick Links** (location "Footer: Quick Links"): Home, About, News & Blogs, Contact, Become a Partner
(Partner page), Donate (Donate page).

**Footer — Ministries** (location "Footer: Ministries"): Children Ministry, Orphan & Children Care, Freedom from
Slavery, Urdu Bibles, Leadership & Discipleship, then "All ministries →", added from the Ministries box's
"View All" tab.

- [ ] Remove the old menus once the new ones are assigned. The location "Footer: Quick Links (home page)" no longer
      exists, because the home page now uses the same footer as every other page.
- [ ] Check every menu link: there should be no `.html` addresses, no `/#contact`, and no old addresses such as
      `/baptisms/`, `/donate-us/` or `/latest-news-blogs/`.

Shortcut on a fresh install: Kingdom Revival → Setup creates all three menus exactly as above, but only for
locations that have no menu yet.

## 4. Logo

- [ ] Appearance → Customize → Site Identity → Logo: **remove** the uploaded logo (`cropped-R-BG-Logo.png`, 500×500,
      171 KB). The theme's bundled logo is the same church logo, made from the 1254 px original. The browser
      downloads it at 64, 128 or 192 px (3–12 KB), whichever the screen needs.
  - To keep an uploaded logo instead, install "Regenerate Thumbnails" and regenerate that one image, so that
    WordPress has small sizes to offer.
- [ ] The site icon (Site Identity → Site Icon) is separate and stays as it is.

## 5. Plugins: what to deactivate, in this order (staging first)

**Read-only findings:** all 37 public addresses in the live sitemap were downloaded and scanned on 2026-11-03.
- No page shows Elementor content, a SureForms, WPForms or FormLayer form, a Robo Gallery gallery or a Zeffy form.
- Every form on the site (Contact, Partner, newsletter, donation cards) belongs to the theme.
- Every photo comes from the Media Library (`uploads/2026/07`, `/08`, `/11`), not from Elementor's folders.

**Deactivate one plugin at a time, then re-check the site after each one** (check list at the end of this step).
Deactivating can be undone. **Do not delete** a plugin until the site has run without it for two weeks. Before
deleting Elementor, make sure "Remove data when uninstalling" is off in its settings.

| # | Plugin | What it loads on every public page today | Used by the site? | Action |
|---|---|---|---|---|
| 1 | Astra Sites / Starter Templates | A template-preview script | No: it only imports demo templates | Deactivate, later delete |
| 2 | Header Footer Elementor | 82 KB CSS + Font Awesome | No: the header and footer come from the theme. Its "Header" template (`/elementor-hf/header/`) is not shown, but it is listed in the sitemap | Deactivate |
| 3 | Elementor Pro | 510 KB CSS | No | Deactivate (before Elementor) |
| 4 | Angie (Elementor add-on) | Script + body class | No | Deactivate |
| 5 | Elementor | 335 KB CSS, 229 KB JS, jQuery, Google Fonts Roboto + Roboto Slab (18 weights each), generated `post-30/31/33/38.css` | No. Pages 30 (Home), 31, 33 and 38 (Elementor's kit) still hold old Elementor data, but the theme's templates do not show it | Deactivate |
| 6 | SureForms | 7 KB CSS | No form is shown on any page. Form 1270 exists (`/form/1270/` already redirects home) | **First** export SureForms → Entries if there are any, then deactivate |
| 7 | FormLayer | 11 KB CSS + 7 KB JS, and **Dashicons (58 KB)** through its block assets | No form is shown | Check its entries/settings, then deactivate |
| 8 | WPForms Lite | Its CSS on `/contact/` only (the page's stored content probably still holds an old `[wpforms]` shortcode; the theme shows its own contact form) | No | Check WPForms → Entries, then deactivate |
| 9 | Robo Gallery (5.1.5 on live) | An empty block stylesheet that depends on `wp-editor`, which pulls **all the WordPress editor styles (≈435 KB)** into every page. Version 5.2 removed it for that reason | No: galleries are the theme's | Deactivate (or at least update). Theme 1.8.0 already drops that stylesheet for visitors |
| 10 | Zeffy donation form | Inline style | No Zeffy form is shown; giving uses the theme's PayPal checkout | Deactivate unless a Zeffy campaign is planned |
| 11 | Ally (accessibility widget, by Elementor) | Body class `ally-default` | Only if its toolbar is wanted | Your choice. The theme itself passes axe-core checks |
| — | CookieAdmin | Consent banner: 21 KB JS, 9 KB CSS, and 52 KB of inline SVG in every page's HTML (its "Powered by" logo) | Yes: cookie consent | **Keep.** In its settings, turn off the "Powered by" branding if the plan allows, or test a lighter consent plugin |
| — | SureRank | SEO titles, descriptions, sitemap, schema | Yes | **Keep exactly one SEO plugin.** Update SureRank to the current version (1.10.x; live has 1.9.1). The theme also supports Rank Math and Yoast if you switch, but never run two at once |

**After each deactivation, check on staging:**
- [ ] home, About, `/ministries/`, two ministry pages, News & Blogs, a story, Contact, Donate, Partner: layout
      intact on desktop and phone, no missing images;
- [ ] the menu opens on desktop and phone, and the photo viewer opens on a ministry gallery;
- [ ] the Contact and Partner forms send (a submission appears under Submissions and the email arrives);
- [ ] Donate → PayPal button opens PayPal checkout (cancel at PayPal);
- [ ] the dashboard still works: Pages, Posts, Ministries, Kingdom Revival → Settings / Site text, Appearance → Menus.

**What the theme now does by itself:** for visitors who are not logged in, it leaves out Dashicons and the
WordPress editor style bundles when a plugin queues them directly. Logged-in users, the admin bar and the dashboard
are not affected. A style that a plugin's own stylesheet depends on is still loaded, so nothing a plugin needs
goes missing. The setting is Kingdom Revival → Settings → Images → "Unused plugin styles".

## 6. SEO fixes (staging, then live)

- [ ] **Duplicate tags.** Every live page prints two canonical links, two `og:title` tags and (on most pages) two
      meta descriptions, plus an `Organization` schema block whose `name` is empty.
  - The second set is SureRank's. The first set, which starts with `<meta name="google"
    content="nositelinkssearchbox">`, is **not printed by the theme**.
  - It is also not in the current free versions of Elementor, Header Footer Elementor, Astra Sites, SureForms,
    FormLayer, WPForms Lite, Robo Gallery, CookieAdmin, Angie, Ally or SureRank (all downloaded and searched).
  - That leaves Elementor Pro, a hosting must-use plugin (`wp-content/mu-plugins`), or a code snippet (for
    example in WPCode or Code Snippets).
  - **To find it on staging:**
    1. Install the free "Query Monitor" plugin.
    2. Open the home page while logged in, then Query Monitor → Hooks → `wp_head`. The callback that prints these
       tags is listed with its plugin.
    3. Alternatively, view the page source after each deactivation in step 5. The duplicates should disappear
       when the responsible plugin is switched off.
- [ ] After step 5, run `python3 tools/visual-qa/seo_check.py <staging URL>` and
      `python3 tools/visual-qa/schema_check.py <staging URL>`. Every page must have exactly one title,
      description, canonical and set of Open Graph and Twitter tags.
- [ ] **Sitemap.** The live sitemap lists pages that should not be indexed:
  - `/elementor-hf/header/`;
  - three `?elementor_library=…` items;
  - `/form/1270/`.

  These disappear when their plugins are deactivated. If a plugin stays, exclude its post type in SureRank →
  Sitemap. `/ministries/` (All Ministries) is now a real page and belongs in the sitemap: in SureRank, make sure
  the Ministries archive is not excluded.
- [ ] Search Console: after go-live, resubmit the sitemap and inspect `/ministries/`.

## 7. Caching (live server: LiteSpeed)

- [ ] Install "LiteSpeed Cache" and turn on page caching. Leave "CSS/JS Combine" and "Unique CSS" **off** at first:
      the theme's styles depend on their order, and these options can break the layout. Test any CSS/JS option on
      staging with the visual-QA tools.
- [ ] Do not install a second optimisation plugin alongside it (Autoptimize, WP Rocket…).

## 8. Final checks on staging

- [ ] Lighthouse/PageSpeed (mobile) on Home, About, `/ministries/`, one ministry, Donate and News & Blogs, three
      runs each (median). Target 90+. Record the numbers.
- [ ] `tools/visual-qa`: `compare.js`, `interactions.js`, `site_check.js`, `forms_check.js`, `seo_check.py`,
      `schema_check.py` (see its README). Expected differences from the approved design are listed in
      PHASE-REPORTS.md, Phase 15.
- [ ] Browse the site on a real phone.

## 9. Go live (only after approval)

1. Take a fresh backup of the live site.
2. Install theme 1.8.0 on the live site (step 2).
3. Build the menus (step 3) and remove the Customizer logo (step 4).
4. Deactivate the plugins in the order and with the checks of step 5.
5. Turn on page caching (step 7). Clear all caches.
6. Repeat the step 6 SEO checks and the step 8 Lighthouse runs on the live address.
7. Search Console: resubmit the sitemap.

**Rollback:**
- re-activate the plugin you last deactivated;
- or restore theme 1.7.4 (Appearance → Themes → upload the previous zip);
- or restore the step 9.1 backup.
