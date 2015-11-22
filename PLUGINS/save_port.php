<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving new port to config: !save_port <new_port>';

function plugin_save_port()
{
 $new = trim($GLOBALS['args']);

 SaveData('../CONFIG.INI', 'SERVER', 'port', $new);
 
 CHANNEL_MSG('Port Saved.');

 CLI_MSG('!save_port on: '.$GLOBALS['C_CNANNEL'].', New port: '.$GLOBALS['args']);
}

?>