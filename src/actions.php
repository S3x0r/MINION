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

function isBotOpped()
{
    if (isset($GLOBALS['BOT_OPPED'])) {
        return true;
    } else { 
             return false;
    }
}
//---------------------------------------------------------------------------------------------------------
function kickUser($_channel, $_nickname, $_reason)
{
    if (isBotOpped()) {
        toServer('KICK '.$_channel.' '.$_nickname.' :'.$_reason);
        
        return true;
    }
}
//---------------------------------------------------------------------------------------------------------
function banUser($_channel, $_hostmask)
{
    if (isBotOpped()) {
        toServer('MODE '.$_channel.' +b '.$_hostmask);
        
        return true;
    }
}
//---------------------------------------------------------------------------------------------------------
function addUserToIgnoreList($_hostmask)
{
    saveValueToListConfigFile('IGNORE', 'users', $_hostmask);
}
//---------------------------------------------------------------------------------------------------------
function opUser($_user)
{
    if (isBotOpped()) {
        toServer('MODE '.getBotChannel().' +o '.$_user);
    }
}
//---------------------------------------------------------------------------------------------------------
function deopUser($_user)
{
    if (isBotOpped()) {
        if (commandFromUser() != getBotNickname() && commandFromUser() != userNickname()) {
            toServer('MODE '.getBotChannel().' -o '.$_user);
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function giveVoice($_user)
{
    if (isBotOpped()) {
        toServer('MODE '.getBotChannel().' +v '.$_user);
    }
}
//---------------------------------------------------------------------------------------------------------
function takeVoice($_user)
{
    if (isBotOpped()) {
        if (commandFromUser() != getBotNickname() && commandFromUser() != userNickname()) {
            toServer('MODE '.getBotChannel().' -v '.$_user);
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function unban($_mask)
{
    if (isBotOpped()) {
        toServer('MODE '.getBotChannel().' -b '.$_mask);
    }
}
//---------------------------------------------------------------------------------------------------------
function quitFromServer($_msg)
{
    toServer('QUIT :'.$_msg);   
}
//---------------------------------------------------------------------------------------------------------
function joinChannel($_channel)
{
   if (!empty(loadValueFromConfigFile('CHANNEL', 'channel key'))) {
       toServer('JOIN '.$_channel.' '.loadValueFromConfigFile('CHANNEL', 'channel key'));
   } else {
            toServer('JOIN '.$_channel);
   }
}
//---------------------------------------------------------------------------------------------------------
function leaveChannel($_channel)
{
    toServer('PART '.$_channel);    
}
//---------------------------------------------------------------------------------------------------------
function channelMode($_channel)
{
    toServer('MODE '.$_channel);
}
//---------------------------------------------------------------------------------------------------------
function setChannelMode($_channel, $_mode)
{
    toServer('MODE '.$_channel.' '.$_mode);    
}
//---------------------------------------------------------------------------------------------------------
function ison($_user)
{
    toServer('ISON :'.$_user);
}
//---------------------------------------------------------------------------------------------------------
function changeTopic($_channel, $_topic)
{
    toServer('TOPIC '.$_channel.' :'.$_topic);
}
//---------------------------------------------------------------------------------------------------------
function channelTopic($_channel)
{
    toServer('TOPIC '.$_channel);    
}
//---------------------------------------------------------------------------------------------------------
function setBotMode($_mode)
{
    toServer('MODE '.getBotNickname().' '.$_mode);    
}
//---------------------------------------------------------------------------------------------------------
function sendRaw($_data)
{
    toServer($_data);    
}
//---------------------------------------------------------------------------------------------------------
function sendPRIVMSG($_user, $_message)
{
    toServer('PRIVMSG '.$_user.' :'.$_message);

    usleep(loadValueFromConfigFile('DELAYS', 'private delay') * 1000000);
}
//---------------------------------------------------------------------------------------------------------
function sayInChannel($_channel, $_msg)
{
    toServer('PRIVMSG '.$_channel.' :'.$_msg);

    usleep(loadValueFromConfigFile('DELAYS', 'channel delay') * 1000000);
}
//---------------------------------------------------------------------------------------------------------
function sendNotice($_user, $_data)
{
    toServer('NOTICE '.$_user.' :'.$_data);

    usleep(loadValueFromConfigFile('DELAYS', 'notice delay') * 1000000);    
}
//---------------------------------------------------------------------------------------------------------
function sendCTCP($_user, $_data)
{
    toServer('NOTICE '.$_user." :".$_data);    
}
//---------------------------------------------------------------------------------------------------------
function changeNick($_nickname)
{
    toServer('NICK '.$_nickname);    
}
//---------------------------------------------------------------------------------------------------------
function setBotNickname($_nickname)
{
    global $BOT_NICKNAME;

    $BOT_NICKNAME = $_nickname;
}
//---------------------------------------------------------------------------------------------------------
function setBotChannel($_channel)
{
    $GLOBALS['BOT_CHANNEL'] = $_channel;
}
//---------------------------------------------------------------------------------------------------------
function getBotNickname()
{
    global $BOT_NICKNAME;

    if (isset($BOT_NICKNAME) && !empty($BOT_NICKNAME)) {
        return $BOT_NICKNAME;
    }
}
//---------------------------------------------------------------------------------------------------------
function getBotChannel()
{
    if (isset($GLOBALS['BOT_CHANNEL']) && !empty($GLOBALS['BOT_CHANNEL'])) {
        return $GLOBALS['BOT_CHANNEL'];
    }
}
//---------------------------------------------------------------------------------------------------------
function getServerName()
{
    global $serverName;

    if (isset($serverName) && !empty($serverName)) {
        return $serverName;
    }
}
//---------------------------------------------------------------------------------------------------------
function setServerName($_name)
{
    global $serverName;

    $serverName = $_name;
}
//---------------------------------------------------------------------------------------------------------
function sendPONG()
{
    toServer('PONG '.dataArray()[1]);    
}
//---------------------------------------------------------------------------------------------------------
function toServer($_data)
{
    global $socket;

    /* send own message to cli if raw mode */
    cliRaw($_data, 1);

    if (@fputs($socket, "$_data\n")) { }
}
