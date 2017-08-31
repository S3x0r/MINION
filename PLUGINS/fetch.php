<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Downloads plugins from repository: !fetch list, !fetch get <plugin>';
 $plugin_command = 'fetch';

function plugin_fetch()
{
  if(empty($GLOBALS['args'])) { CHANNEL_MSG('Usage: '.$GLOBALS['C_CMD_PREFIX'].'fetch <list> / fetch get <plugin>'); } 
  
  else {

  if($GLOBALS['args'] == 'list') 
	  { 
        $addr_list = 'https://raw.githubusercontent.com/S3x0r/davybot_repository_plugins/master/plugin_list.db';
		$list = file_get_contents($addr_list);
	  	CHANNEL_MSG('Repository list:');
		CHANNEL_MSG($list);
		CHANNEL_MSG('End list.');
	  }

 else if($GLOBALS['piece1'] == 'get') {

   $address = $GLOBALS['C_FETCH_SERVER'].'/'.$GLOBALS['piece2'].'.php';  

   CHANNEL_MSG('Downloading plugin: "'.$GLOBALS['piece2'].'" from repository');

	$check_file = '../PLUGINS/'.$GLOBALS['piece2'].'.php';

	if(file_exists($check_file)) 
		{
		  CHANNEL_MSG('I already have this plugin, aborting.');
		}
	
   else {
	      $file = file_get_contents($address);
	      $a = fopen('../PLUGINS/'.$GLOBALS['piece2'].'.php', 'w');
  
        if(!fwrite($a, $file))
			{ 
			  CHANNEL_MSG('no such plugin in repository'); 
			  fclose($a);
			  $delete = '../PLUGINS/'.$GLOBALS['piece2'].'.php';
			  unlink($delete);
			  CLI_MSG('!fetch: '.$GLOBALS['C_CNANNEL'].', by: '.$GLOBALS['nick'].', plugin: '.$GLOBALS['piece2'], '1');
			}
			  else {
			         fclose($a);
					 CHANNEL_MSG('Plugin added.');
					 CLI_MSG('!fetch: '.$GLOBALS['C_CNANNEL'].', by: '.$GLOBALS['nick'].', plugin: '.$GLOBALS['piece2'], '1');
   			       }
   }
  }
 }
}
?>