<?php
/**
 * Saves a calendar
 */

$guid = get_input('guid');

elgg_make_sticky_form('tgscalendar-save');

if ($guid) {
	$entity = get_entity($guid);
	if (elgg_instanceof($entity, 'object', 'google_cal')) {
		$cal = $entity;
	} else {
		register_error(elgg_echo('tgscalendar:error:calendar_not_found'));
		forward($_SERVER['HTTP_REFERER']);
	}
} else {
	$cal = new ElggObject();
	$cal->subtype = 'google_cal';
}

$title = strip_tags(get_input('title'));
$google_cal_feed = get_input('google_cal_feed');
$text_color = get_input('text_color');
$background_color = get_input('background_color');
$access_id = get_input('access_id');

if (!($title && $google_cal_feed)) {
	register_error(elgg_echo('tgscalendar:error:missing_fields'));
	forward(REFERER);
}

$cal->title = $title;
$cal->google_cal_feed = $google_cal_feed;
$cal->text_color = $text_color;
$cal->background_color = $background_color;
$cal->access_id = $access_id;

if ($cal->save()) {
	elgg_clear_sticky_form('tgscalendar-save');
	system_message(elgg_echo('tgscalendar:save:success'));
} else {
	register_error(elgg_echo('tgscalendar:save:failed'));
}

forward(REFERER);