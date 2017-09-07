<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Adds Owner host to config file: !add_owner <nick!ident@hostname>';
 $plugin_command = 'add_owner';

function plugin_add_owner()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'add_owner <nick!ident@hostname>'); } 
  
  else {

         LoadData($GLOBALS['config_file'], 'ADMIN', 'bot_owners');

		 $owners_list = $GLOBALS['LOADED'];
		 $new         = trim($GLOBALS['args']);
		 $new_list    = $owners_list.', '.$new;

	     SaveData($GLOBALS['config_file'], 'ADMIN', 'bot_owners', $new_list);

		 BOT_RESPONSE('Owner Added.');

		 CLI_MSG('!add_owner on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', added: '.$GLOBALS['args'], '1');
	   }
}

?>