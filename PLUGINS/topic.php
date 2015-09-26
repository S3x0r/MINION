<?php

 $plugin_description = 'Changing Topic in channel: !topic <topic>';

function topic()
{

 fputs($GLOBALS['socket'], 'TOPIC '.$GLOBALS['channel'].' '. $GLOBALS['args'] ."\n");

}

?>