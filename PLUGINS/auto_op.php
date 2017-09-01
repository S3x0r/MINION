<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Adds host to auto_op list in config file: !auto_op <nick!ident@host>';
 $plugin_command = 'auto_op';

function plugin_auto_op()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['C_CMD_PREFIX'].'auto_op <nick!ident@host>'); } 
  
  else {

         LoadData('../CONFIG.INI', 'ADMIN', 'auto_op_list');

		 $auto_list   = $GLOBALS['LOADED'];
		 $new         = trim($GLOBALS['args']);
		 $new_list    = $auto_list.', '.$new;

		 SaveData('../CONFIG.INI', 'ADMIN', 'auto_op_list', $new_list);

		 BOT_RESPONSE('Host added to auto op list.');

		 CLI_MSG('!auto_op on: '.$GLOBALS['C_CNANNEL'].', by: '.$GLOBALS['nick'].', added: '.$GLOBALS['args'], '1');
       }
}

?>