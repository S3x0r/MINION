<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows random text from file: !cham <nick>';

function plugin_cham()
{
 $file = '../somefile.txt';
	
 $texts = file_get_contents($file);
 $texts = explode("\n",$texts);
 $count = 0;

 shuffle($texts);
 $text = $texts[$count++];

 $who = trim($GLOBALS['args']);

 CHANNEL_MSG($who.': '.$text);
 
 CLI_MSG('!cham on: '.$GLOBALS['C_CNANNEL'].', who: '.$who);
}
/* add text file */
?>