// Pixel-diff the WordPress screenshots of two compare.js runs (e.g. before and after a theme change).
// usage: node wpdiff.js <outdirA> <outdirB>
// Reports the share of differing pixels (pixelmatch threshold 0.1, as compare.js) and, for those, how large the
// colour difference is: maxDelta / p99Delta are the largest per-channel differences (0–255).
const fs = require('fs');
const path = require('path');
const { PNG } = require('pngjs');
const pixelmatch = require('pixelmatch');

const [a, b] = process.argv.slice(2);
for (const f of fs.readdirSync(a).filter((f) => f.endsWith('-wp.png')).sort()) {
  if (!fs.existsSync(path.join(b, f))) { console.log(JSON.stringify({ shot: f, missing: true })); continue; }
  const A = PNG.sync.read(fs.readFileSync(path.join(a, f)));
  const B = PNG.sync.read(fs.readFileSync(path.join(b, f)));
  const width = Math.min(A.width, B.width), height = Math.min(A.height, B.height);
  const crop = (img) => { const c = new PNG({ width, height }); PNG.bitblt(img, c, 0, 0, width, height, 0, 0); return c; };
  const ca = crop(A), cb = crop(B);
  const n = pixelmatch(ca.data, cb.data, null, width, height, { threshold: 0.1 });
  const deltas = [];
  let identical = 0;
  for (let i = 0; i < ca.data.length; i += 4) {
    const d = Math.max(Math.abs(ca.data[i] - cb.data[i]), Math.abs(ca.data[i + 1] - cb.data[i + 1]), Math.abs(ca.data[i + 2] - cb.data[i + 2]));
    if (d === 0) identical++; else deltas.push(d);
  }
  deltas.sort((x, y) => x - y);
  console.log(JSON.stringify({
    shot: f.replace('-wp.png', ''), sameHeight: A.height === B.height, diffPct: +(100 * n / (width * height)).toFixed(3),
    identicalPct: +(100 * identical / (width * height)).toFixed(2),
    maxDelta: deltas.length ? deltas[deltas.length - 1] : 0, p99Delta: deltas.length ? deltas[Math.floor(deltas.length * 0.99)] : 0,
  }));
}
