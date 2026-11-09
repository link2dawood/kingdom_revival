#!/usr/bin/env python3
"""Build theme/kingdom-revival/assets/images/pakistan-map.svg (dev time only; the site loads no map library or tiles).

Source: geoBoundaries gbOpen PAK ADM1, simplified release (public domain), e.g.
  https://github.com/wmgeolab/geoBoundaries/raw/9469f09/releaseData/gbOpen/PAK/ADM1/geoBoundaries-PAK-ADM1_simplified.geojson

Usage: python3 tools/map/build_pakistan_map.py path/to/geoBoundaries-PAK-ADM1_simplified.geojson

Projection: equirectangular, longitudes scaled by cos(30.5 deg) (the map's middle latitude), 1000 units wide.
Lines are simplified (Douglas-Peucker) and rounded to whole units. Prints each city's position as % of the
map's width / height: copy those into KRM_WHERE_CITIES in template-parts/home/where.php.
"""
import json
import math
import sys

HIGHLIGHT = {"Punjab"}  # Provinces named on the site.
CITIES = {  # Cities named on the site (lat, lon).
    "Faisalabad": (31.4504, 73.1350),
    "Jhang": (31.2681, 72.3181),
    "Chiniot": (31.7200, 72.9789),
    "Toba Tek Singh": (30.9709, 72.4826),
}
WIDTH = 1000
PAD = 6
TOLERANCE = 1.1  # map units


def rings(geom):
    polys = geom["coordinates"] if geom["type"] == "MultiPolygon" else [geom["coordinates"]]
    for poly in polys:
        for ring in poly:
            yield ring


def dp(points, tol):
    if len(points) < 3:
        return points
    ax, ay = points[0]
    bx, by = points[-1]
    dx, dy = bx - ax, by - ay
    norm = math.hypot(dx, dy) or 1e-9
    best, idx = 0.0, 0
    for i in range(1, len(points) - 1):
        px, py = points[i]
        d = abs(dy * px - dx * py + bx * ay - by * ax) / norm if (dx or dy) else math.hypot(px - ax, py - ay)
        if d > best:
            best, idx = d, i
    if best <= tol:
        return [points[0], points[-1]]
    return dp(points[: idx + 1], tol)[:-1] + dp(points[idx:], tol)


def main(src):
    data = json.load(open(src, encoding="utf-8"))
    feats = data["features"]
    kx = math.cos(math.radians(30.5))
    lons = [p[0] for f in feats for r in rings(f["geometry"]) for p in r]
    lats = [p[1] for f in feats for r in rings(f["geometry"]) for p in r]
    lon0, lat1 = min(lons), max(lats)
    span = (max(lons) - lon0) * kx
    s = (WIDTH - 2 * PAD) / span
    height = round((lat1 - min(lats)) * s + 2 * PAD)

    def proj(lon, lat):
        return (PAD + (lon - lon0) * kx * s, PAD + (lat1 - lat) * s)

    def path(geom):
        out = []
        for ring in rings(geom):
            pts = dp([proj(*p) for p in ring], TOLERANCE)
            pts = [(round(x), round(y)) for x, y in pts]
            clean = [pts[0]] + [p for i, p in enumerate(pts[1:], 1) if p != pts[i - 1]]
            if len(clean) < 4:
                continue
            out.append("M" + "L".join("%d %d" % p for p in clean[:-1]) + "Z")
        return "".join(out)

    lines, fills = [], []
    for f in sorted(feats, key=lambda f: f["properties"]["shapeName"]):
        name = f["properties"]["shapeName"]
        d = path(f["geometry"])
        lines.append(d)
        if name in HIGHLIGHT:
            fills.append('<path class="pk-hl" data-province="%s" d="%s"/>' % (name, d))
    svg = (
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d" aria-hidden="true" focusable="false">'
        "<!-- Pakistan, provinces and territories: geoBoundaries gbOpen PAK ADM1 (public domain). "
        "Built by tools/map/build_pakistan_map.py. -->"
        '<g class="pk-fills">%s</g><path class="pk-lines" d="%s"/></svg>\n'
    ) % (WIDTH, height, "".join(fills), "".join(lines))
    out = "theme/kingdom-revival/assets/images/pakistan-map.svg"
    open(out, "w", encoding="utf-8").write(svg)
    print("wrote %s: viewBox 0 0 %d %d, %d bytes" % (out, WIDTH, height, len(svg)))
    for name, (lat, lon) in CITIES.items():
        x, y = proj(lon, lat)
        print("'%s' => array( %.2f, %.2f )," % (name, x / WIDTH * 100, y / height * 100))


if __name__ == "__main__":
    main(sys.argv[1])
