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

function handleNumericResponse($parsedData)
{
    if (function_exists('on_'.$parsedData)) {
        call_user_func('on_'.$parsedData);
    }
}
//---------------------------------------------------------------------------------------------------------
function on_001() /* server message */
{
    /* 1.set server name */
    setServerName(rawDataArray()[0]);

    /* 1.set bot nickname */
    setBotNickname(rawDataArray()[2]);

    cliServer(msgFromServer());
}
//---------------------------------------------------------------------------------------------------------
function on_002() /* host, version server */
{
    /* :server.name 002 minion :Your host is server.name, running version ircd-123.4 */

    cliServer(msgFromServer());
}
//---------------------------------------------------------------------------------------------------------
function on_003() /* server creation time */
{
    /* :server.name 003 minion :This server was created Sat Oct 10 15:08:58 2020 */

    cliServer(msgFromServer());
}
//---------------------------------------------------------------------------------------------------------
function on_004()
{
    /* localhost.local 004 NICK localhost.local <server ver> diopqrstwxzBDGHIRSTWZ beIacdfhiklmnopqrstvzCDFGHKLMNOPQRSTVZ */
}
//---------------------------------------------------------------------------------------------------------
function on_005()
{
    /* :localhost.local 005 nick EXCEPTS EXTBAN=~,acfjmnpqrtACFGOST INVEX KICKLEN=307 KNOCK MAP MAXLIST=b:60,e:60,I:60 MAXNICKLEN=30 MINNICKLEN=0 MODES=12 MONITOR=128 MSGREFTYPES=msgid,timestamp :are supported by this server */
}
//---------------------------------------------------------------------------------------------------------
function on_303() /* ison */
{
    if (rawDataArray()[3] == ':') {
        toServer('NICK '.loadValueFromConfigFile('BOT', 'nickname'));
        /* 1.set nickname from config */
        setBotNickname(loadValueFromConfigFile('BOT', 'nickname'));

        unset($GLOBALS['I_USE_RND_NICKNAME']);

        cliBot('Recovered original nickname from config');

        playSound('prompt.mp3');
    }
}
//---------------------------------------------------------------------------------------------------------
function on_324() /* channel modes */
{
    if (isset(rawDataArray()[4])) {
        unset($GLOBALS['CHANNEL.MODES']);

        $GLOBALS['CHANNEL.MODES'] = str_replace('+', '', rawDataArray()[4]);

        empty(rawDataArray()[5]) ? $msg = $GLOBALS['CHANNEL.MODES'] : $msg = $GLOBALS['CHANNEL.MODES'].' '.rawDataArray()[5];

        if (!empty($GLOBALS['CHANNEL.MODES'])) {
            cliLogChannel('['.getBotChannel().'] * channel modes: +'.$msg);
        } else {
                 cliLogChannel('['.getBotChannel().'] * channel modes are not set');
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function on_331() /* RPL_NOTOPIC - "<channel> :No topic is set" */
{
}
//---------------------------------------------------------------------------------------------------------
function on_332() /* RPL_TOPIC - "<channel> :<topic>" */
{
    if (loadValueFromConfigFile('CHANNEL', 'keep topic') == true && !empty(loadValueFromConfigFile('CHANNEL', 'channel topic'))) {
        if (inputFromLine('4') != loadValueFromConfigFile('CHANNEL', 'channel topic')) {
            setTopic(getBotChannel(), loadValueFromConfigFile('CHANNEL', 'channel topic'));
        }
    }
    
    if (inputFromLine('4') != loadValueFromConfigFile('CHANNEL', 'channel topic')) {
        empty(inputFromLine('4')) ? $msg = 'channel topic is not set' : $msg = 'channel topic: "'.inputFromLine('4').'"';
        
        cliLogChannel('['.rawDataArray()[3].'] * '.$msg);
    }
}
//---------------------------------------------------------------------------------------------------------
function on_353() /* on channel join info */
{
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
        on_bot_opped();
    }
}
//---------------------------------------------------------------------------------------------------------
function on_366() /* end of channel user(s) nicks list- after joining channel */
{
    cliLogChannel('['.getBotChannel().'] Total Channel Users: '.$GLOBALS['channelUsersCount']);
    
    // @
    if (!empty($GLOBALS['channelUsersOP'])) {
        cliLogChannel('['.getBotChannel().'] (@) Op(s): '.$GLOBALS['channelUsersOP']);
    }

    // %
    if (!empty($GLOBALS['channelUsersHalfOp'])) {
        cliLogChannel('['.getBotChannel().'] (%) HalfOp(s): '.$GLOBALS['channelUsersHalfOp']);
    }
    
    // +
    if (!empty($GLOBALS['channelUsersVoice'])) {
        cliLogChannel('['.getBotChannel().'] (+) Voice(s): '.$GLOBALS['channelUsersVoice']);
    }

    // others
    if (!empty($GLOBALS['channelUsersOthers'])) {
        cliLogChannel('['.getBotChannel().'] Other(s): '.$GLOBALS['channelUsersOthers']);
    }

    unset($GLOBALS['353_Start']);
    unset($GLOBALS['channelUsersOP']);
    unset($GLOBALS['channelUsersHalfOp']);
    unset($GLOBALS['channelUsersVoice']);
    unset($GLOBALS['channelUsersOthers']);
    unset($GLOBALS['channelUsersCount']);
}
//---------------------------------------------------------------------------------------------------------
function on_372() /* motd message */
{
    if (loadValueFromConfigFile('SERVER', 'show message of the day') == true) {
        cliServer(msgFromServer());
    }
}
//---------------------------------------------------------------------------------------------------------
function on_375() /* motd start */
{
    if (loadValueFromConfigFile('SERVER', 'show message of the day') == true) {
        cliServer(msgFromServer());
    }
}
//---------------------------------------------------------------------------------------------------------
function on_376() /* motd end */
{
    if (loadValueFromConfigFile('SERVER', 'show message of the day') == true) {
        cliServer(msgFromServer());
    }

    bot_set_own_modes();

    /* show info that we are connected */
    cliNoLog('');
    cliBot('Connected! My nickname is: '.getBotNickname());

    /* register to bot info */
    if (empty(loadValueFromConfigFile('PRIVILEGES', 'OWNER'))) {
        cliNoLog(N.'*********************************************************');
        cliNoLog('Bot owner not registered!');
        cliNoLog('Register to bot by typing /msg '.getBotNickname().' register <password>');
        cliNoLog('*********************************************************'.N);
    }

    /* if autojoin */
    on_bot_auto_join();

    /* send anon stats */
    // Statistics();

    playSound('connected.mp3');
    
    bot_user_commands();
}
//---------------------------------------------------------------------------------------------------------
function on_396()
{
    cliServer(msgFromServer());
}
//---------------------------------------------------------------------------------------------------------
function on_422() /* join if no motd */
{
    on_376();
}
//---------------------------------------------------------------------------------------------------------
function on_431() /* 431 * :No nickname given */
{
    if (empty(loadValueFromConfigFile('BOT', 'nickname'))) {
        cliError('Bot nickname missing, please fill in missing data in config. Exiting!');
    
        winSleep(10);
        exit;
    }    
}
//---------------------------------------------------------------------------------------------------------
function on_432() /* if nick reserved */
{
    /* vars for keep nick functionality */
    (loadValueFromConfigFile('AUTOMATIC', 'keep nick') == true) ? $GLOBALS['I_USE_RND_NICKNAME'] = '1' : false;
   
    /* add random to nick */
    $randomNick = loadValueFromConfigFile('BOT', 'nickname').'|'.rand(0, 999);

    /* set random nick */
    cliBot('Nickname already in use, changing nickname to: '.$randomNick);

    toServer('NICK '.$randomNick);
}
//---------------------------------------------------------------------------------------------------------
function on_433() /* if nick already exists */
{
    on_432();
}
//---------------------------------------------------------------------------------------------------------
function on_448()
{
    cliBot(inputFromLine('4').' Cannot join! Check channel name in config');    
}
//---------------------------------------------------------------------------------------------------------
function on_461() /* USER :Not enough parameters, missing user or ident info */
{
    if (empty(loadValueFromConfigFile('BOT', 'name')) or empty(loadValueFromConfigFile('BOT', 'ident'))) {
        cliError('Bot name or ident missing, please fill in missing data in config. Exiting!');
    
        winSleep(10);
        exit;
    }
}
//---------------------------------------------------------------------------------------------------------
function on_471() /* if +limit on channel */
{
    cliBot('Cannot join - channel is full');
}
//---------------------------------------------------------------------------------------------------------
function on_473() /* if +invite on channel */
{
    cliBot('Cannot join - invite only channel');
}
//---------------------------------------------------------------------------------------------------------
function on_474() /* if bot +banned on channel */
{
    cliBot('Cannot join - bot banned in channel');
}
//---------------------------------------------------------------------------------------------------------
function on_475() /* if +key on channel */
{
    if (!empty(loadValueFromConfigFile('CHANNEL', 'channel key'))) {
        cliBot('Can\'t join channel, bad channel key in config!');
    } else {
             cliBot('Can\'t join channel, channel key required!');
    }

    playSound('prompt.mp3');
}
//---------------------------------------------------------------------------------------------------------
function on_479()
{
    on_448();   
}
