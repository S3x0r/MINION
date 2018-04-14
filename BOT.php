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
//---------------------------------------------------------------------------------------------------------
if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use CLI to run it -> php BOT.php</h2>');
}
//---------------------------------------------------------------------------------------------------------
/* check os type and set path */
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    chdir('../');
} else {
         chdir('src/');
         $GLOBALS['OS_TYPE'] = 'other';
}
    
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
        require_once($file);
    } else {
             echo PHP_EOL.'  ERROR: I need \''.$file.'\' file to run!'.PHP_EOL,
                  PHP_EOL.'  You can download missing files from:'.PHP_EOL,
                  '  https://github.com/S3x0r/MINION/releases'.PHP_EOL,
                  PHP_EOL.'  Terminating program after 10 seconds.'.PHP_EOL.PHP_EOL.'  ';
             sleep(10);
             die();
    }
}
//---------------------------------------------------------------------------------------------------------
    /* let's go! */

    /* Load startup needed variables */
    StartupConfig();

    /* Load translation file */
    SetLanguage();

    /* Check if cli arguments */
    CheckCLIArgs();
    
    /* wcli extension */
    wcliStart();

    /* Logo & info :) */
    Logo();
 
    /* Check if there is new version on server */
    CheckUpdateInfo();
   
    /* Load config */
    LoadConfig('../CONFIG.INI');
