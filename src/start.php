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

function startBot()
{
    /* check that the arguments from cli have been given (args.php file) */
    if (isset($_SERVER['argv'][1])) {
        checkCliArguments();
    }

    /* info baner (cli.php file) */
    baner();

    /* config checks (config.php file) */
    if (checkIfConfigExists() == true) {
        checkIfConfigIsValid(getConfigFileName());
    } else {
             cliNoLog('Configuration file missing! I am creating a default configuration: '.getConfigFileName().N);
             createDefaultConfigFile();
    }

    /* egg */
	in_array(@date('dm'), array('0112', '2412')) ? playSound('egg.mp3') : false;

    /* set timezone from config file */
    setTimezone();

    /* if directories are missing create them */
    checkDirectioriesIfExists();
 
    cliBot('Configuration Loaded from: '.getConfigFileName());

    cliLine();

    /* checks if the owner's default password is set (misc.php file) */
    if (loadValueFromConfigFile('OWNER', 'owner password') == DEFAULT_PWD) {
        cliBot('Owner\'s default password detected!');
        cliBot('For security, please change the owner\'s password (Password must not contain spaces)');
 
        playSound('error_conn.mp3');

        changeDefaultOwnerPwd();
    }

    /* Load plugins (plugins.php file) */
    loadPlugins();

    /* ctrl+c ctrl+break handler */
    if (ifWindowsOs()) {
        sapi_windows_set_ctrl_handler('ctrl_handler');
    }

    connect();
}
