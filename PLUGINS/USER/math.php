<?php
/* Copyright (c) 2013-2017, S3x0r <olisek@gmail.com>
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

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
