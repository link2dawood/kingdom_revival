# Kingdom Revival Ministries: Phase 3 visual QA (HTML design vs WordPress)

**Result: the WordPress theme renders pixel-identical to the original HTML pages**, at desktop, tablet and
mobile widths, in 32 of 33 page comparisons and all 31 interaction states. The one exception is one
deliberate sentence on the Contact page (see below).

Tested on WordPress 7.1.4, PHP 8.4, Chrome (headless), theme `kingdom-revival` 1.1.0, with the content
created by the theme's Setup screen. Tooling and how to re-run it: [tools/visual-qa/](tools/visual-qa/).

## Method

- Each original page in `html/` and its WordPress URL were captured as full-page screenshots at 1440×900
  (desktop), 768×1024 (tablet) and 390×844 (mobile), then compared pixel by pixel (pixelmatch, threshold 0.1).
- Remote images and Google Fonts came from one local cache, so both sides used identical files. Videos were
  hidden (their frame timing is not repeatable); the poster/background behind them was compared.
- Interactive states were driven the same way on both sides and the visible viewport compared.
- Captures used `prefers-reduced-motion`, so they compare end states. Animations and transitions are covered
  by the theme shipping the design's own CSS/JS: `assets/css/site.css` is the original file (plus
  `object-fit: contain` on the two logo images, a no-op for the square logo, so an uploaded logo is not
  stretched); `assets/js/site.js` differs only in WordPress wiring (URLs, form posting, donate-page check).

## Page comparisons (% of pixels that differ)

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

## Interaction states (all 0%)

Desktop dropdown and nested flyout (inner and home headers) · mobile menu open and submenu expanded (home,
inner, tablet) · button and card hover states · home ministry explorer switching · home hero amount picker ·
home floating donation panel · inner-page floating donate panel (desktop, mobile) · newsletter popup
(desktop, mobile) · photo lightbox · donate payment tabs · donate prefill from
`?amount=&frequency=&for=&method=` · "Other" amount · give card frequency/amount · blog category filter,
live search and empty state · partner type switch and `?type=` prefill · contact and partner validation
errors · story page scrolled (reading progress bar).

## Fixed in this phase

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

## Not covered

- Browsers other than Chrome (Firefox/Safari render through the same CSS but were not captured).
- Video playback (hidden on both sides; the same file and markup are used).
- Images imported into the Media Library: the test site kept the live-site image URLs, as the design does.
  After Setup's media import, images are served from the Media Library at full size with `srcset`, so the
  same photo is displayed; only the file URL changes.
