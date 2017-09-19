<?php
if (PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

    $plugin_description = 'Plugins manipulation: '.$GLOBALS['CONFIG_CMD_PREFIX'].'plugin help to list commands';
    $plugin_command = 'plugin';

function plugin_plugin() {
//---------------------------------------------------------------------------------------------------------
    if (empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'plugin <help> to list commands'); } 
//---------------------------------------------------------------------------------------------------------  
     else {

       switch($GLOBALS['args']) {
//---------------------------------------------------------------------------------------------------------
		case 'help': 
		BOT_RESPONSE('Plugin commands:');
		BOT_RESPONSE('plugin delete - Deletes plugin from directory: '.$GLOBALS['CONFIG_CMD_PREFIX'].'plugin delete <plugin_name>');
		BOT_RESPONSE('plugin move   - Move plugin from OWNER dir to USER directory: '.$GLOBALS['CONFIG_CMD_PREFIX'].'plugin move <plugin_name>');
		BOT_RESPONSE('plugin load   - Load plugin to BOT: '.$GLOBALS['CONFIG_CMD_PREFIX'].'plugin load <plugin_name>');	
		BOT_RESPONSE('plugin unload - Unload plugin from BOT: '.$GLOBALS['CONFIG_CMD_PREFIX'].'plugin unload <plugin_name>');	
        break;
//---------------------------------------------------------------------------------------------------------
	    }

       switch($GLOBALS['piece1']) {
//---------------------------------------------------------------------------------------------------------
	    case 'delete':
	    if (file_exists('PLUGINS/USER/'.$GLOBALS['piece2'].'.php') xor file_exists('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php'))
	    {
        if (file_exists('PLUGINS/USER/'.$GLOBALS['piece2'].'.php')) {
		   unlink('PLUGINS/USER/'.$GLOBALS['piece2'].'.php');
		   BOT_RESPONSE('Plugin: '.$GLOBALS['piece2'].' removed from: USER dir.');
		   CLI_MSG('[BOT] !plugin delete on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', deleted: '.$GLOBALS['piece2'], '1');
		   }
	   else if (file_exists('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php')) { 
		   unlink('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php'); 
           BOT_RESPONSE('Plugin: '.$GLOBALS['piece2'].' removed from OWNER dir.');
		   CLI_MSG('[BOT] !plugin delete on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', deleted: '.$GLOBALS['piece2'], '1');
		   }
	    }
	  else { 
		     BOT_RESPONSE('No such plugin, wrong name?'); 
		     CLI_MSG('[BOT] !plugin delete on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', NO SUCH PLUGIN: '.$GLOBALS['piece2'], '1');
		   }
	  break;
//---------------------------------------------------------------------------------------------------------	
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
			 CLI_MSG('[BOT] !plugin move on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', NO SUCH PLUGIN: '.$GLOBALS['piece2'], '1');
		   }  
	 break;
//---------------------------------------------------------------------------------------------------------
        case 'unload':   
        if (!empty($GLOBALS['piece2']))
         {
			$other = $GLOBALS['piece2'];
			/* add to var !+plugin */
			$with_prefix = $GLOBALS['CONFIG_CMD_PREFIX'].$GLOBALS['piece2'];
            
			if (in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) || in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) 
             {                    
                if (($key = array_search($with_prefix, $GLOBALS['OWNER_PLUGINS'])) !== false) {
					unset($GLOBALS['OWNER_PLUGINS'][$key]);
				    if (!in_array($with_prefix, $GLOBALS['OWNER_PLUGINS'])) 
							 { BOT_RESPONSE('Plugin: \''.$other.'\' unloaded'); }
		        }
	 	        if (($key = array_search($with_prefix, $GLOBALS['USER_PLUGINS'])) !== false) {
					unset($GLOBALS['USER_PLUGINS'][$key]); 
				    if (!in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) 
							 { BOT_RESPONSE('Plugin: \''.$other.'\' unloaded'); }
				}
			    CLI_MSG('[BOT] !plugin unload on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', PLUGIN: '.$other, '1');
             } else { BOT_RESPONSE('No such plugin to unload, wrong name?'); }
		 } 
		break;
//---------------------------------------------------------------------------------------------------------
        case 'load': 
		if (!empty($GLOBALS['piece2']))
         {
		    $other = $GLOBALS['piece2'];
			/* add to var !+plugin */
			$with_prefix = $GLOBALS['CONFIG_CMD_PREFIX'].$GLOBALS['piece2'];
                
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
				 break;
//---------------------------------------------------------------------------------------------------------
   }
  }
}
?>