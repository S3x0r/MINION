<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description  = 'Gives voice: !voice <nick>';


function plugin_voice()
{

 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +v '.$GLOBALS['args']."\n");
 MSG('Gived voice to: '.$GLOBALS['args'].', on: '.$GLOBALS['channel']);

}

?>