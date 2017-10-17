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

    $plugin_description = 'Dns: '.$GLOBALS['CONFIG_CMD_PREFIX'].'dns <address>';
    $plugin_command = 'dns';

function plugin_dns()
{
    try {
           if (OnEmptyArg('dns <address>')) {
           } else {
                    $host = gethostbyaddr(trim($GLOBALS['args']));
                    BOT_RESPONSE('host: '.$host);

                    CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'dns on: '.$GLOBALS['channel'].', by: '.
                    $GLOBALS['USER'].', dns: '.$GLOBALS['args'].'/ '.$host, '1');
           }
    } catch (Exception $e) {
                          BOT_RESPONSE(TR_49.' plugin_dns() '.TR_50);
                          CLI_MSG('[ERROR]: '.TR_49.' plugin_dns() '.TR_50, '1');
    }
}
