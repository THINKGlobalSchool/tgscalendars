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
	// css
	$url = elgg_get_simplecache_url('css', 'tgscalendar/fullcalendar');
	elgg_register_css('tgs:fullcalendar', $url, 100);

	$url = elgg_get_simplecache_url('css', 'tgscalendar/css');
	elgg_register_css('tgs:calendar', $url, 200);

	// this is a special url that calls the views and builds the colors for the calendars
	// it is not cached.
	$url = 'pg/ajax/view/tgscalendar/calendars_css';
	elgg_register_css('tgs:calendars_css', $url);

	// js
	$url = elgg_get_simplecache_url('js', 'tgscalendar/fullcalendar.min');
	elgg_register_js('tgs:fullcalendar', $url, 'head', 100);

	$url = elgg_get_simplecache_url('js', 'tgscalendar/gcal');
	elgg_register_js('tgs:gcal', $url, 'head', 200);

	$url = elgg_get_simplecache_url('js', 'tgscalendar/tgscalendar');
	elgg_register_js('tgs:calendar', $url);
	
	// menus
	elgg_register_menu_item('site', array(
		'name' => 'tgscalendar',
		'href' => 'pg/calendar/',
		'text' => elgg_echo('tgscalendar:calendars')
	));

	elgg_register_admin_menu_item('configure', 'tgscalendar', 'appearance');

	// handlers
	register_page_handler('calendar', 'tgscalendar_page_handler');

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
	
	$calendars = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'google_cal'
	));

	// register page menu items for each calendar
	foreach ($calendars as $calendar) {
		$guid = $calendar->getGUID();
		$input = elgg_view('input/checkbox', array(
			'id' => 'elgg-tgscalendar-' . $guid,
			'class' => 'right elgg-tgscalendar-calendar-toggler',
			'checked' => 'checked'
		));
		$text = "<label>$calendar->title$input</label>";
		
		elgg_register_menu_item('tgscalendar-sidebar', array(
			'name' => 'elgg-tgscalendar-' . $guid,
			'text' => $text,
			'href' => false,
			'item_class' => 'pam mvm elgg-tgscalendar-feed elgg-tgscalendar-feed-' . $guid
		));
	}
	
	$content = elgg_view('tgscalendar/calendar', array('calendars' => $calendars));

	$sidebar = elgg_view_title(elgg_echo('tgscalendar:calendars'));
	$sidebar .= elgg_view_menu('tgscalendar-sidebar');
	
	$body = elgg_view_layout('content', array(
		'content' => $content
	));

	$body = elgg_view_layout('one_sidebar', array(
		'filter_context' => 'all',
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar
	));
	
	echo elgg_view_page($title, $body);
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

register_elgg_event_handler('init', 'system', 'tgscalendar_init');