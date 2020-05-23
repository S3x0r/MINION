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

function Connect()
{
    CLI_MSG("Connecting to: {$GLOBALS['CONFIG_SERVER']}, port: {$GLOBALS['CONFIG_PORT']}\n", '1');

    $i = 0;

    while ($i++ <= $GLOBALS['CONFIG_TRY_CONNECT']) {
           $GLOBALS['socket'] = @fsockopen($GLOBALS['CONFIG_SERVER'], $GLOBALS['CONFIG_PORT']);
        if ($GLOBALS['socket'] == false) {
            CLI_MSG("Cannot connect to: {$GLOBALS['CONFIG_SERVER']}:{$GLOBALS['CONFIG_PORT']}, trying again ({$i}/{$GLOBALS['CONFIG_TRY_CONNECT']})...", '1');
            PlaySound('error_conn.mp3');
			usleep($GLOBALS['CONFIG_CONNECT_DELAY'] * 1000000); // reconnect delay
            if ($i == $GLOBALS['CONFIG_TRY_CONNECT']) {
                CLI_MSG('Unable to connect to server, exiting program.', '1');
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
    /* send PASSWORD / NICK / USER to server */

    if (!empty($GLOBALS['CONFIG_SERVER_PASSWD'])) {
        fputs($GLOBALS['socket'], "PASS {$GLOBALS['CONFIG_SERVER_PASSWD']}\n");
    }

    fputs($GLOBALS['socket'], "NICK {$GLOBALS['CONFIG_NICKNAME']}\n");

    fputs($GLOBALS['socket'], "USER {$GLOBALS['CONFIG_IDENT']} 8 * :{$GLOBALS['CONFIG_NAME']}\n");

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
    global $ex;
    global $rawcmd;
    global $mask;
    global $srv_msg;

    global $BOT_CHANNELS;
    global $BOT_NICKNAME;
    global $channel;
    global $I_USE_RND_NICKNAME;
//---------------------------------------------------------------------------------------------------------
    /* set initial */
    $USER_IDENT = null;
    $host  = null;
    $BOT_NICKNAME = $GLOBALS['CONFIG_NICKNAME'];
    $channel = $GLOBALS['CONFIG_CNANNEL'];
    $I_USE_RND_NICKNAME = null;
    $BOT_CHANNELS = array();

    /* save data for web panel */
    WebEntry();
//---------------------------------------------------------------------------------------------------------
    /* main socket loop */
    while (1) {
        while (!feof($GLOBALS['socket'])) {
            /* timers */
            empty($GLOBALS['stop']) ? StartTimers() : false;

            $mask = null;

            /* get data */
            $data = fgets($GLOBALS['socket'], 1024);
//---------------------------------------------------------------------------------------------------------
            if ($GLOBALS['CONFIG_SHOW_RAW'] == 'yes') {
                if (!IsSilent()) {
                    echo $data;
                }
            }
//---------------------------------------------------------------------------------------------------------
            /* put data to array */
            $ex = explode(' ', trim($data));

            /* get channel from ex[2] */
            isset($ex[2]) ? $channel = str_replace(':#', '#', $ex[2]) : false;

//---------------------------------------------------------------------------------------------------------
            /* PING PONG game */
            if (isset($ex[0]) && $ex[0] == 'PING') {
                on_server_ping();
            }
//---------------------------------------------------------------------------------------------------------
            /* parse vars from ex[0] */
            if (preg_match('/^:(.*)\!(.*)\@(.*)$/', $ex[0], $source)) {
                $USER        = $source[1];
                $USER_IDENT  = $source[2];
                $host        = $source[3];
                $USER_HOST   = $USER_IDENT.'@'.$host;
            } else {
                    /* put server to var and remove ':' */
                    $server = str_replace(':', '', $ex[0]);
            }
//---------------------------------------------------------------------------------------------------------
            /* ON JOIN */
            isset($ex[1]) && $ex[1] == 'JOIN' ? on_join() : false;

            /* ON PART */
            isset($ex[1]) && $ex[1] == 'PART' ? on_part() : false;

            /* ON KICK */
            isset($ex[1]) && $ex[1] == 'KICK' ? on_kick() : false;

            /* ON TOPIC */
            isset($ex[1]) && $ex[1] == 'TOPIC' ? on_topic() : false;

            /* ON PRIVMSG */
            isset($ex[1]) && $ex[1] == 'PRIVMSG' ? on_privmsg() : false;

            /* ON MODE */
            isset($ex[1]) && $ex[1] == 'MODE' ? on_mode() : false;

            /* ON NICK */
            isset($ex[1]) && $ex[1] == 'NICK' ? on_nick() : false;

            /* ON QUIT */
            isset($ex[1]) && $ex[1] == 'QUIT' ? on_quit() : false;

//---------------------------------------------------------------------------------------------------------
            if (count($ex) < 4) {
                continue;
            }

            $rawcmd = explode(':', $ex[3]);

            /* Case sensitive */
            isset($rawcmd[1]) ? $rawcmd[1] = strtolower($rawcmd[1]) : false;

            $args = null; for ($i=4; $i < count($ex); $i++) {
                $args .= $ex[$i].'';
            }
            $args1 = null; for ($i=4; $i < count($ex); $i++) {
                $args1 .= $ex[$i].' ';
            }
            $srv_msg = null; for ($i=3; $i < count($ex); $i++) {
                $srv_msg .= str_replace(':', '', $ex[$i]).' ';
            }

            isset($USER) ? $mask = $USER.'!'.$USER_IDENT.'@'.$host : false;

            $pieces = explode(" ", $args1);

            isset($pieces[0]) ? $piece1 = $pieces[0] : $piece1 = '';
            isset($pieces[1]) ? $piece2 = $pieces[1] : $piece2 = '';
            isset($pieces[2]) ? $piece3 = $pieces[2] : $piece3 = '';
            isset($pieces[3]) ? $piece4 = $pieces[3] : $piece4 = '';
//---------------------------------------------------------------------------------------------------------
            if (isset($ex[1])) {
                switch ($ex[1]) {
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
                    if ($GLOBALS['ex'][2] == $GLOBALS['BOT_NICKNAME']) {
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
            CLI_MSG("Cannot connect to: {$GLOBALS['CONFIG_SERVER']}:{$GLOBALS['CONFIG_PORT']}, trying again...", '1');
            Connect();
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function set_channel_modes()
{
    if ($GLOBALS['CONFIG_KEEPCHAN_MODES'] == 'yes') {
        fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].N);
    
        if (BotOpped() == true) {
            if (isset($GLOBALS['CHANNEL_MODES']) && $GLOBALS['CHANNEL_MODES'] != $GLOBALS['CONFIG_CHANNEL_MODES']) {
                sleep(1);
                fputs($GLOBALS['socket'], "MODE {$GLOBALS['channel']} -{$GLOBALS['CHANNEL_MODES']}\n");
                sleep(1);
                fputs($GLOBALS['socket'], "MODE {$GLOBALS['channel']} +{$GLOBALS['CONFIG_CHANNEL_MODES']}\n");
            }
            if (empty($GLOBALS['CHANNEL_MODES'])) {
                if (!empty($GLOBALS['CONFIG_CHANNEL_MODES'])) {
                    sleep(1);
                    fputs($GLOBALS['socket'], "MODE {$GLOBALS['channel']} +{$GLOBALS['CONFIG_CHANNEL_MODES']}\n");
                }
            }
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function set_bans() /* set ban from config list */
{
    if (!empty($GLOBALS['CONFIG_BAN_LIST'])) {
        $ban_list = explode(', ', $GLOBALS['CONFIG_BAN_LIST']);
        foreach ($ban_list as $ban_address) {
            fputs($GLOBALS['socket'], "MODE {$GLOBALS['channel']} +b {$ban_address}\n");
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function response($msg)
{
    switch ($GLOBALS['CONFIG_BOT_RESPONSE']) {
        case 'channel':
            fputs($GLOBALS['socket'], "PRIVMSG {$GLOBALS['channel']} :$msg\n");
            usleep($GLOBALS['CONFIG_CHANNEL_DELAY'] * 1000000);
            break;

        case 'notice':
            fputs($GLOBALS['socket'], "NOTICE {$GLOBALS['USER']} :$msg\n");
            usleep($GLOBALS['CONFIG_NOTICE_DELAY'] * 1000000);
            break;

        case 'priv':
            fputs($GLOBALS['socket'], "PRIVMSG {$GLOBALS['USER']} :$msg\n");
            usleep($GLOBALS['CONFIG_PRIVATE_DELAY'] * 1000000);
            break;
    }
}
//---------------------------------------------------------------------------------------------------------
function privateMSG($msg)
{
    fputs($GLOBALS['socket'], "PRIVMSG {$GLOBALS['USER']} :$msg\n");
    usleep($GLOBALS['CONFIG_PRIVATE_DELAY'] * 1000000);
}
//---------------------------------------------------------------------------------------------------------
function joinChannel($channel)
{
    fputs($GLOBALS['socket'], "JOIN {$channel}\n");
}
