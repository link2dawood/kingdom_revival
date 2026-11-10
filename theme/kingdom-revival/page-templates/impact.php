<?php
/** Template Name: Our Impact
 * Data-driven impact dashboard. All figures and page copy live in inc/data/impact.json.
 * @package Kingdom_Revival
 */
defined( 'ABSPATH' ) || exit;
$path = KRM_DIR . '/inc/data/impact.json';
$data = json_decode( (string) file_get_contents( $path ), true );
$data = is_array( $data ) ? $data : array();
$pending = __( 'Awaiting verified figure', 'kingdom-revival' );
// Visitors see only parts with verified data (the user's placeholder rule); administrators see every part, empty ones marked.
$krm_admin = krm_show_placeholders();
$krm_num   = function ( $v ) { return null !== $v && '' !== $v && is_numeric( $v ); };
$krm_rows  = function ( $rows, $key = 'value' ) use ( $krm_num ) { foreach ( (array) $rows as $r ) { if ( isset( $r[ $key ] ) && $krm_num( $r[ $key ] ) ) { return true; } } return false; };
if ( ! $krm_admin ) {
	$data['headlineCounters'] = array_values( array_filter( (array) $data['headlineCounters'], function ( $c ) use ( $krm_num ) { return $krm_num( $c['value'] ); } ) );
	$data['ministries']       = array_values( array_filter( (array) $data['ministries'], function ( $m ) { return ! empty( $m['keyMetrics'] ) || ! empty( $m['yearlyValues'] ) || ! empty( $m['locations'] ); } ) );
}
$krm_growth     = false;
foreach ( (array) $data['growth']['series'] as $krm_s ) { $krm_growth = $krm_growth || $krm_rows( isset( $krm_s['values'] ) ? $krm_s['values'] : array() ); }
$krm_districts  = false;
foreach ( (array) $data['districts'] as $krm_d ) { $krm_districts = $krm_districts || $krm_num( $krm_d['villages'] ) || $krm_num( $krm_d['kilns'] ) || $krm_num( $krm_d['churches'] ); }
$krm_freed      = $krm_rows( $data['freedom']['familiesFreedPerYear'] );
$data['showPending'] = $krm_admin;
get_header();
?>
<main id="main" class="impact-page">
<?php krm_hero_preload( krm_c( 'hero.image', 'impact' ) ); // Same hero as the other inner pages (photo under the navy overlay), edited in Page content → Hero. ?>
<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_ref_url( krm_c( 'hero.image', 'impact' ) ) ); ?>')<?php krm_bg_small( krm_c( 'hero.image', 'impact' ) ); ?>"><div class="wrap"><?php krm_breadcrumb_html(); ?><p class="eyebrow"><?php krm_t( 'hero.eyebrow', 'impact' ); ?></p><h1><?php krm_h( 'hero.title', 'impact' ); ?></h1><p class="lead"><?php krm_t( 'hero.lead', 'impact' ); ?></p></div></section>

