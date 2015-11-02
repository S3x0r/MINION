<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving nickname to config: !save_nick <new_nick>';

function plugin_save_nick()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("BOT","nickname", "$new");
 $cfg->save();

 CHANNEL_MSG('Nick Saved.');

 MSG('!savenick on: '.$GLOBALS['channel'].', New nick: '.$GLOBALS['args']);

}

?>