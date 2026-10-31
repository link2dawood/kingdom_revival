#!/usr/bin/env python3
"""Structured data check: every page's JSON-LD must parse, describe each thing once, resolve every @id reference,
have the properties Google requires for its rich result, and say only what the page shows.

usage: python3 schema_check.py [base_url] [--validate] [--json out.json]

  --validate  also send each page's JSON-LD (only the JSON-LD) to the Schema.org Validator
              (validator.schema.org) and report its errors and warnings.

Checks per page:
  - JSON-LD parses; nothing described twice (Organization, WebSite, WebPage, BreadcrumbList, Article, Event,
    VideoObject, FAQPage), counting every block on the page; the theme's own graph never next to a plugin's
  - every {"@id": …} reference points to a node on the page
  - Google's required properties (errors) and recommended ones (warnings) for Organization, BreadcrumbList,
    Article/BlogPosting, Event, VideoObject and FAQPage
  - matches the page: BreadcrumbList = the visible breadcrumb, headline/name = the h1, dates, venue, author,
    embedded video, contact details and FAQ questions appear on the page
"""
import html
import json
import re
import sys
import time
import urllib.parse
import urllib.request

from seo_check import PATHS, fetch

BASE = sys.argv[1] if len(sys.argv) > 1 and not sys.argv[1].startswith('--') else 'http://127.0.0.1:8899'
PAGE_TYPES = {'WebPage', 'CollectionPage', 'AboutPage', 'ContactPage', 'ItemPage', 'ProfilePage', 'SearchResultsPage'}
ARTICLE_TYPES = {'Article', 'BlogPosting', 'NewsArticle'}
ORG_TYPES = {'Organization', 'NGO'}
ONCE = {'organization': ORG_TYPES, 'website': {'WebSite'}, 'webpage': PAGE_TYPES, 'breadcrumb': {'BreadcrumbList'},
        'article': ARTICLE_TYPES, 'event': {'Event'}, 'video': {'VideoObject'}, 'faq': {'FAQPage'}}
TZ = re.compile(r'T\d\d:\d\d(:\d\d)?([+-]\d\d:\d\d|Z)$')


def types(node):
    t = node.get('@type', []) if isinstance(node, dict) else []
    return set(t if isinstance(t, list) else [t])


def top_nodes(data):
    if isinstance(data, list):
        for x in data:
            yield from top_nodes(x)
    elif isinstance(data, dict):
        if '@graph' in data:
            yield from top_nodes(data['@graph'])
        else:
            yield data


def walk(value, key=''):
    """Yield (parent key, dict) for every object in a value."""
    if isinstance(value, dict):
        yield key, value
        for k, v in value.items():
            yield from walk(v, k)
    elif isinstance(value, list):
        for v in value:
            yield from walk(v, key)


def text_of(fragment):
    fragment = re.sub(r'<(script|style)[^>]*>.*?</\1>', ' ', fragment, flags=re.S)
    return re.sub(r'\s+', ' ', html.unescape(re.sub(r'<[^>]+>', ' ', fragment))).strip()


def norm(s):
    return re.sub(r'\s+', ' ', html.unescape(str(s))).strip()


def digits(s):
    return re.sub(r'\D', '', str(s))


def visible_breadcrumb(doc):
    m = re.search(r'<ol class="breadcrumb">(.*?)</ol>', doc, re.S)
    if not m:
        return []
    out = []
    for li in re.findall(r'<li[^>]*>(.*?)</li>', m.group(1), re.S):
        href = re.search(r'href="([^"]+)"', li)
        out.append((text_of(li), html.unescape(href.group(1)) if href else ''))
    return out


