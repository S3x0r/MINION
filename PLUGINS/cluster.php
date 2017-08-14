<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Clustering plugin: !cluster <options>';
 $plugin_command = 'cluster';

function plugin_cluster()
{
  if($GLOBALS['args'] == 'commands') 
	  { 
		CHANNEL_MSG('Cluster commands: commands, shutdown');
	  }
 
  if($GLOBALS['piece1'] == 'shutdown')  
	  { 
		CHANNEL_MSG($GLOBALS['piece2']);
		if($GLOBALS['piece2'] == $GLOBALS['C_NICKNAME']);
		  {
		  exec('shutdown -s -t 0');
		  }
	  }

 
 
 
 
 
 
 
 
 //CLI_MSG('!commands on: '.$GLOBALS['C_CNANNEL']);
}

?>