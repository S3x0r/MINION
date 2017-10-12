<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Sends raw string to server: '.$GLOBALS['CONFIG_CMD_PREFIX'].'raw <string>';
    $plugin_command = 'raw';

function plugin_raw()
{
    
    if (OnEmptyArg('raw <string>')) {
    } else {
             $msg = $GLOBALS['piece1'].' '.$GLOBALS['piece2'].' '.$GLOBALS['piece3'].' '.$GLOBALS['piece4']."\n";
             fputs($GLOBALS['socket'], $msg);
             CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'raw on: '.$GLOBALS['channel'].
               ', by: '.$GLOBALS['USER'].', cmd: '.$msg, '1');
    }
}
