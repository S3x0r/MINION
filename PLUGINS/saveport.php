<?php

 $plugin_description = 'Saving new port to config: !saveport <new_port>';

function saveport()
{
$new = trim($GLOBALS['args']);

 $GLOBALS['cfg'] = new iniParser("../CONFIG/CONFIG.INI");
 $GLOBALS['cfg']->setValue("Configuration","port", "$new");
 $GLOBALS['cfg']->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :New Port Saved.\n");

}

?>