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

function SeenSave()
{
    !is_dir(DATADIR.'/'.SEENDIR) ? @mkdir(DATADIR.'/'.SEENDIR) : false;
    
    $seenDataFull = DATADIR.'/'.SEENDIR.'/';

    substr(getBotChannel(), 0, 1) != '#' ? $chan = loadValueFromConfigFile('CHANNEL', 'channel') : $chan = getBotChannel();

    $data = "Last seen user: ".userPreg()[0]." (".userPreg()[3].") On channel: {$chan}, Date: ".date("d.m.Y").", Time: ".date("H:i:s");

    /* illegal chars for file */
    userPreg()[0] = removeIllegalCharsFromNickname(userPreg()[0]);

    is_file($seenDataFull.userPreg()[0]) ?
        @file_put_contents($seenDataFull.userPreg()[0], $data) : @file_put_contents($seenDataFull.userPreg()[0], $data);
}
//---------------------------------------------------------------------------------------------------------
function setPause()
{
    $GLOBALS['pause'] = true;
}
//---------------------------------------------------------------------------------------------------------
function unsetPause()
{
    unset($GLOBALS['pause']);
}
//---------------------------------------------------------------------------------------------------------
function getPause()
{
    if (isset($GLOBALS['pause'])) {
        return true;
    } else {
             return false;
    }
}
