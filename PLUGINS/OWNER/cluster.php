<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Clustering plugin: '.$GLOBALS['CONFIG_CMD_PREFIX'].'cluster help to list commands';
 $plugin_command = 'cluster';

function plugin_cluster()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'cluster <help> to list commands'); } 
  
  else {

	switch($GLOBALS['args']) {

		case 'help': 
		BOT_RESPONSE('Cluster commands:');
		BOT_RESPONSE('cluster help - Shows this help');
		BOT_RESPONSE('cluster shutdown - Bot shutdowns computer: '.$GLOBALS['CONFIG_CMD_PREFIX'].'cluster shutdown <bot_nickname>');
		BOT_RESPONSE('cluster shutdown * - Bot shutdowns all bots computers: '.$GLOBALS['CONFIG_CMD_PREFIX'].'cluster shutdown *');
		break;

	}

  /* me */
  if($GLOBALS['piece1'] == 'shutdown' && $GLOBALS['piece2'] == $GLOBALS['CONFIG_NICKNAME'])
	  { 
	  BOT_RESPONSE('Shutting down machine...');
      CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'cluster shutdown: on '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
	  CLI_MSG('SHUTTING DOWN COMPUTER!', '1');
	  exec('shutdown -s -t 0');
	  }

  /* all */
  if($GLOBALS['piece1'] == 'shutdown' && $GLOBALS['piece2'] == '*')
	  { 
	  BOT_RESPONSE('Shutting down machine...');
	  CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'cluster shutdown: on '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
	  CLI_MSG('SHUTTING DOWN COMPUTER!', '1');
	  exec('shutdown -s -t 0');
	  }

    }
}

?>