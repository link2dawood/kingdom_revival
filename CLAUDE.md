# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A custom WordPress theme for kingdomrevivalministries.com, converted from an approved static HTML design. The
hard requirement across every phase: **with default content, every WordPress page must render pixel-identical
to the original HTML** (desktop 1440, tablet 768, mobile 390). The one known, intentional difference is a single
sentence on the Contact page.

- `html/` — the original design (extracted from `html.zip`). **Reference only; never modify.** `index.html` is a
  self-unpacking React bundle, `all-ministries.html` / `blog-post.html` / `blog.html` are JS-filled shells.
- `theme/kingdom-revival/` — the theme (the actual product).
- `kingdom-revival.zip` — the installable theme package, committed. Rebuild it after theme changes
  (zip root must be the `kingdom-revival/` folder).
- `tools/visual-qa/` — screenshot-diff, interaction, content-audit and SEO checks.
- `PHASE-REPORTS.md` — all phase reports in one file, one section per phase (analysis, visual QA, page
  conversion, editable content, SEO, structured data, keywords, donations, security/a11y, final delivery).
  This is the project's history and decision log; read the relevant section before changing an area.
- `theme/kingdom-revival/readme.txt` — end-user install/editing guide and the theme file map. Keep it in
  sync when adding editable fields, settings, shortcodes or templates.

## Commands

There is no build step, linter or unit-test suite. Verification is the visual-QA tooling
(see [tools/visual-qa/README.md](tools/visual-qa/README.md)):

```sh
# 1. Serve the original design (from project root)
php -S 127.0.0.1:8898 -t html
# 2. Run a WordPress site with the theme active and Kingdom Revival → Setup run, at http://127.0.0.1:8899
#    (static front page, /post-name/ permalinks)
# 3. From tools/visual-qa/
npm install
npm run pages          # full-page pixel diffs, all pages × 3 widths → out/pages
npm run interactions   # menus, hovers, panels, popups, lightbox, form states → out/interactions
npm run audit          # text/headings/links/images/buttons per page + internal link check → out/audit.json
python3 bands.py out/pages <page> <desktop|tablet|mobile>   # where two screenshots differ
python3 seo_check.py [site URL]   # exactly one title/description/canonical/OG/Twitter set per page
node a11y_check.js                # axe-core accessibility audit, HTML vs WP, + skip link / focus checks
```

Env vars: `HTML_URL`, `WP_URL`, `CHROME_PATH` (default `/usr/bin/google-chrome` — set it on Windows).
Results are JSON lines; `diffPct` should be 0 for every page except Contact.

Local tools on this machine: PHP at `F:/xamp/php/php` (XAMPP), Node, Python 3.

## Architecture

**Bootstrap.** `functions.php` only defines `KRM_VERSION` / `KRM_DIR` and requires `inc/*.php`
(`inc/importer.php` admin-only). All functions are prefixed `krm_`; text domain `kingdom-revival`.
Bump `KRM_VERSION` on release — it versions enqueued assets and triggers a rewrite-rule flush
(`krm_rewrite_version` option in `inc/content-types.php`).

**Editable content system (Phase 5)** — the core abstraction, spread over several files:
- `inc/content/<screen>.php` each *return* a schema array: sections → fields built with `krm_f()` /
  `krm_list_f()`. The field's default **is the design's original content**, so unedited pages match the HTML.
  Screens: `home`, `about`, `contact`, `donate`, `partner`, `blog` (per page) and `site` (global Site text).
- `inc/content-fields.php` renders the "Page content" meta box, sanitizes, and stores values in post meta
  `_krm_content_<screen>` (or option `krm_site_text` for `site`). Missing keys fall back to schema defaults.
- Templates read values with `krm_c('section.field')` (raw), `krm_t()` (escaped text), `krm_h()` (limited
  inline HTML, e.g. `<em>`), `krm_u()` (URL). Text supports placeholders `{phone}`, `{email}`, `{address}`,
  `{year}`; URLs support `{home}`, `{about}`, `{donate}`, `#section`, etc. (`krm_fill()`, `krm_resolve_url()`).
- To add an editable field: add it to the schema with the design's value as default, output it in the
  template via `krm_t/h/u`, document it in `readme.txt`. Form field names, the four partnership names and the
  "Where it is needed most" gift option are deliberately fixed — forms/JS depend on them.

