<?php
/* Copyright (c) 2013-2018, S3x0r <olisek@gmail.com>
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
    die('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
         Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>');
}
//---------------------------------------------------------------------------------------------------------
function ErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        return false;
    }

    switch ($errno) {
        case E_USER_ERROR:
            CLI_MSG("[ERROR]: [$errno] $errstr", '1');
            CLI_MSG(TR_54." $errline ".TR_55." $errfile, PHP".PHP_VERSION." (".PHP_OS.")", '1');
            CLI_MSG(TR_56, '1');
            exit(1);
            break;

        case E_USER_WARNING:
            CLI_MSG("[WARNING]: [$errno] $errstr", '1');
            break;

        case E_USER_NOTICE:
            CLI_MSG("[NOTICE]: [$errno] $errstr", '1');
            break;

        default:
             CLI_MSG("Error: $errstr", '1');
            break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}
//---------------------------------------------------------------------------------------------------------
function DEBUG($where)
{
    if (isset($where)) {
        echo PHP_EOL.PHP_EOL."[DEBUG] WHERE: $where".PHP_EOL;
    } else {
              echo PHP_EOL.PHP_EOL.'[DEBUG] ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][0])) {
        echo '[DEBUG] EX0: '.$GLOBALS['ex'][0].PHP_EOL;
    } else {
              echo '[DEBUG] EX0: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][1])) {
        echo '[DEBUG] EX1: '.$GLOBALS['ex'][1].PHP_EOL;
    } else {
              echo '[DEBUG] EX1: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][2])) {
        echo '[DEBUG] EX2: '.$GLOBALS['ex'][2].PHP_EOL;
    } else {
              echo '[DEBUG] EX2: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][3])) {
        echo '[DEBUG] EX3: '.$GLOBALS['ex'][3].PHP_EOL;
    } else {
              echo '[DEBUG] EX3: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][4])) {
        echo '[DEBUG] EX4: '.$GLOBALS['ex'][4].PHP_EOL;
    } else {
              echo '[DEBUG] EX4: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][5])) {
        echo '[DEBUG] EX5: '.$GLOBALS['ex'][5].PHP_EOL;
    } else {
              echo '[DEBUG] EX5: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][6])) {
        echo '[DEBUG] EX6: '.$GLOBALS['ex'][6].PHP_EOL;
    } else {
              echo '[DEBUG] EX6: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['channel'])) {
        echo '[DEBUG] CHANNEL: '.$GLOBALS['channel'].PHP_EOL;
    } else {
              echo '[DEBUG] CHANNEL: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['BOT_NICKNAME'])) {
        echo '[DEBUG] NICKNAME: '.$GLOBALS['BOT_NICKNAME'].PHP_EOL;
    } else {
              echo '[DEBUG] NICKNAME: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['BOT_OPPED'])) {
        echo '[DEBUG] BOT_OPPED: '.$GLOBALS['BOT_OPPED'].PHP_EOL;
    } else {
              echo '[DEBUG] BOT_OPPED: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['USER'])) {
        echo '[DEBUG] USER: '.$GLOBALS['USER'].PHP_EOL;
    } else {
              echo '[DEBUG] USER: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['USER_HOST'])) {
        echo '[DEBUG] USER_HOST: '.$GLOBALS['USER_HOST'].PHP_EOL;
    } else {
              echo '[DEBUG] USER_HOST: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['CHANNEL_MODES'])) {
        echo '[DEBUG] CHANNEL_MODES: '.$GLOBALS['CHANNEL_MODES'].PHP_EOL.PHP_EOL;
    } else {
              echo '[DEBUG] CHANNEL_MODES: ---'.PHP_EOL.PHP_EOL;
    }
}
