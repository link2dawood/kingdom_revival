import sys
from PIL import Image, ImageChops
d, name, w = sys.argv[1:4]
a=Image.open(f'{d}/{name}-{w}-html.png').convert('RGB'); b=Image.open(f'{d}/{name}-{w}-wp.png').convert('RGB')
W,H=min(a.width,b.width),min(a.height,b.height)
dd=ImageChops.difference(a.crop((0,0,W,H)),b.crop((0,0,W,H))).convert('L').point(lambda v:255 if v>24 else 0)
bands=[];cur=None
for y in range(H):
    bb=dd.crop((0,y,W,y+1)).getbbox()
    if bb:
        if cur and y-cur[1]<=6: cur[1]=y; cur[2]=min(cur[2],bb[0]); cur[3]=max(cur[3],bb[2])
        else:
            if cur: bands.append(cur)
            cur=[y,y,bb[0],bb[2]]
if cur: bands.append(cur)
print(name,w,len(bands),'bands (y0,y1,x0,x1):',[tuple(x) for x in bands][:15])