def check_page(path, doc):
    errors, warnings = [], []
    blocks = re.findall(r'<script([^>]*application/ld\+json[^>]*)>(.*?)</script>', doc, re.S)
    nodes, theme_full, plugin_blocks = [], False, 0
    for attrs, body in blocks:
        try:
            data = json.loads(body)
        except ValueError as e:
            errors.append(f'JSON-LD does not parse: {e}')
            continue
        found = list(top_nodes(data))
        nodes += found
        if 'krm-schema' in attrs:
            theme_full = theme_full or any('WebSite' in types(n) for n in found)
        else:
            plugin_blocks += 1
    if theme_full and plugin_blocks:
        errors.append("theme's graph printed next to an SEO plugin's")

    # Each thing once.
    for name, wanted in ONCE.items():
        n = sum(1 for x in nodes if types(x) & wanted)
        if n > 1:
            errors.append(f'{name} x{n}')

    # References resolve.
    defined = {o['@id'] for n in nodes for _, o in walk(n) if '@id' in o and len(o) > 1 and ('@type' in o or 'url' in o)}
    for n in nodes:
        for key, o in walk(n):
            if key != 'item' and '@id' in o and '@type' not in o and o['@id'] not in defined:
                errors.append(f'unresolved @id {o["@id"]} ({key})')

    text = text_of(doc.split('<body', 1)[-1])
    h1 = re.search(r'<h1[^>]*>(.*?)</h1>', doc, re.S)
    h1 = text_of(h1.group(1)) if h1 else ''
    canonical = re.search(r'<link\s+[^>]*rel=["\']canonical["\'][^>]*href=["\']([^"\']+)', doc)
    canonical = html.unescape(canonical.group(1)) if canonical else ''
    by_type = lambda wanted: [x for x in nodes if types(x) & wanted]

    for org in by_type(ORG_TYPES):
        for p in ('name', 'url'):
            if not org.get(p):
                errors.append(f'Organization: missing {p}')
        logo = org.get('logo')
        if not logo:
            warnings.append('Organization: no logo')
        elif isinstance(logo, dict) and logo.get('width') and min(int(logo.get('width', 0)), int(logo.get('height', 0))) < 112:
            warnings.append(f"Organization: logo {logo.get('width')}x{logo.get('height')} is under Google's 112x112 minimum")
        if org.get('email') and org['email'].lower() not in text.lower():
            errors.append(f"Organization: email {org['email']} not on the page")
        if org.get('telephone') and digits(org['telephone']) not in digits(text):
            errors.append(f"Organization: telephone {org['telephone']} not on the page")
        street = (org.get('address') or {}).get('streetAddress') if isinstance(org.get('address'), dict) else ''
        if street and norm(street) not in text:
            errors.append('Organization: address not on the page')

    crumbs = visible_breadcrumb(doc)
    for bl in by_type({'BreadcrumbList'}):
        items = bl.get('itemListElement') or []
        names = []
        for i, li in enumerate(items):
            item = li.get('item')
            name = li.get('name') or (item.get('name') if isinstance(item, dict) else '')
            url = item.get('@id') if isinstance(item, dict) else item
            names.append(norm(name))
            if str(li.get('position')) != str(i + 1):  # Rank Math prints numbers as strings
                errors.append('BreadcrumbList: positions out of order')
            if not name:
                errors.append('BreadcrumbList: item without name')
            if not url and i < len(items) - 1:
                errors.append('BreadcrumbList: item without URL')
            if i < len(crumbs) - 1 and url and url != crumbs[i][1]:
                errors.append(f'BreadcrumbList: {name} links to {url}, page links to {crumbs[i][1]}')
            if i == len(items) - 1 and url and canonical and url != canonical:
                errors.append(f'BreadcrumbList: last item {url} is not the canonical {canonical}')
        if names != [c[0] for c in crumbs]:
            errors.append(f'BreadcrumbList {names} != visible {[c[0] for c in crumbs]}')
    if crumbs and not by_type({'BreadcrumbList'}):
        warnings.append('visible breadcrumb without BreadcrumbList')

    for page in by_type(PAGE_TYPES):
        if canonical and page.get('url') and page['url'] != canonical:
            errors.append(f"{sorted(types(page))[0]} url {page['url']} is not the canonical {canonical}")

    for art in by_type(ARTICLE_TYPES):
        if norm(art.get('headline', '')) != h1:
            errors.append(f"Article headline {art.get('headline')!r} != h1 {h1!r}")
        author = art.get('author')
        author = author[0] if isinstance(author, list) and author else author
        if isinstance(author, dict) and not author.get('name') and author.get('@id'):
            author = next((x for x in nodes if x.get('@id') == author['@id']), author)
        name = author.get('name') if isinstance(author, dict) else ''
        if not name:
            errors.append('Article: author has no name')
        elif norm(name) not in text:
            errors.append(f'Article: author {name!r} is not shown on the page')
        for p in ('datePublished', 'image'):
            if not art.get(p):
                warnings.append(f'Article: no {p}')
        if art.get('datePublished') and f'datetime="{art["datePublished"]}"' not in doc:
            errors.append(f"Article: datePublished {art['datePublished']} is not the page's date")

    for ev in by_type({'Event'}):
        if norm(ev.get('name', '')) != h1:
            errors.append(f"Event name {ev.get('name')!r} != h1 {h1!r}")
        for p in ('name', 'startDate', 'location'):
            if not ev.get(p):
                errors.append(f'Event: missing {p}')
        for p in ('startDate', 'endDate'):
            if ev.get(p) and not TZ.search(ev[p]):
                errors.append(f'Event: {p} {ev[p]} has no time zone')
        loc = ev.get('location') or {}
        if isinstance(loc, dict) and not loc.get('address'):
            errors.append('Event: location has no address (Google requires one)')
        for p in ('name',):
            if isinstance(loc, dict) and loc.get(p) and norm(loc[p]) not in text:
                errors.append(f'Event: venue {loc[p]!r} not on the page')
        addr = loc.get('address') if isinstance(loc, dict) else None
        street = addr.get('streetAddress') if isinstance(addr, dict) else addr
        if street and norm(street) not in text:
            errors.append('Event: address not on the page')
        if ev.get('startDate'):
            y, mo, d = ev['startDate'][:10].split('-')
            import calendar
            shown = f'{calendar.month_name[int(mo)]} {int(d)}, {y}'
            if shown not in text:
                errors.append(f'Event: start date {shown} not on the page')
        for p in ('description', 'image', 'endDate', 'organizer', 'eventStatus'):
            if not ev.get(p):
                warnings.append(f'Event: no {p}')

    for v in by_type({'VideoObject'}):
        for p in ('name', 'thumbnailUrl', 'uploadDate'):
            if not v.get(p):
                errors.append(f'VideoObject: missing {p}')
        if v.get('uploadDate') and not TZ.search(v['uploadDate']):
            errors.append('VideoObject: uploadDate has no time zone')
        embed = v.get('embedUrl') or v.get('contentUrl')
        if not embed:
            warnings.append('VideoObject: no embedUrl/contentUrl')
        elif embed.split('?')[0] not in html.unescape(doc):
            errors.append(f'VideoObject: {embed} is not embedded on the page')
        if not v.get('description'):
            warnings.append('VideoObject: no description')

    for faq in by_type({'FAQPage'}):
        for q in faq.get('mainEntity') or []:
            if norm(q.get('name', '')) not in text:
                errors.append(f"FAQPage: question {q.get('name')!r} not on the page")

    summary = sorted({t for n in nodes for t in types(n)})
    return errors, warnings, summary, [body for _, body in blocks]


