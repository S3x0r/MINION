<?php
/* Copyright (c) 2013-2024, minions
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

function logFileNameFormat()
{
    $data = loadValueFromConfigFile('CHANNEL', 'channel').'.'.loadValueFromConfigFile('SERVER', 'server');

    return LOGSDIR."/{$data}.txt";
}
//---------------------------------------------------------------------------------------------------------
function logsInit()
{
    $data = '-------------------------Session Start: '.date('d.m.Y | H:i:s').'-------------------------'.N;

    saveToFile(logFileNameFormat(), $data, 'a');
}
//---------------------------------------------------------------------------------------------------------
function cliLog($data) /* log message +time */
{
    $line = "[".@date('H:i:s')."] {$data}".N;

    if (loadValueFromConfigFile('LOGS', 'logging') == true) {
        saveToFile(logFileNameFormat(), $line, 'a');
    }

    echo $line;
}
