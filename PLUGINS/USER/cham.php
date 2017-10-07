<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Shows random text from file: '.$GLOBALS['CONFIG_CMD_PREFIX'].'cham <nick>';
    $plugin_command = 'cham';

/*
    For use this plugin you must add file to $file var in main bot directory

*/

function plugin_cham()
{

    if (OnEmptyArg('cham <nick>')) {
    } else {
              $file = ''; //  <----- here

              if(!empty($file)) {
              $texts = file_get_contents($file);
              $texts = explode("\n", $texts);
              $count = 0;

              shuffle($texts);
              $text = $texts[$count++];

              $who = trim($GLOBALS['args']);
              
              CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'cham on: '.$GLOBALS['channel'].', by: '.
              $GLOBALS['nick'].', who: '.$who, '1');

              BOT_RESPONSE($who.': '.$text);

              } else {
                        BOT_RESPONSE('no file specified to use plugin');
              }
    }
}
