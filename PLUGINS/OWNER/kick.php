<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Kicks from channel: '.$GLOBALS['CONFIG_CMD_PREFIX'].'kick <#channel> <who>';
    $plugin_command = 'kick';

function plugin_kick()
{

    if (OnEmptyArg('kick <#channel> <who>')) {
    } else {
              CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'kick on: '.$GLOBALS['piece1'].', by: '
              .$GLOBALS['nick'].', kicked: '.$GLOBALS['piece2'], '1');

              fputs($GLOBALS['socket'], 'KICK '.$GLOBALS['piece1'].' :'.$GLOBALS['piece2']."\n");
    }
}
