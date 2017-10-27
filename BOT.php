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
//---------------------------------------------------------------------------------------------------------
if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use CLI to run it -> php BOT.php</h2>');
}
//---------------------------------------------------------------------------------------------------------
    /* change directory from php to src */
    chdir('../');

$files = array('cli.php',
               'config.php',
               'core_commands.php',
               'ctcp.php',
               'debug.php',
               'define.php',
               'events.php',
               'language.php',      
               'logo.php',
               'logs.php',
               'misc.php',           
               'plugins.php',
               'socket.php',
               'timers.php',
               'web.php'
              );

foreach ($files as $file) {
    if (is_file($file)) {
        require($file);
    } else {
             echo PHP_EOL.'  ERROR: I need \''.$file.'\' file to run!',
             ' Terminating program after 5 seconds.'.PHP_EOL;
             sleep(5);
             die();
    }
}
//---------------------------------------------------------------------------------------------------------

/* let's go! */

    /* check os type and set path */
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    } else {
             chdir('.');
             $GLOBALS['OS_TYPE'] = 'other';
    }

    /* load some needed variables */
    if (is_file('../CONFIG.INI')) {
        $config_file = '../CONFIG.INI';
        $cfg = new IniParser($config_file);
        $GLOBALS['CONFIG_SHOW_LOGO'] = $cfg->get('PROGRAM', 'show_logo');
        $GLOBALS['silent_mode'] = $cfg->get('PROGRAM', 'silent_mode');
        $GLOBALS['CONFIG_CHECK_UPDATE'] = $cfg->get('PROGRAM', 'check_update');
    } else {
             $GLOBALS['CONFIG_SHOW_LOGO'] = 'yes';
             $GLOBALS['silent_mode'] = 'no';
             $GLOBALS['CONFIG_CHECK_UPDATE'] = 'no';
    }

    /* Load translation file */
    SetLanguage();

    /* Check if cli arguments */
    CheckCLIArgs();
    
    /* wcli extension */
    if (extension_loaded('wcli')) {
        if (!IsSilent()) {
            wcli_maximize();
            wcli_set_console_title('MINION '.VER);
            wcli_hide_cursor();
        }
    }

    /* Logo & info :) */
    if (!IsSilent()) {
        if ($GLOBALS['CONFIG_SHOW_LOGO'] == 'yes' or empty($GLOBALS['CONFIG_SHOW_LOGO'])) {
            /* show logo */
            logo();
        }
    }

    if (!IsSilent()) {
        echo "
    MINION - ver: ".VER.", ".TR_10." S3x0r, ".TR_11." olisek@gmail.com
                   ".TR_12." ".TotalLines()." :)
    ".PHP_EOL.PHP_EOL;
    }
    
    /* check if new version on server */
    if ($GLOBALS['CONFIG_CHECK_UPDATE'] == 'yes') {
        $url = 'https://raw.githubusercontent.com/S3x0r/version-for-BOT/master/VERSION.TXT';
        $CheckVersion = @file_get_contents($url);
        
        if ($CheckVersion !='') {
            $version = explode("\n", $CheckVersion);
            if ($version[0] > VER) {
                echo "             >>>> New version available! ($version[0]) <<<<".PHP_EOL.PHP_EOL.PHP_EOL;
            } else {
                     echo "       >>>> No new update, you have the latest version <<<<".PHP_EOL.PHP_EOL.PHP_EOL;
            }
        } else {
                 echo "            >>>> Cannot connect to update server <<<<".PHP_EOL.PHP_EOL.PHP_EOL;
        }
    }

    /* try to load config */
    LoadConfig('../CONFIG.INI');
