<?php
/**
 * Home page data and helpers.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Social icon link attributes. Without a saved URL the icon stays visible but is not a link.
 *
 * @param string $network facebook|youtube|instagram.
 */
function krm_social_href( $network ) {
	$url = krm_opt( $network );
	if ( $url ) {
		echo 'href="' . esc_url( $url ) . '" rel="noopener"';
	} else {
		echo 'role="img"';
	}
}

/**
 * Donate page link for an amount picked on the home page (same parameters the design used).
 *
 * @param string $amount '$50' or 'Other'.
 */
function krm_home_donate_href( $amount ) {
	$value = 'Other' === $amount ? '' : str_replace( '$', '', $amount );
	return krm_donate_url() . '?amount=' . rawurlencode( $value ) . '&frequency=One-time#give';
}

/**
 * Explorer data for assets/js/front.js.
 *
 * @param array $ministries Ministries in home order.
 */
function krm_explorer_data( $ministries ) {
	$items = array();
	foreach ( $ministries as $i => $m ) {
		$id      = is_numeric( $m['image'] ) ? (int) $m['image'] : krm_media_id( (string) $m['image'] );
		$items[] = array(
			'num'    => sprintf( '%02d', $i + 1 ),
			'title'  => $m['title'],
			'desc'   => $m['desc'],
			'url'    => $m['url'],
			'img'    => krm_ref_url( $m['image'] ),
			'srcset' => $id ? (string) wp_get_attachment_image_srcset( $id, 'full' ) : '',
		);
	}
	echo '<script type="application/json" id="krm-explorer-data">' . wp_json_encode( $items, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ) . '</script>';
}

/**
 * The six "You Can Bring Hope to Many by Giving a Little" cards: [ tag, title, image, description ].
 */
function krm_home_causes() {
	return array(
		array( 'EDUCATION', 'Brick Kiln Children', 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Bricks-kiln-slaves-children-2-1-scaled-rsiliajqeyghqckx2003kqpavypwam7blarw8gd3dk.jpg', 'Schooling, Sunday school and care for children growing up beside the kilns.' ),
		array( 'SCRIPTURE', 'Urdu Bible Distribution', 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Urdu-Bible-Distribution-10-e1787225652778-rs6ku7xozhzlsfp1dnnhtt4zfx755n9wewbth95tnc.jpg', 'Placing Urdu Bibles in the hands of families and new believers.' ),
		array( 'ORPHAN CARE', 'Orphanage & Children Care', 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Orphanages-care-16-scaled-e1787819076312-rsij8jj20ojpz9btf5v77vd2yjnnuanqoo6mn3kqjc.jpg', 'Shelter, food and daily care for orphaned and underprivileged children.' ),
		array( 'WIDOWS', 'Widows Ministry', 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Widows-ministry-1-scaled-rsinedytivoatdo3ffn47k3g4v35x8jaac67u3ecm0.jpg', 'Support and fellowship for widows in the church and the village.' ),
		array( 'HEALING', 'Deliverance & Healing', 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Deliverance-and-Healing-ministry-5-scaled-e1787225773575-rs6kxdo5ygbat93txitcpifrbjmk10tf6j9kjqh6qg.jpg', 'Prayer for the sick and oppressed in the power of Jesus Christ.' ),
		// The design reused the Deliverance & Healing photo here; this is a photo from the Freedom from Slavery gallery.
		array( 'FREEDOM', 'Freedom from Slavery', 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-38-scaled.jpg', 'Helping families held in debt bondage at brick kilns walk free.' ),
	);
}

/**
 * Icon paths for the five home page stats (people, church, heart, book, shield).
 */
function krm_home_stat_icons() {
	return array(
		'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8',
		'M3 10h18M5 10V6l7-4 7 4v4M5 10v10h14V10M9 20v-6h6v6',
		'M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z',
		'M4 19.5A2.5 2.5 0 0 1 6.5 17H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15zM20 17v5H6.5A2.5 2.5 0 0 1 4 19.5',
		'M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11zM9 12l2 2 4-4',
	);
}
