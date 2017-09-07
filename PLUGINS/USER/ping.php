<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Pings a host: !ping <host>';
 $plugin_command = 'ping';

function plugin_ping()
{
  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'ping <host>'); } 
  
  else {

  $ip = gethostbyname($GLOBALS['args']);
		
  if((!preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/', $ip)) and (($ip == $GLOBALS['args']) or ($ip === false))) {
  BOT_RESPONSE('Unknown host :'.$GLOBALS['args']); }
   else
	   {
		 $ping = ping($ip);
		  if($ping)
			  {
				$ping[0] = $who.': '.$ping[0];
				foreach ($ping as $thisline) 
				{
				  BOT_RESPONSE($thisline);
			    }
			  }
	   }
		 CLI_MSG('!ping on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', address: '.$GLOBALS['args'], '1');
	   }
}

function ping($hostname)
{
  exec('ping '.escapeshellarg($hostname), $list);
  if(isset($list[4]))
	  {
	    return(array($list[2], $list[3], $list[4]));
	  }
	  else
		  {
			return(array($list[2], $list[3]));
		  }
}

?>