<?php

 $plugin_description = 'Shows BOT admins: !listadmins';

function listadmins()
{

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :My Admins:\n");
 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel'].' :'.$GLOBALS['admin1']."\n");
//fix it and add admins...
}

?>