<?php

 $plugin_description = 'Changes nickname: !newnick <new_nick>';

function newnick()
{

 fputs($GLOBALS['socket'],'NICK '.$GLOBALS['args']."\n");
 MSG('Changing nick to: '.$GLOBALS['args']);


}

?>