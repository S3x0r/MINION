<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Adds Owner host to config file: !add_owner <nick!ident@host>';
 $plugin_command = 'add_owner';

function plugin_add_owner()
{

  if(empty($GLOBALS['args'])) { CHANNEL_MSG('Usage: '.$GLOBALS['C_CMD_PREFIX'].'add_owner <nick!ident@host>'); } 
  
  else {

         LoadData('../CONFIG.INI', 'ADMIN', 'bot_owners');

		 $owners_list = $GLOBALS['LOADED'];
		 $new         = trim($GLOBALS['args']);
		 $new_list    = $owners_list.', '.$new;

	     SaveData('../CONFIG.INI', 'ADMIN', 'bot_owners', $new_list);

		 CHANNEL_MSG('Owner Added.');

		 CLI_MSG('!add_owner on: '.$GLOBALS['C_CNANNEL'].', added: '.$GLOBALS['args']);
	   }
}

?>