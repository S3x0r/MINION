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

function bot_joined_channel()
{
    /* 1. set channel from 353 */
    setBotChannel(str_replace(':', '', rawDataArray()[2]));
    
    cliBot('Joined channel '.getBotChannel());

    /* 1.check channel modes for cli message */
    toServer('MODE '.getBotChannel());
}
//---------------------------------------------------------------------------------------------------------
function bot_leaved_channel()
{
    cliBot('Leaved channel '.getBotChannel());

    SeenSave();

    unset_channel_variables();   
}
//---------------------------------------------------------------------------------------------------------
function bot_kicked_from_channel()
{
    cliBot('Kicked from: '.getBotChannel().' by '.print_userNick_IdentHost().' Reason: "'.inputFromLine('4').'"');
    
    /* Save to database for seen purpose */
    SeenSave();
 
    unset_channel_variables();

    playSound('prompt.mp3');

    /* rejoin when kicked? */
    if (loadValueFromConfigFile('AUTOMATIC', 'auto rejoin') == true) {
        sleep(2);
        cliBot('Rejoining channel '.getBotChannel());
        
        joinChannel(rawDataArray()[2]);
    }
}
//---------------------------------------------------------------------------------------------------------
function unset_channel_variables()
{
    /* set to not opped */
    unset($GLOBALS['BOT_OPPED']);

    /* delete channel */
    unset($GLOBALS['BOT_CHANNEL']);

    /* delete channel modes */
    unset($GLOBALS['CHANNEL.MODES']);   
}
//---------------------------------------------------------------------------------------------------------
function bot_own_modes()
{
    cliBot('Bot modes '.inputFromLine(3));
}
//---------------------------------------------------------------------------------------------------------
function on_bot_opped()
{
    /* send info */
    if (print_userNick_IdentHost() == ' ()') {
        $from = 'server (first on channel)';
    } else {
             $from = print_userNick_IdentHost();
    }
    
    cliBot('Got @ in: '.getBotChannel().', from: '.$from);

    /* set var that we have op */
    $GLOBALS['BOT_OPPED'] = 'yes';

    /* set bans and modes in channel */
    bot_setChannelModesAndBans();
    
    /* set topic if present in config */
    if (!empty(loadValueFromConfigFile('CHANNEL', 'channel topic'))) {
        setTopic(getBotChannel(), loadValueFromConfigFile('CHANNEL', 'channel topic'));
    }

    /* play sound */
    playSound('prompt.mp3');
}
//---------------------------------------------------------------------------------------------------------
function on_bot_deoped()
{
    /* send info */
    cliBot('User: '.print_userNick_IdentHost().' deoped bot on channel: '.getBotChannel());
    
    /* unset bot opped */
    unset($GLOBALS['BOT_OPPED']);

    /* play sound */
    playSound('prompt.mp3');    
}
//---------------------------------------------------------------------------------------------------------
function bot_op_user($userNickname)
{
    if (BotOpped() == true) {
        toServer('MODE '.getBotChannel().' +o '.$userNickname);
    }
}
//---------------------------------------------------------------------------------------------------------
function bot_setChannelModesAndBans()
{
    /* set bans from config */
    if (!empty(loadValueFromConfigFile('BANS', 'ban list')[0])) {
        $banList = loadValueFromConfigFile('BANS', 'ban list');
        
        foreach ($banList as $ban_address) {
            if (!empty($ban_address)) {
                toServer('MODE '.getBotChannel().' +b '.$ban_address);
            }
        }
    }

    /* set channel modes from config */
    if (loadValueFromConfigFile('AUTOMATIC', 'keep channel modes') == true && BotOpped() == true) { //FIX: keep modes
        if (isset($GLOBALS['CHANNEL.MODES']) && $GLOBALS['CHANNEL.MODES'] != loadValueFromConfigFile('CHANNEL', 'channel modes')) {
            sleep(1);
            toServer('MODE '.getBotChannel().' +'.loadValueFromConfigFile('CHANNEL', 'channel modes'));
        }

        if (!isset($GLOBALS['CHANNEL.MODES'])) {
            sleep(1);
            toServer('MODE '.getBotChannel().' +'.loadValueFromConfigFile('CHANNEL', 'channel modes'));
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function bot_newUserRegisteredAsOwner()
{
    /* cli msg */
    cliBot('Successful registration as owner from: '.print_userNick_NickIdentHost());
    cliBot('New Owner added: '.print_userNick_NickIdentHost());
    cliBot('New Auto Op added: '.print_userNick_NickIdentHost());

    user_registered_as_owner();

    /* give op */
    bot_op_user(userNickname());    
}
//---------------------------------------------------------------------------------------------------------
function setTopic($channel, $topic)
{
    toServer("TOPIC {$channel} :{$topic}");
}
//---------------------------------------------------------------------------------------------------------
function on_bot_invited_to_channel()
{
    if (!isIgnoredUser()) {
        if (loadValueFromConfigFile('MESSAGE', 'show users invite messages') == true) {
            cliBot(print_userNick_IdentHost().' invites me to channel: '.inputFromLine('3'));
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function on_bot_auto_join()
{
    /* if autojoin */
    if (loadValueFromConfigFile('CHANNEL', 'auto join') == true) {
        joinChannel(loadValueFromConfigFile('CHANNEL', 'channel'));
    } else {
             cliBot('No auto join channel in '.getConfigFileName().', idling...');    
    }       
}
//---------------------------------------------------------------------------------------------------------
function bot_set_own_modes()
{
    if (!empty(loadValueFromConfigFile('BOT', 'bot modes'))) {
        toServer('MODE '.getBotNickname().' '.loadValueFromConfigFile('BOT', 'bot modes'));
    }
}
//---------------------------------------------------------------------------------------------------------
function bot_user_commands()
{
    if (!empty(loadValueFromConfigFile('COMMANDS', 'raw commands on start')[0])) {
        $commands = loadValueFromConfigFile('COMMANDS', 'raw commands on start');

        foreach ($commands as $command) {
           if (!empty($command)) {
               cliBot('Sending raw command from config: "'.$command.'"');
               toServer($command);
           }
        }
    }
}
