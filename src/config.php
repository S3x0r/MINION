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
    "FLOOD": {
        "flood delay": 0.5,
        "channel flood": "bankick",
        "privmsg flood": "ignore",
        "notice flood": "ignore",
        "ctcp flood": "ignore"
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
        "play sounds": true,
        "list plugins on start": true
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
function loadValueFromConfigFile($_section, $_value)
{
    $config = json_decode(file_get_contents(getConfigFileName()), true);

    if (isset($config[$_section][$_value])) {
        return $config[$_section][$_value];
    }
}
//---------------------------------------------------------------------------------------------------------
function saveValueToConfigFile($_section, $_option, $_value)
{
    $config = json_decode(file_get_contents(getConfigFileName()), true);

    $replace = [$_section => [$_option => $_value]];
    
    $newArray = array_replace_recursive($config, $replace);
    
    $newJsonData = file_put_contents(getConfigFileName(), json_encode($newArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
//---------------------------------------------------------------------------------------------------------
function saveValueToListConfigFile($_section, $_option, $_value)
{
    $config = json_decode(file_get_contents(getConfigFileName()), true);

    array_push($config[$_section][$_option], $_value);

    $newJsonData = file_put_contents(getConfigFileName(), json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
//---------------------------------------------------------------------------------------------------------
function ifConfigExists()
{
    if (is_file(getConfigFileName())) {
        return true;
    } else {
             return false;
    }
}
//---------------------------------------------------------------------------------------------------------
function checkIfConfigIsValid($_configFile)
{
    $config = json_decode(file_get_contents($_configFile), true);

    /* if valid json file, lets now check variables, etc */
    if (json_last_error() === JSON_ERROR_NONE) {
        $infoTxt    = 'Incorrect value in config: ';
        $infoTxtEnd = ' (expected true/false) Please correct it.';
  
        /* SERVER: how many times connect to server */
        if (empty($config['SERVER']['how many times connect to server']) xor !is_int($config['SERVER']['how many times connect to server'])) {
            cliConfigErr($infoTxt.'"how many times connect to server" (expected number value) Please correct it.');
        }
  
        /* SERVER: connect delay */
        if (empty($config['SERVER']['connect delay']) xor !is_int($config['SERVER']['connect delay'])) {
            cliConfigErr($infoTxt.'"connect delay" (expected number value) Please correct it.');
        }
  
        /* DELAYS: channel delay */
        if (empty($config['DELAYS']['channel delay']) xor !is_int($config['DELAYS']['channel delay'])) {
            cliConfigErr($infoTxt.'"channel delay" (expected number) Please correct it.');
        }
  
        /* DELAYS: private delay */
        if (empty($config['DELAYS']['private delay']) xor !is_int($config['DELAYS']['private delay'])) {
            cliConfigErr($infoTxt.'"private delay" (expected number) Please correct it.');
        }
  
        /* DELAYS: notice delay */
        if (empty($config['DELAYS']['notice delay']) xor !is_int($config['DELAYS']['notice delay'])) {
            cliConfigErr($infoTxt.'"notice delay" (expected number) Please correct it.');
        }

        /* FLOOD: flood delay */
        if (empty($config['FLOOD']['flood delay']) xor !is_float($config['FLOOD']['flood delay'])) {
            cliConfigErr($infoTxt.'"flood delay" (expected float) Please correct it.');
        }

        /* RESPONSE: bot response */
        if (empty($config['RESPONSE']['bot response']) xor !in_array($config['RESPONSE']['bot response'], array("channel", "notice", "priv"), true)) {
            cliConfigErr($infoTxt.'"bot response" (expected channel/notice/priv) Please correct it.');
        }

        /* FLOOD: channel flood */
        if (empty($config['FLOOD']['channel flood']) xor !in_array($config['FLOOD']['channel flood'], array("bankick", "kick", "warn"), true)) {
            cliConfigErr($infoTxt.'"channel flood" (expected bankick/kick/warn) Please correct it.');
        }

        /* FLOOD: privmsg flood */
        if (empty($config['FLOOD']['privmsg flood']) xor !in_array($config['FLOOD']['privmsg flood'], array("ignore", "warn"), true)) {
            cliConfigErr($infoTxt.'"privmsg flood" (expected ignore/warn) Please correct it.');
        }

        /* FLOOD: notice flood */
        if (empty($config['FLOOD']['notice flood']) xor !in_array($config['FLOOD']['notice flood'], array("ignore", "warn"), true)) {
            cliConfigErr($infoTxt.'"notice flood" (expected ignore/warn) Please correct it.');
        }

        /* FLOOD: ctcp flood */
        if (empty($config['FLOOD']['ctcp flood']) xor !in_array($config['FLOOD']['ctcp flood'], array("ignore", "warn"), true)) {
            cliConfigErr($infoTxt.'"ctcp flood" (expected ignore/warn) Please correct it.');
        }

        (empty($config['BOT']['nickname']))                                     ? cliConfigErr($infoTxt.'"nickname" Please fill in missing data!')       : false;
        (empty($config['BOT']['name']))                                         ? cliConfigErr($infoTxt.'"name" Please fill in missing data!')           : false;
        (empty($config['BOT']['ident']))                                        ? cliConfigErr($infoTxt.'"ident" Please fill in missing data!')          : false;
        (empty($config['SERVER']['servers'][0]))                                ? cliConfigErr($infoTxt.'"servers" Please fill in missing data!')        : false;
        (empty($config['COMMAND']['command prefix']))                           ? cliConfigErr($infoTxt.'"command prefix" Please fill in missing data!') : false;
        (empty($config['OWNER']['owner password']))                             ? cliConfigErr($infoTxt.'"owner password" Please fill in missing data!') : false;

        (emptyOrNoBool($config, 'SERVER'    , 'show message of the day'))       ? cliConfigErr($infoTxt.'"show message of the day"'.$infoTxtEnd)         : false;
        (emptyOrNoBool($config, 'OWNER'     , 'owner message on join channel')) ? cliConfigErr($infoTxt.'"owner message on join channel"'.$infoTxtEnd)   : false;
        (emptyOrNoBool($config, 'AUTOMATIC' , 'auto op'))                       ? cliConfigErr($infoTxt.'"auto op"'.$infoTxtEnd)                         : false;
        (emptyOrNoBool($config, 'AUTOMATIC' , 'auto rejoin'))                   ? cliConfigErr($infoTxt.'"auto rejoin"'.$infoTxtEnd)                     : false;
        (emptyOrNoBool($config, 'AUTOMATIC' , 'keep channel modes'))            ? cliConfigErr($infoTxt.'"keep channel modes"'.$infoTxtEnd)              : false;
        (emptyOrNoBool($config, 'AUTOMATIC' , 'keep nick'))                     ? cliConfigErr($infoTxt.'"keep nick"'.$infoTxtEnd)                       : false;
        (emptyOrNoBool($config, 'CHANNEL'   , 'auto join'))                     ? cliConfigErr($infoTxt.'"auto join"'.$infoTxtEnd)                       : false;
        (emptyOrNoBool($config, 'CHANNEL'   , 'keep topic'))                    ? cliConfigErr($infoTxt.'"keep topic"'.$infoTxtEnd)                      : false;
        (emptyOrNoBool($config, 'CHANNEL'   , 'give voice users on join'))      ? cliConfigErr($infoTxt.'"give voice users on join"'.$infoTxtEnd)        : false;
        (emptyOrNoBool($config, 'MESSAGE'   , 'show channel user messages'))    ? cliConfigErr($infoTxt.'"show channel user messages"'.$infoTxtEnd)      : false;
        (emptyOrNoBool($config, 'MESSAGE'   , 'show channel kicks messages'))   ? cliConfigErr($infoTxt.'"show channel kicks messages"'.$infoTxtEnd)     : false;
        (emptyOrNoBool($config, 'MESSAGE'   , 'show private messages'))         ? cliConfigErr($infoTxt.'"show private messages"'.$infoTxtEnd)           : false;
        (emptyOrNoBool($config, 'MESSAGE'   , 'show users notice messages'))    ? cliConfigErr($infoTxt.'"show users notice messages"'.$infoTxtEnd)      : false;
        (emptyOrNoBool($config, 'MESSAGE'   , 'show users join channel'))       ? cliConfigErr($infoTxt.'"show users join channel"'.$infoTxtEnd)         : false;
        (emptyOrNoBool($config, 'MESSAGE'   , 'show users part channel'))       ? cliConfigErr($infoTxt.'"show users part channel"'.$infoTxtEnd)         : false;
        (emptyOrNoBool($config, 'MESSAGE'   , 'show users quit messages'))      ? cliConfigErr($infoTxt.'"show users quit messages"'.$infoTxtEnd)        : false;
        (emptyOrNoBool($config, 'MESSAGE'   , 'show users invite messages'))    ? cliConfigErr($infoTxt.'"show users invite messages"'.$infoTxtEnd)      : false;
        (emptyOrNoBool($config, 'MESSAGE'   , 'show topic changes'))            ? cliConfigErr($infoTxt.'"show topic changes"'.$infoTxtEnd)              : false;
        (emptyOrNoBool($config, 'MESSAGE'   , 'show nick changes'))             ? cliConfigErr($infoTxt.'"show nick changes"'.$infoTxtEnd)               : false;
        (emptyOrNoBool($config, 'MESSAGE'   , 'show plugin usage info'))        ? cliConfigErr($infoTxt.'"show plugin usage info"'.$infoTxtEnd)          : false;
        (emptyOrNoBool($config, 'MESSAGE'   , 'show ctcp messages'))            ? cliConfigErr($infoTxt.'"show ctcp messages"'.$infoTxtEnd)              : false;
        (emptyOrNoBool($config, 'CTCP'      , 'ctcp response'))                 ? cliConfigErr($infoTxt.'"ctcp response"'.$infoTxtEnd)                   : false;
        (emptyOrNoBool($config, 'LOGS'      , 'logging'))                       ? cliConfigErr($infoTxt.'"logging"'.$infoTxtEnd)                         : false;
        (emptyOrNoBool($config, 'LOGS'      , 'log bot messages'))              ? cliConfigErr($infoTxt.'"log bot messages"'.$infoTxtEnd)                : false;
        (emptyOrNoBool($config, 'LOGS'      , 'log server messages'))           ? cliConfigErr($infoTxt.'"log server messages"'.$infoTxtEnd)             : false;
        (emptyOrNoBool($config, 'LOGS'      , 'log ctcp messages'))             ? cliConfigErr($infoTxt.'"log ctcp messages"'.$infoTxtEnd)               : false;
        (emptyOrNoBool($config, 'LOGS'      , 'log notice messages'))           ? cliConfigErr($infoTxt.'"log notice messages"'.$infoTxtEnd)             : false;
        (emptyOrNoBool($config, 'LOGS'      , 'log channel messages'))          ? cliConfigErr($infoTxt.'"log channel messages"'.$infoTxtEnd)            : false;
        (emptyOrNoBool($config, 'LOGS'      , 'log plugins usage messages'))    ? cliConfigErr($infoTxt.'"log plugins usage messages"'.$infoTxtEnd)      : false;
        (emptyOrNoBool($config, 'LOGS'      , 'log raw messages'))              ? cliConfigErr($infoTxt.'"log raw messages"'.$infoTxtEnd)                : false;
        (emptyOrNoBool($config, 'PROGRAM'   , 'play sounds'))                   ? cliConfigErr($infoTxt.'"play sounds"'.$infoTxtEnd)                     : false;
        (emptyOrNoBool($config, 'PROGRAM'   , 'list plugins on start'))         ? cliConfigErr($infoTxt.'"list plugins on start"'.$infoTxtEnd)           : false;
        (emptyOrNoBool($config, 'DEBUG'     , 'show raw'))                      ? cliConfigErr($infoTxt.'"show raw"'.$infoTxtEnd)                        : false;
        (emptyOrNoBool($config, 'DEBUG'     , 'show own messages in raw mode')) ? cliConfigErr($infoTxt.'"show own messages in raw mode"'.$infoTxtEnd)   : false;
        (emptyOrNoBool($config, 'DEBUG'     , 'show debug'))                    ? cliConfigErr($infoTxt.'"show debug"'.$infoTxtEnd)                      : false;
     } else {
              /* if invalid json format */
              cliNoLog();
              cliBot('Config file ('.getConfigFileName().') contains JSON syntax errors, Correct errors in config!');
              winSleep(7);
              exit;
     }
}
//---------------------------------------------------------------------------------------------------------
function emptyOrNoBool($_config, $_section, $_value)
{
    $data = strtolower($_config[$_section][$_value]);

    if (empty($data) xor !in_array($data, array("true", "false", "1", "0"), true)) {
        return true;
    }
}
//---------------------------------------------------------------------------------------------------------
function saveToFile($file, $_data, $_method)
{
    $file = @fopen($file, $_method);
    @flock($file, 2);
    @fwrite($file, $_data);
    @flock($file, 3);
    @fclose($file);
}
//---------------------------------------------------------------------------------------------------------
function createLogsDataDir()
{
    /* if directories are missing create them */
    !is_dir(LOGSDIR) ? mkdir(LOGSDIR) : false;

    createLogsDateDir();
    
    !is_dir(DATADIR) ? mkdir(DATADIR) : false;

    !is_dir(DATADIR.'/'.SEENDIR) ? @mkdir(DATADIR.'/'.SEENDIR) : false;
}
//---------------------------------------------------------------------------------------------------------
function createLogsDateDir()
{
    if (is_dir(LOGSDIR)) {
        if (!is_dir(LOGSDIR.'/'.@date('d.m.Y'))) {
            mkdir(LOGSDIR.'/'.@date('d.m.Y')); 
        }
    }
}
