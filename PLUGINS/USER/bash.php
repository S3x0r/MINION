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
    $plugin_description = 'Shows quotes from bash.org: '.$GLOBALS['CONFIG_CMD_PREFIX'].'bash';
    $plugin_command = 'bash';

function plugin_bash()
{
    try {
        if (!file_get_contents('http://bash.org/?random1')) {
            BOT_RESPONSE('Cannot fetch from bash');
        } else {
                 $page = file_get_contents('http://bash.org/?random1');
                 preg_match_all('@<p class="qt">(.*?)</p>@s', $page, $quotes);
        }

        if (!isset($matches[1])) {
            $matches[1] = 3;
        } elseif ($matches[1]>50) {
                  $matches[1] = 50;
        }

        $pr = true;
        for ($i=0; $i < $matches[1]; $i++) {
            if ($pr) {
                       $pr = false;
            } else {
            }
            CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'bash on: '.$GLOBALS['channel'].', by: '.$GLOBALS['USER'], '1');
            BOT_RESPONSE(str_replace('<br />', '', html_entity_decode($quotes[1][$i], ENT_QUOTES)));
        }
    } catch (Exception $e) {
                             BOT_RESPONSE(TR_49.' plugin_bash() '.TR_50);
                             CLI_MSG('[ERROR]: '.TR_49.' plugin_bash() '.TR_50, '1');
    }
}
