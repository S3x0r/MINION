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
    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Kicks from channel: '.$GLOBALS['CONFIG_CMD_PREFIX'].'kick <#channel> <who>';
    $plugin_command = 'kick';

function plugin_kick()
{
    if (OnEmptyArg('kick <#channel> <who>')) {
    } else {
        if (BotOpped() == true) {
            fputs($GLOBALS['socket'], 'KICK '.$GLOBALS['piece1'].' :'.$GLOBALS['piece2'].PHP_EOL);

            CLI_MSG('[PLUGIN: kick] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.$GLOBALS['channel'].
                    ' | kicked user: '.$GLOBALS['args'], '1');
        }
    }
}
