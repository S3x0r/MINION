<?php

 $plugin_description = 'Saving ident to config: !saveident <new_ident>';

function saveident()
{
$new = trim($GLOBALS['args']);

 $GLOBALS['cfg'] = new iniParser("../../CONFIG.INI");
 $GLOBALS['cfg']->setValue("Configuration","ident", "$new");
 $GLOBALS['cfg']->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :New Ident Saved.\n");

}

?>