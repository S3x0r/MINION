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

    $VERIFY             = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = "Shows BOT uptime: {$GLOBALS['CONFIG.CMD.PREFIX']}uptime";
    $plugin_command     = 'uptime';

function plugin_uptime()
{
    $uptime = uptimeCount(microtime(true) - START_TIME);

    response("I've been running since (".date('d.m.Y, H:i:s', START_TIME).") and been running for {$uptime}");
}

function uptimeCount($s)
{
    $weeks = (floor($s / (60 * 60) / 24)) / 7;
    $days = (floor($s / (60 * 60) / 24)) % 7;
    $hours = (floor($s / (60 * 60))) % 24;

    $divisor_for_minutes = $s % (60 * 60);
    $minutes = floor($divisor_for_minutes / 60);

    $divisor_for_seconds = $divisor_for_minutes % 60;
    $s = ceil($divisor_for_seconds);

    $result = '';
    
    !empty($weeks) && $days > 0 ? $result .= $weeks.' week' : false;
    $weeks > 1 ? $result .= 's' : false;
    !empty($days) && $days > 0 ? $result .= $days.' day' : false;
    $days > 1 ? $result .= 's' : false;
    !empty($hours) && $hours > 0 ? $result .= $hours.' hour' : false;
    $hours > 1 ? $result .= 's' : false;
    !empty($minutes) && $minutes > 0 ? $result .= ' '.$minutes.' minute' : false;
    $minutes > 1 ? $result .= 's' : false;
    !empty($s) && $s > 0 ? $result .= ' '.$s.' second' : false;
    $s > 1 ? $result .= 's' : false;

    return trim($result);
}