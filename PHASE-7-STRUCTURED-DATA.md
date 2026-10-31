# Kingdom Revival Ministries: Phase 7 structured data (schema markup)

**Result: every page has valid JSON-LD describing the ministry, the site, the page and its breadcrumb. Stories,
events and sermon videos also get BlogPosting, Event and VideoObject. Nothing is described twice, and every value
matches what the page shows. With no SEO plugin, Yoast SEO or Rank Math, all 32 checked pages pass the theme's
check, and the Schema.org Validator reports 0 errors and 0 warnings on every page type. SureRank's own graph still
has faults the theme can't reach (see below).**

Tested on WordPress 7.1.4 with Yoast SEO 28.8, Rank Math 1.0.280 and SureRank 1.10.3 (each on its own), and with no
SEO plugin. New tool: [tools/visual-qa/schema_check.py](tools/visual-qa/schema_check.py). Code:
[theme/kingdom-revival/inc/schema.php](theme/kingdom-revival/inc/schema.php). Theme version 1.4.0.

## What each page says

| Type | Where | Taken from (what the page shows) |
|---|---|---|
| Organization | every page | Site name, logo, phone, email and address (footer); founder (About page, founder name); social links when set in Settings. Type set in Settings (see Decisions). |
| WebSite | every page | Site name, search |
| WebPage | every indexable page | AboutPage and ContactPage for those two pages; CollectionPage for News & Blogs, story categories, ministries, sermons, events and testimonials lists. Title and description as in the page's meta tags. |
| BreadcrumbList | every page with a visible breadcrumb (all but the home page and the "Choose a ministry" page) | The breadcrumb shown on the page: same names, same links, in the same order |
| BlogPosting | the 7 stories | Title (h1), cover image, published and modified dates, category, author box ("Kingdom Revival Ministries Team") |
| Event | an event with a start date and a venue or address | Title, When (start, end), Where (venue, address), featured image, excerpt; organiser = the ministry; attendance in person |
| VideoObject | a sermon whose video is embedded on the page | The video's title and thumbnail from YouTube/Vimeo, the embed address, the sermon date (shown on the page), excerpt |

**One source for the breadcrumb.** The visible breadcrumb on all nine kinds of page and the BreadcrumbList now come
from the same function (`krm_breadcrumbs()`), so they can't drift apart. The markup is unchanged. Before, they were
written separately, and five kinds of page (sermon and event pages and lists, story categories) showed a
breadcrumb with no BreadcrumbList.

**Not used, on purpose:**
- **FAQPage.** The Donate and Become a Partner FAQs are visible, but Google shows FAQ rich results only for
  well-known government and health sites, so this site isn't eligible.
- **eventStatus, offers, performer** on events. The site has no field for a cancelled or moved event, no ticket
  price and no speaker. Google's documentation treats a missing `eventStatus` as scheduled. Google lists these
  properties as recommended, not required.

## SEO plugins: no duplicates

With an SEO plugin active, the plugin's graph is kept and the theme prints no Organization, WebSite, WebPage,
BreadcrumbList or Article of its own. It only adds what the plugin lacks and corrects what doesn't match the page.

| | Yoast SEO | Rank Math | SureRank |
|---|---|---|---|
| Event, VideoObject | added to the plugin's graph | added to the plugin's graph | separate block with only these two |
| Organization | **Yoast printed none** (it needs a name and logo in its settings); the theme's is added | Rank Math's kept; the address, phone, email, logo, founder and URL it left out are filled in | SureRank's kept |
| BreadcrumbList | matched to the visible trail (it differed only on story categories) | **Rank Math printed none**; the visible trail is added | **8 of 10 page kinds differed** (e.g. "Home › Ministry › Gospel & Church › Baptisms"); now matched on all but the ministries list |
| Story author | **the WordPress user "admin"** → the author box ("Kingdom Revival Ministries Team") | same | unchanged: "admin" (see below) |
| Story headline | already the title | **was "Title - Kingdom Revival Ministries"** → the title | already the title |
| Pages that aren't stories | — | **Home, About, Contact, Donate and Partner were typed "Article"** (Rank Math's default for pages) → removed, unless a schema was chosen in the page's Rank Math Schema tab | — |
| Ministry, sermon and event pages | — | **printed no structured data at all** (no default schema type) → Rank Math now prints its own WebSite/WebPage there | — |

Anything set in the plugin's own schema settings is kept: a custom headline, a schema chosen for a page, the
plugin's organisation name and type.

## Verification

[schema_check.py](tools/visual-qa/schema_check.py) on 32 pages: the 30 from Phase 6 plus a sermon and an event.
It checks that the JSON-LD parses, nothing is described twice across all blocks, every `@id` reference resolves,
Google's required properties are present, and the values appear on the page: BreadcrumbList = the visible
breadcrumb, headline/name = the h1, dates, venue, author and embedded video, plus the contact details. `--validate`
sends one page of each kind (9 per setup) to the Schema.org Validator.

