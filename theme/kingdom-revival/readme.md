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
   - imports the design's images and video into the Media Library (files already in the library are reused, not duplicated) and adds alt text where it is missing; photos that were not on the old site (the Outreach gallery, the upright Urdu Bible photo) and the old site's Elementor thumbnail crops (home page cards and photos, the home hero) are copied from assets/images/content/, so nothing is fetched from the old site's elementor/thumbs folder,
   - creates the 12 ministries, the 7 stories, and the About, Contact, Donate, Become a Partner and News & Blogs pages,
   - moves WordPress's sample post "Hello world!" to the trash, if it was never edited (it would otherwise show in News & Blogs),
   - creates the menus and assigns them to this theme's empty menu locations,
   - optionally sets the Reading settings (static front page + News & Blogs as posts page) and /post-name/ permalinks — only if you tick those boxes.
   Anything that already exists with the same slug or name is skipped and left unchanged, with one exception: on an existing ministry, Setup replaces only photo settings nobody has edited (a gallery that still shows just the placeholder photo, the old sideways Urdu Bible photo, and alt texts still set to the old one-phrase-per-ministry wording). You can run Setup again at any time.
4. Appearance → Customize → Site Identity: upload a logo (optional; the bundled logo is used otherwise, and it is already the church logo). Search engines get the bundled 512×512 logo (assets/images/krm-logo-large.png) unless you upload one. The header and footer show the bundled logo at 64, 128 or 192 pixels (krm-logo-64.png, krm-logo-128.png, krm-logo.png), whichever the screen needs; an uploaded logo is shown through its WordPress-made sizes, never the full upload (after uploading, a plugin such as Regenerate Thumbnails rebuilds the sizes of a logo uploaded before theme 1.8.0). Contact details, social links and impact numbers can be edited with live preview under Customize → Kingdom Revival.
5. Kingdom Revival → Settings: check the contact details, add social media links, the PayPal account email (enables secure one-time and monthly PayPal checkout, saved Donations and receipts — see "Online giving"), bank details and the email address that receives form notifications.
6. Install an SEO plugin: Rank Math or Yoast SEO (both free; SureRank, which the previous site used, also works). The plugin manages every page's SEO title, meta description, canonical URL, Open Graph and Twitter tags, schema and sitemap; the theme then prints none of these tags itself, so nothing is duplicated (for schema, see below). Where a page's field in the plugin is empty, the theme offers the design's title, description and hero image as the default; anything typed in the plugin wins. Rank Math only takes over once its setup wizard is completed or skipped. Tip: set the plugin's title separator to "|" so story and ministry titles match the design ("Baptisms | Kingdom Revival Ministries").
   Without an SEO plugin the theme prints basic tags itself.
   Structured data (schema): the SEO plugin's is kept and nothing is printed twice. The theme adds what the plugin does not build from the theme's fields: Event (an event with a start date and a venue or address) and VideoObject (a sermon whose video is embedded). With Yoast SEO and Rank Math it also adds the ministry's details (address, phone, email, logo, founder) where the plugin left them out, makes the breadcrumb match the one on the page, credits stories to the author box ("Kingdom Revival Ministries Team") instead of the WordPress user, and drops Rank Math's default "Article" on pages that aren't stories. Without an SEO plugin the theme prints all of it (Organization, WebSite, WebPage, BreadcrumbList, BlogPosting, Event, VideoObject).
   Kingdom Revival → Settings → Structured data: the organisation type (Organization, or NGO only if the ministry is a registered non-governmental organisation) and the city, province and country of the address. Set Settings → General → Timezone to Karachi so event times are given in Pakistan time.
   With Rank Math: Titles & Meta → Local SEO → choose "Organization" (it defaults to a person) and add the logo.
   Permalinks must be "Post name" (Settings → Permalinks); Setup can set this.
   Redirects from the previous site's addresses (e.g. /donate-us/ → /donate/, /baptisms/ → /ministries/baptisms/) are on by default: Kingdom Revival → Settings → Redirects.
