<?php

 $plugin_description = 'Shutdown BOT: !quit, !die';

function quit()
{
 
 fputs($GLOBALS['socket'],"QUIT :http://github.com/S3x0r/davybot\n");
 MSG('!quit received');
 MSG('Exiting BOT...');
 die();

}

?>