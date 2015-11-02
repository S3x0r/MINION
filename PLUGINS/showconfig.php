<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT configuration: !showconfig';

function plugin_showconfig()
{

 CHANNEL_MSG('My Config');
 CHANNEL_MSG('Nick: '.$GLOBALS['nickname'].', Alternative: '.$GLOBALS['alternative_nick'].'');
 CHANNEL_MSG('Name: '.$GLOBALS['name'].', Ident: '.$GLOBALS['ident'].'');
 CHANNEL_MSG('Server: '.$GLOBALS['server'].':'.$GLOBALS['port'].', Try connect: '.$GLOBALS['try_connect'].', Delay: '.$GLOBALS['connect_delay'].'');
 CHANNEL_MSG('Auto join: '.$GLOBALS['auto_join'].', Channel(s): '.$GLOBALS['channel'].'');
 CHANNEL_MSG('Command prefix: '.$GLOBALS['command_prefix'].'');
 CHANNEL_MSG('CTCP response: '.$GLOBALS['ctcp_response'].', CTCP version: '.$GLOBALS['ctcp_version'].', CTCP finger: '.$GLOBALS['ctcp_finger'].'');
 CHANNEL_MSG('Show raw: '.$GLOBALS['show_raw'].'');

  MSG('!showconfig on: '.$GLOBALS['channel']);
}

?>