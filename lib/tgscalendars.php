<?php
/**
 * Google Calendars Helper Lib
 *
 * @package GoogleCalendars
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.org
 */

// Get the google client with configured service account credentials
function tgscalendars_get_google_service_client() {
	elgg_load_library('gapc:Client');
	$plugin = elgg_get_plugin_from_id('tgscalendars');

	$client = new Google_Client();
	$client->setApplicationName("TGS Google Calendars");

	if ($plugin->service_token) {
		$client->setAccessToken($plugin->service_token);
	}

	// Get auth/key info from plugin settings
	$key_location = elgg_get_plugin_setting('google_api_client_service_key', 'tgscalendars');
	$key_password = elgg_get_plugin_setting('google_api_client_service_key_password', 'tgscalendars');
	$service_account = elgg_get_plugin_setting('google_api_client_address', 'tgscalendars');
	$impersonate = elgg_get_plugin_setting('google_api_client_service_key_impersonate', 'tgscalendars');

	$key = file_get_contents($key_location);

	// Get credentials
	$credentials = new Google_Auth_AssertionCredentials(
		$service_account,
		array(
			'https://www.googleapis.com/auth/calendar',
			'https://www.googleapis.com/auth/calendar.readonly'
		),
		$key,
		$key_password,
		'http://oauth.net/grant_type/jwt/1.0/bearer',
		$impersonate

	);
	$client->setAssertionCredentials($credentials);

	if($client->getAuth()->isAccessTokenExpired()) {
		$client->getAuth()->refreshTokenWithAssertion($credentials);
	}
	$plugin->service_token = $client->getAccessToken();

	return $client;
}