<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Downloads plugins from repository: !fetch <plugin>';
 $plugin_command = 'fetch';

function plugin_fetch()
{

   $address = $GLOBALS['C_FETCH_SERVER'].'/'.$GLOBALS['args'].'.php';  

   CHANNEL_MSG('Downloading plugin: "'.$GLOBALS['args'].'" from repository');

   $file = file_get_contents($address);
   $a = fopen('../PLUGINS/'.$GLOBALS['args'].'.php', 'w');
  
   if(!fwrite($a, $file)) { 
							CHANNEL_MSG('no such plugin in repository'); 
							fclose($a);
							$delete = '../PLUGINS/'.$GLOBALS['args'].'.php';
							unlink($delete);
							CLI_MSG('!fetch: '.$GLOBALS['C_CNANNEL'].', plugin: '.$GLOBALS['args']);
						  }
							else {
									fclose($a);
									CHANNEL_MSG('Plugin added.');
									CLI_MSG('!fetch: '.$GLOBALS['C_CNANNEL'].', plugin: '.$GLOBALS['args']);
								 }
}

?>