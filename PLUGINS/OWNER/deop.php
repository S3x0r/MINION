<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Deops someone: !deop <nick>';
 $plugin_command = 'deop';

function plugin_deop()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'deop <nick>'); } 
  
  else {
		 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['CONFIG_CNANNEL'].' -o '. $GLOBALS['args'] ."\n");

		 CLI_MSG('!deop on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', who: '.$GLOBALS['args'], '1');
	   }
}

?>