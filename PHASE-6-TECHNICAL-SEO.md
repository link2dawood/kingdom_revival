# Kingdom Revival Ministries: Phase 6 technical SEO (URLs and metadata)

**Result: every indexable page has exactly one SEO title, meta description, canonical URL, Open Graph title,
description, image and URL, and Twitter card, managed by an SEO plugin (Rank Math or Yoast SEO). The theme
prints none of these while a plugin is active. Every public address of the current live site keeps working,
either unchanged or through a single 301 redirect to its new page.**

Tested on WordPress 7.1.4 with Yoast SEO 28.8, Rank Math 1.0.280 and SureRank 1.10.3 (each on its own), and with
no SEO plugin. Tools: [tools/visual-qa/seo_check.py](tools/visual-qa/seo_check.py).

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

Also: single testimonials redirect to `/testimonials/`, so they are kept out of the core, Yoast and Rank Math
sitemaps.

## Decisions for you

1. **Which plugin:** Rank Math or Yoast SEO, as the brief suggests. Keeping SureRank also works and keeps
   its existing settings, but the old per-page descriptions it generated are mostly unusable ("About About
   Kingdom Revival Ministries…").
2. **Title separator:** set the plugin's separator to "|" so story and ministry titles match the design
   ("Baptisms | Kingdom Revival Ministries"). Plugins default to "-".
3. **`/donate-us/` and `/latest-news-blogs/`:** the new pages are `/donate/` and `/blog/`, with 301s from the
   old addresses. To keep the old addresses instead, rename the two pages' slugs. Links across the theme find
   these pages by template, so nothing else changes. Then remove those two lines from Redirects.

## Unchanged

The site looks exactly as before. All 33 page comparisons still match the original HTML pixel for pixel
(Contact's intentional sentence aside), and the Phase 4 content audit, page titles included, is unchanged.
