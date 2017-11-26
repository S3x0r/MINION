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
    $plugin_description = 'Shows BOT configuration: '.$GLOBALS['CONFIG_CMD_PREFIX'].'showconfig';
    $plugin_command = 'showconfig';

function plugin_showconfig()
{

    CLI_MSG('[PLUGIN: showconfig] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
        $GLOBALS['channel'], '1');

    BOT_RESPONSE('My Config:');

    BOT_RESPONSE('Nick: '.$GLOBALS['CONFIG_NICKNAME'].' Name: '.$GLOBALS['CONFIG_NAME'].
        ', Ident: '.$GLOBALS['CONFIG_IDENT'].' Bot response: '.$GLOBALS['CONFIG_BOT_RESPONSE'].'');
 
    BOT_RESPONSE('Server: '.$GLOBALS['CONFIG_SERVER'].':'.$GLOBALS['CONFIG_PORT'].
        ', Try connect: '.$GLOBALS['CONFIG_TRY_CONNECT'].', Delay: '
    .$GLOBALS['CONFIG_CONNECT_DELAY'].'');
 
    BOT_RESPONSE('Auto join: '.$GLOBALS['CONFIG_AUTO_JOIN'].' Auto rejoin: '
    .$GLOBALS['CONFIG_AUTO_REJOIN'].' Auto op: '.$GLOBALS['CONFIG_AUTO_OP'].'  Channel(s): '
    .$GLOBALS['CONFIG_CNANNEL'].'');

    BOT_RESPONSE('Auto op list: '.$GLOBALS['CONFIG_AUTO_OP_LIST'].' Bot owners: '.$GLOBALS['CONFIG_OWNERS'].'');
 
    BOT_RESPONSE('Command prefix: '.$GLOBALS['CONFIG_CMD_PREFIX'].'');

    BOT_RESPONSE('CTCP response: '.$GLOBALS['CONFIG_CTCP_RESPONSE'].', CTCP version: '
    .$GLOBALS['CONFIG_CTCP_VERSION'].', CTCP finger: '.$GLOBALS['CONFIG_CTCP_FINGER'].'');

    BOT_RESPONSE('Logging: '.$GLOBALS['CONFIG_SHOW_RAW'].' Show raw: '
    .$GLOBALS['CONFIG_SHOW_RAW'].' Time zone: '.$GLOBALS['CONFIG_TIMEZONE'].'');

    BOT_RESPONSE('Fetch server: '.$GLOBALS['CONFIG_FETCH_SERVER'].'');
}
