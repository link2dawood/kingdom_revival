// Content audit: for every page in the Phase 1 inventory, compare the original HTML page with its WordPress page:
// visible text, heading outline, links (HTML links mapped to the expected WordPress URLs), images, buttons, and
// the number of headers/footers. Then request every internal link found on the WordPress pages.
// usage: node audit.js [outfile.json]
const { chromium } = require('playwright-core');
const fs = require('fs');
const { prepare } = require('./lib');

const HTML = process.env.HTML_URL || 'http://127.0.0.1:8898';
const WP = process.env.WP_URL || 'http://127.0.0.1:8899';

const MINISTRIES = ['children-ministry', 'orphan-and-children-care', 'brick-kiln-children', 'widows-ministry', 'freedom-from-slavery', 'free-medical-camps', 'clean-water-project', 'outreach-ministry', 'urdu-bibles', 'baptisms', 'deliverance-and-healing', 'leadership-and-discipleship'];
const POSTS = ['gods-word-in-every-home', 'learning-beside-the-kilns', 'new-life-in-the-water', 'a-home-for-the-orphaned', 'standing-with-widows', 'raising-up-shepherds', 'prayer-for-the-sick-and-oppressed'];

const PAGES = [
  ['home', '/index.html', '/'],
  ['about', '/about.html', '/about/'],
  ['contact', '/contact.html', '/contact/'],
  ['donate', '/donate.html', '/donate/'],
  ['partner', '/partner.html', '/partner/'],
  ['blog', '/blog.html', '/blog/'],
  ['ministries (no slug)', '/all-ministries.html', '/ministries/'],
  ...MINISTRIES.map((s) => [`ministry: ${s}`, `/all-ministries.html?ministry=${s}`, `/ministries/${s}/`]),
  ...POSTS.map((s) => [`post: ${s}`, `/blog-post.html?post=${s}`, `/${s}/`]),
];

