<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Devoice user: '.$GLOBALS['CONFIG_CMD_PREFIX'].'devoice <nick>';
    $plugin_command = 'devoice';

function plugin_devoice()
{

    if (OnEmptyArg('devoice <nick>')) {
    } else {
              CLI_MSG('!devoice on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].
                  ', devoiced: '.$GLOBALS['args'], '1');

              fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['CONFIG_CNANNEL'].' -v '.$GLOBALS['args']."\n");
    }
}
