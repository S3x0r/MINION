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

function CheckCliArgs()
{
    if (isset($_SERVER['argv'][1])) {
        switch ($_SERVER['argv'][1]) {
            case '-h': /* show help */
                echo N.'  Bot cli commands usage: BOT.php [option]'.NN,
                     '  -c <config_file> loads config'.N, /* config file */
                     '  -h this help'.N, /* help */
                     '  -o connect to specified server: eg: BOT.php -o irc.dal.net 6667'.N, /* server */
                     '  -p <password> hash password to SHA256'.N, /* hash */
                     '  -s silent mode (no output from bot)'.N, /* silent mode */
                     '  -u check if there is new bot version'.N, /* update */
                     '  -v prints bot version'.NN; /* version */
                exit;
                break;

            case '-c': /* check if config is loaded from -c switch */
                if (!empty($_SERVER['argv'][2]) && is_file(getcwd()."\\".$_SERVER['argv'][2])) {
                    $GLOBALS['configFile'] = getcwd()."\\".$_SERVER['argv'][2];
                } elseif (!empty($_SERVER['argv'][2]) && !is_file(getcwd()."\\".$_SERVER['argv'][2])) {
                          echo '  [ERROR] Config file does not exist, wrong path?'.NN;
                          WinSleep(6);
                          exit;
                } elseif (empty($_SERVER['argv'][2])) {
                          echo '  [ERROR] You need to specify config file! I need some data :)'.NN;
                          WinSleep(6);
                          exit;
                }
                break;

            case '-o': /* server connect: eg: irc.example.net 6667 */
                if (!empty($_SERVER['argv'][2]) && !empty($_SERVER['argv'][3]) && is_numeric($_SERVER['argv'][3])) {
                    $GLOBALS['CUSTOM_SERVER_AND_PORT'] = 'yes';
					$GLOBALS['CONFIG_SERVER'] = $_SERVER['argv'][2];
                    $GLOBALS['CONFIG_PORT'] = $_SERVER['argv'][3];
                } elseif (empty($_SERVER['argv'][2])) {
                          echo N.' ERROR: You need to specify server address, Exiting.';
                          WinSleep(3);
                          exit;
                } elseif (empty($_SERVER['argv'][3])) {
                          echo N.' ERROR: You need to specify server port, Exiting.';
                          WinSleep(3);
                          exit;
                } elseif (!is_numeric($_SERVER['argv'][3])) {
                          echo N.' ERROR: Wrong server port, Exiting.';
                          WinSleep(3);
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
                $hash = hash('sha256', rtrim($pwd, "\n\r"));
                echo N." Hashed: {$hash}".N.N;

                echo N.' Save password to Config file? (yes/no)'.N;
                echo ' > ';

                $answer = trim(fgets($STDIN));
                if ($answer == 'yes' xor $answer == 'y') {
                    if (is_file('CONFIG.INI')) {
                        SaveData('CONFIG.INI', 'OWNER', 'owner_password', $hash);
                        echo N.' Password saved to config file, Exiting.';
                        WinSleep(3);
                        exit;
                    } else {
                             echo N.' Cannot find CONFIG.INI file, exiting!';
                             exit;
                    }
                } else {
                         exit;
                }

            case '-s': /* silent mode */
                $GLOBALS['silent_cli'] = 'yes';
                $GLOBALS['silent_mode'] = 'yes';
                break;

            case '-v': /* show version */
                echo N.' MINION version: '.VER.N;
                exit;
                break;

            case '-u': /* update check */
                if (extension_loaded('openssl')) {
                    $addr = 'https://raw.githubusercontent.com/S3x0r/version-for-BOT/master/VERSION.TXT';
                    $CheckVersion = @file_get_contents($addr);
                    if (!empty($CheckVersion)) {
                        $version = explode("\n", $CheckVersion);
                        if ($version[0] > VER) {
                            echo N.' New version available!'.N;
                            echo N.' My version: '.VER;
                            echo N.' Version on server: '.$version[0].N;
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
}
//---------------------------------------------------------------------------------------------------------
function CLI_MSG($message, $save = 0)
{
    if (!IsSilent()) {
        $line = "[".@date('H:i:s')."] {$message}".N;

        if (isset($GLOBALS['CONFIG_LOGGING']) && $GLOBALS['CONFIG_LOGGING'] == 'yes' && $save == '1') {
            SaveToFile($GLOBALS['logFile'], $line, 'a');
        }

        echo $line;
    }
}
