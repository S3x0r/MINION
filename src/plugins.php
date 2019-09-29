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

PHP_SAPI !== 'cli' ? exit('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
                           Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>') : false;
//---------------------------------------------------------------------------------------------------------

function LoadPlugins()
{
    $count1 = count(glob("../PLUGINS/OWNER/*.php", GLOB_BRACE));
    $count2 = count(glob("../PLUGINS/USER/*.php", GLOB_BRACE));
    $count3 = count(glob("../PLUGINS/ADMIN/*.php", GLOB_BRACE));

    $GLOBALS['OWNER_PLUGINS'] = null;
    $GLOBALS['ADMIN_PLUGINS'] = null;
    $GLOBALS['USER_PLUGINS'] = null;

//---------------------------------------------------------------------------------------------------------
    /* CORE PLUGINS */
    Color(">>> Core Commands (6) <<<".N, '11');
    Line(COLOR);
    Color("[load] -- Loads specified plugins to BOT: !load <plugin>".N, '14');
    Color("[panel] -- Starts web admin panel for BOT: !panel help".N, '14');
    Color("[pause] -- Pause all BOT activity: !pause".N, '14');
    Color("[seen] -- Check specified user when was last seen on channel: !seen <nickname>".N, '14');
    Color("[unload] -- Unloads specified plugin from BOT: !unload <plugin>".N, '14');
    Color("[unpause] -- Restore BOT from pause mode: !unpause".N, '14');

    Line(COLOR);
//---------------------------------------------------------------------------------------------------------
    /* OWNER PLUGINS */
    Color(">>> Owner Plugins ($count1) <<<".N, '11');
    Line(COLOR);

    foreach (glob('../PLUGINS/OWNER/*.php') as $plugin_name) {
         /* simple verify plugin */
         $file = file_get_contents($plugin_name);
        if (strpos($file, PLUGIN_HASH)) {
            include_once($plugin_name);
            $GLOBALS['OWNER_PLUGINS'] .= $GLOBALS['CONFIG_CMD_PREFIX'].''.$plugin_command.' ';
            $plugin_name = basename($plugin_name, '.php');
            Color("[$plugin_name] -- $plugin_description".N, '14');
        } else {
                 echo N."[ERROR] Not compatible plugin: $plugin_name".N.N;
        }
    }
    Line(COLOR);
//---------------------------------------------------------------------------------------------------------
    /* ADMIN PLUGINS */
    Color(">>> Admin Plugins ($count3) <<<".N, '11');
    Line(COLOR);

    foreach (glob('../PLUGINS/ADMIN/*.php') as $plugin_name) {
         /* simple verify plugin */
         $file = file_get_contents($plugin_name);
        if (strpos($file, PLUGIN_HASH)) {
            include_once($plugin_name);
            $GLOBALS['ADMIN_PLUGINS'] .= $GLOBALS['CONFIG_CMD_PREFIX'].''.$plugin_command.' ';
            $plugin_name = basename($plugin_name, '.php');
            Color("[$plugin_name] -- $plugin_description".N, '14');
        } else {
                 echo N."[ERROR] Not compatible plugin: $plugin_name".N.N;
        }
    }
    Line(COLOR);
//---------------------------------------------------------------------------------------------------------
    /* USER PLUGINS */
    Color(">>> User Plugins ($count2) <<<".N, '11');
    Line(COLOR);

    foreach (glob('../PLUGINS/USER/*.php') as $plugin_name) {
         /* simple verify plugin */
         $file = file_get_contents($plugin_name);
        if (strpos($file, PLUGIN_HASH)) {
            include_once($plugin_name);
            $GLOBALS['USER_PLUGINS'] .= $GLOBALS['CONFIG_CMD_PREFIX'].''.$plugin_command.' ';
            $plugin_name = basename($plugin_name, '.php');
            Color("[$plugin_name] -- $plugin_description".N, '14');
        } else {
                 echo N."[ERROR] Not compatible plugin: $plugin_name".N.N;
        }
    }
    $tot = $count1+$count2+$count3+6;
    
    if (!IsSilent()) {
        echo "----------------------------------------------------------".TR_25." ($tot)---------".N;
    }
//---------------------------------------------------------------------------------------------------------
    /* OWNER Plugins array */
    $GLOBALS['OWNER_PLUGINS'] = explode(" ", $GLOBALS['OWNER_PLUGINS']);
    
    /* ADMIN Plugins array */
    $GLOBALS['ADMIN_PLUGINS'] = explode(" ", $GLOBALS['ADMIN_PLUGINS']);

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

        if (in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) || in_array($with_prefix, $GLOBALS['ADMIN_PLUGINS']) ||
            in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) {
            if (($key = array_search($with_prefix, $GLOBALS['OWNER_PLUGINS'])) !== false) {
                unset($GLOBALS['OWNER_PLUGINS'][$key]);
                //TODO: rename function
                if (!in_array($with_prefix, $GLOBALS['OWNER_PLUGINS'])) {
                    CLI_MSG('[Plugin]: \''.$without_prefix.'\' '.TR_39.' '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].
                            ') | chan: '.$GLOBALS['channel'], '1');
                    BOT_RESPONSE(TR_40.' \''.$without_prefix.'\' unloaded.');
                }
            }
//---------------------------------------------------------------------------------------------------------
            if (($key = array_search($with_prefix, $GLOBALS['ADMIN_PLUGINS'])) !== false) {
                unset($GLOBALS['ADMIN_PLUGINS'][$key]);
                //TODO: rename function
                if (!in_array($with_prefix, $GLOBALS['ADMIN_PLUGINS'])) {
                    CLI_MSG('[Plugin]: \''.$without_prefix.'\' '.TR_39.' '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].
                            ') | chan: '.$GLOBALS['channel'], '1');
                    BOT_RESPONSE(TR_40.' \''.$without_prefix.'\' unloaded.');
                }
            }
