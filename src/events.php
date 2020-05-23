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

function on_server_ping()
{
    fputs($GLOBALS['socket'], "PONG {$GLOBALS['ex'][1]}\n");
}
//---------------------------------------------------------------------------------------------------------
function on_first_start() /* event after end motd */
{
    /* check if panel is not closed */
    if (isRunned('serv')) {
        if (kill('serv')) {
            CLI_MSG('[BOT] Detected Panel still running, Terminating.', '1');
        }
    }

    /* send anonymous bot usage statistics */
    Statistics();
}
//---------------------------------------------------------------------------------------------------------
function on_bot_join_channel()
{
	CLI_MSG("Bot joined channel: {$GLOBALS['channel']}", '1');
}
//---------------------------------------------------------------------------------------------------------
function on_join()
{
    /* if bot join */
    if ($GLOBALS['USER'] == $GLOBALS['BOT_NICKNAME']) {
        /* add channel to array */
        array_push($GLOBALS['BOT_CHANNELS'], $GLOBALS['channel']);
        
        /* save data for web panel */
        $data = implode(' ', $GLOBALS['BOT_CHANNELS']);
        WebSave('WEB_BOT_CHANNELS', $data);

        /* on bot join event */
        on_bot_join_channel();
    } else {
             /* if user joined channel */
             CLI_MSG("* {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) has joined {$GLOBALS['channel']}", '1');

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
            CLI_MSG("[BOT] I have nick: {$GLOBALS['USER']} on auto op list, giving op", '1');
            fputs($GLOBALS['socket'], "MODE {$GLOBALS['channel']} +o {$GLOBALS['USER']}\n");
            PlaySound('prompt.mp3');
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function on_part()
{
    /* if bot part channel */
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
    } else {
             /* if someone part channel */
             CLI_MSG("* {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) has leaved {$GLOBALS['channel']}", '1');
          
             /* Save to database for seen purpose */
             SeenSave();
    }
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

        /* rejoin when kicked? */
        if ($GLOBALS['CONFIG_AUTO_REJOIN'] == 'yes') {
            CLI_MSG('I was kicked from channel, rejoining..', '1');
            sleep(2);
            fputs($GLOBALS['socket'], "JOIN :{$GLOBALS['ex'][2]}\n");
            PlaySound('prompt.mp3');
        }
    }
    /* else */
    CLI_MSG("* {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) kicked {$GLOBALS['ex'][3]} from {$GLOBALS['channel']}", '1');

    /* Save to database for seen purpose */
    SeenSave();
}
//---------------------------------------------------------------------------------------------------------
function on_topic()
{
    /* topic change */
    CLI_MSG("* {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) ({$GLOBALS['channel']}) sets topic: ".parse_ex3('3'), '1');
}
//---------------------------------------------------------------------------------------------------------
function on_privmsg()
{
    if ($GLOBALS['ex'][2] == $GLOBALS['BOT_NICKNAME'] && isset($GLOBALS['ex'][3]) && $GLOBALS['ex'][3] == ':register') {
    } else {
             CLI_MSG("[{$GLOBALS['channel']}] <{$GLOBALS['USER']}> ".parse_ex3('3'), '1');
    }
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
                CLI_MSG("[BOT] I have OP now on: {$GLOBALS['channel']}, from: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']})", '1');
                
                /* set to opped var */
                $GLOBALS['BOT_OPPED'] = 'yes';

                /* sound */
                PlaySound('prompt.mp3');

                /* and set it */
                fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].N);
                set_channel_modes();

                /* set ban list */
                set_bans();
            }
        }
        /* if bot deoped */
        if (isset($GLOBALS['ex'][4]) && $GLOBALS['ex'][4] == $GLOBALS['BOT_NICKNAME']) {
            if (isset($GLOBALS['ex'][3]) && $GLOBALS['ex'][3] == '-o') {
                /* info */
                CLI_MSG("[BOT] User: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) DEOPED ME on channel: {$GLOBALS['channel']}", '1');
                
                /* bot not opped anymore */
                unset($GLOBALS['BOT_OPPED']);

                /* sound */
                PlaySound('prompt.mp3');
            }
        }

        isset($GLOBALS['ex'][4]) ? $rest = $GLOBALS['ex'][4] : $rest = '';

        /* show message about modes */
        CLI_MSG("* {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) sets mode: {$GLOBALS['ex'][3]} {$rest}", '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function on_nick()
{
    if ($GLOBALS['USER'] == $GLOBALS['CONFIG_NICKNAME'] && empty($GLOBALS['I_USE_RND_NICKNAME'])) {
        $GLOBALS['BOT_NICKNAME'] = str_replace(':', '', $GLOBALS['ex'][2]);
        CLI_MSG("[BOT] My new nickname is: {$GLOBALS['BOT_NICKNAME']}", '1');
    } elseif (isset($GLOBALS['BOT_NICKNAME']) && $GLOBALS['USER'] == $GLOBALS['BOT_NICKNAME']) {
              $GLOBALS['BOT_NICKNAME'] = str_replace(':', '', $GLOBALS['ex'][2]);
              CLI_MSG("[BOT] My new nickname is: {$GLOBALS['BOT_NICKNAME']}", '1');
    } else {
              $new = str_replace(':', '', $GLOBALS['ex'][2]);
              CLI_MSG(' * '.$GLOBALS['USER']. ' changed nick to '.$new, '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function on_quit()
{
    isset($GLOBALS['ex'][2]) ? $quit = trim(parse_ex3(3)) : $quit = '';
   
    CLI_MSG("* {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) Quit ({$quit})", '1');

    /* Save to database for seen purpose */
    SeenSave();
}
//---------------------------------------------------------------------------------------------------------
function on_001() /* server message */
{
    CLI_MSG("S> {$GLOBALS['srv_msg']}", '1');
}
//---------------------------------------------------------------------------------------------------------
function on_002() /* host, version server */
{
    CLI_MSG("S> {$GLOBALS['srv_msg']}", '1');
}
//---------------------------------------------------------------------------------------------------------
function on_003() /* server creation time */
{
    CLI_MSG("S> {$GLOBALS['srv_msg']}", '1');
}
//---------------------------------------------------------------------------------------------------------
function on_303() /* ison */
{
    if ($GLOBALS['ex'][3] == ':') {
        fputs($GLOBALS['socket'], "NICK {$GLOBALS['NICKNAME_FROM_CONFIG']}\n");
        $GLOBALS['BOT_NICKNAME'] = $GLOBALS['NICKNAME_FROM_CONFIG'];

        unset($GLOBALS['I_USE_RND_NICKNAME']);

        CLI_MSG('[BOT]: I recovered my original nickname', '1');
    }
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
    CLI_MSG('Topic on: '.parse_ex3('3'), '1');
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
    CLI_MSG("Connected, my nickname is: {$GLOBALS['BOT_NICKNAME']}", '1');

    /* register to bot info */
    if (isset($GLOBALS['defaultPwdChanged'])) {
        cli(N.'*********************************************************'.N, '0');
        cli("Register to bot by typing /msg {$GLOBALS['BOT_NICKNAME']} register <password>".N, '0');
        cli('*********************************************************'.N.N, '0');
        unset($GLOBALS['defaultPwdChanged']);
    }

    /* if autojoin */
    if ($GLOBALS['CONFIG_AUTO_JOIN'] == 'yes') {
        CLI_MSG("Trying to join channel: {$GLOBALS['CONFIG_CNANNEL']}", '1');
        joinChannel($GLOBALS['CONFIG_CNANNEL']);
    }

    /* on first start event */
    on_first_start();

    /* play sound :) */
    PlaySound('connected.mp3');
}
//---------------------------------------------------------------------------------------------------------
function on_422() /* join if no motd */
{
    on_376();
}
//---------------------------------------------------------------------------------------------------------
function on_432() /* if nick reserved */
{
    /* vars for keep nick functionality */
    if ($GLOBALS['CONFIG_KEEP_NICK'] == 'yes') {
        $GLOBALS['NICKNAME_FROM_CONFIG'] = $GLOBALS['CONFIG_NICKNAME'];
        $GLOBALS['I_USE_RND_NICKNAME'] = '1';
    }
   
    /* set random nick */
    $GLOBALS['BOT_NICKNAME'] = $GLOBALS['BOT_NICKNAME'].'|'.rand(0, 299);
    CLI_MSG("Nickname from config is already in use on server, changing nickname to: {$GLOBALS['BOT_NICKNAME']}", '1');

    fputs($GLOBALS['socket'], "NICK {$GLOBALS['BOT_NICKNAME']}".N);
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
        joinChannel("{$GLOBALS['CONFIG_CNANNEL']} {$GLOBALS['CONFIG_CHANNEL_KEY']}");
    } else {
              CLI_MSG('[BOT] I cannot join, bad channel key in config or key not set', '1');
              PlaySound('prompt.mp3');
    }
}
