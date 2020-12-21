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
    $plugin_description = "Shows BOT commands: {$GLOBALS['CONFIG_CMD_PREFIX']}help";
    $plugin_command     = 'help';

/* TODO: -move plugin to core commands
         -if plugin(s) dir empty do not show "commands" txt 
*/

function plugin_help()
{
    /* if OWNER use help */
    if (HasOwner($GLOBALS['mask'])) {
        response('Core Commands: '.
                 $GLOBALS['CONFIG_CMD_PREFIX'].'load '.
                 $GLOBALS['CONFIG_CMD_PREFIX'].'panel '.
                 $GLOBALS['CONFIG_CMD_PREFIX'].'pause '.
                 $GLOBALS['CONFIG_CMD_PREFIX'].'seen '.
                 $GLOBALS['CONFIG_CMD_PREFIX'].'unload '.
                 $GLOBALS['CONFIG_CMD_PREFIX'].'unpause');

        response('Owner Commands: '.implode(' ', $GLOBALS['OWNER_PLUGINS']));
        response('Admin Commands: '.implode(' ', $GLOBALS['ADMIN_PLUGINS']));
        response('User Commands: '.implode(' ', $GLOBALS['USER_PLUGINS']));

      /* if ADMIN use help */
    } elseif (!HasOwner($GLOBALS['mask']) && HasAdmin($GLOBALS['mask'])) {
              response("Core Commands: {$GLOBALS['CONFIG_CMD_PREFIX']}seen");
              response('Admin Commands: '.implode(' ', $GLOBALS['ADMIN_PLUGINS']));
              response('User Commands: '.implode(' ', $GLOBALS['USER_PLUGINS']));

        !empty($GLOBALS['CONFIG_BOT_ADMIN']) ? response("Bot Admin: {$GLOBALS['CONFIG_BOT_ADMIN']}") : false;
      
      /* if USER use help */
    } elseif (!HasOwner($GLOBALS['mask']) && !HasAdmin($GLOBALS['mask'])) {
              response("Core Commands: {$GLOBALS['CONFIG_CMD_PREFIX']}seen");
              response('User Commands: '.implode(' ', $GLOBALS['USER_PLUGINS']));
              
        !empty($GLOBALS['CONFIG_BOT_ADMIN']) ? response("Bot Admin: {$GLOBALS['CONFIG_BOT_ADMIN']}") : false;
    }
}
