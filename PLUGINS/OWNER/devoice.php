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
              if (BotOpped() == true) {
              
                  CLI_MSG('!devoice on: '.$GLOBALS['channel'].', by: '.$GLOBALS['USER'].
                  ', devoiced: '.$GLOBALS['args'], '1');

                  fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' -v '.$GLOBALS['args']."\n");
              }
    }
}
