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
    $plugin_description = 'Downloads plugins from repository: '.$GLOBALS['CONFIG_CMD_PREFIX'].
    'fetch list, '.$GLOBALS['CONFIG_CMD_PREFIX'].'fetch get <plugin>';
    $plugin_command = 'fetch';

function plugin_fetch()
{

    if (OnEmptyArg('fetch list / fetch get <plugin>')) {
    } else {
        if ($GLOBALS['args'] == 'list') {
            $addr_list = 'https://raw.githubusercontent.com/S3x0r/minion_repository_plugins/master/plugin_list.db';
            $list = file_get_contents($addr_list);
            BOT_RESPONSE('Repository list:');
            BOT_RESPONSE($list);
            BOT_RESPONSE('End list.');
        } elseif ($GLOBALS['piece1'] == 'get') {
            if (!empty($GLOBALS['piece2'])) {
                $address = $GLOBALS['CONFIG_FETCH_SERVER'].'/'.$GLOBALS['piece2'].'.php';
                BOT_RESPONSE('Downloading plugin: "'.$GLOBALS['piece2'].'" from repository');
                $check_file = 'PLUGINS/'.$GLOBALS['piece2'].'.php';

                if (file_exists($check_file)) {
                    BOT_RESPONSE('I already have this plugin, aborting.');
                }
            } else {
                if (!empty($GLOBALS['piece2'])) {
                    $file = file_get_contents($address);
                    $a = fopen('PLUGINS/'.$GLOBALS['piece2'].'.php', 'w');
  
                    if (!fwrite($a, $file)) {
                        BOT_RESPONSE('no such plugin in repository');
                        fclose($a);
                        $delete = 'PLUGINS/'.$GLOBALS['piece2'].'.php';
                        unlink($delete);
                        CLI_MSG('!fetch: '.$GLOBALS['channel'].', by: '.$GLOBALS['USER'].', plugin: '
                        .$GLOBALS['piece2'], '1');
                    } else {
                        fclose($a);
                        BOT_RESPONSE('Plugin added.');
                        CLI_MSG('!fetch: '.$GLOBALS['channel'].', by: '.$GLOBALS['USER'].', plugin: '
                        .$GLOBALS['piece2'], '1');
                    }
                }
            }
        }
    }
}
