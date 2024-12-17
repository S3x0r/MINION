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

function cliNoLog($data) /* no logs */
{
    echo $data.N;
}
//---------------------------------------------------------------------------------------------------------
function cliError($data) /* no logs */
{
    echo '[ERROR] '.$data.NN;    
}
//---------------------------------------------------------------------------------------------------------
function cliBot($data) /* log -- bot.txt */
{
    $line = '['.@date('H:i:s').'] [bot] '.$data.N;

    saveLog('bot', $line);

    echo $line;
}
//---------------------------------------------------------------------------------------------------------
function cliServer($data) /* log -- server.txt */
{
    $line = '['.@date('H:i:s').'] [server] '.$data.N;

    saveLog('server', $line);
    
    echo $line;
}
//---------------------------------------------------------------------------------------------------------
function cliLogChannel($data) /* log message +time */
{
    $line = '['.@date('H:i:s').'] '.$data.N;

    saveLog('channel', $line);

    echo $line;
}
//---------------------------------------------------------------------------------------------------------
function cliNotice($data) /* log  -- bot.txt */
{
    $line = '['.@date('H:i:s').'] [notice] '.$data.N;

    saveLog('notice', $line);

    if (loadValueFromConfigFile('MESSAGE', 'show users notice messages') == true) {
        echo $line;
    }
}
//---------------------------------------------------------------------------------------------------------
function cliCTCP($type, $from) /* log -- ctcp.txt */
{
    $line = "[".@date('H:i:s')."] [ctcp {$type}] from {$from}".N;

    saveLog('ctcp', $line);

    if (loadValueFromConfigFile('MESSAGE', 'show ctcp messages') == true) {
        echo $line;
    }
}
//---------------------------------------------------------------------------------------------------------
function cliPluginUsage($pluginName) /* log -- plugins.txt */
{
    $line = "[".@date('H:i:s')."] [PLUGIN: {$pluginName}] Used by: ".print_userNick_IdentHost()." channel: ".getBotChannel().N;
    
    saveLog('plugin', $line);

    if (loadValueFromConfigFile('MESSAGE', 'show plugin usage info') == true) {
        echo $line;
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
function cliRaw($data, $mode) /* raw messages */
{
    if (!empty($data)) {
        if ($mode == 0) {
            $line = '['.@date('H:i:s').'] -> [RAW] '.$data;
        } else {
                 $line = '['.@date('H:i:s').'] <- [RAW] '.$data.N;
        }

        saveLog('raw', $line);

        if (loadValueFromConfigFile('DEBUG', 'show raw') == true) {
            echo $line;
        } else if ($mode == 1 && loadValueFromConfigFile('DEBUG', 'show own messages in raw mode') == true) {
                   echo $line;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function cliLine() /* no logs */
{
    echo '------------------------------------------------------------------------------'.N;
}
//---------------------------------------------------------------------------------------------------------
function baner() /* no logs */
{
    echo N.'                 - MINION '.VER.' | Author: minions -'.N;
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
