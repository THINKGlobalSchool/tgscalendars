<?php
$cal = $vars['entity'];

//set up defaults
if ($cal) { //we're editing

	$values = array(
		'form_title' => elgg_echo('tgscalendar:edit_cal_title'),
		'title' => $cal->title,
		'google_cal_feed' => $cal->google_cal_feed,
		'cal_text_color' => $cal->text_color,
		'cal_background_color' => $cal->background_color,
		'access_id' => $cal->access_id,
		'guid' => $cal->guid
		);
} else {  //set up defaults
	
	$values = array(
		'form_title' => elgg_echo('tgscalendar:add_cal_title'),
		'title' => NULL,
		'google_cal_feed' => NULL,
		'cal_text_color' => 'FFFFFF',
		'cal_background_color' => '000000',
		'access_id' => ACCESS_DEFAULT,
		'guid' => NULL
		);	
}




//set up form components
$form_title = elgg_view_title($values['form_title']);

$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'internalname' => 'title',
	'internalid' => 'calendar_title',
	'value' => $values['title']
));

$cal_feed_label = elgg_echo('tgscalendar:feed_label');
$cal_feed_input = elgg_view('input/text', array(
	'internalname' => 'google_cal_feed',
	'internalid' => 'google_cal_feed',
	'value' => $values['google_cal_feed']
));

$text_color_label = elgg_echo('tgscalendar:text_color_label');
$text_color_input = elgg_view('input/text', array(
	'internalname' => 'cal_text_color',
	'internalid' => 'cal_text_color',
	'value' => $values['cal_text_color']
));

$background_color_label = elgg_echo('tgscalendar:background_color_label');
$background_color_input = elgg_view('input/text', array(
	'internalname' => 'cal_background_color',
	'internalid' => 'cal_background_color',
	'value' => $values['cal_background_color']
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
	'internalname' => 'access_id',
	'internalid' => 'calendar_access_id',
	'value' => $values['access_id']
));

$guid_input = elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $values['guid']));

$save_button = elgg_view('input/submit', array('value' => elgg_echo('save'), 'class' => 'submit_button'));

//layout form
$form_body = <<<___END
$form_title

<p>
	<label for="calendar_title">$title_label</label>
	$title_input
</p>

<p>
	<label for="google_cal_feed">$cal_feed_label</label>
	$cal_feed_input
</p>	

<p>
	<label for="cal_text_color">$text_color_label</label>
	$text_color_input
</p>

<p>
	<label for="cal_text_color">$background_color_label</label>
	$background_color_input
</p>

<p>
	<label for="calendar_access_id">$access_label</label>
	$access_input
</p>

$guid_input

$save_button


___END;

//display form
echo elgg_view('input/form', array(
	'internalid' => 'calendar_edit',
	'internalname' => 'calendar_edit',
	'action' => "{$vars['url']}action/tgscalendar/save",
	'body' => $form_body
));


?>

