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

function LoadPlugins()
{
    $GLOBALS['ALL_PLUGINS'] = null;

    /* CORE PLUGINS */
    cli(">>> 'Core' Plugins (".CORECOUNT." Plugins) [lvl: 0] <<<");

    cliLine();

    foreach (CORECOMMANDSLIST as $corePlugin => $corePluginDescription) {
       cli('['.$corePlugin.'] -- '.$corePluginDescription);
    }

    cliLine();

    $pluginsSum = null;

    foreach (usersDirectoriesToArray() as $userDirectory) {
       $pluginsSum = $pluginsSum + countPlugins($userDirectory);
       
       $userLvl = getUserLevelByUserName($userDirectory);
       
       cli(">>> '".$userDirectory."' Plugins (".countPlugins($userDirectory)." Plugins) [lvl: ".$userLvl."] <<<");
       
       cliLine();
       
       /* add to $GLOBALS[$user.'_PLUGINS'] & include plugin */
       loadPluginsFromEachGroupDir($userDirectory);
       
       cliLine();
    }

    $pluginsSum = $pluginsSum + CORECOUNT;

    cli("----------------------------------------------------------Total: (".$pluginsSum.")---------");
}
//---------------------------------------------------------------------------------------------------------
function countPlugins($user)
{
    $count = count(glob(PLUGINSDIR."/{$user}/*.php"));

    if ($count == 0) {
        return 0;
    } else {
             return $count;
    }
}
//---------------------------------------------------------------------------------------------------------
function loadPluginsFromEachGroupDir($user)
{
    $GLOBALS[$user.'_PLUGINS'] = null;

    foreach (glob(PLUGINSDIR."/{$user}/*.php") as $pluginName) {
         /* simple verify plugin */
         if (preg_match("~\b".PLUGIN_HASH."\b~", file_get_contents($pluginName))) {
            include_once($pluginName);

            $GLOBALS[$user.'_PLUGINS'][] .= $plugin_command;
            $GLOBALS['ALL_PLUGINS'][] .= $plugin_command;

            cli('['.basename($pluginName, '.php').'] -- '.$plugin_description);
        } else {
                 cli('[ERROR: '.basename($pluginName, '.php').'] - Incompatible plugin!');
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
    global $rawcmd;
    $who = whoIsUser();

    /* owner */
    if ($who[1] == 0) {
        if (in_array_r(substr($rawcmd[1], 1), [coreCommandsToArray(), $GLOBALS['ALL_PLUGINS']])) {
            call_user_func('plugin_'.substr($rawcmd[1], 1));
            pluginUsageCli(substr($rawcmd[1], 1));
        }
    /* user */
    } else if ($who[1] == 999) {
               if (in_array_r(substr($rawcmd[1], 1), ['seen', $GLOBALS[$who[0].'_PLUGINS']])) {
                   call_user_func('plugin_'.substr($rawcmd[1], 1));
                   pluginUsageCli(substr($rawcmd[1], 1));
               }
    /* all else */
    } else {
             if (in_array_r(substr($rawcmd[1], 1), ['seen', $GLOBALS[$who[0].'_PLUGINS'], $GLOBALS[getStandardUserName().'_PLUGINS'], returnNextUsersCommands($who[1])])) {
                 call_user_func('plugin_'.substr($rawcmd[1], 1));
                 pluginUsageCli(substr($rawcmd[1], 1));
             }
    }
}
//---------------------------------------------------------------------------------------------------------
function getStandardUserName()
{
    $cfg = new IniParser(getConfigFileName());
    $section = $cfg->getsection("USERSLEVELS");

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
    $cfg = new IniParser(getConfigFileName());
    $section = $cfg->getsection("USERSLEVELS");

    foreach ($section as $user => $level) {
        if ($level == 0) {
            return $user;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function isUserOwner()
{
    debug("isUserOwner()");

    $cfg = new IniParser(getConfigFileName());
    $section = $cfg->getsection("PRIVILEGES");

    foreach ($section as $user => $mask) {
       if (fnmatch($mask, userFullMask(), 16)) {
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
    $cfg = new IniParser(getConfigFileName());
    $section = $cfg->getsection("PRIVILEGES");

    foreach ($section as $user => $mask) {
       $pieces = explode(", ", $mask);

       foreach ($pieces as $piece) {

          if (fnmatch($piece, userFullMask(), 16)) {
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
function returnNextUsersCommands($from) /* format: command command2 etc,. */
{
    $cfg = new IniParser(getConfigFileName());
    $section = $cfg->getsection("USERSLEVELS");

    $users = [];

    /* get user name */
    foreach ($section as $user => $level) {
        if ($level > $from && $level != 999) {
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
function getUserLevelByUserName($user)
{
    $cfg = new IniParser(getConfigFileName());
    $section = $cfg->getsection("USERSLEVELS");

    foreach ($section as $entry => $level) {
        if ($entry == $user) {
            return $level;
        }
    }
}
