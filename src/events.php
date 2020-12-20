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

function on_server_ping()
{
    /* send PONG */
    toServer("PONG {$GLOBALS['rawDataArray'][1]}");
}
//---------------------------------------------------------------------------------------------------------
function on_001() /* server message */
{
    /* :server.name 001 minion :Welcome to the Testnet IRC Network minion!minion@localhost */
    
    /* 1.set server name */
    $GLOBALS['serverName'] = $GLOBALS['rawDataArray'][0];

    /* 1.set bot nickname */
    setBotNickname($GLOBALS['rawDataArray'][2]);

    cliLog('[server] '.msgFromServer());
}
//---------------------------------------------------------------------------------------------------------
function on_002() /* host, version server */
{
    /* :server.name 002 minion :Your host is server.name, running version ircd-123.4 */

    cliLog('[server] '.msgFromServer());
}
//---------------------------------------------------------------------------------------------------------
function on_003() /* server creation time */
{
    /* :server.name 003 minion :This server was created Sat Oct 10 15:08:58 2020 */

    cliLog('[server] '.msgFromServer());

    /* show info that we are connected */
    cli('');
    cliLog("[bot] Connected, my nickname is: ".getBotNickname());
}
//---------------------------------------------------------------------------------------------------------
function on_376() /* motd end */
{
    /* register to bot info */
    if (isset($GLOBALS['defaultPwdChanged'])) {
        cli(N.'*********************************************************');
        cli("Register to bot by typing /msg ".getBotNickname()." register <password>");
        cli('*********************************************************'.N);
        unset($GLOBALS['defaultPwdChanged']);
    }

    /* if autojoin */
    if ($GLOBALS['CONFIG_AUTO_JOIN'] == 'yes') {
        cliLog("[bot] Trying to join channel: {$GLOBALS['CONFIG_CNANNEL']}".N);
        joinChannel($GLOBALS['CONFIG_CNANNEL']);
    }

    /* send anon stats */
    Statistics();

    /* play sound :) */
    PlaySound('connected.mp3');
}
//---------------------------------------------------------------------------------------------------------
function on_mode() /* on MODE event */
{
    /* 1. set bot modes */
    if ($GLOBALS['rawDataArray'][0] == ':'.getBotNickname() && $GLOBALS['rawDataArray'][2] == getBotNickname()) {
        $GLOBALS['BOT_MODES'] = str_replace(':+', '', $GLOBALS['rawDataArray'][3]);
    }

    /* 1. set channel modes */
    if ($GLOBALS['rawDataArray'][0] == getServerName() && isset($GLOBALS['rawDataArray'][3]) && !empty($GLOBALS['rawDataArray'][0])) {
        $GLOBALS['CHANNEL_MODES'] = str_replace('+', '', $GLOBALS['rawDataArray'][3]);
    }

    if ($GLOBALS['rawDataArray'][0] != getServerName() && isset($GLOBALS['rawDataArray'][3]) && !empty($GLOBALS['rawDataArray'][3]) && $GLOBALS['rawDataArray'][2] != getBotNickname()) {
        isset($GLOBALS['rawDataArray'][4]) ? $add = $GLOBALS['rawDataArray'][4] : $add = '';
        cliLog("[".getBotChannel()."] * {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) sets mode: {$GLOBALS['rawDataArray'][3]} {$add}");
    }

    /* if bot opped */
    if (isset($GLOBALS['rawDataArray'][4]) && $GLOBALS['rawDataArray'][4] == getBotNickname()) {
        if (isset($GLOBALS['rawDataArray'][3]) && $GLOBALS['rawDataArray'][3] == '+o') {
            /* send info */
            cliLog("[bot] I have OP now on: ".getBotChannel().", from: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']})");
            
            /* on bot opped event */
            on_bot_opped();
    
        /* if bot deoped */
        } elseif (isset($GLOBALS['rawDataArray'][4]) && $GLOBALS['rawDataArray'][4] == getBotNickname()) {
            if (isset($GLOBALS['rawDataArray'][3]) && $GLOBALS['rawDataArray'][3] == '-o') {
                /* send info */
                cliLog("[bot] User: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) DEOPED ME on channel: ".getBotChannel());
                
                /* unset bot opped */
                unset($GLOBALS['BOT_OPPED']);

                /* play sound */
                PlaySound('prompt.mp3');
            }
        }
    }

    isset($GLOBALS['rawDataArray'][4]) ? $rest = $GLOBALS['rawDataArray'][4] : $rest = '';
}
//---------------------------------------------------------------------------------------------------------
function on_join()
{
    /* if user joined channel */
    if ($GLOBALS['USER'] != getBotNickname()) {
        cliLog("[".getBotChannel()."] * {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) has joined");

        /* Save Seen */
        SeenSave();
    }

    /* auto op */
    if ($GLOBALS['CONFIG_AUTO_OP'] == 'yes' && BotOpped() == true) {
        $cfg = new IniParser($GLOBALS['configFile']);
        $GLOBALS['CONFIG_AUTO_OP_LIST'] = $cfg->get("OWNER", "auto_op_list");

        $auto_op_list_c = $GLOBALS['CONFIG_AUTO_OP_LIST'];
        $pieces = explode(", ", $auto_op_list_c);

        $mask2 = $GLOBALS['USER'].'!'.$GLOBALS['USER_IDENT'].'@'.$GLOBALS['host'];

        if (in_array($mask2, $pieces)) {
            cliLog("[bot] I have user: '{$GLOBALS['USER']}' on the auto op list, giving op!");
            
            toServer("MODE ".getBotChannel()." +o {$GLOBALS['USER']}");

            PlaySound('prompt.mp3');
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function on_353() /* on channel join info */
{
    /* when we got confirmation that bot joined channel */
    if (isset($GLOBALS['rawDataArray'][2]) && $GLOBALS['rawDataArray'][2] == getBotNickname()) {
        /* 1. set channel from 353 */
        $GLOBALS['BOT_CHANNEL'] = $GLOBALS['rawDataArray'][4]; /* FIX: we can set channel name faster after JOIN event */
        
        cliLog("[bot] Joined channel: ".getBotChannel());
        
        /* FIX: save data for web panel */

        /* if bot got op */
        if (isset($GLOBALS['rawDataArray'][5]) && $GLOBALS['rawDataArray'][5] == ':@'.getBotNickname()) {
            /* on bot opped event */
            on_bot_opped();
        }
   
        /* check channel modes */
        toServer("MODE ".getBotChannel());
    }
}
//---------------------------------------------------------------------------------------------------------
function on_366() /* end of names list after joining channel */
{
}
//---------------------------------------------------------------------------------------------------------
function on_324() /* channel modes */
{
    if (isset($GLOBALS['rawDataArray'][4])) {
        unset($GLOBALS['CHANNEL_MODES']);

        $GLOBALS['CHANNEL_MODES'] = str_replace('+', '', $GLOBALS['rawDataArray'][4]);

        empty($GLOBALS['rawDataArray'][5]) ? $msg = $GLOBALS['CHANNEL_MODES'] : $msg = $GLOBALS['CHANNEL_MODES'].' '.$GLOBALS['rawDataArray'][5];

        if (!empty($GLOBALS['CHANNEL_MODES'])) {
            cliLog("[".getBotChannel()."] * channel modes: +{$msg}");
        } else {
                 cliLog("[".getBotChannel()."] * channel modes are not set");
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function on_part()
{
    /* if bot part channel */
    if ($GLOBALS['USER'] == getBotNickname()) {
        
        /* FIX: add info to panel */

        /* set to not opped */
        unset($GLOBALS['BOT_OPPED']);

        /* delete channel */
        unset($GLOBALS['BOT_CHANNEL']);

        /* delete channel modes */
        unset($GLOBALS['CHANNEL_MODES']);
    } else {
             /* if someone part channel */
             cliLog("[".getBotChannel()."] * {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) has leaved");
          
             /* Save to database for seen purpose */
             SeenSave();
    }
}
//---------------------------------------------------------------------------------------------------------
function on_471() /* if +limit on channel */
{
    cliLog('[bot] I cannot join, channel is full');
}
//---------------------------------------------------------------------------------------------------------
function on_473() /* if +invite on channel */
{
    cliLog('[bot] I cannot join, channel is invite only');
}
//---------------------------------------------------------------------------------------------------------
function on_474() /* if bot +banned on channel */
{
    cliLog('[bot] I cannot join, im banned on channel');
}
//---------------------------------------------------------------------------------------------------------
function on_331() /* RPL_NOTOPIC - "<channel> :No topic is set" */
{
}
//---------------------------------------------------------------------------------------------------------
function on_332() /* RPL_TOPIC - "<channel> :<topic>" */
{
    empty(inputFromLine('4')) ? $msg = 'channel topic is not set' : $msg = 'channel topic: "'.inputFromLine('4').'"';
    
    cliLog("[".$GLOBALS['rawDataArray'][3]."] * {$msg}");
}
//---------------------------------------------------------------------------------------------------------
function on_303() /* ison */
{
    if ($GLOBALS['rawDataArray'][3] == ':') {
        toServer("NICK {$GLOBALS['CONFIG_NICKNAME']}");
        /* 1.set nickname from config */
        setBotNickname($GLOBALS['CONFIG_NICKNAME']);

        unset($GLOBALS['I_USE_RND_NICKNAME']);

        cliLog('[bot] I recovered my original nickname');
    }
}
//---------------------------------------------------------------------------------------------------------
function on_quit()
{
    isset($GLOBALS['rawDataArray'][2]) ? $quit = inputFromLine(3) : $quit = '';
   
    cliLog("[".getBotChannel()."] * {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) Quit ({$quit})");

    /* Save to database for seen purpose */
    SeenSave();
}
//---------------------------------------------------------------------------------------------------------
function on_TOPIC()  /* topic change */
{
    cliLog('['.getBotChannel().'] * '.$GLOBALS['USER'].'('.$GLOBALS['USER_HOST'].') sets topic: "'.inputFromLine('3').'"');
}
//---------------------------------------------------------------------------------------------------------
function on_privmsg()
{
    /* if register <pwd> */
    if ($GLOBALS['rawDataArray'][2] == getBotNickname() && isset($GLOBALS['rawDataArray'][3]) && $GLOBALS['rawDataArray'][3] == ':register') {
    } elseif ($GLOBALS['rawDataArray'][2] == getBotChannel()) { /* if message in channel */
              cliLog("[".getBotChannel()."] <{$GLOBALS['USER']}> ".inputFromLine('3'));
    } elseif ($GLOBALS['rawDataArray'][2] == getBotNickname()) { /* if private message */
              cliLog("<{$GLOBALS['USER']}> ".inputFromLine('3'));
    }
}
//---------------------------------------------------------------------------------------------------------
function on_475() /* if +key on channel */
{
    if (!empty($GLOBALS['CONFIG_CHANNEL_KEY'])) {
        joinChannel("{$GLOBALS['CONFIG_CNANNEL']} {$GLOBALS['CONFIG_CHANNEL_KEY']}");
    } else {
             cliLog('[bot] I cannot join, bad channel key in config or key not set');

             PlaySound('prompt.mp3');
    }
}
//---------------------------------------------------------------------------------------------------------
function on_433() /* if nick already exists */
{
    on_432();
}
//---------------------------------------------------------------------------------------------------------
function on_432() /* if nick reserved */
{
    /* vars for keep nick functionality */
    ($GLOBALS['CONFIG_KEEP_NICK'] == 'yes') ? $GLOBALS['I_USE_RND_NICKNAME'] = '1' : false;
   
    /* add random to nick */
    $randomNick = $GLOBALS['CONFIG_NICKNAME'].'|'.rand(0, 999);

    /* set random nick */
    cliLog("[bot] Nickname already in use, changing nickname to: {$randomNick}");

    toServer("NICK {$randomNick}");
}
//---------------------------------------------------------------------------------------------------------
function on_422() /* join if no motd */
{
    on_376();
}
//---------------------------------------------------------------------------------------------------------
function on_nick()
{
    $a0 = str_replace(':', '', $GLOBALS['rawDataArray'][0]);
    $a2 = str_replace(':', '', $GLOBALS['rawDataArray'][2]);

    if ($GLOBALS['USER'] == $GLOBALS['CONFIG_NICKNAME'] && empty($GLOBALS['I_USE_RND_NICKNAME'])) {
        /* 1.set nickname */
        setBotNickname($a2);
        cliLog("[bot] My new nickname is: ".getBotNickname());
    } elseif (isset($GLOBALS['BOT_NICKNAME']) && $GLOBALS['USER'] == $GLOBALS['BOT_NICKNAME']) {
              setBotNickname($a2);
              cliLog("[bot] My new nickname is: ".getBotNickname());
    } elseif ($a0 == getBotNickname() && $a2 != getBotNickname())  {
              setBotNickname($a2);
              cliLog("[bot] My new nickname is: ".getBotNickname());
    } else {
              cliLog("[".getBotChannel()."] * ".$GLOBALS['USER']. " changed nick to {$a2}");
    }
}
//---------------------------------------------------------------------------------------------------------
function on_kick()
{
    cliLog("[".getBotChannel()."] * {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) kicked {$GLOBALS['rawDataArray'][3]}");
    
    /* if BOT kicked */
    if (isset($GLOBALS['rawDataArray'][3]) && $GLOBALS['rawDataArray'][3] == getBotNickname()) {
        /* set to not opped */
        unset($GLOBALS['BOT_OPPED']);

        /* delete channel modes */
        unset($GLOBALS['CHANNEL_MODES']);

        /* rejoin when kicked? */
        if ($GLOBALS['CONFIG_AUTO_REJOIN'] == 'yes') {
            cliLog("[bot] I was kicked from: ".getBotChannel()." by {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) - rejoining!");
            sleep(2);
            toServer("JOIN :{$GLOBALS['rawDataArray'][2]}");

            PlaySound('prompt.mp3');
        }
  
        /* delete channel */
        unset($GLOBALS['BOT_CHANNEL']);
    }

    /* Save to database for seen purpose */
    SeenSave();
}
//---------------------------------------------------------------------------------------------------------
function on_bot_opped()
{
    /* 1. set var that we have op */
    $GLOBALS['BOT_OPPED'] = 'yes';

    /* play sound */
    PlaySound('prompt.mp3');

    /* set bans and modes in channel */
    setChannelModesAndBans();
}
//---------------------------------------------------------------------------------------------------------
function setChannelModesAndBans()
{
    /* set bans from config */
    if (!empty($GLOBALS['CONFIG_BAN_LIST'])) {
        $ban_list = explode(', ', $GLOBALS['CONFIG_BAN_LIST']);
        foreach ($ban_list as $ban_address) {
            toServer("MODE ".getBotChannel()." +b {$ban_address}");
        }
    }

    /* set channel modes from config */
    if ($GLOBALS['CONFIG_KEEPCHAN_MODES'] == 'yes') {
        toServer('MODE '.getBotChannel());
    
        if (BotOpped() == true) {
            if (isset($GLOBALS['CHANNEL_MODES']) && $GLOBALS['CHANNEL_MODES'] != $GLOBALS['CONFIG_CHANNEL_MODES']) {
                sleep(1);
                toServer("MODE ".getBotChannel()." -{$GLOBALS['CHANNEL_MODES']}");
                sleep(1);
                toServer("MODE ".getBotChannel()." +{$GLOBALS['CONFIG_CHANNEL_MODES']}");
            }
            if (empty($GLOBALS['CHANNEL_MODES'])) {
                if (!empty($GLOBALS['CONFIG_CHANNEL_MODES'])) {
                    sleep(1);
                    toServer("MODE ".getBotChannel()." +{$GLOBALS['CONFIG_CHANNEL_MODES']}");
                }
            }
        }
    }
}
//---------------------------------------------------------------------------------------------------------