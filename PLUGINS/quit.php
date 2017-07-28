<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shutdown BOT: !quit';
 $plugin_command = 'quit';

function plugin_quit()
{ 
 fputs($GLOBALS['socket'],"QUIT :http://github.com/S3x0r/davybot\n");
 CLI_MSG('!quit received');
 CLI_MSG('Exiting BOT.');
 die();
}

?>