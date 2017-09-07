<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Gives op: !op <nick>';
 $plugin_command = 'op';

function plugin_op()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'op <nick>'); } 
  
  else {
		 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['CONFIG_CNANNEL'].' +o '.$GLOBALS['args']."\n");

		 CLI_MSG('!op on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', for: '.$GLOBALS['args'], '1');
	   }
}

?>