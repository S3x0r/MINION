<?php

 $plugin_description = 'Shows BOT commands: !commands';

function commands()
{

fputs($GLOBALS['socket'], "PRIVMSG ".$GLOBALS['channel']." :My Commands:\n");

 foreach ( glob( '../PLUGINS/*.php' ) as $pluginName )
 {
  $pluginName = basename( $pluginName, '.php' );
  fputs($GLOBALS['socket'], "PRIVMSG ".$GLOBALS['channel']." :!$pluginName\n");
 }

fputs($GLOBALS['socket'], "PRIVMSG ".$GLOBALS['channel']." :End.\n");

}
//fix: change to put msg in one line :0
?>