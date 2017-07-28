<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT configuration: !showconfig';
 $plugin_command = 'showconfig';

function plugin_showconfig()
{
 CHANNEL_MSG('My Config');
 CHANNEL_MSG('Nick: '.$GLOBALS['C_NICKNAME'].'');
 CHANNEL_MSG('Name: '.$GLOBALS['C_NAME'].', Ident: '.$GLOBALS['C_IDENT'].'');
 CHANNEL_MSG('Server: '.$GLOBALS['C_SERVER'].':'.$GLOBALS['C_PORT'].', Try connect: '.$GLOBALS['C_TRY_CONNECT'].', Delay: '.$GLOBALS['C_CONNECT_DELAY'].'');
 CHANNEL_MSG('Auto join: '.$GLOBALS['C_AUTO_JOIN'].', Channel(s): '.$GLOBALS['C_CNANNEL'].'');
 CHANNEL_MSG('Command prefix: '.$GLOBALS['C_CMD_PREFIX'].'');
 CHANNEL_MSG('CTCP response: '.$GLOBALS['C_CTCP_RESPONSE'].', CTCP version: '.$GLOBALS['C_CTCP_VERSION'].', CTCP finger: '.$GLOBALS['C_CTCP_FINGER'].'');
 CHANNEL_MSG('Show raw: '.$GLOBALS['C_SHOW_RAW'].'');

 CLI_MSG('!showconfig on: '.$GLOBALS['C_CNANNEL']);
}

?>