<?php if ( $data['headlineCounters'] ) : ?><section class="impact-counters"<?php echo krm_photo_band_attrs( krm_c( 'numbers.band_photo', 'impact' ), 'stats' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-labelledby="impact-numbers"><div class="wrap"><?php krm_admin_only_note( ! array_filter( $data['headlineCounters'], function ( $c ) use ( $krm_num ) { return $krm_num( $c['value'] ); } ) ); ?><p class="eyebrow">The story in numbers</p><h2 id="impact-numbers">Every number represents a person</h2><div class="impact-counters__grid">
<?php foreach ( $data['headlineCounters'] as $counter ) : ?><article class="impact-counter"><strong<?php echo is_numeric( $counter['value'] ) ? ' data-krm-count' : ''; ?>><?php if ( is_numeric( $counter['value'] ) ) { echo esc_html( number_format_i18n( $counter['value'] ) ); } else { ?><span aria-hidden="true">—</span><span class="visually-hidden"><?php echo esc_html( $pending ); ?></span><?php } ?></strong><span><?php echo esc_html( $counter['label'] ); ?></span></article><?php endforeach; ?>
</div></div></section><?php endif; ?>

<?php if ( $data['ministries'] ) : ?><section class="impact-section impact-section--soft" aria-labelledby="impact-ministries"><div class="wrap"><div class="impact-heading"><div><p class="eyebrow">Impact by ministry</p><h2 id="impact-ministries">One mission, twelve expressions</h2></div><p>Choose a ministry to explore its metrics, yearly progress and places served.</p></div>
<div class="impact-explorer" data-impact-explorer><div class="impact-explorer__tabs" role="tablist" aria-label="What We Do">
<?php foreach ( $data['ministries'] as $i => $ministry ) : ?><button type="button" role="tab" id="impact-tab-<?php echo esc_attr( $i ); ?>" aria-controls="impact-ministry-panel" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $i ? '0' : '-1'; ?>" data-impact-index="<?php echo esc_attr( $i ); ?>"><span><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><?php echo esc_html( $ministry['name'] ); ?></button><?php endforeach; ?>
</div><article class="impact-explorer__panel" id="impact-ministry-panel" role="tabpanel" aria-labelledby="impact-tab-0" tabindex="0"><p class="impact-kicker" data-impact-group><?php echo esc_html( $data['ministries'][0]['group'] ); ?></p><h3 data-impact-name><?php echo esc_html( $data['ministries'][0]['name'] ); ?></h3><div class="impact-mini-metrics" data-impact-metrics><?php if ( $krm_admin ) : ?><p class="impact-pending"><?php echo esc_html( $pending ); ?></p><?php endif; ?></div><div class="impact-chart impact-chart--small" data-impact-ministry-chart aria-label="Yearly ministry impact chart"></div><p class="impact-locations"<?php echo $krm_admin ? '' : ' hidden'; ?>><strong>Locations:</strong> <span data-impact-locations><?php echo $krm_admin ? 'To be supplied' : ''; ?></span></p><a class="impact-pill" data-impact-support href="<?php echo esc_url( add_query_arg( 'ministry', $data['ministries'][0]['slug'], krm_donate_url() ) ); ?>#give">Support this ministry</a></article></div>
</div></section><?php endif; ?>

<?php if ( $krm_admin || $krm_growth ) : ?><section class="impact-section" aria-labelledby="impact-growth"><div class="wrap"><div class="impact-heading"><div><?php krm_admin_only_note( ! $krm_growth ); ?><p class="eyebrow">Year by year</p><h2 id="impact-growth">Growth over the years</h2></div><p>Exact values remain available below every visual as an accessible data table.</p></div><div class="impact-chart impact-chart--large" data-impact-growth aria-label="Growth over the years chart"></div></div></section><?php endif; ?>

<section class="impact-section impact-section--navy" aria-labelledby="impact-map"><div class="wrap"><div class="impact-heading"><div><p class="eyebrow">Where we work</p><h2 id="impact-map">Serving Punjab, village by village</h2></div><p>Our base is in Faisalabad, with ministry reaching Jhang and other districts as verified records are added.</p></div><div class="impact-map-grid"><div class="impact-map"><?php get_template_part( 'template-parts/where-map', null, array( 'places' => (array) $data['districts'], 'label' => __( 'Map of Pakistan with Punjab highlighted, showing the ministry districts and their villages and brick kilns', 'kingdom-revival' ) ) ); // Same map as the home "Where we serve" section. ?></div><?php if ( $krm_admin || $krm_districts ) : ?><div class="impact-table-wrap"><table><caption>District ministry footprint</caption><thead><tr><th>District</th><th>Villages</th><th>Kilns</th><th>Churches</th></tr></thead><tbody><?php foreach ( $data['districts'] as $district ) : ?><tr><th><?php echo esc_html( $district['name'] ); ?></th><?php foreach ( array( 'villages', 'kilns', 'churches' ) as $key ) : ?><td><?php echo is_numeric( $district[ $key ] ) ? esc_html( number_format_i18n( $district[ $key ] ) ) : '<span aria-label="' . esc_attr( $pending ) . '">—</span>'; ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div></div></section>

<section class="impact-section impact-section--soft" aria-labelledby="freedom-spotlight"><div class="wrap"><div class="impact-heading"><div><p class="eyebrow">Freedom spotlight</p><h2 id="freedom-spotlight">From bonded labour to a new beginning</h2></div><p>Yearly family freedom totals sit beside the story behind each number.</p></div><div class="impact-freedom"><?php if ( $krm_admin || $krm_freed ) : ?><div class="impact-chart" data-impact-freedom aria-label="Families freed per year chart"></div><?php endif; ?><div class="impact-compare"><?php get_template_part( 'template-parts/home/freedom', null, array( 'screen' => 'home' ) ); ?></div></div></div></section>

<section class="impact-section impact-section--soft" aria-labelledby="stories-title"><div class="wrap"><div class="impact-heading"><div><p class="eyebrow">Lives changed</p><h2 id="stories-title">Stories from the field</h2></div></div><div class="impact-stories"><?php foreach ( $data['stories'] as $story ) : ?><article><img <?php krm_img_src( $story['image'], '(max-width: 720px) 100vw, 380px', true ); ?> alt="<?php echo esc_attr( $story['alt'] ); ?>" loading="lazy"><div><h3><?php echo esc_html( $story['title'] ); ?></h3><p><?php echo esc_html( $story['text'] ); ?></p></div></article><?php endforeach; ?></div><?php if ( $data['annualReport']['url'] ) : // No report file = no button (the "coming soon" placeholder was removed in theme 1.22.4). ?><div class="impact-report"><a class="impact-pill" href="<?php echo esc_url( krm_ref_url( $data['annualReport']['url'] ) ); ?>" download><?php echo esc_html( $data['annualReport']['label'] ); ?></a></div><?php endif; ?></div></section>

<?php get_template_part( 'template-parts/impact-links' ); // Testimonies and Photo & Video Gallery (Phase 21). ?>

<section class="impact-cta"><div class="wrap"><div><h2><?php echo esc_html( $data['cta']['title'] ); ?></h2><p><?php echo esc_html( $data['cta']['text'] ); ?></p></div><a class="impact-pill" href="<?php echo esc_url( krm_donate_url() ); ?>#give"><?php echo esc_html( $data['cta']['buttonLabel'] ); ?></a></div></section>
<script type="application/json" id="krm-impact-data"><?php echo wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?></script>
</main>
<?php get_footer(); ?>
