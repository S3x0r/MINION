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

function checkArgs()
{
    switch ($_SERVER['argv'][1]) {
        case '-h': /* show help */
            echo N.'  Bot cli commands usage: php BOT.php -[option]'.NN,
                 '  -c <config_file>   # loads config from the specified path (eg. C:\my folder\CONFIG.INI)'.N, /* config file */
                 '  -h                 # this help'.N, /* help */
                 '  -o <server> <port> # connect to specified server: (eg. php BOT.php -o irc.dal.net 6667)'.N, /* server */
                 '  -p                 # hash password to SHA256'.N, /* hash */
                 '  -u                 # check if there is new bot version'.N, /* update */
                 '  -v                 # prints bot version'.NN; /* version */
            exit;
            break;

        case '-c': /* check if config is loaded from -c switch */
            if (!empty($_SERVER['argv'][2]) && is_file($_SERVER['argv'][2])) {
                $GLOBALS['configFile'] = $_SERVER['argv'][2];
            } elseif (!empty($_SERVER['argv'][2]) && !is_file($_SERVER['argv'][2])) {
                      echo '  [ERROR] Config file does not exist, wrong path?'.NN;
                      exit;
            } elseif (empty($_SERVER['argv'][2])) {
                      echo '  [ERROR] You need to specify config file! I need some data :)'.NN;
                      exit;
            }
            break;

        case '-o': /* server connect: eg: irc.example.net 6667 */
            if (!empty($_SERVER['argv'][2]) && !empty($_SERVER['argv'][3]) && is_numeric($_SERVER['argv'][3])) {
                $GLOBALS['CONFIG.SERVER'] = $_SERVER['argv'][2];
                $GLOBALS['CONFIG.PORT']   = $_SERVER['argv'][3];
            } elseif (empty($_SERVER['argv'][2])) {
                      echo N.' ERROR: You need to specify server address, Exiting.';
                      exit;
            } elseif (empty($_SERVER['argv'][3])) {
                      echo N.' ERROR: You need to specify server port, Exiting.';
                      exit;
            } elseif (!is_numeric($_SERVER['argv'][3])) {
                      echo N.' ERROR: Wrong server port, Exiting.';
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
                if (is_file('CONFIG.INI')) {
                    SaveData('CONFIG.INI', 'OWNER', 'owner.password', hash('sha256', rtrim($pwd, "\n\r")));
                    echo N.' Password saved to config file, Exiting.';
                    WinSleep(6);
                    exit;
                } else {
                         echo N.' Cannot find CONFIG.INI file, exiting!';
                         WinSleep(5);
                         exit;
                }
            } else {
                     exit;
            }

        case '-v': /* show version */
            echo N.' MINION version: '.VER.N;
            exit;
            break;

        case '-u': /* update check */
            if (extension_loaded('openssl')) {
                $file = @file_get_contents(VERSION_URL);
                if (!empty($file)) {
                    $serverVersion = explode("\n", $file);
                    if ($serverVersion[0] > VER) {
                        echo N.' New version available!'.N;
                        echo N.' My version: '.VER;
                        echo N.' Version on server: '.$serverVersion[0].N;
                        echo N.' To update BOT msg to bot by typing: !update'.NN;
                        WinSleep(10);
                        exit;
                    } else {
                             echo N.' I am checking if there is a newer version of MINION Bot...'.N;
                             echo N.' No new update, you have the latest version.'.NN;
                             WinSleep(4);
                             exit;
                    }
                } else {
                         echo N.' Cannot connect to update server, try next time.'.NN;
                         WinSleep(4);
                         exit;
                }
            } else {
                     echo N.' I cannot check for update. I need php_openssl extension to work!'.NN;
                     WinSleep(4);
                     exit;
            }
    }
}
