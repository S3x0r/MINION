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
    $plugin_description = 'Shows webpage titile: '.$GLOBALS['CONFIG_CMD_PREFIX'].'webtitle <web address>';
    $plugin_command = 'webtitle';

function plugin_webtitle()
{
    if (OnEmptyArg('webtitle <web address>')) {
    } else {
        if (extension_loaded('openssl')) {
            $data = str_replace('http://', '', str_replace('https://', '', $GLOBALS['args']));
            if ($file = @file_get_contents('http://'.$data)) {
                if (preg_match('@<title>([^<]{1,256}).*?</title>@mi', $file, $matches)) {
                    if (strlen($matches[1]) == 256) {
                        $matches[1].='...';
                    }
                    CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'webtitle on: '.$GLOBALS['channel'].
                    ', by: '.$GLOBALS['USER'], '1');

                    BOT_RESPONSE('Title: '.
                    str_replace("\n", '', str_replace("\r", '', html_entity_decode($matches[1], ENT_QUOTES, 'utf-8'))));
                }
            }
        } else {
                 BOT_RESPONSE('I cannot use this plugin, i need php_openssl extension to work!');
        }
    }
}
