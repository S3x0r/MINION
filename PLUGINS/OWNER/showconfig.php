<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT configuration: '.$GLOBALS['CONFIG_CMD_PREFIX'].'showconfig';
 $plugin_command = 'showconfig';

function plugin_showconfig()
{
 NICK_MSG('My Config:');

 NICK_MSG('Nick: '.$GLOBALS['CONFIG_NICKNAME'].' Name: '.$GLOBALS['CONFIG_NAME'].', Ident: '.$GLOBALS['CONFIG_IDENT'].' Bot response: '.$GLOBALS['CONFIG_BOT_RESPONSE'].'');
 
 NICK_MSG('Server: '.$GLOBALS['CONFIG_SERVER'].':'.$GLOBALS['CONFIG_PORT'].', Try connect: '.$GLOBALS['CONFIG_TRY_CONNECT'].', Delay: '.$GLOBALS['CONFIG_CONNECT_DELAY'].'');
 
 NICK_MSG('Auto join: '.$GLOBALS['CONFIG_AUTO_JOIN'].' Auto rejoin: '.$GLOBALS['CONFIG_AUTO_REJOIN'].' Auto op: '.$GLOBALS['CONFIG_AUTO_OP'].'  Channel(s): '.$GLOBALS['CONFIG_CNANNEL'].'');

 NICK_MSG('Auto op list: '.$GLOBALS['CONFIG_AUTO_OP_LIST'].' Bot owners: '.$GLOBALS['CONFIG_OWNERS'].'');
 
 NICK_MSG('Command prefix: '.$GLOBALS['CONFIG_CMD_PREFIX'].'');

 NICK_MSG('CTCP response: '.$GLOBALS['CONFIG_CTCP_RESPONSE'].', CTCP version: '.$GLOBALS['CONFIG_CTCP_VERSION'].', CTCP finger: '.$GLOBALS['CONFIG_CTCP_FINGER'].'');

 NICK_MSG('Logging: '.$GLOBALS['CONFIG_SHOW_RAW'].' Show raw: '.$GLOBALS['CONFIG_SHOW_RAW'].' Time zone: '.$GLOBALS['CONFIG_TIMEZONE'].'');

 NICK_MSG('Fetch server: '.$GLOBALS['CONFIG_FETCH_SERVER'].'');

 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'showconfig on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
}

?>