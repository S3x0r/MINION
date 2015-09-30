<?php

 $plugin_description = 'Shows BOT commands: !commands';


function commands()
{
global $socket;
global $channel;

fputs($socket, "PRIVMSG ".$channel." :My Commands:\n");

 foreach ( glob( '../PLUGINS/*.php' ) as $pluginName )
 {
  $pluginName = basename( $pluginName, '.php' );
  fputs($socket, "PRIVMSG ".$channel." :!$pluginName\n");
 }

fputs($socket, "PRIVMSG ".$channel." :End.\n");

}
//fix: change to put msg in one line :0
?>