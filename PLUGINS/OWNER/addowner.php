<?php
/* Copyright (c) 2013-2017, S3x0r <olisek@gmail.com>
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

if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}
    
    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Adds Owner host to config file: '
    .$GLOBALS['CONFIG_CMD_PREFIX'].'addowner <nick!ident@hostname>';
    $plugin_command = 'addowner';

function plugin_addowner()
{
    $nick_ex = explode('!', trim($GLOBALS['args']));

    if (OnEmptyArg('addowner <nick!ident@hostname>')) {
    } elseif ($nick_ex[0] != $GLOBALS['BOT_NICKNAME']) {
        if (preg_match('/^(.+?)!(.+?)@(.+?)$/', $GLOBALS['args'], $host)) {
            LoadData($GLOBALS['config_file'], 'ADMIN', 'bot_owners');
 
            $owners_list = $GLOBALS['LOADED'];
            $new         = $host[0];
            if ($owners_list == '') {
                $new_list = $new.'';
            }
            if ($owners_list != '') {
                $new_list = $owners_list.', '.$new;
            }
            SaveData($GLOBALS['config_file'], 'ADMIN', 'bot_owners', $new_list);

            /* update variable with new owners */
            $cfg = new IniParser($GLOBALS['config_file']);
            $GLOBALS['CONFIG_OWNERS'] = $cfg->get("ADMIN", "bot_owners");

            /* inform nick about it */
            $owner_commands = implode(' ', $GLOBALS['OWNER_PLUGINS']);
            $user_commands  = implode(' ', $GLOBALS['USER_PLUGINS']);

            fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :From now you are on my owners list, enjoy.\n");
            fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :Core Commands: ".
                $GLOBALS['CONFIG_CMD_PREFIX']."load ".$GLOBALS['CONFIG_CMD_PREFIX']."unload ".
                $GLOBALS['CONFIG_CMD_PREFIX']."panel\n");
            fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :Owner Commands: $owner_commands\n");
            fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :User Commands: $user_commands\n");
 
            CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'addowner on: '.$GLOBALS['channel'].', by: '
            .$GLOBALS['USER'].', OWNER ADDED: '.$host[0], '1');

            BOT_RESPONSE('Host: \''.$host[0].'\' added to owners.');
        } else {
                 BOT_RESPONSE('Bad input, try: nick!ident@hostname');
        }
    } else {
             BOT_RESPONSE('I cannot add myself to owners, im already master :)');
    }
}
