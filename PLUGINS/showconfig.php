<?php

 $plugin_description = 'Shows BOT configuration: !showconfig';

function showconfig()
{
  global $socket;
  global $channel;
  fputs($socket, 'PRIVMSG '.$channel." :My Config\n");
  fputs($socket, 'PRIVMSG '.$channel.' :Nick: '.$GLOBALS['nickname'].', Alternative: '.$GLOBALS['alternative_nick']."\n");
  fputs($socket, 'PRIVMSG '.$channel.' :Name: '.$GLOBALS['name'].', Ident: '.$GLOBALS['ident']."\n");
  fputs($socket, 'PRIVMSG '.$channel.' :Server: '.$GLOBALS['server'].':'.$GLOBALS['port']."\n");
  fputs($socket, 'PRIVMSG '.$channel.' :Auto join: '.$GLOBALS['auto_join'].', Channel(s): '.$channel."\n");
  fputs($socket, 'PRIVMSG '.$channel.' :Show raw: '.$GLOBALS['show_raw']."\n");
}

?>