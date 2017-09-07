<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Clustering plugin: !cluster <commands>';
 $plugin_command = 'cluster';

function plugin_cluster()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'cluster <options>/<commands>'); } 
  
  else {

  if($GLOBALS['args'] == 'commands') 
	  { 
		BOT_RESPONSE('Cluster commands: commands, shutdown');
	  }
 
  if($GLOBALS['piece1'] == 'shutdown' && $GLOBALS['piece2'] == $GLOBALS['CONFIG_NICKNAME'])
	  { 
	  BOT_RESPONSE('Shutting down machine...');
	  exec('shutdown -s -t 0');
	  }

  if($GLOBALS['piece1'] == 'shutdown' && $GLOBALS['piece2'] == '*')
	  { 
	  BOT_RESPONSE('Shutting down machine...');
	  exec('shutdown -s -t 0');
	  }

    }
// CLI_MSG('!commands on: '.$GLOBALS['CONFIG_CNANNEL']);
}

?>