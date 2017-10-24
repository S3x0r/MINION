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

    /* change directory from php to src */
    chdir('../');

/* include main.php file */
if (is_file('main.php')) {
    require('main.php');
} else {
         echo PHP_EOL.'  ERROR: I need \'MAIN.PHP\' file in \'src\' directory to run!'.PHP_EOL,
              '  Terminating program after 5 seconds.'.PHP_EOL.PHP_EOL;
         sleep(5);
         die();
}

 /* GO! --____^__^           o , . */


             /*   |  */
             /*   |  */
             /*   |  */
          /*  vvvvvvvv  */
/* -----> */  Start();  /* <----- */
          /*  ^^^^^^^^  */
             /*   |  */
             /*   |  */
             /*   |  */
