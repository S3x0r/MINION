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

function createDefaultConfigFile()
{
    $config =
<<<END
{
    "BOT": {
        "nickname": "minion",
        "name": "http://github.com/S3x0r/MINION",
        "ident": "minion",
        "bot modes": ""
    },
    "SERVER": {
        "servers": [
            "localhost:6667:plain",
            "localhost:6679:ssl:password",
            "other-server:7000"
        ],
        "how many times connect to server": 5,
        "connect delay": 6,
        "show message of the day": false
    },
    "OWNER": {
        "bot admin": "minion <user@localhost>",
        "owner password": "47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed",
        "owner message on join channel": true,
        "owner message": "Bello my master!"
    },
    "PRIVILEGES": {
        "OWNER": "",
        "ADMIN": "",
        "USER": ""
    },
    "USERSLEVELS": {
        "OWNER": 0,
        "ADMIN": 1,
        "USER": 999
    },
    "RESPONSE": {
        "bot response": "notice"
    },
    "AUTOMATIC": {
        "auto op": true,
        "auto op list": [
            "user!ident@hostname"
        ],
        "auto rejoin": true,
        "keep channel modes": true,
        "keep nick": true
    },
    "CHANNEL": {
        "channel": "#minion",
        "auto join": true,
        "channel modes": "nt",
        "channel key": "",
        "channel topic": "bello!",
        "keep topic": true,
        "give voice users on join": false
    },
    "COMMANDS": {
        "raw commands on start": [
        ]
    },
    "MESSAGE": {
        "show channel user messages": false,
        "show channel kicks messages": true,
        "show private messages": false,
        "show users notice messages": true,
        "show users join channel": true,
        "show users part channel": true,
        "show users quit messages": true,
        "show users invite messages": true,
        "show topic changes": true,
        "show nick changes": true,
        "show plugin usage info": true,
        "show ctcp messages": true
    },
    "IGNORE": {
       "users": [
       ]
    },
    "BANS": {
        "ban list": [
            "nick!ident@hostname",
            "*!ident@hostname",
            "*!*@onlyhost"
        ]
    },
    "COMMAND": {
        "command prefix": "!"
    },
    "CTCP": {
        "ctcp response": true
    },
    "DELAYS": {
        "channel delay": 1,
        "private delay": 1,
        "notice delay": 1
    },
    "LOGS": {
        "logging": true,
        "log bot messages": true,
        "log server messages": true,
        "log ctcp messages": true,
        "log notice messages": true,
        "log channel messages": true,
        "log plugins usage messages": true,
        "log raw messages": false
    },
    "TIME": {
        "timezone": "Europe/Warsaw"
    },
    "FETCH": {
        "fetch server": "https://raw.githubusercontent.com/S3x0r/minion_repository_plugins/master"
    },
    "PROGRAM": {
        "play sounds": true
    },
    "DEBUG": {
        "show raw": false,
        "show own messages in raw mode": false,
        "show debug": false
    }
}
END;

    /* Save default config to file */
    saveToFile(getConfigFileName(), $config, 'w');

    if (!is_file(getConfigFileName())) {
        cliError('Cannot create default configuration file! Read-Only filesystem? Exiting!');
        winSleep(6);
    }
}
//---------------------------------------------------------------------------------------------------------
function isConfigProvidedFromArgument()
{   
    if (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == '-c' && !empty($_SERVER['argv'][2]) && is_file($_SERVER['argv'][2])) {
        return true;
    } else {
             return false;
    }
}
//---------------------------------------------------------------------------------------------------------
function isServerProvidedFromArgument()
{
    if (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == '-o' &&
       !empty($_SERVER['argv'][2]) && !empty($_SERVER['argv'][3]) && is_numeric($_SERVER['argv'][3])) {
        return true;
    } else {
             return false;
    }
}
//---------------------------------------------------------------------------------------------------------
function getConfigFileName()
{
    if (isConfigProvidedFromArgument()) {
        return $_SERVER['argv'][2];
    } else {
             return CONFIGFILE;
    }
}
//---------------------------------------------------------------------------------------------------------
function loadValueFromConfigFile($section, $value)
{
    $config = json_decode(file_get_contents(getConfigFileName()), true);

    return $config[$section][$value];
}
//---------------------------------------------------------------------------------------------------------
function saveValueToConfigFile($section, $option, $value)
{
    $config = json_decode(file_get_contents(getConfigFileName()), true);

    $replace = [$section => [$option => $value]];
    
    $newArray = array_replace_recursive($config, $replace);
    
    $newJsonData = file_put_contents(getConfigFileName(), json_encode($newArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
//---------------------------------------------------------------------------------------------------------
function saveValueToListConfigFile($section, $option, $value)
{
    $config = json_decode(file_get_contents(getConfigFileName()), true);

    array_push($config[$section][$option], $value);

    $newJsonData = file_put_contents(getConfigFileName(), json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
//---------------------------------------------------------------------------------------------------------
function commandPrefix()
{
    return loadValueFromConfigFile('COMMAND', 'command prefix');
}
//---------------------------------------------------------------------------------------------------------
function checkIfConfigExists()
{
    if (is_file(getConfigFileName())) {
        return TRUE;
    } else {
             return FALSE;
    }
}
//---------------------------------------------------------------------------------------------------------
function checkIfConfigIsValid($configFile)
{
    cliBot('Checking for errors in config file');

    $config = json_decode(file_get_contents($configFile), true);

    /* if valid json file, lets now check variables, etc */
    if (json_last_error() === JSON_ERROR_NONE) {
       /* BOT: nickname */
       if (empty($config['BOT']['nickname'])) {
           errorInConfigInfo('BOT NICKNAME missing in config! Please fill in missing data!');
       }

       /* BOT: name */
       if (empty($config['BOT']['name'])) {
           errorInConfigInfo('BOT NAME missing in config! Please fill in missing data!');
       }
 
       /* BOT: ident */
       if (empty($config['BOT']['ident'])) {
           errorInConfigInfo('BOT IDENT missing in config! Please fill in missing data!');
       }

       /* SERVER: servers[0] */
       if (empty($config['SERVER']['servers'][0])) {
           errorInConfigInfo('SERVER missing in config! Please fill in missing data!');
       }

       /* SERVER: how many times connect to server */
       if (empty($config['SERVER']['how many times connect to server']) xor !is_int($config['SERVER']['how many times connect to server'])) {
           errorInConfigInfo('Incorrect value in config: "how many times connect to server" (expected number value) Please correct it.');
       }

       /* SERVER: connect delay */
       if (empty($config['SERVER']['connect delay']) xor !is_int($config['SERVER']['connect delay'])) {
           errorInConfigInfo('Incorrect value in config: "connect delay" (expected number value) Please correct it.');
       }

       /* SERVER: show message of the day */
       if (empty($config['SERVER']['show message of the day']) xor !checkBool($config['SERVER']['show message of the day'])) {
           errorInConfigInfo('Incorrect value in config: "show message of the day" (expected true/false) Please correct it.');
       }

       /* OWNER: owner password */
       if (empty($config['OWNER']['owner password'])) {
           errorInConfigInfo('Bot "owner password" missing!, please fill in missing data in config. Exiting!');
       }

       /* OWNER: owner message on join channel */
       if (empty($config['OWNER']['owner message on join channel']) xor !checkBool($config['OWNER']['owner message on join channel'])) {
           errorInConfigInfo('Incorrect value in config: "owner message on join channel" (expected true/false) Please correct it.');
       }

       /* RESPONSE: bot response */
       if (empty($config['RESPONSE']['bot response']) xor !in_array($config['RESPONSE']['bot response'], array("channel", "notice", "priv"), true)) {
           errorInConfigInfo('Incorrect value in config: "bot response" (expected channel/notice/priv) Please correct it.');
       }

       /* AUTOMATIC: auto op */
       if (empty($config['AUTOMATIC']['auto op']) xor !checkBool($config['AUTOMATIC']['auto op'])) {
           errorInConfigInfo('Incorrect value in config: "auto op" (expected true/false) Please correct it.');
       }

       /* AUTOMATIC: auto rejoin */
       if (empty($config['AUTOMATIC']['auto rejoin']) xor !checkBool($config['AUTOMATIC']['auto rejoin'])) {
           errorInConfigInfo('Incorrect value in config: "auto rejoin" (expected true/false) Please correct it.');
       }

       /* AUTOMATIC: keep channel modes */
       if (empty($config['AUTOMATIC']['keep channel modes']) xor !checkBool($config['AUTOMATIC']['keep channel modes'])) {
           errorInConfigInfo('Incorrect value in config: "keep channel modes" (expected true/false) Please correct it.');
       }

       /* AUTOMATIC: keep nick */
       if (empty($config['AUTOMATIC']['keep nick']) xor !checkBool($config['AUTOMATIC']['keep nick'])) {
           errorInConfigInfo('Incorrect value in config: "keep nick" (expected true/false) Please correct it.');
       }

       /* CHANNEL: auto join */
       if (empty($config['CHANNEL']['auto join']) xor !checkBool($config['CHANNEL']['auto join'])) {
           errorInConfigInfo('Incorrect value in config: "auto join" (expected true/false) Please correct it.');
       }

       /* CHANNEL: keep topic */
       if (empty($config['CHANNEL']['keep topic']) xor !checkBool($config['CHANNEL']['keep topic'])) {
           errorInConfigInfo('Incorrect value in config: "keep topic" (expected true/false) Please correct it.');
       }

       /* CHANNEL: give voice users on join */
       if (empty($config['CHANNEL']['give voice users on join']) xor !checkBool($config['CHANNEL']['give voice users on join'])) {
           errorInConfigInfo('Incorrect value in config: "give voice users on join" (expected true/false) Please correct it.');
       }

       /* MESSAGE: show channel user messages */
       if (empty($config['MESSAGE']['show channel user messages']) xor !checkBool($config['MESSAGE']['show channel user messages'])) {
           errorInConfigInfo('Incorrect value in config: "show channel user messages" (expected true/false) Please correct it.');
       }

       /* MESSAGE: show channel kicks messages */
       if (empty($config['MESSAGE']['show channel kicks messages']) xor !checkBool($config['MESSAGE']['show channel kicks messages'])) {
           errorInConfigInfo('Incorrect value in config: "show channel kicks messages" (expected true/false) Please correct it.');
       }

       /* MESSAGE: show private messages */
       if (empty($config['MESSAGE']['show private messages']) xor !checkBool($config['MESSAGE']['show private messages'])) {
           errorInConfigInfo('Incorrect value in config: "show private messages" (expected true/false) Please correct it.');
       }

       /* MESSAGE: show users notice messages */
       if (empty($config['MESSAGE']['show users notice messages']) xor !checkBool($config['MESSAGE']['show users notice messages'])) {
           errorInConfigInfo('Incorrect value in config: "show users notice messages" (expected true/false) Please correct it.');
       }

       /* MESSAGE: show users join channel */
       if (empty($config['MESSAGE']['show users join channel']) xor !checkBool($config['MESSAGE']['show users join channel'])) {
           errorInConfigInfo('Incorrect value in config: "show users join channel" (expected true/false) Please correct it.');
       }

       /* MESSAGE: show users part channel */
       if (empty($config['MESSAGE']['show users part channel']) xor !checkBool($config['MESSAGE']['show users part channel'])) {
           errorInConfigInfo('Incorrect value in config: "show users part channel" (expected true/false) Please correct it.');
       }

       /* MESSAGE: show users quit messages */
       if (empty($config['MESSAGE']['show users quit messages']) xor !checkBool($config['MESSAGE']['show users quit messages'])) {
           errorInConfigInfo('Incorrect value in config: "show users quit messages" (expected true/false) Please correct it.');
       }

       /* MESSAGE: show users invite messages */
       if (empty($config['MESSAGE']['show users invite messages']) xor !checkBool($config['MESSAGE']['show users invite messages'])) {
           errorInConfigInfo('Incorrect value in config: "show users invite messages" (expected true/false) Please correct it.');
       }

       /* MESSAGE: show topic changes */
       if (empty($config['MESSAGE']['show topic changes']) xor !checkBool($config['MESSAGE']['show topic changes'])) {
           errorInConfigInfo('Incorrect value in config: "show topic changes" (expected true/false) Please correct it.');
       }

       /* MESSAGE: show nick changes */
       if (empty($config['MESSAGE']['show nick changes']) xor !checkBool($config['MESSAGE']['show nick changes'])) {
           errorInConfigInfo('Incorrect value in config: "show nick changes" (expected true/false) Please correct it.');
       }

       /* MESSAGE: show plugin usage info */
       if (empty($config['MESSAGE']['show plugin usage info']) xor !checkBool($config['MESSAGE']['show plugin usage info'])) {
           errorInConfigInfo('Incorrect value in config: "show plugin usage info" (expected true/false) Please correct it.');
       }

       /* MESSAGE: show ctcp messages */
       if (empty($config['MESSAGE']['show ctcp messages']) xor !checkBool($config['MESSAGE']['show ctcp messages'])) {
           errorInConfigInfo('Incorrect value in config: "show ctcp messages" (expected true/false) Please correct it.');
       }

       /* COMMAND: command prefix */
       if (empty($config['COMMAND']['command prefix'])) {
           errorInConfigInfo('Incorrect value in config: "command prefix" Please correct it.');
       }

       /* CTCP: ctcp response */
       if (empty($config['CTCP']['ctcp response']) xor !checkBool($config['CTCP']['ctcp response'])) {
           errorInConfigInfo('Incorrect value in config: "ctcp response" (expected true/false) Please correct it.');
       }

       /* DELAYS: channel delay */
       if (empty($config['DELAYS']['channel delay']) xor !is_int($config['DELAYS']['channel delay'])) {
           errorInConfigInfo('Incorrect value in config: "channel delay" (expected number) Please correct it.');
       }

       /* DELAYS: private delay */
       if (empty($config['DELAYS']['private delay']) xor !is_int($config['DELAYS']['private delay'])) {
           errorInConfigInfo('Incorrect value in config: "private delay" (expected number) Please correct it.');
       }

       /* DELAYS: notice delay */
       if (empty($config['DELAYS']['notice delay']) xor !is_int($config['DELAYS']['notice delay'])) {
           errorInConfigInfo('Incorrect value in config: "notice delay" (expected number) Please correct it.');
       }

       /* LOGS: logging */
       if (empty($config['LOGS']['logging']) xor !checkBool($config['LOGS']['logging'])) {
           errorInConfigInfo('Incorrect value in config: "logging" (expected true/false) Please correct it.');
       }

       /* LOGS: log bot messages */
       if (empty($config['LOGS']['log bot messages']) xor !checkBool($config['LOGS']['log bot messages'])) {
           errorInConfigInfo('Incorrect value in config: "log bot messages" (expected true/false) Please correct it.');
       }

       /* LOGS: log server messages */
       if (empty($config['LOGS']['log server messages']) xor !checkBool($config['LOGS']['log server messages'])) {
           errorInConfigInfo('Incorrect value in config: "log server messages" (expected true/false) Please correct it.');
       }

       /* LOGS: log ctcp messages */
       if (empty($config['LOGS']['log ctcp messages']) xor !checkBool($config['LOGS']['log ctcp messages'])) {
           errorInConfigInfo('Incorrect value in config: "log ctcp messages" (expected true/false) Please correct it.');
       }

       /* LOGS: log notice messages */
       if (empty($config['LOGS']['log notice messages']) xor !checkBool($config['LOGS']['log notice messages'])) {
           errorInConfigInfo('Incorrect value in config: "log notice messages" (expected true/false) Please correct it.');
       }

       /* LOGS: log channel messages */
       if (empty($config['LOGS']['log channel messages']) xor !checkBool($config['LOGS']['log channel messages'])) {
           errorInConfigInfo('Incorrect value in config: "log channel messages" (expected true/false) Please correct it.');
       }

       /* LOGS: log plugins usage messages */
       if (empty($config['LOGS']['log plugins usage messages']) xor !checkBool($config['LOGS']['log plugins usage messages'])) {
           errorInConfigInfo('Incorrect value in config: "log plugins usage messages" (expected true/false) Please correct it.');
       }

       /* LOGS: log raw messages */
       if (empty($config['LOGS']['log raw messages']) xor !checkBool($config['LOGS']['log raw messages'])) {
           errorInConfigInfo('Incorrect value in config: "log raw messages" (expected true/false) Please correct it.');
       }

       /* PROGRAM: play sounds */
       if (empty($config['PROGRAM']['play sounds']) xor !checkBool($config['PROGRAM']['play sounds'])) {
           errorInConfigInfo('Incorrect value in config: "play sounds" (expected true/false) Please correct it.');
       }

       /* DEBUG: show raw */
       if (empty($config['DEBUG']['show raw']) xor !checkBool($config['DEBUG']['show raw'])) {
           errorInConfigInfo('Incorrect value in config: "show raw" (expected true/false) Please correct it.');
       }

       /* DEBUG: show own messages in raw mode */
       if (empty($config['DEBUG']['show own messages in raw mode']) xor !checkBool($config['DEBUG']['show own messages in raw mode'])) {
           errorInConfigInfo('Incorrect value in config: "show own messages in raw mode" (expected true/false) Please correct it.');
       }

       /* DEBUG: show debug */
       if (empty($config['DEBUG']['show debug']) xor !checkBool($config['DEBUG']['show debug'])) {
           errorInConfigInfo('Incorrect value in config: "show debug" (expected true/false) Please correct it.');
       }

    } else {
             /* if invalid json format */
             echo "\033c";
             cliBot('Config file ('.getConfigFileName().') contains JSON syntax errors, Correct errors in config!');
             winSleep(7);
             exit;
    }
}
//---------------------------------------------------------------------------------------------------------
function errorInConfigInfo($info)
{
    cliError($info);
    winSleep(7);
}
//---------------------------------------------------------------------------------------------------------
function checkBool($string)
{
    $string = strtolower($string);

    return (in_array($string, array("true", "false", "1", "0"), true));
}
