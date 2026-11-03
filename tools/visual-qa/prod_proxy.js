// A production-like front for a local WordPress (Phase 15 step 6): what the live host's LiteSpeed + LSCache do for
// a logged-out visitor, so PageSpeed numbers measure the theme rather than PHP's development server.
//  - page cache: the first GET of a page goes to WordPress, later ones are answered from memory
//  - Brotli for text (HTML, CSS, JS, SVG, JSON), as LiteSpeed sends it
//  - static files get "Cache-Control: public, max-age=31536000" (the readme's .htaccess rules)
// It never caches POSTs, admin pages or responses that set cookies. Restart it to empty the cache.
// usage: node prod_proxy.js [listen port] [WordPress origin]   (defaults 8900, http://127.0.0.1:8899)
// The origin's URLs in the HTML are rewritten to the proxy's, so the page loads everything through it.
const http = require('http');
const zlib = require('zlib');

const PORT = +(process.argv[2] || 8900);
const ORIGIN = new URL(process.argv[3] || 'http://127.0.0.1:8899');
const SELF = `http://127.0.0.1:${PORT}`;
const cache = new Map();
const TEXT = /^(text\/|application\/(javascript|json|xml)|image\/svg)/;
const STATIC = /\.(css|js|woff2|png|jpe?g|webp|avif|gif|svg|mp4|ico)$/i;

function fetchOrigin(req, body) {
  return new Promise((resolve, reject) => {
    const headers = { ...req.headers, host: ORIGIN.host, 'accept-encoding': 'identity' };
    const r = http.request({ host: ORIGIN.hostname, port: ORIGIN.port, method: req.method, path: req.url, headers }, (res) => {
      const chunks = [];
      res.on('data', (c) => chunks.push(c));
      res.on('end', () => resolve({ status: res.statusCode, headers: res.headers, body: Buffer.concat(chunks) }));
    });
    r.on('error', reject);
    r.end(body);
  });
}

function send(req, res, entry) {
  const headers = { ...entry.headers };
  delete headers['content-length'];
  delete headers['transfer-encoding'];
  delete headers.connection;
  let body = entry.body;
  const type = String(headers['content-type'] || '');
  if (TEXT.test(type) && /\bbr\b/.test(req.headers['accept-encoding'] || '') && body.length > 512) {
    body = entry.br || (entry.br = zlib.brotliCompressSync(body, { params: { [zlib.constants.BROTLI_PARAM_QUALITY]: 5 } }));
    headers['content-encoding'] = 'br';
    headers.vary = 'Accept-Encoding';
  }
  headers['content-length'] = body.length;
  res.writeHead(entry.status, headers);
  res.end(req.method === 'HEAD' ? undefined : body);
}

http.createServer((req, res) => {
  const chunks = [];
  req.on('data', (c) => chunks.push(c));
  req.on('end', async () => {
    try {
      const path = req.url.split('?')[0];
      const cacheable = req.method === 'GET' && !/^\/wp-(admin|login|json)|admin-ajax|wp-cron/.test(path) && !/wordpress_logged_in/.test(req.headers.cookie || '');
      if (cacheable && cache.has(req.url)) return send(req, res, cache.get(req.url));
      const entry = await fetchOrigin(req, Buffer.concat(chunks));
      const type = String(entry.headers['content-type'] || '');
      if (/text\/html/.test(type)) {
        entry.body = Buffer.from(entry.body.toString('utf8').split(ORIGIN.origin).join(SELF).split(ORIGIN.origin.replace(/\//g, '\\/')).join(SELF.replace(/\//g, '\\/')));
      }
      if (entry.headers.location) entry.headers.location = entry.headers.location.split(ORIGIN.origin).join(SELF);
      if (STATIC.test(path) && entry.status === 200) entry.headers['cache-control'] = 'public, max-age=31536000';
      if (cacheable && entry.status === 200 && !entry.headers['set-cookie']) cache.set(req.url, entry);
      send(req, res, entry);
    } catch (e) {
      res.writeHead(502);
      res.end(String(e));
    }
  });
}).listen(PORT, '127.0.0.1', () => console.log(`production-like proxy on ${SELF} -> ${ORIGIN.origin}`));
