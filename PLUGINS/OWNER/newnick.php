<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Changes nickname: !newnick <new_nick>';
 $plugin_command = 'newnick';

function plugin_newnick()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'newnick <new_nick>'); } 
  
  else {
		 fputs($GLOBALS['socket'],'NICK '.$GLOBALS['args']."\n");
		
		 /* wcli extension */
		 if (extension_loaded('wcli')) {
		 wcli_set_console_title('davybot '.VER.' (server: '.$GLOBALS['CONFIG_SERVER'].':'.$GLOBALS['CONFIG_PORT'].' | nickname: '.$GLOBALS['args'].' | channel: '.$GLOBALS['CONFIG_CNANNEL'].')');
		 }
	
		 CLI_MSG('!newnick on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', new nick: '.$GLOBALS['args'], '1');
	   }
}

?>