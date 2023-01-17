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
    $plugin_description = "Shows BOT commands: ".loadValueFromConfigFile('COMMAND', 'command.prefix')."help";
    $plugin_command     = 'help';

/* TODO:
         -if plugin(s) dir empty do not show "commands" txt 
*/

function plugin_help()
{
    $prefix = loadValueFromConfigFile('COMMAND', 'command.prefix');

    $who = whoIsUser();

    /* owner */
    if ($who[1] == 0) {
        $plugs = null;

        foreach (CORECOMMANDSLIST as $coreCommand => $coreCmdInfo) {
            $plugs .= $prefix.$coreCommand.' ';
        }

        response('Core Plugins: '.$plugs);

        $allPlugins = implode(' ', $GLOBALS['ALL_PLUGINS']);
        $allPlugins = str_replace(' ', " $prefix", $allPlugins);

        response($prefix.$allPlugins);
    /* user */
    } else if ($who[1] == 999) {
               $userPlugins = implode(' ', $GLOBALS[getStandardUserName().'_PLUGINS']);
               $userPlugins = str_replace(' ', " $prefix", $userPlugins);
               
               response($who[0].' Plugins: '.$prefix.'seen '.$prefix.$userPlugins);
               
               !empty(loadValueFromConfigFile('OWNER', 'bot.admin')) ? response("Bot Admin: ".loadValueFromConfigFile('OWNER', 'bot.admin')) : false;
    
    /* all else */
    } else {
             $userPlugins = implode(' ', $GLOBALS[getStandardUserName().'_PLUGINS']);
             $userPlugins = str_replace(' ', " $prefix", $userPlugins);

             if (!empty($GLOBALS[$who[0].'_PLUGINS'])) {
                 $ownPlugins = implode(' ', $GLOBALS[$who[0].'_PLUGINS']);
                 $ownPlugins = str_replace(' ', " $prefix", $ownPlugins);
                 $ownPlugins = $prefix.$ownPlugins.' ';
             } else {
                      $ownPlugins = '';
             }


             if (!empty(returnNextUsersCommands($who[1]))) {
                 $msg = implode(' ', returnNextUsersCommands($who[1]));
                 $msg = str_replace(' ', " $prefix", $msg);
                 $msg = $prefix.$msg.' ';
             } else {
                      $msg = '';
             }

             response($who[0].' Plugins: '.$prefix.'seen '.
                                           $ownPlugins.
                                           $msg.
                                           $prefix.$userPlugins);
 
             !empty(loadValueFromConfigFile('OWNER', 'bot.admin')) ? response("Bot Admin: ".loadValueFromConfigFile('OWNER', 'bot.admin')) : false;
    }
}
