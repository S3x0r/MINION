<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving fetch server to config: !save_fetch_server <server>';
 $plugin_command = 'save_fetch_server';

function plugin_save_fetch_server()
{
 $new = trim($GLOBALS['args']);

 SaveData('../CONFIG.INI', 'FETCH', 'fetch_server', $new);

 CHANNEL_MSG('Server Saved.');

 CLI_MSG('!save_fetch_server on: '.$GLOBALS['C_CNANNEL'].', New server: '.$GLOBALS['args']);
}

?>