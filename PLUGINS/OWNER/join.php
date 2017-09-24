<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Joins channel: '.$GLOBALS['CONFIG_CMD_PREFIX'].'join <#channel>';
    $plugin_command = 'join';

function plugin_join()
{

    if (empty($GLOBALS['args'])) {
        BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'join <#channel>');
    } else {
        JOIN_CHANNEL($GLOBALS['args']);
        CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'join on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '
        .$GLOBALS['nick'].', joining: '.$GLOBALS['args'], '1');
    }
}
