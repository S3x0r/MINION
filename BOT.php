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
PHP_SAPI !== 'cli' ? exit('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
                           Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>') : false;
//---------------------------------------------------------------------------------------------------------

define('N', PHP_EOL);

if (dirname($_SERVER['PHP_SELF']) == '../..') {
    chdir('../../');
}

if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') {
    $GLOBALS['OS'] = 'Linux';
}
    
$files = ['cli.php',
          'misc.php',
		  'config.php',
          'core_commands.php',
          'debug.php',
          'define.php',
          'events.php',
          'logo.php',
          'logs.php',
          'plugins.php',
          'socket.php',
          'timers.php',
          'web.php',
	      'ctcp.php'
           ];

foreach ($files as $file) {
    if (is_file("src/{$file}")) {
        require_once("src/{$file}");
    } else {
		     echo N;
             echo "  ERROR: I need a file '{$file}' to work!".N.N,
			      '  You can download missing files from:'.N,
                  '  https://github.com/S3x0r/MINION/releases'.N.N,
                  '  Terminating program after 10 seconds.'.N.N.'  ';
             WinSleep(10);
             exit;
    }
}
//---------------------------------------------------------------------------------------------------------
    /* Check if we got cli args */
    CheckCliArgs();
    
    /* Load config */
    LoadConfig();

	/* logging init */
    Logs();

	/* Load plugins */
	LoadPlugins();

    /* Time to connect */
    Connect();
