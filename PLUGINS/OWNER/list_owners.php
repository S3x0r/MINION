<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Shows BOT owners: '.$GLOBALS['CONFIG_CMD_PREFIX'].'list_owners';
    $plugin_command = 'list_owners';

function plugin_list_owners()
{

    CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'list_owners on: '.$GLOBALS['channel'].
        ', by: '.$GLOBALS['USER'], '1');

    LoadData($GLOBALS['config_file'], 'ADMIN', 'bot_owners');

    $pieces = explode(", ", $GLOBALS['LOADED']);
    $owners = $pieces;

    $table = $owners;
 
    NICK_MSG('My Owners Hosts:');

    for ($i=0; $i<count($table); $i++) {
         NICK_MSG($table[$i]);
    }
}
