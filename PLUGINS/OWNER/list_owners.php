<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT owners: !list_owners';
 $plugin_command = 'list_owners';

function plugin_list_owners()
{
 LoadData($GLOBALS['config_file'], 'ADMIN', 'bot_owners');

 $pieces = explode(", ", $GLOBALS['LOADED']);
 $owners = $pieces;

 $table = $owners;
 
 BOT_RESPONSE('My Admins:');

 for ($i=0; $i<count($table); $i++)
 {
   BOT_RESPONSE($table[$i]);
 } 
 
 CLI_MSG('!list_owners on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
}

?>