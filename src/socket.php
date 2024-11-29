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

function connect()
{
    if (tryToConnect()) {
        if (identify()) {
            socketLoop();
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function tryToConnect()
{
    $i = 0;

    while ($i++ <= loadValueFromConfigFile('SERVER', 'how many times connect to server')) {
           $GLOBALS['socket'] = @fsockopen(loadValueFromConfigFile('SERVER', 'server'),
                                           loadValueFromConfigFile('SERVER', 'port'),
                                           $GLOBALS['errno'], $GLOBALS['errstr']);
        if ($GLOBALS['socket'] == false) {
            cliBot('Cannot connect to: '.loadValueFromConfigFile('SERVER', 'server').
                                     ':'.loadValueFromConfigFile('SERVER', 'port').
               ', trying again ('.$i.'/'.loadValueFromConfigFile('SERVER', 'how many times connect to server').
                                  ')...');

            playSound('error_conn.mp3');

            usleep(loadValueFromConfigFile('SERVER', 'connect delay') * 1000000); // reconnect delay

            if ($i == loadValueFromConfigFile('SERVER', 'how many times connect to server')) {
                cliBot('Unable to connect to server, exiting program.');
                playSound('error_conn.mp3');
                winSleep(7);

                exit;
            }
        } else {
                 return true;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function identify()
{
    if (!empty(loadValueFromConfigFile('SERVER', 'server password'))) {
        toServer('PASS '.loadValueFromConfigFile('SERVER', 'server password'));
    }

    toServer('NICK '.loadValueFromConfigFile('BOT', 'nickname'));

    toServer('USER '.loadValueFromConfigFile('BOT', 'ident').' 8 * :'.loadValueFromConfigFile('BOT', 'name'));

    return true;
}
//---------------------------------------------------------------------------------------------------------
function socketLoop()
{
    global $rawData;
    global $rawcmd;
    global $BOT_NICKNAME;
    global $I_USE_RND_NICKNAME;
//---------------------------------------------------------------------------------------------------------
    $BOT_NICKNAME       = null;
    $I_USE_RND_NICKNAME = null;
//---------------------------------------------------------------------------------------------------------
    /* main socket loop */
    while (1) {
        while (!feof($GLOBALS['socket'])) {

            /* start timers */
            StartTimers();

            /* get raw data */
            $rawData = fgets($GLOBALS['socket'], 1024);

            /* if raw mode send debug data to cli */
            cliRaw($rawData, 0);

            /* put raw data to array */
            rawDataArray();

            /* if ping -> response */
            if (isset(rawDataArray()[0]) && rawDataArray()[0] == 'PING') {
                toServer('PONG '.rawDataArray()[1]);
            }

            /* if operation (JOIN,PART,etc) */
            if (isset(rawDataArray()[1]) && in_array(rawDataArray()[1], WORD)) {
                handleUserEvent(rawDataArray()[1]);
            }

            if (count(rawDataArray()) < 4) {
                continue;
            }

            isset(rawDataArray()[3]) ? $rawcmd = explode(':', rawDataArray()[3]) : false;

            /* Case sensitive */
            isset($rawcmd[1]) ? $rawcmd[1] = strtolower($rawcmd[1]) : false;
            
            /* if numeric message from server (001,002,etc) -> response */
            if (isset(rawDataArray()[1]) && is_numeric(rawDataArray()[1])) {
                handleNumericResponse(rawDataArray()[1]);
            }
//---------------------------------------------------------------------------------------------------------
            if (loadValueFromConfigFile('CTCP', 'ctcp response') == true && isset($rawcmd[1][0]) && $rawcmd[1][0] == '') {
                handleCTCP();
            }

            /* Command: 'register' -> register to bot from user */
            if (isset($rawcmd[1]) && $rawcmd[1] == 'register' && rawDataArray()[2] == getBotNickname()) {
                plugin_register();
            }
    
            /* 1. if first char == command prefix (eg. !) */
            if (isset($rawcmd[1][0]) && $rawcmd[1][0] == commandPrefix()) {
                ifPrivilegesExecuteCommand();
    
                if (!function_exists('plugin_')) {
                    function plugin_() { }
                }
           }

           if (preg_match("~\bYou are not authorized to connect to this server\b~", $rawData)) {
               cliBot('Cannot connect to: '.loadValueFromConfigFile('SERVER', 'server').':'.loadValueFromConfigFile('SERVER', 'port').' - Server requires password to connect! Exiting.');

               winSleep(10);
               exit;
           }
//---------------------------------------------------------------------------------------------------------
        }
        /* if disconected */
        if (empty($GLOBALS['disconnected'])) {
            cliRaw($rawData, 0);

            if (preg_match("~\bToo many connections from your IP\b~", $rawData)) {
                cliBot('Cannot connect to: '.loadValueFromConfigFile('SERVER', 'server').':'.loadValueFromConfigFile('SERVER', 'port').', too many connections! Exiting.');

                winSleep(10);
                exit;
            }

            if (preg_match("~\bSession limit exceeded\b~", $rawData)) {
                cliBot('Cannot connect to: '.loadValueFromConfigFile('SERVER', 'server').':'.loadValueFromConfigFile('SERVER', 'port').', too many connections! Exiting.');

                winSleep(10);
                exit;
            }

            if (preg_match("~\bToo many user connections\b~", $rawData)) {
                cliBot('Cannot connect to: '.loadValueFromConfigFile('SERVER', 'server').':'.loadValueFromConfigFile('SERVER', 'port').', too many connections! Exiting.');

                winSleep(10);
                exit;
            }

            cliBot('Disconnected! Trying to reconnect...');
            connect();
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function response($msg)
{
    switch (loadValueFromConfigFile('RESPONSE', 'bot response')) {
        case 'channel':
            toServer('PRIVMSG '.getBotChannel().' :'.$msg);
            usleep(loadValueFromConfigFile('DELAYS', 'channel delay') * 1000000);
            break;

        case 'notice':
            toServer('NOTICE '.userNickname().' :'.$msg);
            usleep(loadValueFromConfigFile('DELAYS', 'notice delay') * 1000000);
            break;

        case 'priv':
            toServer('PRIVMSG '.userNickname().' :'.$msg);
            usleep(loadValueFromConfigFile('DELAYS', 'private delay') * 1000000);
            break;
    }
}
//---------------------------------------------------------------------------------------------------------
function privateMsgTo($user, $message)
{
    toServer("PRIVMSG {$user} :{$message}");
    usleep(loadValueFromConfigFile('DELAYS', 'private delay') * 1000000);
}
//---------------------------------------------------------------------------------------------------------
function joinChannel($channel)
{
    if (!empty(loadValueFromConfigFile('CHANNEL', 'channel key'))) {
        toServer('JOIN '.$channel.' '.loadValueFromConfigFile('CHANNEL', 'channel key'));
    } else {
             toServer('JOIN '.$channel);
    }
}
//---------------------------------------------------------------------------------------------------------
function toServer($data)
{   
    /* send own message to cli if raw mode */
    cliRaw($data, 1);

    if (@fputs($GLOBALS['socket'], "{$data}\n")) { }
}
//---------------------------------------------------------------------------------------------------------
function msgFromServer()
{
    $msg = null;
    
    for ($i=3; $i < count(rawDataArray()); $i++) {
         $msg .= str_replace(':', '', rawDataArray()[$i]).' ';
    }

    return $msg;
}
//---------------------------------------------------------------------------------------------------------
function getBotNickname()
{
    if (isset($GLOBALS['BOT_NICKNAME']) && !empty($GLOBALS['BOT_NICKNAME'])) {
        return $GLOBALS['BOT_NICKNAME'];
    }
}
//---------------------------------------------------------------------------------------------------------
function setBotNickname($nickname)
{
    $GLOBALS['BOT_NICKNAME'] = $nickname;
}
//---------------------------------------------------------------------------------------------------------
function getServerName()
{
    if (isset($GLOBALS['serverName']) && !empty($GLOBALS['serverName'])) {
        return $GLOBALS['serverName'];
    }
}
//---------------------------------------------------------------------------------------------------------
function getBotChannel()
{
    if (isset($GLOBALS['BOT_CHANNEL']) && !empty($GLOBALS['BOT_CHANNEL'])) {
        return $GLOBALS['BOT_CHANNEL'];
    }
}
//---------------------------------------------------------------------------------------------------------
function setBotChannel($channel)
{
    $GLOBALS['BOT_CHANNEL'] = $channel;
}
//---------------------------------------------------------------------------------------------------------
function setServerName($name)
{
    global $serverName;

    $serverName = $name;
}
//---------------------------------------------------------------------------------------------------------
function userNickname()
{
    cliDebug('userNickname()');

    if (preg_match('/^:(.*)\!(.*)\@(.*)$/', rawDataArray()[0], $userNickname)) {
        return $userNickname[1];
    }
}
//---------------------------------------------------------------------------------------------------------
function userIdent()
{
    cliDebug('userIdent()');

    if (preg_match('/^:(.*)\!(.*)\@(.*)$/', rawDataArray()[0], $userIdent)) {
        return $userIdent[2];
    }
}
//---------------------------------------------------------------------------------------------------------
function userHostname()
{
    if (preg_match('/^:(.*)\!(.*)\@(.*)$/', rawDataArray()[0], $userHostname)) {
        return $userHostname[3];
    }
}
//---------------------------------------------------------------------------------------------------------
function userIdentAndHostname()
{
    if (preg_match('/^:(.*)\!(.*)\@(.*)$/', rawDataArray()[0], $source)) {
        return $source[2].'@'.$source[3];
    }
}
//---------------------------------------------------------------------------------------------------------
function userNickIdentAndHostname()
{
    if (preg_match('/^:(.*)\!(.*)\@(.*)$/', rawDataArray()[0], $source)) {
        if (isset($source[1])) {
            return $source[1].'!'.$source[2].'@'.$source[3];
        }
    }
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
function msgPieces()
{
    $args = null;
    for ($i=4; $i < count(rawDataArray()); $i++) {
         $args .= rawDataArray()[$i].' ';
    }

    $pieces = explode(" ", $args);
    
    if (isset($pieces[0])) {
        $piece1 = $pieces[0];
    } else {
             $piece1 = '';
    }
    
    if (isset($pieces[1])) {
        $piece2 = $pieces[1];
    } else {
             $piece2 = '';
    }
    
    if (isset($pieces[2])) {
        $piece3 = $pieces[2];
    } else {
             $piece3 = '';
    }
    if (isset($pieces[3])) {
        $piece4 = $pieces[3];
    } else {
             $piece4 = '';
    }

    return [$piece1, $piece2, $piece3, $piece4];
}
//---------------------------------------------------------------------------------------------------------
function commandFromUser() /* first command only */
{
    if (isset(rawDataArray()[4]) && !empty(rawDataArray()[4])) {
        return rawDataArray()[4];
    }
}
//---------------------------------------------------------------------------------------------------------
function rawDataArray()
{
    $rawDataArray = explode(' ', trim($GLOBALS['rawData']));

    return $rawDataArray;
}
//---------------------------------------------------------------------------------------------------------
function all_args_from_user_array() /* start from 1 */
{
    $input = null;

    for ($i=4; $i <= (count(rawDataArray())-1); $i++) {
         $input .= rawDataArray()[$i]." ";
    }

    $data = rtrim($input);
    $data = explode(' ', $data);
    
    array_unshift($data, '');
    
    unset($data[0]);
    
    return $data;
}
//---------------------------------------------------------------------------------------------------------
function inputFromLine($index)
{
    $a = rawDataArray();
    $current = '';

    while (isset($a[$index])) {
           $current .= $a[$index].' ';
           $index++;
    }

    $string = preg_replace('/^:/', '', $current, 1);
    $string = substr($string, 0, -1);

    return $string;
}
