<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT uptime: !uptime';

function plugin_uptime()
{
 $now	  = time();
 $seconds = $now - $GLOBALS['StartTime'];
 $minutes = floor( $seconds / 60 );
 $hours   = floor( $seconds / 3600 );

 CHANNEL_MSG('My Uptime: '.$hours.' hours, '.$minutes.' minutes');
 
 CLI_MSG('!Uptime on: '.$GLOBALS['C_CNANNEL']);
}

?> 