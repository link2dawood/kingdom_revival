const fs = require('fs');
const path = require('path');
const CACHE = path.join(__dirname, 'cache');
fs.mkdirSync(CACHE, { recursive: true });
const inflight = new Map();

// Serve remote files (site uploads, Google Fonts) from a disk cache so both sides get the same bytes, fast.
async function cached(url, reqHeaders = {}) {
  const key = require('crypto').createHash('sha1').update(url).digest('hex');
  const file = path.join(CACHE, key);
  if (fs.existsSync(file + '.json')) {
    return { meta: JSON.parse(fs.readFileSync(file + '.json', 'utf8')), body: fs.readFileSync(file) };
  }
  if (!inflight.has(url)) {
    inflight.set(url, (async () => {
      for (let attempt = 0; attempt < 3; attempt++) {
        try {
          const res = await fetch(url, { signal: AbortSignal.timeout(90000), headers: { 'user-agent': reqHeaders['user-agent'] || '', accept: reqHeaders.accept || '*/*' } });
          const body = Buffer.from(await res.arrayBuffer());
          const meta = { status: res.status, type: res.headers.get('content-type') || 'application/octet-stream' };
          if (res.status === 200) {
            fs.writeFileSync(file, body);
            fs.writeFileSync(file + '.json', JSON.stringify(meta));
          }
          return { meta, body };
        } catch (e) {
          if (attempt === 2) return { meta: { status: 504, type: 'text/plain' }, body: Buffer.from('') };
        }
      }
    })());
  }
  return inflight.get(url);
}


async function prepare(ctx) {
  await ctx.addInitScript(() => {
    try { localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() })); } catch (e) {}
  });
  await ctx.route(/^https?:\/\/(?!127\.0\.0\.1)/, async (route) => {
    const url = route.request().url();
    if (/\.mp4(\?|$)/.test(url)) return route.abort();
    const { meta, body } = await cached(url, route.request().headers());
    return route.fulfill({ status: meta.status, contentType: meta.type, body, headers: { 'access-control-allow-origin': '*' } });
  });
}
module.exports = { cached, prepare };
