"""Build inc/data/focal.php: where the faces are in each photo, so cropped photos (object-fit: cover) keep them in view.

Dev-time only (like tools/map). Scans the theme's assets/images and a WordPress uploads folder, finds faces with
OpenCV's YuNet detector and writes, per photo key (file name without size/-scaled/-eNNN suffixes, lower case), the
focus point as percentages "x y", plus the shape (width / height, EXIF rotation applied) of the theme's own photos.

    python3 -m venv venv && venv/bin/pip install opencv-python-headless numpy
    curl -L -o yunet.onnx https://github.com/opencv/opencv_zoo/raw/main/models/face_detection_yunet/face_detection_yunet_2023mar.onnx
    venv/bin/python tools/focal/build_focal.py theme/kingdom-revival ~/krm-work/site/wp-content/uploads yunet.onnx
"""
import os
import re
import sys

import cv2

EXT = ('.jpg', '.jpeg', '.png', '.webp')


def key_of(name):
    stem = os.path.splitext(os.path.basename(name))[0].lower()
    stem = re.sub(r'-\d+x\d+$', '', stem)
    stem = re.sub(r'-e\d{10,}$', '', stem)
    stem = re.sub(r'-(scaled|rotated)$', '', stem)
    return re.sub(r'-e\d{10,}$', '', stem)


def is_size_copy(name):
    return re.search(r'-\d+x\d+\.[a-z]+$', name.lower()) is not None


def focus(det, path):
    img = cv2.imread(path, cv2.IMREAD_COLOR)  # applies EXIF orientation
    if img is None:
        return None, None
    h0, w0 = img.shape[:2]
    scale = min(1.0, 1280.0 / max(w0, h0))
    img = cv2.resize(img, (round(w0 * scale), round(h0 * scale))) if scale < 1 else img
    h, w = img.shape[:2]
    det.setInputSize((w, h))
    _, faces = det.detect(img)
    ratio = round(w0 / h0, 4)
    if faces is None:
        return None, ratio
    boxes = [(f[0], f[1], f[2], f[3]) for f in faces if f[14] >= 0.75 and min(f[2], f[3]) >= 0.025 * min(w, h)]
    if not boxes:
        return None, ratio
    big = max(b[2] * b[3] for b in boxes)
    main = [b for b in boxes if b[2] * b[3] >= 0.2 * big]  # the main people, not faces far in the background
    x1 = min(b[0] for b in main); y1 = min(b[1] for b in main)
    x2 = max(b[0] + b[2] for b in main); y2 = max(b[1] + b[3] for b in main)
    fx = max(0, min(100, round((x1 + x2) / 2 / w * 100)))
    fy = max(0, min(100, round((y1 + y2) / 2 / h * 100)))
    return (fx, fy), ratio


def main():
    theme, uploads, model = sys.argv[1:4]
    det = cv2.FaceDetectorYN.create(model, '', (320, 320), 0.75, 0.3, 5000)
    focal, ratios = {}, {}
    files = []
    for root, _, names in os.walk(os.path.join(theme, 'assets', 'images')):
        files += [(os.path.join(root, n), True) for n in names if n.lower().endswith(EXT)]
    for root, _, names in os.walk(uploads):
        files += [(os.path.join(root, n), False) for n in names if n.lower().endswith(EXT) and not is_size_copy(n)]
    for path, in_theme in sorted(files):
        point, ratio = focus(det, path)
        if in_theme and ratio:
            ratios[os.path.relpath(path, os.path.join(theme, 'assets')).replace(os.sep, '/')] = ratio
        if point:
            focal.setdefault(key_of(path), point)
    # Hand-checked corrections (theme 1.27.0): "key x y" lines in overrides.txt beside this script, for photos where the
    # detector misses the faces or picks background faces. They win over detection.
    over = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'overrides.txt')
    if os.path.exists(over):
        for line in open(over, encoding='utf-8'):
            parts = line.split('#')[0].split()
            if len(parts) == 3:
                focal[parts[0]] = (int(parts[1]), int(parts[2]))
            elif len(parts) == 2 and parts[1] == '-':  # "key -": no focus point (the template positions it by hand)
                focal.pop(parts[0], None)
    out = os.path.join(theme, 'inc', 'data', 'focal.php')
    with open(out, 'w', encoding='utf-8') as f:
        f.write("<?php\n/**\n * Built by tools/focal/build_focal.py (do not edit by hand): 'focal' = where the faces are in a photo, as\n"
                " * \"x% y%\" for object-position, by photo key (krm_focal_key()); 'ratio' = width / height of the theme's own photos.\n"
                " *\n * @package Kingdom_Revival\n */\n\ndefined( 'ABSPATH' ) || exit;\n\nreturn array(\n\t'focal' => array(\n")
        for k in sorted(focal):
            f.write("\t\t'%s' => '%d%% %d%%',\n" % (k.replace("'", "\\'"), focal[k][0], focal[k][1]))
        f.write("\t),\n\t'ratio' => array(\n")
        for k in sorted(ratios):
            f.write("\t\t'%s' => %s,\n" % (k.replace("'", "\\'"), ratios[k]))
        f.write("\t),\n);\n")
    print('photos', len(files), 'with faces', len(focal), 'theme ratios', len(ratios), '->', out)


main()
