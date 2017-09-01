<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT configuration: !showconfig';
 $plugin_command = 'showconfig';

function plugin_showconfig()
{
 BOT_RESPONSE('My Config');
 BOT_RESPONSE('Nick: '.$GLOBALS['C_NICKNAME'].'');
 BOT_RESPONSE('Name: '.$GLOBALS['C_NAME'].', Ident: '.$GLOBALS['C_IDENT'].'');
 BOT_RESPONSE('Server: '.$GLOBALS['C_SERVER'].':'.$GLOBALS['C_PORT'].', Try connect: '.$GLOBALS['C_TRY_CONNECT'].', Delay: '.$GLOBALS['C_CONNECT_DELAY'].'');
 BOT_RESPONSE('Auto join: '.$GLOBALS['C_AUTO_JOIN'].', Channel(s): '.$GLOBALS['C_CNANNEL'].'');
 BOT_RESPONSE('Command prefix: '.$GLOBALS['C_CMD_PREFIX'].'');
 BOT_RESPONSE('CTCP response: '.$GLOBALS['C_CTCP_RESPONSE'].', CTCP version: '.$GLOBALS['C_CTCP_VERSION'].', CTCP finger: '.$GLOBALS['C_CTCP_FINGER'].'');
 BOT_RESPONSE('Show raw: '.$GLOBALS['C_SHOW_RAW'].'');

 CLI_MSG('!showconfig on: '.$GLOBALS['C_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
}

?>