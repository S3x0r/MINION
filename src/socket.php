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

function Connect()
{
    /* check if panel is not closed */
    if (isRunned('serv')) {
        kill('serv') ? cliLog('[bot] Detected Panel still running, Terminating.') : false;
    }

    cliLog("[bot] Connecting to: {$GLOBALS['CONFIG_SERVER']}, port: {$GLOBALS['CONFIG_PORT']}\n");

    $i = 0;

    while ($i++ <= $GLOBALS['CONFIG_TRY_CONNECT']) {
           $GLOBALS['socket'] = @fsockopen($GLOBALS['CONFIG_SERVER'], $GLOBALS['CONFIG_PORT']);
        if ($GLOBALS['socket'] == false) {
            cliLog("[bot] Cannot connect to: {$GLOBALS['CONFIG_SERVER']}:{$GLOBALS['CONFIG_PORT']}, trying again ({$i}/{$GLOBALS['CONFIG_TRY_CONNECT']})...");
            PlaySound('error_conn.mp3');
            usleep($GLOBALS['CONFIG_CONNECT_DELAY'] * 1000000); // reconnect delay
            if ($i == $GLOBALS['CONFIG_TRY_CONNECT']) {
                cliLog('[bot] Unable to connect to server, exiting program.');
                PlaySound('error_conn.mp3');
                exit;
            }
        } else {
                 Identify();
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function Identify()
{
    if (!empty($GLOBALS['CONFIG_SERVER_PASSWD'])) {
        toServer("PASS {$GLOBALS['CONFIG_SERVER_PASSWD']}");
    }

    toServer("NICK {$GLOBALS['CONFIG_NICKNAME']}");

    toServer("USER {$GLOBALS['CONFIG_IDENT']} 8 * :{$GLOBALS['CONFIG_NAME']}");

    SocketLoop();
}
//---------------------------------------------------------------------------------------------------------
function SocketLoop()
{
    global $args;
    global $args1;
    global $USER;
    global $USER_IDENT;
    global $USER_HOST;
    global $host;
    global $piece1;
    global $piece2;
    global $piece3;
    global $piece4;
    global $rawDataArray;
    global $rawcmd;
    global $mask;
    global $BOT_NICKNAME;
    global $I_USE_RND_NICKNAME;
//---------------------------------------------------------------------------------------------------------
    /* set initial */
    $USER_IDENT         = null;
    $host               = null;
    $BOT_NICKNAME       = null;
    $mask               = null;
    $I_USE_RND_NICKNAME = null;

    /* save data for web panel */
    WebEntry();
//---------------------------------------------------------------------------------------------------------
    /* main socket loop */
    while (1) {
        while (!feof($GLOBALS['socket'])) {

            /* start timers */
            empty($GLOBALS['stop']) ? StartTimers() : false;

            /* get raw data */
            $rawData = fgets($GLOBALS['socket'], 1024);
//---------------------------------------------------------------------------------------------------------
            if ($GLOBALS['CONFIG_SHOW_RAW'] == 'yes') {
                if (!IsSilent()) {
                    echo $rawData;
                }
            }
//---------------------------------------------------------------------------------------------------------
            /* put raw data to array */
            $rawDataArray = explode(' ', trim($rawData));
//---------------------------------------------------------------------------------------------------------
            /* PING PONG game */
            if (isset($rawDataArray[0]) && $rawDataArray[0] == 'PING') {
                on_server_ping();
            }
//---------------------------------------------------------------------------------------------------------
            /* parse vars from rawDataArray[0] */
            if (preg_match('/^:(.*)\!(.*)\@(.*)$/', $rawDataArray[0], $source)) {
                $USER        = $source[1];
                $USER_IDENT  = $source[2];
                $host        = $source[3];
                $USER_HOST   = $USER_IDENT.'@'.$host;
            }
//---------------------------------------------------------------------------------------------------------
            /* ON JOIN */
            isset($rawDataArray[1]) && $rawDataArray[1] == 'JOIN' ? on_join() : false;

            /* ON PART */
            isset($rawDataArray[1]) && $rawDataArray[1] == 'PART' ? on_part() : false;

            /* ON KICK */
            isset($rawDataArray[1]) && $rawDataArray[1] == 'KICK' ? on_kick() : false;

            /* ON TOPIC */
            isset($rawDataArray[1]) && $rawDataArray[1] == 'TOPIC' ? on_TOPIC() : false;

            /* ON PRIVMSG */
            isset($rawDataArray[1]) && $rawDataArray[1] == 'PRIVMSG' ? on_privmsg() : false;

            /* ON NICK */
            isset($rawDataArray[1]) && $rawDataArray[1] == 'NICK' ? on_nick() : false;

            /* ON QUIT */
            isset($rawDataArray[1]) && $rawDataArray[1] == 'QUIT' ? on_quit() : false;

            /* ON MODE */
            isset($rawDataArray[1]) && $rawDataArray[1] == 'MODE' ? on_mode() : false;
//---------------------------------------------------------------------------------------------------------
            if (count($rawDataArray) < 4) {
                continue;
            }
//---------------------------------------------------------------------------------------------------------
            isset($rawDataArray[3]) ? $rawcmd = explode(':', $rawDataArray[3]) : false;

            /* Case sensitive */
            isset($rawcmd[1]) ? $rawcmd[1] = strtolower($rawcmd[1]) : false;

            $args = null; for ($i=4; $i < count($rawDataArray); $i++) {
                $args .= $rawDataArray[$i].'';
            }
            $args1 = null; for ($i=4; $i < count($rawDataArray); $i++) {
                $args1 .= $rawDataArray[$i].' ';
            }

            isset($USER) ? $mask = $USER.'!'.$USER_IDENT.'@'.$host : false;

            $pieces = explode(" ", $args1);

            isset($pieces[0]) ? $piece1 = $pieces[0] : $piece1 = '';
            isset($pieces[1]) ? $piece2 = $pieces[1] : $piece2 = '';
            isset($pieces[2]) ? $piece3 = $pieces[2] : $piece3 = '';
            isset($pieces[3]) ? $piece4 = $pieces[3] : $piece4 = '';
//---------------------------------------------------------------------------------------------------------
            if (isset($rawDataArray[1])) {
                switch ($rawDataArray[1]) {
                    case '001': /* server welcome message */
                        on_001();
                        break;
                    case '002': /* host, version server */
                        on_002();
                        break;
                    case '003': /* server creation time */
                        on_003();
                        break;
                    case '303': /* ison */
                        on_303();
                        break;
                    case '331': /* topic */
                        on_331();
                        break;
                    case '332': /* topic */
                        on_332();
                        break;
                    case '433': /* if nick already exists */
                        on_432();
                        break;
                    case '432': /* if nick reserved */
                        on_432();
                        break;
                    case '422': /* join if no motd */
                        on_376();
                        break;
                    case '376': /* join after motd */
                        on_376();
                        break;
                    case '324': /* channel modes */
                        on_324();
                        break;
                    case '353': /* on channel join inf */
                        on_353();
                        break;
                    case '366': /* on end names list */
                        on_366();
                        break;
                    case '471': /* if +limit on channel */
                        on_471();
                        break;
                    case '473': /* if +invite on channel */
                        on_473();
                        break;
                    case '474': /* if bot +banned on channel */
                        on_474();
                        break;
                    case '475': /* if +key on channel */
                        on_475();
                        break;
                }
            }
//---------------------------------------------------------------------------------------------------------
            /* CTCP */
            if (empty($GLOBALS['stop'])) {
                if ($GLOBALS['CONFIG_CTCP_RESPONSE'] == 'yes' && isset($rawcmd[1])) {
                    CTCP();
                }
            }

            /* Core command: 'Unpause' Needs to be outside stop :) */
            if (isset($rawcmd[1]) && HasOwner($mask) && $rawcmd[1] == $GLOBALS['CONFIG_CMD_PREFIX'].'unpause') {
                CoreCmd_Unpause();
            }
//---------------------------------------------------------------------------------------------------------
            if (empty($GLOBALS['stop'])) {
                /* Core command: 'Panel' */
                if (isset($rawcmd[1]) && HasOwner($mask) && $rawcmd[1] == $GLOBALS['CONFIG_CMD_PREFIX'].'panel') {
                    CoreCmd_Panel();
                }
                /* Core command: 'Load' */
                if (isset($rawcmd[1]) && HasOwner($mask) && $rawcmd[1] == $GLOBALS['CONFIG_CMD_PREFIX'].'load') {
                    CoreCmd_Load();
                }
                /* Core command: 'Unload' */
                if (isset($rawcmd[1]) && HasOwner($mask) && $rawcmd[1] == $GLOBALS['CONFIG_CMD_PREFIX'].'unload') {
                    CoreCmd_Unload();
                }
                /* Core command: 'Pause' */
                if (isset($rawcmd[1]) && HasOwner($mask) && $rawcmd[1] == $GLOBALS['CONFIG_CMD_PREFIX'].'pause') {
                    CoreCmd_Pause();
                }
                /* Core commands: 'Seen' */
                if (isset($rawcmd[1]) && $rawcmd[1] == $GLOBALS['CONFIG_CMD_PREFIX'].'seen') {
                    CoreCmd_Seen();
                }

                /* Core command: "register 'password'" */
                if (isset($rawcmd[1]) && $rawcmd[1] == 'register') {
                    if ($rawDataArray[2] == getBotNickname()) {
                        CoreCmd_RegisterToBot();
                    }
                }
//---------------------------------------------------------------------------------------------------------
                /* plugins */
                if (HasOwner($mask) && isset($rawcmd[1])) {
                    $pn = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], '', $rawcmd[1]);

                    in_array($rawcmd[1], $GLOBALS['OWNER_PLUGINS']) ? call_user_func('plugin_'.$pn) : false;
                    in_array($rawcmd[1], $GLOBALS['ADMIN_PLUGINS']) ? call_user_func('plugin_'.$pn) : false;
                    in_array($rawcmd[1], $GLOBALS['USER_PLUGINS']) ? call_user_func('plugin_'.$pn) : false;
                } elseif (!HasOwner($mask) && HasAdmin($mask) && isset($rawcmd[1])) {
                    $pn = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], '', $rawcmd[1]);

                    in_array($rawcmd[1], $GLOBALS['ADMIN_PLUGINS']) ? call_user_func('plugin_'.$pn) : false;
                    in_array($rawcmd[1], $GLOBALS['USER_PLUGINS']) ? call_user_func('plugin_'.$pn) : false;
                } elseif (!HasOwner($mask) && !HasAdmin($mask) && isset($rawcmd[1])) {
                    $pn = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], '', $rawcmd[1]);
                
                    in_array($rawcmd[1], $GLOBALS['USER_PLUGINS']) ? call_user_func('plugin_'.$pn) : false;
                }

                if (!function_exists('plugin_')) {
                    function plugin_()
                    {
                    }
                }
            }
