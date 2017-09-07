<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Changing Topic in channel: !topic <new_topic>';
 $plugin_command = 'topic';

function plugin_topic()
{
  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'topic <new_topic>'); } 
  
   else {
		  fputs($GLOBALS['socket'], 'TOPIC '.$GLOBALS['CONFIG_CNANNEL'].' '.msg_without_command()."\n");
		  CLI_MSG('!topic on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New topic: \''.msg_without_command().'\'', '1');
	    }
}

?>