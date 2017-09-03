<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT configuration: !showconfig';
 $plugin_command = 'showconfig';

function plugin_showconfig()
{
 BOT_RESPONSE('My Config');
 BOT_RESPONSE('Nick: '.$GLOBALS['CONFIG_NICKNAME'].'');
 BOT_RESPONSE('Name: '.$GLOBALS['CONFIG_NAME'].', Ident: '.$GLOBALS['CONFIG_IDENT'].'');
 BOT_RESPONSE('Server: '.$GLOBALS['CONFIG_SERVER'].':'.$GLOBALS['CONFIG_PORT'].', Try connect: '.$GLOBALS['CONFIG_TRY_CONNECT'].', Delay: '.$GLOBALS['CONFIG_CONNECT_DELAY'].'');
 BOT_RESPONSE('Auto join: '.$GLOBALS['CONFIG_AUTO_JOIN'].', Channel(s): '.$GLOBALS['CONFIG_CNANNEL'].'');
 BOT_RESPONSE('Command prefix: '.$GLOBALS['CONFIG_CMD_PREFIX'].'');
 BOT_RESPONSE('CTCP response: '.$GLOBALS['CONFIG_CTCP_RESPONSE'].', CTCP version: '.$GLOBALS['CONFIG_CTCP_VERSION'].', CTCP finger: '.$GLOBALS['CONFIG_CTCP_FINGER'].'');
 BOT_RESPONSE('Show raw: '.$GLOBALS['CONFIG_SHOW_RAW'].'');

 CLI_MSG('!showconfig on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
}

?>