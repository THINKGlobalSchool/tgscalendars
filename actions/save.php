<?php


$guid = get_input('guid');
if ($guid) {
	$entity = get_entity($guid);
	if (elgg_instanceof($entity, 'object', 'google_cal')) {
		$cal = $entity;
	} else {
		register_error(elgg_echo('blog:error:post_not_found'));
		forward($_SERVER['HTTP_REFERER']);
	}
} else {
	$cal = new ElggObject();
	$cal->subtype = 'google_cal';
}

$title = strip_tags(get_input('title'));
$google_cal_feed = get_input('google_cal_feed');
$text_color = get_input('cal_text_color');
$background_color = get_input('cal_background_color');
$access_id = get_input('access_id');

$cal->title = $title;
$cal->google_cal_feed = $google_cal_feed;
$cal->text_color = $text_color;
$cal->background_color = $background_color;
$cal->access_id = $access_id;

if ($cal->save()) {
	system_message(elgg_echo('tgscalendar:save:success'));
} else {
	register_error(elgg_echo('tgscalendar:save:failed'));
}
forward($vars['url'].'pg/calendar_admin');

?>