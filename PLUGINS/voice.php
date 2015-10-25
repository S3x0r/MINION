<?php

 $plugin_description  = 'Gives voice: !voice <nick>';


function voice()
{

 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +v '.$GLOBALS['args']."\n");
 MSG('Gived voice to: '.$GLOBALS['args'].', on: '.$GLOBALS['channel']);

}

?>