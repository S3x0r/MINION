<?php
if (PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

    $plugin_description = 'Plugins manipulation: '.$GLOBALS['CONFIG_CMD_PREFIX'].'plugin help to list commands';
    $plugin_command = 'plugin';

function plugin_plugin() {

    if (empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'plugin <help> to list commands'); } 
  
     else {

       switch($GLOBALS['args']) {

		case 'help': 
		BOT_RESPONSE('Plugin commands:');
		BOT_RESPONSE('plugin delete - Deletes plugin from directory: '.$GLOBALS['CONFIG_CMD_PREFIX'].'plugin delete <plugin_name>');
		BOT_RESPONSE('plugin move - Move plugin from OWNER dir to USER directory: '.$GLOBALS['CONFIG_CMD_PREFIX'].'plugin move <plugin_name>');
		break;

	    }

       switch($GLOBALS['piece1']) {

	    case 'delete':
	    if (file_exists('PLUGINS/USER/'.$GLOBALS['piece2'].'.php') xor file_exists('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php'))
	    {
        if (file_exists('PLUGINS/USER/'.$GLOBALS['piece2'].'.php')) {
		   unlink('PLUGINS/USER/'.$GLOBALS['piece2'].'.php');
		   BOT_RESPONSE('Plugin: '.$GLOBALS['piece2'].' removed from: USER dir.');
		   CLI_MSG('!plugin delete on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', deleted: '.$GLOBALS['piece2'], '1');
		   }
	   else if (file_exists('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php')) { 
		   unlink('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php'); 
           BOT_RESPONSE('Plugin: '.$GLOBALS['piece2'].' removed from OWNER dir.');
		   CLI_MSG('!plugin delete on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', deleted: '.$GLOBALS['piece2'], '1');
		   }
	    }
	  else { 
		     BOT_RESPONSE('No such plugin, wrong name?'); 
		     CLI_MSG('!plugin delete on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', NO SUCH PLUGIN: '.$GLOBALS['piece2'], '1');
		   }
	  break;
	
	    case 'move':
        if (file_exists('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php'))
	     {
		   rename('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php', 'PLUGINS/USER/'.$GLOBALS['piece2'].'.php');
           BOT_RESPONSE('Plugin: '.$GLOBALS['piece2'].' moved to USER dir.');
		   CLI_MSG('!plugin move on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', moved plugin to USER: '.$GLOBALS['piece2'], '1');
		 }
		 else if (!file_exists('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php'))
	       {
		     BOT_RESPONSE('No such plugin in OWNER dir, wrong name?');
			 CLI_MSG('!plugin move on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', NO SUCH PLUGIN: '.$GLOBALS['piece2'], '1');
		   }  
	 break;
   }
  }
}
?>