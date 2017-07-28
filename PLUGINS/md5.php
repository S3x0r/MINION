<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Changing string to MD5: !md5 <string>';
 $plugin_command = 'md5';

function plugin_md5()
{
 $data = str_replace(" ","",$GLOBALS['args']);
 $md5  = md5($data);

 CHANNEL_MSG('(MD5) \''.$data.'\' -> '.$md5);

 CLI_MSG('!md5 on: '.$GLOBALS['C_CNANNEL'].', data: '.$data);
}

?>