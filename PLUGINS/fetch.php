<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Downloads plugins from repository: !fetch list, !fetch get <plugin>';
 $plugin_command = 'fetch';

function plugin_fetch()
{
  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'fetch list / fetch get <plugin>'); } 
  
  else {

  if($GLOBALS['args'] == 'list') 
	  { 
        $addr_list = 'https://raw.githubusercontent.com/S3x0r/davybot_repository_plugins/master/plugin_list.db';
		$list = file_get_contents($addr_list);
	  	BOT_RESPONSE('Repository list:');
		BOT_RESPONSE($list);
		BOT_RESPONSE('End list.');
	  }

 else if($GLOBALS['piece1'] == 'get') {

   $address = $GLOBALS['CONFIG_FETCH_SERVER'].'/'.$GLOBALS['piece2'].'.php';  

   BOT_RESPONSE('Downloading plugin: "'.$GLOBALS['piece2'].'" from repository');

	$check_file = '../PLUGINS/'.$GLOBALS['piece2'].'.php';

	if(file_exists($check_file)) 
		{
		  BOT_RESPONSE('I already have this plugin, aborting.');
		}
	
   else {
	      $file = file_get_contents($address);
	      $a = fopen('../PLUGINS/'.$GLOBALS['piece2'].'.php', 'w');
  
        if(!fwrite($a, $file))
			{ 
			  BOT_RESPONSE('no such plugin in repository'); 
			  fclose($a);
			  $delete = '../PLUGINS/'.$GLOBALS['piece2'].'.php';
			  unlink($delete);
			  CLI_MSG('!fetch: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', plugin: '.$GLOBALS['piece2'], '1');
			}
			  else {
			         fclose($a);
					 BOT_RESPONSE('Plugin added.');
					 CLI_MSG('!fetch: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', plugin: '.$GLOBALS['piece2'], '1');
   			       }
   }
  }
 }
}
?>