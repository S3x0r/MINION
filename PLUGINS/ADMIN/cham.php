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
    $plugin_description = 'Shows random text from file: '.$GLOBALS['CONFIG_CMD_PREFIX'].'cham <nick>';
    $plugin_command = 'cham';

/*
    For use this plugin you must add file to $file var in main bot directory

*/

function plugin_cham()
{
    try {
        if (OnEmptyArg('cham <nick>')) {
        } else {
                 $file = ''; //  <----- here

            if (!empty($file)) {
                $texts = file_get_contents($file);
                $texts = explode("\n", $texts);
                $count = 0;

                shuffle($texts);
                $text = $texts[$count++];

                $who = trim($GLOBALS['args']);

                BOT_RESPONSE($who.': '.$text);

                CLI_MSG('[PLUGIN: cham] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                $GLOBALS['channel'].' | to: '.$who, '1');
            } else {
                     BOT_RESPONSE('no file specified to use plugin');
            }
        }
    } catch (Exception $e) {
                             CLI_MSG('[ERROR]: '.TR_49.' '.__FUNCTION__.' '.TR_50, '1');
    }
}
