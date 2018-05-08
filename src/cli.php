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

if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
         Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>');
}
//---------------------------------------------------------------------------------------------------------
function CheckCLIArgs()
{
    if (isset($_SERVER['argv'][1])) {
        switch ($_SERVER['argv'][1]) {
            case '-h': /* show help */
                echo PHP_EOL.'  '.TR_62.PHP_EOL.PHP_EOL,
                     '  -c '.TR_63.PHP_EOL, /* config file */
                     '  -h '.TR_67.PHP_EOL, /* help */
                     '  -o connect to specified server: eg: BOT.php irc.dal.net 6667'.PHP_EOL, /* server */
                     '  -p '.TR_64.PHP_EOL, /* hash */
                     '  -s '.TR_65.PHP_EOL, /* silent mode */
                     '  -u check if there is new bot version on server'.PHP_EOL, /* update */
                     '  -v '.TR_66.PHP_EOL.PHP_EOL; /* version */
                die();
                break;

            case '-o': /* server connect: eg: irc.example.net 6667 */
                if (!empty($_SERVER['argv'][2]) && !empty($_SERVER['argv'][3]) && is_numeric($_SERVER['argv'][3])) {
                    $GLOBALS['CONFIG_SERVER'] = $_SERVER['argv'][2];
                    $GLOBALS['CONFIG_PORT'] = $_SERVER['argv'][3];
                } elseif (empty($_SERVER['argv'][2])) {
                          echo PHP_EOL.' ERROR: You need to specify server address, Exiting.'.PHP_EOL;
                          sleep(3);
                          die();
                } elseif (empty($_SERVER['argv'][3])) {
                          echo PHP_EOL.' ERROR: You need to specify server port, Exiting.'.PHP_EOL;
                          sleep(3);
                          die();
                } elseif (!is_numeric($_SERVER['argv'][3])) {
                          echo PHP_EOL.' ERROR: Wrong server port, Exiting.'.PHP_EOL;
                          sleep(3);
                          die();
                }
                break;

            case '-p': /* generate hash */
                echo PHP_EOL.' '.TR_68.PHP_EOL;
                echo PHP_EOL.' '.TR_69.' ';
                $STDIN = fopen('php://stdin', 'r');
                $pwd = str_replace(' ', '', fread($STDIN, 30));
                while (strlen($pwd) < 8) {
                       echo ' '.TR_16.PHP_EOL;
                       echo ' '.TR_69.' ';
                       unset($pwd);
                       $pwd = fread($STDIN, 30);
                }
                $hash = hash('sha256', rtrim($pwd, "\n\r"));
                echo PHP_EOL.' '.TR_70." $hash".PHP_EOL.PHP_EOL;
                die();

            case '-s': /* silent mode */
                $GLOBALS['silent_cli'] = 'yes';
                $GLOBALS['silent_mode'] = 'yes';
                if (extension_loaded('wcli')) {
                    wcli_minimize();
                }
                break;

            case '-v': /* show version */
                echo PHP_EOL.' '.TR_71.' '.VER.PHP_EOL;
                die();
                break;

            case '-u': /* update check */
                if (extension_loaded('openssl')) {
                    $addr = 'https://raw.githubusercontent.com/S3x0r/version-for-BOT/master/VERSION.TXT';
                    $CheckVersion = @file_get_contents($addr);
                    if (!empty($CheckVersion)) {
                        $version = explode("\n", $CheckVersion);
                        if ($version[0] > VER) {
                            echo PHP_EOL.' New version available!'.PHP_EOL;
                            echo PHP_EOL.' My version: '.VER;
                            echo PHP_EOL.' Version on server: '.$version[0].PHP_EOL;
                            echo PHP_EOL.' To update me msg to bot by typing: !update'.PHP_EOL.PHP_EOL;
                            sleep(10);
                            die();
                        } else {
                                 echo PHP_EOL.' Checking if there is new version...'.PHP_EOL;
                                 echo PHP_EOL.' No new update, you have the latest version.'.PHP_EOL.PHP_EOL;
                                 sleep(8);
                                 die();
                        }
                    } else {
                             echo PHP_EOL.' Cannot connect to update server, try next time.'.PHP_EOL.PHP_EOL;
                             sleep(7);
                             die();
                    }
                } else {
                         echo PHP_EOL.' I cannot update, i need php_openssl extension to work!'.PHP_EOL.PHP_EOL;
                         sleep(7);
                         die();
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
function CLI_MSG($msg, $log)
{
    if (!IsSilent()) {
        $line='['.@date('H:i:s').'] '.$msg.PHP_EOL;

        if (isset($GLOBALS['CONFIG_LOGGING']) && $GLOBALS['CONFIG_LOGGING'] == 'yes') {
            if ($log=='1') {
                SaveToFile($GLOBALS['log_file'], $line, 'a');
            }
        }
        echo $line;
    }
}
