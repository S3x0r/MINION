<?php

 $plugin_description = 'Saving new port to config: !saveport <new_port>';

function saveport()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("Configuration","port", "$new");
 $cfg->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :New Port Saved.\n");

}

?>