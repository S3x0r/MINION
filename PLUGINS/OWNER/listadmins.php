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
    $plugin_description = 'Shows BOT admins: '.$GLOBALS['CONFIG_CMD_PREFIX'].'listadmins';
    $plugin_command = 'listadmins';

function plugin_listadmins()
{

    CLI_MSG('[PLUGIN: listadmins] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
        $GLOBALS['channel'], '1');

    LoadData($GLOBALS['config_file'], 'ADMIN', 'admin_list');

    if (empty($GLOBALS['LOADED'])) {
        BOT_RESPONSE('Empty admin list.');
    } else {
             $pieces = explode(", ", $GLOBALS['LOADED']);

             BOT_RESPONSE('My Admin(s) Host(s):');

        for ($i=0; $i<count($pieces); $i++) {
             BOT_RESPONSE($pieces[$i]);
        }
    }
}
