<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Leave channel: !leave <#channel>';
 $plugin_command = 'leave';

function plugin_leave()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'leave <#channel>'); } 
  
  else {
		 fputs($GLOBALS['socket'],'PART '.$GLOBALS['args']."\n");

		 CLI_MSG('!leave on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', leaving: '.$GLOBALS['args'], '1');
	   }
}

?>