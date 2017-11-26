<?php
/* Copyright (c) 2013-2017, S3x0r <olisek@gmail.com>
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

if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use CLI to run it -> php BOT.php</h2>');
}
//---------------------------------------------------------------------------------------------------------
function Connect()
{
    CLI_MSG(TR_27.' '.$GLOBALS['CONFIG_SERVER'].', '.TR_26.' '.$GLOBALS['CONFIG_PORT'].PHP_EOL, '1');

    $i=0;

    while ($i++ < $GLOBALS['CONFIG_TRY_CONNECT']) {
           $GLOBALS['socket'] = fsockopen($GLOBALS['CONFIG_SERVER'], $GLOBALS['CONFIG_PORT']);
           //socket_set_blocking($GLOBALS['socket'], false);
        if ($GLOBALS['socket']==false) {
            PlaySound('error_conn.mp3');
            CLI_MSG(TR_28, '1');
            usleep($GLOBALS['CONFIG_CONNECT_DELAY'] * 1000000);
            if ($i==$GLOBALS['CONFIG_TRY_CONNECT']) {
                PlaySound('error_conn.mp3');
                CLI_MSG(TR_29, '1');
                die();
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
        fputs($GLOBALS['socket'], 'PASS '.$GLOBALS['CONFIG_SERVER_PASSWD']."\n");
    }

    fputs($GLOBALS['socket'], 'NICK '.$GLOBALS['CONFIG_NICKNAME']."\n");

    fputs($GLOBALS['socket'], 'USER '.$GLOBALS['CONFIG_IDENT'].' 8 * :'.$GLOBALS['CONFIG_NAME']."\n");

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
            if (isset($ex[2])) {
                $channel = str_replace(':#', '#', $ex[2]);
            }
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
            if (isset($ex[1]) && $ex[1] == 'JOIN') {
                on_join();
            }
            /* ON PART */
            if (isset($ex[1]) && $ex[1] == 'PART') {
                on_part();
            }
            /* ON KICK */
            if (isset($ex[1]) && $ex[1] == 'KICK') {
                on_kick();
            }
            /* ON TOPIC */
            if (isset($ex[1]) && $ex[1] == 'TOPIC') {
                on_topic();
            }
            /* ON PRIVMSG */
            if (isset($ex[1]) && $ex[1] == 'PRIVMSG') {
                on_privmsg();
            }
            /* ON MODE */
            if (isset($ex[1]) && $ex[1] == 'MODE') {
                on_mode();
            }
            /* ON NICK */
            if (isset($ex[1]) && $ex[1] == 'NICK') {
                on_nick();
            }
            /* ON QUIT */
            if (isset($ex[1]) && $ex[1] == 'QUIT') {
                on_quit();
            }
//---------------------------------------------------------------------------------------------------------
            if (count($ex) < 4) {
                continue;
            }

            $rawcmd = explode(':', $ex[3]);

            /* Case sensitive */
            if (isset($rawcmd[1])) {
                $rawcmd[1] = strtolower($rawcmd[1]);
            }

            $args = null; for ($i=4; $i < count($ex); $i++) {
                $args .= $ex[$i].'';
            }
            $args1 = null; for ($i=4; $i < count($ex); $i++) {
                $args1 .= $ex[$i].' ';
            }
            $srv_msg = null; for ($i=3; $i < count($ex); $i++) {
                $srv_msg .= str_replace(':', '', $ex[$i]).' ';
            }

            if (isset($USER)) {
                $mask = $USER.'!'.$USER_IDENT.'@'.$host;
            }

            $pieces = explode(" ", $args1);

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
            /* timers */
            StartTimers();

            /* CTCP */
            if ($GLOBALS['CONFIG_CTCP_RESPONSE'] == 'yes' && isset($rawcmd[1])) {
                CTCP();
            }
//---------------------------------------------------------------------------------------------------------
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

                if (in_array($rawcmd[1], $GLOBALS['OWNER_PLUGINS'])) {
                    call_user_func('plugin_'.$pn);
                }

                if (in_array($rawcmd[1], $GLOBALS['ADMIN_PLUGINS'])) {
                    call_user_func('plugin_'.$pn);
                }

                if (in_array($rawcmd[1], $GLOBALS['USER_PLUGINS'])) {
                    call_user_func('plugin_'.$pn);
                }
            } elseif (!HasOwner($mask) && HasAdmin($mask) && isset($rawcmd[1])) {
                $pn = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], '', $rawcmd[1]);
                
                if (in_array($rawcmd[1], $GLOBALS['ADMIN_PLUGINS'])) {
                    call_user_func('plugin_'.$pn);
                }
              
                if (in_array($rawcmd[1], $GLOBALS['USER_PLUGINS'])) {
                    call_user_func('plugin_'.$pn);
                }
            } elseif (!HasOwner($mask) && !HasAdmin($mask) && isset($rawcmd[1])) {
                $pn = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], '', $rawcmd[1]);
                
                if (in_array($rawcmd[1], $GLOBALS['USER_PLUGINS'])) {
                    call_user_func('plugin_'.$pn);
                }
            }

            if (!function_exists('plugin_')) {
                function plugin_()
                {
                }
            }
//---------------------------------------------------------------------------------------------------------
            /* keep nick - check every 60 sec */
            if ($GLOBALS['CONFIG_KEEP_NICK']=='yes' && isset($GLOBALS['I_USE_RND_NICKNAME'])) {
                if (time()-$GLOBALS['first_time'] > 60) {
                    fputs($GLOBALS['socket'], "ISON :".$GLOBALS['NICKNAME_FROM_CONFIG']."\n");
                    $GLOBALS['first_time'] = time();
                }
                if ($GLOBALS['ex'][1] == '303' && $GLOBALS['ex'][3] == ':') {
                    fputs($GLOBALS['socket'], "NICK ".$GLOBALS['NICKNAME_FROM_CONFIG']."\n");
                    $GLOBALS['BOT_NICKNAME'] = $GLOBALS['NICKNAME_FROM_CONFIG'];
                    unset($GLOBALS['I_USE_RND_NICKNAME']);
                    CLI_MSG('[BOT]: '.TR_37, '1');
                    /* wcli extension */
                    wcliExt();
                }
            }
//---------------------------------------------------------------------------------------------------------
        }
        /* if disconected */
        CLI_MSG('[BOT] Disconected from server, I will try to connect again...', '1');
        Connect();
    }
}
