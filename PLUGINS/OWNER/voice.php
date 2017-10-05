<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Gives voice: '.$GLOBALS['CONFIG_CMD_PREFIX'].'voice <nick>';
    $plugin_command = 'voice';

function plugin_voice()
{

    if (OnEmptyArg('voice <nick>')) {
    } else {
              CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'voice on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '
              .$GLOBALS['nick'].', for: '.$GLOBALS['args'], '1');

              fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['CONFIG_CNANNEL'].' +v '.$GLOBALS['args']."\n");
    }
}
