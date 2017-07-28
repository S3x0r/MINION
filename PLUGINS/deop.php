<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Deops someone: !deop <nick>';
 $plugin_command = 'deop';

function plugin_deop()
{
 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['C_CNANNEL'].' -o '. $GLOBALS['args'] ."\n");

 CLI_MSG('!deop on: '.$GLOBALS['C_CNANNEL'].', who: '.$GLOBALS['args']);
}

?>