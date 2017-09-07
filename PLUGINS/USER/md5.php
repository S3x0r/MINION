<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Changing string to MD5: !md5 <string>';
 $plugin_command = 'md5';

function plugin_md5()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'md5 <string>'); } 
  
  else {

		 $data = str_replace(" ","",$GLOBALS['args']);
		 $md5  = md5($data);

		 BOT_RESPONSE('(MD5) \''.$data.'\' -> '.$md5);

		 CLI_MSG('!md5 on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', data: '.$data, '1');
       }
}

?>