# Kingdom Revival Ministries: Phase 6 technical SEO (URLs and metadata)

**Result: every indexable page has exactly one SEO title, meta description, canonical URL, Open Graph title,
description, image and URL, and Twitter card, managed by an SEO plugin (Rank Math or Yoast SEO). The theme
prints none of these while a plugin is active. Every public address of the current live site keeps working,
either unchanged or through a single 301 redirect to its new page.**

**Indexing (6.4):** no page is accidentally `noindex`. Canonicals are valid, robots.txt blocks nothing important,
and every sitemap now lists only pages that answer 200. Old redirected addresses had been listed; they no longer
are. **Internal linking (6.5):** existing links are unchanged, and five link additions are proposed for approval.

Tested on WordPress 7.1.4 with Yoast SEO 28.8, Rank Math 1.0.280 and SureRank 1.10.3 (each on its own), and with
no SEO plugin. Tools: [tools/visual-qa/seo_check.py](tools/visual-qa/seo_check.py),
[tools/visual-qa/index_check.py](tools/visual-qa/index_check.py).

## 6.1 URLs

**Permalinks:** "Post name" (`/%postname%/`). The theme's addresses are short and readable:
`/about/`, `/contact/`, `/donate/`, `/partner/`, `/blog/`, stories at `/{story}/`, ministries at
`/ministries/{ministry}/`, and `/sermons/`, `/events/`, `/testimonials/`.

**Existing public URLs.** The live site (kingdomrevivalministries.com, WordPress + Elementor + the SureRank SEO
plugin) lists 27 public addresses in its sitemaps. All were checked on the new site:

| Old address (live site today) | On the new site |
|---|---|
| `/`, `/about/`, `/contact/` | unchanged (200) |
| `/donate-us/` | 301 → `/donate/` |
| `/latest-news-blogs/` | 301 → `/blog/` |
| `/baptisms/`, `/clean-water-project/`, `/leadership-and-discipleship/`, `/outreach-ministry/`, `/widows-ministry/` | 301 → the same name under `/ministries/` |
| `/bricks-kiln-slaves-children/` | 301 → `/ministries/brick-kiln-children/` |
| `/deliverance-and-healing-ministry/` | 301 → `/ministries/deliverance-and-healing/` |
| `/freedom-from-slavery-missions/` | 301 → `/ministries/freedom-from-slavery/` |
| `/free-medical-camp-for-brick-kiln-slaves-and-village-people/` | 301 → `/ministries/free-medical-camps/` |
| `/orphanage-and-underprivileged-children-care/`, `/orphanages-care/` | 301 → `/ministries/orphan-and-children-care/` |
| `/urdu-bibles-distribution/` | 301 → `/ministries/urdu-bibles/` |
| `/outreach-evangelism/` | 301 → `/ministries/outreach-ministry/` |
| `/sunday-school/` (placeholder text) | 301 → `/ministries/children-ministry/` |
| `/executive-message/` (founder's welcome) | 301 → `/about/#founder` |
| `/services/` (template placeholder) | 301 → `/#all-ministries` |
| `/form/`, `/elementor-2338/` (home page drafts), `/sample-page/` | 301 → `/` |
| `/hello-world/` (WordPress sample post), `/category/uncategorized/` | 301 → `/blog/` |
| `/category/ministries/` | 301 → `/ministries/` |

- Each redirect is a single hop to a page that returns 200. Matching ignores letter case and the trailing
  slash.
- Redirects take priority over an old page that still exists at that address. This matters when the theme
  is installed on the live site's database, where the old Elementor pages remain.
- **Editable:** Kingdom Revival → Settings → Redirects, one `old new` pair per line. Targets can be a
  `/path`, a full URL or `{donate}`, `{blog}`, `{about}`….
- The design's static-file addresses (`about.html`, `donate.html?amount=…`,
  `all-ministries.html?ministry=…`, `blog-post.html?post=…`) also redirect in one hop, keeping
  donate/partner pre-fill values.
- Address variants such as `/?ministry=…` declare the clean address as canonical, and WordPress already
  301s `?p=` links.

## 6.2 Metadata

**Managed by the SEO plugin.** While Rank Math, Yoast SEO, SureRank, All in One SEO, SEOPress, The SEO
Framework or Slim SEO is active, the theme prints no title format, description, canonical, Open Graph, Twitter
or JSON-LD tags. Every page's values are edited in the plugin's box on that page.

**Design defaults, only where the plugin's field is empty** (Rank Math, Yoast SEO, SureRank):

