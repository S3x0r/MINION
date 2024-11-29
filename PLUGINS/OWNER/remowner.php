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
    $plugin_description = 'Removes owner from config file: '.commandPrefix().'remowner <nick!ident@hostname>';
    $plugin_command     = 'remowner';

function plugin_remowner()
{
    if (OnEmptyArg('remowner <nick!ident@hostname>')) {
    } else {
        if (preg_match('/^(.+?)!(.+?)@(.+?)$/', commandFromUser(), $data)) {
            if ($data[1] != getBotNickname() && $data[1] != userNickname()) {
                /* read owners from config */
                $ownersList = loadValueFromConfigFile('PRIVILEGES', getOwnerUserName());
                $array = explode(" ", str_replace(',', '', $ownersList));
                $key = array_search(commandFromUser(), $array);
          
                if ($key !== false) {
                    /* remove from host from array */
                    unset($array[$key]);
          
                    /* new owners string */
                    $string = implode(' ', $array);
                    $string2 = str_replace(' ', ', ', $string);
          
                    /* save new list to config */
                    saveValueToConfigFile('PRIVILEGES', getOwnerUserName(), $string2);
          
                    /* send info to user */
                    response("Host: '".commandFromUser()."' removed from owners.");
                } else {
                         response('No such host in my list.');
                }
            } else {
                     response('nope.');
            }
        } else {
                 response('Bad input, try: nick!ident@hostname');
        }
    }
}
