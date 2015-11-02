<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Changing Topic in channel: !topic <topic>';

function plugin_topic()
{

 fputs($GLOBALS['socket'], 'TOPIC '.$GLOBALS['channel'].' '. $GLOBALS['args'] ."\n");

}

?>