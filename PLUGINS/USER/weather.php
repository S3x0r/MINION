<?php
/* Copyright (c) 2013-2018, S3x0r <olisek@gmail.com>
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

//---------------------------------------------------------------------------------------------------------
PHP_SAPI !== 'cli' ? exit('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
                           Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>') : false;
//---------------------------------------------------------------------------------------------------------

    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Shows actual weather: '.$GLOBALS['CONFIG_CMD_PREFIX'].'weather <city>';
    $plugin_command = 'weather';

/*

 Plugin from 'suphpbot' adapted to minion :)

 */

function plugin_weather()
{
    if (OnEmptyArg('weather <city>')) {
    } else {
        if (extension_loaded('curl')) {
            $query = str_replace(" ", "_", $GLOBALS['args']);
            $query = urlencode($query);
            $w = json_decode(get_contents('http://api.wunderground.com/api/d05f04ecd492639d/conditions/q/'.
             $query.'.json'), true);
            if (isset($w['response']['error'])) {
                BOT_RESPONSE("weather: ".$w['response']['error']['description']);
            } elseif (isset($w['current_observation'])) {
                      $response = array(
                      $w['current_observation']['display_location']['full'],
                      'Conditions: '.$w['current_observation']['weather'],
                      'Temperature: '.$w['current_observation']['temperature_string'],
                      'Wind chill: '.$w['current_observation']['windchill_string'],
                      'Dew point: '.$w['current_observation']['dewpoint_string'],
                      'Humidity: '.$w['current_observation']['relative_humidity'],
                      'Wind: '.$w['current_observation']['wind_string'],
                      'Visibility: '.$w['current_observation']['visibility_mi'].
                      'mi, '.$w['current_observation']['visibility_km'].'km',
                      'Pressure: '.$w['current_observation']['pressure_mb'].'mb ('.
                       $w['current_observation']['pressure_in'] . 'in)',
                      'Precipitation today: '.$w['current_observation']['precip_today_string'],
                      );

                      BOT_RESPONSE(implode(', ', $response));
            } elseif (isset($w['response']['results'])) {
                      $cities = array();
                for ($i=0; ($i <count($w['response']['results'])) && ($i <10); $i++) {
                     $location = $w['response']['results'][$i];
                    if ($location['country_name'] == 'USA') {
                        $cities[] = $location['name'].','.$location['state'];
                    } else {
                             $cities[] = $location['name'].','.$location['country_name'];
                    }
                }

                  $message = "Clarify the location, for example: ";
                  $message .= implode(' - ', $cities);

                  BOT_RESPONSE($message);
            }
        } else {
                 BOT_RESPONSE('I cannot use this plugin, i need php_curl extension to work!');
        }

        CLI_MSG('[PLUGIN: weather] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                 $GLOBALS['channel'], '1');
    }
}

function get_contents($url, $post = null)
{

    $ch = curl_init();
    curl_setopt_array($ch, array(
    CURLOPT_FOLLOWLOCATION=>true,
    CURLOPT_MAXREDIRS=>5,
    CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_URL=>$url,
    CURLOPT_USERAGENT=>'irc',
    CURLOPT_CONNECTTIMEOUT=>10,
    ));
    if (is_array($post)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
