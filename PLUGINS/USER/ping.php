<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Pings a host: '.$GLOBALS['CONFIG_CMD_PREFIX'].'ping <host>';
    $plugin_command = 'ping';

function plugin_ping()
{
    try {
    if (OnEmptyArg('ping <host>')) {
        } else {
                  $ip = gethostbyname($GLOBALS['args']);
                 
            if ((!preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/', $ip)) and
               (($ip == $GLOBALS['args']) or ($ip === false))) {
                BOT_RESPONSE('Unknown host: \''.$GLOBALS['args'].'\'');
            } else {
                      $ping = ping($ip);
                if ($ping) {
                              $ping[0] = $GLOBALS['nick'].': '.$ping[0];
                    foreach ($ping as $thisline) {
                             BOT_RESPONSE($thisline);
                    }
                }
            }
              CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'ping on: '.$GLOBALS['CONFIG_CNANNEL'].
                  ', by: '.$GLOBALS['nick'].', address: '.$GLOBALS['args'], '1');
        }
    } catch (Exception $e) {
             BOT_RESPONSE('[ERROR] Exception: plugin_ping() '.$e);
             CLI_MSG('[ERROR] Exception: plugin_ping() '.$e);
    }
}

function ping($hostname)
{
    try {
           exec('ping '.escapeshellarg($hostname), $list);
        if (isset($list[4])) {
            return(array($list[2], $list[3], $list[4]));
        } else {
                  return(array($list[2], $list[3]));
        }
    } catch (Exception $e) {
             BOT_RESPONSE('[ERROR] Exception: ping() '.$e);
             CLI_MSG('[ERROR] Exception: ping() '.$e);
    }
}
