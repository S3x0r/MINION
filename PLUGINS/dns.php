<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Dns: !dns <address>';

function plugin_dns()
{
 $host = gethostbyaddr(trim($GLOBALS['args']));
  
 CHANNEL_MSG('host: '.$host);

 MSG('!dns on: '.$GLOBALS['C_CNANNEL'].', dns: '.$GLOBALS['args'].'/ '.$host);
}
?>