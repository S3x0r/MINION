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
function on_server_ping()
{
    fputs($GLOBALS['socket'], "PONG ".$GLOBALS['ex'][1]."\n");
}
//---------------------------------------------------------------------------------------------------------
function on_first_start()
{
    /* event after end motd */
}
//---------------------------------------------------------------------------------------------------------
function on_bot_join_channel()
{
    BOT_RESPONSE('bello! :)');
}
//---------------------------------------------------------------------------------------------------------
function on_join()
{
    /* if someone join */
    CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') has joined '.$GLOBALS['channel'], '1');
    
    /* if bot join */
    if ($GLOBALS['USER'] == $GLOBALS['BOT_NICKNAME']) {
        /* add channel to array */
        array_push($GLOBALS['BOT_CHANNELS'], $GLOBALS['channel']);
        
        /* save data for web panel */
        $data = implode(' ', $GLOBALS['BOT_CHANNELS']);
        WebSave('WEB_BOT_CHANNELS', $data);

        /* on bot join event */
        on_bot_join_channel();
    }
    
    /* auto op */
    if ($GLOBALS['CONFIG_AUTO_OP'] == 'yes' && BotOpped() == true) {
        $cfg = new IniParser($GLOBALS['config_file']);
        $GLOBALS['CONFIG_AUTO_OP_LIST'] = $cfg->get("OWNER", "auto_op_list");

        $auto_op_list_c = $GLOBALS['CONFIG_AUTO_OP_LIST'];
        $pieces = explode(", ", $auto_op_list_c);

        $mask2 = $GLOBALS['USER'].'!'.$GLOBALS['USER_IDENT'].'@'.$GLOBALS['host'];

        if (in_array($mask2, $pieces)) {
            CLI_MSG(TR_31.' '.$GLOBALS['USER'].' '.TR_32, '1');
            fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +o '.$GLOBALS['USER']."\n");
            PlaySound('prompt.mp3');
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function on_part()
{
    /* if bot part */
    if ($GLOBALS['USER'] == $GLOBALS['BOT_NICKNAME']) {
        /* delete channel from array */
        $key = array_search($GLOBALS['channel'], $GLOBALS['BOT_CHANNELS']);
        if ($key!== false) {
            unset($GLOBALS['BOT_CHANNELS'][$key]);

            /* save data for web panel */
            $data = implode(' ', $GLOBALS['BOT_CHANNELS']);
            WebSave('WEB_BOT_CHANNELS', $data);
        }
        /* set to not opped */
        unset($GLOBALS['BOT_OPPED']);
        
        /* delete channel modes */
        unset($GLOBALS['CHANNEL_MODES']);
    }
    CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') has leaved '.$GLOBALS['channel'], '1');
}
//---------------------------------------------------------------------------------------------------------
function on_kick()
{
    /* if BOT kicked */
    if (isset($GLOBALS['ex'][3]) && $GLOBALS['ex'][3] == $GLOBALS['BOT_NICKNAME']) {
        /* set to not opped */
        unset($GLOBALS['BOT_OPPED']);

        /* delete channel from array */
        $key = array_search($GLOBALS['channel'], $GLOBALS['BOT_CHANNELS']);
        if ($key!== false) {
            unset($GLOBALS['BOT_CHANNELS'][$key]);
        }

        /* delete channel modes */
        unset($GLOBALS['CHANNEL_MODES']);

        /* rejoin if kicked? */
        if ($GLOBALS['CONFIG_AUTO_REJOIN'] == 'yes') {
            CLI_MSG(TR_30, '1');
            sleep(2);
            fputs($GLOBALS['socket'], "JOIN :".$GLOBALS['ex'][2]."\n");
            PlaySound('prompt.mp3');
        }
    }
    /* else */
    CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') kicked '.
        $GLOBALS['ex'][3].' from '.$GLOBALS['channel'], '1');
}
//---------------------------------------------------------------------------------------------------------
function on_topic()
{
    /* topic change */
    CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') ('.
        $GLOBALS['channel'].') sets topic: '.parse_ex3(), '1');
}
//---------------------------------------------------------------------------------------------------------
function on_privmsg()
{
    CLI_MSG('['.$GLOBALS['channel'].'] <'.$GLOBALS['USER'].'> '.parse_ex3(), '1');
}
//---------------------------------------------------------------------------------------------------------
function on_mode()
{
    /* check if someone changed modes and set default if changed */
    if ($GLOBALS['ex'][2] == $GLOBALS['channel'] && $GLOBALS['USER'] != $GLOBALS['BOT_NICKNAME']) {
        set_channel_modes();
    }

    /* if server mode */
    if (empty($GLOBALS['USER_HOST'])) { /* TODO: save bot mode */
    } else {
        /* if bot opped */
        if (isset($GLOBALS['ex'][4]) && $GLOBALS['ex'][4] == $GLOBALS['BOT_NICKNAME']) {
            if (isset($GLOBALS['ex'][3]) && $GLOBALS['ex'][3] == '+o') {
                /* info */
                CLI_MSG('[BOT] I have OP now on: '.$GLOBALS['channel']. ', from: '.$GLOBALS['USER'].
                    ' ('.$GLOBALS['USER_HOST'].')', '1');
                
                /* set to opped var */
                $GLOBALS['BOT_OPPED'] = 'yes';

                /* sound */
                PlaySound('prompt.mp3');

                /* and set it */
                fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel']."\n");
                set_channel_modes();

                /* set ban list */
                set_bans();
            }
        }
        /* if bot deoped */
        if (isset($GLOBALS['ex'][4]) && $GLOBALS['ex'][4] == $GLOBALS['BOT_NICKNAME']) {
            if (isset($GLOBALS['ex'][3]) && $GLOBALS['ex'][3] == '-o') {
                /* info */
                CLI_MSG('[BOT] User: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') DEOPED ME on channel: '.
                    $GLOBALS['channel'], '1');
                
                /* bot not opped anymore */
                unset($GLOBALS['BOT_OPPED']);

                /* sound */
                PlaySound('prompt.mp3');
            }
        }
        if (isset($GLOBALS['ex'][4])) {
            $rest = $GLOBALS['ex'][4];
        } else {
                  $rest = '';
        }
        /* show message about modes */
        CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') sets mode: '.$GLOBALS['ex'][3].' '.$rest, '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function on_nick()
{
    if ($GLOBALS['USER'] == $GLOBALS['CONFIG_NICKNAME'] && empty($GLOBALS['I_USE_RND_NICKNAME'])) {
        $GLOBALS['BOT_NICKNAME'] = str_replace(':', '', $GLOBALS['ex'][2]);
        wcliExt();
        CLI_MSG('[BOT] My new nickname is: '.$GLOBALS['BOT_NICKNAME'], '1');
    } elseif (isset($GLOBALS['BOT_NICKNAME']) && $GLOBALS['USER'] == $GLOBALS['BOT_NICKNAME']) {
              $GLOBALS['BOT_NICKNAME'] = str_replace(':', '', $GLOBALS['ex'][2]);
              wcliExt();
              CLI_MSG('[BOT] My new nickname is: '.$GLOBALS['BOT_NICKNAME'], '1');
    } else {
              $new = str_replace(':', '', $GLOBALS['ex'][2]);
              CLI_MSG(' * '.$GLOBALS['USER']. ' changed nick to '.$new, '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function on_quit()
{
    if (isset($GLOBALS['ex'][2])) {
        $quit = $GLOBALS['ex'][2];
    } else {
              $quit = '';
    } //need fix not showing all
   
    CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') Quit ('.$quit.')', '1');
}
//---------------------------------------------------------------------------------------------------------
function on_001() /* server message */
{
    CLI_MSG('S>'.$GLOBALS['srv_msg'], '1');
}
//---------------------------------------------------------------------------------------------------------
function on_002() /* host, version server */
{
    CLI_MSG('S>'.$GLOBALS['srv_msg'], '1');
}
//---------------------------------------------------------------------------------------------------------
function on_003() /* server creation time */
{
    CLI_MSG('S>'.$GLOBALS['srv_msg'], '1');
}
//---------------------------------------------------------------------------------------------------------
function on_324() /* channel modes */
{
    if (isset($GLOBALS['ex'][4])) {
        unset($GLOBALS['CHANNEL_MODES']);
        $GLOBALS['CHANNEL_MODES'] = str_replace('+', '', $GLOBALS['ex'][4]);
    }
}
//---------------------------------------------------------------------------------------------------------
function on_332() /* topic */
{
    CLI_MSG('Topic on: '.parse_ex3(), '1');
}
//---------------------------------------------------------------------------------------------------------
function on_353() /* on channel join inf */
{
    if (isset($GLOBALS['ex'][2]) && $GLOBALS['ex'][2] == $GLOBALS['BOT_NICKNAME']) {
        /* set channel from 353 */
        $GLOBALS['channel'] = $GLOBALS['ex'][4];

        if (isset($GLOBALS['ex'][5]) && $GLOBALS['ex'][5] == ':@'.$GLOBALS['BOT_NICKNAME']) {
            /* i have op: first on channel */
            $GLOBALS['BOT_OPPED'] = 'yes';

            /* set modes: first on channel */
            set_channel_modes();

            /* set ban list */
            set_bans();
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function on_376() /* join after motd */
{
    if (empty($GLOBALS['silent_mode'])) {
        echo "\n";
    }
    CLI_MSG(TR_58.' '.$GLOBALS['BOT_NICKNAME'], '1');

    /* register to bot info */
    if (isset($GLOBALS['if_first_time_pwd_change'])) {
        CLI_MSG('****************************************************', '0');
        CLI_MSG(TR_34.' /msg '.$GLOBALS['BOT_NICKNAME'].' register '.$GLOBALS['pwd'], '0');
        CLI_MSG('****************************************************', '0');
        unset($GLOBALS['pwd']);
        unset($GLOBALS['if_first_time_pwd_change']);
    }

    /* wcli extension */
    wcliExt();

    /* if autojoin */
    if ($GLOBALS['CONFIG_AUTO_JOIN'] == 'yes') {
        /* play sound :) */
        PlaySound('connected.mp3');

        CLI_MSG(TR_35.' '.$GLOBALS['CONFIG_CNANNEL'], '1');
        JOIN_CHANNEL($GLOBALS['CONFIG_CNANNEL']);
        
        /* on first start event */
        on_first_start();
    } else {
             /* play sound :) */
             PlaySound('connected.mp3');

             /* on first start event */
             on_first_start();
    }
}
//---------------------------------------------------------------------------------------------------------
function on_422() /* join if no motd */
{
    on_376();
}
//---------------------------------------------------------------------------------------------------------
function on_432() /* if nick reserved */
{
    /* keep nick */
    if ($GLOBALS['CONFIG_KEEP_NICK']=='yes') {
        $GLOBALS['NICKNAME_FROM_CONFIG'] = $GLOBALS['CONFIG_NICKNAME'];
        $GLOBALS['I_USE_RND_NICKNAME']='1';
        $GLOBALS['first_time'] = time();
    }
   
    /* set random nick */
    $GLOBALS['BOT_NICKNAME'] = $GLOBALS['BOT_NICKNAME'].'|'.rand(0, 99);
    CLI_MSG(TR_33.' '.$GLOBALS['BOT_NICKNAME']."\n", '1');

    fputs($GLOBALS['socket'], 'NICK '.$GLOBALS['BOT_NICKNAME']."\n");
}
//---------------------------------------------------------------------------------------------------------
function on_433() /* if nick already exists */
{
    on_432();
}
//---------------------------------------------------------------------------------------------------------
function on_471() /* if +limit on channel */
{
    CLI_MSG('[BOT] I cannot join, channel is full', '1');
}
//---------------------------------------------------------------------------------------------------------
function on_473() /* if +invite on channel */
{
    CLI_MSG('[BOT] I cannot join, channel is invite only', '1');
}
//---------------------------------------------------------------------------------------------------------
function on_474() /* if bot +banned on channel */
{
    CLI_MSG('[BOT] I cannot join, im banned on channel', '1');
}
//---------------------------------------------------------------------------------------------------------
function on_475() /* if +key on channel */
{
    if (!empty($GLOBALS['CONFIG_CHANNEL_KEY'])) {
        JOIN_CHANNEL($GLOBALS['CONFIG_CNANNEL'].' '.$GLOBALS['CONFIG_CHANNEL_KEY']);
    } else {
              CLI_MSG('[BOT] I cannot join, bad channel key in config or key not set', '1');
              PlaySound('prompt.mp3');
    }
}
