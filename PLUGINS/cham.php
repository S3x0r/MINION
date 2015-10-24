<?php

 $plugin_description = 'Shows random text from file: !cham <nick>';

function cham()
{
 
 $file = '../somefile.txt';
	
 $texts = file_get_contents($file);
 $texts = explode("\n",$texts);
 $count = 0;

 shuffle($texts);
 $text  =  $texts[$count++];

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel'].' :'.$GLOBALS['args'].": $text\n");

}
//add text file
?>