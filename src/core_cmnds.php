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

function plugin_seen()
{
    if (OnEmptyArg('seen <nickname> to check specified user when was last seen on channel')) {
    } else { /* prevent directory traversal */
             $data = str_replace('..', '', str_replace('/', '', msgAsArguments())); //to sprawdzić czy ok?
        if ($data == getBotNickname()) {
            response('Yes im here! :)');
        } elseif ($data == userPreg()[0]) {
                  response('Look at mirror!');
        } elseif ($data == 'owner') {
            !empty(loadValueFromConfigFile('OWNER', 'bot.admin')) ? response('My Owner: '.loadValueFromConfigFile('OWNER', 'bot.admin')) : false;
        } else {
                 /* revert from illegal chars file */
                 $data = removeIllegalCharsFromNickname($data);

            is_file(DATADIR.'/'.SEENDIR.'/'.$data) ?
                response(file_get_contents(DATADIR.'/'.SEENDIR.'/'.$data)) : response('No such user in my database.');
            
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function plugin_pause()
{
    response('Pausing all activity');
  
    setPause();

    cliLog("[PLUGIN: pause] Used by: ".userPreg()[0]." (".userPreg()[3]."), channel: ".getBotChannel());
    cliLog('[bot] Im in Pause mode');
}
//---------------------------------------------------------------------------------------------------------
function plugin_unpause()
{
    unsetPause();

    response('Back to life!');

    cliLog("[PLUGIN: unpause] Used by: ".userPreg()[0]." (".userPreg()[3]."), channel: ".getBotChannel());
    cliLog('[bot] Unpaused');
}
//---------------------------------------------------------------------------------------------------------
function plugin_load()
{
    if (empty(msgAsArguments())) {
        response("Usage ".loadValueFromConfigFile('COMMAND', 'command.prefix')."load <plugin_name>");
    } else {
             if (!empty(msgPieces()[0])) {
                 $plugin = msgPieces()[0];
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
         
                                  response("Plugin: '".$plugin."' loaded.");
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
}
//---------------------------------------------------------------------------------------------------------
function plugin_unload()
{
    if (empty(msgAsArguments())) {
        response("Usage ".loadValueFromConfigFile('COMMAND', 'command.prefix')."unload <plugin_name>");
    } else {
             $plugin = msgPieces()[0];
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
    $owner = getOwnerUserName();

    /* if owner host is empty */
    if (empty(loadValueFromConfigFile('PRIVILEGES', $owner))) {
        if (!isUserOwner()) {
            /* hash message from user to use for comparsion */
            $hashed = hash('sha256', msgAsArguments());
    
            /* if user password match password in config do the rest */
            if ($hashed == loadValueFromConfigFile('OWNER', 'owner.password')) {
                $botOwners = loadValueFromConfigFile('PRIVILEGES', $owner);
        
                cliLog("[bot] Successful registration as owner from: ".userPreg()[0]." (".userFullMask().")");

                $new = trim(userFullMask());

                empty($botOwners) ? $newList = $new : $newList = "{$botOwners}, {$new}";

                SaveValueToConfigFile('PRIVILEGES', $owner, $newList);

                /* Add host to auto op list */
                $autoOpList = loadValueFromConfigFile('AUTOMATIC', 'auto.op.list');

                $new = trim(userFullMask());

                empty($autoOpList) ? $newList = $new : $newList = "{$autoOpList}, {$new}";

                SaveValueToConfigFile('AUTOMATIC', 'auto.op.list', $newList);
      
                /* cli msg */
                cliLog("[bot] New Owner added: ".userPreg()[0]." (".userFullMask().")");
                cliLog("[bot] New Auto Op added: ".userPreg()[0]." (".userFullMask().")");

                /* send information to user about commands */
                response('From now you are my owner, enjoy!');

                /* show core Plugins */
                $response = null;
           
                foreach (CORECOMMANDSLIST as $corePlugin => $corePluginDescription) {
                    $response .= loadValueFromConfigFile('COMMAND', 'command.prefix').$corePlugin.' ';
                }

                response("Core Plugins: ".$response);
                
                $prefix = loadValueFromConfigFile('COMMAND', 'command.prefix');

                $plug = implode(' ', $GLOBALS['ALL_PLUGINS']);
                $plug = str_replace(' ', " $prefix", $plug);
                $plug = $prefix.$plug.' ';

                response('All Plugins: '.$plug);

                /* give op */
                if (BotOpped() == true) {
                    toServer("MODE ".getBotChannel()." +o ".userPreg()[0]);
                }
            }
        } else {
                 $hashed = hash('sha256', msgAsArguments());
                 /* if user is already an owner */
                 $hashed == loadValueFromConfigFile('OWNER', 'owner.password') ? response('You are already my owner') : false;
        }
    }
}
