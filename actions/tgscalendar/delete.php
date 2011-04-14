<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (elgg_instanceof($entity, 'object', 'google_cal')) {
	$entity->delete();
} else {
	register_error(elgg_echo('tgscalendar:error:calendar_not_found'));
}

forward(REFERER);