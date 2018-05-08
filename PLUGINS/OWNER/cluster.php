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

if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
         Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>');
}
    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Clustering plugin: '.$GLOBALS['CONFIG_CMD_PREFIX'].'cluster help to list commands';
    $plugin_command = 'cluster';

function plugin_cluster()
{
    if (OnEmptyArg('cluster <help> to list commands')) {
    } else {
        switch ($GLOBALS['args']) {
            case 'help':
                  BOT_RESPONSE('Cluster commands:');
                  BOT_RESPONSE('cluster help       - Shows this help');
                  BOT_RESPONSE('cluster shutdown   - Bot shutdowns computer: '.$GLOBALS['CONFIG_CMD_PREFIX'].
                      'cluster shutdown <bot_nickname>');
                  BOT_RESPONSE('cluster shutdown * - Bot shutdowns all bots computers: '.$GLOBALS['CONFIG_CMD_PREFIX'].
                      'cluster shutdown *');
                break;
        }
        /* me */
        if ($GLOBALS['piece1'] == 'shutdown' && $GLOBALS['piece2'] == $GLOBALS['BOT_NICKNAME']) {
            BOT_RESPONSE('Shutting down machine...');
            
            CLI_MSG('[PLUGIN: cluster] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                    $GLOBALS['channel'], '1');
            CLI_MSG('SHUTTING DOWN COMPUTER!', '1');
            
            exec('shutdown -s -t 0');
        }
        /* all */
        if ($GLOBALS['piece1'] == 'shutdown' && $GLOBALS['piece2'] == '*') {
            BOT_RESPONSE('Shutting down machine...');

            CLI_MSG('[PLUGIN: cluster] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                    $GLOBALS['channel'], '1');
            CLI_MSG('SHUTTING DOWN COMPUTER!', '1');
            
            exec('shutdown -s -t 0');
        }
    }
}
