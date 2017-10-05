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
              CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'topic on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '
              .$GLOBALS['nick'].', New topic: \''.msg_without_command().'\'', '1');
              
              fputs($GLOBALS['socket'], 'TOPIC '.$GLOBALS['CONFIG_CNANNEL'].' '.msg_without_command()."\n");
    }
}
