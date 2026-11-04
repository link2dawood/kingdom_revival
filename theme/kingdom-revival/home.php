<?php
/** News archive (/news/). @package Kingdom_Revival */
defined( 'ABSPATH' ) || exit;
get_header();
global $wp_query;
$posts      = $wp_query->posts;
$featured   = ( ! is_paged() && $posts ) ? $posts[0] : null;
$categories = array( 'All', 'Freedom', 'Urdu Bibles', 'Medical Camps', 'Children', 'Church Planting', 'Events' );
?>
<main id="main" class="news-page">
<?php krm_hero_preload( krm_c( 'hero.image' ) ); ?>
<section class="page-hero news-hero" style="background-image:url('<?php echo esc_url( krm_ref_url( krm_c( 'hero.image' ) ) ); ?>')<?php krm_bg_small( krm_c( 'hero.image' ) ); ?>"><div class="wrap"><?php krm_breadcrumb_html(); ?><p class="eyebrow">News & Updates</p><h1><?php krm_h( 'hero.title' ); ?></h1><p class="lead"><?php krm_t( 'hero.lead' ); ?></p></div></section>

<section class="news-main"><div class="wrap">
<?php if ( $featured ) : ?><div class="news-featured"><p class="eyebrow">Featured latest story</p><?php krm_featured_card( $featured ); ?></div><?php endif; ?>
<div class="news-filter" aria-label="Filter stories by category"><div class="filters" id="filters" role="group"><?php foreach ( $categories as $category ) : ?><button type="button" class="chip" data-filter="<?php echo esc_attr( $category ); ?>" aria-pressed="<?php echo 'All' === $category ? 'true' : 'false'; ?>"><?php echo esc_html( $category ); ?></button><?php endforeach; ?></div><span class="post-meta" id="result-count" aria-live="polite"><?php echo esc_html( count( $posts ) . ( 1 === count( $posts ) ? ' story' : ' stories' ) ); ?></span></div>

<div class="news-layout"><div class="news-feed"><div class="post-grid" id="post-grid"><?php foreach ( $posts as $post ) { krm_post_card( $post, krm_blog_card_attrs( $post, $featured ) ); } ?></div><?php krm_pagination(); ?><div class="empty" id="empty"<?php echo $posts ? ' hidden' : ''; ?>><strong><?php krm_t( 'list.empty_title' ); ?></strong><?php krm_t( 'list.empty_text' ); ?></div></div>

<aside class="news-sidebar" aria-label="News tools">
<section class="news-widget"><h2>Search</h2><label class="search"><span class="visually-hidden">Search stories</span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="M21 21l-4.3-4.3"></path></svg><input type="search" id="search" placeholder="Search stories…"></label></section>
<section class="news-widget"><h2>Categories</h2><ul><?php foreach ( array_slice( $categories, 1 ) as $category ) : ?><li><button type="button" data-sidebar-filter="<?php echo esc_attr( $category ); ?>"><?php echo esc_html( $category ); ?><span>→</span></button></li><?php endforeach; ?></ul></section>
<section class="news-subscribe"><p class="eyebrow">Stay connected</p><h2>Get prayer & ministry updates</h2><p>Receive stories from the field, prayer needs and practical ways to help.</p><button type="button" class="button button-primary" data-open-newsletter>Subscribe for updates</button></section>
</aside></div>
</div></section>
</main>
<?php get_footer(); ?>
