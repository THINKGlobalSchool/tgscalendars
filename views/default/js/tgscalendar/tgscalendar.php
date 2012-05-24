<?php
/**
 * Calendar JS
 *
 * Calendards are stored as an object in elgg.tgsCalendar.calendars.
 *
 * @note The calendar JSON should never be moved here because this file is cached.
 * Leave it inline!
 */
?>
//<script>

elgg.provide('elgg.tgsCalendar');

elgg.tgsCalendar.init = function() {
	// calendars are stored in elgg.tgsCalendar.calendars.
	elgg.tgsCalendar.buildCalendar(elgg.tgsCalendar.getCalendars());

	$('.elgg-tgscalendar-calendar-toggler').live('click', elgg.tgsCalendar.toggleCalendar);
}

/**
 * Returns the calendars
 *
 * @return {Object}
 */
elgg.tgsCalendar.getCalendars = function() {
	return elgg.tgsCalendar.calendars;
}

/**
 * Builds the calendar from a JSON object
 */
elgg.tgsCalendar.buildCalendar = function(calendars) {
	$('#elgg-tgscalendar').fullCalendar({
		weekMode: 'liquid',
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		eventSources: elgg.tgsCalendar.buildSources(calendars)
	});
}

/**
 * Build array of Full Calendar gcal sources with unique class names
 *
 * @param {Object} The calendars object
 * @return {Array}
 */
elgg.tgsCalendar.buildSources = function(calendars) {
	var sources = [];
	var i = 0;
	$.each(calendars, function(k, v) {
		if (v.display) {
			sources[i] = $.fullCalendar.gcalFeed(v.url, {className: 'elgg-tgscalendar-feed-' + k});
			i++;
		}
	});

	return sources;
}

/*
 * Toggle calendar requested and rebuild display
 */
elgg.tgsCalendar.toggleCalendar = function() {
	var guid = $(this).attr('id').split('-')[2];
	var calendars = elgg.tgsCalendar.getCalendars();

	calendars[guid]['display'] = $(this).is(':checked');

	$('#elgg-tgscalendar').empty();
	elgg.tgsCalendar.buildCalendar(calendars);
}

elgg.register_hook_handler('init', 'system', elgg.tgsCalendar.init);