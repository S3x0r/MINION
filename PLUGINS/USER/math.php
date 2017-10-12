<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Solves mathematical tasks: '.$GLOBALS['CONFIG_CMD_PREFIX'].'math <eg. 8*8+6>';
    $plugin_command = 'math';

function plugin_math()
{

    if (OnEmptyArg('math <eg. 8*8+6>')) {
    } else {
              $input = rtrim($GLOBALS['args']);
              $input = preg_replace('/([0-9.]+)\*\*([0-9.]+)/', 'pow($1, $2)', $input);
              $sum = math($input);
        if ($sum == "null") {
        } else {
                  CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'math on: '.$GLOBALS['channel'].
                  ', by: '.$GLOBALS['USER'], '1');
                  
                  BOT_RESPONSE('Value is: '.$sum);
        }
    }
}

function math($input)
{

    $result=eval("return ($input);");
    if ($result == null) {
        BOT_RESPONSE('Invalid characters were assigned in the math function.');
        return "null";
    } else {
        return $result;
    }
}
