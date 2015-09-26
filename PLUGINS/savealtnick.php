<?php

 $plugin_description = 'Saving altnick to config: !savealtnick <new_altnick>';

function savealtnick()
{
$new = trim($GLOBALS['args']);

 $GLOBALS['cfg'] = new iniParser("../CONFIG/CONFIG.INI");
 $GLOBALS['cfg']->setValue("Configuration","alternative_nick", "$new");
 $GLOBALS['cfg']->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :New AltNick Saved.\n");

}

?>