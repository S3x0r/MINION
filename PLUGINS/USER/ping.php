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
    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Pings a host: '.$GLOBALS['CONFIG_CMD_PREFIX'].'ping <host>';
    $plugin_command = 'ping';

function plugin_ping()
{
    try {
        if (OnEmptyArg('ping <host>')) {
        } else {
                 if (!isset($GLOBALS['OS_TYPE'])) {
                     $ip = gethostbyname($GLOBALS['args']);
                 
                 if ((!preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/', $ip)) and
                      (($ip == $GLOBALS['args']) or ($ip === false))) {
                        BOT_RESPONSE('Unknown host: \''.$GLOBALS['args'].'\'');
                 } else {
                          $ping = ping($ip);
                          if ($ping) {
                              $ping[0] = $GLOBALS['USER'].': '.$ping[0];
                          foreach ($ping as $thisline) {
                                  BOT_RESPONSE($thisline);
                          }
                }
                }
              CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'ping on: '.$GLOBALS['channel'].
                  ', by: '.$GLOBALS['USER'].', address: '.$GLOBALS['args'], '1');
                } else {
                         BOT_RESPONSE('This plugin works on windows only at this time.');
                }
        }
    } catch (Exception $e) {
             BOT_RESPONSE('[ERROR] Exception: plugin_ping() '.$e);
             CLI_MSG('[ERROR] Exception: plugin_ping() '.$e. '1');
    }
}

function ping($hostname)
{
    try {
           exec('ping '.escapeshellarg($hostname), $list);
        if (isset($list[4])) {
            return(array($list[2], $list[3], $list[4]));
        } else {
                  return(array($list[2], $list[3]));
        }
    } catch (Exception $e) {
             BOT_RESPONSE('[ERROR] Exception: ping() '.$e);
             CLI_MSG('[ERROR] Exception: ping() '.$e, '1');
    }
}
