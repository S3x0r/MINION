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
    $plugin_description = "Add host to admin list in config: {$GLOBALS['CONFIG_CMD_PREFIX']}addadmin <nick!ident@host>";
    $plugin_command     = 'addadmin';

function plugin_addadmin()
{
    $nick = explode('!', trim($GLOBALS['args']));

    if (OnEmptyArg('addadmin <nick!ident@hostname>')) {
    } elseif ($nick[0] != getBotNickname()) {
        if (preg_match('/^(.+?)!(.+?)@(.+?)$/', $GLOBALS['args'], $host)) {
            LoadData($GLOBALS['configFile'], 'ADMIN', 'admin_list');
 
            if (strpos($GLOBALS['LOADED'], $GLOBALS['args']) !== false) {
                response('I already have this host.');
            } else {
                empty($GLOBALS['LOADED']) ? $new_list = $host[0] : $new_list = "{$GLOBALS['LOADED']}, {$host[0]}";

                SaveData($GLOBALS['configFile'], 'ADMIN', 'admin_list', $new_list);

                /* update variable with new owners */
                $cfg = new IniParser($GLOBALS['configFile']);
                $GLOBALS['CONFIG_ADMIN_LIST'] = $cfg->get("ADMIN", "admin_list");

                /* inform user about adding */
                toServer("PRIVMSG {$nick[0]} :From now you are on my ADMIN(S) list, enjoy.");
                toServer("PRIVMSG {$nick[0]} :Core Commands: {$GLOBALS['CONFIG_CMD_PREFIX']}seen");
                toServer("PRIVMSG {$nick[0]} :Admin Commands: ".implode(' ', $GLOBALS['ADMIN_PLUGINS']));
                toServer("PRIVMSG {$nick[0]} :User Commands: ".implode(' ', $GLOBALS['USER_PLUGINS']));

                response("Host: '{$host[0]}' added to admin list.");
            }
        } else {
                 response('Bad input, try: nick!ident@hostname');
        }
    } else {
             response("I'm already a master!");
    }
}
