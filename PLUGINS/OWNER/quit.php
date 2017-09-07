<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shutdown BOT: !quit';
 $plugin_command = 'quit';

function plugin_quit()
{ 
  /* give op before restart */
  fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['CONFIG_CNANNEL'].' +o '.$GLOBALS['nick']."\n");
  
  fputs($GLOBALS['socket'],"QUIT :http://github.com/S3x0r/davybot\n");
  CLI_MSG('!quit received by: '.$GLOBALS['nick'], '1');
  CLI_MSG('Terminating BOT.', '1');
  CLI_MSG('------------------LOG ENDED: '.date('d.m.Y | H:i:s')."------------------\r\n", '1');
  die();
}

?>