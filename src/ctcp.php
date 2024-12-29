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

function handleCTCP()
{
    switch ($GLOBALS['rawcmd'][1]) {
        case 'version':
            if (loadValueFromConfigFile('CTCP', 'ctcp response') == true) {
                sendCTCP(userNickname(), 'VERSION minion ('.VER.') powered by minions!');
            }

            cliCTCP('VERSION', print_userNick_IdentHost());
            playSound('ctcp.mp3');
            break;

        case 'clientinfo':
            if (loadValueFromConfigFile('CTCP', 'ctcp response') == true) { 
                sendCTCP(userNickname(), 'CLIENTINFO I know these CTCP commands: CLIENTINFO FINGER PING SOURCE TIME USERINFO VERSION');
            }

            cliCTCP('CLIENTINFO', print_userNick_IdentHost());
            playSound('ctcp.mp3');
            break;

        case 'source':
            if (loadValueFromConfigFile('CTCP', 'ctcp response') == true) {
                sendCTCP(userNickname(), 'SOURCE http://github.com/S3x0r/MINION');
            }

            cliCTCP('SOURCE', print_userNick_IdentHost());
            playSound('ctcp.mp3');
            break;

        case 'userinfo':
            if (loadValueFromConfigFile('CTCP', 'ctcp response') == true) {
                sendCTCP(userNickname(), 'USERINFO Powered by Minions!');
            }

            cliCTCP('USERINFO', print_userNick_IdentHost());
            playSound('ctcp.mp3');
            break;

        case 'finger':
            if (loadValueFromConfigFile('CTCP', 'ctcp response') == true) {
                sendCTCP(userNickname(), 'FINGER minion');
            }

            cliCTCP('FINGER', print_userNick_IdentHost());
            playSound('ctcp.mp3');
            break;

        case 'ping':
            if (loadValueFromConfigFile('CTCP', 'ctcp response') == true) {
                sendCTCP(userNickname(), 'PING '.str_replace(' ', '', commandFromUser()));
            }

            cliCTCP('PING', print_userNick_IdentHost());
            playSound('ctcp.mp3');
            break;

        case 'time':
            if (loadValueFromConfigFile('CTCP', 'ctcp response') == true) {
                sendCTCP(userNickname(), 'TIME '.date("F j, Y, g:i a"));
            }

            cliCTCP('TIME', print_userNick_IdentHost());
            playSound('ctcp.mp3');
            break;
    }
}
