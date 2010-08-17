<!-- relying on the Elgg copy of jQuery <script type='text/javascript' src='<?= $vars['url'].'mod/sandbox/js/' ?>jquery.js'></script>  -->
<script type='text/javascript' src='<?= $vars['url'].'mod/tgscalendar/js/' ?>fullcalendar.js'></script>
<script type='text/javascript' src='<?= $vars['url'].'mod/tgscalendar/js/' ?>gcal.js'></script>
<script type='text/javascript'>
var calendars={};
<?php
//build javascript array from calendar entities
$calendars = $vars['calendars'];
foreach($calendars as $calendar) {
	echo "calendars['{$calendar->guid}']={'url':'{$calendar->google_cal_feed}','text_color':'$calendar->text_color','background_color':'{$calendar->background_color}','display':true};\n";
}

?>

//build array of Full Calendar gcal sources with unique class names
function calSources() {
	sources = [];
	i=0;
	for (var guid in calendars) {
		if (calendars[guid]['display']) {
			sources[i] = $.fullCalendar.gcalFeed(calendars[guid]['url'],{className: 'feed'+guid})
			i++;
		}
	}
	return sources;
}

function setCalColors() {
	for (var guid in calendars) {
		$(".feed"+guid+" > a").css('background-color','#'+calendars[guid]['background_color']);
		$(".feed"+guid+" > a").css('border-color','#'+calendars[guid]['background_color']);
		$(".feed"+guid+" > a").css('color','#'+calendars[guid]['text_color']);
		
		$(".feed"+guid).css('background-color','#'+calendars[guid]['background_color']);
		$(".feed"+guid).css('border-color','#'+calendars[guid]['background_color']);
		$(".feed"+guid).css('color','#'+calendars[guid]['text_color']);
	}
}

function toggleCal(guid) {
	calendars[guid]['display'] = !(calendars[guid]['display']);
	$('#calendar').empty();
	buildCal();
}

function buildCal() {
	
  $('#calendar').fullCalendar({
    weekMode: 'liquid', 
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		}, 
		eventSources: calSources(),
		loading: function(isloading,view) { if (! isloading) setCalColors(); }
  });
}

$(document).ready(function() {
		// page is now ready, initialize the calendar...
		buildCal();

});
</script>
<div id="calendar"></div>
