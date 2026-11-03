<?php
/**
 * Story content: block output matched to the design's article markup.
 *
 * Stories are written with ordinary blocks in the editor. On the front end, inside single stories, a few blocks are
 * printed the way the design printed them, so the approved styles apply unchanged:
 *  - Quote               → <blockquote>text<cite>source</cite></blockquote>
 *  - Image with caption  → <figure><img><figcaption>[zoom icon] caption</figcaption></figure>
 *  - List with class "checks" → check-mark list
 *  - Gallery             → photo grid + "Tap a photo…" tip
 *  - [krm_give] shortcode → "Help write the next chapter" box
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

/**
 * Only reshape blocks inside a single story's main content.
 */
function krm_in_story() {
	return is_singular( 'post' ) && in_the_loop() && is_main_query();
}

add_filter(
	'render_block',
	function ( $html, $block ) {
		if ( ! krm_in_story() || empty( $block['blockName'] ) ) {
			return $html;
		}
		switch ( $block['blockName'] ) {
			case 'core/quote':
				$cite = '';
				if ( preg_match( '#<cite[^>]*>(.*?)</cite>#s', $html, $m ) ) {
					$cite = $m[1];
					$html = str_replace( $m[0], '', $html );
				}
				preg_match_all( '#<p[^>]*>(.*?)</p>#s', $html, $paras );
				$text = $paras[1] ? implode( '<br>', $paras[1] ) : trim( wp_strip_all_tags( $html, false ) );
				return '<blockquote>' . $text . ( '' !== $cite ? '<cite>' . $cite . '</cite>' : '' ) . '</blockquote>';

			case 'core/image':
				if ( ! empty( $block['attrs']['krmInGallery'] ) ) {
					return $html;
				}
				if ( ! preg_match( '#<img[^>]+>#', $html, $img ) ) {
					return $html;
				}
				$caption = '';
				if ( preg_match( '#<figcaption[^>]*>(.*?)</figcaption>#s', $html, $c ) ) {
					$caption = '<figcaption>' . krm_icon( 'zoom' ) . $c[1] . '</figcaption>';
				}
				return '<figure>' . krm_clean_img( $img[0], '(max-width: 1040px) 100vw, 740px', 'figure' ) . $caption . '</figure>';

			case 'core/list':
				$classes = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';
				if ( false === strpos( $classes, 'checks' ) ) {
					return $html;
				}
				$html = preg_replace( '#<li[^>]*>(.*?)</li>#s', '<li>' . krm_icon( 'check' ) . '<span>$1</span></li>', $html );
				return preg_replace( '#^\s*<ul[^>]*>#', '<ul class="checks">', $html );

			case 'core/gallery':
				preg_match_all( '#<img[^>]+>#', $html, $imgs );
				if ( ! $imgs[0] ) {
					return $html;
				}
				return '<div class="post-gallery">' . implode(
					'',
					array_map(
						function ( $img ) {
							return krm_clean_img( $img, '(max-width: 1040px) 50vw, 370px', 'tile' );
						},
						$imgs[0]
					)
				) . '</div><p class="zoom-tip">' . krm_icon( 'zoom' ) . esc_html__( 'Tap a photo to open it full screen, zoom in and browse.', 'kingdom-revival' ) . '</p>';
		}
		return $html;
	},
	10,
	2
);

/* Mark images inside galleries so the image rule above leaves them for the gallery rule. */
add_filter(
	'render_block_data',
	function ( $block ) {
		if ( 'core/gallery' === $block['blockName'] && ! empty( $block['innerBlocks'] ) ) {
			foreach ( $block['innerBlocks'] as &$inner ) {
				$inner['attrs']['krmInGallery'] = true;
			}
		}
		return $block;
	}
);

/**
 * Print an <img> the way the design did (no block classes, sizes or inline styles), adding responsive
 * srcset/sizes for Media Library images and lazy loading.
 *
 * $kind 'tile' (gallery photo, a 4:3 box) uses the 4:3 card crops when the photo has them. 'figure' (photo in the
 * text, full width with its own height) keeps the photo's width and height attributes, so its space is reserved
 * before it loads (post.css gives it height: auto, so the attributes set only the shape).
 *
 * @param string $img   Image tag.
 * @param string $sizes Sizes attribute for the srcset.
 * @param string $kind  'tile', 'figure' or ''.
 */
