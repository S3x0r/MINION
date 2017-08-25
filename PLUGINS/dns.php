<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Dns: !dns <address>';
 $plugin_command = 'dns';

function plugin_dns()
{

  if(empty($GLOBALS['args'])) { CHANNEL_MSG('Usage: '.$GLOBALS['C_CMD_PREFIX'].'dns <address>'); } 
  
  else {
		 $host = gethostbyaddr(trim($GLOBALS['args']));
  		 CHANNEL_MSG('host: '.$host);

		 CLI_MSG('!dns on: '.$GLOBALS['C_CNANNEL'].', dns: '.$GLOBALS['args'].'/ '.$host);
	   }
}
?>