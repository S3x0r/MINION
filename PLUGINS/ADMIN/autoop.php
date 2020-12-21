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
    $plugin_description = "Adds host to autoop list in config file: {$GLOBALS['CONFIG_CMD_PREFIX']}autoop <nick!ident@host>";
    $plugin_command     = 'autoop';

function plugin_autoop()
{
    $nick = explode('!', trim($GLOBALS['args']));

    if (OnEmptyArg('autoop <nick!ident@hostname>')) {
    } elseif ($nick[0] != getBotNickname()) {
        if (preg_match('/^(.+?)!(.+?)@(.+?)$/', $GLOBALS['args'], $host)) {
            LoadData($GLOBALS['configFile'], 'OWNER', 'auto_op_list');

            if (strpos($GLOBALS['LOADED'], $GLOBALS['args']) !== false) {
                response('I already have this host.');
            } else {
                empty($GLOBALS['LOADED']) ? $new_list = $host[0] : $new_list = "{$GLOBALS['LOADED']}, {$host[0]}";
 
                     SaveData($GLOBALS['configFile'], 'OWNER', 'auto_op_list', $new_list);

                     /* update variable with new owners */
                     $cfg = new IniParser($GLOBALS['configFile']);
                     $GLOBALS['CONFIG_AUTO_OP_LIST'] = $cfg->get("OWNER", "auto_op_list");

                     /* Inform nick about it */
                     privateMsg('From now you are on my auto op list, enjoy.');

                     response("Host: '{$host[0]}' added to auto op list.");
            }
        } else {
                 response('Bad input, try: nick!ident@hostname');
        }
    } else {
             response('I cannot add myself to auto op list, im already OP MASTER :)');
    }
}
