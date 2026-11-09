<?php
/**
 * Template Name: Photo & Video Gallery
 * Photo & Video Gallery (/our-impact/gallery/): hero (breadcrumb, "Our Gallery", line, Give Now and Contact), tabs
 * Photos / Videos and filter chips — "Everything" plus one per ministry that has photos (assets/js/components.js).
 * Photos: every published ministry's gallery plus the page's "General photos", in a 2/3/4-column grid where every
 * sixth photo is a large tile, with a scripture card after about every 10 photos (Everything only), opened full
 * screen by the photo viewer (assets/js/viewer.js; it follows the active filter and skips the scripture cards).
 * Loading: skeleton tiles, lazy images below the fold, a fade-in once loaded (assets/js/gallery.js,
 * assets/css/pages/gallery.css). Videos: the page's YouTube links, loaded only when played; the Videos tab is shown
 * only once at least one YouTube link has been added (Phase 21).
 *
 * Without JavaScript both lists show, one after the other, unfiltered.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();

$krm_list   = krm_ministries();
$krm_by_t   = array();
$krm_photos = array();
$krm_seen   = array();
foreach ( $krm_list as $krm_m ) {
	$krm_by_t[ strtolower( $krm_m['title'] ) ] = $krm_m['slug'];
	foreach ( krm_ministry_photos( $krm_m ) as $krm_p ) {
		// A photo in two ministries' galleries is one tile in both filters (data-group "a b"), not two tiles.
		$krm_k = krm_project_ref_key( $krm_p[0] );
		if ( isset( $krm_seen[ $krm_k ] ) ) {
			$krm_photos[ $krm_seen[ $krm_k ] ][2] .= ' ' . $krm_m['slug'];
			continue;
		}
		$krm_seen[ $krm_k ] = count( $krm_photos );
		$krm_photos[]       = array( $krm_p[0], $krm_p[1], $krm_m['slug'], $krm_m['title'] );
	}
}
foreach ( (array) krm_c( 'photos.items' ) as $krm_p ) {
	if ( ! empty( $krm_p['image'] ) ) {
		$krm_photos[] = array( $krm_p['image'], krm_ref_alt( $krm_p['image'], $krm_p['image_alt'] ), 'general', krm_fill( krm_c( 'labels.general' ) ) );
	}
}
$krm_videos = array();
$krm_video_items = (array) krm_c( 'videos.items' );
if ( ! $krm_video_items ) {
	$krm_gallery_schema = krm_content_schema( 'gallery' );
	$krm_video_items    = (array) $krm_gallery_schema['videos']['fields']['items']['default'];
}
foreach ( $krm_video_items as $krm_v ) {
	if ( empty( $krm_v['url'] ) ) {
		continue;
	}
	$krm_key      = strtolower( trim( (string) $krm_v['ministry'] ) );
	$krm_video   = array(
		'id'       => '',
		'src'      => '',
		'title'    => krm_fill( $krm_v['title'] ),
		'ministry' => isset( $krm_by_t[ $krm_key ] ) ? $krm_by_t[ $krm_key ] : '',
		'name'     => trim( (string) $krm_v['ministry'] ),
	);
	if ( preg_match( '~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/))([A-Za-z0-9_-]{11})~', $krm_v['url'], $krm_match ) ) {
		$krm_video['id'] = $krm_match[1];
	} elseif ( preg_match( '#^(?:krm-theme:video/|https?://).+\.mp4(?:\?.*)?$#i', $krm_v['url'] ) ) {
		$krm_video['src'] = krm_media( $krm_v['url'] );
	} else {
		continue;
	}
	$krm_videos[] = $krm_video;
}
// The Videos tab exists only once a YouTube link has been added (Phase 21, client request); until then the page is
// photos only and the local video files are not shown.
$krm_has_videos = (bool) array_filter( array_column( $krm_videos, 'id' ) );
if ( ! $krm_has_videos ) {
	$krm_videos = array();
}
$krm_used = array_unique( array_merge( explode( ' ', implode( ' ', array_column( $krm_photos, 2 ) ) ), array_column( $krm_videos, 'ministry' ) ) );
?>
	<main id="main">
	<?php krm_hero_preload( krm_c( 'hero.image' ) ); ?>
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( krm_c( 'hero.image' ) ) ); ?>')<?php krm_bg_small( krm_c( 'hero.image' ) ); ?>">
		<div class="wrap">
		<?php krm_breadcrumb_html(); ?>
		<h1><?php krm_h( 'hero.title' ); ?></h1>
		<p class="lead"><?php krm_t( 'hero.lead' ); ?></p>
		<?php
		$krm_b1 = trim( (string) krm_c( 'hero.button1_label' ) );
		$krm_b2 = trim( (string) krm_c( 'hero.button2_label' ) );
		?>
		<?php if ( '' !== $krm_b1 || '' !== $krm_b2 ) : ?>
		<div class="hero-actions">
			<?php if ( '' !== $krm_b1 ) : ?>
			<a class="button button-primary" href="<?php krm_u( 'hero.button1_url' ); ?>"><?php echo krm_icon( 'heart18' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php krm_t( 'hero.button1_label' ); ?></a>
			<?php endif; ?>
			<?php if ( '' !== $krm_b2 ) : ?>
			<a class="button button-light" href="<?php krm_u( 'hero.button2_url' ); ?>"><?php krm_t( 'hero.button2_label' ); ?></a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		</div>
	</section>

	<section class="section">
		<div class="wrap">
		<div class="krm-filter-row">
			<?php if ( $krm_has_videos ) : ?>
			<div class="ministry-filter is-start" role="tablist" aria-label="<?php echo esc_attr( krm_fill( krm_c( 'hero.title' ) ) ); ?>" data-krm-tabs data-param="view">
			<button type="button" role="tab" id="tab-photos" aria-controls="panel-photos" aria-selected="true" data-tab="photos"><?php krm_t( 'labels.photos' ); ?></button>
			<button type="button" role="tab" id="tab-videos" aria-controls="panel-videos" aria-selected="false" data-tab="videos" tabindex="-1"><?php krm_t( 'labels.videos' ); ?></button>
			</div>
			<?php endif; ?>
			<?php // Chips: "Everything", then each ministry that has photos (or videos) — never an empty chip. Shown by components.js. ?>
			<div class="krm-chips" role="group" aria-label="<?php echo esc_attr( krm_fill( krm_c( 'labels.filter_label' ) ) ); ?>" data-krm-filter="gallery" data-param="ministry" hidden>
				<button type="button" data-group="" aria-pressed="true"><?php krm_t( 'labels.all_label' ); ?></button>
				<?php foreach ( $krm_list as $krm_m ) : ?>
					<?php if ( in_array( $krm_m['slug'], $krm_used, true ) ) : ?>
				<button type="button" data-group="<?php echo esc_attr( $krm_m['slug'] ); ?>" aria-pressed="false"><?php echo esc_html( $krm_m['title'] ); ?></button>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if ( in_array( 'general', $krm_used, true ) ) : ?>
				<button type="button" data-group="general" aria-pressed="false"><?php krm_t( 'labels.general' ); ?></button>
				<?php endif; ?>
			</div>
		</div>
		<p class="visually-hidden" aria-live="polite" data-krm-filter-status="gallery" data-text="<?php echo esc_attr( krm_fill( krm_c( 'labels.count_text' ) ) ); ?>"></p>

		<div<?php echo $krm_has_videos ? ' role="tabpanel" aria-labelledby="tab-photos"' : ''; ?> id="panel-photos">
			<?php if ( $krm_photos ) : ?>
			<p class="gallery-tip" style="display: flex; align-items: center; gap: 8px; margin: 0 0 18px; color: var(--color-text-muted); font-size: 14px"><?php echo krm_icon( 'zoom' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php krm_t( 'labels.tip' ); ?></p>
			<div class="krm-gal" data-gallery data-krm-filter-list="gallery">
				<?php
				$krm_verses = array();
				foreach ( array( 1, 2 ) as $krm_vn ) {
					if ( '' !== trim( (string) krm_c( 'labels.verse' . $krm_vn ) ) ) {
						$krm_verses[] = array( krm_c( 'labels.verse' . $krm_vn ), krm_c( 'labels.cite' . $krm_vn ) );
					}
				}
				$krm_total = count( $krm_photos );
				foreach ( $krm_photos as $krm_i => $krm_p ) :
					// Every sixth photo is a large tile (2 × 2 from tablet up); the first eight load at once.
					$krm_big = 0 === $krm_i % 6;
					?>
				<figure class="krm-gal__tile<?php echo $krm_big ? ' is-big' : ''; ?>" data-group="<?php echo esc_attr( $krm_p[2] ); ?>"><img <?php krm_img_src( $krm_p[0], $krm_big ? '(max-width: 639px) calc(50vw - 22px), (max-width: 1023px) calc(66vw - 30px), 610px' : '(max-width: 639px) calc(50vw - 22px), (max-width: 1023px) calc(33vw - 24px), 300px', true ); ?> alt="<?php echo esc_attr( $krm_p[1] ); ?>"<?php echo $krm_i >= 8 ? ' loading="lazy"' : ''; ?> decoding="async"><figcaption><?php echo esc_html( $krm_p[3] ); ?></figcaption></figure>
					<?php
					// A scripture card after every 10 photos (not after the last), alternating; hidden by any filter.
					if ( $krm_verses && 0 === ( $krm_i + 1 ) % 10 && $krm_i + 1 < $krm_total ) :
						$krm_v = $krm_verses[ ( ( $krm_i + 1 ) / 10 - 1 ) % count( $krm_verses ) ];
						?>
				<blockquote class="krm-gal__verse" data-group=""><p><?php echo krm_text( $krm_v[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p><cite><?php echo krm_text( $krm_v[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></cite></blockquote>
						<?php
					endif;
				endforeach;
				?>
			</div>
			<p class="krm-note" data-krm-filter-empty hidden><?php krm_t( 'labels.empty' ); ?></p>
			<?php else : ?>
			<p class="krm-note"><?php krm_t( 'labels.empty' ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $krm_has_videos ) : ?>
		<div role="tabpanel" id="panel-videos" aria-labelledby="tab-videos">
			<?php if ( $krm_videos ) : ?>
			<ul class="video-grid" data-krm-filter-list="gallery" data-krm-more="18" data-more-text="<?php echo esc_attr( krm_fill( krm_c( 'labels.more' ) ) ); ?>">
				<?php foreach ( $krm_videos as $krm_v ) : ?>
				<li data-group="<?php echo esc_attr( $krm_v['ministry'] ); ?>">
				<figure class="video-card">
					<?php if ( $krm_v['id'] ) : ?>
					<button type="button" data-krm-video="<?php echo esc_attr( $krm_v['id'] ); ?>" data-title="<?php echo esc_attr( $krm_v['title'] ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: video title */ __( 'Play video: %s', 'kingdom-revival' ), $krm_v['title'] ) ); ?>">
					<img src="<?php echo esc_url( 'https://i.ytimg.com/vi/' . $krm_v['id'] . '/hqdefault.jpg' ); ?>" alt="" width="480" height="360" loading="lazy" decoding="async">
					</button>
					<?php else : ?>
					<video controls preload="metadata" playsinline aria-label="<?php echo esc_attr( $krm_v['title'] ); ?>"><source src="<?php echo esc_url( $krm_v['src'] ); ?>" type="video/mp4"><?php esc_html_e( 'Your browser does not support HTML video.', 'kingdom-revival' ); ?></video>
					<?php endif; ?>
					<figcaption><?php echo esc_html( $krm_v['title'] ); ?><?php if ( '' !== $krm_v['name'] ) : ?><small><?php echo esc_html( $krm_v['name'] ); ?></small><?php endif; ?></figcaption>
				</figure>
				</li>
				<?php endforeach; ?>
			</ul>
			<p class="krm-note" data-krm-filter-empty hidden><?php krm_t( 'labels.empty' ); ?></p>
			<?php else : ?>
			<p class="krm-note"><?php krm_t( 'labels.no_videos' ); ?></p>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		</div>
	</section>
	</main>
<?php
get_footer();
