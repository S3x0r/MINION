<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Shows BOT commands: '.$GLOBALS['CONFIG_CMD_PREFIX'].'help';
    $plugin_command = 'help';

function plugin_help()
{

    if (HasOwner($GLOBALS['mask'])) {
        $owner_cmd = implode(' ', $GLOBALS['OWNER_PLUGINS']);
        $user_cmd  = implode(' ', $GLOBALS['USER_PLUGINS']);

        BOT_RESPONSE('Owner Commands:');
        BOT_RESPONSE($owner_cmd);
        BOT_RESPONSE('User Commands:');
        BOT_RESPONSE($user_cmd);
    } elseif (!HasOwner($GLOBALS['mask'])) {
              $user_cmd  = implode(' ', $GLOBALS['USER_PLUGINS']);

              BOT_RESPONSE('User Commands:');
              BOT_RESPONSE($user_cmd);
    }

    CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'help on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
}
