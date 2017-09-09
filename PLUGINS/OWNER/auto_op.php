<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Adds host to auto_op list in config file: '.$GLOBALS['CONFIG_CMD_PREFIX'].'auto_op <nick!ident@host>';
 $plugin_command = 'auto_op';

function plugin_auto_op()
{
  $nick_ex = explode('!', trim($GLOBALS['args']));

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'auto_op <nick!ident@hostname>'); } 
  
   elseif($nick_ex[0] != $GLOBALS['CONFIG_NICKNAME']) {

    LoadData($GLOBALS['config_file'], 'ADMIN', 'auto_op_list');

	$auto_list   = $GLOBALS['LOADED'];
	$new         = trim($GLOBALS['args']);
	if($auto_list == '') { $new_list = $new.''; }
	if($auto_list != '') { $new_list = $auto_list.', '.$new; }
		 
	SaveData($GLOBALS['config_file'], 'ADMIN', 'auto_op_list', $new_list);

    /* Inform nick about it */
	fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :From now you are on my auto op list, enjoy.\n");
	
	BOT_RESPONSE('Host: \''.$GLOBALS['args'].'\' added to auto op list.');

	CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'auto_op on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', added: '.$GLOBALS['args'], '1');
   }
   else {
	  BOT_RESPONSE('I cannot add myself to auto op list, im already OP MASTER :)');
   	  CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'auto_op on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', CANNOT ADD MYSELF: '.$GLOBALS['args'], '1');
        }
}

?>