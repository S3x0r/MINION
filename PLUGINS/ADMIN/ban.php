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
    $plugin_description = 'Ban specified host: '.commandPrefix().'ban <nick!ident@hostname>';
    $plugin_command     = 'ban';

function plugin_ban()
{
    if (OnEmptyArg('ban <nick!ident@hostname>')) {
    } elseif (BotOpped()) {
              /* if nick!ident@hostname */
              if (preg_match('/^(.*)\!(.*)\@(.*)$/', commandFromUser(), $data)) {
                  $fullmask  = $data[0];
                  $nickToBan = $data[1];

                  if ($nickToBan != getBotNickname() && $nickToBan != userNickname()) {
                      /* ban host */
                      toServer('MODE '.getBotChannel().' +b '.$fullmask);

                      if (!in_array($fullmask, loadValueFromConfigFile('BANS', 'ban list'))) {
                          saveValueToListConfigFile('BANS', 'ban list', $fullmask); 
                              
                          response("Host: '{$fullmask}' added to ban list.");
                      } else {
                               response('I already have that host in my ban list.');
                      }
                  } else {
                           response('nope.');
                  }
              } else {
                       response('Bad input, try: ban <nick!ident@hostname>');
              }
    }
}
