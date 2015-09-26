<?php

 $plugin_description = 'Shows info: !info';

function info()
{

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :    __                      __           __\n");
 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :.--|  |.---.-.--.--.--.--. |  |--.-----.|  |_\n");
 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :|  _  ||  _  |  |  |  |  | |  _  |  _  ||   _|\n");
 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :|_____||___._|\___/|___  | |_____|_____||____| ".VER."\n");
 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :                   |_____|\n");
 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :----------------------------------------------\n");
 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :  Author: S3x0r (olisek@gmail.com)\n");

}

?>