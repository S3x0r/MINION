<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Dns: '.$GLOBALS['CONFIG_CMD_PREFIX'].'dns <address>';
    $plugin_command = 'dns';

function plugin_dns()
{
    try {
           if (OnEmptyArg('dns <address>')) {
           } else {
                    $host = gethostbyaddr(trim($GLOBALS['args']));
                    BOT_RESPONSE('host: '.$host);

                    CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'dns on: '.$GLOBALS['channel'].', by: '.
                    $GLOBALS['USER'].', dns: '.$GLOBALS['args'].'/ '.$host, '1');
           }
    } catch (Exception $e) {
                          BOT_RESPONSE(TR_49.' plugin_dns() '.TR_50);
                          CLI_MSG('[ERROR]: '.TR_49.' plugin_dns() '.TR_50, '1');
    }
}
