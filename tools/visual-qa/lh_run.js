// Lighthouse performance runs for the Phase 15 step 6 pages: RUNS runs per page and device, the median per metric,
// raw reports kept. Mobile is Lighthouse's default (Moto G Power, simulated slow 4G, 4× CPU); desktop is its desktop
// preset. INP needs real visitors (field data), so the lab report gives TBT as its stand-in.
// usage: node lh_run.js <label> [base URL]     env: LIGHTHOUSE (CLI path, default: lighthouse on PATH), RUNS (3),
//        DEVICES (mobile,desktop), PAGES (comma-separated paths), CHROME_PATH
// Writes out/lh/<label>/<page>-<device>-<n>.json and out/lh/<label>/summary.json, and prints a table.
const { execFileSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const LABEL = process.argv[2] || 'run';
const BASE = (process.argv[3] || 'http://127.0.0.1:8900').replace(/\/$/, '');
const LH = process.env.LIGHTHOUSE || 'lighthouse';
const RUNS = +(process.env.RUNS || 3);
const DEVICES = (process.env.DEVICES || 'mobile,desktop').split(',');
const PAGES = process.env.PAGES ? process.env.PAGES.split(',').map((p) => [p.replace(/\W+/g, '-').replace(/^-|-$/g, '') || 'home', p])
  : [['home', '/'], ['all-ministries', '/ministries/'], ['ministry', '/ministries/brick-kiln-children/'], ['blog', '/blog/'],
    ['article', '/learning-beside-the-kilns/'], ['contact', '/contact/'], ['donate', '/donate/']];
const OUT = path.join(__dirname, 'out', 'lh', LABEL);
fs.mkdirSync(OUT, { recursive: true });

const METRICS = {
  score: (r) => Math.round(r.categories.performance.score * 100),
  fcp: (r) => r.audits['first-contentful-paint'].numericValue,
  lcp: (r) => r.audits['largest-contentful-paint'].numericValue,
  tbt: (r) => r.audits['total-blocking-time'].numericValue,
  cls: (r) => r.audits['cumulative-layout-shift'].numericValue,
  si: (r) => r.audits['speed-index'].numericValue,
  bytes: (r) => r.audits['total-byte-weight'].numericValue,
  jsBoot: (r) => r.audits['bootup-time'].numericValue,
  mainThread: (r) => r.audits['mainthread-work-breakdown'].numericValue,
  requests: (r) => r.audits['network-requests'].details.items.length,
};
const median = (a) => { const s = [...a].sort((x, y) => x - y); return s[Math.floor(s.length / 2)]; };

const summary = {};
for (const [name, p] of PAGES) {
  for (const device of DEVICES) {
    const runs = [];
    for (let n = 1; n <= RUNS; n++) {
      const file = path.join(OUT, `${name}-${device}-${n}.json`);
      const args = [BASE + p, '--output=json', `--output-path=${file}`, '--only-categories=performance', '--quiet',
        `--chrome-flags=--headless=new --no-sandbox`];
      if (device === 'desktop') args.push('--preset=desktop');
      execFileSync(LH, args, { stdio: 'inherit', env: { ...process.env, CHROME_PATH: process.env.CHROME_PATH || '/usr/bin/google-chrome' } });
      const r = JSON.parse(fs.readFileSync(file, 'utf8'));
      runs.push(Object.fromEntries(Object.entries(METRICS).map(([k, f]) => [k, f(r)])));
      // The LCP element and the biggest opportunities, from the first run.
      if (n === 1) {
        const lcpEl = r.audits['largest-contentful-paint-element']?.details?.items?.[0]?.items?.[0]?.node?.snippet || '';
        const opps = Object.values(r.audits).filter((a) => a.details?.type === 'opportunity' && a.numericValue > 50)
          .sort((a, b) => b.numericValue - a.numericValue).slice(0, 4).map((a) => `${a.id} ${Math.round(a.numericValue)}ms`);
        runs.meta = { lcpEl: lcpEl.slice(0, 160), opps };
      }
    }
    const med = Object.fromEntries(Object.keys(METRICS).map((k) => [k, median(runs.map((x) => x[k]))]));
    summary[`${name}/${device}`] = { ...med, scores: runs.map((x) => x.score), ...runs.meta };
    console.log(`${name.padEnd(15)} ${device.padEnd(8)} score ${String(med.score).padStart(3)} (${runs.map((x) => x.score).join('/')})  FCP ${(med.fcp / 1000).toFixed(2)}s  LCP ${(med.lcp / 1000).toFixed(2)}s  TBT ${Math.round(med.tbt)}ms  CLS ${med.cls.toFixed(3)}  SI ${(med.si / 1000).toFixed(2)}s  ${Math.round(med.bytes / 1024)} KB  JS ${Math.round(med.jsBoot)}ms  ${med.requests} req`);
  }
}
fs.writeFileSync(path.join(OUT, 'summary.json'), JSON.stringify(summary, null, 2));
console.log('saved', path.join(OUT, 'summary.json'));
