<?php

 $plugin_description = 'Saving nickname to config: !savenick <new_nick>';

function savenick()
{
$new = trim($GLOBALS['args']);

 $GLOBALS['cfg'] = new iniParser("../CONFIG/CONFIG.INI");
 $GLOBALS['cfg']->setValue("Configuration","nickname", "$new");
 $GLOBALS['cfg']->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :New Nick Saved.\n");

}

?>