function krm_clean_img( $img, $sizes = '100vw', $kind = '' ) {
	$srcset = '';
	$dims   = '';
	if ( preg_match( '#wp-image-(\d+)#', $img, $m ) ) {
		$id = (int) $m[1];
		if ( false === strpos( $img, 'srcset=' ) ) {
			$set = 'tile' === $kind ? krm_card_srcset( $id ) : '';
			if ( ! $set ) {
				$set = wp_get_attachment_image_srcset( $id, 'full' );
			}
			if ( $set ) {
				$srcset = ' srcset="' . esc_attr( $set ) . '" sizes="' . esc_attr( $sizes ) . '"';
			}
		}
		$full = 'figure' === $kind ? wp_get_attachment_image_src( $id, 'full' ) : false;
		if ( $full && $full[1] && $full[2] ) {
			$dims = ' width="' . (int) $full[1] . '" height="' . (int) $full[2] . '"';
		}
	}
	$img = preg_replace( '#\s(class|style|width|height)="[^"]*"#', '', $img );
	if ( $srcset || $dims ) {
		$img = preg_replace( '#^<img #', '<img' . $srcset . $dims . ' ', $img );
	}
	if ( false === strpos( $img, 'loading=' ) ) {
		$img = str_replace( '<img ', '<img loading="lazy" ', $img );
	}
	if ( false === strpos( $img, 'decoding=' ) ) {
		$img = str_replace( '<img ', '<img decoding="async" ', $img );
	}
	return $img;
}

/* "Help write the next chapter" box from the design. */
add_shortcode(
	'krm_give',
	function () {
		$ministry = krm_post_ministry( get_the_ID() );
		return '<div class="inline-give" role="note"><span class="icon">' . krm_icon( 'heart' ) . '</span><div><h3>' . esc_html__( 'Help write the next chapter', 'kingdom-revival' ) . '</h3>' .
			'<p>' . sprintf(
				/* translators: %s: ministry name */
				esc_html__( 'Stories like this happen because people like you give. Your gift to %s goes straight to the work you are reading about.', 'kingdom-revival' ),
				esc_html( $ministry['title'] )
			) . '</p>' .
			'<a class="button button-primary" href="#give">' . esc_html( sprintf( /* translators: %s: ministry name */ __( 'Give to %s', 'kingdom-revival' ), $ministry['title'] ) ) . ' ' . krm_icon( 'arrow' ) . '</a></div></div>';
	}
);

/* Shortcode blocks wrap output in nothing, but classic paragraphs may wrap it in <p>; unwrap it. */
add_filter(
	'the_content',
	function ( $content ) {
		return preg_replace( '#<p>\s*(<div class="inline-give" role="note">.*?</div></div>)\s*</p>#s', '$1', $content );
	},
	20
);

/* News & Blogs shows 12 stories per page. */
add_action(
	'pre_get_posts',
	function ( $q ) {
		if ( ! is_admin() && $q->is_main_query() && ( $q->is_home() || $q->is_category() || $q->is_tag() ) ) {
			$q->set( 'posts_per_page', 12 );
		}
	}
);

/**
 * Print a story card (blog grid, related stories, archives, search).
 *
 * @param WP_Post $post   Post.
 * @param array   $attrs  Extra attributes for the card link.
 */
