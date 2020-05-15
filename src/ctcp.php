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

PHP_SAPI !== 'cli' ? exit('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
                           Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>') : false;
//---------------------------------------------------------------------------------------------------------

function CTCP()
{
    switch ($GLOBALS['rawcmd'][1]) {
        case 'version':
            fputs($GLOBALS['socket'], 'NOTICE '.$GLOBALS['USER'].' :VERSION '.
                  $GLOBALS['CONFIG_CTCP_VERSION'].N);

            CLI_MSG('[CTCP REQUEST] VERSION by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].')', '1');
            PlaySound('ctcp.mp3');
            break;

        case 'clientinfo':
            fputs($GLOBALS['socket'], 'NOTICE '.$GLOBALS['USER'].' :CLIENTINFO '.
                  $GLOBALS['CONFIG_CTCP_VERSION'].N);

            CLI_MSG('[CTCP REQUEST] CLIENTINFO by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].')', '1');
            PlaySound('ctcp.mp3');
            break;

        case 'source':
            fputs($GLOBALS['socket'], 'NOTICE '.$GLOBALS['USER']." :SOURCE http://github.com/S3x0r/MINION\n");

            CLI_MSG('[CTCP REQUEST] SOURCE by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].')', '1');
            PlaySound('ctcp.mp3');
            break;

        case 'userinfo':
            fputs($GLOBALS['socket'], 'NOTICE '.$GLOBALS['USER']." :USERINFO Powered by Minions!\n");

            CLI_MSG('[CTCP REQUEST] USERINFO by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].')', '1');
            PlaySound('ctcp.mp3');
            break;

        case 'finger':
            fputs($GLOBALS['socket'], 'NOTICE '.$GLOBALS['USER'].' :FINGER '.$GLOBALS['CONFIG_CTCP_FINGER'].N);

            CLI_MSG('[CTCP REQUEST] FINGER by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].')', '1');
            PlaySound('ctcp.mp3');
            break;

        case 'ping':
            $a = str_replace(' ', '', $GLOBALS['args']);
            fputs($GLOBALS['socket'], 'NOTICE '.$GLOBALS['USER'].' :PING '.$a.N);

            CLI_MSG('[CTCP REQUEST] PING by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].')', '1');
            PlaySound('ctcp.mp3');
            break;

        case 'time':
            $a = date("F j, Y, g:i a");
            fputs($GLOBALS['socket'], 'NOTICE '.$GLOBALS['USER'].' :TIME '.$a.N);

            CLI_MSG('[CTCP REQUEST] TIME by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].')', '1');
            PlaySound('ctcp.mp3');
            break;
    }
}
