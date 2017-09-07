<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Searchs wikipedia: !wikipedia <lang> <string>';
 $plugin_command = 'wikipedia';

function plugin_wikipedia()
{
  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'wikipedia <lang> <string>'); } 
  
  else {
         $query = $GLOBALS['piece2'].' '.$GLOBALS['piece3'].' '.$GLOBALS['piece4'];
         $json  = file_get_contents('https://'.$GLOBALS['piece1'].'.wikipedia.org/w/api.php?action=opensearch&list=search&search='.urlencode($query));
         $json  = json_decode($json);

         for($i = 0; $i < 3; $i++) {
         if(isset($json[1][$i])) {
         $resultTitle = $json[1][$i];
         $resultUrl   = $json[3][$i];

         BOT_RESPONSE($resultTitle.' - '.$resultUrl);
         }
        }
       }
  
  CLI_MSG('!wikipedia on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', search: '.$query, '1');
}

?>