<?php

 $plugin_description = 'Joins channel: !join <#channel>, !j <#channel>';

function joinc()
{

 fputs($GLOBALS['socket'],'JOIN '.$GLOBALS['args']."\n");
 
 MSG('Joining channel: '.$GLOBALS['args']);

}

?>