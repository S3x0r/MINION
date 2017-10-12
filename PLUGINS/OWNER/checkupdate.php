<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Checking for updates: '.$GLOBALS['CONFIG_CMD_PREFIX'].'checkupdate';
    $plugin_command = 'checkupdate';
//------------------------------------------------------------------------------------------------
function plugin_checkupdate()
{
    global $CheckVersion;
    $addr = 'https://raw.githubusercontent.com/S3x0r/version-for-BOT/master/VERSION.TXT';

    CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'checkupdate on: '.$GLOBALS['channel'].', by: '.$GLOBALS['USER'], '1');

    $CheckVersion = file_get_contents($addr);

    if ($CheckVersion !='') {
        checkVersion();
    } else {
              BOT_RESPONSE('Cannot connect to update server, try next time.');
    }
}
//------------------------------------------------------------------------------------------------
function checkVersion()
{
    global $CheckVersion;

    $version = explode("\n", $CheckVersion);

    if ($version[0] > VER) {
        BOT_RESPONSE('New version available!');
        BOT_RESPONSE('My version: '.VER.', version on server: '.$version[0].'');
        BOT_RESPONSE('To update me use '.$GLOBALS['CONFIG_CMD_PREFIX'].'update');
    } else {
              BOT_RESPONSE('No new update, you have the latest version.');
    }
}
