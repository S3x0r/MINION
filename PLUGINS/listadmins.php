<?php

 $plugin_description = 'Shows BOT admins: !listadmins';

function listadmins()
{
 $table = $GLOBALS['owners'];

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :My Admins:\n");

 for ($i=0; $i<count($table); $i++)
 {
  fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel'].' :'.$table[$i]."\n");
 } 

}

?>