7. Make sure the site can send email (an SMTP plugin is recommended on most hosts) so form notifications arrive. Every submission is also saved under Kingdom Revival → Submissions.
8. Online giving (PayPal), see "Online giving" below: enter the PayPal Business account email in Kingdom Revival → Settings → Online giving, test in Sandbox mode, then switch to Live.

== Editing content ==

Everything below is edited in the dashboard; no plugin is needed.

* Page text, headings, images and buttons: Pages → edit Home, About, Contact, Donate, Become a Partner or News & Blogs. The "Page content" box has one section per part of the page (Hero, Who we are, FAQ…). Fields start with the design's content; lists (cards, photos, FAQ questions…) can be added to, removed and reordered. "Restore this section to the original design" undoes edits to a section.
  - Headings marked "formatting allowed" accept <em>…</em> for the highlighted words (on the home page: <span style="color: #F5A04A">…</span>).
  - Text can use {phone}, {email}, {address} and {year}; links can use {home}, {about}, {contact}, {donate}, {partner}, {blog}, {ministries} (All Ministries page), {email}, {phone}, {whatsapp}, {paypal}, or #section.
  - Form fields (names, emails, topics…), the four partnership names and the "Where it is needed most" gift option are fixed, because the forms and donation page depend on them.
* Home page sections (Pages → Home → Page content), in page order: Hero and donation card, Your gift at work (amount cards), About, Ministry explorer, Giving cards, Flagship: Freedom from Slavery (five steps and the before/after photo slider; the step numbers 01–05 follow the list order), Flagship cards (Urdu Bibles, Church Planting), Where we work (places list; optional map image with pins placed by % from the left and top, the first pin is the red headquarters pin), Ministry in action (video and photos), Stories from the field (quotes; photo optional), Get involved, Prayer request, Founder (quote, WhatsApp button and "Read his story" link; leave a text empty to hide it), What we believe and our team (one belief per line; team photos), Latest news & blogs (the three newest posts, automatic), Closing verse, Floating WhatsApp button, Floating "Donate" panel.
  - Text in [square brackets] (amounts, names, villages, district counts, the founder's quote, team members, "[Amount]") is the design's placeholder: replace it with the real content before launch.
  - Prayer requests are saved under Kingdom Revival → Submissions ("Prayer request") and emailed to the form notification addresses, with the same spam protection as the contact form. WhatsApp links use the WhatsApp number in Kingdom Revival → Settings.
* Site text (Kingdom Revival → Site text): header name, top bar button, footer, ministry page and story page wording, the All Ministries page (/ministries/: header image, headings, introduction, card button), the 404 page and the sermons/events/testimonials page headings.
* Ministries (dashboard → Ministries): title, Excerpt (the short description), focus, "how this ministry serves" points, photo gallery, home page explorer image and position. Page Attributes → Order sets the order in menus and the donation/partner forms.
* Stories (Posts): normal posts. Pick the ministry and reading time in the "Story details" box. Blocks that match the design: Paragraph (add the class "lead" to the opening paragraph), Heading, Quote, Image with caption, List with the class "checks", Gallery, and the shortcode [krm_give] for the "Help write the next chapter" box.
* Sermons (/sermons/): title, notes, excerpt, featured image, Series, and in "Sermon details" the speaker, Bible passage, video link (YouTube/Vimeo/Facebook) and audio file. The publish date is the date preached.
* Events (/events/): title, description, excerpt, featured image, and in "Event details" start and end, venue, address and a registration link. Upcoming events are listed first, past events below them.
* Testimonials (/testimonials/): the title is the person's name, the editor holds their words, the featured image is their photo. Show them inside any story or page with [krm_testimonials count="3"] or [krm_testimonials ministry="widows-ministry"].
  The sermons, events and testimonials pages are not linked anywhere until you add them to a menu (Appearance → Menus → Custom Links or the "Sermons/Events/Testimonials" boxes).
* Menus (Appearance → Menus): Main navigation, Footer Quick Links, Footer Ministries. Every page, the home page included, uses the same header, menu and footer. A custom link "#" with sub-items becomes a dropdown; when its sub-items have sub-items of their own (as Ministries → groups → ministries), on desktop it opens as a full-width panel with one column per group. "All ministries" is the All Ministries page: add it from the "Ministries" box → "View All" tab (it stays correct if the site's address changes), and give it the CSS class "menu-all".
  Prefer adding pages with the "Pages" box. A custom link to a page by anchor or old file name ("#contact", "/#contact", "contact.html", "index.html", "blog-post.html?post=…", "all-ministries.html?ministry=…") still opens the real WordPress page; a plain "all-ministries.html" link opens the All Ministries page. Anchors of home page sections such as "/#all-ministries" (the home page's ministry explorer) are kept (the page scrolls to the section and the "#…" is not shown in the address bar).
* Impact numbers, contact details, social links, bank and PayPal details: Kingdom Revival → Settings (or Appearance → Customize for the first three). Logo: Appearance → Customize → Site Identity.
* Design pages: About, Contact, Donate and Become a Partner use the templates in page-templates/ (Page Attributes → Template). Setup assigns them; a page with one of those slugs and no template chosen gets its design template automatically. Links across the site find these pages by template, so a page can be renamed safely.

== Online giving ==

Gifts are paid on PayPal's own secure pages (paypal.com): by PayPal balance or by debit/credit card without a PayPal account. No card details reach WordPress, and no API keys are stored. The Donate page looks and works as in the design; the gift form fills in PayPal's checkout.

* Kingdom Revival → Settings → Online giving:
  - PayPal account email: the ministry's PayPal Business account (it must be able to receive payments; PayPal does not open accounts for Pakistan, so it is held by the partner abroad). With it set, donors get PayPal's checkout for one-time AND monthly gifts with the amount and ministry filled in, and gifts are recorded. Empty = the PayPal.me link (one-time only, nothing recorded).
  - PayPal mode: Sandbox (testing on sandbox.paypal.com, no real money) or Live.
  - Thank-you receipts: on/off, plus the receipt email's subject and message (placeholders {name}, {full_name}, {amount}, {frequency}, {fund}, {date}, {transaction}, {email}, {phone}).
* How a gift is recorded: every checkout tells PayPal to report the payment to this site (PayPal "Instant Payment Notification", sent to /wp-admin/admin-post.php?action=krm_paypal_ipn). The theme sends each report back to PayPal to confirm it is genuine and that it is for the ministry's account; only then is it saved under Kingdom Revival → Donations (donor, email, amount, monthly or one-time, ministry, PayPal fee and transaction ID; Export CSV). The donor is emailed the thank-you receipt once the payment is Completed, and the ministry is notified at the form notification addresses. Repeated reports never create duplicates. Refunds, reversals and cancelled monthly gifts update the donation and notify the ministry.
* After paying, PayPal returns the donor to the Donate page's thank-you view (/donate/?thanks=1; not indexed by search engines). Its wording is in Pages → Donate → Page content → "Thank-you view".
* Testing: create a free PayPal Developer account (developer.paypal.com), use its sandbox Business account's email in Settings with mode Sandbox, and give from the sandbox Personal account. The site must be reachable from the internet for PayPal's reports to arrive (not localhost). Sandbox gifts are marked "sandbox test" in Donations. Switch to Live and enter the real account email before launch.
* Monthly gifts are PayPal subscriptions: donors can cancel them in their PayPal account; the ministry can see and cancel them in its PayPal account.

== Speed ==

The theme already loads only what each page needs: one or two small stylesheets, scripts at the end of the page, the self-hosted font (preloaded, shown with a fallback font until it arrives), lazy-loaded images below the first screen, and a high-priority preload of each page's large hero image. Run Setup's image import (step 3 above): after it, images come from the site's own Media Library in the right size for each screen (phones get smaller files) instead of full-size files from the old site. Kingdom Revival → Settings → Images → "Image format" saves those resized copies as WebP (the default; much smaller files at the same visible quality). Images already uploaded keep their current copies until rebuilt with a plugin such as Regenerate Thumbnails. The home page's background video starts once the page has finished loading, so the first view isn't slowed by it; it is not started at all for visitors whose device asks for reduced motion or has data saver on (they see the same picture, still), and it pauses while scrolled out of view. The video file is about 9 MB: a shorter or lower-bitrate copy uploaded in its place (Pages → Home → Page content → "Background video (MP4 link)") is the biggest saving left for phone visitors. The photo viewer (assets/js/viewer.js) loads only on pages with photos that open full screen: home, About, ministry pages, stories, sermons and events. Design images are looked up in the Media Library in one go per page (two queries instead of two per image). Ministry gallery photos load as they scroll into view, in the size the photo grid shows. Cards and gallery tiles (home cause cards and field stories, story cards, Donate's impact cards, ministry and story galleries, All Ministries) get 4:3 crops of portrait photos (sizes krm-card-480/800/1200, made only for photos taller than 5:4), so phones no longer download a whole tall photo to show its middle band; the crop shows exactly what the card showed. Images uploaded before theme 1.8.1 get these crops in the background, a few at a time on WP-Cron (option krm_card_backfill, -1 when done). Standard-density desktops up to 1536 px wide get the 1536 px copy of a hero or band photo when it is big enough to cover it; sharper screens keep the full image. Kingdom Revival → Settings → Images → "Unused plugin styles" leaves out WordPress editor styles and the Dashicons font that some plugins add to every public page (visitors only; on by default).
Most of a slow PageSpeed score usually comes from plugins, not the theme: page builders (Elementor, Elementor Pro, Header Footer Elementor), starter-template and form plugins load their CSS, fonts and jQuery on every page although the theme does not use them. Keep only the plugins the site needs (see GO-LIVE-CHECKLIST.md in the project).

On the hosting side (a theme cannot set these):
* Page caching: use the host's page cache, or one caching plugin (e.g. WP Super Cache, W3 Total Cache or LiteSpeed Cache on LiteSpeed servers). Pages are cache-safe: visitors' pages carry no per-visitor data.
* Compression and browser caching for theme files and uploads. Theme files have a version in their address (?ver=…) that changes with every theme update, so they can be cached for a year. On Apache/LiteSpeed hosts without a caching plugin that does this, add to .htaccess:
    <IfModule mod_deflate.c>
      AddOutputFilterByType DEFLATE text/html text/css application/javascript application/json image/svg+xml
    </IfModule>
    <IfModule mod_expires.c>
      ExpiresActive On
      ExpiresByType text/css "access plus 1 year"
      ExpiresByType application/javascript "access plus 1 year"
      ExpiresByType font/woff2 "access plus 1 year"
      ExpiresByType image/webp "access plus 1 year"
      ExpiresByType image/jpeg "access plus 1 year"
      ExpiresByType image/png "access plus 1 year"
      ExpiresByType video/mp4 "access plus 1 year"
    </IfModule>
* Optimisation plugins: they are not needed for the theme's own files. Avoid "delay JavaScript until interaction" options (the menus, donation form and gallery would not respond to the first tap) and extra lazy-loading of images or backgrounds (it would delay the hero image, which the theme loads first on purpose).

== Security and accessibility ==

* Forms (contact, partner, newsletter, prayer request) are protected without a captcha: a hidden "leave empty" field, a signed token from the page the form is on (it does not expire, so cached pages keep working), a minimum time on the form, at most 5 messages per visitor per 10 minutes, and messages with more than 3 links are kept as "possible spam" without an email. Logged-in users' posts also need a WordPress nonce. "Please reload the page and try again" means the token was missing — usually a page cached before a theme update; clear the page cache.
* Submissions and Donations hold visitors' personal details: only Editors and Administrators can see or export them. Settings, Site text and Setup need an Administrator.
* Front-end pages send the headers X-Content-Type-Options, X-Frame-Options (SAMEORIGIN), Referrer-Policy and Permissions-Policy. If the host or a security plugin already sends them, remove the theme's with the `krm_security_headers` filter.
* Visitors can't look up user names (/?author=1 goes to the home page; the REST users list needs a login).
* Not done by the theme (use the host or one security plugin, e.g. Wordfence or Solid Security): login attempt limits, two-factor login, a firewall, XML-RPC blocking, file-change scans, backups.
* Accessibility: "Skip to content" link, keyboard-operable menus (Enter/Space opens, Esc closes), donation tabs (arrow keys), photo viewer and popups (focus stays inside, Esc closes, focus returns), visible focus outlines, labelled form fields whose errors are read out, alt text on every image (from the Media Library, or the alt field beside each image in Page content). When adding images, always fill in the Media Library "Alternative text"; leave it empty only for purely decorative images.

== Theme files ==

header.php / footer.php ..... the one header (top bar, logo, menu) and footer used on every page
front-page.php ............. home page; sections in template-parts/home/
page-templates/ ............ about.php, contact.php, donate.php, partner.php (design pages)
single-ministry.php ........ /ministries/{slug}/      archive-ministry.php ... /ministries/ (All Ministries)
archive-/single-sermon.php . /sermons/  taxonomy-sermon_series.php  archive-/single-event.php . /events/  archive-testimonial.php
home.php ................... News & Blogs            single.php ............. a story
page.php, 404.php, search.php, archive.php, index.php, comments.php
template-parts/ ............ home/* (one file per home section), page-hero, give-band, faq, post-list, type-archive, type-single, donate-thanks
theme.json ................. colour palette, font (Plus Jakarta Sans, self-hosted), content widths for the editor
inc/setup.php .............. theme supports, menus, page template routing, block CSS rules
inc/enqueue.php ............ styles and scripts per view
inc/customizer.php ......... Customizer controls (same option as the Settings screen)
inc/content-fields.php ..... editable page content (Page content boxes, Site text) — schemas in inc/content/
inc/content-types.php ...... sermons, events, testimonials (fields, ordering, [krm_testimonials])
inc/template-functions.php . settings, URLs, media, logo, icons
inc/seo.php ................ design titles/descriptions/share images for SEO plugins; basic tags when none is active
inc/schema.php ............. structured data (JSON-LD) and the breadcrumb trail shown on pages
inc/redirects.php .......... 301 redirects from the previous site's addresses (editable in Settings)
inc/donations.php .......... PayPal checkout settings, payment notifications (IPN), Donations, receipts
inc/performance.php ........ hero image preload, smaller hero copies for phones/tablets/1× desktops, WebP image sizes, 4:3 card crops
inc/security.php ........... security headers, form token, no user-name lookup, rel="noopener" on new-tab links
inc/ ....................... also settings, ministries, menus, forms, story meta, setup importer
assets/css/theme-global.css  design tokens (colours, fonts, sizes, spacing, radii, shadows) and the components every page shares; change a token here to restyle the whole site
assets/css/site.css ........ inner-page base and layout  assets/css/pages/*.css ... per-page design styles
assets/css/front.css ....... home page design styles  assets/js/*.js ........... design behaviour (site.js on every page, viewer.js the photo viewer, one script per page type)
assets/icons/*.svg ......... theme icons (Lucide-style line icons), printed inline with krm_icon( 'name' )
assets/images/, assets/fonts/  (assets/images/content/: ministry photos and the old site's Elementor crops shipped with the theme, imported by Setup; the list of the crops is inc/data/media-local.php)

== Privacy ==

The font is served from the theme (no Google Fonts request). Form submissions are stored in WordPress and emailed to the ministry; nothing is sent to third parties unless a newsletter service URL is added in Settings. Donations: payment happens on PayPal's site under PayPal's privacy policy; the theme stores the donor name, email, country, amount and PayPal transaction ID that PayPal reports, never card details.
