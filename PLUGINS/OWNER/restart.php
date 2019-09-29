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
    $plugin_description = 'Restarts Bot: '.$GLOBALS['CONFIG_CMD_PREFIX'].'restart';
    $plugin_command = 'restart';

function plugin_restart()
{
    /* give op before restart */
    if (BotOpped() == true) {
        fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +o '.$GLOBALS['USER'].PHP_EOL);
    }

    /* quit from irc server */
    fputs($GLOBALS['socket'], "QUIT :Restarting...\n");

    /* send cli messages */
    CLI_MSG('[PLUGIN: restart] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.$GLOBALS['channel'], '1');
    CLI_MSG('Restarting BOT...', '1');
  
    /* execute batch script */
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        system('cd .. & START_BOT.BAT');
    } else {
              system('php -f BOT.php');
    }
    /* kill old script */
    exit;
}
