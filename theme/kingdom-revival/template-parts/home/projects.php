<?php
/**
 * Home: the first three current projects (krm_projects()), in the giving-card style of the section above it.
 * Hidden when there are no current projects (visitors never get the data file's sample projects: krm_projects()).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

$krm_projects = krm_projects( 'current', 3 );
if ( ! $krm_projects ) {
	return;
}
?>
<!-- Current projects -->
<section id="projects" aria-labelledby="projects-h" style="max-width: 1240px; margin: 0 auto; padding: clamp(72px, 8vw, 110px) clamp(16px, 3vw, 32px)">
<div style="display: flex; flex-wrap: wrap; gap: 12px 48px; justify-content: space-between; align-items: flex-end; margin-bottom: 32px">
<div>
<?php krm_admin_only_note( ! array_filter( wp_list_pluck( $krm_projects, 'placeholder' ), function ( $v ) { return ! $v; } ) ); // phpcs:ignore Generic.Formatting.MultipleStatementAlignment ?>
<p style="margin: 0 0 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--color-secondary)"><?php krm_t( 'projects.eyebrow' ); ?></p>
<h2 id="projects-h" style="margin: 0; font-size: clamp(30px, 3.4vw, 40px); font-weight: 800; line-height: 1.15; color: var(--color-primary)"><?php krm_h( 'projects.heading' ); ?></h2>
</div>
<a href="<?php echo esc_url( krm_donate_url() . '#projects' ); ?>" style="font-weight: 700; min-height: 44px; display: inline-flex; align-items: center"><?php krm_t( 'projects.all_label' ); ?></a>
</div>
<div class="ministry-grid">
<?php
foreach ( $krm_projects as $krm_i => $krm_p ) {
	echo '<div style="display: flex; min-width: 0">';
	get_template_part(
		'template-parts/project-card',
		null,
		array(
			'project' => $krm_p,
			'index'   => $krm_i,
		)
	);
	echo '</div>';
}
?>
</div>
</section>
