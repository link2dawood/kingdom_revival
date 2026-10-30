# Kingdom Revival Ministries: Phase 5 editable content

**Result: the site's text, headings, images, buttons, links, hero content, ministries, donation and contact
details and footer are all edited from the WordPress dashboard, using only WordPress's own features (custom
fields, custom post types, menus, the Customizer). No plugin is required.** Sermons, Events and Testimonials
have their own content types.

The front end is unchanged: with the default content, every page still matches the original HTML design
pixel for pixel (same results as Phase 3 and 4; see "Verified" below).

## Where each thing is edited

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

## How the "Page content" box works

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

## What stays fixed, and why

| Fixed | Reason |
|---|---|
| Form fields (labels, the contact topics, validation messages) | Messages and sign-ups must arrive complete and match what the server checks. |
| The four partnership names | The sign-up form and saved submissions use them. Their prices, texts, check lists and buttons are editable. |
| "Where it is needed most" on the Donate page | The donation page's script recognises this option. |
| Icons, colours and layout | Part of the design; icons follow the position of each card. |

## Sermons, events and testimonials

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

## Verified

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

## Not covered

- Visual design for sermons, events and testimonials beyond existing components: their pages are functional
  and consistent with the site, but were not part of the approved design. Review them before linking them.
- Popups and controls built by JavaScript (the newsletter popup, the give card's labels, the floating donate
  button on inner pages) keep their wording in `assets/js/site.js`.
- Revisions and Preview cover the normal editor, not the Page content box: content changes apply when the
  page is updated.
