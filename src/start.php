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

function Start()
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
      
   /* Load config - config.php */
   if (isset($GLOBALS['configFile'])) { /* args already checks if file exists */
       LoadConfig();
   } else {
            $GLOBALS['configFile'] = CONFIGFILE;

            /* if config file exists */
            if (is_file($GLOBALS['configFile'])) {
                LoadConfig();
            } else {
                     cliLog('[bot] Config file missing! Creating default config in: '.CONFIGFILE.N);
                     CreateDefaultConfig(CONFIGFILE);

                     /* Load config again */
                     if (is_file(CONFIGFILE)) {
                         LoadConfig();
                     } else { /* read only file system? */
                              cliLog('[bot]: Error! Cannot make default config! Read-Only filesystem? Exiting.');
                              WinSleep(6);
                              exit;
                     }
            }
   }

   /* check if we got default owner password, if yes -> change it - misc.php */
   if ($GLOBALS['CONFIG.OWNER.PASSWD'] == DEFAULT_PWD) {
       PlaySound('error_conn.mp3');

       cliLog('[bot] Default BOT owner(s) password detected!');
       cliLog('[bot] For security please change it (password can not contain spaces)');
     
       changeDefaultOwnerPwd();
   }
  
   /* Logging init - logs.php */
   if ($GLOBALS['CONFIG.LOGGING'] == 'yes' && is_dir(LOGSDIR)) {
       LogsInit();
   }
  
   /* Load plugins - plugins.php */
   LoadPlugins();
  
   /* check if panel is not closed */
   if (isRunned('serv')) {
       kill('serv') ? cliLog('[bot] Detected Panel still running, Terminating.') : false;
   }

   /* Time to connect */
   cliLog("[bot] Connecting to: {$GLOBALS['CONFIG.SERVER']}, port: {$GLOBALS['CONFIG.PORT']}\n");
  
   /* socket.php */
   if (tryToConnect()) {
       if (Identify()) {
           /* main loop */
           SocketLoop();
       }
   }
}
