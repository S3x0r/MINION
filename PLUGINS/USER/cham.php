<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows random text from file: !cham <nick>';
 $plugin_command = 'cham';

/*
    For use this plugin you must add somefile.txt in main bot directory

*/

function plugin_cham()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'cham <nick>'); } 
  
  else {

		 $file = 'somefile.txt';
	
		 $texts = file_get_contents($file);
		 $texts = explode("\n",$texts);
		 $count = 0;

		 shuffle($texts);
		 $text = $texts[$count++];

		 $who = trim($GLOBALS['args']);

		 BOT_RESPONSE($who.': '.$text);
 
		 CLI_MSG('!cham on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', who: '.$who, '1');
	   }
}

?>