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

function if_CTCP()
{
    switch ($GLOBALS['rawcmd'][1]) {
        case 'version':
            toServer("NOTICE ".userPreg()[0]." :VERSION ".loadValueFromConfigFile('CTCP', 'ctcp.version'));

            cliLog("[bot] (ctcp) VERSION by: ".userPreg()[0]." (".userPreg()[3].")");
            PlaySound('ctcp.mp3');
            break;

        case 'clientinfo':
            toServer("NOTICE ".userPreg()[0]." :CLIENTINFO ".loadValueFromConfigFile('CTCP', 'ctcp.version'));

            cliLog("[bot] (ctcp) CLIENTINFO by: ".userPreg()[0]." (".userPreg()[3].")");
            PlaySound('ctcp.mp3');
            break;

        case 'source':
            toServer("NOTICE ".userPreg()[0]." :SOURCE http://github.com/S3x0r/MINION");

            cliLog("[bot] (ctcp) SOURCE by: ".userPreg()[0]." (".userPreg()[3].")");
            PlaySound('ctcp.mp3');
            break;

        case 'userinfo':
            toServer("NOTICE ".userPreg()[0]." :USERINFO Powered by Minions!");

            cliLog("[bot] (ctcp) USERINFO by: ".userPreg()[0]." (".userPreg()[3].")");
            PlaySound('ctcp.mp3');
            break;

        case 'finger':
            toServer("NOTICE ".userPreg()[0]." :FINGER ".loadValueFromConfigFile('CTCP', 'ctcp.finger'));

            cliLog("[bot] (ctcp) FINGER by: ".userPreg()[0]." (".userPreg()[3].")");
            PlaySound('ctcp.mp3');
            break;

        case 'ping':
            toServer("NOTICE ".userPreg()[0]." :PING ".str_replace(' ', '', msgAsArguments()));

            cliLog("[bot] (ctcp) PING by: ".userPreg()[0]." (".userPreg()[3].")");
            PlaySound('ctcp.mp3');
            break;

        case 'time':
            toServer("NOTICE ".userPreg()[0]." :TIME ".date("F j, Y, g:i a"));

            cliLog("[bot] (ctcp) TIME by: ".userPreg()[0]." (".userPreg()[3].")");
            PlaySound('ctcp.mp3');
            break;
    }
}
