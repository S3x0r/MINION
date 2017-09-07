<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows info: !info';
 $plugin_command = 'info';

function plugin_info()
{
 BOT_RESPONSE('    __                      __           __');
 BOT_RESPONSE('.--|  |.---.-.--.--.--.--. |  |--.-----.|  |_');
 BOT_RESPONSE('|  _  ||  _  |  |  |  |  | |  _  |  _  ||   _|');
 BOT_RESPONSE('|_____||___._|\___/|___  | |_____|_____||____|');
 BOT_RESPONSE('                   |_____|    version '.VER);
 BOT_RESPONSE('----------------------------------------------');
 BOT_RESPONSE('   Author: S3x0r, contact: olisek@gmail.com');

 CLI_MSG('!info on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
}
?>