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

function checkCliArguments()
{
    if (isset($_SERVER['argv'][1])) { $argument_1 = $_SERVER['argv'][1]; }
    if (isset($_SERVER['argv'][2])) { $argument_2 = $_SERVER['argv'][2]; }
    if (isset($_SERVER['argv'][3])) { $argument_3 = $_SERVER['argv'][3]; }
    
    switch ($argument_1) {
        case '-h': /* show help */
            echo N.'  Minion Bot cli commands usage: php BOT.php -[option]'.NN,
                 '  -c <config_file>   # loads config from the specified path (eg. C:\my folder\\'.getConfigFileName().')'.N,
                 '  -x                 # create default configuration file (config.json)'.N, /* default config */
                 '  -h                 # this help'.N, /* help */
                 '  -n                 # set bot nickname'.N, /* change bot nickname */
                 '  -o <server> <port> # connect to specified server: (eg. php BOT.php -o irc.dal.net 6667)'.N, /* server */
                 '  -p                 # hash password to SHA256'.N, /* hash */
                 '  -u                 # check if there is new bot version'.N, /* update */
                 '  -v                 # prints bot version'.NN; /* version */
            exit;
            break;

        case '-n': /* set nickname */
            if (!empty($argument_2)) {
                saveValueToConfigFile('BOT', 'nickname', $argument_2);
            } else {
                     cliError('You need to specify bot nickname! Exiting.');
                     exit;
            }
            break;

        case '-c': /* check if config is loaded from -c switch */
            if (!empty($argument_2) && !is_file($argument_2)) {
                cliError('Config file does not exist, wrong path?');
                exit;
            } elseif (empty($argument_2)) {
                      cliError('You need to specify config file! I need some data.');
                      exit;
            }
            break;

        case '-x': /* create default configuration */
            echo N.' Creating default configuration file ('.getConfigFileName().')'.N;
            createDefaultConfigFile();

            if (is_file(getConfigFileName())) {
                echo ' Done.'.N;
            } else {
                     cliError('Cannot create default configuration file. Read-only file system?');
            }

            exit;
            break;

        case '-o': /* server connect: eg: irc.example.net 6667 */
            if (!empty($argument_2) && !empty($argument_3) && is_numeric($argument_3)) {
                /* we will check this later in code */
            } elseif (empty($argument_2)) {
                      cliError('You need to specify server address, Exiting.');
                      exit;
            } elseif (empty($argument_3)) {
                      cliError('You need to specify server port, Exiting.');
                      exit;
            } elseif (!is_numeric($argument_3)) {
                      cliError('Wrong server port, Exiting.');
                      exit;
            }
            break;

        case '-p': /* hash password => sha256 */
            echo N.' I will hash your password to SHA256'.N;
            echo N.' Password: ';
            $STDIN = fopen('php://stdin', 'r');
            $pwd = str_replace(' ', '', fread($STDIN, 30));
            while (strlen($pwd) < 10) {
                   echo ' Password too short, password must be at least 8 characters long'.N;
                   echo ' Password: ';
                   unset($pwd);
                   $pwd = fread($STDIN, 30);
            }
            echo N.' Password Hashed!'.N.N;
            echo N.' Save password to Config file? (yes/no)'.N;
            echo ' > ';

            $answer = trim(fgets($STDIN));

            if ($answer == 'yes' xor $answer == 'y') {
                if (is_file(getConfigFileName())) {
                    saveValueToConfigFile('OWNER', 'owner password', hash('sha256', rtrim($pwd, "\n\r")));
                    echo N.' Password saved to config file, Exiting.';
                    winSleep(6);
                } else {
                         cliError('Cannot find \''.getConfigFileName().'\' file, exiting!');
                         winSleep(5);
                }
            } else {
                     exit;
            }

        case '-v': /* show version */
            echo ' MINION Bot version: '.VER;
            exit;
            break;

        case '-u': /* update check */
            if (extension_loaded('openssl')) {
                $versionFile = @file_get_contents(VERSION_URL);
                if (!empty($versionFile)) {
                    $version = explode("\n", $versionFile);
                    if ($version[0] > VER) {
                        echo N.' New version available!'.N;
                        echo N.' Version installed: '.VER;
                        echo N.' Version on server: '.$version[0].N;
                        echo N.' Link:'.N;
                        echo N.' https://github.com/S3x0r/MINION/releases/tag/'.$version[0].NN;
                        exit;
                    } else {
                             echo N.' No new update, you have the latest version.'.NN;
                             exit;
                    }
                } else {
                         cliError('Cannot connect to update server, try next time.');
                         exit;
                }
            } else {
                     cliError('I cannot check for update. I need php_openssl extension to work!');
                     exit;
            }
    }
}
