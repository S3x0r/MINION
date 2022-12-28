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
    $plugin_description = "Shows BOT configuration: {$GLOBALS['CONFIG.CMD.PREFIX']}showconfig";
    $plugin_command     = 'showconfig';

function plugin_showconfig()
{
    response('My Config:');

    response('[BOT]');
    response('Nickname        : '.$GLOBALS['CONFIG.NICKNAME'].'');
    response('Name            : '.$GLOBALS['CONFIG.NAME'].'');
    response('Ident           : '.$GLOBALS['CONFIG.IDENT'].'');

    response('[Server]');
    response('Server          : '.$GLOBALS['CONFIG.SERVER'].'');
    response('Port            : '.$GLOBALS['CONFIG.PORT'].'');
    response('Server Pass     : '.$GLOBALS['CONFIG.SERVER.PASSWD'].'');
    response('Try Connect     : '.$GLOBALS['CONFIG.TRY.CONNECT'].'');
    response('Connect Delay   : '.$GLOBALS['CONFIG.CONNECT.DELAY'].'');

    response('[OWNER]');
    response('Bot Admin       : '.$GLOBALS['CONFIG.BOT.ADMIN'].'');
    response('Auto OP List    : '.$GLOBALS['CONFIG.AUTO.OP.LIST'].'');
    response('Bot Owners      : '.$GLOBALS['CONFIG.OWNERS'].'');

    response('[ADMIN]');
    response('Admin List      : '.$GLOBALS['CONFIG.ADMIN.LIST'].'');
    
    response('[RESPONSE]');
    response('Bot Response    : '.$GLOBALS['CONFIG.BOT.RESPONSE'].'');
    
    response('[AUTOMATIC]');
    response('Auto OP         : '.$GLOBALS['CONFIG.AUTO.OP'].'');
    response('Auto Rejoin     : '.$GLOBALS['CONFIG.AUTO.REJOIN'].'');
    response('Keep Chan Modes : '.$GLOBALS['CONFIG.KEEPCHAN.MODES'].'');
    response('Keep Nick       : '.$GLOBALS['CONFIG.KEEP.NICK'].'');

    response('[CHANNEL]');
    response('Channel         : '.$GLOBALS['CONFIG.CHANNEL'].'');
    response('Auto Join       : '.$GLOBALS['CONFIG.AUTO.JOIN'].'');
    response('Channel Modes   : '.$GLOBALS['CONFIG.CHANNEL.MODES'].'');
    response('Channel Key     : '.$GLOBALS['CONFIG.CHANNEL.KEY'].'');

    response('[BANS]');
    response('Ban List        : '.$GLOBALS['CONFIG.BAN.LIST'].'');

    response('[COMMAND]');
    response('Command Prefix  : '.$GLOBALS['CONFIG.CMD.PREFIX'].'');

    response('[CTCP]');
    response('CTCP Response   : '.$GLOBALS['CONFIG.CTCP.RESPONSE'].'');
    response('CTCP Version    : '.$GLOBALS['CONFIG.CTCP.VERSION'].'');
    response('CTCP Finger     : '.$GLOBALS['CONFIG.CTCP.FINGER'].'');

    response('[DELAYS]');
    response('Channel Delay   : '.$GLOBALS['CONFIG.CHANNEL.DELAY'].'');
    response('Private Delay   : '.$GLOBALS['CONFIG.PRIVATE.DELAY'].'');
    response('Notice Delay    : '.$GLOBALS['CONFIG.NOTICE.DELAY'].'');

    response('[LOGS]');
    response('Logging         : '.$GLOBALS['CONFIG.LOGGING'].'');

    response('[TIME]');
    response('Time Zone       : '.$GLOBALS['CONFIG.TIMEZONE'].'');

    response('[FETCH]');
    response('Fetch Server    : '.$GLOBALS['CONFIG.FETCH.SERVER'].'');

    response('[PROGRAM]');
    response('Check Update    : '.$GLOBALS['CONFIG_CHECK_UPDATE'].'');
    response('Play Sounds     : '.$GLOBALS['CONFIG.PLAY.SOUNDS'].'');

    response('[DEBUG]');
    response('Show RAW        : '.$GLOBALS['CONFIG.SHOW.RAW'].'');
    
    response('End.');
}
