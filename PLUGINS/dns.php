<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Dns: !dns <address>';

function plugin_dns()
{
	CHANNEL_MSG(gethostbyaddr(trim($GLOBALS['args'])));
}

?>