#!/usr/bin/env python3
"""Indexing check for the WordPress site: robots.txt, robots meta / X-Robots-Tag, canonical URLs, XML sitemaps
and whether crawlers can reach every important page by following plain links from the home page.

usage: python3 index_check.py [base_url] [--json out.json]
"""
import html
import json
import re
import sys
import urllib.parse
import urllib.request
import urllib.robotparser
from collections import deque

from seo_check import BASE, PATHS, NoRedirect

# Pages that should stay out of search results on purpose (not reported as problems).
INTENTIONAL_NOINDEX = ['/?s=water', '/wp-login.php', '/this-page-does-not-exist/']
# Indexable but left out of the sitemap on purpose: the short "Choose a ministry" page.
NOT_IN_SITEMAP = ['/what-we-do/']
CRAWL_LIMIT = 300


def fetch(url):
    opener = urllib.request.build_opener(NoRedirect)
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (compatible; Googlebot/2.1)'})
    try:
        with opener.open(req, timeout=60) as r:
            return r.status, dict(r.headers), r.read().decode('utf-8', 'replace')
    except urllib.error.HTTPError as e:
        return e.code, dict(e.headers), e.read().decode('utf-8', 'replace')


def norm(url):
    """Absolute URL without fragment."""
    return urllib.parse.urldefrag(urllib.parse.urljoin(BASE + '/', html.unescape(url)))[0]


def internal(url):
    return urllib.parse.urlsplit(url).netloc == urllib.parse.urlsplit(BASE).netloc


def robots_directives(headers, doc):
    head = doc.split('</head>')[0]
    found = [m.group(1) for m in re.finditer(r'<meta\s+name=["\'](?:robots|googlebot)["\']\s+content=["\']([^"\']*)', head, re.I)]
    found += [v for k, v in headers.items() if k.lower() == 'x-robots-tag']
    return ', '.join(found)


def canonicals(doc):
    head = doc.split('</head>')[0]
    return [re.search(r'href=["\']([^"\']+)', m.group(0)).group(1)
            for m in re.finditer(r'<link\s+[^>]*rel=["\']canonical["\'][^>]*>', head)]


def links(doc):
    """Plain <a href> links a crawler follows (ignores rel=nofollow)."""
    out = []
    for m in re.finditer(r'<a\s[^>]*>', doc, re.I):
        tag = m.group(0)
        href = re.search(r'href=["\']([^"\']+)', tag)
        if not href or re.search(r'rel=["\'][^"\']*nofollow', tag, re.I):
            continue
        if href.group(1).startswith(('mailto:', 'tel:', 'javascript:', '#')):
            continue
        out.append(norm(href.group(1)))
    return out


def sitemap_urls(url, seen=None):
    seen = seen if seen is not None else set()
    if url in seen:
        return []
    seen.add(url)
    status, _, doc = fetch(url)
    if status != 200:
        return [('!', url, status)]
    locs = [html.unescape(x) for x in re.findall(r'<loc>\s*([^<]+?)\s*</loc>', doc)]
    if '<sitemapindex' in doc:
        return [u for loc in locs for u in sitemap_urls(loc, seen)]
    return [('url', loc, url) for loc in locs]


def check_page(url):
    status, headers, doc = fetch(url)
    issues = []
    if status != 200:
        issues.append(f'status {status}' + (f" -> {headers.get('Location', '')}" if 300 <= status < 400 else ''))
        return issues, '', [], doc
    robots = robots_directives(headers, doc)
    if re.search(r'noindex|none', robots, re.I):
        issues.append(f'noindex ({robots})')
    canon = canonicals(doc)
    if len(canon) != 1:
        issues.append(f'{len(canon)} canonical tags')
    for c in canon:
        if not c.startswith(('http://', 'https://')):
            issues.append(f'relative canonical {c}')
        elif not internal(c):
            issues.append(f'canonical to another host {c}')
        elif norm(c) != url:
            cs, ch, _ = fetch(c)
            issues.append(f'canonical points elsewhere {c} ({cs})')
    return issues, robots, canon, doc


