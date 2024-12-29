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

function cliNoLog($_data = null) /* no logs */
{
    if ($_data != null) {
        echo $_data.N;
    } else {
             echo N;
    }
}
//---------------------------------------------------------------------------------------------------------
function cliError($_data) /* no logs */
{
    echo '[ERROR] '.$_data.NN;    
}
//---------------------------------------------------------------------------------------------------------
function cliConfigErr($_info)
{
    cliError($_info);
    winSleep(7);
}
//---------------------------------------------------------------------------------------------------------
function cliBot($_data) /* log -- bot.txt */
{
    $line = '['.@date('H:i:s').'] [bot] '.$_data.N;

    saveLog('bot', $line);

    echo $line;
}
//---------------------------------------------------------------------------------------------------------
function cliServer($_data) /* log -- server.txt */
{
    $line = '['.@date('H:i:s').'] [server] '.$_data.N;

    saveLog('server', $line);
    
    echo $line;
}
//---------------------------------------------------------------------------------------------------------
function cliLogChannel($_data) /* log message +time */
{
    $line = '['.@date('H:i:s').'] '.$_data.N;

    saveLog('channel', $line);

    echo $line;
}
//---------------------------------------------------------------------------------------------------------
function cliNotice($_data) /* log  -- bot.txt */
{
    $line = '['.@date('H:i:s').'] [notice] '.$_data.N;

    saveLog('notice', $line);

    if (loadValueFromConfigFile('MESSAGE', 'show users notice messages') == true) {
        echo $line;
    }
}
//---------------------------------------------------------------------------------------------------------
function cliCTCP($_type, $_from) /* log -- ctcp.txt */
{
    $line = "[".@date('H:i:s')."] [ctcp {$_type}] from {$_from}".N;

    saveLog('ctcp', $line);

    if (loadValueFromConfigFile('MESSAGE', 'show ctcp messages') == true) {
        echo $line;
    }
}
//---------------------------------------------------------------------------------------------------------
function cliPluginUsage($_pluginName) /* log -- plugins.txt */
{
    $line = "[".@date('H:i:s')."] [PLUGIN: {$_pluginName}] Used by: ".print_userNick_IdentHost()." channel: ".getBotChannel().N;
    
    saveLog('plugin', $line);

    if (loadValueFromConfigFile('MESSAGE', 'show plugin usage info') == true) {
        echo $line;
    }
}
//---------------------------------------------------------------------------------------------------------
function cliDebug($_data)
{
    if (loadValueFromConfigFile('DEBUG', 'show debug') == true) {
        echo '['.@date('H:i:s').'] [DEBUG] '.$_data.N;
    }
}
//---------------------------------------------------------------------------------------------------------
function cliRaw($_data, $_mode) /* raw messages */
{
    if (!empty($_data)) {
        if ($_mode == 0) {
            $line = '['.@date('H:i:s').'] -> [RAW] '.$_data;
        } else {
                 $line = '['.@date('H:i:s').'] <- [RAW] '.$_data.N;
        }

        saveLog('raw', $line);

        if (loadValueFromConfigFile('DEBUG', 'show raw') == true) {
            echo $line;
        } else if ($_mode == 1 && loadValueFromConfigFile('DEBUG', 'show own messages in raw mode') == true) {
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
function print_userNick_IdentHost()
{
    return userNickname().' ('.userIdentAndHostname().')';    
}
//---------------------------------------------------------------------------------------------------------
function print_userNick_NickIdentHost()
{
    return userNickname().' ('.userNickIdentAndHostname().')';
}
//---------------------------------------------------------------------------------------------------------
function baner() /* no logs */
{
    echo N.'                 - MINION '.VER.' | Author: minions -'.N;
    echo '    ---------------------------------------------------------'.N;
         
    /* check if we have needed extensions */
    if (extension_loaded('curl') && extension_loaded('openssl')) {
        echo '                    All needed extensions loaded'.N;
    }

    if (!extension_loaded('curl')) {
        echo '       Extension \'curl\' missing - some plugins will not work!'.N;
    }

    if (!extension_loaded('openssl')) {
        echo '     Extension \'openssl\' missing - some plugins will not work!'.N;
    }

    /* os txt */
    $sys = (ifWindowsOs()) ? 'Windows' : 'Linux';

    echo '                    PHP Ver: '.PHP_VERSION.', OS: '.$sys.N;
    echo '    ---------------------------------------------------------'.N;
    echo '                    Total Lines of code: '.totalLines().' :)'.NN;

    if (PHP_VERSION != PHPDEV_VER) {
        echo '                             CAUTION!'.N;
        echo '               Bot was tested on PHP 7.4.33 version'.N;
        echo '             With other versions it may run unstable!'.NN;
    }

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
