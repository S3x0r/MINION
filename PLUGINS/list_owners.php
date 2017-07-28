<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT owners: !list_owners';
 $plugin_command = 'list_owners';

function plugin_list_owners()
{
 LoadData('../CONFIG.INI', 'ADMIN', 'bot_owners');

 $pieces = explode(", ", $GLOBALS['LOADED']);
 $owners = $pieces;

 $table = $owners;
 
 CHANNEL_MSG('My Admins:');

 for ($i=0; $i<count($table); $i++)
 {
   CHANNEL_MSG($table[$i]);
 } 
 
 CLI_MSG('!list_owners on: '.$GLOBALS['C_CNANNEL']);
}

?>