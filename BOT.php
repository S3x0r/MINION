<?php
/* Copyright (c) 2013-2024, minions
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

 /* checks if the bot was started from the PHP provided by the program */
 dirname($_SERVER['PHP_SELF']) == '../..' ? chdir('../../') : false;

 /* simple os check */
 function ifWindowsOs()
 {
     if (PHP_OS == 'WINNT') {
         return true;
     } else {
              return false;
     }
 }

 /* hide cli prompt */
 echo "\e[?25l";

 /* needed files */
 $botCoreFiles = ['define.php',
                  'args.php',
                  'cli.php',
                  'misc.php',
                  'config.php',
                  'start.php',
                  'core_cmnds.php',
                  'core_cmnds_helpers.php',
                  'bot_events.php',
                  'user_events.php',
                  'logs.php',
                  'plugins.php',
                  'socket.php',
                  'numeric_events.php',
                  'word_events.php',
                  'timers.php',
                  'ctcp.php'
                 ];

 /* checks if we have all the files */
 foreach ($botCoreFiles as $botCoreFile) {
     if (is_file("src/{$botCoreFile}")) {
         include("src/{$botCoreFile}");
     } else {
              echo "\n";
              echo "  I need a file '{$botCoreFile}' to work!\n\n",
                   "  You can download missing files from:\n",
                   "  https://github.com/S3x0r/MINION/releases\n\n",
                   "  Terminating BOT after 10 seconds.\n\n";
              (ifWindowsOs()) ? sleep(10) : false;
              exit;
     }
 }

/* if we cannot write */
if (!is_writable('BOT.php')) {
    echo N.' Bot has no permissions to save files, Check your permissions! Exiting.';
    winSleep(7);
    exit;
} else {
         startBot();
}
