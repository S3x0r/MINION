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

function handleUserEvent($parsedData)
{
    if (function_exists('on_'.$parsedData)) {
        call_user_func('on_'.$parsedData);
    }
}
//---------------------------------------------------------------------------------------------------------
function on_NOTICE()
{
    if (rawDataArray()[2] == getBotNickname()) {
        user_notice();
    } else {
             cliServer('[notice] '.msgFromServer());
    }
}
//---------------------------------------------------------------------------------------------------------
function on_MODE()
{
    /* bot own modes */
    if (rawDataArray()[0] == ':'.getBotNickname() && rawDataArray()[2] == getBotNickname()) {
        bot_own_modes();
    }

    /* 1. set channel modes to variable */
    if (rawDataArray()[0] == getServerName() && isset(rawDataArray()[3]) && !empty(rawDataArray()[0])) {
        $GLOBALS['CHANNEL.MODES'] = str_replace('+', '', rawDataArray()[3]); //check this
    }
    
    /* user sets channel modes */
    if (rawDataArray()[0] != getServerName() && isset(rawDataArray()[3]) && !empty(rawDataArray()[3]) && rawDataArray()[2] != getBotNickname()) {
        user_modes();
    }

    if (isset(rawDataArray()[4]) && rawDataArray()[4] == getBotNickname()) {
        if (isset(rawDataArray()[3])) {
            if (rawDataArray()[3] == '+o') { /* if bot opped */
                on_bot_opped();
            } elseif (rawDataArray()[3] == '-o') { /* if bot deoped */
                      on_bot_deoped();
            }
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function on_JOIN()
{
    (userNickname() == getBotNickname()) ? bot_joined_channel() : user_joined_channel();
}
//---------------------------------------------------------------------------------------------------------
function on_PART()
{
    (userNickname() == getBotNickname()) ? bot_leaved_channel() : user_leaved_channel();
}
//---------------------------------------------------------------------------------------------------------
function on_QUIT()
{
    user_quit();
}
//---------------------------------------------------------------------------------------------------------
function on_TOPIC()  /* topic change */
{
    user_changed_topic();
}
//---------------------------------------------------------------------------------------------------------
function on_PRIVMSG()
{
    /* if register <pwd> */
    if (rawDataArray()[2] == getBotNickname() && isset(rawDataArray()[3]) && rawDataArray()[3] == ':register') {
    } elseif (rawDataArray()[2] == getBotChannel()) { /* if message in channel */
              user_message_channel();
    } elseif (rawDataArray()[2] == getBotNickname()) { /* if private message */
              if (isset(rawDataArray()[3][1]) && rawDataArray()[3][1] != '') { /* if not ctcp */
                  user_message_private();
              }
    }
}
//---------------------------------------------------------------------------------------------------------
function on_NICK() /* not checked */
{
    cliDebug('on_NICK()');

    $a0 = str_replace(':', '', rawDataArray()[0]);
    $a2 = str_replace(':', '', rawDataArray()[2]);

    if (userNickname() == loadValueFromConfigFile('BOT', 'nickname') && empty($GLOBALS['I_USE_RND_NICKNAME'])) {
        /* 1.set nickname */
        setBotNickname($a2);
        cliBot('My new nickname is: '.getBotNickname());
    } elseif (isset($GLOBALS['BOT_NICKNAME']) && userNickname() == $GLOBALS['BOT_NICKNAME']) {
              setBotNickname($a2);
              cliBot('My new nickname is: '.getBotNickname());
    } elseif ($a0 == getBotNickname() && $a2 != getBotNickname())  {
              setBotNickname($a2);
              cliBot('My new nickname is: '.getBotNickname());
    } else {
             user_changed_nick();
    }
}
//---------------------------------------------------------------------------------------------------------
function on_KICK()
{
    (isset(rawDataArray()[3]) && rawDataArray()[3] == getBotNickname()) ? bot_kicked_from_channel() : user_kicked_from_channel();
}
//---------------------------------------------------------------------------------------------------------
function on_INVITE()
{
    on_bot_invited_to_channel();
}
//---------------------------------------------------------------------------------------------------------
function on_KILL()
{
    cliBot('Bot killed from server ('.inputFromLine('3').') Reconnecting!'); 
}