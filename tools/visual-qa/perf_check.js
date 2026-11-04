// Page-load performance (Phase 13): Core Web Vitals-style lab metrics per page, median of RUNS loads.
//   mobile:  390x844, 4x CPU slowdown, Lighthouse's "Slow 4G" (150 ms RTT, 1.6 Mbps down, 750 kbps up)
//   desktop: 1440x900, no throttling
// Reports TTFB, FCP, LCP, CLS, TBT (sum of long-task time over 50 ms, from FCP to load), requests and bytes
// transferred up to the load event, and how many requests left the site. No request interception: every file comes
// from where the page really asks for it. COMPARE=1 also measures the original HTML page (its images come from the
// live site over the internet, so its numbers include that).
// usage: node perf_check.js [outfile.json]      env: WP_URL, HTML_URL, CHROME_PATH, RUNS (default 3), COMPARE
const { chromium } = require('playwright-core');
const fs = require('fs');

const HTML = process.env.HTML_URL || 'http://127.0.0.1:8898';
const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const OUT = process.argv[2] || 'out/perf.json';
const RUNS = +(process.env.RUNS || 3);

const PAGES = [
  ['home', '/index.html', '/'],
  ['about', '/about.html', '/about/'],
  ['contact', '/contact.html', '/contact/'],
  ['donate', '/donate.html', '/donate/'],
  ['partner', '/partner.html', '/partner-with-us/'],
  ['blog', '/blog.html', '/news/'],
  ['post', '/blog-post.html?post=learning-beside-the-kilns', '/news/learning-beside-the-kilns/'],
  ['ministry', '/all-ministries.html?ministry=brick-kiln-children', '/what-we-do/brick-kiln-family-care/'],
];
const PROFILES = {
  mobile: { viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true, deviceScaleFactor: 2, cpu: 4,
    net: { offline: false, latency: 150, downloadThroughput: (1.6 * 1024 * 1024) / 8, uploadThroughput: (750 * 1024) / 8 } },
  desktop: { viewport: { width: 1440, height: 900 }, deviceScaleFactor: 1, cpu: 1, net: null },
};

async function measure(browser, url, prof) {
  const { cpu, net, ...ctxOpts } = prof;
  const ctx = await browser.newContext(ctxOpts);
  await ctx.addInitScript(() => {
    try { localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() })); } catch (e) {}
    window.__perf = { lcp: 0, cls: 0, long: [] };
    new PerformanceObserver((l) => { for (const e of l.getEntries()) window.__perf.lcp = e.startTime; }).observe({ type: 'largest-contentful-paint', buffered: true });
    new PerformanceObserver((l) => { for (const e of l.getEntries()) if (!e.hadRecentInput) window.__perf.cls += e.value; }).observe({ type: 'layout-shift', buffered: true });
    new PerformanceObserver((l) => { for (const e of l.getEntries()) window.__perf.long.push([e.startTime, e.duration]); }).observe({ type: 'longtask', buffered: true });
  });
  const page = await ctx.newPage();
  const cdp = await ctx.newCDPSession(page);
  await cdp.send('Network.enable');
  await cdp.send('Network.setCacheDisabled', { cacheDisabled: true });
  if (net) await cdp.send('Network.emulateNetworkConditions', net);
  if (cpu > 1) await cdp.send('Emulation.setCPUThrottlingRate', { rate: cpu });
  const host = new URL(url).host;
  const reqs = new Map();
  let loaded = false;
  cdp.on('Network.requestWillBeSent', (e) => { if (!loaded && !reqs.has(e.requestId)) reqs.set(e.requestId, { url: e.request.url, type: e.type, bytes: 0 }); });
  cdp.on('Network.loadingFinished', (e) => { const r = reqs.get(e.requestId); if (r) r.bytes = e.encodedDataLength; });
  await page.goto(url, { waitUntil: 'load', timeout: 120000 });
  loaded = true;
  await page.waitForTimeout(3000); // let LCP/CLS settle
  const m = await page.evaluate(() => {
    const nav = performance.getEntriesByType('navigation')[0];
    const fcp = (performance.getEntriesByName('first-contentful-paint')[0] || {}).startTime || 0;
    const tbt = window.__perf.long.filter(([s]) => s >= fcp && s <= nav.loadEventEnd).reduce((t, [, d]) => t + Math.max(0, d - 50), 0);
    return { ttfb: nav.responseStart, fcp, lcp: window.__perf.lcp, cls: window.__perf.cls, tbt, load: nav.loadEventEnd };
  });
  const list = [...reqs.values()].filter((r) => !r.url.startsWith('data:'));
  m.requests = list.length;
  m.kb = list.reduce((t, r) => t + r.bytes, 0) / 1024;
  m.external = list.filter((r) => { try { return new URL(r.url).host !== host; } catch (e) { return false; } }).length;
  m.byType = {};
  for (const r of list) m.byType[r.type] = (m.byType[r.type] || 0) + r.bytes / 1024;
  await ctx.close();
  return m;
}

const median = (xs) => { const s = [...xs].sort((a, b) => a - b); return s[Math.floor(s.length / 2)]; };

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const results = [];
  for (const [name, htmlPath, wpPath] of PAGES) {
    for (const [pname, prof] of Object.entries(PROFILES)) {
      const sides = [['wp', WP + wpPath]];
      if (process.env.COMPARE) sides.push(['html', HTML + htmlPath]);
      for (const [side, url] of sides) {
        const runs = [];
        for (let i = 0; i < RUNS; i++) {
          try { runs.push(await measure(browser, url, prof)); } catch (e) { runs.push({ error: String(e.message || e).split('\n')[0] }); }
        }
        const ok = runs.filter((r) => !r.error);
        const row = { page: name, profile: pname, side, runs: ok.length };
        if (ok.length) {
          for (const k of ['ttfb', 'fcp', 'lcp', 'tbt', 'load', 'requests', 'external']) row[k] = Math.round(median(ok.map((r) => r[k])));
          row.cls = +median(ok.map((r) => r.cls)).toFixed(3);
          row.kb = Math.round(median(ok.map((r) => r.kb)));
          const types = ok[0].byType;
          row.kbByType = Object.fromEntries(Object.entries(types).map(([t, v]) => [t, Math.round(v)]));
        } else row.error = runs[0].error;
        results.push(row);
        const { kbByType, ...line } = row;
        console.log(JSON.stringify(line));
      }
    }
  }
  fs.writeFileSync(OUT, JSON.stringify(results, null, 2));
  await browser.close();
})();
