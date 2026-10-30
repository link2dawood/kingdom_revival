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
6. Install an SEO plugin (Rank Math or Yoast SEO). The theme detects it and hands over titles, meta descriptions, Open Graph, schema and sitemaps to it, so nothing is printed twice. Without a plugin, the theme prints sensible defaults itself.
7. Make sure the site can send email (an SMTP plugin is recommended on most hosts) so form notifications arrive. Every submission is also saved under Kingdom Revival → Submissions.

== Editing content ==

* Ministries (dashboard → Ministries): title, Excerpt (the short description), focus, "how this ministry serves" points, photo gallery, home page explorer image and position. Page Attributes → Order sets the order in menus and the donation/partner forms.
* Stories (Posts): normal posts. Pick the ministry and reading time in the "Story details" box. Blocks that match the design: Paragraph (add the class "lead" to the opening paragraph), Heading, Quote, Image with caption, List with the class "checks", Gallery, and the shortcode [krm_give] for the "Help write the next chapter" box.
* Menus (Appearance → Menus): Main navigation, Footer Quick Links, Footer Ministries, Home Page Footer Quick Links. A custom link "#" with sub-items becomes a dropdown. Give the "All ministries" item the CSS class "menu-all".
* Impact numbers, contact details, social links, bank and PayPal details: Kingdom Revival → Settings (or Appearance → Customize for the first three).
* Design pages: About, Contact, Donate and Become a Partner use the templates in page-templates/ (Page Attributes → Template). Setup assigns them; a page with one of those slugs and no template chosen gets its design template automatically. Links across the site find these pages by template, so a page can be renamed safely.

== Theme files ==

front-page.php ............. home page (header-home.php / footer-home.php); sections in template-parts/home/
page-templates/ ............ about.php, contact.php, donate.php, partner.php (design pages)
single-ministry.php ........ /ministries/{slug}/      archive-ministry.php ... /ministries/
home.php ................... News & Blogs            single.php ............. a story
page.php, 404.php, search.php, archive.php, index.php, comments.php
template-parts/ ............ home/*, page-hero, give-band, post-list
theme.json ................. colour palette, font (Plus Jakarta Sans, self-hosted), content widths for the editor
inc/setup.php .............. theme supports, menus, page template routing, block CSS rules
inc/enqueue.php ............ styles and scripts per view
inc/customizer.php ......... Customizer controls (same option as the Settings screen)
inc/template-functions.php . settings, URLs, media, logo, icons
inc/seo.php ................ titles, meta, Open Graph, JSON-LD (steps aside for SEO plugins)
inc/ ....................... also settings, ministries, menus, forms, story meta, setup importer
assets/css/site.css ........ shared design styles     assets/css/pages/*.css ... per-page design styles
assets/css/front.css ....... home page design styles  assets/js/*.js ........... design behaviour
assets/images/, assets/icons/, assets/fonts/

== Privacy ==

The font is served from the theme (no Google Fonts request). Form submissions are stored in WordPress and emailed to the ministry; nothing is sent to third parties unless a newsletter service URL is added in Settings.
