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
    $plugin_description = "Changing string to choosed algorithm: ".loadValueFromConfigFile('COMMAND', 'command.prefix')."hash help to list algorithms";
    $plugin_command     = 'hash';

/*
TODO: if server message limit -cut and send in parts
*/

function plugin_hash()
{
    if (OnEmptyArg('hash help to get algorithms list')) {
    } elseif (msgAsArguments() == 'help') {
              response("Usage: ".loadValueFromConfigFile('COMMAND', 'command.prefix')."hash <algorithm> <string>");
              response('Algos: '.implode(' ', hash_algos()));
    } elseif (in_array(msgPieces()[0], hash_algos())) {
              response(msgPieces()[0].": ".hash(msgPieces()[0], inputFromLine(5)));
    } else {
              response('Unknown hashing algorithm.');
    }
}