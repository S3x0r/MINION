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

function on_NOTICE()
{
    debug("on_NOTICE()");
    
    cliLog('[server] (NOTICE) '.msgFromServer());
}
//---------------------------------------------------------------------------------------------------------
function on_PING()
{
    debug("on_PING()");

    toServer("PONG ".rawDataArray()[1]);
}
//---------------------------------------------------------------------------------------------------------
function on_MODE() /* on MODE event */
{
    debug("on_mode()");

    /* 1. set bot modes */
    if (rawDataArray()[0] == ':'.getBotNickname() && rawDataArray()[2] == getBotNickname()) {
        setBotModes(str_replace(':+', '', rawDataArray()[3]));
    }

    /* 1. set channel modes */
    if (rawDataArray()[0] == getServerName() && isset(rawDataArray()[3]) && !empty(rawDataArray()[0])) {
        $GLOBALS['CHANNEL.MODES'] = str_replace('+', '', rawDataArray()[3]);
    }

    if (rawDataArray()[0] != getServerName() && isset(rawDataArray()[3]) && !empty(rawDataArray()[3]) && rawDataArray()[2] != getBotNickname()) {
        isset(rawDataArray()[4]) ? $add = rawDataArray()[4] : $add = '';
        cliLog("[".getBotChannel()."] * ".userPreg()[0]." (".userPreg()[3].") sets mode: ".rawDataArray()[3]." {$add}");
    }

    /* if bot opped */
    if (isset(rawDataArray()[4]) && rawDataArray()[4] == getBotNickname()) {
        if (isset(rawDataArray()[3]) && rawDataArray()[3] == '+o') {
            /* send info */
            cliLog("[bot] I have OP now on: ".getBotChannel().", from: ".userPreg()[0]." (".userPreg()[3].")");
            
            /* on bot opped event */
            on_bot_opped();
    
        /* if bot deoped */
        } elseif (isset(rawDataArray()[4]) && rawDataArray()[4] == getBotNickname()) {
            if (isset(rawDataArray()[3]) && rawDataArray()[3] == '-o') {
                /* send info */
                cliLog("[bot] User: ".userPreg()[0]." (".userPreg()[3].") DEOPED ME on channel: ".getBotChannel());
                
                /* unset bot opped */
                unset($GLOBALS['BOT_OPPED']);

                /* play sound */
                PlaySound('prompt.mp3');
            }
        }
    }

    isset(rawDataArray()[4]) ? $rest = rawDataArray()[4] : $rest = '';
}
//---------------------------------------------------------------------------------------------------------
function on_JOIN()
{
    debug("on_join()");

    /* if bot joined */
    if (userPreg()[0] == getBotNickname()) {
        /* 1. set channel from 353 */
        setBotChannel(str_replace(':', '', rawDataArray()[2]));
        
        cliLog("[bot] Joined channel: ".getBotChannel());

        /* 1.check channel modes for cli message */
        toServer("MODE ".getBotChannel());
    }

    /* if user joined channel */
    if (userPreg()[0] != getBotNickname()) {
        cliLog("[".getBotChannel()."] * ".userPreg()[0]." (".userPreg()[3].") has joined");

        /* Save Seen */
        SeenSave();
    }

    /* auto op */
    if (loadValueFromConfigFile('AUTOMATIC', 'auto.op') == 'yes' && BotOpped() == true) {
        $auto_op_list_c = loadValueFromConfigFile('AUTOMATIC', 'auto.op.list');
        $pieces = explode(", ", $auto_op_list_c);

        $mask2 = userPreg()[0].'!'.userPreg()[1].'@'.userPreg()[2];

        if (in_array($mask2, $pieces)) {
            cliLog("[bot] I have user: '".userPreg()[0]."' on the auto op list, giving op!");
            
            toServer("MODE ".getBotChannel()." +o ".userPreg()[0]);

            PlaySound('prompt.mp3');
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function on_PART()
{
    /* if bot part channel */
    if (userPreg()[0] == getBotNickname()) {
        
        /* set to not opped */
        unset($GLOBALS['BOT_OPPED']);

        /* delete channel */
        unset($GLOBALS['BOT_CHANNEL']);

        /* delete channel modes */
        unset($GLOBALS['CHANNEL.MODES']);
    } else {
             /* if someone part channel */
             cliLog("[".getBotChannel()."] * ".userPreg()[0]." (".userPreg()[3].") has leaved");
          
             /* Save to database for seen purpose */
             SeenSave();
    }
}
//---------------------------------------------------------------------------------------------------------
function on_QUIT()
{
    isset(rawDataArray()[2]) ? $quit = inputFromLine(3) : $quit = '';
   
    cliLog("[".getBotChannel()."] * ".userPreg()[0]." (".userPreg()[3].") Quit ({$quit})");

    /* Save to database for seen purpose */
    SeenSave();
}
//---------------------------------------------------------------------------------------------------------
function on_TOPIC()  /* topic change */
{
    cliLog('['.getBotChannel().'] * '.userPreg()[0].' ('.userPreg()[3].') sets topic: "'.inputFromLine('3').'"');
}
//---------------------------------------------------------------------------------------------------------
function on_PRIVMSG()
{
    /* if register <pwd> */
    if (rawDataArray()[2] == getBotNickname() && isset(rawDataArray()[3]) && rawDataArray()[3] == ':register') {
    } elseif (rawDataArray()[2] == getBotChannel()) { /* if message in channel */
              cliLog("[".getBotChannel()."] <".userPreg()[0]."> ".inputFromLine('3'));
    } elseif (rawDataArray()[2] == getBotNickname()) { /* if private message */
              cliLog("<".userPreg()[0]."> ".inputFromLine('3'));
    }
}
//---------------------------------------------------------------------------------------------------------
function on_NICK()
{
    debug("on_nick()");

    $a0 = str_replace(':', '', rawDataArray()[0]);
    $a2 = str_replace(':', '', rawDataArray()[2]);

    if (userPreg()[0] == loadValueFromConfigFile('BOT', 'nickname') && empty($GLOBALS['I_USE_RND_NICKNAME'])) {
        /* 1.set nickname */
        setBotNickname($a2);
        cliLog("[bot] My new nickname is: ".getBotNickname());
    } elseif (isset($GLOBALS['BOT_NICKNAME']) && userPreg()[0] == $GLOBALS['BOT_NICKNAME']) {
              setBotNickname($a2);
              cliLog("[bot] My new nickname is: ".getBotNickname());
    } elseif ($a0 == getBotNickname() && $a2 != getBotNickname())  {
              setBotNickname($a2);
              cliLog("[bot] My new nickname is: ".getBotNickname());
    } else {
              cliLog("[".getBotChannel()."] * ".userPreg()[0]. " changed nick to {$a2}");
    }
}
//---------------------------------------------------------------------------------------------------------
function on_KICK()
{
    cliLog("[".getBotChannel()."] * ".userPreg()[0]." (".userPreg()[3].") kicked ".rawDataArray()[3]);
    
    /* if BOT kicked */
    if (isset(rawDataArray()[3]) && rawDataArray()[3] == getBotNickname()) {
        /* set to not opped */
        unset($GLOBALS['BOT_OPPED']);

        /* delete channel modes */
        unset($GLOBALS['CHANNEL.MODES']);

        /* rejoin when kicked? */
        if (loadValueFromConfigFile('AUTOMATIC', 'auto.rejoin') == 'yes') {
            cliLog("[bot] I was kicked from: ".getBotChannel()." by ".userPreg()[0]." (".userPreg()[3].") - rejoining!");
            sleep(2);
            toServer("JOIN :".rawDataArray()[2]);

            PlaySound('prompt.mp3');
        }
  
        /* delete channel */
        unset($GLOBALS['BOT_CHANNEL']);
    }

    /* Save to database for seen purpose */
    SeenSave();
}
