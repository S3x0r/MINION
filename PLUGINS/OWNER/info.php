<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Shows info: '.$GLOBALS['CONFIG_CMD_PREFIX'].'info';
    $plugin_command = 'info';

function plugin_info()
{

    CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'info on: '.$GLOBALS['channel'].', by: '
    .$GLOBALS['USER'], '1');

    NICK_MSG('    __                      __           __');
    NICK_MSG('.--|  |.---.-.--.--.--.--. |  |--.-----.|  |_');
    NICK_MSG('|  _  ||  _  |  |  |  |  | |  _  |  _  ||   _|');
    NICK_MSG('|_____||___._|\___/|___  | |_____|_____||____|');
    NICK_MSG('                   |_____|    version '.VER);
    NICK_MSG('----------------------------------------------');
    NICK_MSG('   Author: S3x0r, contact: olisek@gmail.com');
    NICK_MSG('----------------------------------------------');

    if (!empty($GLOBALS['CONFIG_BOT_ADMIN'])) {
        NICK_MSG('Bot Admin: '.$GLOBALS['CONFIG_BOT_ADMIN']);
    }

// NICK_MSG('PHP version: '.PHP_VER);

}
