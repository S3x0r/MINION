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

    $VERIFY             = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = "Connect to specified server: ".loadValueFromConfigFile('COMMAND', 'command.prefix')."server <server ip>";
    $plugin_command     = 'server';

function plugin_server()
{
    if (OnEmptyArg('server <server port>')) {
    } elseif (!empty(msgAsArguments()) && !empty(msgPieces()[1]) && is_numeric(msgPieces()[1])) {
              $GLOBALS['disconnected'] = 'yes';
              
              cliLog("[bot] Changing server to: ".msgPieces()[0].":".msgPieces()[1]);

              toServer("QUIT :Changing server...");
  
              if (ifWindowsOs()) {
                  chdir('src/php');
                  runProgram('start php.exe ../../BOT.php -o '.msgPieces()[0].' '.msgPieces()[1]);
                  exit;
              } else {
                       runProgram('php BOT.php -o '.msgPieces()[0].' '.msgPieces()[1]);
                       exit;
              }

        } elseif (empty(msgAsArguments())) {
                  response('You need to specify server address.');
        } elseif (empty(msgPieces()[1])) {
                  response('You need to specify server port.');
        } elseif (!is_numeric(msgPieces()[1])) {
                  response('Wrong server port.');
        }
}
