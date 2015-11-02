<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shutdown BOT: !quit, !die';

function plugin_quit()
{
 
 fputs($GLOBALS['socket'],"QUIT :http://github.com/S3x0r/davybot\n");
 MSG('!quit received');
 MSG('Exiting BOT...');
 die();

}

?>