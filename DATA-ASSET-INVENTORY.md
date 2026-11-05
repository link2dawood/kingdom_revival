# Kingdom Revival Ministries: data folder asset inventory

**Result:** every one of the 243 files in `Kingdom Revival ministries-Data_/` was opened and checked. All 81 photos and the one video on the website came from this folder (renamed and re-saved by the old Elementor site), from 76 of its files, and all are already in the Media Library through Kingdom Revival → Setup, with no duplicates. 151 photos and 15 videos are not used yet. Four ministry folders are empty and no written content was supplied. **Theme 1.7.4 (Phase 14 Task 7) imported what you approved** (decisions 1, 2, 3, 5 and 6, see [Imported in theme 1.7.4](#imported-in-theme-174)): the upright Urdu Bible photo, a 12-photo Outreach gallery, a brick kiln photo on the home "Freedom from Slavery" card, a 512×512 search-engine logo and descriptive Media Library alt text. Decisions 7 and 8 are not acted on.

Generated 2026-11-02, updated for theme 1.7.4 the same day. Method at the end. Local test site: 85 design files imported (`krm_media_map`, attachment IDs below), plus the 13 new photos (IDs 185–197). The tables below describe the folder and the site **before** 1.7.4.

## Added in theme 1.15.1

| Theme file | Source in data folder | Where it shows |
|---|---|---|
| `team-adil-arif.jpg` | `Team members/Founder- Pastor Adil Arif.png` | About → Our staff (Pastor Adil Arif, Founder) |
| `team-sajid-arif.jpg` | `Team members/Co-founder- Sajid Arif_.png` | About → Our staff (Sajid Arif, Co-founder) |
| `team-aber-sarwar.jpg` | `Team members/Administrator- Aber Sarwar.png` | About → Our staff (Aber Sarwar, Administrator) |
| `team-samia-arif.jpg` | `Team members/International Relations Coordinator- Samia Arif_.png` | About → Our staff (Samia Arif, International Relations Coordinator) |
| `team-sehrish-gill.jpg` | `Team members/Projects Coordinator- Sehrish Gill.png` | About → Our staff (Sehrish Gill, Projects Coordinator) |
| `freedom-before-1.jpg` | `Freedom from slavery missions/Before... images/20251101_144704.jpg` | Freedom before/after slider, "Before" side (home and Slavery & Freedom page) |
| `freedom-after-1.jpg` | `Freedom from slavery missions/After... images/20251126_143324.jpg` | Freedom slider, "After" side |
| `freedom-before-2.jpg`, `-3.jpg` | `Before... images/20250919_113635.jpg`, `20250614_120026.jpg` (portrait) | "More families, before and after" row under the slider |
| `freedom-after-2.jpg` … `-4.jpg` | `After... images/20251126_130035.jpg`, `20251111_162458.jpg`, `20251111_162838.jpg` | Same row |

Names and roles come from the team file names. Team PNGs (1254² round portraits on white) were cropped to the circle
(2% inset, so no white rim shows in the round frame) and saved as 600² JPEG q82 (39–54 KB each). Before/after photos:
EXIF orientation applied, metadata stripped, 1600 px long side, JPEG q74. The folder does not say which before and
after photos show the same family, so the slider pair is the two landscape photos; the two portrait "before" photos
would lose most of the family in the wide slider frame, so they are in the row below.

## Added in theme 1.14.0

| Theme file | Source in data folder | Where it shows |
|---|---|---|
| `church-planting-1.jpg` … `-4.jpg` | `General pictures_/20250919_170238, 173409, 174044, 174100.jpg` | Church Planting gallery (was empty) |
| `church-planting-5.jpg` | `General pictures_/IMG_20250418_181835.jpg` | Church Planting gallery |
| `church-planting-6.jpg` … `-9.jpg` | `General pictures_/IMG_2511, IMG_2512, IMG_4302, IMG_4303.HEIC` | Church Planting gallery, hero and intro photos |
| `widows-ministry-6.jpg`, `-7.jpg` | `Widows ministry/IMG_20240302_123923.jpg`, `IMG_20240302_123933.jpg` | Widows Support gallery (5 → 7) |

Same method as 1.7.4: EXIF orientation applied, metadata stripped, 1600 px long side, JPEG quality 74. Clean Water
Project and Free medical camp are still empty folders, and no other folder shows that work, so those two ministries
have no photos (visitors see no empty photo boxes). `Pastor pictures_/` holds one PNG, not used.

## Contents

- [Imported in theme 1.7.4](#imported-in-theme-174)
- [Summary](#summary)
- [Decisions for you](#decisions-for-you)
- [Missing assets and unresolved mappings](#missing-assets-and-unresolved-mappings)
- [Website images and their source files](#website-images-and-their-source-files)
- [Videos](#videos)
- [Written content](#written-content)
- [Full inventory of the data folder](#full-inventory-of-the-data-folder)
- [Method](#method)

## Imported in theme 1.7.4

New photos are shipped in the theme (`assets/images/content/`, 13 JPEGs, 3.2 MB: converted from HEIC where needed,
EXIF orientation applied and metadata stripped, 1600 px long side, quality 74). The theme refers to them as
`krm-theme:images/content/<file>`; Kingdom Revival → Setup copies them into the Media Library like the design's other
images, and until then pages use the theme's copy. Each matches its source with image-hash distance 0.

| Decision | Theme file / change | Source in data folder | Where it shows |
|---|---|---|---|
| 1. Sideways photo | `urdu-bible-distribution-12.jpg` (740×1600) replaces `Urdu-Bible-Distribution-12.jpg` | `Urdu Bibles Distribution ministry/20250301_180839.jpg` (upright once its EXIF orientation is applied; the old site's copy had lost the tag) | Urdu Bibles gallery, photo 12 (caption unchanged) |
| 2. Outreach gallery | `outreach-ministry-1.jpg` … `-12.jpg` | `Outreach Ministry/IMG_2463, IMG_2556, IMG_2606, IMG_2640, IMG_2598, IMG_2612, IMG_2617, IMG_2621, IMG_2626, IMG_2666, IMG_2669, IMG_2692.HEIC` (in that order) | Outreach Ministry gallery (was the General placeholder only), its card on the home explorer and /ministries/, featured image |
| 3. Freedom card | Home "Freedom from Slavery" cause card uses `Bricks-kiln-slaves-children-31-scaled.jpg` | `Bricks kiln slaves children_/20260225_154404.jpg` (already in the Media Library; children at a meal in a kiln courtyard) instead of the proposed `20251221_155850.jpg`, which would have been a new upload | Home page, causes section |
| 5. Logo | `assets/images/krm-logo-large.png` (512×512) | `Church logo/file_000000004fb48211ba27b99f0eed6cab.png` (1254×1254, scaled) | Structured-data `logo` only (Google's 112 px minimum). The header and footer keep the 99×99 `krm-logo.png`, so pages stay pixel-identical |
| 6. Alt text | `inc/data/media.php`: the proposed alt for every photo (table below) | — | Media Library alt (`alt` attributes of story covers, About and Home images, and the viewer caption there). Ministry galleries print their saved captions as visible text, so they keep the design's wording: putting the long alt there made ministry pages 25–44 % different and taller |

Existing sites: re-running Setup updates only what nobody edited: a ministry gallery that still shows only the old
placeholder gets the Outreach photos, the old sideways Urdu photo is swapped (caption kept), and Media Library alt
texts still set to the old one-phrase-per-ministry wording get the new text. Edited galleries and alts are left alone.

Not acted on: decision 4 (no photos exist), 7 (keep the old site's copies, as recommended), 8 (more photos and videos:
you have not chosen which). The 8 Outreach HEIC photos not used and its 2 videos remain candidates for decision 8.

## Summary

- **Files:** 243 (702.5 MB): 161 JPEG, 63 HEIC (iPhone photos), 3 PNG, 16 MP4 videos. No documents, text files or fonts. The only logo is the church logo.
- **Exact duplicates:** none (SHA-1 of every file).
- **Website photos traced to a source file:** 81 of 81 (70 identical, 10 crops, 1 mirrored). The 3 payment logos (PayPal, Western Union, MoneyGram) are not in the folder, as expected.
- **Website video traced:** `a.mp4` (hero background and field-stories video) is `Vedios/lv_0_20251013121200.mp4`, re-encoded.
- **Founder photo** is `Pastor pictures_/file_00000000b46471f484befac1dc0ee291.png`, the very same file (same name on the old site).
- **Logo:** `Church logo/…6cab.png` is the same artwork as the theme logo, at 1254×1254 instead of 99×99 (white background).

| Folder | Files | Used on the site | Not used | Website section |
|---|---|---|---|---|
| `Church logo` | 1 | 1 | 0 | Site logo (header, footer, schema logo) |
| `Ministries data/ Clean Water Project` | 0 | 0 | 0 | Ministry: Clean Water Project **(empty folder)** |
| `Ministries data/Baptisms` | 37 | 11 | 26 | Ministry: Baptisms; story "New life in the water" |
| `Ministries data/Children ministry/Bricks kiln slaves children_` | 64 | 13 | 51 | Ministries: Children Ministry, Brick Kiln Children (and Freedom from Slavery, which reuses this gallery); story "Learning beside the kilns" |
| `Ministries data/Children ministry/Orphanages care` | 20 | 12 | 8 | Ministry: Orphan & Children Care; story "A home for the orphaned" |
| `Ministries data/Deliverance and Healing ministry` | 12 | 10 | 2 | Ministry: Deliverance & Healing; story "Prayer for the sick and oppressed" |
| `Ministries data/Free medical camp for bricks kiln slaves and village people_s_` | 0 | 0 | 0 | Ministry: Free Medical Camps **(empty folder)** |
| `Ministries data/Freedom from slavery missions` | 0 | 0 | 0 | Ministry: Freedom from Slavery **(empty folder)** |
| `Ministries data/General pictures_` | 25 | 1 | 24 | Site-wide: home hero, Partner background, placeholder for ministries without photos |
| `Ministries data/Leadership and Discipleship` | 22 | 8 | 14 | Ministry: Leadership & Discipleship; story "Raising up shepherds"; About hero |
| `Ministries data/Orphanage and underprivileged children care_` | 0 | 0 | 0 | Ministry: Orphan & Children Care **(empty folder)** |
| `Ministries data/Outreach Ministry` | 22 | 0 | 22 | Ministry: Outreach Ministry (currently has only the General placeholder) |
| `Ministries data/Urdu Bibles Distribution ministry` | 29 | 14 | 15 | Ministry: Urdu Bibles; story "God's Word in every home" |
| `Ministries data/Vedios` | 3 | 1 | 2 | Home: hero background video and field-stories video |
| `Ministries data/Widows ministry` | 7 | 5 | 2 | Ministry: Widows Ministry; story "Standing with widows" |
| `Pastor pictures_` | 1 | 1 | 0 | About + Home: founder photo |

## Decisions for you

Each of these changes what an approved page shows. Status after theme 1.7.4: 1, 2, 3, 5, 6 done (see above);
4 waits for photos; 7 not done (as recommended); 8 still open.

1. **Fix the sideways photo.** `Urdu-Bible-Distribution-12.jpg` (Urdu Bibles gallery) shows people lying on their side, on the live site and in the source file `20250301_180839.jpg`. Proposal: import it rotated 90° so it stands upright.
2. **Outreach Ministry gallery.** It shows only the General placeholder photo. The `Outreach Ministry` folder has 20 photos (HEIC) of food and supplies (blue and green bags) handed out to families at brick kilns, and 2 videos. Proposal: convert to JPEG, import, and use them as the Outreach gallery and card image.
3. **Home card "Freedom from Slavery"** uses a Deliverance & Healing photo (a pastor praying for a sick woman on a drip). Proposal: use a brick kiln photo instead, e.g. `Bricks kiln slaves children_/20251221_155850.jpg` (children at a kiln, chimney behind). The Freedom from Slavery page reuses the Brick Kiln Children gallery; there is no separate folder content (folder empty).
4. **Free Medical Camps and Clean Water Project** have no photos (folders empty, and no other folder shows a medical camp or a water project). They keep the General placeholder until you send photos.
5. **Higher-resolution logo.** Use the 1254×1254 church logo for the site logo and the schema logo (Google asks for at least 112 px, an open Phase 7 item). The header would look the same but its pixels change slightly.
6. **Descriptive alt text.** Current alt texts are one phrase per ministry ("Baptism in a local community" for all 11 baptism photos). Proposed alt text for each photo, written from what it shows, is in the table below. Alt text is not visible, so the pages stay pixel-identical.
7. **Full-quality originals.** The site uses the old site's re-saved copies (max 2560 px, some cropped or edited there). The originals are larger. Swapping them in would change crops (the old site's `-e…` edited versions are crops) and the mirrored home/Partner image would flip, so I recommend keeping the current copies and only importing new photos.
8. **More photos and videos for existing ministries.** 151 unused photos and 15 videos. Galleries currently show 5–12 photos each. Say if you want more photos added (which ones, or "up to N per ministry"), and which videos go on which ministry page.

## Missing assets and unresolved mappings

- **Empty folders:** `Clean Water Project`, `Free medical camp for bricks kiln slaves and village people_s_`, `Freedom from slavery missions`, `Orphanage and underprivileged children care_` (the orphan photos are in `Children ministry/Orphanages care`).
- **No photos at all for:** Free Medical Camps, Clean Water Project, Freedom from Slavery (shares Brick Kiln photos). Outreach has its own gallery since theme 1.7.4.
- **Not in the folder:** the PayPal, Western Union and MoneyGram logos (Donate page tabs), `assets/images/krm-logo.png` at its current size (only the large version), any written content, fonts, or poster images for videos.
- **HEIC photos (63):** cannot be shown by most browsers; WordPress converts them on upload only if the server's image library supports HEIC. They must be converted to JPEG first (I can do this, keeping the filenames).
- **Videos:** 7 of 16 are stored with their index at the end of the file ("faststart" off), so a browser must download most of the file before playing. They should be re-saved with faststart (and smaller) before use. `Leadership and Discipleship/.mp4` has no filename and is HEVC (H.265), which some browsers cannot play.
- **Ambiguous folder content:** `Leadership and Discipleship/IMG-20260622-WA0022.jpg` (used as Leadership gallery photo `Leadership-and-Discipleship-4-1`) shows bowls of food, not teaching. `Bricks kiln slaves children_/…` also holds clothes and blanket distribution photos.

## Website images and their source files

Every file the design uses. "Attachment" is the Media Library ID on the local test site (IDs differ per site; Setup recreates them).

| Website file | Source in data folder | Match | Used on | Attachment | Current alt | Proposed alt |
|---|---|---|---|---|---|---|
| `Baptisms-21-e1784293966685.jpg` | `Baptisms/20251006_172606.jpg` | identical image (hash distance 1) | Ministry Baptisms: gallery<br>Story new-life-in-the-water: photo in text | 78 | Baptism in a local community | Two men standing waist-deep in a baptism pool with their arms raised |
| `Baptisms-22.jpg` | `Baptisms/20251006_172724.jpg` | identical image (hash distance 0) | Ministry Baptisms: gallery | 79 | Baptism in a local community | A baptism pool of water scattered with rose petals |
| `Baptisms-23.jpg` | `Baptisms/IMG-20251010-WA0000.jpg` | identical image (hash distance 0) | Ministry Baptisms: gallery | 80 | Baptism in a local community | A newly baptised young man in a white shawl and rose garland |
| `Baptisms-24.jpg` | `Baptisms/IMG-20251010-WA0001.jpg` | identical image (hash distance 0) | Ministry Baptisms: gallery | 81 | Baptism in a local community | A newly baptised woman in an orange headscarf and rose garland |
| `Baptisms-25.jpg` | `Baptisms/IMG-20251010-WA0002.jpg` | identical image (hash distance 0) | Ministry Baptisms: gallery | 82 | Baptism in a local community | A newly baptised man in a rust-coloured shalwar kameez and rose garland |
| `Baptisms-26.jpg` | `Baptisms/IMG-20251010-WA0003.jpg` | identical image (hash distance 0) | Ministry Baptisms: gallery | 83 | Baptism in a local community | A newly baptised man in white raising both hands in praise |
| `Baptisms-27.jpg` | `Baptisms/IMG-20251010-WA0004.jpg` | identical image (hash distance 0) | Ministry Baptisms: gallery<br>Story new-life-in-the-water: gallery | 84 | Baptism in a local community | A newly baptised woman raising her hands, wearing a rose garland |
| `Baptisms-28.jpg` | `Baptisms/IMG-20251010-WA0005.jpg` | identical image (hash distance 0) | Ministry Baptisms: gallery<br>Story new-life-in-the-water: gallery | 85 | Baptism in a local community | A newly baptised woman in a white shawl and rose garland |
| `Baptisms-29.jpg` | `Baptisms/IMG-20251010-WA0006.jpg` | identical image (hash distance 0) | About page: image<br>Ministry Baptisms: card / explorer image<br>Ministry Baptisms: gallery<br>Partner page: image<br>Story new-life-in-the-water: cover | 86 | Baptism in a local community | A newly baptised woman in a pink and white shawl with a rose garland |
| `Baptisms-30.jpg` | `Baptisms/PhotoCollage_1760380082479.jpg` | identical image (hash distance 0) | Ministry Baptisms: gallery<br>Story new-life-in-the-water: gallery | 87 | Baptism in a local community | A pastor baptising a man, and the man afterwards wearing a rose garland |
| `Baptisms-31.jpg` | `Baptisms/PhotoCollage_1760380196318.jpg` | identical image (hash distance 0) | About page: image3<br>Ministry Baptisms: gallery<br>Story new-life-in-the-water: gallery | 88 | Baptism in a local community | A pastor baptising a young man, and the young man afterwards in a white shawl |
| `Urdu-Bible-Distribution-10-e1787225652778.jpg` | `Urdu Bibles Distribution ministry/20250301_180656.jpg` | crop (edited on the old site) | Ministry Urdu Bibles: gallery | 89 | Urdu Bible distribution | A pastor handing an Urdu Bible to a man at a brick kiln, beside a table of Bibles |
| `Urdu-Bible-Distribution-11-e1784536054126.jpg` | `Urdu Bibles Distribution ministry/20250301_180802.jpg` | identical image (hash distance 0) | Ministry Urdu Bibles: gallery | 90 | Urdu Bible distribution | A pastor and a woman holding her new Urdu Bible at a brick kiln |
| `Urdu-Bible-Distribution-12.jpg` | `Urdu Bibles Distribution ministry/20250301_180839.jpg` | identical image (hash distance 0) | Ministry Urdu Bibles: gallery | 91 | Urdu Bible distribution | A pastor giving an Urdu Bible to a woman and a young girl |
| `Urdu-Bible-Distribution-13-e1784536079985.jpg` | `Urdu Bibles Distribution ministry/20250301_180845.jpg` | identical image (hash distance 2) | Ministry Urdu Bibles: gallery<br>Story gods-word-in-every-home: gallery | 92 | Urdu Bible distribution | A pastor with a woman and a small girl holding a new Urdu Bible |
| `Urdu-Bible-Distribution-14-e1784536098255.jpg` | `Urdu Bibles Distribution ministry/20250301_180908.jpg` | identical image (hash distance 0) | Ministry Urdu Bibles: gallery<br>Story gods-word-in-every-home: gallery | 93 | Urdu Bible distribution | A pastor presenting an Urdu Bible to a woman in yellow |
| `Urdu-Bible-Distribution-15-e1784536109482.jpg` | `Urdu Bibles Distribution ministry/20250301_180929.jpg` | identical image (hash distance 0) | Ministry Urdu Bibles: gallery<br>Story gods-word-in-every-home: gallery | 94 | Urdu Bible distribution | A pastor with a woman holding her new Urdu Bible, a child beside them |
| `Urdu-Bible-Distribution-16-e1784536121378.jpg` | `Urdu Bibles Distribution ministry/20250301_181001.jpg` | identical image (hash distance 3) | Ministry Urdu Bibles: gallery<br>Story gods-word-in-every-home: photo in text | 95 | Urdu Bible distribution | A pastor and a woman holding an Urdu Bible together |
| `Urdu-Bible-Distribution-17-scaled.jpg` | `Urdu Bibles Distribution ministry/IMG_20230211_163619(1).jpg` | identical image (hash distance 0) | Contact page: image<br>Ministry Urdu Bibles: gallery<br>Story gods-word-in-every-home: gallery | 96 | Urdu Bible distribution | A pastor giving an Urdu Bible to an elderly man in a white cap |
| `Urdu-Bible-Distribution-18-scaled.jpg` | `Urdu Bibles Distribution ministry/IMG_20230211_163656(1).jpg` | identical image (hash distance 0) | About page: image<br>Donate page: image<br>Ministry Urdu Bibles: card / explorer image<br>Ministry Urdu Bibles: gallery<br>Story gods-word-in-every-home: cover | 97 | Urdu Bible distribution | A pastor handing an Urdu Bible to a young man outside a brick house |
| `Urdu-Bible-Distribution-7-e1784535997635.jpg` | `Urdu Bibles Distribution ministry/20250301_180440.jpg` | identical image (hash distance 2) | Ministry Urdu Bibles: gallery | 98 | Urdu Bible distribution | A pastor with a young girl holding her Urdu Bible at a brick kiln |
| `Urdu-Bible-Distribution-8-e1784536009842.jpg` | `Urdu Bibles Distribution ministry/20250301_180523.jpg` | identical image (hash distance 3) | Ministry Urdu Bibles: gallery | 99 | Urdu Bible distribution | A pastor giving an Urdu Bible to an elderly man beside stacks of Bibles |
| `Urdu-Bible-Distribution-9-e1784536026463.jpg` | `Urdu Bibles Distribution ministry/20250301_180524.jpg` | identical image (hash distance 2) | Ministry Urdu Bibles: gallery | 100 | Urdu Bible distribution | An elderly man receiving an Urdu Bible from a pastor at a brick kiln |
| `money-gram-1024x642.png` | **not in folder** |  | Donate page: payment method tab logo | 101 | MoneyGram |  |
| `paypal-logo-png_seeklogo-249214-300x300.png` | **not in folder** |  | Donate page: payment method tab logo | 102 | PayPal |  |
| `western-union-new-20232644.logowik.com_.webp` | **not in folder** |  | Donate page: payment method tab logo | 103 | Western Union |  |
| `Bricks-kiln-slaves-children-29-scaled-e1787822836311.jpg` | `Children ministry/Bricks kiln slaves children_/20260225_154013.jpg` | identical image (hash distance 0) | Ministry Brick Kiln Children: gallery<br>Ministry Children Ministry: gallery<br>Ministry Freedom from Slavery: gallery | 104 | Children and families in a brick kiln community | A small girl from a brick kiln community with a plate of rice and a juice box |
| `Bricks-kiln-slaves-children-30-scaled-e1787822825532.jpg` | `Children ministry/Bricks kiln slaves children_/20260225_154021.jpg` | identical image (hash distance 0) | Ministry Brick Kiln Children: gallery<br>Ministry Children Ministry: gallery<br>Ministry Freedom from Slavery: gallery | 105 | Children and families in a brick kiln community | A girl in a headscarf holding a plate of rice and a juice box |
| `Bricks-kiln-slaves-children-31-scaled.jpg` | `Children ministry/Bricks kiln slaves children_/20260225_154404.jpg` | identical image (hash distance 0) | Ministry Brick Kiln Children: gallery<br>Ministry Children Ministry: gallery<br>Ministry Freedom from Slavery: gallery | 106 | Children and families in a brick kiln community | Children sitting in a circle for a meal in a brick courtyard, a pastor behind them |
| `Bricks-kiln-slaves-children-32-scaled.jpg` | `Children ministry/Bricks kiln slaves children_/20260225_154416.jpg` | identical image (hash distance 1) | Ministry Brick Kiln Children: gallery<br>Ministry Children Ministry: gallery<br>Ministry Freedom from Slavery: gallery | 107 | Children and families in a brick kiln community | Children raising their hands during a meal gathering in a brick courtyard |
| `Bricks-kiln-slaves-children-33-scaled-e1787822814979.jpg` | `Children ministry/Bricks kiln slaves children_/20260225_154551.jpg` | identical image (hash distance 2) | Ministry Brick Kiln Children: gallery<br>Ministry Children Ministry: gallery<br>Ministry Freedom from Slavery: gallery | 108 | Children and families in a brick kiln community | A volunteer serving rice to young children sitting on a mat |
| `Bricks-kiln-slaves-children-34-scaled-e1787822803466.jpg` | `Children ministry/Bricks kiln slaves children_/20260225_154604.jpg` | identical image (hash distance 1) | Ministry Brick Kiln Children: gallery<br>Ministry Children Ministry: gallery<br>Ministry Freedom from Slavery: gallery | 109 | Children and families in a brick kiln community | A volunteer serving plates of rice to a group of children |
| `Bricks-kiln-slaves-children-35-scaled-e1787822792740.jpg` | `Children ministry/Bricks kiln slaves children_/20260225_154730.jpg` | identical image (hash distance 0) | Ministry Brick Kiln Children: gallery<br>Ministry Children Ministry: gallery<br>Ministry Freedom from Slavery: gallery<br>Story learning-beside-the-kilns: gallery | 110 | Children and families in a brick kiln community | A girl in a pink dress sitting with a plate of rice |
| `Bricks-kiln-slaves-children-36-scaled-e1787822780752.jpg` | `Children ministry/Bricks kiln slaves children_/20260225_154739.jpg` | identical image (hash distance 0) | Ministry Brick Kiln Children: gallery<br>Ministry Children Ministry: gallery<br>Ministry Freedom from Slavery: gallery<br>Story learning-beside-the-kilns: gallery | 111 | Children and families in a brick kiln community | Young children eating rice and drinking juice together on a mat |
| `Bricks-kiln-slaves-children-37-scaled-e1787822770768.jpg` | `Children ministry/Bricks kiln slaves children_/20260225_154743.jpg` | identical image (hash distance 2) | Ministry Brick Kiln Children: gallery<br>Ministry Children Ministry: gallery<br>Ministry Freedom from Slavery: gallery<br>Story learning-beside-the-kilns: gallery | 112 | Children and families in a brick kiln community | Children sharing a meal of rice on a mat |
| `Bricks-kiln-slaves-children-38-scaled.jpg` | `Children ministry/Bricks kiln slaves children_/20260225_154835.jpg` | identical image (hash distance 0) | Blog page: image<br>Ministry Brick Kiln Children: gallery<br>Ministry Children Ministry: gallery<br>Ministry Freedom from Slavery: card / explorer image<br>Ministry Freedom from Slavery: gallery<br>Site page: image<br>Story learning-beside-the-kilns: gallery<br>page.php<br>template-parts/page-hero.php | 113 | Children and families in a brick kiln community | Children seated around a long mat for a shared meal, a pot of food in the middle |
| `Bricks-kiln-slaves-children-39-scaled.jpg` | `Children ministry/Bricks kiln slaves children_/IMG_1251.JPG` | identical image (hash distance 0) | About page: image<br>Donate page: image<br>Ministry Brick Kiln Children: card / explorer image<br>Ministry Brick Kiln Children: gallery<br>Ministry Children Ministry: gallery<br>Ministry Freedom from Slavery: gallery<br>Story learning-beside-the-kilns: photo in text | 114 | Children and families in a brick kiln community | Children seated in front of piles of new clothes and blankets ready to be given out |
| `Bricks-kiln-slaves-children-40-scaled.jpg` | `Children ministry/Bricks kiln slaves children_/IMG_1359.JPG` | identical image (hash distance 0) | About page: image<br>Donate page: image<br>Ministry Brick Kiln Children: gallery<br>Ministry Children Ministry: card / explorer image<br>Ministry Children Ministry: gallery<br>Ministry Freedom from Slavery: gallery<br>Newsletter popup image<br>Story learning-beside-the-kilns: cover | 115 | Children and families in a brick kiln community | A pastor praying over a boy beside donated clothes |
| `Deliverance-and-Healing-ministry-1-scaled-e1787828396674.jpg` | `Deliverance and Healing ministry/20250904_165924.jpg` | identical image (hash distance 3) | Ministry Deliverance & Healing: gallery | 116 | Deliverance and healing ministry | A pastor praying for a sick woman lying on a bed with a drip |
| `Deliverance-and-Healing-ministry-10.jpg` | `Deliverance and Healing ministry/Screenshot_20260711_001316_Video Player.jpg` | identical image (hash distance 0) | About page: image<br>Ministry Deliverance & Healing: card / explorer image<br>Ministry Deliverance & Healing: gallery<br>Story prayer-for-the-sick-and-oppressed: cover | 117 | Deliverance and healing ministry | A pastor laying his hand on an elderly woman in a wheelchair |
| `Deliverance-and-Healing-ministry-2-scaled-e1787828386736.jpg` | `Deliverance and Healing ministry/20250904_171632.jpg` | identical image (hash distance 0) | Ministry Deliverance & Healing: gallery | 118 | Deliverance and healing ministry | A pastor praying over a sick man lying on a bed |
| `Deliverance-and-Healing-ministry-3-scaled-e1787828373981.jpg` | `Deliverance and Healing ministry/20250904_171637.jpg` | identical image (hash distance 0) | Ministry Deliverance & Healing: gallery | 119 | Deliverance and healing ministry | A pastor praying with his hand stretched over a man lying on a bed |
| `Deliverance-and-Healing-ministry-4-scaled-e1787828361817.jpg` | `Deliverance and Healing ministry/20251115_170305.jpg` | identical image (hash distance 0) | Ministry Deliverance & Healing: gallery | 120 | Deliverance and healing ministry | A pastor praying for a man with his hand on the man's stomach |
| `Deliverance-and-Healing-ministry-5-scaled-e1787828305580.jpg` | `Deliverance and Healing ministry/20251115_170309.jpg` | identical image (hash distance 0) | Ministry Deliverance & Healing: gallery<br>Story prayer-for-the-sick-and-oppressed: gallery | 121 | Deliverance and healing ministry | A pastor laying hands on a man in prayer outdoors |
| `Deliverance-and-Healing-ministry-6-scaled-e1787828407656.jpg` | `Deliverance and Healing ministry/20260308_131851.jpg` | identical image (hash distance 0) | Ministry Deliverance & Healing: gallery<br>Story prayer-for-the-sick-and-oppressed: gallery | 122 | Deliverance and healing ministry | A pastor sitting beside a sick woman lying on a bed, praying for her |
| `Deliverance-and-Healing-ministry-7-scaled.jpg` | `Deliverance and Healing ministry/IMG_1837.JPG` | identical image (hash distance 0) | Ministry Deliverance & Healing: gallery<br>Story prayer-for-the-sick-and-oppressed: gallery | 123 | Deliverance and healing ministry | A pastor praying over an elderly woman in an orange headscarf |
| `Deliverance-and-Healing-ministry-8-scaled.jpg` | `Deliverance and Healing ministry/IMG_1887.JPG` | identical image (hash distance 0) | Ministry Deliverance & Healing: gallery<br>Story prayer-for-the-sick-and-oppressed: gallery | 124 | Deliverance and healing ministry | A pastor praying for a woman lying on a bed in a brick courtyard |
| `Deliverance-and-Healing-ministry-9.jpg` | `Deliverance and Healing ministry/Screenshot_20260711_001248_Video Player.jpg` | identical image (hash distance 0) | Ministry Deliverance & Healing: gallery<br>Story prayer-for-the-sick-and-oppressed: photo in text | 125 | Deliverance and healing ministry | A pastor praying over an elderly woman in a wheelchair |
| `Leadership-and-Discipleship-10-scaled.jpg` | `Leadership and Discipleship/IMG-20260625-WA0009.jpg` | identical image (hash distance 0) | Ministry Leadership & Discipleship: gallery<br>Story raising-up-shepherds: photo in text | 126 | Leadership and discipleship gathering | A full hall of men, women and children at a church gathering |
| `Leadership-and-Discipleship-11-scaled.jpg` | `Leadership and Discipleship/IMG-20260625-WA0010.jpg` | identical image (hash distance 1) | About page: image<br>Ministry Leadership & Discipleship: card / explorer image<br>Ministry Leadership & Discipleship: gallery<br>Story raising-up-shepherds: cover | 127 | Leadership and discipleship gathering | A pastor speaking to a full hall from the front |
| `Leadership-and-Discipleship-4-1-scaled.jpg` | `Leadership and Discipleship/IMG-20260622-WA0022.jpg` | identical image (hash distance 0) | Ministry Leadership & Discipleship: gallery | 128 | Leadership and discipleship gathering | Dozens of bowls of food set out on the ground for a gathering |
| `Leadership-and-Discipleship-5-1-scaled.jpg` | `Leadership and Discipleship/IMG-20260622-WA0059.jpg` | identical image (hash distance 1) | Ministry Leadership & Discipleship: gallery | 129 | Leadership and discipleship gathering | A large congregation seated on blue mats in a church hall |
| `Leadership-and-Discipleship-6-1-scaled.jpg` | `Leadership and Discipleship/IMG-20260622-WA0060.jpg` | identical image (hash distance 0) | Ministry Leadership & Discipleship: gallery<br>Story raising-up-shepherds: gallery | 130 | Leadership and discipleship gathering | A pastor preaching with a microphone to a packed hall |
| `Leadership-and-Discipleship-7-1.jpg` | `Leadership and Discipleship/IMG-20260625-WA0001.jpg` | identical image (hash distance 0) | Ministry Leadership & Discipleship: gallery<br>Story raising-up-shepherds: gallery | 131 | Leadership and discipleship gathering | A speaker addressing a congregation seated on either side of a red aisle |
| `Leadership-and-Discipleship-8.jpg` | `Leadership and Discipleship/IMG-20260625-WA0002.jpg` | identical image (hash distance 0) | Ministry Leadership & Discipleship: gallery<br>Story raising-up-shepherds: gallery | 132 | Leadership and discipleship gathering | A speaker teaching a seated congregation in a church hall |
| `Leadership-and-Discipleship-9-scaled.jpg` | `Leadership and Discipleship/IMG-20260625-WA0008.jpg` | identical image (hash distance 0) | About page: image2<br>Ministry Leadership & Discipleship: gallery<br>Story raising-up-shepherds: gallery | 133 | Leadership and discipleship gathering | A pastor with a microphone leading children and adults at the front of a hall |
| `Orphanages-care-10-scaled-e1787818978151.jpg` | `Children ministry/Orphanages care/20251222_164155.jpg` | identical image (hash distance 1) | Ministry Orphan & Children Care: gallery | 134 | Children receiving care at an orphanage | Children sitting in two long rows for a meal in a courtyard |
| `Orphanages-care-11-scaled-e1787818995644.jpg` | `Children ministry/Orphanages care/20251222_164350.jpg` | identical image (hash distance 0) | Ministry Orphan & Children Care: gallery | 135 | Children receiving care at an orphanage | A volunteer serving rice from a large pot |
| `Orphanages-care-12-scaled-e1787819009333.jpg` | `Children ministry/Orphanages care/20251222_165526.jpg` | identical image (hash distance 0) | Ministry Orphan & Children Care: gallery | 136 | Children receiving care at an orphanage | A girl sitting with a plate of rice beside other children |
| `Orphanages-care-13-scaled-e1787819027587.jpg` | `Children ministry/Orphanages care/20251222_165545.jpg` | identical image (hash distance 1) | Ministry Orphan & Children Care: gallery | 137 | Children receiving care at an orphanage | Two girls with plates of rice and gift boxes |
| `Orphanages-care-14-scaled-e1787819044976.jpg` | `Children ministry/Orphanages care/20251222_165717.jpg` | identical image (hash distance 0) | Ministry Orphan & Children Care: gallery | 138 | Children receiving care at an orphanage | Long rows of children eating together in a courtyard |
| `Orphanages-care-15-scaled-e1787819059654.jpg` | `Children ministry/Orphanages care/20251222_170042.jpg` | identical image (hash distance 0) | Ministry Orphan & Children Care: gallery<br>Story a-home-for-the-orphaned: gallery | 139 | Children receiving care at an orphanage | Boys eating together with juice boxes |
| `Orphanages-care-16-scaled-e1787819076312.jpg` | `Children ministry/Orphanages care/20251222_170129.jpg` | identical image (hash distance 3) | About page: image1<br>Ministry Orphan & Children Care: gallery<br>Story a-home-for-the-orphaned: gallery | 140 | Children receiving care at an orphanage | Children seated in long rows for a meal, a pastor standing at the far end |
| `Orphanages-care-17-scaled-e1787819100112.jpg` | `Children ministry/Orphanages care/20251222_170239.jpg` | identical image (hash distance 0) | Ministry Orphan & Children Care: gallery<br>Story a-home-for-the-orphaned: gallery | 141 | Children receiving care at an orphanage | Long rows of children with gift boxes and meals in a courtyard |
| `Orphanages-care-18.jpg` | `Children ministry/Orphanages care/FB_IMG_1766909237683.jpg` | identical image (hash distance 0) | Ministry Orphan & Children Care: gallery<br>Story a-home-for-the-orphaned: gallery | 142 | Children receiving care at an orphanage | A young girl in green with her eyes closed and hands open in prayer |
| `Orphanages-care-19.jpg` | `Children ministry/Orphanages care/Screenshot_20251201_224038_Gallery.jpg` | identical image (hash distance 0) | Blog page: image<br>Ministry Orphan & Children Care: gallery<br>Site page: image<br>Story a-home-for-the-orphaned: photo in text | 143 | Children receiving care at an orphanage | A pastor kneeling with a large group of children in a courtyard |
| `Orphanages-care-20.jpg` | `Children ministry/Orphanages care/Screenshot_20251201_224208_Gallery.jpg` | identical image (hash distance 0) | About page: image<br>Donate page: image<br>Ministry Orphan & Children Care: card / explorer image<br>Ministry Orphan & Children Care: gallery<br>Partner page: image<br>Story a-home-for-the-orphaned: cover | 144 | Children receiving care at an orphanage | A large group of children cheering with their arms raised at a brick kiln |
| `Orphanages-care-9-scaled-e1787818960294.jpg` | `Children ministry/Orphanages care/20251222_163043.jpg` | identical image (hash distance 2) | Ministry Orphan & Children Care: gallery | 145 | Children receiving care at an orphanage | A pastor holding a smiling child and a gift box beside a pile of wrapped gifts |
| `Widows-ministry-1-scaled.jpg` | `Widows ministry/20251221_154631.jpg` | identical image (hash distance 2) | Ministry Widows Ministry: gallery<br>Story standing-with-widows: gallery | 146 | Widows ministry | A pastor with a widow and her children beside a sack of food supplies |
| `Widows-ministry-2-scaled.jpg` | `Widows ministry/IMG_20240302_122853.jpg` | identical image (hash distance 2) | Ministry Widows Ministry: gallery<br>Story standing-with-widows: gallery | 147 | Widows ministry | A pastor speaking to a group of women seated in a brick courtyard |
| `Widows-ministry-3-scaled.jpg` | `Widows ministry/IMG_20240302_122903.jpg` | identical image (hash distance 0) | Ministry Widows Ministry: gallery<br>Story standing-with-widows: gallery | 148 | Widows ministry | A pastor praying with a group of women seated in a courtyard |
| `Widows-ministry-4-scaled.jpg` | `Widows ministry/IMG_20240302_123849.jpg` | identical image (hash distance 1) | Ministry Widows Ministry: gallery<br>Story standing-with-widows: photo in text | 149 | Widows ministry | A pastor giving a gift to a widow |
| `Widows-ministry-5-scaled.jpg` | `Widows ministry/IMG_20240302_123909.jpg` | identical image (hash distance 0) | About page: image<br>Donate page: image<br>Ministry Widows Ministry: card / explorer image<br>Ministry Widows Ministry: gallery<br>Story standing-with-widows: cover | 150 | Widows ministry | A pastor handing supplies to a widow in a red shawl |
| `a.mp4` | `Vedios/lv_0_20251013121200.mp4` | same video, re-encoded 848x478 (frames matched in Chrome) | Home: hero background video + field-stories video | 151 | Kingdom Revival Ministries video |  |
| `file_00000000b46471f484befac1dc0ee291.png` | `Pastor pictures_/file_00000000b46471f484befac1dc0ee291.png` | identical image (hash distance 0) | About page: image<br>Home page: image | 152 | Pastor Adil Arif, founder of Kingdom Revival Ministries | (keep) |
| `Bible-Distribution-rqhhvb1bt4op61pyxtdlg5smtcfpsj957ob37u870` | `Urdu Bibles Distribution ministry/1783588870297.png` | crop | Home page: image | 153 | Bible distribution | A group of men at a brick kiln holding up their new Urdu Bibles |
| `Bricks-kiln-slaves-children-2-1-scaled-rsiliajqeyghqckx2003k` | `Children ministry/Bricks kiln slaves children_/20251221_155850.jpg` | crop | Home ministry card: Brick Kiln Children | 154 | Children from a brick kiln community | Children at a brick kiln raising their hands with a pastor, a kiln chimney behind |
| `Deliverance-and-Healing-ministry-5-scaled-e1787225773575-rs6` | `Deliverance and Healing ministry/20250904_165924.jpg` | crop | Home ministry card: Deliverance & Healing<br>Home ministry card: Freedom from Slavery<br>Home page: image | 155 | Deliverance and healing ministry | A pastor praying for a sick woman on a drip |
| `Deliverance-and-Healing-ministry-7-scaled-rsipx59tezwztxu5gn` | `Deliverance and Healing ministry/IMG_1837.JPG` | crop | Ministry pages (gallery) | 156 | Deliverance and Healing ministry | A pastor praying over an elderly woman |
| `General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbp` | `General pictures_/20251006_150450.jpg` | **mirrored** (flipped left-right) full frame | Default social share image (SEO)<br>Home: hero background + field-stories video poster<br>Ministry Clean Water Project: card / explorer image<br>Ministry Free Medical Camps: card / explorer image<br>Ministry Outreach Ministry: card / explorer image<br>Ministry Outreach Ministry: gallery<br>Partner page: image<br>Site page: hero_image | 157 | Community outreach gathering | A pastor waving to a large outdoor gathering of families at a brick kiln |
| `Leadership-and-Discipleship-9-scaled-rsiplqicgeaowcet38ec719` | `Leadership and Discipleship/IMG-20260625-WA0008.jpg` | crop | Home page: image1 | 158 | Leadership and discipleship ministry | A pastor with a microphone leading children and adults at the front of a hall |
| `Orphanages-care-16-scaled-e1787819076312-rsij8jj20ojpz9btf5v` | `Children ministry/Orphanages care/20251222_170129.jpg` | crop | Home ministry card: Orphanage & Children Care<br>Home page: image2 | 159 | Orphanage care ministry | Children seated in long rows for a meal, a pastor standing at the far end |
| `Urdu-Bible-Distribution-10-e1787225652778-rs6ku7xozhzlsfp1dn` | `Urdu Bibles Distribution ministry/20250301_180656.jpg` | crop | Home ministry card: Urdu Bible Distribution | 160 | Urdu Bible Distribution | A pastor handing an Urdu Bible to a man at a brick kiln |
| `Urdu-Bible-Distribution-6-e1787296463599-rs8073jb4tpz51knz3w` | `Urdu Bibles Distribution ministry/20250301_180428.jpg` | crop (checked by eye) | Home page: image | 161 | Urdu Bible distribution | A pastor with a girl holding her new Urdu Bible at a brick kiln |
| `Widows-ministry-1-scaled-rsinedytivoatdo3ffn47k3g4v35x8jaac6` | `Widows ministry/20251221_154631.jpg` | crop | Home ministry card: Widows Ministry | 162 | Widows ministry | A pastor with a widow and her children beside a sack of food supplies |

The ministry and story text in `inc/data/` was not changed. All 12 ministries and 7 stories keep their text and photos.

## Videos

The site shows one video, on the home page: muted, looping hero background (`aria-hidden`, no sound) and the same file in the field-stories player (`controls`, `playsinline`, `preload="metadata"`, poster image). Both are responsive (`width: 100%`). No supplied video has a poster image; one can be taken from a frame.

| Video | Size | Length, resolution | Codecs | Faststart | Use |
|---|---|---|---|---|---|
| `Baptisms/20250525_180748.mp4` | 14.4 MB | 6.0 s, 1920×1080 | avc1, mp4a | no | Not used. Candidate for a ministry page video (needs approval) |
| `Baptisms/20251006_171200.mp4` | 20.4 MB | 9.9 s, 1920×1080 | avc1, mp4a | no | Not used. Candidate for a ministry page video (needs approval) |
| `Baptisms/lv_7490830631769836861_20250527003834.mp4` | 8.2 MB | 8.5 s, 1080×1920 | avc1, mp4a | no | Not used. Candidate for a ministry page video (needs approval) |
| `Children ministry/Bricks kiln slaves children_/20251221_163511.mp4` | 8.8 MB | 4.2 s, 1920×1080 | avc1, mp4a | no | Not used. Candidate for a ministry page video (needs approval) |
| `Children ministry/Bricks kiln slaves children_/20251221_170055.mp4` | 14.6 MB | 7.0 s, 1920×1080 | avc1, mp4a | no | Not used. Candidate for a ministry page video (needs approval) |
| `Children ministry/Bricks kiln slaves children_/20251221_170140.mp4` | 29.0 MB | 14.1 s, 1920×1080 | avc1, mp4a | no | Not used. Candidate for a ministry page video (needs approval) |
| `Leadership and Discipleship/.mp4` | 0.7 MB | 8.1 s, 1080×1920 | mp4a, hvc1 | yes | Not used. Candidate for a ministry page video (needs approval) |
| `Leadership and Discipleship/VID-20260622-WA0057.mp4` | 3.0 MB | 13.1 s, 1280×720 | avc1, mp4a | yes | Not used. Candidate for a ministry page video (needs approval) |
| `Leadership and Discipleship/VID-20260623-WA0005.mp4` | 4.3 MB | 19.1 s, 1280×720 | avc1, mp4a | yes | Not used. Candidate for a ministry page video (needs approval) |
| `Leadership and Discipleship/VID-20260623-WA0006.mp4` | 5.1 MB | 22.3 s, 1280×720 | avc1, mp4a | yes | Not used. Candidate for a ministry page video (needs approval) |
| `Outreach Ministry/VID-20260301-WA0044.mp4` | 3.4 MB | 9.6 s, 1280×720 | avc1, mp4a | yes | Not used. Candidate for a ministry page video (needs approval) |
| `Outreach Ministry/VID-20260301-WA0163.mp4` | 13.6 MB | 39.8 s, 1280×720 | avc1, mp4a | yes | Not used. Candidate for a ministry page video (needs approval) |
| `Urdu Bibles Distribution ministry/20250301_175806.mp4` | 21.4 MB | 10.4 s, 1920×1080 | avc1, mp4a | no | Not used. Candidate for a ministry page video (needs approval) |
| `Vedios/GE-6whhM7je7HKMDADvReZA2y39zbmdjAAAF.mp4` | 11.5 MB | 46.9 s, 720×1280 | avc1, mp4a | yes | Not used. Candidate for a ministry page video (needs approval) |
| `Vedios/VID-20260712-WA0003.mp4` | 6.3 MB | 25.2 s, 1280×720 | avc1, mp4a | yes | Not used. Candidate for a ministry page video (needs approval) |
| `Vedios/lv_0_20251013121200.mp4` | 45.4 MB | 50.1 s, 1280×720 | avc1, mp4a | no | In use (re-encoded copy `a.mp4`, attachment 151) |

## Written content

None supplied: the folder has no documents or text files (only photos, videos and two PNG images). The approved page text, the 12 ministries (`inc/data/ministries.php`) and the 7 stories (`inc/data/posts.php`) are unchanged; there is nothing to compare them against. If you have written content (ministry descriptions, stories, statement of faith), send it and I will report differences before changing anything.

## Full inventory of the data folder

Sizes are after EXIF rotation. "Taken" is the camera date when the file has one.

### `Church logo`

| File | Type | Size | Dimensions | Taken | Status / destination |
|---|---|---|---|---|---|
| `file_000000004fb48211ba27b99f0eed6cab.png` | PNG | 1.1 MB | 1254×1254 |  | Same artwork as the theme logo `assets/images/krm-logo.png` (99×99). Higher-resolution replacement proposed (needs approval) |

### `Ministries data/ Clean Water Project`

Empty folder.

### `Ministries data/Baptisms`

| File | Type | Size | Dimensions | Taken | Status / destination |
|---|---|---|---|---|---|
| `20250525_175010.jpg` | JPG | 4.0 MB | 2296×4080 | 2025-05-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250525_175125.jpg` | JPG | 4.0 MB | 2296×4080 | 2025-05-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250525_175622.jpg` | JPG | 4.0 MB | 4080×2296 | 2025-05-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250525_175818.jpg` | JPG | 4.0 MB | 4080×2296 | 2025-05-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250525_180748.mp4` | MP4 | 14.4 MB | 1920×1080, 6.0 s |  | Not used. Candidate for a ministry page video (needs approval) |
| `20250525_180914.jpg` | JPG | 2.1 MB | 4080×1884 | 2025-05-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250525_181022.jpg` | JPG | 2.5 MB | 4080×1884 | 2025-05-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250525_181131.jpg` | JPG | 2.2 MB | 4080×1884 | 2025-05-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250525_181215.jpg` | JPG | 4.0 MB | 2296×4080 |  | Not used. Candidate for this folder's gallery (needs approval) |
| `20250525_181840.jpg` | JPG | 2.7 MB | 4080×1884 | 2025-05-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250525_181842.jpg` | JPG | 2.8 MB | 4080×1884 | 2025-05-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_170816.jpg` | JPG | 2.4 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_170828.jpg` | JPG | 3.1 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_171007.jpg` | JPG | 2.3 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_171024.jpg` | JPG | 2.7 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_171200.mp4` | MP4 | 20.4 MB | 1920×1080, 9.9 s |  | Not used. Candidate for a ministry page video (needs approval) |
| `20251006_171316.jpg` | JPG | 2.7 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_171356.jpg` | JPG | 2.8 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_171549.jpg` | JPG | 2.7 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_171634.jpg` | JPG | 3.1 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_171802.jpg` | JPG | 3.8 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_171806.jpg` | JPG | 2.7 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_172105.jpg` | JPG | 3.0 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_172508.jpg` | JPG | 3.1 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_172606.jpg` | JPG | 1.1 MB | 1440×2560 | 2025-10-06 | In use as `Baptisms-21-e1784293966685.jpg` (attachment 78) |
| `20251006_172724.jpg` | JPG | 1.4 MB | 2560×1440 | 2025-10-06 | In use as `Baptisms-22.jpg` (attachment 79) |
| `IMG-20251010-WA0000.jpg` | JPG | 0.3 MB | 900×1600 |  | In use as `Baptisms-23.jpg` (attachment 80) |
| `IMG-20251010-WA0001.jpg` | JPG | 0.3 MB | 900×1600 |  | In use as `Baptisms-24.jpg` (attachment 81) |
| `IMG-20251010-WA0002.jpg` | JPG | 0.3 MB | 900×1600 |  | In use as `Baptisms-25.jpg` (attachment 82) |
| `IMG-20251010-WA0003.jpg` | JPG | 0.2 MB | 900×1600 |  | In use as `Baptisms-26.jpg` (attachment 83) |
| `IMG-20251010-WA0004.jpg` | JPG | 0.3 MB | 900×1600 |  | In use as `Baptisms-27.jpg` (attachment 84) |
| `IMG-20251010-WA0005.jpg` | JPG | 0.3 MB | 900×1600 |  | In use as `Baptisms-28.jpg` (attachment 85) |
| `IMG-20251010-WA0006.jpg` | JPG | 0.3 MB | 900×1600 |  | In use as `Baptisms-29.jpg` (attachment 86) |
| `Messenger_creation_0135543B-C3FD-4A38-AE92-04AA49070E78.jpeg` | JPEG | 0.4 MB | 2048×2048 |  | Not used. Candidate for this folder's gallery (needs approval) |
| `PhotoCollage_1760380082479.jpg` | JPG | 2.3 MB | 2048×2048 | 2025-10-13 | In use as `Baptisms-30.jpg` (attachment 87) |
| `PhotoCollage_1760380196318.jpg` | JPG | 2.4 MB | 2048×2048 | 2025-10-13 | In use as `Baptisms-31.jpg` (attachment 88) |
| `lv_7490830631769836861_20250527003834.mp4` | MP4 | 8.2 MB | 1080×1920, 8.5 s |  | Not used. Candidate for a ministry page video (needs approval) |

### `Ministries data/Children ministry/Bricks kiln slaves children_`

| File | Type | Size | Dimensions | Taken | Status / destination |
|---|---|---|---|---|---|
| `20251221_155817.jpg` | JPG | 2.1 MB | 4080×2296 | 2025-12-21 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251221_155850.jpg` | JPG | 1.9 MB | 4080×2296 | 2025-12-21 | In use as `Bricks-kiln-slaves-children-2-1-scaled-rsiliajqeyghqckx2003kqpavypwam7blarw8gd3dk.jpg` (attachment 154) |
| `20251221_162417.jpg` | JPG | 2.2 MB | 2296×4080 | 2025-12-21 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251221_163511.mp4` | MP4 | 8.8 MB | 1920×1080, 4.2 s |  | Not used. Candidate for a ministry page video (needs approval) |
| `20251221_164713.jpg` | JPG | 1.6 MB | 2296×4080 | 2025-12-21 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251221_164812.jpg` | JPG | 2.1 MB | 4080×2296 | 2025-12-21 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251221_165039.jpg` | JPG | 1.7 MB | 2296×4080 | 2025-12-21 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251221_165252.jpg` | JPG | 2.3 MB | 4080×2296 | 2025-12-21 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251221_165349.jpg` | JPG | 2.4 MB | 4080×2296 | 2025-12-21 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251221_165506.jpg` | JPG | 2.5 MB | 4080×2296 | 2025-12-21 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251221_170002.jpg` | JPG | 3.0 MB | 4080×2296 | 2025-12-21 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251221_170019.jpg` | JPG | 3.2 MB | 4080×2296 | 2025-12-21 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251221_170055.mp4` | MP4 | 14.6 MB | 1920×1080, 7.0 s |  | Not used. Candidate for a ministry page video (needs approval) |
| `20251221_170140.mp4` | MP4 | 29.0 MB | 1920×1080, 14.1 s |  | Not used. Candidate for a ministry page video (needs approval) |
| `20260202_143903.jpg` | JPG | 2.5 MB | 2296×4080 | 2026-02-02 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260202_143955.jpg` | JPG | 2.0 MB | 2296×4080 | 2026-02-02 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260202_144702.jpg` | JPG | 1.8 MB | 2560×1440 | 2026-02-02 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260202_151107.jpg` | JPG | 2.7 MB | 2296×4080 | 2026-02-02 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260202_151119.jpg` | JPG | 2.5 MB | 2296×4080 | 2026-02-02 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260202_151127.jpg` | JPG | 2.6 MB | 2296×4080 | 2026-02-02 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260202_151132.jpg` | JPG | 2.8 MB | 2296×4080 | 2026-02-02 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260202_151140.jpg` | JPG | 2.5 MB | 2296×4080 | 2026-02-02 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260202_151429.jpg` | JPG | 3.0 MB | 2296×4080 | 2026-02-02 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260202_151430.jpg` | JPG | 3.3 MB | 2296×4080 | 2026-02-02 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260202_151522.jpg` | JPG | 4.0 MB | 4080×2296 | 2026-02-02 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260225_151134.jpg` | JPG | 1.6 MB | 2560×1440 | 2026-02-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260225_153513.jpg` | JPG | 3.0 MB | 4080×2296 | 2026-02-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260225_153641.jpg` | JPG | 3.5 MB | 2296×4080 | 2026-02-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260225_153651.jpg` | JPG | 3.5 MB | 2296×4080 | 2026-02-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260225_153742.jpg` | JPG | 4.0 MB | 4080×2296 | 2026-02-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260225_154007.jpg` | JPG | 2.6 MB | 2296×4080 | 2026-02-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20260225_154013.jpg` | JPG | 2.6 MB | 2296×4080 | 2026-02-25 | In use as `Bricks-kiln-slaves-children-29-scaled-e1787822836311.jpg` (attachment 104) |
| `20260225_154021.jpg` | JPG | 2.6 MB | 2296×4080 | 2026-02-25 | In use as `Bricks-kiln-slaves-children-30-scaled-e1787822825532.jpg` (attachment 105) |
| `20260225_154404.jpg` | JPG | 2.7 MB | 4080×2296 | 2026-02-25 | In use as `Bricks-kiln-slaves-children-31-scaled.jpg` (attachment 106) |
| `20260225_154416.jpg` | JPG | 3.8 MB | 4080×2296 | 2026-02-25 | In use as `Bricks-kiln-slaves-children-32-scaled.jpg` (attachment 107) |
| `20260225_154551.jpg` | JPG | 3.5 MB | 2296×4080 | 2026-02-25 | In use as `Bricks-kiln-slaves-children-33-scaled-e1787822814979.jpg` (attachment 108) |
| `20260225_154604.jpg` | JPG | 3.7 MB | 2296×4080 | 2026-02-25 | In use as `Bricks-kiln-slaves-children-34-scaled-e1787822803466.jpg` (attachment 109) |
| `20260225_154730.jpg` | JPG | 2.8 MB | 2296×4080 | 2026-02-25 | In use as `Bricks-kiln-slaves-children-35-scaled-e1787822792740.jpg` (attachment 110) |
| `20260225_154739.jpg` | JPG | 2.7 MB | 2296×4080 | 2026-02-25 | In use as `Bricks-kiln-slaves-children-36-scaled-e1787822780752.jpg` (attachment 111) |
| `20260225_154743.jpg` | JPG | 2.5 MB | 2296×4080 | 2026-02-25 | In use as `Bricks-kiln-slaves-children-37-scaled-e1787822770768.jpg` (attachment 112) |
| `20260225_154835.jpg` | JPG | 3.1 MB | 4080×2296 | 2026-02-25 | In use as `Bricks-kiln-slaves-children-38-scaled.jpg` (attachment 113) |
| `IMG_1245.HEIC` | HEIC | 4.6 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1251.JPG` | JPG | 2.0 MB | 3520×1980 | 2026-01-27 | In use as `Bricks-kiln-slaves-children-39-scaled.jpg` (attachment 114) |
| `IMG_1255.HEIC` | HEIC | 3.3 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1288.HEIC` | HEIC | 1.7 MB | 3024×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1292.HEIC` | HEIC | 2.3 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1341.HEIC` | HEIC | 3.5 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1342.HEIC` | HEIC | 3.3 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1351.HEIC` | HEIC | 2.9 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1359.JPG` | JPG | 1.3 MB | 1980×3520 | 2026-01-27 | In use as `Bricks-kiln-slaves-children-40-scaled.jpg` (attachment 115) |
| `IMG_1372.HEIC` | HEIC | 1.5 MB | 3024×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1378.HEIC` | HEIC | 2.4 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1386.HEIC` | HEIC | 1.7 MB | 3024×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1402.HEIC` | HEIC | 1.7 MB | 3024×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1408.HEIC` | HEIC | 2.0 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1419.HEIC` | HEIC | 2.2 MB | 5712×3213 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1420.HEIC` | HEIC | 1.6 MB | 5712×3213 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1424.HEIC` | HEIC | 1.6 MB | 3213×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1437.HEIC` | HEIC | 1.5 MB | 3213×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1453.HEIC` | HEIC | 2.3 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1469.HEIC` | HEIC | 2.9 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1470.HEIC` | HEIC | 2.9 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1474.HEIC` | HEIC | 3.1 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `PhotoCollage_1766563096653.jpg` | JPG | 3.1 MB | 2048×2048 | 2025-12-24 | Not used. Candidate for this folder's gallery (needs approval) |

### `Ministries data/Children ministry/Orphanages care`

| File | Type | Size | Dimensions | Taken | Status / destination |
|---|---|---|---|---|---|
| `20251221_170645.jpg` | JPG | 1.8 MB | 2296×4080 | 2025-12-21 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251221_170649.jpg` | JPG | 2.5 MB | 2296×4080 | 2025-12-21 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251222_160743.jpg` | JPG | 2.4 MB | 4080×2296 | 2025-12-22 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251222_162318.jpg` | JPG | 2.1 MB | 2296×4080 | 2025-12-22 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251222_162349.jpg` | JPG | 2.1 MB | 2296×4080 | 2025-12-22 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251222_162417.jpg` | JPG | 2.1 MB | 2296×4080 | 2025-12-22 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251222_162722.jpg` | JPG | 2.0 MB | 2296×4080 | 2025-12-22 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251222_162929.jpg` | JPG | 2.0 MB | 2296×4080 | 2025-12-22 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251222_163043.jpg` | JPG | 1.9 MB | 2296×4080 | 2025-12-22 | In use as `Orphanages-care-9-scaled-e1787818960294.jpg` (attachment 145) |
| `20251222_164155.jpg` | JPG | 2.6 MB | 2296×4080 | 2025-12-22 | In use as `Orphanages-care-10-scaled-e1787818978151.jpg` (attachment 134) |
| `20251222_164350.jpg` | JPG | 3.5 MB | 2296×4080 | 2025-12-22 | In use as `Orphanages-care-11-scaled-e1787818995644.jpg` (attachment 135) |
| `20251222_165526.jpg` | JPG | 3.2 MB | 2296×4080 | 2025-12-22 | In use as `Orphanages-care-12-scaled-e1787819009333.jpg` (attachment 136) |
| `20251222_165545.jpg` | JPG | 2.5 MB | 2296×4080 | 2025-12-22 | In use as `Orphanages-care-13-scaled-e1787819027587.jpg` (attachment 137) |
| `20251222_165717.jpg` | JPG | 3.4 MB | 2296×4080 | 2025-12-22 | In use as `Orphanages-care-14-scaled-e1787819044976.jpg` (attachment 138) |
| `20251222_170042.jpg` | JPG | 2.5 MB | 2296×4080 | 2025-12-22 | In use as `Orphanages-care-15-scaled-e1787819059654.jpg` (attachment 139) |
| `20251222_170129.jpg` | JPG | 2.2 MB | 2296×4080 | 2025-12-22 | In use as `Orphanages-care-16-scaled-e1787819076312-rsij8jj20ojpz9btf5v77vd2yjnnuanqoo6mn3kqjc.jpg`, `Orphanages-care-16-scaled-e1787819076312.jpg` (attachment 140, 159) |
| `20251222_170239.jpg` | JPG | 2.3 MB | 2296×4080 | 2025-12-22 | In use as `Orphanages-care-17-scaled-e1787819100112.jpg` (attachment 141) |
| `FB_IMG_1766909237683.jpg` | JPG | 0.1 MB | 652×924 |  | In use as `Orphanages-care-18.jpg` (attachment 142) |
| `Screenshot_20251201_224038_Gallery.jpg` | JPG | 0.2 MB | 874×499 | 2025-12-01 | In use as `Orphanages-care-19.jpg` (attachment 143) |
| `Screenshot_20251201_224208_Gallery.jpg` | JPG | 0.2 MB | 975×435 | 2025-12-01 | In use as `Orphanages-care-20.jpg` (attachment 144) |

### `Ministries data/Deliverance and Healing ministry`

| File | Type | Size | Dimensions | Taken | Status / destination |
|---|---|---|---|---|---|
| `20250904_165924.jpg` | JPG | 2.8 MB | 2296×4080 | 2025-09-04 | In use as `Deliverance-and-Healing-ministry-1-scaled-e1787828396674.jpg`, `Deliverance-and-Healing-ministry-5-scaled-e1787225773575-rs6kxdo5ygbat93txitcpifrbjmk10tf6j9kjqh6qg.jpg` (attachment 116, 155) |
| `20250904_171632.jpg` | JPG | 3.1 MB | 2296×4080 | 2025-09-04 | In use as `Deliverance-and-Healing-ministry-2-scaled-e1787828386736.jpg` (attachment 118) |
| `20250904_171637.jpg` | JPG | 3.2 MB | 2296×4080 | 2025-09-04 | In use as `Deliverance-and-Healing-ministry-3-scaled-e1787828373981.jpg` (attachment 119) |
| `20251115_170305.jpg` | JPG | 2.4 MB | 2296×4080 | 2025-11-15 | In use as `Deliverance-and-Healing-ministry-4-scaled-e1787828361817.jpg` (attachment 120) |
| `20251115_170309.jpg` | JPG | 2.6 MB | 2296×4080 | 2025-11-15 | In use as `Deliverance-and-Healing-ministry-5-scaled-e1787828305580.jpg` (attachment 121) |
| `20260308_131851.jpg` | JPG | 2.5 MB | 2296×4080 | 2026-03-08 | In use as `Deliverance-and-Healing-ministry-6-scaled-e1787828407656.jpg` (attachment 122) |
| `IMG_1831.HEIC` | HEIC | 1.3 MB | 3024×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1837.JPG` | JPG | 1.1 MB | 1980×3520 | 2026-02-08 | In use as `Deliverance-and-Healing-ministry-7-scaled-rsipx59tezwztxu5gnyaynlp07r154u9fjtzzwtvtk.jpg`, `Deliverance-and-Healing-ministry-7-scaled.jpg` (attachment 123, 156) |
| `IMG_1887.JPG` | JPG | 1.5 MB | 1980×3520 | 2026-02-08 | In use as `Deliverance-and-Healing-ministry-8-scaled.jpg` (attachment 124) |
| `IMG_2601.HEIC` | HEIC | 1.1 MB | 3213×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `Screenshot_20260711_001248_Video Player.jpg` | JPG | 0.5 MB | 1080×1826 | 2026-07-11 | In use as `Deliverance-and-Healing-ministry-9.jpg` (attachment 125) |
| `Screenshot_20260711_001316_Video Player.jpg` | JPG | 0.6 MB | 1080×1877 | 2026-07-11 | In use as `Deliverance-and-Healing-ministry-10.jpg` (attachment 117) |

### `Ministries data/Free medical camp for bricks kiln slaves and village people_s_`

Empty folder.

### `Ministries data/Freedom from slavery missions`

Empty folder.

### `Ministries data/General pictures_`

| File | Type | Size | Dimensions | Taken | Status / destination |
|---|---|---|---|---|---|
| `20250301_175340.jpg` | JPG | 0.8 MB | 2560×1184 | 2025-03-01 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250301_175344.jpg` | JPG | 1.0 MB | 2560×1184 | 2025-03-01 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250301_175439.jpg` | JPG | 0.9 MB | 2560×1184 | 2025-03-01 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250525_172251.jpg` | JPG | 4.0 MB | 4080×1884 | 2025-05-25 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250717_134654.jpg` | JPG | 3.3 MB | 2296×4080 | 2025-07-17 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250919_170238.jpg` | JPG | 2.6 MB | 4080×2296 | 2025-09-19 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250919_173219.jpg` | JPG | 3.5 MB | 4080×2296 | 2025-09-19 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250919_173409.jpg` | JPG | 3.7 MB | 4080×2296 | 2025-09-19 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250919_174044.jpg` | JPG | 2.2 MB | 4080×2296 | 2025-09-19 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250919_174100.jpg` | JPG | 3.7 MB | 4080×2296 | 2025-09-19 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_144227.jpg` | JPG | 3.1 MB | 4080×2296 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_144335.jpg` | JPG | 2.7 MB | 4080×2296 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_150259.jpg` | JPG | 2.7 MB | 2296×4080 | 2025-10-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `20251006_150450.jpg` | JPG | 2.4 MB | 4080×2296 | 2025-10-06 | In use as `General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg` (attachment 157) |
| `IMG_20250418_181835.jpg` | JPG | 6.0 MB | 4080×3072 | 2025-04-18 | Not used. Candidate for this folder's gallery (needs approval) |
| `IMG_2511.HEIC` | HEIC | 1.9 MB | 4032×3024 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2512.HEIC` | HEIC | 2.0 MB | 4032×3024 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2514.HEIC` | HEIC | 1.8 MB | 3024×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2528.HEIC` | HEIC | 2.8 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_4298.HEIC` | HEIC | 1.4 MB | 3024×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_4302.HEIC` | HEIC | 2.6 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_4303.HEIC` | HEIC | 2.6 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_4312.HEIC` | HEIC | 2.0 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_4349.JPG` | JPG | 1.4 MB | 3520×1980 | 2026-05-06 | Not used. Candidate for this folder's gallery (needs approval) |
| `IMG_4361.HEIC` | HEIC | 1.8 MB | 4032×3024 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |

### `Ministries data/Leadership and Discipleship`

| File | Type | Size | Dimensions | Taken | Status / destination |
|---|---|---|---|---|---|
| `.mp4` | MP4 | 0.7 MB | 1080×1920, 8.1 s |  | Not used. Candidate for a ministry page video (needs approval) |
| `IMG-20260622-WA0006.jpg` | JPG | 1.1 MB | 4160×3120 |  | Not used. Candidate for this folder's gallery (needs approval) |
| `IMG-20260622-WA0011.jpg` | JPG | 0.6 MB | 3520×1980 |  | Not used. Candidate for this folder's gallery (needs approval) |
| `IMG-20260622-WA0022.jpg` | JPG | 1.9 MB | 3120×4160 |  | In use as `Leadership-and-Discipleship-4-1-scaled.jpg` (attachment 128) |
| `IMG-20260622-WA0059.jpg` | JPG | 1.3 MB | 4032×3024 |  | In use as `Leadership-and-Discipleship-5-1-scaled.jpg` (attachment 129) |
| `IMG-20260622-WA0060.jpg` | JPG | 1.6 MB | 4032×3024 |  | In use as `Leadership-and-Discipleship-6-1-scaled.jpg` (attachment 130) |
| `IMG-20260625-WA0001.jpg` | JPG | 0.1 MB | 1320×674 |  | In use as `Leadership-and-Discipleship-7-1.jpg` (attachment 131) |
| `IMG-20260625-WA0002.jpg` | JPG | 0.1 MB | 1320×703 |  | In use as `Leadership-and-Discipleship-8.jpg` (attachment 132) |
| `IMG-20260625-WA0008.jpg` | JPG | 0.8 MB | 3092×1996 |  | In use as `Leadership-and-Discipleship-9-scaled-rsiplqicgeaowcet38ec7199etzmpejk72qt9zqxco.jpg`, `Leadership-and-Discipleship-9-scaled.jpg` (attachment 133, 158) |
| `IMG-20260625-WA0009.jpg` | JPG | 1.1 MB | 4160×3120 |  | In use as `Leadership-and-Discipleship-10-scaled.jpg` (attachment 126) |
| `IMG-20260625-WA0010.jpg` | JPG | 1.4 MB | 4032×3024 |  | In use as `Leadership-and-Discipleship-11-scaled.jpg` (attachment 127) |
| `IMG_1739.HEIC` | HEIC | 1.5 MB | 3024×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1747.HEIC` | HEIC | 3.3 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1756.HEIC` | HEIC | 1.6 MB | 3024×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1762.HEIC` | HEIC | 1.7 MB | 3024×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1771.HEIC` | HEIC | 2.7 MB | 3024×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1775.JPG` | JPG | 1.3 MB | 3520×1980 | 2026-02-08 | Not used. Candidate for this folder's gallery (needs approval) |
| `IMG_1779.HEIC` | HEIC | 2.8 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_1787.HEIC` | HEIC | 2.0 MB | 3024×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `VID-20260622-WA0057.mp4` | MP4 | 3.0 MB | 1280×720, 13.1 s |  | Not used. Candidate for a ministry page video (needs approval) |
| `VID-20260623-WA0005.mp4` | MP4 | 4.3 MB | 1280×720, 19.1 s |  | Not used. Candidate for a ministry page video (needs approval) |
| `VID-20260623-WA0006.mp4` | MP4 | 5.1 MB | 1280×720, 22.3 s |  | Not used. Candidate for a ministry page video (needs approval) |

### `Ministries data/Orphanage and underprivileged children care_`

Empty folder.

### `Ministries data/Outreach Ministry`

| File | Type | Size | Dimensions | Taken | Status / destination |
|---|---|---|---|---|---|
| `IMG_2453.HEIC` | HEIC | 3.0 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2463.HEIC` | HEIC | 2.9 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2544.HEIC` | HEIC | 3.7 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2546.HEIC` | HEIC | 4.1 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2556.HEIC` | HEIC | 2.5 MB | 5712×4284 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2598.HEIC` | HEIC | 2.5 MB | 3213×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2599.HEIC` | HEIC | 2.3 MB | 3213×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2606.HEIC` | HEIC | 2.3 MB | 5712×3213 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2612.HEIC` | HEIC | 1.7 MB | 3213×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2616.HEIC` | HEIC | 1.3 MB | 2268×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2617.HEIC` | HEIC | 1.1 MB | 2268×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2621.HEIC` | HEIC | 1.6 MB | 2268×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2623.HEIC` | HEIC | 2.0 MB | 3213×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2626.HEIC` | HEIC | 1.3 MB | 2268×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2636.HEIC` | HEIC | 1.5 MB | 2268×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2640.HEIC` | HEIC | 2.1 MB | 4032×2268 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2666.HEIC` | HEIC | 1.5 MB | 2268×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2669.HEIC` | HEIC | 1.9 MB | 2268×4032 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2689.HEIC` | HEIC | 3.2 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2692.HEIC` | HEIC | 2.3 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `VID-20260301-WA0044.mp4` | MP4 | 3.4 MB | 1280×720, 9.6 s |  | Not used. Candidate for a ministry page video (needs approval) |
| `VID-20260301-WA0163.mp4` | MP4 | 13.6 MB | 1280×720, 39.8 s |  | Not used. Candidate for a ministry page video (needs approval) |

### `Ministries data/Urdu Bibles Distribution ministry`

| File | Type | Size | Dimensions | Taken | Status / destination |
|---|---|---|---|---|---|
| `1783588870297.png` | PNG | 1.5 MB | 1367×768 |  | In use as `Bible-Distribution-rqhhvb1bt4op61pyxtdlg5smtcfpsj957ob37u8700.png` (attachment 153) |
| `20250301_175806.mp4` | MP4 | 21.4 MB | 1920×1080, 10.4 s |  | Not used. Candidate for a ministry page video (needs approval) |
| `20250301_175904.jpg` | JPG | 1.4 MB | 2560×1184 | 2025-03-01 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250301_175906.jpg` | JPG | 1.4 MB | 2560×1184 | 2025-03-01 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250301_175934.jpg` | JPG | 0.7 MB | 1184×2560 | 2025-03-01 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250301_175942.jpg` | JPG | 0.8 MB | 1184×2560 | 2025-03-01 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250301_180258.jpg` | JPG | 0.8 MB | 1184×2560 | 2025-03-01 | Not used. Candidate for this folder's gallery (needs approval) |
| `20250301_180428.jpg` | JPG | 0.8 MB | 1184×2560 | 2025-03-01 | In use as `Urdu-Bible-Distribution-6-e1787296463599-rs8073jb4tpz51knz3wuum8c11mkotg8ytctdb8ink.jpg` (attachment 161) |
| `20250301_180440.jpg` | JPG | 0.7 MB | 1184×2560 | 2025-03-01 | In use as `Urdu-Bible-Distribution-7-e1784535997635.jpg` (attachment 98) |
| `20250301_180523.jpg` | JPG | 0.8 MB | 1184×2560 | 2025-03-01 | In use as `Urdu-Bible-Distribution-8-e1784536009842.jpg` (attachment 99) |
| `20250301_180524.jpg` | JPG | 0.7 MB | 1184×2560 | 2025-03-01 | In use as `Urdu-Bible-Distribution-9-e1784536026463.jpg` (attachment 100) |
| `20250301_180656.jpg` | JPG | 0.7 MB | 1184×2560 | 2025-03-01 | In use as `Urdu-Bible-Distribution-10-e1787225652778-rs6ku7xozhzlsfp1dnnhtt4zfx755n9wewbth95tnc.jpg`, `Urdu-Bible-Distribution-10-e1787225652778.jpg` (attachment 160, 89) |
| `20250301_180802.jpg` | JPG | 0.7 MB | 1184×2560 | 2025-03-01 | In use as `Urdu-Bible-Distribution-11-e1784536054126.jpg` (attachment 90) |
| `20250301_180839.jpg` | JPG | 0.7 MB | 1184×2560 | 2025-03-01 | In use as `Urdu-Bible-Distribution-12.jpg` (attachment 91) |
| `20250301_180845.jpg` | JPG | 0.7 MB | 1184×2560 | 2025-03-01 | In use as `Urdu-Bible-Distribution-13-e1784536079985.jpg` (attachment 92) |
| `20250301_180908.jpg` | JPG | 0.7 MB | 1184×2560 | 2025-03-01 | In use as `Urdu-Bible-Distribution-14-e1784536098255.jpg` (attachment 93) |
| `20250301_180929.jpg` | JPG | 0.7 MB | 1184×2560 | 2025-03-01 | In use as `Urdu-Bible-Distribution-15-e1784536109482.jpg` (attachment 94) |
| `20250301_181001.jpg` | JPG | 0.7 MB | 1184×2560 | 2025-03-01 | In use as `Urdu-Bible-Distribution-16-e1784536121378.jpg` (attachment 95) |
| `IMG-20260709-WA0002.jpg` | JPG | 1.3 MB | 4160×2339 |  | Not used. Candidate for this folder's gallery (needs approval) |
| `IMG_20230211_163619(1).jpg` | JPG | 1.2 MB | 2000×3120 | 2023-02-11 | In use as `Urdu-Bible-Distribution-17-scaled.jpg` (attachment 96) |
| `IMG_20230211_163656(1).jpg` | JPG | 1.2 MB | 2144×3120 | 2023-02-11 | In use as `Urdu-Bible-Distribution-18-scaled.jpg` (attachment 97) |
| `IMG_2807.HEIC` | HEIC | 2.8 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2810.HEIC` | HEIC | 2.3 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2811.HEIC` | HEIC | 2.5 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2812.HEIC` | HEIC | 2.8 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `IMG_2813.HEIC` | HEIC | 2.2 MB | 4284×5712 |  | Not used. HEIC: browsers cannot show it; convert to JPEG before import (needs approval) |
| `unnamed (2).jpg` | JPG | 0.2 MB | 960×960 |  | Not used. Candidate for this folder's gallery (needs approval) |
| `unnamed (3).jpg` | JPG | 0.1 MB | 960×960 |  | Not used. Candidate for this folder's gallery (needs approval) |
| `unnamed.jpg` | JPG | 0.1 MB | 960×960 |  | Not used. Candidate for this folder's gallery (needs approval) |

### `Ministries data/Vedios`

| File | Type | Size | Dimensions | Taken | Status / destination |
|---|---|---|---|---|---|
| `GE-6whhM7je7HKMDADvReZA2y39zbmdjAAAF.mp4` | MP4 | 11.5 MB | 720×1280, 46.9 s |  | Not used. Candidate for a ministry page video (needs approval) |
| `VID-20260712-WA0003.mp4` | MP4 | 6.3 MB | 1280×720, 25.2 s |  | Not used. Candidate for a ministry page video (needs approval) |
| `lv_0_20251013121200.mp4` | MP4 | 45.4 MB | 1280×720, 50.1 s |  | In use (re-encoded copy `a.mp4`, attachment 151) |

### `Ministries data/Widows ministry`

| File | Type | Size | Dimensions | Taken | Status / destination |
|---|---|---|---|---|---|
| `20251221_154631.jpg` | JPG | 3.0 MB | 4080×2296 | 2025-12-21 | In use as `Widows-ministry-1-scaled-rsinedytivoatdo3ffn47k3g4v35x8jaac67u3ecm0.jpg`, `Widows-ministry-1-scaled.jpg` (attachment 146, 162) |
| `IMG_20240302_122853.jpg` | JPG | 3.5 MB | 3060×4080 | 2024-03-02 | In use as `Widows-ministry-2-scaled.jpg` (attachment 147) |
| `IMG_20240302_122903.jpg` | JPG | 4.1 MB | 3060×4080 | 2024-03-02 | In use as `Widows-ministry-3-scaled.jpg` (attachment 148) |
| `IMG_20240302_123849.jpg` | JPG | 4.0 MB | 3060×4080 | 2024-03-02 | In use as `Widows-ministry-4-scaled.jpg` (attachment 149) |
| `IMG_20240302_123909.jpg` | JPG | 3.7 MB | 3060×4080 | 2024-03-02 | In use as `Widows-ministry-5-scaled.jpg` (attachment 150) |
| `IMG_20240302_123923.jpg` | JPG | 3.6 MB | 3060×4080 | 2024-03-02 | Not used. Candidate for this folder's gallery (needs approval) |
| `IMG_20240302_123933.jpg` | JPG | 3.8 MB | 3060×4080 | 2024-03-02 | Not used. Candidate for this folder's gallery (needs approval) |

### `Pastor pictures_`

| File | Type | Size | Dimensions | Taken | Status / destination |
|---|---|---|---|---|---|
| `file_00000000b46471f484befac1dc0ee291.png` | PNG | 1.7 MB | 1254×1254 |  | In use as `file_00000000b46471f484befac1dc0ee291.png` (attachment 152) |

## Method

- Every file was read: SHA-1 (exact duplicates), image size and EXIF date/orientation (Pillow; HEIC via `pillow-heif`), MP4 length, size, codecs and faststart from the file's own boxes.
- Website images: every `kingdomrevivalministries.com/wp-content/uploads/…` URL in the theme and `html/`, compared by image content, since the old site renamed every file. A 256-bit difference hash at 0/90/180/270° matched 70 photos (distance 0–3; unrelated photos score 90+). Cropped thumbnails were matched by searching windows of each photo (correlation 0.96–1.00), and 2 more by eye; General-pictures-14 matched only when mirrored.
- `a.mp4` was downloaded from the live site and compared frame by frame (1 per second) with all 16 videos in Chrome: for all 48 sampled frames the best match is `lv_0_20251013121200.mp4`, in time order (27 with correlation above 0.9).
- Media Library state from the local test site (`wp option get krm_media_map`): 85 design files, one attachment each.