**Settings.** `krm_opt($key)` reads option `krm_settings` merged over `krm_defaults()`
(`inc/template-functions.php`). Shared by `inc/settings.php` (Kingdom Revival → Settings) and
`inc/customizer.php` (same option, live preview).

**Images.** Design images are referenced by their original remote URL. The Setup importer
(`inc/importer.php`, data in `inc/data/`) imports them into the Media Library and records
`krm_media_map` (remote URL → attachment ID); `krm_media()` / `krm_img_src()` / `krm_ref_url()` return the
local copy when imported, else the remote URL. Image fields may hold a URL or an attachment reference.

**Content types.** `ministry` CPT (`inc/ministry.php`, `/ministries/{slug}/`), plus `sermon`
(+ `sermon_series`), `event`, `testimonial` (`inc/content-types.php`). Stories are normal posts with meta from
`inc/post-meta.php`. Setup (idempotent, skips existing slugs) creates the 12 ministries, 7 stories, pages and
menus from `inc/data/`.

**Page routing.** About/Contact/Donate/Partner use `page-templates/*.php`; `inc/setup.php` auto-assigns the
template to a page with the matching slug. Links find these pages **by template, not slug**
(`krm_page_url()`), so pages can be renamed. The home page uses its own `header-home.php` / `footer-home.php`
and `template-parts/home/*`.

**Assets.** `assets/css/theme-global.css` (Task 5) is the single source of the design system: design tokens as
CSS custom properties (old short names like `--navy`/`--red` are aliases) plus every component shared by the home
and inner pages (menu, image viewer, popups, buttons, header/footer, forms, story cards, FAQ, animations). It is
enqueued once as `krm-global`, a dependency of `krm-front` and `krm-site`. Put shared rules there, not in two files;
a rule that must override a rule left in `site.css`/`front.css` has to stay after it (load order).
`assets/css/site.css` holds the inner pages' base and layout, `front.css` the home page's own rules.
`assets/js/site.js` is the design's original shared script — keep it as close to the original as possible
(differences are only WordPress wiring: URLs, form posting).
Per-page CSS/JS in `assets/css/pages/` and `assets/js/`, enqueued per view in `inc/enqueue.php`. The font is
self-hosted (no Google Fonts request). `theme.json` mirrors the palette/font for the editor.

**Forms** (`inc/forms.php`): contact/partner/newsletter post to WordPress (nonce + honeypot), are saved as
Submissions and emailed via `wp_mail`. Every post must carry `KRM_CONFIG.formToken`, a non-expiring HMAC page
token from `inc/security.php` (Phase 11); new forms must send it too. Submissions/Donations are Editor+ only
(`edit_posts` mapped to `edit_others_posts`).

**Donations** (`inc/donations.php`, Phase 9): PayPal's hosted checkout only (no card data or API keys in WordPress).
`assets/js/donate.js` builds the checkout link with `notify_url`; PayPal IPN posts to
`admin-post.php?action=krm_paypal_ipn`, which is verified with PayPal (`_notify-validate`) and the receiver email
before saving a `krm_donation` and emailing the receipt. `/donate/?thanks=1` renders `template-parts/donate-thanks.php`
instead of the hero and giving steps; the default Donate page is unchanged.

**SEO** (`inc/seo.php`): when Rank Math, Yoast or SureRank is active the theme prints no meta tags and only
feeds the design's title/description/image as plugin defaults; with no plugin it prints basic tags itself.
`inc/redirects.php` holds 301s from the old Elementor site's URLs (editable in Settings).

## Project memory and progress tracking

Keep state across sessions in two places:

1. **Phase reports in the repo** (`PHASE-REPORTS.md`, one `## … Phase <n> …` section per phase, listed in its
   Contents) are the durable, shareable record. When a phase or significant piece of work finishes,
   add/update its section at the end of that file in the same style: a bold **Result** line
   first, then what changed, how it was verified (with numbers from the QA tools), what was fixed, what is
   "Not covered", and any "Decisions for you". Never rewrite earlier phase sections except to correct facts.
2. **Claude's auto memory** (`~/.claude/projects/<this project>/memory/`, indexed by `MEMORY.md`) holds
   things not derivable from the repo: current phase and next steps, the user's decisions and preferences,
   environment details (local WordPress URL, credentials location — never the secrets themselves).
   Update the "current status" memory when a phase starts or ends; don't duplicate what the phase reports
   already say.

Before starting work: read `MEMORY.md`, the latest section of `PHASE-REPORTS.md`, and `git log --oneline -5`.