// Where a link in the HTML design should point in WordPress.
function mapHtmlUrl(href) {
  if (!href.startsWith(HTML)) return href;
  const u = new URL(href);
  const file = u.pathname.replace(/^\//, '');
  const q = u.searchParams;
  let p;
  if (file === '' || file === 'index.html') p = '/';
  else if (file === 'blog-post.html' && q.get('post')) { p = `/${q.get('post')}/`; q.delete('post'); }
  else if (file === 'all-ministries.html' && q.get('ministry')) { p = `/ministries/${q.get('ministry')}/`; q.delete('ministry'); }
  else if (file === 'all-ministries.html') p = '/ministries/';
  else if (file === 'blog.html') p = '/blog/';
  else p = '/' + file.replace(/\.html$/, '') + '/';
  const qs = q.toString();
  return WP + p + (qs ? '?' + qs : '') + u.hash;
}

async function extract(page) {
  return page.evaluate(() => {
    const clean = (s) => (s || '').replace(/\s+/g, ' ').trim();
    const visible = (el) => !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
    const body = document.body.cloneNode(true);
    body.querySelectorAll('script, style, template, noscript').forEach((n) => n.remove());
    return {
      title: document.title,
      text: document.body.innerText.split('\n').map(clean).filter(Boolean),
      headings: [...document.querySelectorAll('h1,h2,h3,h4,h5,h6')].filter(visible).map((h) => h.tagName + ' ' + clean(h.textContent)),
      links: [...document.querySelectorAll('a[href]')].map((a) => ({ href: a.href, text: clean(a.textContent) || a.getAttribute('aria-label') || '' })),
      images: [...document.querySelectorAll('img')].map((i) => ({ src: i.currentSrc || i.src, alt: i.alt })),
      buttons: [...document.querySelectorAll('button, input[type=submit]')].map((b) => clean(b.textContent || b.value) || b.getAttribute('aria-label') || ''),
      ids: [...document.querySelectorAll('[id]')].map((e) => e.id),
      counts: {
        header: document.querySelectorAll('header').length,
        footer: document.querySelectorAll('footer').length,
        mainNav: document.querySelectorAll('nav[aria-label="Main"]').length,
        topbar: document.querySelectorAll('.topbar').length + [...document.querySelectorAll('div')].filter((d) => /Donate Now/.test(d.textContent) && d.children.length === 2 && d.parentElement && d.parentElement.style.background).length,
        h1: document.querySelectorAll('h1').length,
        main: document.querySelectorAll('main').length,
      },
    };
  });
}

function seqDiff(a, b) {
  // Lines in one list but not the other, keeping order context (LCS-based).
  const n = a.length, m = b.length;
  const dp = Array.from({ length: n + 1 }, () => new Int32Array(m + 1));
  for (let i = n - 1; i >= 0; i--) for (let j = m - 1; j >= 0; j--) dp[i][j] = a[i] === b[j] ? dp[i + 1][j + 1] + 1 : Math.max(dp[i + 1][j], dp[i][j + 1]);
  const out = [];
  let i = 0, j = 0;
  while (i < n && j < m) {
    if (a[i] === b[j]) { i++; j++; } else if (dp[i + 1][j] >= dp[i][j + 1]) out.push('- ' + a[i++]); else out.push('+ ' + b[j++]);
  }
  while (i < n) out.push('- ' + a[i++]);
  while (j < m) out.push('+ ' + b[j++]);
  return out;
}

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const report = [];
  const internal = new Set();
  for (const [name, htmlPath, wpPath] of PAGES) {
    const data = {};
    for (const [side, url] of [['html', HTML + htmlPath], ['wp', WP + wpPath]]) {
      const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
      await prepare(ctx);
      const page = await ctx.newPage();
      const res = await page.goto(url, { waitUntil: 'load', timeout: 90000 });
      await page.waitForTimeout(800);
      data[side] = await extract(page);
      data[side].status = res.status();
      await ctx.close();
    }
    const h = data.html, w = data.wp;
    const mappedLinks = h.links.map((l) => mapHtmlUrl(l.href) + ' | ' + l.text);
    const wpLinks = w.links.map((l) => l.href + ' | ' + l.text);
    w.links.forEach((l) => { if (l.href.startsWith(WP)) internal.add(l.href.split('#')[0]); });
    const r = {
      page: name,
      wpStatus: w.status,
      title: [h.title, w.title],
      text: seqDiff(h.text, w.text),
      headings: seqDiff(h.headings, w.headings),
      links: seqDiff(mappedLinks, wpLinks),
      images: seqDiff(h.images.map((i) => i.src + ' | ' + i.alt), w.images.map((i) => i.src + ' | ' + i.alt)),
      buttons: seqDiff(h.buttons, w.buttons),
      missingIds: h.ids.filter((id) => !w.ids.includes(id) && !/^dc-|^krm-/.test(id)),
      htmlLinksLeft: w.links.filter((l) => /\.html(\?|#|$)/.test(l.href)).map((l) => l.href),
      counts: [h.counts, w.counts],
    };
    report.push(r);
    const issues = ['text', 'headings', 'links', 'images', 'buttons'].map((k) => `${k}:${r[k].length}`).join(' ');
    console.log(`${String(w.status).padEnd(4)} ${name.padEnd(42)} ${issues} missingIds:${r.missingIds.length} .htmlLinks:${r.htmlLinksLeft.length} header/footer/nav/h1/main wp=${Object.values(w.counts).join('/')} html=${Object.values(h.counts).join('/')}`);
  }
  // Crawl every internal link found on the WordPress pages.
  const broken = [];
  for (const url of internal) {
    const res = await fetch(url, { redirect: 'manual' });
    if (res.status !== 200) broken.push(`${res.status} ${url} ${res.headers.get('location') || ''}`);
  }
  console.log(`internal links checked: ${internal.size}; not 200: ${broken.length}`);
  broken.forEach((b) => console.log('  ' + b));
  fs.writeFileSync(process.argv[2] || 'out/audit.json', JSON.stringify({ report, internal: [...internal], broken }, null, 1));
  await browser.close();
})();
