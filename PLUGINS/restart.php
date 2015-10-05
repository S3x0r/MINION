<?php

 $plugin_description = 'Restarts Bot: !restart';

function restart()
{

 fputs($GLOBALS['socket'],"QUIT :Restarting..\n");
 system('restart.bat');
 die();
}

?>