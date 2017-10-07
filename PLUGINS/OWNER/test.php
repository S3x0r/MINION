<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'test plugin';
    $plugin_command = 'test';



function plugin_test()
{

$a = implode(' ', GetBotChannels());

BOT_RESPONSE($a);

BOT_RESPONSE('bot nickname var: '.$GLOBALS['BOT_NICKNAME']);
 
}
