<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Gives op: '.$GLOBALS['CONFIG_CMD_PREFIX'].'op <nick>';
    $plugin_command = 'op';

function plugin_op()
{

    if (OnEmptyArg('op <nick>')) {
    } else {
              if (BotOpped() == true) {
                  CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'op on: '.$GLOBALS['channel'].', by: '
                  .$GLOBALS['USER'].', for: '.$GLOBALS['args'], '1');

                  fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +o '.$GLOBALS['args']."\n");
              }
    }
}
