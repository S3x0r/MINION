<?php
if (PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

    $plugin_description = 'Unloads plugin from BOT: '.$GLOBALS['CONFIG_CMD_PREFIX'].'unload <plugin_name>';
    $plugin_command = 'unload';

function plugin_unload() {

    if (empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'unload <plugin_name>'); } 
      
	  else {
		     if (!empty($GLOBALS['piece1']))
              {
				$other = $GLOBALS['piece1'];
				/* add to var !+plugin */
				$with_prefix = $GLOBALS['CONFIG_CMD_PREFIX'].$GLOBALS['piece1'];

                 if (in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) || in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) 
                  {                    
                     if (($key = array_search($with_prefix, $GLOBALS['OWNER_PLUGINS'])) !== false) 
						 {
						    unset($GLOBALS['OWNER_PLUGINS'][$key]);
							//functionrename('plugin_'.$other, 'garbage_'.rand(100,9999));
                            if (!in_array($with_prefix, $GLOBALS['OWNER_PLUGINS'])) 
							 { BOT_RESPONSE('Plugin: \''.$other.'\' unloaded'); }
						 }
		             if (($key = array_search($with_prefix, $GLOBALS['USER_PLUGINS'])) !== false) 
						 { 
						    unset($GLOBALS['USER_PLUGINS'][$key]);
							//rename_function('plugin_'.$other, 'garbage_'.rand(100,9999));
						    if (!in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) 
							 { BOT_RESPONSE('Plugin: \''.$other.'\' unloaded'); }
						 }
			         CLI_MSG('[BOT] !unload on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', PLUGIN: '.$other, '1');
                  } 
				  else { BOT_RESPONSE('No such plugin to unload, wrong name?'); }
		      }

	        }


}
?>