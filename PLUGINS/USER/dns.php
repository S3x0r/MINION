<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Dns: !dns <address>';
 $plugin_command = 'dns';

function plugin_dns()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'dns <address>'); } 
  
  else {
		 $host = gethostbyaddr(trim($GLOBALS['args']));
  		 BOT_RESPONSE('host: '.$host);

		 CLI_MSG('!dns on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', dns: '.$GLOBALS['args'].'/ '.$host, '1');
	   }
}
?>