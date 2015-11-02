<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT owners: !list_owners';

function plugin_list_owners()
{
 $conf_file = '../CONFIG.INI';
 $cfg = new iniParser($conf_file);
 $GLOBALS['c_owners'] = $cfg->get("ADMIN","bot_owners");

 $owners_c  = $GLOBALS['c_owners'];
 $pieces = explode(", ", $owners_c);
 $owners = $pieces;

 $table = $owners;
 
 CHANNEL_MSG('My Admins:');

 for ($i=0; $i<count($table); $i++)
 {
   CHANNEL_MSG($table[$i]);
 } 

}

?>