<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Gives voice: !voice <nick>';


function plugin_voice()
{
 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['C_CNANNEL'].' +v '.$GLOBALS['args']."\n");
 
 CLI_MSG('!voice on: '.$GLOBALS['C_CNANNEL'].', for: '.$GLOBALS['args']);
}

?>