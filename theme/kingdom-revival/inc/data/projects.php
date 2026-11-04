<?php
/**
 * Projects for Donate → "Our Projects" and the home page: the sample entries shown until the first real project is
 * added in the dashboard (Projects → Add New). As soon as one project is published, this file is no longer used.
 *
 * Per project: title, ministry (slug from inc/data/ministries.php), status (current | upcoming), goal and raised
 * (whole dollars, '' = not set), date (planned start for upcoming projects, free text), image, text.
 * Everything in [square brackets] is a placeholder: the client has not supplied any projects yet.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'title'    => '[Current project 1 — name to be supplied by the client]',
		'ministry' => 'bible-distribution',
		'status'   => 'current',
		'goal'     => '',
		'raised'   => '',
		'date'     => '',
		'image'    => '',
		'text'     => '[Project description — to be supplied by the client]',
	),
	array(
		'title'    => '[Current project 2 — name to be supplied by the client]',
		'ministry' => 'slavery-freedom',
		'status'   => 'current',
		'goal'     => '',
		'raised'   => '',
		'date'     => '',
		'image'    => '',
		'text'     => '[Project description — to be supplied by the client]',
	),
	array(
		'title'    => '[Current project 3 — name to be supplied by the client]',
		'ministry' => 'children-orphan-care',
		'status'   => 'current',
		'goal'     => '',
		'raised'   => '',
		'date'     => '',
		'image'    => '',
		'text'     => '[Project description — to be supplied by the client]',
	),
	array(
		'title'    => '[Upcoming project 1 — name to be supplied by the client]',
		'ministry' => 'clean-water',
		'status'   => 'upcoming',
		'goal'     => '',
		'raised'   => '',
		'date'     => '[Planned date]',
		'image'    => '',
		'text'     => '[Project description — to be supplied by the client]',
	),
	array(
		'title'    => '[Upcoming project 2 — name to be supplied by the client]',
		'ministry' => 'church-planting',
		'status'   => 'upcoming',
		'goal'     => '',
		'raised'   => '',
		'date'     => '[Planned date]',
		'image'    => '',
		'text'     => '[Project description — to be supplied by the client]',
	),
);
