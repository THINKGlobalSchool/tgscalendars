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

/**
 * Get events from calendar api
 *
 * @param string $calendar_id Calendar ID
 * @param string $class_name  Class name for display in fullcalendar
 * @param string $start_date  Event upper limit
 * @param string $end_date    Event lower limit
 * @return array
 */
function tgscalendars_get_events($calendar_id, $class_name = FALSE, $start_date = FALSE, $end_date = FALSE) {
	if (!$calendar_id) {
		return FALSE;
	}

	// Get google client and load calendar API
	elgg_load_library('gapc:Client');
	elgg_load_library('gapc:Calendar');
	$client = tgscalendars_get_google_service_client();

	$service = new Google_Service_Calendar($client);

	$optParams = array(
		'showDeleted' => 0,
		'maxResults' => '2500',
		'singleEvents' => 1
	);

	// Attempt to create DateTime objects with given date strings
	$start_date = $start_date ? new DateTime($start_date) : FALSE;
	$end_date =  $end_date ? new DateTime($end_date) : FALSE;

	// If we've got dates, format then ISO-8602 (2014-12-07T00:00:00Z)
	if ($start_date) {
		$optParams['timeMin'] = $start_date->format('c');
	}

	if ($end_date) {
		$optParams['timeMax'] = $end_date->format('c');
	}

	$events = $service->events->listEvents($calendar_id, $optParams);

	$event_array = array();

	foreach ($events->getItems() as $item) {
		// Determine if this is an all day event		
		$allDay = FALSE;
		if (!$item->getStart()->getDateTime()) {
			$allDay = true;
		}

		// Figure out start date
		$start_string = $item->getStart()->getDateTime() ? $item->getStart()->getDateTime() : $item->getStart()->getDate();

		// Figure out end date
		$end_string = $item->getEnd()->getDateTime() ? $item->getEnd()->getDateTime() : $item->getEnd()->getDate();

		// Format dates
		$start = new DateTime($start_string);
		$end = new DateTime($end_string);

		// Add event to event array
		$event_array[] = array(
			'id' => $item->getId(),
			'title' => $item->getSummary(),
			'url' => $item->getHtmlLink(),
			'start' => $start->format('c'),
			'end' => $end->format('c'),
			'allDay' => $allDay,
			'location' => $item->getLocation(),
			'description' => $item->getDescription(),
			'className' => $class_name,
			'editable' => false,
		);
	}

	return $event_array;
}