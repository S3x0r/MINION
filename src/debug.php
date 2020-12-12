<?php
/* Copyright (c) 2013-2020, S3x0r <olisek@gmail.com>
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

//---------------------------------------------------------------------------------------------------------
 !in_array(PHP_SAPI, array('cli', 'cli-server', 'phpdbg')) ?
  exit('This script can\'t be run from a web browser. Use CLI terminal to run it<br>'.
       'Visit <a href="https://github.com/S3x0r/MINION/">this page</a> for more information.') : false;
//---------------------------------------------------------------------------------------------------------

function DEBUG($where)
{
  if (isset($where)) {
        echo N.N."[DEBUG] WHERE: $where".N;
    } else {
              echo N.N.'[DEBUG] ---'.N;
    }
    if (isset($GLOBALS['ex'][0])) {
        echo '[DEBUG] EX0: '.$GLOBALS['ex'][0].N;
    } else {
              echo '[DEBUG] EX0: ---'.N;
    }
    if (isset($GLOBALS['ex'][1])) {
        echo '[DEBUG] EX1: '.$GLOBALS['ex'][1].N;
    } else {
              echo '[DEBUG] EX1: ---'.N;
    }
    if (isset($GLOBALS['ex'][2])) {
        echo '[DEBUG] EX2: '.$GLOBALS['ex'][2].N;
    } else {
              echo '[DEBUG] EX2: ---'.N;
    }
    if (isset($GLOBALS['ex'][3])) {
        echo '[DEBUG] EX3: '.$GLOBALS['ex'][3].N;
    } else {
              echo '[DEBUG] EX3: ---'.N;
    }
    if (isset($GLOBALS['ex'][4])) {
        echo '[DEBUG] EX4: '.$GLOBALS['ex'][4].N;
    } else {
              echo '[DEBUG] EX4: ---'.N;
    }
    if (isset($GLOBALS['ex'][5])) {
        echo '[DEBUG] EX5: '.$GLOBALS['ex'][5].N;
    } else {
              echo '[DEBUG] EX5: ---'.N;
    }
    if (isset($GLOBALS['ex'][6])) {
        echo '[DEBUG] EX6: '.$GLOBALS['ex'][6].N;
    } else {
              echo '[DEBUG] EX6: ---'.N;
    }
    if (isset($GLOBALS['channel'])) {
        echo '[DEBUG] CHANNEL: '.$GLOBALS['channel'].N;
    } else {
              echo '[DEBUG] CHANNEL: ---'.N;
    }
    if (isset($GLOBALS['BOT_NICKNAME'])) {
        echo '[DEBUG] NICKNAME: '.$GLOBALS['BOT_NICKNAME'].N;
    } else {
              echo '[DEBUG] NICKNAME: ---'.N;
    }
    if (isset($GLOBALS['BOT_OPPED'])) {
        echo '[DEBUG] BOT_OPPED: '.$GLOBALS['BOT_OPPED'].N;
    } else {
              echo '[DEBUG] BOT_OPPED: ---'.N;
    }
    if (isset($GLOBALS['USER'])) {
        echo '[DEBUG] USER: '.$GLOBALS['USER'].N;
    } else {
              echo '[DEBUG] USER: ---'.N;
    }
    if (isset($GLOBALS['USER_HOST'])) {
        echo '[DEBUG] USER_HOST: '.$GLOBALS['USER_HOST'].N;
    } else {
              echo '[DEBUG] USER_HOST: ---'.N;
    }
    if (isset($GLOBALS['CHANNEL_MODES'])) {
        echo '[DEBUG] CHANNEL_MODES: '.$GLOBALS['CHANNEL_MODES'].NN;
    } else {
              echo '[DEBUG] CHANNEL_MODES: ---'.NN;
    }
}
