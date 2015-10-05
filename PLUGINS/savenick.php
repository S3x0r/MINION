<?php

 $plugin_description = 'Saving nickname to config: !savenick <new_nick>';

function savenick()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("Configuration","nickname", "$new");
 $cfg->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :New Nick Saved.\n");

}

?>