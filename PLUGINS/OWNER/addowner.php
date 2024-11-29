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
    $plugin_description = 'Add owner host to config: '.commandPrefix().'addowner <nick!ident@hostname>';
    $plugin_command     = 'addowner';

function plugin_addowner()
{
    $newOwnerNick = explode('!', trim(commandFromUser()));
    $newOwnerNick = $newOwnerNick[0];

    if (OnEmptyArg('addowner <nick!ident@hostname>')) {
    } elseif ($newOwnerNick != getBotNickname() && $newOwnerNick != userNickname()) {
        if (preg_match('/^(.+?)!(.+?)@(.+?)$/', commandFromUser())) {
            $botOwnersConfig = loadValueFromConfigFile('PRIVILEGES', getOwnerUserName());

            if (strpos($botOwnersConfig, commandFromUser()) !== false) {
                response('I already have this host.');
            } else {
                /* add user to owner's host's */
                empty($botOwnersConfig) ? $newList = commandFromUser() : $newList = $botOwnersConfig.', '.commandFromUser();

                saveValueToConfigFile('PRIVILEGES', getOwnerUserName(), $newList);

                /* add user to auto op list */
                $autoOpList = loadValueFromConfigFile('AUTOMATIC', 'auto op list');

                if (strpos($autoOpList, commandFromUser()) === false) {
                    empty($autoOpList) ? $newAutoOpList = commandFromUser() : $newAutoOpList = $autoOpList.', '.commandFromUser();

                    saveValueToConfigFile('AUTOMATIC', 'auto op list', $newAutoOpList);
                }

                /* inform user about it */
                privateMsgTo($newOwnerNick, 'From now you are on my owner(s)/auto op(s) lists, enjoy.');
                privateMsgTo($newOwnerNick, 'Core Plugins: '.allPluginsString());
                privateMsgTo($newOwnerNick, 'All Plugins: '.allPluginsWithoutCoreString());

                /* give op */
                if (BotOpped()) {
                    bot_op_user($newOwnerNick);
                }

                response("Host: '".commandFromUser()."' added to owner(s) list.");
            }
        } else {
                 response('Bad input, try: nick!ident@hostname');
        }
    } else {
             response('nope.');
    }
}
