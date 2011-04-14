<?php
/* 
 * Generates the CSS for calendars
 */
header('Content-type: text/css', true);

$calendars = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'google_cal',
	'limit' => 0
));

foreach ($calendars as $calendar) {
	$bg_color = $calendar->background_color;
	$color = $calendar->text_color;
	$guid = $calendar->getGUID();
	
echo <<<___CSS
	.elgg-tgscalendar-feed-$guid a,
	.elgg-tgscalendar-feed-$guid,
	.fc-agenda .elgg-tgscalendar-feed-$guid .fc-event-time {
		background-color: #$bg_color;
		border-color: #$bg_color;
		color: #$color
	}

___CSS;
}