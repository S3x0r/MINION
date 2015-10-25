<?php

 $plugin_description = 'Deops someone: !deop <nick>';

function deop()
{

 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' -o '. $GLOBALS['args'] ."\n");

 MSG('Taking op on: '.$GLOBALS['channel'].', Who: '.$GLOBALS['args']);

}

?>