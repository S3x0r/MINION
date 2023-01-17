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

function cliLine()
{
    echo '------------------------------------------------------------------------------'.N;
}
//---------------------------------------------------------------------------------------------------------
function cli($data) /* no logging */
{
    echo $data.N;
}
//---------------------------------------------------------------------------------------------------------
function cliDebug($data, $mode) /* debug message */
{
    if (loadValueFromConfigFile('DEBUG', 'show.raw') == 'yes') {
        if ($mode == 0) {
            echo "[DEBUG <-] $data";
        } else if ($mode == 1 && loadValueFromConfigFile('DEBUG', 'show.own.messages.in.raw.mode') == 'yes') {
                   echo "[DEBUG ->] $data".N;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function cliLog($data) /* log message +time */
{
    $line = "[".@date('H:i:s')."] {$data}".N;

    if (loadValueFromConfigFile('LOGS', 'logging') == 'yes') {
        SaveToFile(logFileNameFormat(), $line, 'a');
    }

    echo $line;
}
//---------------------------------------------------------------------------------------------------------
function Baner()
{
    echo N.'                 - MINION '.VER.' | Author: S3x0r -'.N;
    echo '    ---------------------------------------------------------'.N;
         
    /* os var */
    (ifWindowsOs()) ? $system = 'Windows' : $system = 'Linux';

    /* check if we have needed extensions */
    if (extension_loaded('curl') && extension_loaded('openssl')) {
        echo '                   All needed extensions loaded'.N;
    }

    if (!extension_loaded('curl')) {
        echo "       Extension 'curl' missing, some plugins will not work".N;
    }

    if (!extension_loaded('openssl')) {
        echo "     Extension 'openssl' missing, some plugins will not work".N;
    }

    echo '                    PHP Ver: '.PHP_VER.', OS: '.$system.N;
    echo '    ---------------------------------------------------------'.N;
    echo '                   Total Lines of code: '.TotalLines().' :)'.NN.N;
}
//---------------------------------------------------------------------------------------------------------
function CheckUpdateInfo()
{
    if (extension_loaded('openssl')) {
        $file = @file_get_contents(VERSION_URL);
    
        if (!empty($file)) {
            $version = explode("\n", $file);
            if ($version[0] > VER) {
                echo "             >>>> New version available! ($version[0]) <<<<".NN.N;
            } else {
                     echo "       >>>> No new update, you have the latest version <<<<".NN.N;
            }
        } else {
                 echo "            >>>> Cannot connect to update server <<<<".NN.N;
        }
    } else {
             echo "   ! I cannot check update, i need: php_openssl extension to work!".NN.N;
    }
}
//---------------------------------------------------------------------------------------------------------
function pluginUsageCli($pluginName)
{
    cliLog("[PLUGIN: {$pluginName}] Used by: ".userPreg()[0]." (".userPreg()[3]."), channel: ".getBotChannel());
}
//---------------------------------------------------------------------------------------------------------
function debug($data)
{
    if (loadValueFromConfigFile('DEBUG', 'show.debug') == 'yes') {
        echo "[".@date('H:i:s')."] [DEBUG] {$data}".N;
    }
}