| Setup | Pages passing the theme check | Schema.org Validator (page kinds) | SEO tags check (Phase 6, 30 pages) |
|---|---|---|---|
| No SEO plugin (theme's graph) | 32 / 32 | 9 kinds: 0 errors, 0 warnings | 30 / 30 |
| Yoast SEO | 32 / 32 | 9 kinds: 0 errors, 0 warnings | 30 / 30 |
| Rank Math | 32 / 32 | 9 kinds: 0 errors, 0 warnings | 30 / 30 |
| SureRank | 0 / 32 (SureRank's own faults, below) | 2 of 9 kinds (home, About): 0 errors, 0 warnings; the validator then refused further requests (HTTP 429) | 30 / 30 |

Remaining warnings with every setup: the logo is 99×99, under Google's 112×112 minimum (see Decisions), and the
event has no `eventStatus` (above).

**Google Rich Results Test.** It can't reach a local test site, so it couldn't be run here. The theme check covers
the same required properties for Organization, BreadcrumbList, Article, Event and VideoObject. Run the test on the
live site after go-live (search.google.com/test/rich-results): one story, one event, one sermon with a video, and
the home page.

## Fixed along the way

1. **Organisation type.** The theme called the ministry an `NGO`, but nothing on the site says it is registered,
   and donations go to a personal bank account. It is now plain `Organization`, with NGO selectable in Settings.
2. **Address.** "Faisalabad" was in both the street address and the city. The street address is now the
   Address setting without the city. City, province and country are editable.
3. **Founder** is now read from the About page's founder name instead of being fixed in code.
4. **`</script>` in a title** (story, event…) would have ended the JSON-LD block early, breaking it and letting the
   rest of the title run as HTML. Values are now encoded so that can't happen.
5. **Sermon video lookup.** The sermon page asked YouTube/Vimeo for the embed on every view. The answer is now kept
   (refreshed when the link changes), and the same answer feeds VideoObject.

## Not covered

- **SureRank's own faults.** On every page SureRank's WebSite and WebPage refer to an author `#person` it never
  prints (125 broken references across the 32 pages). Its Article credits the WordPress user ("admin") rather than
  the author box, and its Organization lists "admin" as founder. These come from SureRank's schema settings
  (SureRank → Schema), and its filters give the theme no clean way to correct them. They are fixed by switching to
  Rank Math or Yoast (Phase 6, decision 1), or by editing those three schemas in SureRank. On the ministries list
  SureRank keeps a one-item "Home" BreadcrumbList; an empty one would be worse.
- **Rank Math encodes "&" as "&amp;"** in its JSON-LD text ("News &amp; Blogs"). That happens after every filter
  and applies to all Rank Math text, so the theme can't change it. The Schema.org Validator accepts it; how Google displays it is unverified.
- Real event and sermon content: the test site had one sample of each, with a public YouTube video.

## Decisions for you

1. **Organisation type.** Is Kingdom Revival Ministries registered, for example as an NGO, society or trust with
   the Pakistani authorities? If yes, choose NGO in Kingdom Revival → Settings → Structured data; if not, keep
   Organization. (schema.org has no type for a church-planting ministry, and `Church` means a church building.)
2. **Logo for search results.** Upload a square logo of at least 112×112 pixels (512×512 is ideal) under
   Appearance → Customize → Site Identity. The bundled logo is 99×99, below Google's minimum. Check the header
   after uploading; this wasn't tested with a larger logo.
3. **Go-live settings:**
   - Settings → General → Timezone: Karachi, so event times carry +05:00 (the test site is on UTC).
   - With Rank Math: Titles & Meta → Local SEO → "Organization" (Rank Math defaults to a person), the logo, and the
     name "Kingdom Revival Ministries".
   - With Yoast SEO: Settings → Site representation → Organization, the name and logo. Until then the theme's
     Organization is used.
   - Run the Rich Results Test on the live pages listed above.

## Unchanged

The visible pages are unchanged. On the 34 pages checked (the 32 above, search results and a second
category), the HTML outside the JSON-LD is byte-identical for 24. The other 10 differ only in whitespace and in
`&#038;` versus `&amp;`, which are the same character ("News & Blogs", "Deliverance & Healing").

Pixel comparison (`compare.js`, 33 comparisons) **could not re-confirm 0% here**. The local test database is the
Phase 6 copy of the live site's database, with "Hello world!" published and older content. On it, News & Blogs and
the stories differ from the design (up to 46%) with the Phase 6 theme (1.3.0) as much as with 1.4.0, and a few small
diffs (0–5%) change from run to run. Since the rendered HTML is the same, the screenshots are too. Re-run
`npm run pages` on a clean default-content site before release to record the 0% figures.
