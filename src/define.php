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
    define('VER', '1.1.7');
//---------------------------------------------------------------------------------------------------------
    error_reporting(-1);
    
    /* PHP_EOL shortcuts */
    define('N', PHP_EOL);
    define('NN', PHP_EOL.N);

    /* directory names */
    define('LOGSDIR', 'LOGS');
    define('DATADIR', 'DATA');
    define('SEENDIR', 'SEEN');
    define('PLUGINSDIR', 'plugins');

    /* config filename */
    define('CONFIGFILE', 'CONFIG.INI');

    define('START_TIME', time());
    define('PHP_VER', phpversion());
    define('PLUGIN_HASH', 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c');
    define('DEFAULT_PWD', '47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed');
    
    /* core commands count */
    define('CORECOMMANDSLIST', ['load'    => 'Loads specified plugins to BOT: !load <plugin>',
                                'pause'   => 'Pause all BOT activity: !pause',
                                'seen'    => 'Check specified user when was last seen on channel: !seen <nickname>',
                                'unload'  => 'Unloads specified plugin from BOT: !unload <plugin>',
                                'unpause' => 'Restore BOT from pause mode: !unpause']);
    define('CORECOUNT', '5');
    
    /* check version url */
    define('VERSION_URL', 'https://raw.githubusercontent.com/S3x0r/version-for-BOT/master/VERSION.TXT');
