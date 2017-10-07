<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Changing string to MD5: '.$GLOBALS['CONFIG_CMD_PREFIX'].'md5 <string>';
    $plugin_command = 'md5';

function plugin_md5()
{

    if (OnEmptyArg('md5 <string>')) {
    } else {
              $data = str_replace(" ", "", $GLOBALS['args']);
              $md5  = md5($data);

              CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'md5 on: '.$GLOBALS['channel'].
                  ', by: '.$GLOBALS['nick'].', data: '.$data, '1');

              BOT_RESPONSE('(MD5) \''.$data.'\' -> '.$md5);
    }
}
