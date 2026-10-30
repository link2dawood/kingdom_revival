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

Environment: `HTML_URL`, `WP_URL`, `CHROME_PATH` (defaults: the ports above and `/usr/bin/google-chrome`).
