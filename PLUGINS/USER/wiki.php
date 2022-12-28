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
    $plugin_description = "Searchs wikipedia: {$GLOBALS['CONFIG.CMD.PREFIX']}wiki <lang> <string>";
    $plugin_command     = 'wiki';

function plugin_wiki()
{
    if (OnEmptyArg('wikipedia <lang> <string>')) {
    } elseif (extension_loaded('openssl')) {
              $json = @file_get_contents("http://{$GLOBALS['piece1']}.wikipedia.org/w/api.php?action=opensearch&list=search&search=".urlencode(inputFromLine('5')));
            
              if (!empty($json)) {
                  $json = json_decode($json);

                for ($i = 0; $i < 3; $i++) {
                    if (isset($json[1][$i])) {
                        $resultTitle = $json[1][$i];
                        $resultUrl   = $json[3][$i];

                        response("{$resultTitle} - {$resultUrl}");
                    }
                }
            } else {
                     response('No such language.');
            }
        } else {
                 response('I cannot use this plugin, i need php_openssl extension to work!');
        }
}
