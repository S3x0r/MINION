<?php
/* Copyright (c) 2013-2024, minions
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

function plugin_seen()
{
    $plugin_name        = 'seen';
    $plugin_arguments   = '<nickname>';
    $plugin_description = 'to check specified user when was last seen on channel';
    $plugin_info        = $plugin_name.' '.$plugin_arguments.' '.$plugin_description;
    
    if (OnEmptyArg($plugin_info)) {
    } else { /* prevent directory traversal */
             $data = str_replace('..', '', str_replace('/', '', commandFromUser()));
        if ($data == getBotNickname()) {
            response('Yes im here! :)');
        } elseif ($data == userNickname()) {
                  response('Look at mirror!');
        } elseif ($data == 'owner') {
                  !empty(loadValueFromConfigFile('OWNER', 'bot admin')) ? response('My Owner: '.loadValueFromConfigFile('OWNER', 'bot admin')) : false;
        } else {
                 /* revert from illegal chars file */
                 $data = removeIllegalCharsFromNickname($data);

                if (is_file(DATADIR.'/'.SEENDIR.'/'.$data)) {
                    response(file_get_contents(DATADIR.'/'.SEENDIR.'/'.$data));
                } else { 
                         response('No such user in my database.');
                }
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function plugin_load()
{
    $plugin_name        = 'load';
    $plugin_arguments   = '<plugin_name>';
    $plugin_description = 'load specified plugin';
    $plugin_info        = $plugin_name.' '.$plugin_arguments.' '.$plugin_description;

    if (OnEmptyArg($plugin_info)) {
    } else {
             $plugin = commandFromUser();
             $users = usersDirectoriesToArray();
             $lastKey = end($users);

             foreach($users as $user) {
                     if (@in_array($plugin, $GLOBALS[$user.'_PLUGINS'])) {
                         response('Plugin already loaded!');
                         break;
                     } else {
                              if (@!in_array($plugin, $GLOBALS[$user.'_PLUGINS']) && is_file(PLUGINSDIR.'/'.$user.'/'.$plugin.'.php')) {
                                  include_once(PLUGINSDIR.'/'.$user.'/'.$plugin.'.php');
         
                                  /* add plugin name to plugins array */
                                  array_push($GLOBALS[$user.'_PLUGINS'], $plugin);
         
                                  if (($key = array_search($plugin, $GLOBALS['ALL_PLUGINS'])) !== true) {
                                      array_push($GLOBALS['ALL_PLUGINS'], $plugin);
                                  }
         
                                  response("Plugin: '{$plugin}' loaded.");
                                  break;
                              } else {
                                       if ($user == $lastKey) {
                                           response('No such plugin to load.');
                                       }
                              }
                     }
             }
    }
}
//---------------------------------------------------------------------------------------------------------
function plugin_unload()
{
    $plugin_name        = 'unload';
    $plugin_arguments   = '<plugin_name>';
    $plugin_description = 'unload specified plugin';
    $plugin_info        = $plugin_name.' '.$plugin_arguments.' '.$plugin_description;

    if (OnEmptyArg($plugin_info)) {
    } else {
             $plugin = commandFromUser();
             $users = usersDirectoriesToArray();
             $lastKey = end($users);

             foreach($users as $user) {
                 if (@in_array($plugin, $GLOBALS[$user.'_PLUGINS'])) {
 
                     if (($key = array_search($plugin, $GLOBALS[$user.'_PLUGINS'])) !== false) {
                         unset($GLOBALS[$user.'_PLUGINS'][$key]);
 
                         if (($key = array_search($plugin, $GLOBALS['ALL_PLUGINS'])) !== false) {
                             unset($GLOBALS['ALL_PLUGINS'][$key]);
                         }
 
                         if (!in_array($plugin, $GLOBALS[$user.'_PLUGINS'])) {
                             response("Plugin: '{$plugin}' unloaded.");
                             break;
                         }
                     }
                 }
                 if ($user == $lastKey) {
                     response('No such plugin to unload.');
                 }
             }
    }
}
//---------------------------------------------------------------------------------------------------------
function plugin_register()
{
    /* if owner host is empty */
    if (empty(loadValueFromConfigFile('PRIVILEGES', getOwnerUserName()))) {
        /* hash message from user to use for comparsion */
        $hashed = hash('sha256', commandFromUser());
    
        /* if user password match password in config do the rest */
        if ($hashed == loadValueFromConfigFile('OWNER', 'owner password')) {
            $botOwnersFromConfig = loadValueFromConfigFile('PRIVILEGES', getOwnerUserName());
    
            $new = trim(userNickIdentAndHostname());

            empty($botOwnersFromConfig) ? $newList = $new : $newList = "{$botOwnersFromConfig}, {$new}";

            saveValueToConfigFile('PRIVILEGES', getOwnerUserName(), $newList);

            /* Add host to auto op list */
            if (!in_array($new, loadValueFromConfigFile('AUTOMATIC', 'auto op list'))) {
                saveValueToListConfigFile('AUTOMATIC', 'auto op list', $new);
            }

            bot_newUserRegisteredAsOwner();
        }
    }
}
