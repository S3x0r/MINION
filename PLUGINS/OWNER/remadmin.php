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

if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
         Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>');
}
    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Removes admin from config file: '
    .$GLOBALS['CONFIG_CMD_PREFIX'].'remadmin <nick!ident@hostname>';
    $plugin_command = 'remadmin';

function plugin_remadmin()
{
    if (OnEmptyArg('remadmin <nick!ident@hostname>')) {
    } else {
        if (preg_match('/^(.+?)!(.+?)@(.+?)$/', $GLOBALS['args'], $host)) {
            /* read owners from config */
            LoadData($GLOBALS['config_file'], 'ADMIN', 'admin_list');
            $admin_list = $GLOBALS['LOADED'];
            $array = explode(" ", str_replace(',', '', $admin_list));

            $key = array_search($GLOBALS['args'], $array);
       
            if ($key !== false) {
                /* remove from host from array */
                unset($array[$key]);
                      
                /* new owners string */
                $string = implode(' ', $array);
                $string2 = str_replace(' ', ', ', $string);

                /* save new list to config */
                SaveData($GLOBALS['config_file'], 'ADMIN', 'admin_list', $string2);

                /* update variable with new owners */
                $cfg = new IniParser($GLOBALS['config_file']);
                $GLOBALS['CONFIG_ADMIN_LIST'] = $cfg->get("ADMIN", "admin_list");

                /* send info to user */
                BOT_RESPONSE('Host: \''.$GLOBALS['args'].'\' removed from admin list.');

                /* & to CLI */
                CLI_MSG('[PLUGIN: remadmin] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                        $GLOBALS['channel'].' | removed host: '.$GLOBALS['args'], '1');
            } else {
                     BOT_RESPONSE('No such host in my list.');
            }
        } else {
                 BOT_RESPONSE('Bad input, try: nick!ident@hostname');
        }
    }
}
