<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Kicks from channel: !kick <#channel> <who>';
 $plugin_command = 'kick';

function plugin_kick()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'kick <#channel> <who>'); } 
  
  else {
	
	     fputs($GLOBALS['socket'], 'KICK '.$GLOBALS['piece1'].' :'.$GLOBALS['piece2']."\n");

		 CLI_MSG('!kick on: '.$GLOBALS['piece1'].', by: '.$GLOBALS['nick'].', kicked: '.$GLOBALS['piece2'], '1');

	   }
}

?>