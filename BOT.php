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

 /* check if the bot was launched from the attached php */
 dirname($_SERVER['PHP_SELF']) == '../..' ? chdir('../../') : false;

 /* simple os check */
 /* PHP 7.2.0 we have PHP_OS_FAMILY */
 strtoupper(substr(PHP_OS, 0, 3)) != 'WIN' ? $GLOBALS['OS'] = 'Linux' : false;

 /* hide prompt */
 echo "\e[?25l";

 /* needed files */
 $botCoreFiles = ['define.php',
                  'args.php',
                  'cli.php',
                  'misc.php',
                  'config.php',
                  'core_commands.php',
                  'debug.php',
                  'events.php',
                  'logs.php',
                  'plugins.php',
                  'socket.php',
                  'timers.php',
                  'web.php',
                  'ctcp.php',
                  'start.php'
                 ];

 /* check if we got all files */
 foreach ($botCoreFiles as $botCoreFile) {
     if (is_file("src/{$botCoreFile}")) {
         require_once("src/{$botCoreFile}");
     } else {
              echo "\n";
              echo "  I need a file '{$botCoreFile}' to work!\n\n",
                   "  You can download missing files from:\n",
                   "  https://github.com/S3x0r/MINION/releases\n\n",
                   "  Terminating program after 10 seconds.\n\n";
              !isset($GLOBALS['os']) ? sleep(10) : false;
              exit;
     }
 }

    /* if we cannot write */
    if (!is_writable('BOT.php')) {
        echo "\n Bot has no permissions to save files, Check your permissions! Exiting.";
        WinSleep(7);
        exit;
    } else {
             Start();
    }
