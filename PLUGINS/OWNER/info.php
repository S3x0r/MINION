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

    $plugin_description = 'Shows info: '.$GLOBALS['CONFIG_CMD_PREFIX'].'info';
    $plugin_command = 'info';

function plugin_info()
{

    CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'info on: '.$GLOBALS['channel'].', by: '
    .$GLOBALS['USER'], '1');

    NICK_MSG('    __                      __           __');
    NICK_MSG('.--|  |.---.-.--.--.--.--. |  |--.-----.|  |_');
    NICK_MSG('|  _  ||  _  |  |  |  |  | |  _  |  _  ||   _|');
    NICK_MSG('|_____||___._|\___/|___  | |_____|_____||____|');
    NICK_MSG('                   |_____|    version '.VER);
    NICK_MSG('----------------------------------------------');
    NICK_MSG('   Author: S3x0r, contact: olisek@gmail.com');
    NICK_MSG('----------------------------------------------');

    if (!empty($GLOBALS['CONFIG_BOT_ADMIN'])) {
        NICK_MSG('Bot Admin: '.$GLOBALS['CONFIG_BOT_ADMIN']);
    }

// NICK_MSG('PHP version: '.PHP_VER);

}
