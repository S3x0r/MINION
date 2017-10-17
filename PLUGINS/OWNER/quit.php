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

    $plugin_description = 'Shutdown BOT: '.$GLOBALS['CONFIG_CMD_PREFIX'].'quit';
    $plugin_command = 'quit';

function plugin_quit()
{

    /* give op before restart */
    if (BotOpped() == true) {
        fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +o '.$GLOBALS['USER']."\n");
    }

    fputs($GLOBALS['socket'], "QUIT :http://github.com/S3x0r/davybot\n");
    CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'quit received by: '.$GLOBALS['USER'], '1');
    CLI_MSG('Terminating BOT.', '1');
    CLI_MSG('------------------LOG ENDED: '.date('d.m.Y | H:i:s')."------------------\r\n", '1');
    die();
}
