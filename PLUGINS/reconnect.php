<?php

 $plugin_description = 'Reconnect Bot: !reconnect';

function reconnect()
{

 fputs($GLOBALS['socket'],"QUIT :Reconnecting...\n");
 system('run.bat');
 die();
}

?>