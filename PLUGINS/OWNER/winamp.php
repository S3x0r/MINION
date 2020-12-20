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
    $plugin_description = "Controls winamp: {$GLOBALS['CONFIG_CMD_PREFIX']}winamp <help>";
    $plugin_command     = 'winamp';

/*
   NEED TO CONFIGURE!
   Specify winamp CLAmp.exe program location
   eg. 'C:\programs\Winamp\CLAmp.exe'
*/
    $GLOBALS['winamp_loc'] = '';
//---

function plugin_winamp()
{
    if (OnEmptyArg('winamp <help> to list commands')) {
    } elseif (!empty($GLOBALS['winamp_loc'])) {
            switch ($GLOBALS['args']) {
                case 'help':
                     response('Winamp commands:');
                     response('winamp stop  - Stop music: !winamp stop');
                     response('winamp pause - Pause music: !winamp pause');
                     response('winamp play  - Play music: !winamp play');
                     response('winamp next  - Next song: !winamp next');
                     response('winamp prev  - Previous song: !winamp prev');
                     response('winamp title - Show song title: !winamp title');
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
                 response('CLAmp not specified!');
        }
    cliLog("[PLUGIN: winamp] Used by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
}

function sendTitle($target)
{
    $title = exec($GLOBALS['winamp_loc'].' /title');
    response("Playing: {$title}, {$target}");
}
