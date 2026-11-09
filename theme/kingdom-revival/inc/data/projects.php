<?php
/**
 * Projects for Donate → "Our Projects" and the home page: the sample entries shown until the first real project is
 * added in the dashboard (Projects → Add New). As soon as one project is published, this file is no longer used.
 *
 * Per project: title, ministry (slug from inc/data/ministries.php), status (current | upcoming), goal and raised
 * (whole dollars, '' = not set), date (planned start for upcoming projects, free text), image, text, page (optional:
 * key of its detail page in inc/data/project-pages.php, adds "Learn more"). Title: <span class="hl"> highlights words.
 * These are DUMMY projects (names, goals and amounts are made up): replace them with real ones before launch.
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'title'    => 'Urdu Bibles for <span class="hl">New Believers</span>',
		'page'     => 'urdu-bibles',
		'ministry' => 'bible-distribution',
		'status'   => 'current',
		'goal'     => '6000',
		'raised'   => '2400',
		'date'     => '',
		'image'    => '',
		'text'     => 'Printing and delivering Urdu Bibles to new believers and village churches around Faisalabad and Jhang.',
	),
	array(
		'title'    => 'Freedom for <span class="hl">Bonded Kiln Families</span>',
		'page'     => 'freedom-for-kiln-families',
		'ministry' => 'slavery-freedom',
		'status'   => 'current',
		'goal'     => '15000',
		'raised'   => '6200',
		'date'     => '',
		'image'    => '',
		'text'     => 'Paying off the debts of ten brick kiln families and helping each one start again with work and a home.',
	),
	array(
		'title'    => 'Building a <span class="hl">Home</span> for Orphaned Children',
		'page'     => 'orphanage-home',
		'ministry' => 'children-orphan-care',
		'status'   => 'current',
		'goal'     => '12000',
		'raised'   => '4800',
		'date'     => '',
		'image'    => '',
		'text'     => 'We are building an orphanage where orphaned and underprivileged children can receive shelter, meals, schooling and daily care.',
	),
	array(
		'title'    => 'Hand pumps for five villages',
		'ministry' => 'clean-water',
		'status'   => 'upcoming',
		'goal'     => '9000',
		'raised'   => '0',
		'date'     => 'January 2027',
		'image'    => '',
		'text'     => 'Installing five new hand pumps so families no longer walk for unsafe water.',
	),
	array(
		'title'    => 'A church building in a village near Jhang',
		'ministry' => 'church-planting',
		'status'   => 'upcoming',
		'goal'     => '10000',
		'raised'   => '0',
		'date'     => 'March 2027',
		'image'    => '',
		'text'     => 'A simple, permanent meeting place for a growing house church.',
	),
);
