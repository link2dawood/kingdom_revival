<?php
/**
 * What We Do (Phase 21): the ministry archive serves two kinds of page.
 *  - /what-we-do/          the overview: template-parts/what-we-do/overview.php (group cards, a preview row of each
 *                          group's ministries, the closing banner);
 *  - /what-we-do/{group}/  one ministry group: template-parts/what-we-do/group.php (intro, the group's ministry cards,
 *                          "Your gift at work", a card to the other group, the closing banner).
 * The group comes from the krm_group query variable (inc/ministry.php routes the address).
 *
 * Text: Kingdom Revival → Site text → What We Do page and What We Do groups. Cards: each ministry's own fields.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

get_header();

$krm_group = krm_current_group();
?>
	<main id="main">
	<?php
	if ( $krm_group ) {
		get_template_part( 'template-parts/what-we-do/group', null, array( 'group' => $krm_group ) );
	} else {
		get_template_part( 'template-parts/what-we-do/overview' );
	}
	?>
	<div class="krm-design">
		<?php get_template_part( 'template-parts/home/cta', null, array( 'screen' => 'home' ) ); ?>
	</div>
	</main>
<?php
get_footer();
