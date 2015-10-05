<?php

 $plugin_description = 'Saving altnick to config: !savealtnick <new_altnick>';

function savealtnick()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("Configuration","alternative_nick", "$new");
 $cfg->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :New AltNick Saved.\n");

}

?>