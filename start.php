<?php
/*
 * TGS Calendar plugin
 *
 * Provides capability of displaying multiple Google calendar feeds on a 
 * central calendar for users.
 *
 * This plugin relies on the Full Calendar jQuery plugin
 * Documentation available at http://arshaw.com/fullcalendar/docs/
 */

/**
 * Init
 */
function tgscalendar_init() {
	// Register and load library
	elgg_register_library('elgg:tgscalendars', elgg_get_plugins_path() . 'tgscalendars/lib/tgscalendars.php');
	elgg_load_library('elgg:tgscalendars');
	
	// css
	$url = elgg_get_simplecache_url('css', 'fullcalendar');
	elgg_register_simplecache_view('css/fullcalendar');
	elgg_register_css('tgs.fullcalendar', $url, 100);

	$url = elgg_get_simplecache_url('css', 'tgscalendar/css');
	elgg_register_simplecache_view('css/tgscalendar/css');
	elgg_register_css('tgs:calendar', $url, 200);

	// this is a special url that calls the views and builds the colors for the calendars
	// it is not cached.
	$url = 'ajax/view/tgscalendar/calendars_css';
	elgg_register_css('tgs:calendars_css', $url, 999);

	// js
	$url = elgg_get_simplecache_url('js', 'tgscalendar/fullcalendar.min');
	elgg_register_simplecache_view('js/tgscalendar/fullcalendar.min');
	elgg_register_js('tgs:fullcalendar', $url, 'head', 900);

	$url = elgg_get_simplecache_url('js', 'tgscalendar/spotgcal');
	elgg_register_simplecache_view('js/tgscalendar/spotgcal');
	elgg_register_js('tgs:gcal', $url, 'head', 1000);

	$url = elgg_get_simplecache_url('js', 'tgscalendar/tgscalendar');
	elgg_register_simplecache_view('js/tgscalendar/tgscalendar');
	elgg_register_js('tgs:calendar', $url);

	// Whitelist ajax views
	elgg_register_ajax_view('tgscalendar/calendars_css');
	
	// Register menu for logged in users
	if (elgg_is_logged_in()) {
		elgg_register_menu_item('site', array(
			'name' => 'tgscalendar',
			'href' => 'calendar/',
			'text' => elgg_echo('tgscalendar:calendars')
		));
	}

	elgg_register_admin_menu_item('configure', 'tgscalendar', 'appearance');

	// handlers
	elgg_register_page_handler('calendar', 'tgscalendar_page_handler');

	// actions
	$action_path = dirname(__FILE__) . '/actions/tgscalendar';
	
	elgg_register_action('tgscalendar/save', "$action_path/save.php");
	elgg_register_action('tgscalendar/delete', "$action_path/delete.php");
}

/**
 * Serves a single page: the calendar.
 *
 * @param array $page
 */
function tgscalendar_page_handler($page) {
	gatekeeper();

	if ($page[0] == 'load') {
		$id = get_input('id', FALSE);
		$start_date = get_input('start_date', FALSE);
		$end_date = get_input('end_date', FALSE);
		$class_name = get_input('class_name', FALSE);

		if (!$id) {
			return FALSE;
		} else {
			echo json_encode(tgscalendars_get_events($id, $class_name, $start_date, $end_date));
		}
	} else {
		$calendars = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'google_cal'
		));

		$title = elgg_echo('tgscalendar:tgscalendars');

		// register page menu items for each calendar
		foreach ($calendars as $calendar) {
			$guid = $calendar->getGUID();
			$input = elgg_view('input/checkbox', array(
				'id' => 'elgg-tgscalendar-' . $guid,
				'class' => 'right elgg-tgscalendar-calendar-toggler',
				'checked' => 'checked'
			));
			$text = "<label>$calendar->title</label>$input";
			
			elgg_register_menu_item('tgscalendar-filter', array(
				'name' => 'elgg-tgscalendar-' . $guid,
				'text' => $text,
				'href' => false,
				'item_class' => 'pas mrs elgg-tgscalendar-feed elgg-tgscalendar-feed-' . $guid
			));
		}

		$content .= elgg_view_menu('tgscalendar-filter', array(
			'class' => 'elgg-menu-hz'
		));

		$content = "<div class='elgg-head clearfix'><h2 class='elgg-heading-main'>{$title}</h2></div>";

		$content .= elgg_view_menu('tgscalendar-filter', array(
			'class' => 'elgg-menu-hz'
		));

		$content .= elgg_view('tgscalendar/calendar', array('calendars' => $calendars));

		$body = elgg_view_layout('one_column', array(
			'filter' => '',
			'content' => $content,
			'title' => '',
		));

		echo elgg_view_page($title, $body);
	}
	return TRUE;
}

/**
 * Prepare form vars optionally from an entity
 *
 * @param mixed $entity 
 * @return arary
 */
function tgscalendar_prepare_form_vars($calendar = null) {
	// input names => defaults
	$values = array(
		'title' => '',
		'google_cal_feed' => '',
		'text_color' => '',
		'background_color' => '',
		'access_id' => ACCESS_DEFAULT,
		'guid' => ''
	);

	if (elgg_is_sticky_form('tgscalendar-save')) {
		foreach (array_keys($values) as $field) {
			$values[$field] = elgg_get_sticky_value('tgscalendar-save', $field);
		}
	}

	elgg_clear_sticky_form('tgscalendar-save');

	if (!$calendar) {
		return $values;
	}

	foreach (array_keys($values) as $field) {
		if (isset($calendar->$field)) {
			$values[$field] = $calendar->$field;
		}
	}

	$values['entity'] = $calendar;
	return $values;
}

elgg_register_event_handler('init', 'system', 'tgscalendar_init');