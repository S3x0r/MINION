<?php
if (PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

    $plugin_description = 'Loads plugin to BOT: '.$GLOBALS['CONFIG_CMD_PREFIX'].'load <plugin_name>';
    $plugin_command = 'load';

function plugin_load() {

    if (empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'load <plugin_name>'); } 
      
	     if (!empty($GLOBALS['piece1']))
              {
				$other = $GLOBALS['piece1'];
				/* add to var !+plugin */
				$with_prefix = $GLOBALS['CONFIG_CMD_PREFIX'].$GLOBALS['piece1'];
                
				if (in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) || in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) 
                 {  
					BOT_RESPONSE('Plugin already Loaded!');
			     }					

     		     /* if there is no plugin name in plugins array */ 
			     else if (!in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) || !in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) 
                  {
					 /* if no plugin in array & file exists in dir */ 
                     if (file_exists('PLUGINS/OWNER/'.$other.'.php')) { 
					
                         /* include that file */
						 include_once('PLUGINS/OWNER/'.$other.'.php');
						 
						 /* add prefix & plugin name to plugins array */
						 array_push($GLOBALS['OWNER_PLUGINS'], $with_prefix);
						 
						 /* bot responses */
						 BOT_RESPONSE('Plugin: \''.$other.'\' Loaded.'); 
			             CLI_MSG('[BOT] !load on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', PLUGIN: '.$other, '1');
						 }

					 /* if no plugin in array & file exists in dir */ 
					 if (file_exists('PLUGINS/USER/'.$other.'.php')) { 

                         /* include that file */
						 include_once('PLUGINS/USER/'.$other.'.php');

						 /* add prefix & plugin name to plugins array */
						 array_push($GLOBALS['USER_PLUGINS'], $with_prefix);

						 /* bot responses */
						 BOT_RESPONSE('Plugin: \''.$other.'\' Loaded.');
	     	             CLI_MSG('[BOT] !load on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', PLUGIN: '.$other, '1');
						 }
	                 }
				  } 
} 
?>