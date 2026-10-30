<?php
/**
 * Home: field stories (featured video and photo gallery).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="field-stories" class="krm-field-stories" aria-labelledby="field-stories-title">
	<div class="krm-field-stories__inner">
	<div class="krm-field-stories__intro">
		<p class="krm-field-stories__eyebrow">Ministry programs</p>
		<h2 id="field-stories-title">See the ministry in action</h2>
		<p>From evangelism and discipleship to children’s education, Bible distribution and practical care, Kingdom Revival Ministries serves families across Pakistan and beyond.</p>
	</div>
	<div class="krm-field-stories__feature">
		<video class="krm-field-stories__video" controls playsinline preload="metadata" poster="<?php echo esc_url( krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/General-pictures-14-scaled-e1787296605557-rs80at0e3asoxo6sbplvoojsdrcp1u64t5zrilqm40.jpg' ) ); ?>" title="Kingdom Revival Ministries featured video">
		<source src="<?php echo esc_url( krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/a.mp4' ) ); ?>" type="video/mp4">
		Your browser does not support HTML video. <a href="<?php echo esc_url( krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/a.mp4' ) ); ?>">Open the ministry video</a>.
		</video>
		<div class="krm-field-stories__feature-copy">
		<p class="krm-field-stories__eyebrow">Featured video</p>
		<h3>Faith, service and hope</h3>
		<p>Watch the ministry’s official video and explore real images from its work in Pakistan.</p>
		<a class="krm-field-stories__video-link" href="<?php echo esc_url( krm_media( 'https://kingdomrevivalministries.com/wp-content/uploads/2026/08/a.mp4' ) ); ?>" target="_blank" rel="noopener noreferrer">Open video in a new tab</a>
		</div>
	</div>
	<div class="krm-field-stories__gallery" aria-label="Ministry photos" data-gallery>
		<figure>
		<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Deliverance-and-Healing-ministry-5-scaled-e1787225773575-rs6kxdo5ygbat93txitcpifrbjmk10tf6j9kjqh6qg.jpg' ); ?> alt="Deliverance and healing ministry" loading="lazy" decoding="async">
		<figcaption>Prayer and healing ministry</figcaption>
		</figure>
		<figure>
		<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Bible-Distribution-rqhhvb1bt4op61pyxtdlg5smtcfpsj957ob37u8700.png' ); ?> alt="Bible distribution" loading="lazy" decoding="async">
		<figcaption>Sharing Scripture</figcaption>
		</figure>
		<figure>
		<img <?php krm_img_src( 'https://kingdomrevivalministries.com/wp-content/uploads/elementor/thumbs/Urdu-Bible-Distribution-6-e1787296463599-rs8073jb4tpz51knz3wuum8c11mkotg8ytctdb8ink.jpg' ); ?> alt="Urdu Bible distribution" loading="lazy" decoding="async">
		<figcaption>Urdu Bible distribution</figcaption>
		</figure>
	</div>
	</div>
</section>
