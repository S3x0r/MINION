<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving nickname to config: !save_nick <new_nick>';

function plugin_save_nick()
{
 $new = trim($GLOBALS['args']);

 SaveData('../CONFIG.INI', 'BOT', 'nickname', $new);
 
 CHANNEL_MSG('Nick Saved.');

 CLI_MSG('!save_nick on: '.$GLOBALS['C_CNANNEL'].', New nick: '.$GLOBALS['args']);
}

?>