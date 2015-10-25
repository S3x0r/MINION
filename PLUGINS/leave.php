<?php

 $plugin_description = 'Leave channel: !leave <#channel>, !part <#channel>';

function leave()
{

 fputs($GLOBALS['socket'],'PART '.$GLOBALS['args']."\n");

 MSG('Leaving channel: '.$GLOBALS['args']);

}

?>