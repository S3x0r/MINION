<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows info: !info';

function plugin_info()
{
 CHANNEL_MSG('    __                      __           __');
 CHANNEL_MSG('.--|  |.---.-.--.--.--.--. |  |--.-----.|  |_');
 CHANNEL_MSG('|  _  ||  _  |  |  |  |  | |  _  |  _  ||   _|');
 CHANNEL_MSG('|_____||___._|\___/|___  | |_____|_____||____|');
 CHANNEL_MSG('                   |_____|    version '.VER);
 CHANNEL_MSG('----------------------------------------------');
 CHANNEL_MSG('   Author: S3x0r, contact: olisek@gmail.com');

 CLI_MSG('!info on: '.$GLOBALS['C_CNANNEL']);
}
?>