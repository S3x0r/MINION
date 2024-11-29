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

function cli($data) /* no log */
{
    echo $data.N;
}
//---------------------------------------------------------------------------------------------------------
function cliBot($data) /* log */
{
    cliLog('[bot] '.$data);
}
//---------------------------------------------------------------------------------------------------------
function cliServer($data) /* log */
{
     cliLog('[server] '.$data);
}
//---------------------------------------------------------------------------------------------------------
function cliNotice($data) /* log */
{
    cliLog('[notice] '.$data);
}
//---------------------------------------------------------------------------------------------------------
function cliError($data) /* no log */
{
    echo '[ERROR] '.$data.NN;    
}
//---------------------------------------------------------------------------------------------------------
function cliCTCP($type, $from) /* log */
{
    cliLog('[ctcp '.$type.'] from '.$from);    
}
//---------------------------------------------------------------------------------------------------------
function cliRaw($data, $mode) /* raw messages */
{
    if (loadValueFromConfigFile('DEBUG', 'show raw') == true) {
        if ($mode == 0) {
            echo '['.@date('H:i:s').'] -> [RAW] '.$data;
        } else if ($mode == 1 && loadValueFromConfigFile('DEBUG', 'show own messages in raw mode') == true) {
                   echo '['.@date('H:i:s').'] <- [RAW] '.$data.N;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function cliDebug($data)
{
    if (loadValueFromConfigFile('DEBUG', 'show debug') == true) {
        echo '['.@date('H:i:s').'] [DEBUG] '.$data.N;
    }
}
//---------------------------------------------------------------------------------------------------------
function cliPluginUsage($pluginName)
{
    if (loadValueFromConfigFile('MESSAGE', 'show plugin usage info') == true) {
        cliLog('[PLUGIN: '.$pluginName.'] Used by: '.print_userNick_IdentHost().', channel: '.getBotChannel());
    }
}
//---------------------------------------------------------------------------------------------------------
function cliLine() /* no log */
{
    echo '------------------------------------------------------------------------------'.N;
}
//---------------------------------------------------------------------------------------------------------
function baner()
{
    echo N.'                 - MINION '.VER.' | Author: S3x0r -'.N;
    echo '    ---------------------------------------------------------'.N;
         
    /* check if we have needed extensions */
    if (extension_loaded('curl') && extension_loaded('openssl')) {
        echo '                   All needed extensions loaded'.N;
    }

    if (!extension_loaded('curl')) {
        echo '       Extension \'curl\' missing, some plugins will not work'.N;
    }

    if (!extension_loaded('openssl')) {
        echo '     Extension \'openssl\' missing, some plugins will not work'.N;
    }

    /* os txt */
    (ifWindowsOs()) ? $sys = 'Windows' : $sys = 'Linux';

    echo '                    PHP Ver: '.PHP_VER.', OS: '.$sys.N;
    echo '    ---------------------------------------------------------'.N;
    echo '                   Total Lines of code: '.totalLines().' :)'.NN.N;

    $date = date('dm');

	if ($date == '0112' or $date == '2412') {
	    playSound('egg.mp3');
	}
}
//---------------------------------------------------------------------------------------------------------
function checkUpdateInfo()
{
    if (extension_loaded('openssl')) {
        $file = @file_get_contents(VERSION_URL);
    
        if (!empty($file)) {
            $version = explode("\n", $file);
            if ($version[0] > VER) {
                echo "             >>>> New version available! ($version[0]) <<<<".NN.N;
            } else {
                     echo '       >>>> No new update, you have the latest version <<<<'.NN.N;
            }
        } else {
                 echo '            >>>> Cannot connect to update server <<<<'.NN.N;
        }
    } else {
             echo '   ! I cannot check update, i need: php_openssl extension to work!'.NN.N;
    }
}
