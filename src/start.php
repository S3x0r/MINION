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

function StartBot()
{
    /* if no dirs -> create */
    !is_dir(LOGSDIR) ? mkdir(LOGSDIR) : false;
    !is_dir(DATADIR) ? mkdir(DATADIR) : false;
 
    /* Check if we got cli args - args.php */
    if (isset($_SERVER['argv'][1])) {
        checkArgs();
    }
 
    /* info */
    Baner();
 
    /* Check if there is new version */
    CheckUpdateInfo();
       
    /* if no config -> create default one */
    if (!is_file(getConfigFileName())) {
        cli('[WARNING] Config file missing! Creating default config in: '.getConfigFileName().N);
        CreateDefaultDataConfigFile();
        
        if (!is_file(getConfigFileName())) {
            cli('[ERROR]: Error! Cannot make default config! Read-Only filesystem? Exiting.');
            WinSleep(6);
            exit;
        }
    }
 
    /* set timezone from config */
    if (!empty(loadValueFromConfigFile('TIME', 'time.zone'))) {
        date_default_timezone_set(loadValueFromConfigFile('TIME', 'time.zone'));
    }
 
    /* Logging init - logs.php */
    if (loadValueFromConfigFile('LOGS', 'logging') == 'yes' && is_dir(LOGSDIR)) {
        LogsInit();
    }
 
    cliLog('Configuration Loaded from: '.getConfigFileName());
 
    cliLine();
 
    /* check if we got default owner password, if yes -> change it - misc.php */
    if (loadValueFromConfigFile('OWNER', 'owner.password') == DEFAULT_PWD) {
        PlaySound('error_conn.mp3');
 
        cliLog('[WARNING] Default BOT owner(s) password detected!');
        cliLog('[WARNING] For security please change it (password can not contain spaces)');
      
        changeDefaultOwnerPwd();
    }
   
    /* Load plugins - plugins.php */
    LoadPlugins();
   
    /* Time to connect */
    cliLog("[bot] Connecting to: ".loadValueFromConfigFile('SERVER', 'server').", port: ".loadValueFromConfigFile('SERVER', 'port')."\n");
   
    /* socket.php */
    if (tryToConnect()) {
        if (Identify()) {
            /* main loop */
            SocketLoop();
        }
    }
}
