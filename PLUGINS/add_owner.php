<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Adds Owner host to config file: !add_owner <nick!id@host>';

function plugin_add_owner()
{
 LoadData('../CONFIG.INI', 'ADMIN', 'bot_owners');

 $owners_list = $GLOBALS['LOADED'];
 $new         = trim($GLOBALS['args']);
 $new_list    = $owners_list.', '.$new;

 SaveData('../CONFIG.INI', 'ADMIN', 'bot_owners', $new_list);

 CHANNEL_MSG('Owner Added.');

 MSG('!add_owner on: '.$GLOBALS['C_CNANNEL'].', added: '.$GLOBALS['args']);
}

?>