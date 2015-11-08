<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT commands: !commands, !help';

function plugin_commands()
{
 CHANNEL_MSG('My Commands:');

 foreach(glob('../PLUGINS/*.php') as $pluginName)
 {
  $pluginName = basename($pluginName, '.php');
  CHANNEL_MSG("!$pluginName");
 }

 CHANNEL_MSG('End.');

 MSG('!commands, !help on: '.$GLOBALS['C_CNANNEL']);
}
//need fix
?>