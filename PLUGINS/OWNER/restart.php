<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Restarts Bot: '.$GLOBALS['CONFIG_CMD_PREFIX'].'restart';
    $plugin_command = 'restart';

function plugin_restart()
{

    /* give op before restart */
    if (BotOpped() == true) {
        fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +o '.$GLOBALS['USER']."\n");
    }

    /* quit from irc server */
    fputs($GLOBALS['socket'], "QUIT :Restarting...\n");

    /* send cli messages */
    CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'restart on: '.$GLOBALS['channel'].' by: '.$GLOBALS['USER'], '1');
    CLI_MSG('Restarting BOT...', '1');
  
    /* execute batch script */
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        system('START_BOT.BAT');
    } else {
              system('php -f BOT.php');
    }
    /* kill old script */
    die();
}
