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
    $plugin_description = "Shows BOT configuration: {$GLOBALS['CONFIG_CMD_PREFIX']}showconfig";
    $plugin_command = 'showconfig';

function plugin_showconfig()
{
    response('My Config:');

    response('[BOT]');
    response('Nickname        : '.$GLOBALS['CONFIG_NICKNAME'].'');
    response('Name            : '.$GLOBALS['CONFIG_NAME'].'');
    response('Ident           : '.$GLOBALS['CONFIG_IDENT'].'');

    response('[Server]');
    response('Server          : '.$GLOBALS['CONFIG_SERVER'].'');
    response('Port            : '.$GLOBALS['CONFIG_PORT'].'');
    response('Server Pass     : '.$GLOBALS['CONFIG_SERVER_PASSWD'].'');
    response('Try Connect     : '.$GLOBALS['CONFIG_TRY_CONNECT'].'');
    response('Connect Delay   : '.$GLOBALS['CONFIG_CONNECT_DELAY'].'');

    response('[OWNER]');
    response('Bot Admin       : '.$GLOBALS['CONFIG_BOT_ADMIN'].'');
    response('Auto OP List    : '.$GLOBALS['CONFIG_AUTO_OP_LIST'].'');
    response('Bot Owners      : '.$GLOBALS['CONFIG_OWNERS'].'');

    response('[ADMIN]');
    response('Admin List      : '.$GLOBALS['CONFIG_ADMIN_LIST'].'');
    
    response('[RESPONSE]');
    response('Bot Response    : '.$GLOBALS['CONFIG_BOT_RESPONSE'].'');
    
    response('[AUTOMATIC]');
    response('Auto OP         : '.$GLOBALS['CONFIG_AUTO_OP'].'');
    response('Auto Rejoin     : '.$GLOBALS['CONFIG_AUTO_REJOIN'].'');
    response('Keep Chan Modes : '.$GLOBALS['CONFIG_KEEPCHAN_MODES'].'');
    response('Keep Nick       : '.$GLOBALS['CONFIG_KEEP_NICK'].'');

    response('[CHANNEL]');
    response('Channel         : '.$GLOBALS['CONFIG_CNANNEL'].'');
    response('Auto Join       : '.$GLOBALS['CONFIG_AUTO_JOIN'].'');
    response('Channel Modes   : '.$GLOBALS['CONFIG_CHANNEL_MODES'].'');
    response('Channel Key     : '.$GLOBALS['CONFIG_CHANNEL_KEY'].'');

    response('[BANS]');
    response('Ban List        : '.$GLOBALS['CONFIG_BAN_LIST'].'');

    response('[COMMAND]');
    response('Command Prefix  : '.$GLOBALS['CONFIG_CMD_PREFIX'].'');

    response('[CTCP]');
    response('CTCP Response   : '.$GLOBALS['CONFIG_CTCP_RESPONSE'].'');
    response('CTCP Version    : '.$GLOBALS['CONFIG_CTCP_VERSION'].'');
    response('CTCP Finger     : '.$GLOBALS['CONFIG_CTCP_FINGER'].'');

    response('[DELAYS]');
    response('Channel Delay   : '.$GLOBALS['CONFIG_CHANNEL_DELAY'].'');
    response('Private Delay   : '.$GLOBALS['CONFIG_PRIVATE_DELAY'].'');
    response('Notice Delay    : '.$GLOBALS['CONFIG_NOTICE_DELAY'].'');

    response('[LOGS]');
    response('Logging         : '.$GLOBALS['CONFIG_LOGGING'].'');

    response('[TIME]');
    response('Time Zone       : '.$GLOBALS['CONFIG_TIMEZONE'].'');

    response('[FETCH]');
    response('Fetch Server    : '.$GLOBALS['CONFIG_FETCH_SERVER'].'');

    response('[PROGRAM]');
    response('Show Logo       : '.$GLOBALS['CONFIG_SHOW_LOGO'].'');
    response('Silent Mode     : '.$GLOBALS['silent_mode'].'');
    response('Check Update    : '.$GLOBALS['CONFIG_CHECK_UPDATE'].'');
    response('Play Sounds     : '.$GLOBALS['CONFIG_PLAY_SOUNDS'].'');

    response('[DEBUG]');
    response('Show RAW        : '.$GLOBALS['CONFIG_SHOW_RAW'].'');

    CLI_MSG("[PLUGIN: showconfig] by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) | chan: {$GLOBALS['channel']}", '1');
}
