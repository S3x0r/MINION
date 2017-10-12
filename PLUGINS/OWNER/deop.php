<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Deops someone: '.$GLOBALS['CONFIG_CMD_PREFIX'].'deop <nick>';
    $plugin_command = 'deop';

function plugin_deop()
{

    if (OnEmptyArg('deop <nick>')) {
    } else {
              if (BotOpped() == true) {
                  CLI_MSG('!deop on: '.$GLOBALS['channel'].', by: '.$GLOBALS['USER'].', deoped: '
                  .$GLOBALS['args'], '1');

                  fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' -o '. $GLOBALS['args'] ."\n");
              }
    }
}
