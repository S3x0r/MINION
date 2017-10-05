<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Shows webpage titile: '.$GLOBALS['CONFIG_CMD_PREFIX'].'htmltitle <address>';
    $plugin_command = 'htmltitle';

function plugin_htmltitle()
{

    if (OnEmptyArg('htmltitle <address>')) {
    } else {
        if ($file = file_get_contents('http://'.$GLOBALS['args'])) {
            if (preg_match('@<title>([^<]{1,256}).*?</title>@mi', $file, $matches)) {
                if (strlen($matches[1]) == 256) {
                    $matches[1].='...';
                }

                CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'htmltitle on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');

                BOT_RESPONSE('Title: '.
                    str_replace("\n", '', str_replace("\r", '', html_entity_decode($matches[1], ENT_QUOTES, 'utf-8'))));
            }
        }
    }
}
