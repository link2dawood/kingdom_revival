# Visual QA: HTML design vs WordPress theme

Takes full-page screenshots of each original HTML page (`html/`) and its WordPress equivalent at desktop
(1440), tablet (768) and mobile (390) widths, and pixel-diffs them. `interactions.js` does the same for
interactive states (menus, hovers, panels, popups, lightbox, form states). `audit.js` compares every page in
the Phase 1 inventory (8 pages, 12 ministries, 7 stories) for text, headings, links, images, buttons and
header/footer counts, then requests every internal link.

Remote images and Google Fonts are served from a local disk cache (`cache/`) so both sides get identical
bytes; videos are blocked and hidden, and the newsletter popup is pre-dismissed (except in its own scenarios).

## Run

1. Serve the design: `php -S 127.0.0.1:8898 -t html` (from the project root).
2. Serve a WordPress site with the theme active and Setup run (ministries, stories, pages, menus, static
   front page, /post-name/ permalinks) at `http://127.0.0.1:8899`.
3. `npm install`, then `npm run pages`, `npm run interactions` and `npm run audit`.

Results print as JSON lines (`diffPct` = % of pixels that differ); screenshots and `-diff.png` images go to
`out/`. `python3 bands.py out/pages <page> <desktop|tablet|mobile>` lists where two screenshots differ.

`python3 seo_check.py [site URL]` checks every page for exactly one title, description, canonical, Open Graph
and Twitter card tag set (run it with no SEO plugin, then with each plugin active).

`python3 schema_check.py [site URL] [--validate]` checks structured data (JSON-LD) on every page: it parses, each
thing is described once across all blocks, every `@id` reference resolves, Google's required properties for
Organization, BreadcrumbList, Article, Event, VideoObject and FAQPage are there, and the values match the page (the
BreadcrumbList is the visible breadcrumb, a headline is the h1, dates, venue, author and embedded video appear on the
page). `--validate` also sends one page of each kind to the Schema.org Validator (only the JSON-LD is sent). Run it
with no SEO plugin, then with each plugin active.

`python3 index_check.py [site URL]` checks indexing: robots.txt, robots meta and `X-Robots-Tag` on every page,
canonical URLs, every URL in the sitemaps robots.txt announces (each must answer 200 and be indexable), and which
pages crawlers reach by plain links from the home page. Keep the WordPress site's WP-Cron off
(`DISABLE_WP_CRON`) when serving it with `php -S`, or its loopback request can stall the single-threaded server.

`node a11y_check.js [outfile] [page ...]` runs axe-core (WCAG 2.1 A/AA and best practices) on each original HTML
page and its WordPress page, so design-inherited issues can be told from ones the theme adds, and checks that the
first Tab stop is the skip link, its target exists and the first Tab stops show a focus outline. One JSON line per
page (rule → number of elements); details in the outfile (default `out/a11y.json`). `WIDTH=390` audits at phone width.

`MASK_PHOTOS=1 node compare.js <outdir>` blanks photo pixels (images and background images) on both sides, keeping
their boxes. Media Library copies are resized/WebP, so photos never match the design's original files byte for byte;
with them masked, any remaining difference is layout or text.

`node site_check.js [outfile]` loads every WordPress page (design pages, 12 ministries, 7 stories, archives, search,
404, the donation thank-you view) at all three widths, plus the matching HTML page, and reports JavaScript/console
errors, failed or 4xx/5xx requests, images that did not load, horizontal overflow, noindex (meta or `X-Robots-Tag`;
only search, 404 and the thank-you view should have it) and PHP warnings printed into the page.

`ADMIN_PASS=... node admin_check.js [outfile]` logs in as an administrator, opens every dashboard screen the theme adds
or uses (status, JS errors, PHP messages), then edits real content and checks the public page: a design page's Page
content box, Site text, Settings, a story and a ministry (title and meta box) in the block editor. Every edit is undone.

`PAYPAL_EMAIL=... node forms_check.js [outfile]` sends the contact, partner and newsletter forms in a browser at 1440
and 390, reads the PayPal checkout links the Donate page builds, and posts PayPal payment notifications (a gift, the
same one again, one for another account). Check Submissions/Donations and the emails on the server afterwards. On a
local site PayPal can't be reached, so its verification answer has to be stubbed there (never on a real site).

`node perf_check.js [outfile]` measures page loads (median of `RUNS`, default 3) on a throttled phone profile (390 px,
4× CPU slowdown, "Slow 4G": 150 ms RTT, 1.6 Mbps) and an unthrottled desktop: TTFB, FCP, LCP, CLS, Total Blocking
Time, requests, KB transferred and requests to other hosts, up to the load event. No request interception.
`COMPARE=1` also measures the original HTML page. `node inp_check.js` measures interaction latency (INP) on the
phone profile.

`node prod_proxy.js [port] [origin]` (defaults 8900, http://127.0.0.1:8899) puts a production-like front on the local site,
as the live host's LiteSpeed cache does for visitors: a page cache, Brotli for text files and year-long cache headers for
static files. Measure through it to keep PHP's development server out of the numbers; restart it after a theme change
(it holds the old pages).

`LIGHTHOUSE=<path to lighthouse CLI> node lh_run.js <label> [base URL]` runs Lighthouse 12.2.1 (the version earlier phases
used; install it with `npm install lighthouse@12.2.1` anywhere) on home, All Ministries, a ministry, Blog, a story,
Contact and Donate: `RUNS` runs (default 3) per page on mobile and desktop (`DEVICES`), medians of score, FCP, LCP,
TBT, CLS, Speed Index, bytes, JS execution time and requests. Raw reports and `summary.json` go to `out/lh/<label>/`.
`PAGES=/,/about/` limits the pages.

Environment: `HTML_URL`, `WP_URL`, `CHROME_PATH` (defaults: the ports above and `/usr/bin/google-chrome`).

`node placeholder_check.js` crawls the WordPress site logged out (home page, sitemap and every internal link) and lists
any "[…]" placeholder visitors could see (text, alt/title/aria-label, `<title>`, meta tags), plus links to the Testimonies
page or hidden ministries while those are hidden. Exit code 1 when a placeholder is found. Run it before a release.

`node final_pass.js [out.json]` crawls every public page (consistency, overflow at 4 widths, axe at 1440 and 360,
tap targets, SEO tags, images, scripts). Since Phase 20 it also checks WCAG AA colour contrast in **both themes**:
light in the main axe run, dark in a second context (`data-theme="dark"`), at 1440 and 360, after showing any
scroll-reveal blocks; failures are listed under `contrastLight` / `contrastDark` in the summary.

`WP_USER=… WP_PASS=… node phase20_check.js [out.json]` (Phase 20): screenshots of Home, What We Do, a ministry, Our Impact,
About and Donate in light + dark at 390 and 1440 (`out/phase20/shots/`); the saved theme on the first frame of the
next page (no flash) and the toggle's state; the progress bar (0 at the top, 1 at the bottom, hidden on short pages,
under the admin bar when logged in — needs a user, else skipped); scroll reveal with JavaScript off, with reduced motion,
on the first screen and after scrolling to the bottom; the header transparent → frosted, with the nav text contrast
measured on the pixels behind it; photo bands (lazy, ≤ 250 KB); and per page/width/theme: horizontal scroll, console
errors and CLS. Every failure is printed as `FAIL …` and listed in `failures`.