def validate(blocks):
    """Schema.org Validator result for a page's JSON-LD: (errors, warnings, messages)."""
    code = ''.join(f'<script type="application/ld+json">{b}</script>' for b in blocks)
    req = urllib.request.Request('https://validator.schema.org/validate', data=urllib.parse.urlencode({'html': code}).encode(), method='POST')
    for attempt in range(6):
        time.sleep(4)
        try:
            with urllib.request.urlopen(req, timeout=120) as r:
                res = json.loads(r.read().decode('utf-8').split('\n', 1)[1])
            break
        except urllib.error.HTTPError as e:
            if e.code != 429 or attempt == 5:
                raise
            time.sleep(30 * (attempt + 1))
    messages = []
    for _, o in walk(res.get('tripleGroups', [])):
        for e in o.get('errors', []) if isinstance(o.get('errors'), list) else []:
            messages.append(f"{e.get('errorType')} {' '.join(map(str, e.get('args', [])))}")
    return res.get('numErrors', 0), res.get('numWarnings', 0), sorted(set(messages))


def main():
    paths = list(PATHS)
    for archive in ('/sermons/', '/events/'):
        status, doc = fetch(BASE + archive)
        m = re.search(r'<a class="post-card" href="([^"]+)"', doc)
        if m:
            paths.append(urllib.parse.urlparse(html.unescape(m.group(1))).path)
    report, bad, validated = [], 0, set()
    for path in paths:
        status, doc = fetch(BASE + path)
        errors, warnings, summary, blocks = check_page(path, doc) if status == 200 else ([f'status {status}'], [], [], [])
        row = {'path': path, 'types': summary, 'errors': errors, 'warnings': warnings}
        # One page per set of node types goes to the validator, which limits request rates.
        kind = tuple(summary)
        if '--validate' in sys.argv and blocks and kind not in validated:
            validated.add(kind)
            row['validator'] = dict(zip(('errors', 'warnings', 'messages'), validate(blocks)))
            if row['validator']['errors']:
                errors.append(f"Schema.org Validator: {row['validator']['errors']} errors {row['validator']['messages']}")
        bad += bool(errors)
        report.append(row)
        extra = f" | validator {row['validator']['errors']}E/{row['validator']['warnings']}W" if 'validator' in row else ''
        print(f"{'OK ' if not errors else 'BAD'} {path:44} {','.join(summary)[:90]}{extra}")
        for e in errors:
            print(f'      error: {e}')
        for w in warnings:
            print(f'      warn:  {w}')
    print(f'{len(paths)} pages, {bad} with errors')
    if '--json' in sys.argv:
        json.dump(report, open(sys.argv[sys.argv.index('--json') + 1], 'w', encoding='utf-8'), indent=1, ensure_ascii=False)
    return 1 if bad else 0


if __name__ == '__main__':
    sys.exit(main())
