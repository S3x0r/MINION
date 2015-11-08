<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Gives op: !op <nick>';

function plugin_op()
{
 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['C_CNANNEL'].' +o '.$GLOBALS['args']."\n");

 MSG('!op on: '.$GLOBALS['C_CNANNEL'].', for: '.$GLOBALS['args']);
}

?>