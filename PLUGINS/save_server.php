<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving new server to config: !save_server <new_server>';

function plugin_save_server()
{
 $new = trim($GLOBALS['args']);

 SaveData('../CONFIG.INI', 'SERVER', 'server', $new);

 CHANNEL_MSG('Server Saved.');

 CLI_MSG('!save_server on: '.$GLOBALS['C_CNANNEL'].', New server: '.$GLOBALS['args']);
}

?>