=== Kingdom Revival Ministries ===
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
License: GPLv2 or later

Custom theme for kingdomrevivalministries.com, converted from the approved HTML design.

== Installing ==

1. Back up the site (database + wp-content). Nothing in this theme deletes content, but a backup is the safe first step on a live site.
2. Appearance → Themes → Add New → Upload Theme → choose kingdom-revival.zip → Install → Activate.
3. Open Kingdom Revival → Setup and click "Run setup". It:
   - imports the design's images and video into the Media Library (files already in the library are reused, not duplicated) and adds alt text where it is missing,
   - creates the 12 ministries, the 7 stories, and the About, Contact, Donate, Become a Partner and News & Blogs pages,
   - creates the menus and assigns them to this theme's empty menu locations,
   - optionally sets the Reading settings (static front page + News & Blogs as posts page) and /post-name/ permalinks — only if you tick those boxes.
   Anything that already exists with the same slug or name is skipped and left unchanged. You can run Setup again at any time.
4. Appearance → Customize → Site Identity: upload a logo (optional; the bundled logo is used otherwise). Contact details, social links and impact numbers can be edited with live preview under Customize → Kingdom Revival.
5. Kingdom Revival → Settings: check the contact details, add social media links, the PayPal account email (enables secure one-time and monthly PayPal checkout), bank details and the email address that receives form notifications.
6. Install an SEO plugin: Rank Math or Yoast SEO (both free; SureRank, which the previous site used, also works). The plugin manages every page's SEO title, meta description, canonical URL, Open Graph and Twitter tags, schema and sitemap; the theme then prints none of these itself, so nothing is duplicated. Where a page's field in the plugin is empty, the theme offers the design's title, description and hero image as the default; anything typed in the plugin wins. Rank Math only takes over once its setup wizard is completed or skipped. Tip: set the plugin's title separator to "|" so story and ministry titles match the design ("Baptisms | Kingdom Revival Ministries").
   Without an SEO plugin the theme prints basic tags itself.
   Permalinks must be "Post name" (Settings → Permalinks); Setup can set this.
   Redirects from the previous site's addresses (e.g. /donate-us/ → /donate/, /baptisms/ → /ministries/baptisms/) are on by default: Kingdom Revival → Settings → Redirects.
7. Make sure the site can send email (an SMTP plugin is recommended on most hosts) so form notifications arrive. Every submission is also saved under Kingdom Revival → Submissions.

== Editing content ==

Everything below is edited in the dashboard; no plugin is needed.

* Page text, headings, images and buttons: Pages → edit Home, About, Contact, Donate, Become a Partner or News & Blogs. The "Page content" box has one section per part of the page (Hero, Who we are, FAQ…). Fields start with the design's content; lists (cards, photos, FAQ questions…) can be added to, removed and reordered. "Restore this section to the original design" undoes edits to a section.
  - Headings marked "formatting allowed" accept <em>…</em> for the highlighted words (on the home page: <span style="color: #F5A04A">…</span>).
  - Text can use {phone}, {email}, {address} and {year}; links can use {home}, {about}, {contact}, {donate}, {partner}, {blog}, {email}, {phone}, {whatsapp}, {paypal}, or #section.
  - Form fields (names, emails, topics…), the four partnership names and the "Where it is needed most" gift option are fixed, because the forms and donation page depend on them.
* Site text (Kingdom Revival → Site text): header name, top bar button, footer, ministry page and story page wording, the /ministries/ overview, the 404 page and the sermons/events/testimonials page headings.
* Ministries (dashboard → Ministries): title, Excerpt (the short description), focus, "how this ministry serves" points, photo gallery, home page explorer image and position. Page Attributes → Order sets the order in menus and the donation/partner forms.
* Stories (Posts): normal posts. Pick the ministry and reading time in the "Story details" box. Blocks that match the design: Paragraph (add the class "lead" to the opening paragraph), Heading, Quote, Image with caption, List with the class "checks", Gallery, and the shortcode [krm_give] for the "Help write the next chapter" box.
* Sermons (/sermons/): title, notes, excerpt, featured image, Series, and in "Sermon details" the speaker, Bible passage, video link (YouTube/Vimeo/Facebook) and audio file. The publish date is the date preached.
* Events (/events/): title, description, excerpt, featured image, and in "Event details" start and end, venue, address and a registration link. Upcoming events are listed first, past events below them.
* Testimonials (/testimonials/): the title is the person's name, the editor holds their words, the featured image is their photo. Show them inside any story or page with [krm_testimonials count="3"] or [krm_testimonials ministry="widows-ministry"].
  The sermons, events and testimonials pages are not linked anywhere until you add them to a menu (Appearance → Menus → Custom Links or the "Sermons/Events/Testimonials" boxes).
* Menus (Appearance → Menus): Main navigation, Footer Quick Links, Footer Ministries, Home Page Footer Quick Links. A custom link "#" with sub-items becomes a dropdown. Give the "All ministries" item the CSS class "menu-all".
* Impact numbers, contact details, social links, bank and PayPal details: Kingdom Revival → Settings (or Appearance → Customize for the first three). Logo: Appearance → Customize → Site Identity.
* Design pages: About, Contact, Donate and Become a Partner use the templates in page-templates/ (Page Attributes → Template). Setup assigns them; a page with one of those slugs and no template chosen gets its design template automatically. Links across the site find these pages by template, so a page can be renamed safely.

== Theme files ==

front-page.php ............. home page (header-home.php / footer-home.php); sections in template-parts/home/
page-templates/ ............ about.php, contact.php, donate.php, partner.php (design pages)
single-ministry.php ........ /ministries/{slug}/      archive-ministry.php ... /ministries/
archive-/single-sermon.php . /sermons/  taxonomy-sermon_series.php  archive-/single-event.php . /events/  archive-testimonial.php
home.php ................... News & Blogs            single.php ............. a story
page.php, 404.php, search.php, archive.php, index.php, comments.php
template-parts/ ............ home/*, page-hero, give-band, faq, post-list, type-archive, type-single
theme.json ................. colour palette, font (Plus Jakarta Sans, self-hosted), content widths for the editor
inc/setup.php .............. theme supports, menus, page template routing, block CSS rules
inc/enqueue.php ............ styles and scripts per view
inc/customizer.php ......... Customizer controls (same option as the Settings screen)
inc/content-fields.php ..... editable page content (Page content boxes, Site text) — schemas in inc/content/
inc/content-types.php ...... sermons, events, testimonials (fields, ordering, [krm_testimonials])
inc/template-functions.php . settings, URLs, media, logo, icons
inc/seo.php ................ design titles/descriptions/share images for SEO plugins; basic tags when none is active
inc/redirects.php .......... 301 redirects from the previous site's addresses (editable in Settings)
inc/ ....................... also settings, ministries, menus, forms, story meta, setup importer
assets/css/site.css ........ shared design styles     assets/css/pages/*.css ... per-page design styles
assets/css/front.css ....... home page design styles  assets/js/*.js ........... design behaviour
assets/images/, assets/icons/, assets/fonts/

== Privacy ==

The font is served from the theme (no Google Fonts request). Form submissions are stored in WordPress and emailed to the ministry; nothing is sent to third parties unless a newsletter service URL is added in Settings.
