<?php
/**
 * Design images that were Elementor thumbnails on the old site (uploads/elementor/thumbs/), mapped to the copy shipped
 * in the theme's assets/ folder. The design still names them by their old URL (that URL stays the key in content
 * defaults and in krm_media_map), but nothing loads from the Elementor folder: Setup imports the theme's copy and,
 * until then, pages show it.
 *
 * The JPEGs are the old site's files byte for byte. Bible-Distribution was an 800×600 PNG photo (710 KB, no
 * transparency); its copy is a quality-92 JPEG (133 KB, visually the same).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Bible-Distribution-rqhhvb1bt4op61pyxtdlg5smtcfpsj957ob37u8700.png' => 'images/content/bible-distribution-crop.jpg',
	'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Bricks-kiln-slaves-children-2-1-scaled-rsiliajqeyghqckx2003kqpavypwam7blarw8gd3dk.jpg' => 'images/content/bricks-kiln-slaves-children-2-1-crop.jpg',
	'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Deliverance-and-Healing-ministry-5-scaled-e1787225773575-rs6kxdo5ygbat93txitcpifrbjmk10tf6j9kjqh6qg.jpg' => 'images/content/deliverance-and-healing-ministry-5-crop.jpg',
	'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Deliverance-and-Healing-ministry-7-scaled-rsipx59tezwztxu5gnyaynlp07r154u9fjtzzwtvtk.jpg' => 'images/content/deliverance-and-healing-ministry-7-crop.jpg',
	'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg' => 'images/content/general-pictures-14-crop.jpg',
	'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Leadership-and-Discipleship-9-scaled-rsiplqicgeaowcet38ec7199etzmpejk72qt9zqxco.jpg' => 'images/content/leadership-and-discipleship-9-crop.jpg',
	'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Orphanages-care-16-scaled-e1787819076312-rsij8jj20ojpz9btf5v77vd2yjnnuanqoo6mn3kqjc.jpg' => 'images/content/orphanages-care-16-crop.jpg',
	'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Urdu-Bible-Distribution-10-e1787225652778-rs6ku7xozhzlsfp1dnnhtt4zfx755n9wewbth95tnc.jpg' => 'images/content/urdu-bible-distribution-10-crop.jpg',
	'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Urdu-Bible-Distribution-6-e1787296463599-rs8073jb4tpz51knz3wuum8c11mkotg8ytctdb8ink.jpg' => 'images/content/urdu-bible-distribution-6-crop.jpg',
	'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Widows-ministry-1-scaled-rsinedytivoatdo3ffn47k3g4v35x8jaac67u3ecm0.jpg' => 'images/content/widows-ministry-1-crop.jpg',
);
