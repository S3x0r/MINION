<?php

 $plugin_description = 'Shows BOT uptime: !uptime';

function uptime()
{
     $now	  = time();
	 $seconds = $now - $GLOBALS['StartTime'];
	 $minutes = floor( $seconds / 60 );
	 $hours   = floor( $seconds / 3600 );
	 
	 $msg = 'My Uptime: '. $hours .' hours, '. $minutes .' minutes';
     fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :$msg\n");
}

?> 