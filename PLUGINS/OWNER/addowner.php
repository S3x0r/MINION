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
    $plugin_description = "Add owner host to config: ".loadValueFromConfigFile('COMMAND', 'command.prefix')."addowner <nick!ident@hostname>";
    $plugin_command     = 'addowner';

function plugin_addowner()
{
    $nick_ex = explode('!', trim(msgAsArguments()));

    if (OnEmptyArg('addowner <nick!ident@hostname>')) {
    } elseif ($nick_ex[0] != getBotNickname()) {
        if (preg_match('/^(.+?)!(.+?)@(.+?)$/', msgAsArguments())) {
            $botOwners = loadValueFromConfigFile('PRIVILEGES', getOwnerUserName());

            if (strpos($botOwners, msgAsArguments()) !== false) {
                response('I already have this host.');
            } else {
                /* add user to owner's host's */
                empty($botOwners) ? $new_list = msgAsArguments() : $new_list = "{$botOwners}, ".msgAsArguments();

                SaveValueToConfigFile('PRIVILEGES', getOwnerUserName(), $new_list);

                /* add user to auto op list */
                $autoOpList = loadValueFromConfigFile('AUTOMATIC', 'auto.op.list');

                if (strpos($autoOpList, msgAsArguments()) === false) {
                    empty($autoOpList) ? $newAutoOpList = msgAsArguments() : $newAutoOpList = $autoOpList.', '.msgAsArguments();

                    SaveValueToConfigFile('AUTOMATIC', 'auto.op.list', $newAutoOpList);
                }

                /* inform user about it */
                privateMsgTo($nick_ex[0], "From now you are on my owner(s)/auto op(s) lists, enjoy.");

                $prefix = loadValueFromConfigFile('COMMAND', 'command.prefix');
                $plugs = null;
            
                foreach (CORECOMMANDSLIST as $coreCommand => $coreCmdInfo) {
                    $plugs .= $prefix.$coreCommand.' ';
                }
            
                privateMsgTo($nick_ex[0], "Core Plugins: ".$plugs);
            
                $allPlugins = implode(' ', $GLOBALS['ALL_PLUGINS']);
                $allPlugins = str_replace(' ', " $prefix", $allPlugins);
            
                privateMsgTo($nick_ex[0], $prefix.$allPlugins);

                response("Host: '".msgAsArguments()."' added to owner list.");
            }
        } else {
                 response('Bad input, try: nick!ident@hostname');
        }
    } else {
             response('I cannot add myself to owners, iam already master.');
    }
}
