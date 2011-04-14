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
	$url = elgg_get_simplecache_url('css', 'tgscalendar/css');
	elgg_register_css('tgs:gcal', $url);

	$url = elgg_get_simplecache_url('css', 'tgscalendar/fullcalendar');
	elgg_register_css('tgs:fullcalendar', $url);

	// js
	$url = elgg_get_simplecache_url('js', 'tgscalendar/fullcalendar.min');
	elgg_register_js('tgs:fullcalendar', $url, 'head', 100);

	$url = elgg_get_simplecache_url('js', 'tgscalendar/gcal');
	elgg_register_js('tgs:gcal', $url, 'head', 200);

	$url = elgg_get_simplecache_url('js', 'tgscalendar/tgscalendar');
	elgg_register_js('tgs:calendar', $url);
	
	// menus
	elgg_register_menu_item('site', array(
		'url' => 'pg/calendar/',
		'text' => elgg_echo('tgscalendar:calendars')
	));

	// handlers
	register_page_handler('calendar', 'tgscalendar_page_handler');
	register_page_handler('calendar_admin', 'tgscalendar_admin_page_handler');

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
		$text = "<label class=\"mhs\" >$calendar->title$input</label>";
		
		elgg_register_menu_item('tgscalendar-sidebar', array(
			'name' => 'elgg-tgscalendar-' . $guid,
			'text' => $text,
			'href' => false,
			'class' => 'elgg-tgscalendar',
			'item_class' => 'pvm mvm elgg-tgscalendar-feed elgg-tgscalendar-feed-' . $guid
		));
	}
	
	//$sidebar = elgg_view('tgscalendar/sidebar', array('calendars' => $calendars));
	$content = elgg_view('tgscalendar/calendar', array('calendars' => $calendars));

	$sidebar = elgg_view_title(elgg_echo('tgscalendar:calendars'));
	$sidebar .= elgg_view_menu('tgscalendar-sidebar', array(

	));
	
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

function tgscalendar_admin_page_handler($page) {
	global $CONFIG;
	admin_gatekeeper();

	set_context('admin');
	
	$title = elgg_echo('tgscalendar:admin_title');
	
	$content = elgg_view_title(elgg_echo('tgscalendar:admin_title'));
	$content .= elgg_view('tgscalendar/admin/cal_list');
	
	//check to see if we want to edit an entry
	$guid = get_input('guid');
	$entity = get_entity($guid);
	$content .= elgg_view('tgscalendar/admin/edit',array('entity'=>$entity));
	
	$body = elgg_view_layout('administration', array('content' => $content));
	echo elgg_view_page($title, $body, 'admin');
}

register_elgg_event_handler('init', 'system', 'tgscalendar_init');