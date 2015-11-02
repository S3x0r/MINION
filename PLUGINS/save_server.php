<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving new server to config: !save_server <new_server>';

function plugin_save_server()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("SERVER","server", "$new");
 $cfg->save();

 CHANNEL_MSG('Server Saved.');

 MSG('!saveserver on: '.$GLOBALS['channel'].', New server: '.$GLOBALS['args']);
}

?>