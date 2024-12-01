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
"{
  \"BOT\": {
           \"nickname\" : \"minion\",
           \"name\"     : \"http://github.com/S3x0r/MINION\",
           \"ident\"    : \"minion\",
           \"bot modes\": \"\"
         },
  \"SERVER\": {
              \"server\"                           : \"localhost\",
              \"port\"                             : 6667,
              \"server password\"                  : \"\",
              \"how many times connect to server\" : 99,
              \"connect delay\"                    :  6,
              \"show message of the day\"          : false
            },
  \"OWNER\": {
             \"bot admin\"      : \"minion <user@localhost>\",
             \"owner password\" : \"47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed\",
             \"owner message on join channel\": true,
             \"owner message\": \"Bello my master!\"
           },
  \"PRIVILEGES\": {
                  \"OWNER\" : \"\",
                  \"ADMIN\" : \"\",
                  \"USER\"  : \"\"
                },
  \"USERSLEVELS\": {
                   \"OWNER\" : 0,
                   \"ADMIN\" : 1,
                   \"USER\"  : 999
                 },
  \"RESPONSE\": {
                \"bot response\" : \"notice\"
              },
  \"AUTOMATIC\": {
                 \"auto op\"            : true,
                 \"auto op list\"       : \"\",
                 \"auto rejoin\"        : true,
                 \"keep channel modes\" : true,
                 \"keep nick\"          : true
               },
  \"CHANNEL\": {
                 \"channel\"       : \"#minion\",
                 \"auto join\"     : true,
                 \"channel modes\" : \"nt\",
                 \"channel key\"   : \"\",
                 \"channel topic\" : \"bello!\",
                 \"keep topic\"    : true,
                 \"give voice users on join channel\": false
             },
  \"COMMANDS\": {
                 \"raw commands on start\": \"\"
    },
  \"MESSAGE\": {
        \"show channel user messages\": false,
        \"show channel kicks messages\": true,
        \"show private messages\": false,
        \"show users notice messages\": true,
        \"show users join channel\": true,
        \"show users part channel\": true,
        \"show users quit messages\": true,
        \"show users invite messages\": true,
        \"show topic changes\": true,
        \"show nick changes\": true,
        \"show plugin usage info\": true
    },
  \"BANS\": {
            \"ban list\" : \"nick!ident@hostname, *!ident@hostname, *!*@onlyhost\"
          },
  \"COMMAND\": {
               \"command prefix\" : \"!\"
             },
  \"CTCP\": {
            \"ctcp response\" : true,
            \"ctcp version\"  : \"minion (".VER.") powered by minions!\",
            \"ctcp finger\"   : \"minion\"
          },
  \"DELAYS\": {
              \"channel delay\" : 1,
              \"private delay\" : 1,
              \"notice delay\"  : 1
            },
  \"LOGS\": {
            \"logging\" : true
          },
  \"TIME\": {
            \"timezone\" : \"Europe/Warsaw\"
          },
  \"FETCH\": {
             \"fetch server\" : \"https://raw.githubusercontent.com/S3x0r/minion_repository_plugins/master\"
           },
  \"PROGRAM\": {
               \"play sounds\" : true
             },
  \"DEBUG\": {
             \"show raw\"                      : false,
             \"show own messages in raw mode\" : false,
             \"show debug\"                    : false
           }
}
";

    /* Save default config to file */
    saveToFile(getConfigFileName(), $config, 'w');
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
function commandPrefix()
{
    return loadValueFromConfigFile('COMMAND', 'command prefix');
}
//---------------------------------------------------------------------------------------------------------
function checkIfConfigExists()
{
    /* if no config -> create default one */
    if (!is_file(getConfigFileName())) {
        cli('Configuration file missing! I am creating a default configuration: '.getConfigFileName().N);

        createDefaultConfigFile();
        
        if (!is_file(getConfigFileName())) {
            cliError('Cannot create default configuration file! Read-Only filesystem? Exiting!');
            winSleep(6);
            exit;
        }
    }
}
