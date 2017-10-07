<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Joins channel: '.$GLOBALS['CONFIG_CMD_PREFIX'].'join <#channel>';
    $plugin_command = 'join';

function plugin_join()
{

    if (OnEmptyArg('join <#channel>')) {
    } else {
        CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'join on: '.$GLOBALS['channel'].', by: '
        .$GLOBALS['nick'].', joining: '.$GLOBALS['args'], '1');
        
        JOIN_CHANNEL($GLOBALS['args']);
    }
}
