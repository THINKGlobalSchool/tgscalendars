<?php
	$calendars = elgg_get_entities(array(type=>'object','subtype'=>'google_cal'));
	if (empty($calendars)) {
		echo "<p>No calendars have been added yet.</p>";
		return;
	}
	
	echo "<ul>";
	foreach ($calendars as $cal) {
		echo elgg_view('tgscalendar/admin/view_cal',array('entity'=>$cal));
	}
	echo "</ul>";
?>