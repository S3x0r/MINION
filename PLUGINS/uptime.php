<?php
 
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT uptime: !uptime';
 $plugin_command = 'uptime';

function plugin_uptime()
{
 $now  = time();
 $time = $now - START_TIME;

 $year=floor($time/(365*24*60*60));
 $time-=$year*(365*24*60*60);

 $month=floor($time/(30*24*60*60));
 $time-=$month*(30*24*60*60);

 $day=floor($time/(24*60*60));
 $time-=$day*(24*60*60);

 $hour=floor($time/(60*60));
 $time-=$hour*(60*60);

 $minute=floor($time/(60));
 $time-=$minute*(60);

 $second=floor($time);
 $time-=$second;

 CHANNEL_MSG("Bot uptime: $year year(s), $month month(s), $day day(s), $hour hour(s), $minute minute(s), $second second(s)"); 
 
 CLI_MSG('!Uptime on: '.$GLOBALS['C_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
}

?>