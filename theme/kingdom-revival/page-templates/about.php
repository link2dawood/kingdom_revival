<?php
/**
 * Template Name: About
 * About page.
 *
 * Markup copied from about.html; only links, images and contact details are dynamic.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="main">
	<section class="page-hero" style="background-image: url('<?php echo esc_url( krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-11-scaled.jpg' ) ); ?>')">
		<div class="wrap">
		<ol class="breadcrumb"><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li><li aria-current="page">About</li></ol>
		<p class="eyebrow">About Us</p>
		<h1>A Gospel-centred ministry serving <em>Pakistan’s forgotten</em></h1>
		<p class="lead">We preach Jesus Christ, plant churches, equip leaders and care for orphans, widows and brick kiln families — so that the forgotten know they are loved by God.</p>
		<div class="hero-actions">
			<a class="button button-primary" href="<?php echo esc_url( krm_page_url( 'partner' ) ); ?>">Partner with us</a>
			<a class="button button-light" href="#founder">Meet our founder</a>
		</div>
		</div>
	</section>

	<section class="section">
		<div class="wrap">
		<div class="story">
			<div class="collage" data-gallery>
			<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-16-scaled-e1787819076312.jpg' ); ?> alt="Children in our orphan care ministry" loading="lazy" decoding="async">
			<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-9-scaled.jpg' ); ?> alt="Leadership and discipleship gathering" loading="lazy" decoding="async">
			<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-31.jpg' ); ?> alt="New believers at a baptism" loading="lazy" decoding="async">
			<div class="collage-badge"><strong>12</strong><span>ministries, one mission</span></div>
			</div>
			<div class="story-copy">
			<p class="eyebrow">Who we are</p>
			<h2>Bringing the hope of Jesus to those the world overlooks</h2>
			<p>Kingdom Revival Ministries is based in Faisalabad, Pakistan. We serve unreached villages, marginalised communities and families who live and work beside the brick kilns — many of them trapped in poverty and debt bondage.</p>
			<p>Our mission is to glorify God by making disciples of Jesus Christ, proclaiming the Gospel among unreached communities, planting churches, equipping leaders, and serving vulnerable children and brick kiln families.</p>
			<div class="hero-actions">
				<a class="button button-primary" href="<?php echo esc_url( home_url( '/#all-ministries' ) ); ?>">Explore our ministries</a>
			</div>
			</div>
		</div>
		<div class="mv-grid">
			<article class="mv-card">
			<p class="eyebrow">Our mission</p>
			<h3>Make disciples. Serve the vulnerable.</h3>
			<p>To glorify God by proclaiming the Gospel, planting churches, equipping leaders and showing Christ’s love through practical care.</p>
			</article>
			<article class="mv-card vision">
			<p class="eyebrow">Our vision</p>
			<h3>Revival that reaches every village</h3>
			<p>Communities across Pakistan transformed by the Gospel — with thriving local churches, freed families and children who grow up knowing Jesus.</p>
			</article>
		</div>
		</div>
	</section>

	<section class="section section-soft">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow">What we do</p>
			<h2>Word and deed, hand in hand</h2>
			<p>Everything we do flows from the Gospel and is expressed through practical love.</p>
		</div>
		<div class="work-grid">
			<article class="work-card">
			<span class="work-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20"></path></svg></span>
			<h3>Proclaim the Gospel</h3>
			<p>Taking the good news of Jesus Christ to villages and unreached communities across Pakistan.</p>
			</article>
			<article class="work-card">
			<span class="work-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 10h18M5 10V6l7-4 7 4v4M5 10v10h14V10M9 20v-6h6v6"></path></svg></span>
			<h3>Plant churches</h3>
			<p>Planting and strengthening local and house churches where believers can gather and grow.</p>
			</article>
			<article class="work-card">
			<span class="work-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"></path></svg></span>
			<h3>Equip leaders</h3>
			<p>Discipling believers and training pastors and leaders to shepherd their communities.</p>
			</article>
			<article class="work-card">
			<span class="work-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg></span>
			<h3>Care for the vulnerable</h3>
			<p>Serving orphans, widows and brick kiln families with education, aid and love.</p>
			</article>
		</div>
		</div>
	</section>

	<section class="stats-wrap" aria-label="Our impact">
		<div class="wrap">
		<div class="stats">
			<?php foreach ( krm_stats() as $krm_stat ) : ?>
			<div class="stat"><strong><?php echo esc_html( $krm_stat['value'] ); ?>+</strong><span><?php echo esc_html( $krm_stat['label'] ); ?></span></div>
			<?php endforeach; ?>
		</div>
		</div>
	</section>

	<section class="section" id="founder">
		<div class="wrap founder">
		<div class="founder-photo">
			<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/file_00000000b46471f484befac1dc0ee291.png' ); ?> alt="Pastor Adil Arif, founder of Kingdom Revival Ministries" data-zoom="founder" loading="lazy" decoding="async">
		</div>
		<div>
			<p class="eyebrow">Founder &amp; Pastor</p>
			<h2>Pastor Adil Arif</h2>
			<p>Pastor Adil leads Kingdom Revival Ministries in evangelism, discipleship and church ministry, with a heart for children and youth — and for practical care among villages, marginalised communities and brick kiln families.</p>
			<blockquote>“Go therefore and make disciples of all nations.”<cite>Matthew 28:19</cite></blockquote>
			<div class="hero-actions">
			<a class="button button-secondary" href="<?php echo esc_url( krm_mailto() ); ?>">Contact Pastor Adil</a>
			</div>
		</div>
		</div>
	</section>

	<section class="section section-soft">
		<div class="wrap">
		<div class="section-head center">
			<p class="eyebrow">Get involved</p>
			<h2>Three ways to stand with us</h2>
		</div>
		<div class="ways">
			<article class="way"><span class="way-num">01</span><span class="work-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v6M9 6h6M5 21V11l7-4 7 4v10M9 21v-5h6v5"></path></svg></span><h3>Pray</h3><p>Intercede for the ministry, the families we serve, and the power of the Gospel.</p><a href="<?php echo esc_url( krm_page_url( 'contact' ) ); ?>">Send a prayer request <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg></a></article>
			<article class="way"><span class="way-num">02</span><span class="work-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path></svg></span><h3>Give</h3><p>Provide Bibles, education, orphan care and emergency aid for families in crisis.</p><a href="<?php echo esc_url( krm_page_url( 'partner' ) ); ?>">Become a partner <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg></a></article>
			<article class="way"><span class="way-num">03</span><span class="work-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20"></path></svg></span><h3>Go</h3><p>Support outreach, church planting and humanitarian work on the ground.</p><a href="<?php echo esc_url( krm_page_url( 'contact' ) ); ?>">Get in touch <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"></path></svg></a></article>
		</div>
		</div>
	</section>

	<section class="section">
		<div class="wrap">
		<div class="section-head">
			<p class="eyebrow">In the field</p>
			<h2>Moments from our ministry</h2>
		</div>
		<p class="gallery-tip"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="M21 21l-4.3-4.3M11 8v6M8 11h6"></path></svg>Tap any photo to view it full screen and zoom in.</p>
		<div class="photo-grid" data-gallery>
			<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-39-scaled.jpg' ); ?> alt="Children from a brick kiln community" loading="lazy" decoding="async">
			<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Baptisms-29.jpg' ); ?> alt="A baptism service" loading="lazy" decoding="async">
			<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Orphanages-care-20.jpg' ); ?> alt="Children in orphan care" loading="lazy" decoding="async">
			<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/07/Urdu-Bible-Distribution-18-scaled.jpg' ); ?> alt="Urdu Bible distribution" loading="lazy" decoding="async">
			<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Widows-ministry-5-scaled.jpg' ); ?> alt="Widows ministry gathering" loading="lazy" decoding="async">
			<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Leadership-and-Discipleship-11-scaled.jpg' ); ?> alt="Leadership and discipleship training" loading="lazy" decoding="async">
			<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Deliverance-and-Healing-ministry-10.jpg' ); ?> alt="Prayer for the sick" loading="lazy" decoding="async">
			<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-40-scaled.jpg' ); ?> alt="Brick kiln children" loading="lazy" decoding="async">
		</div>
		</div>
	</section>

	<section class="section" style="padding-top: 0">
		<div class="wrap">
		<?php
		get_template_part(
			'template-parts/give-band',
			null,
			array(
				'image'   => 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-40-scaled.jpg',
				'eyebrow' => 'Become a partner',
				'heading' => 'Your gift carries the Gospel further',
				'text'    => 'Every gift helps us preach Christ, plant churches and care for orphans, widows and brick kiln families across Pakistan.',
				'points'  => array( 'Bibles for new believers and house churches', 'Education and care for vulnerable children', 'Practical help for widows and families in crisis' ),
				'form'    => array(
					'short'   => true,
					'heading' => 'Give to the mission',
				),
			)
		);
		?>
		</div>
	</section>
	</main>
<?php
get_footer();
