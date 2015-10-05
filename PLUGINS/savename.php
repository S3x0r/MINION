<?php

 $plugin_description = 'Saving name to config: !savename <new_name>';

function savename()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("Configuration","name", "$new");
 $cfg->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :New Name Saved.\n");

}

?>