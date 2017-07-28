<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving channel to config: !save_channel <#new_channel>';
 $plugin_command = 'save_channel';

function plugin_save_channel()
{
 $new = trim($GLOBALS['args']);

 SaveData('../CONFIG.INI', 'CHANNEL', 'channel', $new);

 CHANNEL_MSG('Channel Saved.');

 CLI_MSG('!save_channel on: '.$GLOBALS['C_CNANNEL'].', New channel: '.$GLOBALS['args']);
}

?>