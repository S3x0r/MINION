<?php
/* Copyright (c) 2013-2024, minions
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

define('CTCP_VERSION',    'version');
define('CTCP_CLIENTINFO', 'clientinfo');
define('CTCP_SOURCE',     'source');
define('CTCP_USERINFO',   'userinfo');
define('CTCP_FINGER',     'finger');
define('CTCP_PING',       'ping');
define('CTCP_TIME',       'time');

function handleCTCP()
{
    $ctcpCommands = [
        CTCP_VERSION    => ['VERSION minion ('.VER.') powered by minions!', 'VERSION'],
        CTCP_CLIENTINFO => ['CLIENTINFO I know these CTCP commands: CLIENTINFO FINGER PING SOURCE TIME USERINFO VERSION', 'CLIENTINFO'],
        CTCP_SOURCE     => ['SOURCE http://github.com/S3x0r/MINION', 'SOURCE'],
        CTCP_USERINFO   => ['USERINFO Powered by Minions!', 'USERINFO'],
        CTCP_FINGER     => ['FINGER minion', 'FINGER'],
        CTCP_PING       => ['PING '.str_replace(' ', '', commandFromUser()), 'PING'],
        CTCP_TIME       => ['TIME '.date("F j, Y, g:i a"), 'TIME']
    ];

    $command = $GLOBALS['rawcmd'][1];

    if (isset($ctcpCommands[$command])) {
        if (loadValueFromConfigFile('CTCP', 'ctcp response') == true) {
            sendCTCP(userNickname(), $ctcpCommands[$command][0]);
        }

        cliCTCP($ctcpCommands[$command][1], print_userNick_IdentHost());
        playSound('ctcp.mp3');
    }
}
