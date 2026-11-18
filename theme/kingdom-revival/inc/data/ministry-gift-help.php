<?php
/**
 * "How Your Gift Helps" data for each ministry: three amounts ($25, $50, $100) with a short description.
 *
 * Key is the ministry slug. Each entry has three amounts, each with a short description.
 * This data drives the interactive gift selector on ministry pages (template-parts/ministry/gift-selector.php).
 *
 * @package Kingdom_Revival
 */

defined( 'ABSPATH' ) || exit;

return array(
	'evangelism-outreach' => array(
		25  => 'Helps provide Gospel outreach materials.',
		50  => 'Supports evangelism visits to local communities.',
		100 => 'Helps organize community Gospel outreach.',
	),
	'slavery-freedom' => array(
		25  => 'Helps provide essential family supplies.',
		50  => 'Supports families trapped in debt bondage.',
		100 => 'Contributes towards family freedom and recovery.',
	),
	'brick-kiln-family-care' => array(
		25  => 'Helps provide shoes and clothing for children.',
		50  => 'Provides food and essential supplies for families.',
		100 => 'Supports care and outreach for brick kiln communities.',
	),
	'children-orphan-care' => array(
		25  => 'Helps provide school supplies.',
		50  => 'Supports food and clothing for children.',
		100 => 'Helps meet children\'s educational and basic needs.',
	),
	'clean-water' => array(
		25  => 'Helps provide clean water essentials.',
		50  => 'Contributes towards community water projects.',
		100 => 'Supports hand-pump installation costs.',
	),
	'persecuted-christians' => array(
		25  => 'Helps provide emergency essentials.',
		50  => 'Supports food assistance for affected families.',
		100 => 'Helps families recover from persecution-related hardship.',
	),
	'widows-support' => array(
		25  => 'Provides essential household supplies.',
		50  => 'Helps provide food assistance.',
		100 => 'Supports vulnerable widows and their families.',
	),
	'medical-health' => array(
		25  => 'Helps provide essential medicines.',
		50  => 'Supports basic medical assistance.',
		100 => 'Helps organize community health outreach.',
	),
	'food-relief' => array(
		25  => 'Helps provide a basic food package.',
		50  => 'Supports food assistance for a family.',
		100 => 'Helps feed multiple vulnerable families.',
	),
	'disaster-relief' => array(
		25  => 'Provides emergency essentials.',
		50  => 'Helps provide food and hygiene supplies.',
		100 => 'Supports families affected by disasters.',
	),
	'church-planting' => array(
		25  => 'Helps provide church resources.',
		50  => 'Supports Bibles and discipleship materials.',
		100 => 'Helps establish and strengthen house churches.',
	),
	'bible-distribution' => array(
		25  => 'Helps provide Bibles to new believers.',
		50  => 'Supports Bible distribution in communities.',
		100 => 'Helps provide Scripture to more families.',
	),
	'leadership-training' => array(
		25  => 'Provides pastoral training materials.',
		50  => 'Supports leadership development.',
		100 => 'Helps organize leadership training sessions.',
	),
	'baptisms-discipleship' => array(
		25  => 'Provides discipleship materials.',
		50  => 'Supports baptism-related practical needs.',
		100 => 'Helps organize discipleship gatherings.',
	),
	'prayer-deliverance' => array(
		25  => 'Helps provide prayer ministry materials.',
		50  => 'Supports prayer meetings and gatherings.',
		100 => 'Helps organize prayer and deliverance outreach.',
	),
);
