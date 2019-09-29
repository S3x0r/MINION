<?php
/* Copyright (c) 2013-2018, S3x0r <olisek@gmail.com>
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

PHP_SAPI !== 'cli' ? exit('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
                           Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>') : false;
//---------------------------------------------------------------------------------------------------------
function CheckCliArgs()
{
    if (isset($_SERVER['argv'][1])) {
        switch ($_SERVER['argv'][1]) {
            case '-h': /* show help */
                echo N.'  '.TR_62.N.N,
                     '  -c '.TR_63.N, /* config file */
                     '  -h '.TR_67.N, /* help */
                     '  -o connect to specified server: eg: BOT.php irc.dal.net 6667'.N, /* server */
                     '  -p '.TR_64.N, /* hash */
                     '  -s '.TR_65.N, /* silent mode */
                     '  -u check if there is new bot version'.N, /* update */
                     '  -v '.TR_66.N.N; /* version */
                exit;
                break;

            case '-o': /* server connect: eg: irc.example.net 6667 */
                if (!empty($_SERVER['argv'][2]) && !empty($_SERVER['argv'][3]) && is_numeric($_SERVER['argv'][3])) {
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

            case '-p': /* encrypt password => sha256 */
                echo N.' '.TR_68.N;
                echo N.' '.TR_69.' ';
                $STDIN = fopen('php://stdin', 'r');
                $pwd = str_replace(' ', '', fread($STDIN, 30));
                while (strlen($pwd) < 8) {
                       echo ' '.TR_16.N;
                       echo ' '.TR_69.' ';
                       unset($pwd);
                       $pwd = fread($STDIN, 30);
                }
                $hash = hash('sha256', rtrim($pwd, "\n\r"));
                echo N.' '.TR_70." $hash".N.N;

                echo N.' Save password to Config file? (yes/no)'.N;
                echo ' > ';

                $answer = trim(fgets($STDIN));
                if ($answer == 'yes' xor $answer == 'y') {
                    if (is_file('../CONFIG.INI')) {
                        SaveData('../CONFIG.INI', 'OWNER', 'owner_password', $hash);
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
                if (extension_loaded('wcli')) {
                    wcli_minimize();
                }
                break;

            case '-v': /* show version */
                echo N.' '.TR_71.' '.VER.N;
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
                            echo N.' To update BOT msg to bot by typing: !update'.N.N;
                            WinSleep(10);
                            exit;
                        } else {
                                 echo N.' Checking if there is new BOT version...'.N;
                                 echo N.' No new update, you have the latest version.'.N;
                                 WinSleep(4);
                                 exit;
                        }
                    } else {
                             echo N.' Cannot connect to update server, try next time.'.N.N;
                             WinSleep(4);
                             exit;
                    }
                } else {
                         echo N.' I cannot check for update. I need php_openssl extension to work!'.N.N;
                         WinSleep(4);
                         exit;
                }
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function wcliStart()
{
    if (!IsSilent()) {
        if (extension_loaded('wcli')) {
            wcli_maximize();
            wcli_set_console_title('MINION '.VER);
            wcli_hide_cursor();
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function wcliExt()
{
    if (!IsSilent()) {
        if (extension_loaded('wcli')) {
            wcli_set_console_title('MINION '.VER.' ('.TR_51.' '.$GLOBALS['CONFIG_SERVER'].':'
            .$GLOBALS['CONFIG_PORT'].' | '.TR_52.' '.$GLOBALS['BOT_NICKNAME'].' | '.TR_53.' '
            .$GLOBALS['CONFIG_CNANNEL'].')');
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function CLI_MSG($message, $save = 0)
{
    if (!IsSilent()) {
        $line = '['.@date('H:i:s').'] '.$message.N;

        if (isset($GLOBALS['CONFIG_LOGGING']) && $GLOBALS['CONFIG_LOGGING'] == 'yes' && $save == '1') {
            SaveToFile($GLOBALS['log_file'], $line, 'a');
        }
        echo $line;
    }
}
