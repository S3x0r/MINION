<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT commands: !commands';
 $plugin_command = 'commands';

function plugin_commands()
{
 CHANNEL_MSG('My Commands:');

 $commands = file_get_contents('../plugins.ini');
 CHANNEL_MSG($commands);

 CHANNEL_MSG('End.');

 CLI_MSG('!commands on: '.$GLOBALS['C_CNANNEL']);
}

?>