| | Default the theme offers |
|---|---|
| SEO title and social title | The design's titles: "Kingdom Revival Ministries — Home", "About Us \| Kingdom Revival Ministries", "Ministry Details \| …", "News & Blogs \| …" and the other design pages. Other pages use the plugin's title format. |
| Meta and social description | The design's description for each page; a ministry's or story's excerpt; list pages: their introduction. |
| Social image | The page's hero image (Page content → Hero), a story's cover, a ministry's first photo. |

Anything typed in the plugin wins. Tested: a custom title and description in Yoast and in Rank Math replace
the defaults, and the share title then follows the custom SEO title.

**Results** (30 pages: the 26 design pages + sermons, events, testimonials, a story category):

| Setup | Pages with every required tag exactly once | Theme tags printed |
|---|---|---|
| Yoast SEO | 30 / 30 | none |
| Rank Math (setup wizard done) | 30 / 30 | none |
| SureRank | 30 / 30 | none |
| No SEO plugin | 30 / 30 (theme's basic tags) | yes, as fallback |

**Two findings fixed during testing:**

1. **SureRank wasn't detected.** The live site uses SureRank, which the theme didn't recognise. Installing the
   theme on the live site would have printed every tag twice. It is now detected.
2. **Rank Math prints nothing until its setup wizard is completed or skipped.** Before the fix, a site with
   Rank Math installed but not set up had no meta tags at all. The theme now keeps its own tags until Rank
   Math is ready, and Kingdom Revival → Settings shows a notice saying so.

Also: single testimonials redirect to `/testimonials/`, so they are kept out of the core, Yoast, Rank Math and
SureRank sitemaps.

## 6.4 Indexing

Tested on WordPress 7.1.4 with no SEO plugin, Yoast SEO 28.8, Rank Math 1.0.280 and SureRank 1.10.3. The test
site ran on the live site's kind of database: the theme's content plus old published pages at `/donate-us/`,
`/latest-news-blogs/`, `/baptisms/`, `/executive-message/` and `/services/`, plus WordPress's "Hello world!" and
"Sample Page". Tool: [tools/visual-qa/index_check.py](tools/visual-qa/index_check.py), requesting pages as Googlebot.

| Check | Result |
|---|---|
| Accidental `noindex` | None. All 30 important pages are indexable (no robots meta `noindex`, no `X-Robots-Tag`) with every setup. The theme prints no robots directives of its own. |
| Intentional `noindex` | Kept: search results (`/?s=`), the login page and 404 pages stay `noindex`. These come from WordPress and the SEO plugin, and nothing was removed. |
| Search-engine visibility | "Discourage search engines" is off on the test site. If it's switched on, every page gets `noindex, nofollow` and the sitemap is switched off. The tool flags that on all 30 pages, and the theme's Setup screen also warns while it's on. The live site today is `index, follow`. |
| Canonical URLs | Exactly one canonical per page. It's absolute, on the site's own domain and self-referencing, and it answers 200. Same result with every setup. |
| Crawler access | All 30 pages answer 200 to Googlebot. robots.txt blocks only `/wp-admin/` (Yoast: nothing). Theme CSS, JS and uploads are allowed, and robots.txt names the sitemap. |
| XML sitemaps | **Two problems found and fixed (below).** Now 35 URLs with each setup. Every one answers 200, is indexable and has itself as canonical. |

**Fixed: sitemaps listed redirected addresses.** Old pages that still exist in the database (the Elementor
"Donate us", "Baptisms" and so on), "Hello world!", "Sample Page" and Uncategorized were listed in every sitemap,
even though each one 301s to its new page. Installed on the live database, that would submit about 25 redirecting
URLs. Now any address in Kingdom Revival → Settings → Redirects is left out of the core, Yoast, Rank Math and
SureRank sitemaps, so editing the Redirects list keeps the sitemaps in step.

**Fixed: list pages were missing or inconsistent.**
- Core sitemaps didn't list `/sermons/`, `/events/` or `/testimonials/`. A small archives sitemap now lists each
  one, but only while it has published items, so an empty list is never submitted.
- Yoast and SureRank had dropped `/testimonials/`, which is now listed.
- `/ministries/` (the design's short "Choose a ministry to explore" page) is now left out of every sitemap; the
  12 ministry pages are listed. It stays indexable.
- Yoast listed `/author/admin/`, which exposes the username. Author archives are now left out with Yoast, as they
  already were in core sitemaps.

| Sitemap | URLs listed | Redirecting |
|---|---|---|
| Before, no SEO plugin (core sitemaps) | 40 | 8 |
| Before, SureRank | 45 | 9 |
| After, each of no plugin / Yoast / Rank Math / SureRank | 35 (the same 35) | 0 |

Yoast and Rank Math were first tested after the redirect fix. Their remaining differences were the author
archive, `/ministries/`, `/testimonials/` and, with Rank Math, a single testimonial; all are fixed.

**Also found on the live site, now handled:**
- The live SureRank sitemap lists six Robo Gallery photo pages (`/gallery/widows-ministry/` and five others)
  that weren't in the 6.1 table. They now 301 to the matching ministry page in one hop, from the default
  Redirects list.
- It also lists Elementor templates (`/elementor-hf/header/`, `?elementor_library=…`) and a SureForms form
  (`/form/1270/`). These aren't real pages, and once Elementor and SureForms are deactivated they will 404,
  which is the right way for search engines to drop them.

## 6.5 Internal linking

All existing internal links are unchanged; no new link has been inserted. Every crawlable page is reached by
plain links in one or two clicks from the home page (17 in one click, 7 in two). Today's links:

| Path from the brief | Today |
|---|---|
| Home → About | Yes: About section button, main menu and footer |
| Home → Ministries | Yes: all 12 ministry pages from the menu, the ministry explorer and the footer |
| Home → Donate | Yes: header button, hero, give section and floating button |
| Home → Sermons | **No.** No page links to `/sermons/`, `/events/` or `/testimonials/`; they're found only through the sitemap |
| Ministry pages → sermons | **No.** Ministry pages link only to "All ministries" and their give form, and sermons have no "Related ministry" field |
| Blog articles → ministry | Yes: each story links to its ministry twice (the "Ministry:" line and the sidebar card) |
| Contact → calls to action | Partly: Donate (sidebar button) and prayer (form). Become a Partner only from the menu |

**Proposed for approval.** None of these is built; each one adds visible content.

1. **Sermons link, once sermons are published.** Add "Sermons" to the footer Quick Links menu (Appearance →
   Menus; no code) or as a text link in the home Get Involved section. Same for Events and Testimonials when
   they have content. Linking an empty list now would send visitors and crawlers to a blank page.
2. **"Stories from this ministry" on ministry pages.** A row of the existing story cards (the blog's design)
   above the give band, showing stories whose Ministry is set to that ministry. Today that covers 7 of the 12
   ministries; the row is hidden where there are none. This is the reverse of the story → ministry link that
   already exists.
3. **Sermons on ministry pages.** Add the "Related ministry" field that testimonials already have to sermons,
   then show that ministry's latest sermons in the same row as proposal 2.
4. **In-text ministry links in stories.** Each story names its ministry in the body ("Our widows ministry
   brings…"). Link that first mention to the ministry page. This is a content edit in the story, with no code.
5. **Contact → Become a Partner.** Add one line under the Contact sidebar's give button: "Want to stand with us
   every month? Become a partner →".

## Decisions for you

1. **Which plugin:** Rank Math or Yoast SEO, as the brief suggests. Keeping SureRank also works and keeps
   its existing settings, but the old per-page descriptions it generated are mostly unusable ("About About
   Kingdom Revival Ministries…").
2. **Title separator:** set the plugin's separator to "|" so story and ministry titles match the design
   ("Baptisms | Kingdom Revival Ministries"). Plugins default to "-".
3. **`/donate-us/` and `/latest-news-blogs/`:** the new pages are `/donate/` and `/blog/`, with 301s from the
   old addresses. To keep the old addresses instead, rename the two pages' slugs. Links across the theme find
   these pages by template, so nothing else changes. Then remove those two lines from Redirects.
4. **Internal links:** which of the five 6.5 proposals to build.
5. **Go-live steps for the live database:**
   - Untick Settings → Reading → "Discourage search engines" if it is on.
   - Move "Hello world!" to the trash. While it's published, News & Blogs and the related-stories rows show it,
     and it links to `/hello-world/`, which redirects to `/blog/`.
   - Deactivate Elementor, Robo Gallery and SureForms if they're no longer used.
   - Regenerate the SEO plugin's sitemap once after switching theme (SureRank and Rank Math cache it).
   - Resubmit the sitemap in Google Search Console.

## Unchanged

The site looks exactly as before. All 33 page comparisons still match the original HTML pixel for pixel
(Contact's intentional sentence aside), and the Phase 4 content audit, page titles included, is unchanged.
