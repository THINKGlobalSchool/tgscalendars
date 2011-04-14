<?php
/**
 * Draws the calendar using JS.
 */

elgg_load_js('tgs:fullcalendar');
elgg_load_js('tgs:gcal');
elgg_load_js('tgs:calendar');

elgg_load_css('tgs:gcal');
elgg_load_css('tgs:fullcalendar');
elgg_load_css('tgs:calendar_css');

//build javascript array from calendar entities
$calendars = $vars['calendars'];
$info = array();
foreach($calendars as $calendar) {
	$info[$calendar->getGUID()] = array(
		'url' => $calendar->google_cal_feed,
		'text_color' => $calendar->text_color,
		'background_color' => $calendar->background_color,
		'display' => true
	);
}

$json = json_encode($info);
?>
<script type='text/javascript'>
	// the elgg JS object is already loaded at this point, so we can't do this without wrapping in
	// $() or hooks.
	// kids, don't try this at home.
	elgg.provide('elgg.tgsCalendar');

	elgg.tgsCalendar.calendars = <?php echo $json; ?>;
</script>

<div class="mtl" id="elgg-tgscalendar"></div>
<!-- For debug><p><a href="javascript:setCalColors()">Set Colors</a></p> -->
