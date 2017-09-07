<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Solves mathematical tasks: !math <eg. 8*8+6>';
 $plugin_command = 'math';

function plugin_math()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'math <eg. 8*8+6>'); } 
  
  else {
		 $input = rtrim($GLOBALS['args']);
         $input = preg_replace('/([0-9.]+)\*\*([0-9.]+)/', 'pow($1, $2)', $input);
         $sum = math($input);
         if($sum == "NULL") { }
                              else {
                                     BOT_RESPONSE('Value is: '.$sum);
                                   }


		 CLI_MSG('!math on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
	  }
}

function math($input)
{
  $result=eval("return ($input);");
  if($result == NULL) {
                        BOT_RESPONSE('Invalid characters were assigned in the math function.');
                        return "NULL";
                      }
                      else { return $result; }
}

?>