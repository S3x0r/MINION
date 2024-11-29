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

    $VERIFY             = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Adds host to autoop list in config file: '.commandPrefix().'autoop <nick!ident@host>';
    $plugin_command     = 'autoop';

function plugin_autoop()
{
    if (OnEmptyArg('autoop <nick!ident@hostname>')) {
    } else {
             /* if nick!ident@hostname */
             if (preg_match('/^(.*)\!(.*)\@(.*)$/', commandFromUser(), $data)) {
                 $fullmask = $data[0];
                 $nick = $data[1];

                 if ($nick != getBotNickname() && $nick != userNickname()) {
                     $autoOpList = loadValueFromConfigFile('AUTOMATIC', 'auto op list');

                     /* if not in config */
                     if (strpos($autoOpList, $fullmask) === false) {
                         empty($autoOpList) ? $newList = $fullmask : $newList = "{$autoOpList}, {$fullmask}";
 
                         saveValueToConfigFile('AUTOMATIC', 'auto op list', $newList);

                         privateMsgTo($nick, 'From now you are on my auto op list, enjoy.');

                         if (BotOpped()) {
                             toServer('MODE '.getBotChannel().' +o '.$nick);
                         }

                         response("Host: '{$fullmask}' added to auto op list.");
                     } else {
                              response('I already have that host in my auto op list.');
                     }
                 } else {
                          response('nope.');
                 }
             } else {
                      response('Bad input, try: nick!ident@hostname');
             }
    }
}
