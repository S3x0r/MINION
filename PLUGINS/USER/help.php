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
        
        CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'help on: '.$GLOBALS['channel'].', by: '.$GLOBALS['nick'], '1');

        BOT_RESPONSE('Core Commands: '.$GLOBALS['CONFIG_CMD_PREFIX'].'load '.$GLOBALS['CONFIG_CMD_PREFIX'].'unload');
        BOT_RESPONSE('Owner Commands: '.$owner_cmd);
        BOT_RESPONSE('User Commands: '.$user_cmd);
    } elseif (!HasOwner($GLOBALS['mask'])) {
              $user_cmd  = implode(' ', $GLOBALS['USER_PLUGINS']);

              CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'help on: '.$GLOBALS['channel'].', by: '.$GLOBALS['nick'], '1');

              BOT_RESPONSE('User Commands: '.$user_cmd);
    }
}
