<?php
/* Copyright (c) 2013-2018, S3x0r <olisek@gmail.com>
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
PHP_SAPI !== 'cli' ? exit('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
                           Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>') : false;
//---------------------------------------------------------------------------------------------------------

    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Add host to admin list in config: '
    .$GLOBALS['CONFIG_CMD_PREFIX'].'addadmin <nick!ident@host>';
    $plugin_command = 'addadmin';

function plugin_addadmin()
{
    $nick_ex = explode('!', trim($GLOBALS['args']));

    if (OnEmptyArg('addadmin <nick!ident@hostname>')) {
    } elseif ($nick_ex[0] != $GLOBALS['BOT_NICKNAME']) {
        if (preg_match('/^(.+?)!(.+?)@(.+?)$/', $GLOBALS['args'], $host)) {
            LoadData($GLOBALS['config_file'], 'ADMIN', 'admin_list');
 
            if (strpos($GLOBALS['LOADED'], $GLOBALS['args']) !== false) {
                BOT_RESPONSE('I already have this host.');
            } else {
                     $admin_list = $GLOBALS['LOADED'];
                     $new         = $host[0];

                empty($admin_list) ? $new_list = $new : $new_list = $admin_list.', '.$new;

                SaveData($GLOBALS['config_file'], 'ADMIN', 'admin_list', $new_list);

                /* update variable with new owners */
                $cfg = new IniParser($GLOBALS['config_file']);
                $GLOBALS['CONFIG_ADMIN_LIST'] = $cfg->get("ADMIN", "admin_list");

                /* inform nick about it */
                $admin_commands = implode(' ', $GLOBALS['ADMIN_PLUGINS']);
                $user_commands  = implode(' ', $GLOBALS['USER_PLUGINS']);

                fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :From now you are on my ADMIN(S) list, enjoy.\n");
                fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :Core Commands: ".
                      $GLOBALS['CONFIG_CMD_PREFIX']."seen\n");
                fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :Admin Commands: $admin_commands\n");
                fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :User Commands: $user_commands\n");

                BOT_RESPONSE('Host: \''.$host[0].'\' added to admin list.');
            
                CLI_MSG('[PLUGIN: addadmin] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                        $GLOBALS['channel'].' | admin added: '.$host[0], '1');
            }
        } else {
                 BOT_RESPONSE('Bad input, try: nick!ident@hostname');
        }
    } else {
             BOT_RESPONSE('I cannot add myself to admins, im already master :)');
    }
}
