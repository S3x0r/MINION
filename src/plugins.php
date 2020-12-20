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
    $CountedOwner = count(glob("PLUGINS/OWNER/*.php", GLOB_BRACE));
    $CountedAdmin = count(glob("PLUGINS/ADMIN/*.php", GLOB_BRACE));
    $CountedUser  = count(glob("PLUGINS/USER/*.php", GLOB_BRACE));
  
    $GLOBALS['OWNER_PLUGINS'] = null;
    $GLOBALS['ADMIN_PLUGINS'] = null;
    $GLOBALS['USER_PLUGINS'] = null;

//---------------------------------------------------------------------------------------------------------
    /* CORE PLUGINS */
    cli('>>> Core Commands ('.CORECOUNT.') <<<');
    Line();
    cli('[load] -- Loads specified plugins to BOT: !load <plugin>');
    cli('[panel] -- Starts web admin panel for BOT: !panel help');
    cli('[pause] -- Pause all BOT activity: !pause');
    cli('[seen] -- Check specified user when was last seen on channel: !seen <nickname>');
    cli('[unload] -- Unloads specified plugin from BOT: !unload <plugin>');
    cli('[unpause] -- Restore BOT from pause mode: !unpause');

    Line();
//---------------------------------------------------------------------------------------------------------
    /* OWNER PLUGINS */
    cli(">>> Owner Plugins ({$CountedOwner}) <<<");
    Line();

    foreach (glob('PLUGINS/OWNER/*.php') as $pluginName) {
         /* simple verify plugin */
         if (preg_match("~\b".PLUGIN_HASH."\b~", file_get_contents($pluginName))) {
            include_once($pluginName);
            $GLOBALS['OWNER_PLUGINS'] .= "{$GLOBALS['CONFIG_CMD_PREFIX']}{$plugin_command} ";
            $pluginName = basename($pluginName, '.php');
            cli("[{$pluginName}] -- {$plugin_description}");
        } else {
                 $pluginName = basename($pluginName, '.php');
                 cli("[ERROR: {$pluginName}] - Incompatible plugin!");
        }
    }
    (count(glob("PLUGINS/OWNER/*.php")) === 0) ? cli("(no plugins)") : false;
    Line();
//---------------------------------------------------------------------------------------------------------
    /* ADMIN PLUGINS */
    cli(">>> Admin Plugins ({$CountedAdmin}) <<<");
    Line();

    foreach (glob('PLUGINS/ADMIN/*.php') as $pluginName) {
         /* simple verify plugin */
        if (preg_match("~\b".PLUGIN_HASH."\b~", file_get_contents($pluginName))) {
            include_once($pluginName);
            $GLOBALS['ADMIN_PLUGINS'] .= "{$GLOBALS['CONFIG_CMD_PREFIX']}{$plugin_command} ";
            $pluginName = basename($pluginName, '.php');
            cli("[{$pluginName}] -- {$plugin_description}");
        } else {
                 $pluginName = basename($pluginName, '.php');
                 cli("[ERROR: {$pluginName}] - Incompatible plugin!");
        }
    }
    (count(glob("PLUGINS/ADMIN/*.php")) === 0) ? cli("(no plugins)") : false;
    Line();
//---------------------------------------------------------------------------------------------------------
    /* USER PLUGINS */
    cli(">>> User Plugins ({$CountedUser}) <<<");
    Line();

    foreach (glob('PLUGINS/USER/*.php') as $pluginName) {
         /* simple verify plugin */
        if (preg_match("~\b".PLUGIN_HASH."\b~", file_get_contents($pluginName))) {
            include_once($pluginName);
            $GLOBALS['USER_PLUGINS'] .= "{$GLOBALS['CONFIG_CMD_PREFIX']}{$plugin_command} ";
            $pluginName = basename($pluginName, '.php');
            cli("[{$pluginName}] -- {$plugin_description}");
        } else {
                 $pluginName = basename($pluginName, '.php');
                 cli("[ERROR: {$pluginName}] - Incompatible plugin!");
        }
    }

    (count(glob("PLUGINS/USER/*.php")) === 0) ? cli("(no plugins)") : false;

    $allCounted = CORECOUNT+$CountedOwner+$CountedAdmin+$CountedUser;
    
    cli("----------------------------------------------------------Total: ({$allCounted})---------");
    unset($allCounted);

