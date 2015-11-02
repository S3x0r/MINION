<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description  = 'Devoice user: !devoice <nick>';

function plugin_devoice()
{

 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' -v '.$GLOBALS['args']."\n");

 MSG('Taking voice: '.$GLOBALS['args'].', on: '.$GLOBALS['channel']);

}

?>