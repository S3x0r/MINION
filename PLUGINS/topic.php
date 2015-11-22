<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Changing Topic in channel: !topic <topic>';

function plugin_topic()
{
 $data = trim($GLOBALS['args']);
 fputs($GLOBALS['socket'], 'TOPIC '.$GLOBALS['C_CNANNEL'].' '.$data."\n");

 CLI_MSG('!topic on: '.$GLOBALS['C_CNANNEL'].', New topic: '.$data);
}

?>