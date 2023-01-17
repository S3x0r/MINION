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

function on_bot_opped()
{
    debug("on_bot_opped()");

    /* 1. set var that we have op */
    $GLOBALS['BOT_OPPED'] = 'yes';

    /* play sound */
    PlaySound('prompt.mp3');

    /* set bans and modes in channel */
    setChannelModesAndBans();
}
//---------------------------------------------------------------------------------------------------------
function setChannelModesAndBans()
{
    debug("setChannelModesAndBans()");

    /* set bans from config */
    if (!empty(loadValueFromConfigFile('BANS', 'ban.list'))) {
        $banList = explode(', ', loadValueFromConfigFile('BANS', 'ban.list'));
        foreach ($banList as $ban_address) {
            toServer('MODE '.getBotChannel().' +b '.$ban_address);
        }
    }

    /* set channel modes from config */
    if (loadValueFromConfigFile('AUTOMATIC', 'keep.chan.modes') == 'yes' && BotOpped() == true) { //FIX: keep modes
        if (isset($GLOBALS['CHANNEL.MODES']) && $GLOBALS['CHANNEL.MODES'] != loadValueFromConfigFile('CHANNEL', 'channel.modes')) {
            sleep(1);
            toServer("MODE ".getBotChannel()." +".loadValueFromConfigFile('CHANNEL', 'channel.modes'));
        }

        if (!isset($GLOBALS['CHANNEL.MODES'])) {
            sleep(1);
            toServer("MODE ".getBotChannel()." +".loadValueFromConfigFile('CHANNEL', 'channel.modes'));
        }
    }
}
//---------------------------------------------------------------------------------------------------------
