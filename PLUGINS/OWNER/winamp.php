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
    $plugin_description = 'Controls winamp: '.$GLOBALS['CONFIG_CMD_PREFIX'].'winamp <help>';
    $plugin_command = 'winamp';

/*
   NEED TO CONFIGURE!
   Specify winamp CLAmp.exe program location
   eg. 'C:\Dokumenty\programy\Winamp\CLAmp.exe'
*/
    $GLOBALS['winamp_loc'] = '';
//---


function plugin_winamp()
{

    CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'winamp on: '.$GLOBALS['channel'].
        ', by: '.$GLOBALS['USER'], '1');

    if (OnEmptyArg('winamp <help> to list commands')) {
    } else {
        if (!empty($GLOBALS['winamp_loc'])) {
            switch ($GLOBALS['args']) {
                case 'help':
                     BOT_RESPONSE('Winamp commands:');
                     BOT_RESPONSE('winamp stop  - Stop music: !winamp stop');
                     BOT_RESPONSE('winamp pause - Pause music: !winamp pause');
                     BOT_RESPONSE('winamp play  - Play music: !winamp play');
                     BOT_RESPONSE('winamp next  - Next song: !winamp next');
                     BOT_RESPONSE('winamp prev  - Previous song: !winamp prev');
                     BOT_RESPONSE('winamp title - Show song title: !winamp title');
                    break;

                case 'stop':
                     exec($GLOBALS['winamp_loc'].' /stop');
                    break;
                case 'pause':
                     exec($GLOBALS['winamp_loc'].' /pause');
                    break;
                case 'play':
                     exec($GLOBALS['winamp_loc'].' /play');
                     sendTitle($target);
                    break;
                case 'next':
                     exec($GLOBALS['winamp_loc'].' /next');
                     sendTitle($target);
                    break;
                case 'prev':
                     exec($GLOBALS['winamp_loc'].' /prev');
                     sendTitle($target);
                    break;
                case 'title':
                     sendTitle($target);
                    break;
            }
        } else {
                 BOT_RESPONSE('CLAmp not specified!');
        }
    }
}

function sendTitle($target)
{
    $title = exec($GLOBALS['winamp_loc'].' /title');
    BOT_RESPONSE("Playing: ".$title, $target);
}