//---------------------------------------------------------------------------------------------------------
            if (($key = array_search($with_prefix, $GLOBALS['USER_PLUGINS'])) !== false) {
                unset($GLOBALS['USER_PLUGINS'][$key]);
                //TODO: rename function
                if (!in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) {
                    CLI_MSG('[Plugin]: \''.$without_prefix.'\' '.TR_39.' '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].
                            ') | chan: '.$GLOBALS['channel'], '1');
                    BOT_RESPONSE(TR_40.' \''.$without_prefix.'\' unloaded.');
                }
            }
        } else {
                  CLI_MSG('[PLUGIN]: '.TR_42.': \''.$GLOBALS['piece1'].'\' by: '.$GLOBALS['USER'].' ('
                          .$GLOBALS['USER_HOST'].') | chan: '.$GLOBALS['channel'], '1');
                  BOT_RESPONSE(TR_42);
        }
    } catch (Exception $e) {
                              CLI_MSG('[ERROR]: '.TR_49.' '.__FUNCTION__.' '.TR_50, '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function LoadPlugin($plugin)
{
    try {
           $with_prefix    = $GLOBALS['CONFIG_CMD_PREFIX'].$plugin;
           $without_prefix = $plugin;

        if (in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) || in_array($with_prefix, $GLOBALS['ADMIN_PLUGINS']) ||
            in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) {
            BOT_RESPONSE(TR_41);

          /* if there is no plugin name in plugins array */
        } elseif (!in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) ||
            !in_array($with_prefix, $GLOBALS['ADMIN_PLUGINS']) || !in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) {
            /* if no plugin in array & file exists in dir */
            if (is_file('../PLUGINS/OWNER/'.$without_prefix.'.php')) {
                /* include that file */
                include_once('../PLUGINS/OWNER/'.$without_prefix.'.php');

                /* add prefix & plugin name to plugins array */
                array_push($GLOBALS['OWNER_PLUGINS'], $with_prefix);
 
                /* bot responses */
                BOT_RESPONSE(TR_40.' \''.$without_prefix.'\' '.TR_38);
                CLI_MSG('[PLUGIN]: '.TR_61.' '.$without_prefix.', '.TR_48.' '.$GLOBALS['USER'].' ('
                        .$GLOBALS['USER_HOST'].') | chan: '.$GLOBALS['channel'], '1');
            } elseif (is_file('../PLUGINS/ADMIN/'.$without_prefix.'.php')) {
                /* include that file */
                include_once('../PLUGINS/ADMIN/'.$without_prefix.'.php');

                /* add prefix & plugin name to plugins array */
                array_push($GLOBALS['ADMIN_PLUGINS'], $with_prefix);

                /* bot responses */
                BOT_RESPONSE(TR_40.' \''.$without_prefix.'\' '.TR_38);
                CLI_MSG('[PLUGIN]: '.TR_61.' '.$without_prefix.', '.TR_48.' '.$GLOBALS['USER'].' ('
                        .$GLOBALS['USER_HOST'].') | chan: '.$GLOBALS['channel'], '1');
            } elseif (is_file('../PLUGINS/USER/'.$without_prefix.'.php')) {
                /* include that file */
                include_once('../PLUGINS/USER/'.$without_prefix.'.php');

                /* add prefix & plugin name to plugins array */
                array_push($GLOBALS['USER_PLUGINS'], $with_prefix);

                /* bot responses */
                BOT_RESPONSE(TR_40.' \''.$without_prefix.'\' '.TR_38);
                CLI_MSG('[PLUGIN]: '.TR_61.' '.$without_prefix.', '.TR_48.' '.$GLOBALS['USER'].' ('
                        .$GLOBALS['USER_HOST'].') | chan: '.$GLOBALS['channel'], '1');
            } else {
                     BOT_RESPONSE('No such plugin to load.');
            }
        }
    } catch (Exception $e) {
                             CLI_MSG('[ERROR]: '.TR_49.' '.__FUNCTION__.' '.TR_50, '1');
    }
}
