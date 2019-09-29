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
    $plugin_description = 'Shows BOT commands: '.$GLOBALS['CONFIG_CMD_PREFIX'].'help';
    $plugin_command = 'help';

function plugin_help()
{
    $owner_cmd = implode(' ', $GLOBALS['OWNER_PLUGINS']);
    $admin_cmd = implode(' ', $GLOBALS['ADMIN_PLUGINS']);
    $user_cmd  = implode(' ', $GLOBALS['USER_PLUGINS']);
    
    /* if OWNER use help */
    if (HasOwner($GLOBALS['mask'])) {
        BOT_RESPONSE('Core Commands: '.
                     $GLOBALS['CONFIG_CMD_PREFIX'].'load '.
                     $GLOBALS['CONFIG_CMD_PREFIX'].'panel '.
                     $GLOBALS['CONFIG_CMD_PREFIX'].'pause '.
                     $GLOBALS['CONFIG_CMD_PREFIX'].'seen '.
                     $GLOBALS['CONFIG_CMD_PREFIX'].'unload '.
                     $GLOBALS['CONFIG_CMD_PREFIX'].'unpause');

        BOT_RESPONSE('Owner Commands: '.$owner_cmd);
        BOT_RESPONSE('Admin Commands: '.$admin_cmd);
        BOT_RESPONSE('User Commands: '.$user_cmd);

      /* if ADMIN use help */
    } elseif (!HasOwner($GLOBALS['mask']) && HasAdmin($GLOBALS['mask'])) {
              BOT_RESPONSE('Core Commands: '.$GLOBALS['CONFIG_CMD_PREFIX'].'seen');
              BOT_RESPONSE('Admin Commands: '.$admin_cmd);
              BOT_RESPONSE('User Commands: '.$user_cmd);

        if (!empty($GLOBALS['CONFIG_BOT_ADMIN'])) {
            BOT_RESPONSE('Bot Admin: '.$GLOBALS['CONFIG_BOT_ADMIN']);
        }
      
      /* if USER use help */
    } elseif (!HasOwner($GLOBALS['mask']) && !HasAdmin($GLOBALS['mask'])) {
              BOT_RESPONSE('Core Commands: '.$GLOBALS['CONFIG_CMD_PREFIX'].'seen');
              BOT_RESPONSE('User Commands: '.$user_cmd);
              
        if (!empty($GLOBALS['CONFIG_BOT_ADMIN'])) {
            BOT_RESPONSE('Bot Admin: '.$GLOBALS['CONFIG_BOT_ADMIN']);
        }
    }

    CLI_MSG('[PLUGIN: help] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
            $GLOBALS['channel'], '1');
}
