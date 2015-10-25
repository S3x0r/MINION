<?php

 $plugin_description = 'Saving new server to config: !saveserver <new_server>';

function saveserver()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("SERVER","server", "$new");
 $cfg->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :Server Saved.\n");

 MSG('!saveserver on: '.$GLOBALS['channel'].', New server: '.$GLOBALS['args']);
}

?>