<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving altnick to config: !save_altnick <new_altnick>';

function plugin_save_altnick()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("BOT","alternative_nick", "$new");
 $cfg->save();

 CHANNEL_MSG('Alternative nick Saved.');

 MSG('!savealtnick on: '.$GLOBALS['channel'].', New altnick: '.$GLOBALS['args']);

}

?>