function krm_post_card( $post, $attrs = array() ) {
	$cover = krm_post_cover( $post->ID );
	$extra = '';
	foreach ( $attrs as $k => $v ) {
		$extra .= ' ' . $k . ( true === $v ? '' : '="' . esc_attr( $v ) . '"' );
	}
	?>
	<a class="post-card" href="<?php the_permalink( $post ); ?>"<?php echo $extra; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><div class="post-card-media"><img <?php krm_img_src( $cover, '(max-width: 760px) 100vw, 400px', true ); ?> alt="<?php echo esc_attr( krm_cover_alt( $post->ID ) ); ?>" loading="lazy" decoding="async"><span class="tag"><?php echo esc_html( krm_post_category( $post->ID ) ); ?></span></div><div class="post-card-body"><div class="post-meta"><span><?php echo krm_icon( 'cal' ) . esc_html( get_the_date( 'F j, Y', $post ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><span><?php echo krm_icon( 'clock' ) . esc_html( krm_read_time( $post->ID ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> min read</span></div><h3><?php echo esc_html( get_the_title( $post ) ); ?></h3><p><?php echo esc_html( get_the_excerpt( $post ) ); ?></p><span class="read-more">Read the story <?php echo krm_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></div></a>
	<?php
}

/**
 * Featured story at the top of News & Blogs (markup from blog.html).
 *
 * @param WP_Post $post Post.
 */
function krm_featured_card( $post ) {
	$cover = krm_post_cover( $post->ID );
	?>
<a class="featured" href="<?php the_permalink( $post ); ?>"><div class="featured-media"><img <?php krm_img_src( $cover, '(max-width: 900px) 100vw, 60vw' ); ?> alt="<?php echo esc_attr( krm_cover_alt( $post->ID ) ); ?>" loading="lazy" decoding="async"><span class="featured-badge">★ Featured story</span></div><div class="featured-body"><span class="tag"><?php echo esc_html( krm_post_category( $post->ID ) ); ?></span><h2><?php echo esc_html( get_the_title( $post ) ); ?></h2><p><?php echo esc_html( get_the_excerpt( $post ) ); ?></p><div class="post-meta"><span><?php echo krm_icon( 'cal' ) . esc_html( get_the_date( 'F j, Y', $post ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><span><?php echo krm_icon( 'clock' ) . esc_html( krm_read_time( $post->ID ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> min read</span></div><span class="button button-primary">Read the full story <?php echo krm_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></div></a>
	<?php
}

/**
 * Data used by the blog filters. The featured story's grid card starts hidden: like the design, it only
 * appears in the grid while a category or search is active.
 *
 * @param WP_Post      $post     Post.
 * @param WP_Post|null $featured Featured post.
 */
function krm_blog_card_attrs( $post, $featured ) {
	$cat   = krm_post_category( $post->ID );
	$attrs = array(
		'data-category' => $cat,
		'data-search'   => mb_strtolower( get_the_title( $post ) . ' ' . get_the_excerpt( $post ) . ' ' . $cat ),
	);
	if ( $featured && $featured->ID === $post->ID ) {
		$attrs['data-featured'] = '1';
		$attrs['hidden']        = true;
	}
	return $attrs;
}

/**
 * Page links for story listings (only when there is more than one page).
 */
function krm_pagination() {
	$links = paginate_links(
		array(
			'type'      => 'array',
			'mid_size'  => 1,
			'prev_text' => '← Newer stories',
			'next_text' => 'Older stories →',
		)
	);
	if ( ! $links ) {
		return;
	}
	echo '<nav class="krm-pagination" aria-label="' . esc_attr__( 'More stories', 'kingdom-revival' ) . '">';
	foreach ( $links as $link ) {
		echo str_replace( array( 'page-numbers current', 'page-numbers' ), array( 'chip" aria-pressed="true', 'chip' ), $link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core markup.
	}
	echo '</nav>';
}

/**
 * Up to three related stories: same category first, then the newest others (as in the design).
 *
 * @param int $post_id Current post.
 * @return WP_Post[]
 */
function krm_related_posts( $post_id ) {
	$cats    = wp_get_post_categories( $post_id );
	$related = $cats ? get_posts(
		array(
			'category__in'        => $cats,
			'post__not_in'        => array( $post_id ),
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	) : array();
	if ( count( $related ) < 3 ) {
		$more    = get_posts(
			array(
				'post__not_in'        => array_merge( array( $post_id ), wp_list_pluck( $related, 'ID' ) ),
				'posts_per_page'      => 3 - count( $related ),
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		$related = array_merge( $related, $more );
	}
	return $related;
}
