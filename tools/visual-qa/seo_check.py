#!/usr/bin/env python3
"""SEO tag check for the WordPress site: every indexable page must have exactly one title, description,
canonical, og:title, og:description, og:image, og:url and twitter:card, and nothing printed twice.

usage: python3 seo_check.py [base_url] [--json out.json]
"""
import html
import json
import re
import sys
import urllib.request

BASE = sys.argv[1] if len(sys.argv) > 1 and not sys.argv[1].startswith('--') else 'http://127.0.0.1:8899'
MINISTRIES = ['children-ministry', 'orphan-and-children-care', 'brick-kiln-children', 'widows-ministry', 'freedom-from-slavery', 'free-medical-camps', 'clean-water-project', 'outreach-ministry', 'urdu-bibles', 'baptisms', 'deliverance-and-healing', 'leadership-and-discipleship']
POSTS = ['gods-word-in-every-home', 'learning-beside-the-kilns', 'new-life-in-the-water', 'a-home-for-the-orphaned', 'standing-with-widows', 'raising-up-shepherds', 'prayer-for-the-sick-and-oppressed']
PATHS = ['/', '/about/', '/contact/', '/donate/', '/partner/', '/blog/', '/ministries/'] + \
    [f'/ministries/{m}/' for m in MINISTRIES] + [f'/{p}/' for p in POSTS] + \
    ['/sermons/', '/events/', '/testimonials/', '/category/children/']
REQUIRED = ['title', 'description', 'canonical', 'og:title', 'og:description', 'og:image', 'og:url', 'twitter:card']


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *args, **kwargs):
        return None


def fetch(url):
    opener = urllib.request.build_opener(NoRedirect)
    try:
        with opener.open(url, timeout=60) as r:
            return r.status, r.read().decode('utf-8', 'replace')
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode('utf-8', 'replace')


def tags(doc):
    head = doc.split('</head>')[0]
    found = {}

    def add(name, value):
        found.setdefault(name, []).append(html.unescape(value).strip())
    for m in re.finditer(r'<title[^>]*>(.*?)</title>', head, re.S):
        add('title', m.group(1))
    for m in re.finditer(r'<meta\s+[^>]*>', head):
        t = m.group(0)
        key = re.search(r'(?:name|property)=["\']([^"\']+)["\']', t)
        val = re.search(r'content=["\']([^"\']*)["\']', t)
        if key and val and re.match(r'^(description|robots|og:|twitter:|article:)', key.group(1)):
            add(key.group(1), val.group(1))
    for m in re.finditer(r'<link\s+[^>]*rel=["\']canonical["\'][^>]*>', head):
        add('canonical', re.search(r'href=["\']([^"\']+)', m.group(0)).group(1))
    ld = re.findall(r'<script[^>]+application/ld\+json[^>]*>(.*?)</script>', doc, re.S)
    found['_jsonld'] = len(ld)
    found['_theme_jsonld'] = sum(1 for x in ld if '"@type":"NGO"' in x)
    return found


def main():
    report, problems = [], 0
    for path in PATHS:
        status, doc = fetch(BASE + path)
        t = tags(doc)
        issues = []
        if status != 200:
            issues.append(f'status {status}')
        for k in REQUIRED:
            n = len(t.get(k, []))
            if n == 0:
                issues.append(f'missing {k}')
        for k, v in t.items():
            if not k.startswith('_') and isinstance(v, list) and len(v) > 1 and k not in ('og:image', 'article:tag', 'og:image:alt'):
                issues.append(f'{k} x{len(v)}')
        if t['_theme_jsonld'] and t['_jsonld'] > t['_theme_jsonld']:
            issues.append('theme JSON-LD next to plugin JSON-LD')
        robots = ' '.join(t.get('robots', []))
        problems += bool(issues)
        row = {
            'path': path, 'status': status, 'issues': issues,
            'title': (t.get('title') or [''])[0], 'description': (t.get('description') or [''])[0],
            'canonical': (t.get('canonical') or [''])[0], 'og:image': (t.get('og:image') or [''])[0],
            'robots': robots, 'jsonld': t['_jsonld'],
        }
        report.append(row)
        print(f"{'OK ' if not issues else 'BAD'} {path:44} {row['title'][:60]:60} | desc {len(row['description']):3} | {', '.join(issues)}")
    print(f'{len(PATHS)} pages, {problems} with problems')
    if '--json' in sys.argv:
        json.dump(report, open(sys.argv[sys.argv.index('--json') + 1], 'w'), indent=1)
    return 1 if problems else 0


if __name__ == '__main__':
    sys.exit(main())
