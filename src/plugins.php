<?php
/* Copyright (c) 2013-2017, S3x0r <olisek@gmail.com>
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
    die('<h2>This script can\'t be run from a web browser. Use CLI to run it -> php BOT.php</h2>');
}
//---------------------------------------------------------------------------------------------------------
function LoadPlugins()
{
    $count1 = count(glob("../PLUGINS/OWNER/*.php", GLOB_BRACE));
    $GLOBALS['OWNER_PLUGINS'] = null;

    if (!IsSilent()) {
        /* CORE COMMANDS */
        CLI_MSG('Core Commands (3):', '0');
        Line(COLOR);
        echo 'load -- Loads specified plugins to BOT: !load <plugin>'.PHP_EOL;
        echo 'panel -- Starts web admin panel for BOT: !panel help'.PHP_EOL;
        echo 'unload -- Unloads specified plugin from BOT: !unload <plugin>'.PHP_EOL;
        Line(COLOR);
        
        /* OWNERS PLUGINS */
        CLI_MSG(TR_23." ($count1):", '0');
        Line(COLOR);
    }
    foreach (glob('../PLUGINS/OWNER/*.php') as $plugin_name) {
         /* simple verify plugin */
         $file = file_get_contents($plugin_name);
        if (strpos($file, PLUGIN_HASH)) {
            include_once($plugin_name);
            $GLOBALS['OWNER_PLUGINS'] .= $GLOBALS['CONFIG_CMD_PREFIX'].''.$plugin_command.' ';
            $plugin_name = basename($plugin_name, '.php');
            if (!IsSilent()) {
                echo "$plugin_name -- $plugin_description".PHP_EOL;
            }
        } else {
                 echo PHP_EOL."[ERROR] Not compatible plugin: $plugin_name".PHP_EOL.PHP_EOL;
        }
    }
    if (!IsSilent()) {
        Line(COLOR);
    }
//---------------------------------------------------------------------------------------------------------
    $count2 = count(glob("../PLUGINS/USER/*.php", GLOB_BRACE));

    $GLOBALS['USER_PLUGINS'] = null;

    if (!IsSilent()) {
        CLI_MSG(TR_24." ($count2):", '0');
        Line(COLOR);
    }
    foreach (glob('../PLUGINS/USER/*.php') as $plugin_name) {
         /* simple verify plugin */
         $file = file_get_contents($plugin_name);
        if (strpos($file, PLUGIN_HASH)) {
            include_once($plugin_name);
            $GLOBALS['USER_PLUGINS'] .= $GLOBALS['CONFIG_CMD_PREFIX'].''.$plugin_command.' ';
            $plugin_name = basename($plugin_name, '.php');
            if (!IsSilent()) {
                echo "$plugin_name -- $plugin_description".PHP_EOL;
            }
        } else {
                 echo PHP_EOL."[ERROR] Not compatible plugin: $plugin_name".PHP_EOL.PHP_EOL;
        }
    }
    $tot = $count1+$count2+3;
    
    if (!IsSilent()) {
        echo "----------------------------------------------------------".TR_25." ($tot)---------".PHP_EOL;
    }

    /* OWNER Plugins array */
    $GLOBALS['OWNER_PLUGINS'] = explode(" ", $GLOBALS['OWNER_PLUGINS']);
    
    /* USER Plugins array */
    $GLOBALS['USER_PLUGINS'] = explode(" ", $GLOBALS['USER_PLUGINS']);

    /* time for socket */
    Connect();
}
//---------------------------------------------------------------------------------------------------------
function UnloadPlugin($plugin)
{
    try {
           $with_prefix = $GLOBALS['CONFIG_CMD_PREFIX'].$plugin;
           $without_prefix = $plugin;

        if (in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) || in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) {
            if (($key = array_search($with_prefix, $GLOBALS['OWNER_PLUGINS'])) !== false) {
                unset($GLOBALS['OWNER_PLUGINS'][$key]);
                //todo rename function
                if (!in_array($with_prefix, $GLOBALS['OWNER_PLUGINS'])) {
                    CLI_MSG('[Plugin]: \''.$without_prefix.'\' '.TR_39, '1');
                    BOT_RESPONSE(TR_40.' \''.$without_prefix.'\' '.TR_39);
                }
            }
            if (($key = array_search($with_prefix, $GLOBALS['USER_PLUGINS'])) !== false) {
                unset($GLOBALS['USER_PLUGINS'][$key]);
                //todo rename function
                if (!in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) {
                    CLI_MSG('[Plugin]: \''.$without_prefix.'\' '.TR_39, '1');
                    BOT_RESPONSE(TR_40.' \''.$without_prefix.'\' '.TR_39);
                }
            }
        } else {
                  CLI_MSG('[PLUGIN]: '.TR_42, '1');
                  BOT_RESPONSE(TR_42);
        }
    } catch (Exception $e) {
                              BOT_RESPONSE(TR_49.' '.__FUNCTION__.' '.TR_50);
                              CLI_MSG('[ERROR]: '.TR_49.' '.__FUNCTION__.' '.TR_50, '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function LoadPlugin($plugin)
{
    try {
           $with_prefix    = $GLOBALS['CONFIG_CMD_PREFIX'].$plugin;
           $without_prefix = $plugin;

        if (in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) || in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) {
            BOT_RESPONSE(TR_41);

          /* if there is no plugin name in plugins array */
        } elseif (!in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) ||
            !in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) {
            /* if no plugin in array & file exists in dir */
            if (file_exists('PLUGINS/OWNER/'.$without_prefix.'.php')) {
                /* include that file */
                include_once('PLUGINS/OWNER/'.$without_prefix.'.php');

                /* add prefix & plugin name to plugins array */
                array_push($GLOBALS['OWNER_PLUGINS'], $with_prefix);
 
                /* bot responses */
                BOT_RESPONSE(TR_40.' \''.$without_prefix.'\''.TR_38);
                CLI_MSG('[PLUGIN]: '.TR_61.' '.$without_prefix.', '.TR_48.' '.$GLOBALS['USER'], '1');
            }

            /* if no plugin in array & file exists in dir */
            if (file_exists('PLUGINS/USER/'.$without_prefix.'.php')) {
                /* include that file */
                include_once('PLUGINS/USER/'.$without_prefix.'.php');

                /* add prefix & plugin name to plugins array */
                array_push($GLOBALS['USER_PLUGINS'], $with_prefix);

                /* bot responses */
                BOT_RESPONSE(TR_40.' \''.$without_prefix.'\''.TR_38);
                CLI_MSG('[PLUGIN]: '.TR_61.' '.$without_prefix.', '.TR_48.' '.$GLOBALS['USER'], '1');
            }
        }
    } catch (Exception $e) {
                             BOT_RESPONSE(TR_49.' '.__FUNCTION__.' '.TR_50);
                             CLI_MSG('[ERROR]: '.TR_49.' '.__FUNCTION__.' '.TR_50, '1');
    }
}
