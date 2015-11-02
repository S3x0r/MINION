<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description  = 'Gives op: !op <nick>';

function plugin_op()
{

 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +o '.$GLOBALS['args']."\n");

  MSG('Giving op on: '.$GLOBALS['channel'].', To: '.$GLOBALS['args']);



}

?>