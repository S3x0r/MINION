<?php
if (PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

    $plugin_description = 'TEST PLUGIN: '.$GLOBALS['CONFIG_CMD_PREFIX'].'TEST';
    $plugin_command = 'test';

function plugin_test() {

 BOT_RESPONSE('TEST PLUGIN WORKING!! :)');
 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'test on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');

}
?>