<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Changing string to MD5: !md5 <string>';

function plugin_emd5()
{
 CHANNEL_MSG('(MD5) '.$GLOBALS['args'].'-> '.md5($GLOBALS['args']));
}

?>