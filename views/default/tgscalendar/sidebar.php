<?php
/**
 * The sidebar view for the
 */
?>
<h3><?= elgg_echo('tgscalendar:calendars'); ?></h3>

<?php

	$calendars=$vars['calendars'];
	
	foreach($calendars as $cal) {
		$output = "<div class='cal_label feed{$cal->guid}'>{$cal->title}";
		$output .= "<span class='cal_toggle'><input type='checkbox' id='cal{$cal->guid}' checked onclick='toggleCal({$cal->guid});'></span>";
		$output .= "</div>";
		echo $output;
	}

?>