//---------------------------------------------------------------------------------------------------------
    /* OWNER Plugins array */
    $GLOBALS['OWNER_PLUGINS'] = explode(" ", $GLOBALS['OWNER_PLUGINS']);
    
    /* ADMIN Plugins array */
    $GLOBALS['ADMIN_PLUGINS'] = explode(" ", $GLOBALS['ADMIN_PLUGINS']);

    /* USER Plugins array */
    $GLOBALS['USER_PLUGINS'] = explode(" ", $GLOBALS['USER_PLUGINS']);
}
//---------------------------------------------------------------------------------------------------------
function UnloadPlugin($plugin)
{
    try {
           $withPrefix    = $GLOBALS['CONFIG_CMD_PREFIX'].$plugin;
           $withoutPrefix = $plugin;

        if (in_array($withPrefix, $GLOBALS['OWNER_PLUGINS']) || in_array($withPrefix, $GLOBALS['ADMIN_PLUGINS']) ||
            in_array($withPrefix, $GLOBALS['USER_PLUGINS'])) {
            if (($key = array_search($withPrefix, $GLOBALS['OWNER_PLUGINS'])) !== false) {
                unset($GLOBALS['OWNER_PLUGINS'][$key]);
                //TODO: rename function
                if (!in_array($withPrefix, $GLOBALS['OWNER_PLUGINS'])) {
                    cliLog("[Plugin]: '{$withoutPrefix}' unloaded by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
                    response("Plugin: '{$withoutPrefix}' unloaded.");
                }
            }
//---------------------------------------------------------------------------------------------------------
            if (($key = array_search($withPrefix, $GLOBALS['ADMIN_PLUGINS'])) !== false) {
                unset($GLOBALS['ADMIN_PLUGINS'][$key]);
                //TODO: rename function
                if (!in_array($withPrefix, $GLOBALS['ADMIN_PLUGINS'])) {
                    cliLog("[Plugin]: '{$withoutPrefix}' unloaded by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
                    response("Plugin: '{$withoutPrefix}' unloaded.");
                }
            }
//---------------------------------------------------------------------------------------------------------
            if (($key = array_search($withPrefix, $GLOBALS['USER_PLUGINS'])) !== false) {
                unset($GLOBALS['USER_PLUGINS'][$key]);
                //TODO: rename function
                if (!in_array($withPrefix, $GLOBALS['USER_PLUGINS'])) {
                    cliLog("[Plugin]: '{$withoutPrefix}' unloaded by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
                    response("Plugin: '{$withoutPrefix}' unloaded.");
                }
            }
        } else {
                  cliLog("[PLUGIN]: No such plugin to unload: '{$GLOBALS['piece1']}' by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
                  response('No such plugin to unload');
        }
    } catch (Exception $e) {
                              cliLog('[ERROR]: Function: '.__FUNCTION__.' failed');
    }
}
//---------------------------------------------------------------------------------------------------------
function LoadPlugin($plugin)
{
    try {
           $withPrefix    = $GLOBALS['CONFIG_CMD_PREFIX'].$plugin;
           $withoutPrefix = $plugin;

        if (in_array($withPrefix, $GLOBALS['OWNER_PLUGINS']) || in_array($withPrefix, $GLOBALS['ADMIN_PLUGINS']) ||
            in_array($withPrefix, $GLOBALS['USER_PLUGINS'])) {
            response('Plugin already Loaded!');

          /* if there is no plugin name in plugins array */
        } elseif (!in_array($withPrefix, $GLOBALS['OWNER_PLUGINS']) ||
            !in_array($withPrefix, $GLOBALS['ADMIN_PLUGINS']) || !in_array($withPrefix, $GLOBALS['USER_PLUGINS'])) {
            /* if no plugin in array & file exists in dir */
            if (is_file("PLUGINS/OWNER/{$withoutPrefix}.php")) {
                /* include that file */
                include_once("PLUGINS/OWNER/{$withoutPrefix}.php");

                /* add prefix & plugin name to plugins array */
                array_push($GLOBALS['OWNER_PLUGINS'], $withPrefix);
 
                /* bot responses */
                response("Plugin: '{$withoutPrefix}' loaded.");
                cliLog("[PLUGIN]: Plugin Loaded: '{$withoutPrefix}', by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
            } elseif (is_file("PLUGINS/ADMIN/{$withoutPrefix}.php")) {
                /* include that file */
                include_once("PLUGINS/ADMIN/{$withoutPrefix}.php");

                /* add prefix & plugin name to plugins array */
                array_push($GLOBALS['ADMIN_PLUGINS'], $withPrefix);

                /* bot responses */
                response("Plugin: '{$withoutPrefix}' loaded.");
                cliLog("[PLUGIN]: Plugin Loaded: '{$withoutPrefix}', by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
            } elseif (is_file("PLUGINS/USER/{$withoutPrefix}.php")) {
                /* include that file */
                include_once("PLUGINS/USER/{$withoutPrefix}.php");

                /* add prefix & plugin name to plugins array */
                array_push($GLOBALS['USER_PLUGINS'], $withPrefix);

                /* bot responses */
                response("Plugin: '{$withoutPrefix}' loaded.");
                cliLog("[PLUGIN]: Plugin Loaded: '{$withoutPrefix}', by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
            } else {
                     response('No such plugin to load.');
            }
        }
    } catch (Exception $e) {
                             cliLog('[ERROR]: Function: '.__FUNCTION__.' failed');
    }
}
