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
    if (pickServerFromConfig()) {
        socketLoop();
    }
}
//---------------------------------------------------------------------------------------------------------
function pickServerFromConfig()
{
    global $socket;
    
    if (isServerProvidedFromArgument()) {
        $servers = [$_SERVER['argv'][2]];
        $port    = $_SERVER['argv'][3];
    } else {
             $servers = array_filter(loadValueFromConfigFile('SERVER', 'servers'));
    }

    $retries = loadValueFromConfigFile('SERVER', 'how many times connect to server');

    for ($s = 0; $s <= count($servers); $s++) {

         foreach ($servers as $server) {
            $serverData = explode(':', $server);
            $server = $serverData[0]; /* server host */

            /* port - default 6667 */
            $port = (isset($serverData[1]) && !empty($serverData[1])) ? $serverData[1] : 6667;

            /* ssl,plain - default plain */
            $connectionType = (isset($serverData[2]) && !empty($serverData[2])) ? $serverData[2] : 'plain';

            /* server password - default empty */
            $serverPassword = (isset($serverData[3]) && !empty($serverData[3])) ? $serverData[3] : '';

            for ($r = 1; $r <= $retries; $r++) {
                 /* if connected */
                 if (server($server, $port, $connectionType, $r) == true) {
                     /* identify to server */
                     if (!empty($serverPassword)) {
                         sendRaw('PASS '.$serverPassword);
                     }

                     sendRaw('NICK '.loadValueFromConfigFile('BOT', 'nickname'));
                     sendRaw('USER '.loadValueFromConfigFile('BOT', 'ident').' 8 * :'.loadValueFromConfigFile('BOT', 'name'));

                     return true;
                 } else {
                          playSound('error_conn.mp3');
                          cliBot("Unable to connect: {$server}:{$port}");
                          usleep(loadValueFromConfigFile('SERVER', 'connect delay') * 1000000);
                 }

                 if ($r == $retries && $s != 0) {
                     cliBot('Changing server...');
                 }
            }
         }

         if ($s == 0) {
             cliBot('Can\'t connect to any of the servers - Exiting!');
             winSleep(10);
         }
    }
}
//---------------------------------------------------------------------------------------------------------
function server($_server, $_port, $_connectionType, $_times)
{
    global $socket, $connectedToServer, $connectedToPort, $server_port;

    cliBot("Connecting: {$_server}:{$_port} ({$_times}/".loadValueFromConfigFile('SERVER', 'how many times connect to server').")");

    $type = ($_connectionType == 'ssl') ? 'ssl://' : '';

    $socket_options = [];

    $socket_context = @stream_context_create($socket_options);

    $socket = @stream_socket_client($type.$_server.':'.$_port, $GLOBALS['errno'], $GLOBALS['errstr'], 15, STREAM_CLIENT_CONNECT, $socket_context);

    $stream_timeout = 320;

    @stream_set_timeout($socket, $stream_timeout, 0);

    if ($socket == true) {
        $connectedToServer = $_server;
        $connectedToPort   = $_port;
        $server_port       = $connectedToServer.':'.$connectedToPort;

        return true;
    } else {
             return false;
    }
}
//---------------------------------------------------------------------------------------------------------
function socketLoop()
{
    global $socket, $rawData, $rawcmd, $flood, $server_port, $serverName, $BOT_NICKNAME, $recoverNickname;

    $flood              = [];
    $BOT_NICKNAME       = null;
    $serverName         = null;
    $recoverNickname    = null;

    /* main socket loop */
    while (1) {
       while (!feof($socket)) {
          /* start timers */
          StartTimers();
     
          $rawData = fgets($socket);
     
          /* if raw mode send debug data to cli */
          cliRaw($rawData, 0);
     
          /* put raw data to array */
          dataArray();
     
          /* if ping -> response */
          if (isset(dataArray()[0]) && dataArray()[0] == 'PING') {
              sendPONG();
          }
     
          /* if operation (JOIN,PART,etc) */
          if (isset(dataArray()[1]) && in_array(dataArray()[1], WORD)) {
              handleUserEvent(dataArray()[1]);
          }
     
          if (count(dataArray()) < 4) {
              continue;
          }
     
          isset(dataArray()[3]) ? $rawcmd = explode(':', dataArray()[3]) : false;
     
          /* Case sensitive */
          isset($rawcmd[1]) ? $rawcmd[1] = strtolower($rawcmd[1]) : false;
          
          /* if numeric message from server (001,002,etc) -> response */
          if (isset(dataArray()[1]) && is_numeric(dataArray()[1])) {
              handleNumericResponse(dataArray()[1]);
          }
     
          /* ctcp */
          if (isset($rawcmd[1][0]) && $rawcmd[1][0] == '') {
              if (!isIgnoredUser()) {
                  floodProtect('ctcp');
                  handleCTCP();
              }
          }
     
          /* Command: 'register' register to bot from user */
          if (isset($rawcmd[1]) && $rawcmd[1] == 'register' && dataArray()[2] == getBotNickname()) {
              if (!isIgnoredUser()) {
                  plugin_register();
              }
          }
     
          /* 1. if first char == command prefix (eg. !) */
          if (isset($rawcmd[1][0]) && $rawcmd[1][0] == commandPrefix()) {
              if (!isIgnoredUser()) {
                  ifPrivilegesExecuteCommand();
     
                  if (!function_exists('plugin_')) {
                      function plugin_() { }
                  }
              }
          }
       }

       /* if disconected */
       handleDisconnection($rawData, $server_port);
    }
}
//---------------------------------------------------------------------------------------------------------
function response($_msg)
{
    switch (loadValueFromConfigFile('RESPONSE', 'bot response')) {
        case 'channel':
            sayInChannel(getBotChannel(), $_msg);
            break;

        case 'notice':
            sendNotice(userNickname(), $_msg);  
            break;

        case 'priv':
            sendPRIVMSG(userNickname(), $_msg);
            break;
    }
}
//---------------------------------------------------------------------------------------------------------
function msgFromServer()
{
    $msg = null;
    
    for ($i=3; $i < count(dataArray()); $i++) {
         $msg .= str_replace(':', '', dataArray()[$i]).' ';
    }

    return $msg;
}
//---------------------------------------------------------------------------------------------------------
function userNickname()
{
    if (preg_match('/^:(.*)\!(.*)\@(.*)$/', dataArray()[0], $userNickname)) {
        return $userNickname[1];
    }
}
//---------------------------------------------------------------------------------------------------------
function userIdent()
{
    if (preg_match('/^:(.*)\!(.*)\@(.*)$/', dataArray()[0], $userIdent)) {
        return $userIdent[2];
    }
}
//---------------------------------------------------------------------------------------------------------
function userHostname()
{
    if (preg_match('/^:(.*)\!(.*)\@(.*)$/', dataArray()[0], $userHostname)) {
        return $userHostname[3];
    }
}
//---------------------------------------------------------------------------------------------------------
function userIdentAndHostname()
{
    if (preg_match('/^:(.*)\!(.*)\@(.*)$/', dataArray()[0], $source)) {
        return $source[2].'@'.$source[3];
    }
}
//---------------------------------------------------------------------------------------------------------
function userNickIdentAndHostname()
{
    if (preg_match('/^:(.*)\!(.*)\@(.*)$/', dataArray()[0], $source)) {
        if (isset($source[1])) {
            return $source[1].'!'.$source[2].'@'.$source[3];
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function msgPiece()
{
    $args = null;

    for ($i=4; $i < count(dataArray()); $i++) {
         $args .= dataArray()[$i].' ';
    }

    $pieces = explode(" ", $args);

    $piece1 = $pieces[0] ?? '';
    $piece2 = $pieces[1] ?? '';
    $piece3 = $pieces[2] ?? '';
    $piece4 = $pieces[3] ?? '';

    return [$piece1, $piece2, $piece3, $piece4];
}
//---------------------------------------------------------------------------------------------------------
function commandFromUser() /* first command only */
{
    if (isset(dataArray()[4]) && !empty(dataArray()[4])) {
        return dataArray()[4];
    }
}
//---------------------------------------------------------------------------------------------------------
function dataArray()
{
    return explode(' ', trim($GLOBALS['rawData']));
}
//---------------------------------------------------------------------------------------------------------
function all_args_from_user_array() /* start from 1 */
{
    $input = null;

    for ($i=4; $i <= (count(dataArray())-1); $i++) {
         $input .= dataArray()[$i]." ";
    }

    $data = rtrim($input);
    $data = explode(' ', $data);
    
    array_unshift($data, '');
    
    unset($data[0]);
    
    return $data;
}
//---------------------------------------------------------------------------------------------------------
function inputFromLine($_index)
{
    $a = dataArray();
    $current = '';

    while (isset($a[$_index])) {
           $current .= $a[$_index].' ';
           $_index++;
    }

    $string = preg_replace('/^:/', '', $current, 1);
    $string = substr($string, 0, -1);

    return $string;
}
//---------------------------------------------------------------------------------------------------------
function handleDisconnection($_data, $_server_port)
{
    cliRaw($_data, 0);
    
    $msg1  = 'Cannot connect to: '.$_server_port.' - ';

    $toManyUsersMsgs = ['Too many user connections', 'Too many connections from your IP', 'Session limit exceeded'];

    /* if server password missing */
    if (preg_match("~\bYou are not authorized to connect to this server\b~", $_data)) {
        cliBot($msg1.'Server requires password to connect! Exiting.');
        winSleep(10);
    }

    /* too many users */
    if (preg_match_all("~\b(?<toManyUsersMsgs>)\b~", $_data)) {
        cliBot($msg1.'Too many connections to server! Exiting.');
        winSleep(10);
    }

    ifDisconected($_server_port);
}
//---------------------------------------------------------------------------------------------------------
function ifDisconected($_server_port)
{
    cliBot('Disconnected from: '.$_server_port.' - Trying to reconnect...');

    usleep(loadValueFromConfigFile('SERVER', 'connect delay') * 1000000);

    connect();    
}
