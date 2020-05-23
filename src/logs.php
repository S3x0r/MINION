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

function Logs()
{
    if (!isSilent() && $GLOBALS['CONFIG_LOGGING'] == 'yes') {
        global $logFile;

        if (!is_dir('LOGS')) {
            mkdir('LOGS');
        }

        /* + computer name to prevent fetch file from panel server */
        !empty($_SERVER['COMPUTERNAME']) ? $a = $_SERVER['COMPUTERNAME'] : $a = gethostname();

        $logFile = "LOGS/".date('Y.m.d').",{$a}.txt";

        $data = "-------------------------LOG CREATED: ".date('d.m.Y | H:i:s')."-------------------------\r\n";

        SaveToFile($logFile, $data, 'a');
    }
}
