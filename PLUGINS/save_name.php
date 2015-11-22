<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving name to config: !save_name <new_name>';

function plugin_save_name()
{
 $new = trim($GLOBALS['args']);

 SaveData('../CONFIG.INI', 'BOT', 'name', $new);

 CHANNEL_MSG('Name Saved.');

 CLI_MSG('!save_name on: '.$GLOBALS['C_CNANNEL'].', New name: '.$GLOBALS['args']);
}

?>