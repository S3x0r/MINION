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
    $plugin_description = 'Adds host to autoop list in config file: '
    .$GLOBALS['CONFIG_CMD_PREFIX'].'autoop <nick!ident@host>';
    $plugin_command = 'autoop';

function plugin_autoop()
{
    $nick_ex = explode('!', trim($GLOBALS['args']));

    if (OnEmptyArg('autoop <nick!ident@hostname>')) {
    } elseif ($nick_ex[0] != $GLOBALS['BOT_NICKNAME']) {
        if (preg_match('/^(.+?)!(.+?)@(.+?)$/', $GLOBALS['args'], $host)) {
            LoadData($GLOBALS['config_file'], 'OWNER', 'auto_op_list');

            if (strpos($GLOBALS['LOADED'], $GLOBALS['args']) !== false) {
                BOT_RESPONSE('I already have this host.');
            } else {
                     $auto_list   = $GLOBALS['LOADED'];
                     $new         = $host[0];

                empty($auto_list) ? $new_list = $new : $new_list = $auto_list.', '.$new;
 
                     SaveData($GLOBALS['config_file'], 'OWNER', 'auto_op_list', $new_list);

                     /* update variable with new owners */
                     $cfg = new IniParser($GLOBALS['config_file']);
                     $GLOBALS['CONFIG_AUTO_OP_LIST'] = $cfg->get("OWNER", "auto_op_list");

                     /* Inform nick about it */
                     fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0].
                           " :From now you are on my auto op list, enjoy.\n");

                     BOT_RESPONSE('Host: \''.$host[0].'\' added to auto op list.');

                     CLI_MSG('[PLUGIN: autoop] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                             $GLOBALS['channel'].' | host added: '.$GLOBALS['args'], '1');
            }
        } else {
                 BOT_RESPONSE('Bad input, try: nick!ident@hostname');
        }
    } else {
             BOT_RESPONSE('I cannot add myself to auto op list, im already OP MASTER :)');
    }
}
