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
    $plugin_description = "Add owner host to config: {$GLOBALS['CONFIG_CMD_PREFIX']}addowner <nick!ident@hostname>";
    $plugin_command     = 'addowner';

function plugin_addowner()
{
    $nick_ex = explode('!', trim($GLOBALS['args']));

    if (OnEmptyArg('addowner <nick!ident@hostname>')) {
    } elseif ($nick_ex[0] != getBotNickname()) {
        if (preg_match('/^(.+?)!(.+?)@(.+?)$/', $GLOBALS['args'], $host)) {
            LoadData($GLOBALS['configFile'], 'OWNER', 'bot_owners');
 
            if (strpos($GLOBALS['LOADED'], $GLOBALS['args']) !== false) {
                response('I already have this host.');
            } else {
                /* add user to owner's host's */
                empty($GLOBALS['LOADED']) ? $new_list = $host[0] : $new_list = "{$GLOBALS['LOADED']}, {$host[0]}";

                SaveData($GLOBALS['configFile'], 'OWNER', 'bot_owners', $new_list);

                /* add user to auto op list */
                LoadData($GLOBALS['configFile'], 'OWNER', 'auto_op_list');

                empty($GLOBALS['LOADED']) ? $new_list = $host[0] : $new_list = "{$GLOBALS['LOADED']}, {$host[0]}";

                SaveData($GLOBALS['configFile'], 'OWNER', 'auto_op_list', $new_list);

                /* update variables with new owners/autoop list */
                $cfg = new IniParser($GLOBALS['configFile']);
                $GLOBALS['CONFIG_OWNERS']       = $cfg->get("OWNER", "bot_owners");
                $GLOBALS['CONFIG_AUTO_OP_LIST'] = $cfg->get('OWNER', 'auto_op_list');

                /* inform user about it */
                toServer("PRIVMSG {$nick_ex[0]} :From now you are on my owner(s)/auto op(s) lists, enjoy.");

                toServer("PRIVMSG {$nick_ex[0]} :Core Commands: ".
                         $GLOBALS['CONFIG_CMD_PREFIX']."load ".
                         $GLOBALS['CONFIG_CMD_PREFIX']."panel ".
                         $GLOBALS['CONFIG_CMD_PREFIX']."pause ".
                         $GLOBALS['CONFIG_CMD_PREFIX']."seen ".
                         $GLOBALS['CONFIG_CMD_PREFIX']."unload ".
                         $GLOBALS['CONFIG_CMD_PREFIX']."unpause");

                toServer("PRIVMSG {$nick_ex[0]} :Owner Commands: ".implode(' ', $GLOBALS['OWNER_PLUGINS']));
                toServer("PRIVMSG {$nick_ex[0]} :User Commands: ".implode(' ', $GLOBALS['USER_PLUGINS']));

                response("Host: '{$host[0]}' added to owner list.");
            }
        } else {
                 response('Bad input, try: nick!ident@hostname');
        }
    } else {
             response('I cannot add myself to owners, im already master :)');
    }

    cliLog("[PLUGIN: addowner] Used by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
}
