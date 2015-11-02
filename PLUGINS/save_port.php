<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving new port to config: !save_port <new_port>';

function plugin_save_port()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("SERVER","port", "$new");
 $cfg->save();

 CHANNEL_MSG('Port Saved.');

 MSG('!saveport on: '.$GLOBALS['channel'].', New port: '.$GLOBALS['args']);

}

?>