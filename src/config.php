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

        $checks = [
            ['SERVER', 'how many times connect to server', 'is_int', 'number value'],
            ['SERVER', 'connect delay', 'is_int', 'number value'],
            ['DELAYS', 'channel delay', 'is_int', 'number'],
            ['DELAYS', 'private delay', 'is_int', 'number'],
            ['DELAYS', 'notice delay', 'is_int', 'number'],
            ['FLOOD', 'flood delay', 'is_float', 'float'],
            ['RESPONSE', 'bot response', function($v) { return in_array($v, ["channel", "notice", "priv"], true); }, 'channel/notice/priv'],
            ['FLOOD', 'channel flood', function($v) { return in_array($v, ["bankick", "kick", "warn"], true); }, 'bankick/kick/warn'],
            ['FLOOD', 'privmsg flood', function($v) { return in_array($v, ["ignore", "warn"], true); }, 'ignore/warn'],
            ['FLOOD', 'notice flood', function($v) { return in_array($v, ["ignore", "warn"], true); }, 'ignore/warn'],
            ['FLOOD', 'ctcp flood', function($v) { return in_array($v, ["ignore", "warn"], true); }, 'ignore/warn'],
        ];

        foreach ($checks as $check) {
            [$section, $key, $validation, $expected] = $check;
            if (empty($config[$section][$key]) xor !$validation($config[$section][$key])) {
                cliConfigErr($infoTxt . "\"$key\" (expected $expected) Please correct it.");
            }
        }

        $requiredFields = [
            ['BOT', 'nickname'],
            ['BOT', 'name'],
            ['BOT', 'ident'],
            ['SERVER', 'servers', 0],
            ['COMMAND', 'command prefix'],
            ['OWNER', 'owner password'],
        ];

        foreach ($requiredFields as $field) {
            [$section, $key, $subkey] = array_pad($field, 3, null);
            if (is_null($subkey) && empty($config[$section][$key])) {
                cliConfigErr($infoTxt."\"$key\" Please fill in missing data!");
            } elseif (!is_null($subkey) && empty($config[$section][$key][$subkey])) {
                cliConfigErr($infoTxt."\"$key\" Please fill in missing data!");
            }
        }

        $boolChecks = [
            ['SERVER', 'show message of the day'],
            ['OWNER', 'owner message on join channel'],
            ['AUTOMATIC', 'auto op'],
            ['AUTOMATIC', 'auto rejoin'],
            ['AUTOMATIC', 'keep channel modes'],
            ['AUTOMATIC', 'keep nick'],
            ['CHANNEL', 'auto join'],
            ['CHANNEL', 'keep topic'],
            ['CHANNEL', 'give voice users on join'],
            ['MESSAGE', 'show channel user messages'],
            ['MESSAGE', 'show channel kicks messages'],
            ['MESSAGE', 'show private messages'],
            ['MESSAGE', 'show users notice messages'],
            ['MESSAGE', 'show users join channel'],
            ['MESSAGE', 'show users part channel'],
            ['MESSAGE', 'show users quit messages'],
            ['MESSAGE', 'show users invite messages'],
            ['MESSAGE', 'show topic changes'],
            ['MESSAGE', 'show nick changes'],
            ['MESSAGE', 'show plugin usage info'],
            ['MESSAGE', 'show ctcp messages'],
            ['CTCP', 'ctcp response'],
            ['LOGS', 'logging'],
            ['LOGS', 'log bot messages'],
            ['LOGS', 'log server messages'],
            ['LOGS', 'log ctcp messages'],
            ['LOGS', 'log notice messages'],
            ['LOGS', 'log channel messages'],
            ['LOGS', 'log plugins usage messages'],
            ['LOGS', 'log raw messages'],
            ['PROGRAM', 'play sounds'],
            ['PROGRAM', 'list plugins on start'],
            ['DEBUG', 'show raw'],
            ['DEBUG', 'show own messages in raw mode'],
            ['DEBUG', 'show debug'],
        ];

        foreach ($boolChecks as $check) {
            [$section, $key] = $check;
            if (emptyOrNoBool($config, $section, $key)) {
                cliConfigErr($infoTxt . "\"$key\"$infoTxtEnd");
            }
        }
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
