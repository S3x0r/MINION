<?php

 $plugin_description = 'Saving name to config: !savename <new_name>';

function savename()
{
$new = trim($GLOBALS['args']);

 $GLOBALS['cfg'] = new iniParser("../../CONFIG.INI");
 $GLOBALS['cfg']->setValue("Configuration","name", "$new");
 $GLOBALS['cfg']->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :New Name Saved.\n");

}

?>