<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows webpage titile: !htmltitle <http://address>';
 $plugin_command = 'htmltitle';

function plugin_htmltitle()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['C_CMD_PREFIX'].'htmltitle <http://address>'); } 
  
  else {
	
		if($file = file_get_contents($GLOBALS['args']))
			{
			  if (preg_match('@<title>([^<]{1,256}).*?</title>@mi', $file, $matches))
				  {
					if (strlen($matches[1]) == 256)
						{
						  $matches[1].='...';
					    }

				BOT_RESPONSE('Title: ' . str_replace("\n", '', str_replace("\r", '', html_entity_decode($matches[1], ENT_QUOTES, 'utf-8'))));
				  }
			}
  }
    CLI_MSG('!htmltitle on: '.$GLOBALS['C_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
}

?>