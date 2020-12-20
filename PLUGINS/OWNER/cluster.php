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
    $plugin_description = "Clustering plugin: {$GLOBALS['CONFIG_CMD_PREFIX']}cluster help to list commands";
    $plugin_command     = 'cluster';

function plugin_cluster()
{
    if (OnEmptyArg('cluster <help> to list commands')) {
    } else {
        switch ($GLOBALS['args']) {
            case 'help':
                  response('Cluster commands:');
                  response('cluster help       - Shows this help');
                  response("cluster shutdown   - Bot shutdowns computer: {$GLOBALS['CONFIG_CMD_PREFIX']}".
                           "cluster shutdown <bot_nickname>");
                  response("cluster shutdown * - Bot shutdowns all bots computers: {$GLOBALS['CONFIG_CMD_PREFIX']}".
                           "cluster shutdown *");
                break;
        }
        /* me */
        if ($GLOBALS['piece1'] == 'shutdown' && $GLOBALS['piece2'] == getBotNickname()) {
            response('Shutting down machine...');

            cliLog('SHUTTING DOWN COMPUTER!');
            
            exec('shutdown -s -t 0');
        }
        /* all */
        if ($GLOBALS['piece1'] == 'shutdown' && $GLOBALS['piece2'] == '*') {
            response('Shutting down machine...');

            cliLog('SHUTTING DOWN COMPUTER!');
            
            exec('shutdown -s -t 0');
        }
    }

    cliLog("[PLUGIN: cluster] Used by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
}
