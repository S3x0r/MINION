<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Joins channel: !join <#channel>, !j <#channel>';

function plugin_joinc()
{
 JOIN_CHANNEL($GLOBALS['args']); 

 CLI_MSG('!join, !j on: '.$GLOBALS['C_CNANNEL'].', joining: '.$GLOBALS['args']);
}

?>