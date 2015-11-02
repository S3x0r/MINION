<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving name to config: !save_name <new_name>';

function plugin_save_name()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("BOT","name", "$new");
 $cfg->save();

 CHANNEL_MSG('Name Saved.');

 MSG('!savename on: '.$GLOBALS['channel'].', New name: '.$GLOBALS['args']);

}

?>