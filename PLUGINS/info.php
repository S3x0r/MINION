<?php

 $plugin_description = 'Shows info: !info';

function info()
{
 global $socket;
 global $channel;

 fputs($socket, 'PRIVMSG '.$channel." :    __                      __           __\n");
 fputs($socket, 'PRIVMSG '.$channel." :.--|  |.---.-.--.--.--.--. |  |--.-----.|  |_\n");
 fputs($socket, 'PRIVMSG '.$channel." :|  _  ||  _  |  |  |  |  | |  _  |  _  ||   _|\n");
 fputs($socket, 'PRIVMSG '.$channel." :|_____||___._|\___/|___  | |_____|_____||____| ".VER."\n");
 fputs($socket, 'PRIVMSG '.$channel." :                   |_____|\n");
 fputs($socket, 'PRIVMSG '.$channel." :----------------------------------------------\n");
 fputs($socket, 'PRIVMSG '.$channel." :  Author: S3x0r (olisek@gmail.com)\n");

}

?>