<?php

 $plugin_description = 'Shows BOT configuration: !showconfig';

function showconfig()
{
  fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :My Config\n");
  fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel'].' :Nick: '.$GLOBALS['nickname'].', Alternative: '.$GLOBALS['alternative_nick']."\n");
  fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel'].' :Name: '.$GLOBALS['name'].', Ident: '.$GLOBALS['ident']."\n");
  fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel'].' :Server: '.$GLOBALS['server'].':'.$GLOBALS['port']."\n");
  fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel'].' :Channel(s): '.$GLOBALS['channel']."\n");
}

?>