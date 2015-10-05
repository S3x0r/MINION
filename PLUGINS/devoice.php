<?php

 $plugin_description  = 'Devoice user: !devoice <nick>';

function devoice()
{

 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' -v '.$GLOBALS['args']."\n");

}

?>