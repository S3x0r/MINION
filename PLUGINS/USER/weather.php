<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows actual weather: !weather <city>';
 $plugin_command = 'weather';

/*
 
 Plugin from 'suphpbot' adapted to davybot :)

 */

function plugin_weather()
{

  if (empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'weather <city>'); } 
  
   else {

		$query = str_replace(" ","_", $GLOBALS['args']);
		$query = urlencode($query);

		$w = json_decode(get_contents('http://api.wunderground.com/api/d05f04ecd492639d/conditions/q/' . $query . '.json'),TRUE);
		
		if (isset($w['response']['error'])) {
			
			BOT_RESPONSE("Error: ".$w['response']['error']['description']);

		} elseif (isset($w['current_observation'])) {

			$response = array(
				$w['current_observation']['display_location']['full'],
				'Conditions: ' . $w['current_observation']['weather'],
				'Temperature: ' . $w['current_observation']['temperature_string'],
				'Wind chill: ' . $w['current_observation']['windchill_string'],
				'Dew point: ' . $w['current_observation']['dewpoint_string'],
				'Humidity: ' . $w['current_observation']['relative_humidity'],
				'Wind: ' . $w['current_observation']['wind_string'],
				'Visibility: ' . $w['current_observation']['visibility_mi'] . 'mi, ' . $w['current_observation']['visibility_km'] . 'km',
				'Pressure: ' . $w['current_observation']['pressure_mb'] . 'mb (' . $w['current_observation']['pressure_in'] . 'in)',
				'Precipitation today: ' . $w['current_observation']['precip_today_string'],
			);

			BOT_RESPONSE(implode(', ',$response));

		} elseif (isset($w['response']['results'])) {

			$cities = array();
			for ($i=0; ($i < count($w['response']['results'])) && ($i < 10) ; $i++) {

			$location = $w['response']['results'][$i];

				if ($location['country_name'] == 'USA' ) {

					$cities[] = $location['name'].','.$location['state'];

				} else {

					$cities[] = $location['name'].','.$location['country_name'];
				}
			}
			
			$message = "Clarify the location, for example: ";
			$message .= implode(' - ',$cities);

			BOT_RESPONSE($message);

		}
	}

	 CLI_MSG('!weather on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
}


function get_contents($url,$post=NULL) {
	$ch = curl_init();
	curl_setopt_array($ch,array(
		CURLOPT_FOLLOWLOCATION=>TRUE,
		CURLOPT_MAXREDIRS=>5,
		CURLOPT_RETURNTRANSFER=>TRUE,
		CURLOPT_URL=>$url,
		CURLOPT_USERAGENT=>'irc',
		CURLOPT_CONNECTTIMEOUT=>10,
	));
	if (is_array($post)) {
		curl_setopt($ch,CURLOPT_POST,TRUE);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
	}
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}


?>