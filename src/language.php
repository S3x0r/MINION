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
function SetLanguage()
{
/* TODO: More from this should be in StartupConfig() */

    /* is config file? */
    if (is_file('../CONFIG.INI')) {
        $config_file = '../CONFIG.INI';
        $cfg = new IniParser($config_file);
        $GLOBALS['CONFIG_LANGUAGE'] = $cfg->get("LANG", "language");

        /* if language is set in config */
        if (!empty($GLOBALS['CONFIG_LANGUAGE'])) {
            /* if is language from config in lang dir */
            if (is_file('lang/'.$GLOBALS['CONFIG_LANGUAGE'].'.php')) {
                require('lang/'.$GLOBALS['CONFIG_LANGUAGE'].'.php');
            } else {
                /* if no language file from config in lang dir, change to default 'EN' if is present */
                if (is_file('lang/EN.php')) {
                    CLI_MSG('ERROR: No such language: \''.$GLOBALS['CONFIG_LANGUAGE'].'\' in LANG dir', '0');
                    CLI_MSG('[BOT] Changing to default language: EN', '0');
                    require('lang/EN.php');
                } else {
                         /* if no language files */
                         no_lang_file();
                }
            } /* if not set language in config, set to default EN if is present */
        } elseif (empty($GLOBALS['CONFIG_LANGUAGE'])) {
                  $GLOBALS['CONFIG_LANGUAGE'] = 'EN';
            if (is_file('lang/EN.php')) {
                require('lang/EN.php');
            } else { /* else no language files is directory */
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
    echo PHP_EOL.PHP_EOL.'  ERROR: I need at least one translation in LANG directory to work! Exiting.'.PHP_EOL,
         PHP_EOL.'  You can download missing files from:'.PHP_EOL,
         '  https://github.com/S3x0r/MINION/releases'.PHP_EOL,
         PHP_EOL.'  Terminating program after 10 seconds.'.PHP_EOL.PHP_EOL.'  ';
    sleep(10);
    die();
}
