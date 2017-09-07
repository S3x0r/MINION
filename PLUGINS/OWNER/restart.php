<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Restarts Bot: !restart';
 $plugin_command = 'restart';

function plugin_restart()
{
   /* give op before restart */
  fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['CONFIG_CNANNEL'].' +o '.$GLOBALS['nick']."\n");
  
  /* send restart to server */
  fputs($GLOBALS['socket'],"QUIT :Restarting...\n");

  /* send cli messages */
  CLI_MSG('!restart on: '.$GLOBALS['CONFIG_CNANNEL'].' by: '.$GLOBALS['nick'], '1');
  CLI_MSG('Restarting BOT...', '1');
  
  /* execute batch script */
  system('START_BOT.BAT');

  /* kill old script */
  die();
}

?>