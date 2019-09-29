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

//---------------------------------------------------------------------------------------------------------
PHP_SAPI !== 'cli' ? exit('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
                           Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>') : false;
//---------------------------------------------------------------------------------------------------------

    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Searchs wikipedia: '.$GLOBALS['CONFIG_CMD_PREFIX'].'wikipedia <lang> <string>';
    $plugin_command = 'wikipedia';

function plugin_wikipedia()
{
    if (OnEmptyArg('wikipedia <lang> <string>')) {
    } else {
        if (extension_loaded('openssl')) {
            $query = $GLOBALS['piece2'].' '.$GLOBALS['piece3'].' '.$GLOBALS['piece4'];
            
            $json  = @file_get_contents('http://'.$GLOBALS['piece1'].
            '.wikipedia.org/w/api.php?action=opensearch&list=search&search='.urlencode($query));
            
            if (!empty($json)) {
                $json  = json_decode($json);

                for ($i = 0; $i < 3; $i++) {
                    if (isset($json[1][$i])) {
                        $resultTitle = $json[1][$i];
                        $resultUrl   = $json[3][$i];

                        BOT_RESPONSE($resultTitle.' - '.$resultUrl);
                    }
                }
                CLI_MSG('[PLUGIN: wikipedia] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                        $GLOBALS['channel'].' | find: '.$query, '1');
            } else {
                     BOT_RESPONSE('No such language.');
            }
        } else {
                 BOT_RESPONSE('I cannot use this plugin, i need php_openssl extension to work!');
        }
    }
}
