<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Leave channel: !leave <#channel>, !part <#channel>';

function plugin_leave()
{

 fputs($GLOBALS['socket'],'PART '.$GLOBALS['args']."\n");

 MSG('Leaving channel: '.$GLOBALS['args']);

}

?>