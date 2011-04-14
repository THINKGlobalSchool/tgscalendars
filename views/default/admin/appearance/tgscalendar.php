<?php
/*
 * Calendar admin
 */

// want the colors
elgg_load_css('tgs:calendars_css');
elgg_load_css('tgs:calendar');

// show the existing calendars
$calendars = elgg_get_entities(array(
	'type' => 'object',
	'subtype'=>'google_cal'
));

if ($calendars) {
	echo '<ul class="elgg-tgscalendar-admin">';
	foreach($calendars as $cal) {
		echo elgg_view_entity($cal);
	}
	echo '</ul>';
}  else {
	echo '<p>' . elgg_echo('tgscalendar:admin:no_calendars') . '</p>';
}

// show a new form
$guid = get_input('guid');
$entity = get_entity($guid);

if (elgg_instanceof($entity, 'object', 'google_cal')) {
	$vars = tgscalendar_prepare_form_vars($entity);
} else {
	$vars = tgscalendar_prepare_form_vars();
}

echo elgg_view_form('tgscalendar/save', array(), $vars);