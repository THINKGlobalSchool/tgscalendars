<?php
/**
 * Google Calendars Plugin Settings
 *
 * @package GoogleCalendars
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */

// Get plugin settings
$google_api_client_id  = elgg_get_plugin_setting('google_api_client_id', 'tgscalendars');
$google_api_client_address  = elgg_get_plugin_setting('google_api_client_address', 'tgscalendars');
$google_api_client_service_key = elgg_get_plugin_setting('google_api_client_service_key', 'tgscalendars');
$google_api_client_service_key_password = elgg_get_plugin_setting('google_api_client_service_key_password', 'tgscalendars');
$google_api_client_service_impersonate = elgg_get_plugin_setting('google_api_client_service_key_impersonate', 'tgscalendars');

// Labels & Inputs
$google_api_client_label = elgg_echo('tgscalendar:admin:api_client_id');
$google_api_client_input = elgg_view('input/text', array(
	'name' => 'params[google_api_client_id]',
	'value' => $google_api_client_id
));

$google_api_client_service_label= elgg_echo('tgscalendar:admin:service_address');
$google_api_client_service_input = elgg_view('input/text', array(
	'name' => 'params[google_api_client_address]',
	'value' => $google_api_client_address
));

$google_api_client_service_key_label = elgg_echo('tgscalendar:admin:keylocation');
$google_api_client_service_key_input = elgg_view('input/text', array(
	'name' => 'params[google_api_client_service_key]',
	'value' => $google_api_client_service_key
));

$google_api_client_service_key_password_label = elgg_echo('tgscalendar:admin:keypassword');
$google_api_client_service_key_password_input = elgg_view('input/text', array(
	'name' => 'params[google_api_client_service_key_password]',
	'value' => $google_api_client_service_key_password
));

$google_api_client_service_impersonate_label = elgg_echo('tgscalendar:admin:impersonate');
$google_api_client_service_impersonate_input = elgg_view('input/text', array(
	'name' => 'params[google_api_client_service_key_impersonate]',
	'value' => $google_api_client_service_impersonate
));

// Authentication/Authorization Module
$auth_title = elgg_echo('tgscalendar:admin:authentication');
$auth_body = <<<HTML
	<div>
		<label>$google_api_client_label</label><br />
		$google_api_client_input
	</div><br />
	<div>
		<label>$google_api_client_service_label</label><br />
		$google_api_client_service_input
	</div><br />
	<div>
		<label>$google_api_client_service_key_label</label><br />
		$google_api_client_service_key_input
	</div><br />
	<div>
		<label>$google_api_client_service_key_password_label</label><br />
		$google_api_client_service_key_password_input
	</div><br />
	<div>
		<label>$google_api_client_service_impersonate_label</label><br />
		$google_api_client_service_impersonate_input
	</div><br />
HTML;
echo elgg_view_module('inline', $auth_title, $auth_body);