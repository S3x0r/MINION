<?php

 $plugin_description = 'Restarts Bot: !restart';

function restart()
{

 fputs($GLOBALS['socket'],"QUIT :Restarting..\n");
 MSG('!restart on: '.$GLOBALS['channel']);
 MSG('Restarting BOT...');
 system('restart.bat');
 die();
}

?>