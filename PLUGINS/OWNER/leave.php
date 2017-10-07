<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Leave channel: '.$GLOBALS['CONFIG_CMD_PREFIX'].'leave <#channel>';
    $plugin_command = 'leave';

function plugin_leave()
{

    if (OnEmptyArg('leave <#channel>')) {
    } else {
             CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'leave on: '.$GLOBALS['channel'].', by: '
             .$GLOBALS['nick'].', leaving: '.$GLOBALS['args'], '1');

             fputs($GLOBALS['socket'], 'PART '.$GLOBALS['args']."\n");
    }
}
