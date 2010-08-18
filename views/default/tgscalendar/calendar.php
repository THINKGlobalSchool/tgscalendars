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

//add in css properties for the feeds as stored in the entity
function setCalColors() {
	for (var guid in calendars) {
		
		//css pattern from Full Calendar documentation
		selector = ".feed"+guid+" a,.feed"+guid+",.fc-agenda .feed"+guid+" .fc-event-time";
		$(selector).css('background-color','#'+calendars[guid]['background_color']);
		$(selector).css('border-color','#'+calendars[guid]['background_color']);
		$(selector).css('color','#'+calendars[guid]['text_color']);	
	}
}

//toggle calendar requested and rebuild display
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
		setCalColors();
		// page is now ready, initialize the calendar...
		buildCal();
		
		//bit of a hack - but it's the only event that keeps the colors set consistently
		$('#calendar').click(function() {
			setCalColors();
		});

});
</script>
<div id="calendar"></div>
<!-- For debug><p><a href="javascript:setCalColors()">Set Colors</a></p> -->
