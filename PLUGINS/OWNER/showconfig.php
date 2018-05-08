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
    $plugin_description = 'Shows BOT configuration: '.$GLOBALS['CONFIG_CMD_PREFIX'].'showconfig';
    $plugin_command = 'showconfig';

function plugin_showconfig()
{
    BOT_RESPONSE('My Config:');

    BOT_RESPONSE('[BOT]');
    BOT_RESPONSE('Nickname        : '.$GLOBALS['CONFIG_NICKNAME'].'');
    BOT_RESPONSE('Name            : '.$GLOBALS['CONFIG_NAME'].'');
    BOT_RESPONSE('Ident           : '.$GLOBALS['CONFIG_IDENT'].'');

    BOT_RESPONSE('[Server]');
    BOT_RESPONSE('Server          : '.$GLOBALS['CONFIG_SERVER'].'');
    BOT_RESPONSE('Port            : '.$GLOBALS['CONFIG_PORT'].'');
    BOT_RESPONSE('Server Pass     : '.$GLOBALS['CONFIG_SERVER_PASSWD'].'');
    BOT_RESPONSE('Try Connect     : '.$GLOBALS['CONFIG_TRY_CONNECT'].'');
    BOT_RESPONSE('Connect Delay   : '.$GLOBALS['CONFIG_CONNECT_DELAY'].'');

    BOT_RESPONSE('[OWNER]');
    BOT_RESPONSE('Bot Admin       : '.$GLOBALS['CONFIG_BOT_ADMIN'].'');
    BOT_RESPONSE('Auto OP List    : '.$GLOBALS['CONFIG_AUTO_OP_LIST'].'');
    BOT_RESPONSE('Bot Owners      : '.$GLOBALS['CONFIG_OWNERS'].'');

    BOT_RESPONSE('[ADMIN]');
    BOT_RESPONSE('Admin List      : '.$GLOBALS['CONFIG_ADMIN_LIST'].'');
    
    BOT_RESPONSE('[RESPONSE]');
    BOT_RESPONSE('Bot Response    : '.$GLOBALS['CONFIG_BOT_RESPONSE'].'');
    
    BOT_RESPONSE('[AUTOMATIC]');
    BOT_RESPONSE('Auto OP         : '.$GLOBALS['CONFIG_AUTO_OP'].'');
    BOT_RESPONSE('Auto Rejoin     : '.$GLOBALS['CONFIG_AUTO_REJOIN'].'');
    BOT_RESPONSE('Keep Chan Modes : '.$GLOBALS['CONFIG_KEEPCHAN_MODES'].'');
    BOT_RESPONSE('Keep Nick       : '.$GLOBALS['CONFIG_KEEP_NICK'].'');

    BOT_RESPONSE('[CHANNEL]');
    BOT_RESPONSE('Channel         : '.$GLOBALS['CONFIG_CNANNEL'].'');
    BOT_RESPONSE('Auto Join       : '.$GLOBALS['CONFIG_AUTO_JOIN'].'');
    BOT_RESPONSE('Channel Modes   : '.$GLOBALS['CONFIG_CHANNEL_MODES'].'');
    BOT_RESPONSE('Channel Key     : '.$GLOBALS['CONFIG_CHANNEL_KEY'].'');

    BOT_RESPONSE('[BANS]');
    BOT_RESPONSE('Ban List        : '.$GLOBALS['CONFIG_BAN_LIST'].'');

    BOT_RESPONSE('[COMMAND]');
    BOT_RESPONSE('Command Prefix  : '.$GLOBALS['CONFIG_CMD_PREFIX'].'');

    BOT_RESPONSE('[CTCP]');
    BOT_RESPONSE('CTCP Response   : '.$GLOBALS['CONFIG_CTCP_RESPONSE'].'');
    BOT_RESPONSE('CTCP Version    : '.$GLOBALS['CONFIG_CTCP_VERSION'].'');
    BOT_RESPONSE('CTCP Finger     : '.$GLOBALS['CONFIG_CTCP_FINGER'].'');

    BOT_RESPONSE('[DELAYS]');
    BOT_RESPONSE('Channel Delay   : '.$GLOBALS['CONFIG_CHANNEL_DELAY'].'');
    BOT_RESPONSE('Private Delay   : '.$GLOBALS['CONFIG_PRIVATE_DELAY'].'');
    BOT_RESPONSE('Notice Delay    : '.$GLOBALS['CONFIG_NOTICE_DELAY'].'');

    BOT_RESPONSE('[LOGS]');
    BOT_RESPONSE('Logging         : '.$GLOBALS['CONFIG_LOGGING'].'');

    BOT_RESPONSE('[LANG]');
    BOT_RESPONSE('Language        : '.$GLOBALS['CONFIG_LANGUAGE'].'');

    BOT_RESPONSE('[TIME]');
    BOT_RESPONSE('Time Zone       : '.$GLOBALS['CONFIG_TIMEZONE'].'');

    BOT_RESPONSE('[FETCH]');
    BOT_RESPONSE('Fetch Server    : '.$GLOBALS['CONFIG_FETCH_SERVER'].'');

    BOT_RESPONSE('[PROGRAM]');
    BOT_RESPONSE('Show Logo       : '.$GLOBALS['CONFIG_SHOW_LOGO'].'');
    BOT_RESPONSE('Silent Mode     : '.$GLOBALS['silent_mode'].'');
    BOT_RESPONSE('Check Update    : '.$GLOBALS['CONFIG_CHECK_UPDATE'].'');
    BOT_RESPONSE('Play Sounds     : '.$GLOBALS['CONFIG_PLAY_SOUNDS'].'');

    BOT_RESPONSE('[DEBUG]');
    BOT_RESPONSE('Show RAW        : '.$GLOBALS['CONFIG_SHOW_RAW'].'');

    CLI_MSG('[PLUGIN: showconfig] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
            $GLOBALS['channel'], '1');
}
