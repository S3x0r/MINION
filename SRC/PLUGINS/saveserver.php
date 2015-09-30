<?php

 $plugin_description = 'Saving new server to config: !saveserver <new_server>';

function saveserver()
{
 $new = trim($GLOBALS['args']);

 $GLOBALS['cfg'] = new iniParser("../../CONFIG.INI");
 $GLOBALS['cfg']->setValue("Configuration","server", "$new");
 $GLOBALS['cfg']->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :New Server Saved.\n");
}

?>