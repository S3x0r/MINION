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
    $plugin_description = "Pings host/ip: ".loadValueFromConfigFile('COMMAND', 'command.prefix')."ping <host/ip>";
    $plugin_command     = 'ping';

function plugin_ping()
{
    if (OnEmptyArg('ping <host/ip>')) {
    } elseif (ifWindowsOs()) {
              $ip = gethostbyname(msgAsArguments());

              if ((!preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/', $ip)) and
                 (($ip == msgAsArguments()) or ($ip === false))) {
                   response("Unknown host/ip: '".msgAsArguments()."'");
              } else {
                       $ping = ping($ip);
                if ($ping) {
                    $ping[0] = userPreg()[0].': '.$ping[0];
                    foreach ($ping as $thisline) {
                             response($thisline);
                    }
                }
            }
        } else {
                 response('This plugin works on windows only at this time.');
        }
}

function ping($hostname)
{
    exec('ping '.escapeshellarg($hostname), $list);
    
    if (isset($list[4])) {
        return([$list[2], $list[3], $list[4]]);
    } else {
             return([$list[2], $list[3]]);
    }
}