//---------------------------------------------------------------------------------------------------------
        }
        /* if disconected */
        if (empty($GLOBALS['disconnected'])) {
            cliLog("[bot] Cannot connect to: {$GLOBALS['CONFIG_SERVER']}:{$GLOBALS['CONFIG_PORT']}, trying again...");
            Connect();
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function response($msg)
{
    switch ($GLOBALS['CONFIG_BOT_RESPONSE']) {
        case 'channel':
            toServer("PRIVMSG ".getBotChannel()." :$msg");
            usleep($GLOBALS['CONFIG_CHANNEL_DELAY'] * 1000000);
            break;

        case 'notice':
            toServer("NOTICE {$GLOBALS['USER']} :$msg");
            usleep($GLOBALS['CONFIG_NOTICE_DELAY'] * 1000000);
            break;

        case 'priv':
            toServer("PRIVMSG {$GLOBALS['USER']} :$msg");
            usleep($GLOBALS['CONFIG_PRIVATE_DELAY'] * 1000000);
            break;
    }
}
//---------------------------------------------------------------------------------------------------------
function privateMsg($message)
{
    toServer("PRIVMSG {$GLOBALS['USER']} :$msg");
    usleep($GLOBALS['CONFIG_PRIVATE_DELAY'] * 1000000);
}
//---------------------------------------------------------------------------------------------------------
function joinChannel($channel)
{
    toServer("JOIN {$channel}");
}
//---------------------------------------------------------------------------------------------------------
function toServer($data)
{   
    if ($GLOBALS['CONFIG_SHOW_RAW'] == 'yes' && !IsSilent()) {
        echo $data.N;
    }

    fputs($GLOBALS['socket'], "{$data}\n");
}
//---------------------------------------------------------------------------------------------------------
function msgFromServer()
{
    $msg = null;
    
    for ($i=3; $i < count($GLOBALS['rawDataArray']); $i++) {
         $msg .= str_replace(':', '', $GLOBALS['rawDataArray'][$i]).' ';
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
