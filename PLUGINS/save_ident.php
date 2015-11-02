<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving ident to config: !save_ident <new_ident>';

function plugin_save_ident()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("BOT","ident", "$new");
 $cfg->save();

 CHANNEL_MSG('Ident Saved.');

 MSG('!saveident on: '.$GLOBALS['channel'].', New ident: '.$GLOBALS['args']);

}

?>