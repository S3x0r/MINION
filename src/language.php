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
function SetLanguage()
{
    /* is IS config file */
    if (is_file('../CONFIG.INI')) {
        $config_file = '../CONFIG.INI';
        $cfg = new IniParser($config_file);
        $GLOBALS['CONFIG_LANGUAGE'] = $cfg->get("LANG", "language");

        if (!empty($GLOBALS['CONFIG_LANGUAGE'])) {
            if (is_file('lang/'.$GLOBALS['CONFIG_LANGUAGE'].'.php')) {
                require('lang/'.$GLOBALS['CONFIG_LANGUAGE'].'.php');
            } else {
                if (is_file('lang/EN.php')) {
                    CLI_MSG('ERROR: No such language: \''.$GLOBALS['CONFIG_LANGUAGE'].'\' in LANG dir', '0');
                    CLI_MSG('[BOT] Changing to default language: EN', '0');
                    require('lang/EN.php');
                } else {
                         no_lang_file();
                }
            }
        } elseif (empty($GLOBALS['CONFIG_LANGUAGE'])) {
                  $GLOBALS['CONFIG_LANGUAGE'] = 'EN';
            if (is_file('lang/EN.php')) {
                require('lang/EN.php');
            } else {
                     no_lang_file();
            }
        }    /* if NO config file */
    } elseif (!is_file('../CONFIG.INI')) {
              $GLOBALS['CONFIG_LANGUAGE'] = 'EN';
        if (is_file('lang/EN.php')) {
            require('lang/EN.php');
        } else {
                 no_lang_file();
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function no_lang_file()
{
    echo PHP_EOL.PHP_EOL.'ERROR: I need at least one translation in LANG directory to work! Exiting.'.PHP_EOL.PHP_EOL;
    sleep(6);
    die();
}
