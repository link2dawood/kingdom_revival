import sys
from PIL import Image, ImageChops
# usage: firstdiff.py dir name width [crop_height]
d, name, w = sys.argv[1:4]
ch = int(sys.argv[4]) if len(sys.argv) > 4 else 500
a = Image.open(f"{d}/{name}-{w}-html.png").convert("RGB")
b = Image.open(f"{d}/{name}-{w}-wp.png").convert("RGB")
W, H = min(a.width, b.width), min(a.height, b.height)
diff = ImageChops.difference(a.crop((0, 0, W, H)), b.crop((0, 0, W, H))).convert("L").point(lambda v: 255 if v > 24 else 0)
first = None
for y in range(H):
    if diff.crop((0, y, W, y + 1)).getbbox():
        first = y
        break
print(name, w, "html", a.size, "wp", b.size, "first diff row:", first)
if first is not None:
    y0 = max(0, first - 120)
    out = Image.new("RGB", (W * 2 + 10, ch), "red")
    out.paste(a.crop((0, y0, W, y0 + ch)), (0, 0))
    out.paste(b.crop((0, y0, W, y0 + ch)), (W + 10, 0))
    out.save(f"{d}/{name}-{w}-first.png")
    bbox = diff.crop((0, first, W, min(H, first + 40))).getbbox()
    print("bbox of first band (x0,y0,x1,y1 rel):", bbox)
