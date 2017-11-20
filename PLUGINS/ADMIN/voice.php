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
    $plugin_description = 'Gives voice: '.$GLOBALS['CONFIG_CMD_PREFIX'].'voice <nick>';
    $plugin_command = 'voice';

function plugin_voice()
{

    if (OnEmptyArg('voice <nick>')) {
    } else {
        if (BotOpped() == true) {
            CLI_MSG('[PLUGIN: voice] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.$GLOBALS['channel'].
                ' | voiced user: '.$GLOBALS['args'], '1');

            fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +v '.$GLOBALS['args']."\n");
        }
    }
}
