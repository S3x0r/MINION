<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Changing Topic in channel: '.$GLOBALS['CONFIG_CMD_PREFIX'].'topic <new_topic>';
    $plugin_command = 'topic';

function plugin_topic()
{

    if (OnEmptyArg('topic <new_topic>')) {
    } else {
              if (BotOpped() == true) {
                  CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'topic on: '.$GLOBALS['channel'].', by: '
                  .$GLOBALS['USER'].', New topic: \''.msg_without_command().'\'', '1');

                  fputs($GLOBALS['socket'], 'TOPIC '.$GLOBALS['channel'].' '.msg_without_command()."\n");
              }
    }
}
