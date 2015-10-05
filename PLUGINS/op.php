<?php

 $plugin_description  = 'Gives op: !op <nick>';

function op()
{

 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +o '.$GLOBALS['args']."\n");

}

?>