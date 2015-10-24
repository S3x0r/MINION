<?php

 $plugin_description = 'Dns: !dns <address>';

function dns()
{
  fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel'].' :'. gethostbyaddr(trim($GLOBALS['args']))."\n");
}

?>