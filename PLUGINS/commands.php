<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT commands: !commands';


function plugin_commands()
{
CHANNEL_MSG('My Commands:');

 foreach ( glob( '../PLUGINS/*.php' ) as $pluginName )
 {
  $pluginName = basename( $pluginName, '.php' );
  CHANNEL_MSG("!$pluginName");
 }

CHANNEL_MSG('End.');

}
//fix: change to put msg in one line :0
?>