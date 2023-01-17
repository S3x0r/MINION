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

function tryToConnect()
{
    $i = 0;

    while ($i++ <= loadValueFromConfigFile('SERVER', 'try.connect')) {
           $GLOBALS['socket'] = @fsockopen(loadValueFromConfigFile('SERVER', 'server'),
                                           loadValueFromConfigFile('SERVER', 'port'),
                                           $GLOBALS['errno'], $GLOBALS['errstr']);
        if ($GLOBALS['socket'] == false) {
            cliLog("[bot] Cannot connect to: ".loadValueFromConfigFile('SERVER', 'server').
                                             ":".loadValueFromConfigFile('SERVER', 'port').
                                             ", trying again ({$i}/".
                                             loadValueFromConfigFile('SERVER', 'try.connect').")...");
            PlaySound('error_conn.mp3');
            usleep(loadValueFromConfigFile('SERVER', 'connect.delay') * 1000000); // reconnect delay
            if ($i == loadValueFromConfigFile('SERVER', 'try.connect')) {
                cliLog('[bot] Unable to connect to server, exiting program.');
                PlaySound('error_conn.mp3');
                WinSleep(7);
                exit;
            }
        } else {
                 return true;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function Identify()
{
    if (!empty(loadValueFromConfigFile('SERVER', 'server.password'))) {
        toServer("PASS ".loadValueFromConfigFile('SERVER', 'server.password'));
    }

    toServer("NICK ".loadValueFromConfigFile('BOT', 'nickname'));

    toServer("USER ".loadValueFromConfigFile('BOT', 'ident')." 8 * :".loadValueFromConfigFile('BOT', 'name'));

    return true;
}
//---------------------------------------------------------------------------------------------------------
function SocketLoop()
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
            !getPause() ? StartTimers() : false;

            /* get raw data */
            $rawData = fgets($GLOBALS['socket'], 1024);

            /* if raw mode send debug data to cli */
            cliDebug($rawData, 0);

            /* put raw data to array */
            rawDataArray();

            /* if ping -> response */
            if (isset(rawDataArray()[0]) && rawDataArray()[0] == 'PING') {
                on_PING();
            }

            /* if operation (JOIN,PART,etc) -> response */
            if (isset(rawDataArray()[1]) && in_array(rawDataArray()[1], ['JOIN', 'PART', 'KICK', 'TOPIC', 'PRIVMSG',
                                                                         'NICK', 'QUIT', 'MODE', 'NOTICE'])) {
                function_exists('on_'.rawDataArray()[1]) ? call_user_func('on_'.rawDataArray()[1]) : false;
            }

            if (count(rawDataArray()) < 4) {
                continue;
            }

            isset(rawDataArray()[3]) ? $rawcmd = explode(':', rawDataArray()[3]) : false;

            /* Case sensitive */
            isset($rawcmd[1]) ? $rawcmd[1] = strtolower($rawcmd[1]) : false;
//---------------------------------------------------------------------------------------------------------
            /* if numeric message from server (001,002,etc) -> response */
            if (isset(rawDataArray()[1]) && is_numeric(rawDataArray()[1])) {
                function_exists('on_'.rawDataArray()[1]) ? call_user_func('on_'.rawDataArray()[1]) : false;
            }

            /* if CTCP request -> response */
            if (!getPause() && loadValueFromConfigFile('CTCP', 'ctcp.response') == 'yes' && isset($rawcmd[1][0]) && $rawcmd[1][0] == '') {
                if_CTCP();
            }

            /* if plugin request -> response */
            if (getPause() == true) {
                /* Command: 'unpause' -> OWNER core command: works only if paused */
                if (isUserOwner() && isset($rawcmd[1]) && $rawcmd[1] == loadValueFromConfigFile('COMMAND', 'command.prefix').'unpause') {
                    plugin_unpause();
                }
            }
        
            if (getPause() == false) {
                
                /* Command: 'register' -> register to bot from user */
                if (isset($rawcmd[1]) && $rawcmd[1] == 'register' && rawDataArray()[2] == getBotNickname()) {
                    plugin_register();
                }
        
                /* 1. if first char == command prefix (eg. !) */
                if (isset($rawcmd[1][0]) && $rawcmd[1][0] == loadValueFromConfigFile('COMMAND', 'command.prefix')) {
                    ifPrivilegesExecuteCommand();
        
                    if (!function_exists('plugin_')) {
                        function plugin_() { }
                    }
               }
            }
//---------------------------------------------------------------------------------------------------------
        }
        /* if disconected */
        if (empty($GLOBALS['disconnected'])) {
            cliDebug($rawData, 0);

            if (preg_match("~\bToo many connections from your IP\b~", $rawData)) {
                cliLog("[bot] Cannot connect to: ".loadValueFromConfigFile('SERVER', 'server').":".loadValueFromConfigFile('SERVER', 'port').", too many connections! Exiting.");

                WinSleep(10);
                exit;
            }

            if (preg_match("~\bSession limit exceeded\b~", $rawData)) {
                cliLog("[bot] Cannot connect to: ".loadValueFromConfigFile('SERVER', 'server').":".loadValueFromConfigFile('SERVER', 'port').", too many connections! Exiting.");

                WinSleep(10);
                exit;
            }

            if (preg_match("~\bToo many user connections\b~", $rawData)) {
                cliLog("[bot] Cannot connect to: ".loadValueFromConfigFile('SERVER', 'server').":".loadValueFromConfigFile('SERVER', 'port').", too many connections! Exiting.");

                WinSleep(10);
                exit;
            }

            cliLog("[bot] Cannot connect to: ".loadValueFromConfigFile('SERVER', 'server').":".loadValueFromConfigFile('SERVER', 'port').", Exiting!");
            WinSleep(10);
            exit;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function response($msg)
{
    switch (loadValueFromConfigFile('RESPONSE', 'bot.response')) {
        case 'channel':
            toServer("PRIVMSG ".getBotChannel()." :$msg");
            usleep(loadValueFromConfigFile('DELAYS', 'channel.delay') * 1000000);
            break;

        case 'notice':
            toServer("NOTICE ".userPreg()[0]." :$msg");
            usleep(loadValueFromConfigFile('DELAYS', 'notice.delay') * 1000000);
            break;

        case 'priv':
            toServer("PRIVMSG ".userPreg()[0]." :$msg");
            usleep(loadValueFromConfigFile('DELAYS', 'private.delay') * 1000000);
            break;
    }
}
//---------------------------------------------------------------------------------------------------------
function privateMsg($message)
{
    toServer("PRIVMSG ".userPreg()[0]." :{$message}");
    usleep(loadValueFromConfigFile('DELAYS', 'private.delay') * 1000000);
}
//---------------------------------------------------------------------------------------------------------
function privateMsgTo($user, $message)
{
    toServer("PRIVMSG {$user} :{$message}");
    usleep(loadValueFromConfigFile('DELAYS', 'private.delay') * 1000000);
}
//---------------------------------------------------------------------------------------------------------
function joinChannel($channel)
{
    toServer("JOIN {$channel}");
}
//---------------------------------------------------------------------------------------------------------
function toServer($data)
{   
    /* send own message to cli if raw mode */
    cliDebug($data, 1);

    if (@fputs($GLOBALS['socket'], "{$data}\n")) { }
}
//---------------------------------------------------------------------------------------------------------
function msgFromServer()
{
    debug("msgFromServer()");
 
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
function getBotModes()
{
    if (isset($GLOBALS['BOT_MODES']) && !empty($GLOBALS['BOT_MODES'])) {
        return $GLOBALS['BOT_MODES'];
    }
}
//---------------------------------------------------------------------------------------------------------
function setBotModes($modes)
{
    $GLOBALS['BOT_MODES'] = $modes;
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
function msgAsArguments()
{
    $args = null;

    for ($i=4; $i < count(rawDataArray()); $i++) {
         $args .= rawDataArray()[$i].'';
    }

    return $args;
}
//---------------------------------------------------------------------------------------------------------
function userPreg()
{
    debug("userPreg()");

    if (preg_match('/^:(.*)\!(.*)\@(.*)$/', rawDataArray()[0], $source)) {
        $USER       = $source[1];
        $USER_IDENT = $source[2];
        $host       = $source[3];
        $USER_HOST  = $USER_IDENT.'@'.$host;

        return [$USER, $USER_IDENT, $host, $USER_HOST];
    }
}
//---------------------------------------------------------------------------------------------------------
function userFullMask()
{
    debug("userFullMask()");

    if (isset(userPreg()[0])) {
        return userPreg()[0].'!'.userPreg()[1].'@'.userPreg()[2];
    }
}
//---------------------------------------------------------------------------------------------------------
function msgPieces()
{
    debug("msgPieces()");

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
function rawDataArray()
{
    $rawDataArray = explode(' ', trim($GLOBALS['rawData']));

    return $rawDataArray;
}