def main():
    report, problems = {}, 0

    # robots.txt
    status, _, txt = fetch(BASE + '/robots.txt')
    rp = urllib.robotparser.RobotFileParser()
    rp.parse(txt.splitlines())
    blocked = [p for p in PATHS + ['/wp-content/themes/kingdom-revival/assets/css/site.css', '/wp-content/uploads/'] if not rp.can_fetch('Googlebot', BASE + p)]
    has_sitemap = bool(re.search(r'^sitemap:', txt, re.I | re.M))
    disallow_all = bool(re.search(r'^disallow:\s*/\s*$', txt, re.I | re.M))
    print(f'robots.txt {status}: blocked important paths {blocked or "none"}; Sitemap line {"yes" if has_sitemap else "NO"}; Disallow: / {"YES" if disallow_all else "no"}')
    problems += bool(blocked) + (not has_sitemap) + disallow_all + (status != 200)
    report['robots_txt'] = {'status': status, 'body': txt, 'blocked': blocked}

    # Important pages.
    print('\nImportant pages')
    report['pages'] = []
    for path in PATHS:
        url = BASE + path
        issues, robots, canon, _ = check_page(url)
        problems += bool(issues)
        report['pages'].append({'path': path, 'issues': issues, 'robots': robots, 'canonical': canon})
        print(f"{'OK ' if not issues else 'BAD'} {path:44} robots: {robots[:40]:40} | {', '.join(issues)}")

    # Intentional noindex pages stay noindex.
    print('\nIntentionally noindex')
    for path in INTENTIONAL_NOINDEX:
        status, headers, doc = fetch(BASE + path)
        robots = robots_directives(headers, doc)
        print(f"    {path:44} {status} robots: {robots}")

    # Sitemaps.
    print('\nSitemaps')
    # The sitemaps robots.txt announces (core: /wp-sitemap.xml, Yoast and Rank Math: /sitemap_index.xml).
    roots = re.findall(r'^sitemap:\s*(\S+)', txt, re.I | re.M) or [BASE + '/wp-sitemap.xml']
    print('from robots.txt:', ', '.join(roots))
    seen = set()
    entries = [e for root in roots for e in sitemap_urls(root, seen)]
    in_map = set()
    report['sitemap'] = []
    for kind, loc, src in entries:
        if kind == '!':
            print(f'BAD sitemap {loc} returned {src}')
            problems += 1
            continue
        in_map.add(loc)
        issues, _, _, _ = check_page(loc)
        problems += bool(issues)
        report['sitemap'].append({'url': loc, 'sitemap': src, 'issues': issues})
        if issues:
            print(f"BAD {loc.replace(BASE, ''):44} in {src.replace(BASE, '')}: {', '.join(issues)}")
    missing = [p for p in PATHS if BASE + p not in in_map and p not in NOT_IN_SITEMAP]
    problems += len(missing)
    print(f'{len(in_map)} URLs listed, {sum(1 for r in report["sitemap"] if r["issues"])} not indexable; important pages not listed: {missing or "none"}')
    report['sitemap_missing'] = missing

    # Crawl from the home page by plain links.
    print('\nCrawl from /')
    depth, queue, graph = {BASE + '/': 0}, deque([BASE + '/']), {}
    while queue and len(graph) < CRAWL_LIMIT:
        url = queue.popleft()
        status, _, doc = fetch(url)
        if status != 200 or '<html' not in doc[:2000].lower():
            continue
        out = [u for u in links(doc) if internal(u) and not re.search(r'/wp-(admin|login|json)|\.(jpe?g|png|webp|gif|svg|pdf|mp4)$|[?&](s|replytocom)=', u)]
        graph[url] = sorted(set(out))
        for u in out:
            if u not in depth:
                depth[u] = depth[url] + 1
                queue.append(u)
    unreachable = [p for p in PATHS if BASE + p not in depth]
    deep = {p: depth[BASE + p] for p in PATHS if BASE + p in depth and depth[BASE + p] > 3}
    # Reported, not counted: adding links changes the approved design, so they are proposed for approval instead.
    print(f'{len(graph)} pages crawled; important pages not reachable by links (found through the sitemap only): {unreachable or "none"}; deeper than 3 clicks: {deep or "none"}')
    report['crawl'] = {'depth': {u.replace(BASE, ''): d for u, d in depth.items()}, 'links': {u.replace(BASE, ''): [x.replace(BASE, '') for x in v] for u, v in graph.items()}}

    print(f'\n{problems} problems')
    if '--json' in sys.argv:
        json.dump(report, open(sys.argv[sys.argv.index('--json') + 1], 'w'), indent=1)
    return 1 if problems else 0


if __name__ == '__main__':
    sys.exit(main())
