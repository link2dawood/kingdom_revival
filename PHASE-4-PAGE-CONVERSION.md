# Kingdom Revival Ministries: Phase 4 page conversion audit

**Result: every page in the Phase 1 inventory exists in WordPress, renders through a theme template, and
matches the original page's content, headings, sections, images, buttons and links.** The only content
change is one sentence on the Contact page, explained below.

Checked on WordPress 7.1.4 with the content created by the theme's Setup screen. Each page was compared
against the original HTML page after the HTML page's own JavaScript had rendered it. Tool:
[tools/visual-qa/audit.js](tools/visual-qa/audit.js). Pixel comparisons are in
[PHASE-3-VISUAL-QA.md](PHASE-3-VISUAL-QA.md).

## Page inventory → WordPress

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

## What was compared, per page

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

### Link conversion

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

## Differences that remain

- **Contact form intro (visible):** "Fill in the form and your message will go straight to our team."
  The design says "…it will open in your email app, ready to send to our team." The WordPress form sends
  the message itself (saved and emailed), so the original sentence would be untrue.
- **Not visible:** a "Skip to content" link on the home page (the design's inner pages already have it),
  and a hidden "Leave this field empty" spam-trap field on the contact, partner and newsletter forms.
- **Hidden leftovers not carried over:** the HTML ministry and story pages contain a hidden "not found"
  block, and the HTML ministries page contains a hidden, empty ministry layout. They only exist because those
  HTML pages are filled by JavaScript; WordPress renders the right state on the server.

## Fixed in this phase

- **Home page social icons:** with no social URL saved, they again link to `#contact` (the footer
  contact details) as in the design, instead of being non-links. Saving a URL in Settings or the Customizer
  links them to the real page.
- **`/ministries/` title:** "Ministry Details | Kingdom Revival Ministries", as in the design (was
  "Ministries | …").
- **Home header brand text:** removed whitespace between "Kingdom Revival" and "MINISTRIES" so the markup
  matches the design (no visual change).
