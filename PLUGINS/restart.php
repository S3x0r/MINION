<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Restarts Bot: !restart';

function plugin_restart()
{

 fputs($GLOBALS['socket'],"QUIT :Restarting..\n");
 MSG('!restart on: '.$GLOBALS['channel']);
 MSG('Restarting BOT...');
 system('restart.bat');
 die();
}

?>