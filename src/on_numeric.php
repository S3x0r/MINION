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

function on_001() /* server message */
{
    /* 1.set server name */
    setServerName(rawDataArray()[0]);

    /* 1.set bot nickname */
    setBotNickname(rawDataArray()[2]);

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
}
//---------------------------------------------------------------------------------------------------------
function on_375() /* motd start */
{
    if (loadValueFromConfigFile('SERVER', 'show.motd') == 'yes') {
        cliLog('[server] '.msgFromServer());
    }
}
//---------------------------------------------------------------------------------------------------------
function on_372() /* motd message */
{
    if (loadValueFromConfigFile('SERVER', 'show.motd') == 'yes') {
        cliLog('[server] '.msgFromServer());
    }
}
//---------------------------------------------------------------------------------------------------------
function on_376() /* motd end */
{
    if (loadValueFromConfigFile('SERVER', 'show.motd') == 'yes') {
        cliLog('[server] '.msgFromServer());
    }
    
    /* show info that we are connected */
    cli('');
    cliLog("[bot] Connected! My nickname is: ".getBotNickname());

    /* register to bot info */
    if (isset($GLOBALS['defaultPwdChanged'])) {
        cli(N.'*********************************************************');
        cli("Register to bot by typing /msg ".getBotNickname()." register <password>");
        cli('*********************************************************'.N);
        unset($GLOBALS['defaultPwdChanged']);
    }

    /* if autojoin */
    if (loadValueFromConfigFile('CHANNEL', 'auto.join') == 'yes') {
        cliLog("[bot] Trying to join channel: ".loadValueFromConfigFile('CHANNEL', 'channel'));

        joinChannel(loadValueFromConfigFile('CHANNEL', 'channel'));
    } else {
             cliLog('[bot] No auto join mode in '.getConfigFileName().', idling...');
    }

    /* send anon stats */
    // Statistics();

    /* play sound :) */
    PlaySound('connected.mp3');
}
//---------------------------------------------------------------------------------------------------------
function on_396()
{
    cliLog('[server] '.msgFromServer());
}
//---------------------------------------------------------------------------------------------------------
function on_353() /* on channel join info */
{
    debug("on_353()");

    if (!isset($GLOBALS['353_Start'])) {
        $GLOBALS['channelUsersOP']     = null;
        $GLOBALS['channelUsersHalfOp'] = null;
        $GLOBALS['channelUsersVoice']  = null;
        $GLOBALS['channelUsersOthers'] = null;
        $GLOBALS['channelUsersCount']  = 0;
        
        $GLOBALS['353_Start'] = true;
    }

    $usersData = explode(" ", inputFromLine(5));

    foreach ($usersData as $user) {
        if ($user[0] == '@') {
            $GLOBALS['channelUsersOP'] .= $user.' ';
        } else if ($user[0] == '%') {
                   $GLOBALS['channelUsersHalfOp'] .= $user.' ';
        } else if ($user[0] == '+') {
                   $GLOBALS['channelUsersVoice'] .= $user.' ';
        } else {
                 $GLOBALS['channelUsersOthers'] .= $user.' ';
        }

        $GLOBALS['channelUsersCount']++;
    }
    
    $nick = str_replace(':', '', rawDataArray()[5]);

    /* check if bot is first in channel and if got OP */
    if ($nick == '@'.getBotNickname()) {
        /* do some actions when bot is oped */
        on_bot_opped();
    }
}
//---------------------------------------------------------------------------------------------------------
function on_366() /* end of channel user(s) nicks list- after joining channel */
{
    cliLog('[bot] Total Channel Users: '.$GLOBALS['channelUsersCount']);
    
    // @
    if (!empty($GLOBALS['channelUsersOP'])) {
        cliLog('[bot] (@) Op(s): '.$GLOBALS['channelUsersOP']);
    }

    // %
    if (!empty($GLOBALS['channelUsersHalfOp'])) {
        cliLog('[bot] (%) HalfOp(s): '.$GLOBALS['channelUsersHalfOp']);
    }
    
    // +
    if (!empty($GLOBALS['channelUsersVoice'])) {
        cliLog('[bot] (+) Voice(s): '.$GLOBALS['channelUsersVoice']);
    }

    // others
    if (!empty($GLOBALS['channelUsersOthers'])) {
        cliLog('[bot] Other(s): '.$GLOBALS['channelUsersOthers']);
    }

    unset($GLOBALS['353_Start']);
    unset($GLOBALS['channelUsersOP']);
    unset($GLOBALS['channelUsersHalfOp']);
    unset($GLOBALS['channelUsersVoice']);
    unset($GLOBALS['channelUsersOthers']);
    unset($GLOBALS['channelUsersCount']);
}
//---------------------------------------------------------------------------------------------------------
function on_324() /* channel modes */
{
    debug("on_324()");

    if (isset(rawDataArray()[4])) {
        unset($GLOBALS['CHANNEL.MODES']);

        $GLOBALS['CHANNEL.MODES'] = str_replace('+', '', rawDataArray()[4]);

        empty(rawDataArray()[5]) ? $msg = $GLOBALS['CHANNEL.MODES'] : $msg = $GLOBALS['CHANNEL.MODES'].' '.rawDataArray()[5];

        if (!empty($GLOBALS['CHANNEL.MODES'])) {
            cliLog("[".getBotChannel()."] * channel modes: +{$msg}");
        } else {
                 cliLog("[".getBotChannel()."] * channel modes are not set");
        }
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
    debug("on_332()");

    empty(inputFromLine('4')) ? $msg = 'channel topic is not set' : $msg = 'channel topic: "'.inputFromLine('4').'"';
    
    cliLog("[".rawDataArray()[3]."] * {$msg}");
}
//---------------------------------------------------------------------------------------------------------
function on_303() /* ison */
{
    if (rawDataArray()[3] == ':') {
        toServer("NICK ".loadValueFromConfigFile('BOT', 'nickname'));
        /* 1.set nickname from config */
        setBotNickname(loadValueFromConfigFile('BOT', 'nickname'));

        unset($GLOBALS['I_USE_RND_NICKNAME']);

        cliLog('[bot] I recovered my original nickname');
    }
}
//---------------------------------------------------------------------------------------------------------
function on_475() /* if +key on channel */
{
    if (!empty(loadValueFromConfigFile('CHANNEL', 'channel.key'))) {
        joinChannel(loadValueFromConfigFile('CHANNEL', 'channel').' '.loadValueFromConfigFile('CHANNEL', 'channel.key'));
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
    (loadValueFromConfigFile('AUTOMATIC', 'keep.nick') == 'yes') ? $GLOBALS['I_USE_RND_NICKNAME'] = '1' : false;
   
    /* add random to nick */
    $randomNick = loadValueFromConfigFile('BOT', 'nickname').'|'.rand(0, 999);

    /* set random nick */
    cliLog("[bot] Nickname already in use, changing nickname to: {$randomNick}");

    toServer("NICK {$randomNick}");
}
//---------------------------------------------------------------------------------------------------------
function on_422() /* join if no motd */
{
    on_376();
}
