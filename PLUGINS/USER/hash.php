<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Changing string to choosed algorithm: '.
    $GLOBALS['CONFIG_CMD_PREFIX'].'hash help to list algorithms';
    $plugin_command = 'hash';

function plugin_hash()
{

    if (OnEmptyArg('hash <algorithm> <string>')) {
    } elseif ($GLOBALS['args'] == 'help') {
              BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'hash <algorithm> <string>');
              BOT_RESPONSE('Valid algos: ' . implode(' ', hash_algos()));
    } else {
              if (hash($GLOBALS['piece1'], $GLOBALS['piece2'])) {
                  CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'hash on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.
                  $GLOBALS['nick'].', string: '.$GLOBALS['piece2'], '1');
                  BOT_RESPONSE($GLOBALS['piece1'].': ' . hash($GLOBALS['piece1'], $GLOBALS['piece2']));
              }
    }
}
