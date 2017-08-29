<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows youtube video title from link: !youtube <link>';
 $plugin_command = 'youtube';

function plugin_youtube()
{

  if(empty($GLOBALS['args'])) { CHANNEL_MSG('Usage: '.$GLOBALS['C_CMD_PREFIX'].'youtube <link>'); } 
  
  else {
         $site = $GLOBALS['args'];
         $content = file_get_contents($site);

         $search = '<meta name="twitter:title" content="';
         $searchb = '<meta name="twitter:description" content="';

         $pieces = explode($search, $content);
         $piece = explode ('">', $pieces[1]);

         $piecesb = explode($searchb, $content);
         $pieceb = explode('">', $piecesb[1]);

         CHANNEL_MSG('Youtube Title: '.htmlspecialchars_decode($piece[0]));
         CLI_MSG('!youtube on: '.$GLOBALS['C_CNANNEL'].', by: '.$GLOBALS['nick']);
	   }

}

?>