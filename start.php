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

function tgscalendar_init() {
	global $CONFIG;
	
	elgg_extend_view('css','tgscalendar/fullcalendar_css');
	elgg_extend_view('css','tgscalendar/css');
	elgg_extend_view('css','tgscalendar/cal_css');
	elgg_extend_view('layouts/administration','tgscalendar/admin/css');
	
	add_menu(elgg_echo('tgscalendar:calendars'), "{$CONFIG->wwwroot}pg/calendar/", array());
	
	register_page_handler('calendar','tgscalendar_page_handler');
	register_page_handler('calendar_admin','tgscalendar_admin_page_handler');
	
	register_action('tgscalendar/save',false, $CONFIG->pluginspath . 'tgscalendar/actions/save.php');
	register_action('tgscalendar/delete',false,$CONFIG->pluginspath . 'tgscalendar/actions/delete.php');
	
	register_elgg_event_handler('pagesetup','system','tgscalendar_admin_submenus');
}
register_elgg_event_handler('init','system','tgscalendar_init');


function tgscalendar_admin_submenus() {
	global $CONFIG;
	
	elgg_add_submenu_item(array('text'=>elgg_echo('tgscalendar:admin_title'),'href'=>"{$CONFIG->url}pg/calendar_admin",'id'=>'calendar_admin'),'admin');
}

function tgscalendar_page_handler($page) {
	gatekeeper();
	
	$calendars = elgg_get_entities(array('type'=>'object','subtype'=>'google_cal'));
	$sidebar = elgg_view('tgscalendar/sidebar', array('calendars'=>$calendars));
	$content = elgg_view('tgscalendar/calendar', array('calendars'=>$calendars));
	
	$body = elgg_view_layout('one_column_with_sidebar', $content, $sidebar);
	echo elgg_view_page($title, $body);
}

function tgscalendar_admin_page_handler($page) {
	global $CONFIG;
	admin_gatekeeper();
	elgg_admin_add_plugin_settings_sidemenu();
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