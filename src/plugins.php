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

function loadPlugins()
{
    $GLOBALS['ALL_PLUGINS'] = null;

    if (loadValueFromConfigFile('PROGRAM', 'list plugins on start')) {
        $countedCoreCmnds = count(CORECOMMANDSLIST);

        /* CORE PLUGINS */
        cliNoLog('>>> \'Core\' Plugins ('.$countedCoreCmnds.' Plugins) [lvl: 0] <<<');

        cliLine();

        foreach (CORECOMMANDSLIST as $corePlugin => $corePluginDescription) {
           cliNoLog('['.$corePlugin.'] -- '.$corePluginDescription);
        }

        cliLine();

        $pluginsSum = null;
    }

    if (is_dir(PLUGINSDIR)) {
        if (!empty(usersDirectoriesToArray())) {
            foreach (usersDirectoriesToArray() as $userDirectory) {
               if (loadValueFromConfigFile('PROGRAM', 'list plugins on start')) {
                   $pluginsSum = $pluginsSum + countPlugins($userDirectory);

                   $userLvl = getUserLevelByUserName($userDirectory);

                   cliNoLog('>>> \''.$userDirectory.'\' Plugins ('.countPlugins($userDirectory).' Plugins) [lvl: '.$userLvl.'] <<<');

                   cliLine();
               }

               /* add to $GLOBALS[$user.'_PLUGINS'] & include plugin */
               loadPluginsFromEachGroupDir($userDirectory);

               if (loadValueFromConfigFile('PROGRAM', 'list plugins on start')) {
                   cliLine();
               }
            }
        }
    }

    if (loadValueFromConfigFile('PROGRAM', 'list plugins on start')) {
        $pluginsSum = $pluginsSum + $countedCoreCmnds;
       
        cliNoLog('--------------------------------------------------Total Plugins: ('.$pluginsSum.')---------');
    }
}
//---------------------------------------------------------------------------------------------------------
function countPlugins($_user)
{
    $count = count(glob(PLUGINSDIR."/{$_user}/*.php"));

    return ($count == 0) ? 0 : $count;
}
//---------------------------------------------------------------------------------------------------------
function loadPluginsFromEachGroupDir($_user)
{
    $GLOBALS[$_user.'_PLUGINS'] = null;

    foreach (glob(PLUGINSDIR."/{$_user}/*.php") as $pluginName) {
         /* simple verify plugin */
         if (preg_match("~\b".PLUGIN_HASH."\b~", file_get_contents($pluginName))) {
             include_once($pluginName);

             $GLOBALS[$_user.'_PLUGINS'][] .= $plugin_command;
             $GLOBALS['ALL_PLUGINS'][] .= $plugin_command;

             if (loadValueFromConfigFile('PROGRAM', 'list plugins on start')) {
                 cliNoLog('['.basename($pluginName, '.php').'] -- '.$plugin_description);
             }
        } else {
                 cliError(basename($pluginName, '.php').' - Incompatible plugin!');
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function usersDirectoriesToArray() /* return directory as groups array */
{
    $a = null;

    foreach (glob(PLUGINSDIR."/*", GLOB_ONLYDIR) as $group) {
       $a[] .= str_replace(PLUGINSDIR.'/', '', $group);
    }

    return $a;
}
//---------------------------------------------------------------------------------------------------------
function coreCommandsToArray() /* return core commands array */
{
    return array_keys(CORECOMMANDSLIST);
}
//---------------------------------------------------------------------------------------------------------
function ifPrivilegesExecuteCommand()
{
    cliDebug('ifPrivilegesExecuteCommand()');
    
    global $rawcmd;

    $who = whoIsUser();

    /* owner */
    if ($who[1] == 0) {
        if (in_array_r(substr($rawcmd[1], 1), [coreCommandsToArray(), $GLOBALS['ALL_PLUGINS']])) {
            call_user_func('plugin_'.substr($rawcmd[1], 1));
            cliPluginUsage(substr($rawcmd[1], 1));
        }
    /* user */
    } else if ($who[1] == 999) {
               if (in_array_r(substr($rawcmd[1], 1), ['seen', $GLOBALS[$who[0].'_PLUGINS']])) {
                   call_user_func('plugin_'.substr($rawcmd[1], 1));
                   cliPluginUsage(substr($rawcmd[1], 1));
               }
    /* all else */
    } else {
             if (in_array_r(substr($rawcmd[1], 1), ['seen', $GLOBALS[$who[0].'_PLUGINS'], $GLOBALS[getStandardUserName().'_PLUGINS'], returnNextUsersCommands($who[1])])) {
                 call_user_func('plugin_'.substr($rawcmd[1], 1));
                 cliPluginUsage(substr($rawcmd[1], 1));
             }
    }
}
//---------------------------------------------------------------------------------------------------------
function getStandardUserName()
{
    $section = json_decode(file_get_contents(getConfigFileName()), true)['USERSLEVELS'];

    /* get user name */
    foreach ($section as $user => $level) {
        if ($level == 999) {
            return $user;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function getOwnerUserName()
{
    $section = json_decode(file_get_contents(getConfigFileName()), true)['USERSLEVELS'];

    foreach ($section as $user => $level) {
        if ($level == 0) {
            return $user;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function isUserOwner()
{
    cliDebug('isUserOwner()');

    $section = json_decode(file_get_contents(getConfigFileName()), true)['PRIVILEGES'];

    foreach ($section as $user => $mask) {
       if (fnmatch($mask, userNickIdentAndHostname(), 16)) {
           $level = loadValueFromConfigFile('USERSLEVELS', $user);
           
           if ($level == 0) {
               return true;
           } else {
                    return false;
           }
       }
    }
}
//---------------------------------------------------------------------------------------------------------
function whoIsUser()
{
    $section = json_decode(file_get_contents(getConfigFileName()), true)['PRIVILEGES'];

    foreach ($section as $user => $mask) {
       $pieces = explode(", ", $mask);

       foreach ($pieces as $piece) {

          if (fnmatch($piece, userNickIdentAndHostname(), 16)) {
              $level = loadValueFromConfigFile('USERSLEVELS', $user);
              $data = [$user, $level, $mask];
          }
       }
    }

    if (!empty($data)) {
        return $data;
    } else {
        return [getStandardUserName(), '999'];
    }
}
//---------------------------------------------------------------------------------------------------------
function returnNextUsersCommands($_from) /* format: command command2 etc,. */
{
    $section = json_decode(file_get_contents(getConfigFileName()), true)['USERSLEVELS'];

    $users = [];

    /* get user name */
    foreach ($section as $user => $level) {
        if ($level > $_from && $level != 999) {
            $users[] .= $user;
        }
    }

    $commands = null;
    
    for ($i=0; $i<count($users); $i++) {
        if (!empty($GLOBALS[$users[$i].'_PLUGINS'])) {
            $commands .= json_encode($GLOBALS[$users[$i].'_PLUGINS'], true);
        }
    }

    if (!empty($commands)) {
        $commands = json_decode($commands);
        $commands = implode(' ',$commands);
        $commands = explode(' ',$commands);
       
        return $commands;
    }
}
//---------------------------------------------------------------------------------------------------------
function getUserLevelByUserName($_user)
{
    $section = json_decode(file_get_contents(getConfigFileName()), true)['USERSLEVELS'];

    foreach ($section as $entry => $level) {
        if ($entry == $_user) {
            return $level;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function allPluginsWithoutCoreString()
{
    $plug = implode(' ', $GLOBALS['ALL_PLUGINS']);
    $plug = str_replace(' ', ' '.commandPrefix(), $plug);
    
    return commandPrefix().$plug.' ';
}
//---------------------------------------------------------------------------------------------------------
function allPluginsString()
{
    $corePlugins = null;

    foreach (CORECOMMANDSLIST as $corePlugin => $corePluginDescription) {
        $corePlugins .= commandPrefix().$corePlugin.' ';
    }
    
    return $corePlugins;
}
//---------------------------------------------------------------------------------------------------------
/* if first arg after !plugin <arg> is empty */
function OnEmptyArg($_info)
{
    if (empty(commandFromUser())) {
        response('Usage: '.commandPrefix().$_info);
        return true;
    } else {
              return false;
    }
}
