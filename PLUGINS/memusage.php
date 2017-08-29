<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows how much ram is being used by bot: !memusage';
 $plugin_command = 'memusage';

function plugin_memusage()
{
  $mem = memory_get_usage(); 
  $memory = byte_convert($mem);
	
  CHANNEL_MSG('I\'m using '.$memory.' of RAM to run currently');
  CLI_MSG('!memusage by: '.$GLOBALS['nick']);
}

function byte_convert($bytes)
{
  $symbol = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
  $exp = 0;
  $converted_value = 0;
  if( $bytes > 0 )
	{
      $exp = floor(log($bytes)/log(1024));
	  $converted_value = ($bytes/pow(1024,floor($exp)));
	}
	return sprintf( '%.2f '.$symbol[$exp], $converted_value);
}

?>