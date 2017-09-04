<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Sends raw string to server: !raw <string>';
 $plugin_command = 'raw';

function plugin_raw()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'raw <string>'); } 
  
  else {
         $msg = $GLOBALS['piece1'].' '.$GLOBALS['piece2'].' '.$GLOBALS['piece3'].' '.$GLOBALS['piece4']."\n";	     
         fputs($GLOBALS['socket'], $msg);

		 CLI_MSG('!raw on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', cmd: '.$msg, '1');
	   }
}

?>