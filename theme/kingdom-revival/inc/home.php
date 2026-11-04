<?php
/**
 * Home page data and helpers.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Social profiles saved in Kingdom Revival → Settings, as network => [ label, URL ]. The top bar shows an icon for
 * each one; with none saved it shows none (the inner-page design's top bar).
 */
function krm_social_links() {
	$links = array();
	foreach ( array(
		'facebook'  => 'Facebook',
		'youtube'   => 'YouTube',
		'instagram' => 'Instagram',
	) as $network => $label ) {
		$url = trim( (string) krm_opt( $network ) );
		if ( '' !== $url ) {
			$links[ $network ] = array( $label, $url );
		}
	}
	return $links;
}

/**
 * Donate page link for an amount picked on the home page (same parameters the design used).
 *
 * @param string $amount   '$50' or 'Other'.
 * @param string $for      Fund name (?for=…), used when no ministry slug is given.
 * @param string $ministry Ministry slug (?ministry=…), which Donate preselects.
 */
function krm_home_donate_href( $amount, $for = '', $ministry = '' ) {
	$value = 'Other' === $amount ? '' : str_replace( '$', '', $amount );
	$fund  = '' !== $ministry ? '&ministry=' . rawurlencode( $ministry ) : ( '' !== $for ? '&for=' . rawurlencode( $for ) : '' );
	return krm_donate_url() . '?amount=' . rawurlencode( $value ) . '&frequency=One-time' . $fund . '#give';
}

/**
 * Donate page link that preselects a ministry: /donate/?ministry=slug#give (plus optional extra parameters).
 *
 * @param string $slug Ministry slug.
 * @param array  $args Extra query parameters (e.g. amount).
 */
function krm_ministry_donate_url( $slug, $args = array() ) {
	$args = array_merge( '' !== (string) $slug ? array( 'ministry' => rawurlencode( $slug ) ) : array(), $args );
	return add_query_arg( $args, krm_donate_url() ) . '#give';
}

/**
 * The ministry the hero donation card gives to (Pages → Home → Page content → card ministry): its title, or ''.
 */
function krm_home_card_ministry_slug() {
	$slug = sanitize_title( (string) krm_c( 'hero.card_ministry', 'home' ) );
	return '' !== krm_home_card_ministry() ? $slug : '';
}

/**
 * The hero donation card ministry's title, or ''.
 */
function krm_home_card_ministry() {
	$slug = sanitize_title( (string) krm_c( 'hero.card_ministry', 'home' ) );
	foreach ( krm_ministries() as $m ) {
		if ( $m['slug'] === $slug ) {
			return $m['title'];
		}
	}
	return '';
}

/**
 * Home page amounts (hero card and floating panel): "$25" => selected?, then "Other" (what front.js reads).
 *
 * @return array<string,bool>
 */
function krm_home_amounts() {
	$default = (float) preg_replace( '/[^0-9.]/', '', krm_c( 'hero.default_amount', 'home' ) );
	$out     = array();
	foreach ( explode( ',', krm_c( 'hero.amounts', 'home' ) ) as $amount ) {
		$amount = (float) preg_replace( '/[^0-9.]/', '', $amount );
		if ( $amount > 0 ) {
			$out[ '$' . ( floor( $amount ) === $amount ? (int) $amount : $amount ) ] = $amount === $default;
		}
	}
	$out['Other'] = ! in_array( true, $out, true );
	return $out;
}

/**
 * The amount selected at first on the home page ("$50" or "Other").
 */
function krm_home_selected_amount() {
	return (string) array_search( true, krm_home_amounts(), true );
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
			'slug'   => $m['slug'],
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
		array( 'FAMILY CARE', 'Brick Kiln Family Care', 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Bricks-kiln-slaves-children-2-1-scaled-rsiliajqeyghqckx2003kqpavypwam7blarw8gd3dk.jpg', 'Schooling, Sunday school and care for children growing up beside the kilns.' ),
		array( 'SCRIPTURE', 'Bible Distribution', 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Urdu-Bible-Distribution-10-e1787225652778-rs6ku7xozhzlsfp1dnnhtt4zfx755n9wewbth95tnc.jpg', 'Placing Urdu Bibles in the hands of families and new believers.' ),
		array( 'CHILDREN', 'Children & Orphan Care', 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Orphanages-care-16-scaled-e1787819076312-rsij8jj20ojpz9btf5v77vd2yjnnuanqoo6mn3kqjc.jpg', 'Shelter, food and daily care for orphaned and underprivileged children.' ),
		array( 'WIDOWS', 'Widows Support', 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Widows-ministry-1-scaled-rsinedytivoatdo3ffn47k3g4v35x8jaac67u3ecm0.jpg', 'Support and fellowship for widows in the church and the village.' ),
		array( 'PRAYER', 'Prayer & Deliverance Ministry', 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Deliverance-and-Healing-ministry-5-scaled-e1787225773575-rs6kxdo5ygbat93txitcpifrbjmk10tf6j9kjqh6qg.jpg', 'Prayer for the sick and oppressed in the power of Jesus Christ.' ),
		array( 'FREEDOM', 'Slavery & Freedom Ministry', 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-31-scaled.jpg', 'Helping families held in debt bondage at brick kilns walk free.' ),
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
