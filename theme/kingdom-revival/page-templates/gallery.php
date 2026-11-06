<?php
/**
 * Template Name: Photo & Video Gallery
 * Photo & Video Gallery (/our-impact/gallery/): tabs Photos / Videos and a ministry filter (assets/js/components.js).
 * Photos: every published ministry's gallery plus the page's "General photos", opened full screen by the photo
 * viewer (assets/js/viewer.js). Videos: the page's YouTube links, loaded only when played; the Videos tab is shown
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
foreach ( $krm_list as $krm_m ) {
	$krm_by_t[ strtolower( $krm_m['title'] ) ] = $krm_m['slug'];
	foreach ( krm_ministry_photos( $krm_m ) as $krm_p ) {
		$krm_photos[] = array( $krm_p[0], $krm_p[1], $krm_m['slug'], $krm_m['title'] );
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
$krm_used = array_unique( array_merge( array_column( $krm_photos, 2 ), array_column( $krm_videos, 'ministry' ) ) );
?>
	<main id="main">
	<?php krm_hero_preload( krm_c( 'hero.image' ) ); ?>
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( krm_c( 'hero.image' ) ) ); ?>')<?php krm_bg_small( krm_c( 'hero.image' ) ); ?>">
		<div class="wrap">
		<?php krm_breadcrumb_html(); ?>
		<p class="eyebrow"><?php krm_t( 'hero.eyebrow' ); ?></p>
		<h1><?php krm_h( 'hero.title' ); ?></h1>
		<p class="lead"><?php krm_t( 'hero.lead' ); ?></p>
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
			<label class="krm-select krm-needs-js" data-krm-filter="gallery" data-param="ministry"><?php krm_t( 'labels.filter_label' ); ?>
			<select>
				<option value=""><?php krm_t( 'labels.all_label' ); ?></option>
				<?php foreach ( $krm_list as $krm_m ) : ?>
					<?php if ( in_array( $krm_m['slug'], $krm_used, true ) ) : ?>
				<option value="<?php echo esc_attr( $krm_m['slug'] ); ?>"><?php echo esc_html( $krm_m['title'] ); ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if ( in_array( 'general', $krm_used, true ) ) : ?>
				<option value="general"><?php krm_t( 'labels.general' ); ?></option>
				<?php endif; ?>
			</select>
			</label>
		</div>
		<p class="visually-hidden" aria-live="polite" data-krm-filter-status="gallery" data-text="<?php echo esc_attr( krm_fill( krm_c( 'labels.count_text' ) ) ); ?>"></p>

		<div<?php echo $krm_has_videos ? ' role="tabpanel" aria-labelledby="tab-photos"' : ''; ?> id="panel-photos">
			<?php if ( $krm_photos ) : ?>
			<p class="gallery-tip" style="display: flex; align-items: center; gap: 8px; margin: 0 0 18px; color: var(--color-text-muted); font-size: 14px"><?php echo krm_icon( 'zoom' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php krm_t( 'labels.tip' ); ?></p>
			<div class="gallery" data-gallery data-krm-filter-list="gallery" data-krm-more="18" data-more-text="<?php echo esc_attr( krm_fill( krm_c( 'labels.more' ) ) ); ?>">
				<?php foreach ( $krm_photos as $krm_p ) : ?>
				<figure class="photo" data-group="<?php echo esc_attr( $krm_p[2] ); ?>"><img <?php krm_img_src( $krm_p[0], '(max-width: 520px) calc(100vw - 32px), (max-width: 760px) calc(50vw - 25px), (max-width: 1240px) calc(33vw - 24px), 400px', true ); ?> alt="<?php echo esc_attr( $krm_p[1] ); ?>" loading="lazy" decoding="async"><figcaption><?php echo esc_html( $krm_p[3] ); ?></figcaption></figure>
				<?php endforeach; ?>
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
