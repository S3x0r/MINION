<?php

 $plugin_description = 'Changing string to MD5: !md5 <string>';

function emd5()
{
 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel'].' :(MD5) '.$GLOBALS['args'].'-> '.md5($GLOBALS['args'])."\n");

}

?>