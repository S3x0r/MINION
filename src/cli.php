<?php
/* Copyright (c) 2013-2017, S3x0r <olisek@gmail.com>
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
    die('<h2>This script can\'t be run from a web browser. Use CLI to run it -> php BOT.php</h2>');
}
//---------------------------------------------------------------------------------------------------------
function CheckCLIArgs()
{
    if (isset($_SERVER['argv'][1])) {
        switch ($_SERVER['argv'][1]) {
            case '-h': /* show help */
                echo PHP_EOL.'  '.TR_62.PHP_EOL.PHP_EOL,
                     '  -c '.TR_63.PHP_EOL,
                     '  -p '.TR_64.PHP_EOL,
                     '  -s '.TR_65.PHP_EOL,
                     '  -v '.TR_66.PHP_EOL,
                     '  -h '.TR_67.PHP_EOL.PHP_EOL;
                die();
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
