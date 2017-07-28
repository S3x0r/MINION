<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving ident to config: !save_ident <new_ident>';
 $plugin_command = 'save_ident';

function plugin_save_ident()
{
 $new = trim($GLOBALS['args']);

 SaveData('../CONFIG.INI', 'BOT', 'ident', $new);

 CHANNEL_MSG('Ident Saved.');

 CLI_MSG('!save_ident on: '.$GLOBALS['C_CNANNEL'].', New ident: '.$GLOBALS['args']);
}

?>