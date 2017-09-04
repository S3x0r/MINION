<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT commands: !help';
 $plugin_command = 'help';

function plugin_help()
{
 BOT_RESPONSE('My Commands:');

 $commands = file_get_contents('plugins.ini');
 BOT_RESPONSE($commands);

 BOT_RESPONSE('End.');

 CLI_MSG('!help on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
}

?>