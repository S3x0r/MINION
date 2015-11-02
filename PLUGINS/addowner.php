<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Adds Owner host to config file: !addowner <nick!id@host>';


function plugin_addowner()
{
 global $cfg;
 
 $conf_file = '../CONFIG.INI';
 $cfg = new iniParser($conf_file);

 $GLOBALS['c_owners']  = $cfg->get("ADMIN","bot_owners");

 $owners_list = $GLOBALS['c_owners'];
 $new         = trim($GLOBALS['args']);
 $new_list    = $owners_list.', '.$new;

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("ADMIN","bot_owners", "$new_list");
 $cfg->save();

 CHANNEL_MSG('Owner Added.');

 MSG('!addowner on: '.$GLOBALS['channel'].', Added new BOT owner: '.$GLOBALS['args']);

}

?>