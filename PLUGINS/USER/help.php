<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT commands: '.$GLOBALS['CONFIG_CMD_PREFIX'].'help';
 $plugin_command = 'help';

function plugin_help()
{
  if(HasOwner($GLOBALS['mask']))
   {
     $owner_cmd = file_get_contents('plugins_owner.ini');
     $user_cmd  = file_get_contents('plugins_user.ini');

     BOT_RESPONSE('Owner Commands:');
     BOT_RESPONSE($owner_cmd);
     BOT_RESPONSE('User Commands:');
     BOT_RESPONSE($user_cmd);
   }
  
  else if(!HasOwner($GLOBALS['mask']))
   {
     $user_cmd = file_get_contents('plugins_user.ini');

	 BOT_RESPONSE('User Commands:');
     BOT_RESPONSE($user_cmd);
   }

 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'help on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
}

?>