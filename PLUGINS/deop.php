<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Deops someone: !deop <nick>';

function plugin_deop()
{

 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' -o '. $GLOBALS['args'] ."\n");

 MSG('Taking op on: '.$GLOBALS['channel'].', Who: '.$GLOBALS['args']);

}

?>