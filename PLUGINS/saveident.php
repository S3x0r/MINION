<?php

 $plugin_description = 'Saving ident to config: !saveident <new_ident>';

function saveident()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("BOT","ident", "$new");
 $cfg->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :Ident Saved.\n");

 MSG('!saveident on: '.$GLOBALS['channel'].', New ident: '.$GLOBALS['args']);

}

?>