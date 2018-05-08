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

if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
         Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>');
}
    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Plugins manipulation: '.$GLOBALS['CONFIG_CMD_PREFIX'].'plugin help to list commands';
    $plugin_command = 'plugin';

function plugin_plugin()
{
//---------------------------------------------------------------------------------------------------------
    if (OnEmptyArg('plugin <help> to list commands')) {
    } else {
        switch ($GLOBALS['args']) {
//---------------------------------------------------------------------------------------------------------
            case 'help':
                 BOT_RESPONSE('Plugin commands:');
                 BOT_RESPONSE('plugin delete - Deletes plugin from directory: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'plugin delete <plugin_name>');
                 BOT_RESPONSE('plugin move <plugin name> <from> <to> - Move plugin from group to group: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'plugin move <plugin name> <from> <to>');
                 BOT_RESPONSE('plugin load   - Load plugin to BOT: '.$GLOBALS['CONFIG_CMD_PREFIX'].
                     'plugin load <plugin_name>');
                 BOT_RESPONSE('plugin unload - Unload plugin from BOT: '.$GLOBALS['CONFIG_CMD_PREFIX'].
                     'plugin unload <plugin_name>');
                break;
//---------------------------------------------------------------------------------------------------------
        }

        switch ($GLOBALS['piece1']) {
//---------------------------------------------------------------------------------------------------------
            case 'delete':
                if (is_file('../PLUGINS/USER/'.$GLOBALS['piece2'].'.php') xor
                    is_file('../PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php') xor
                    is_file('../PLUGINS/ADMIN/'.$GLOBALS['piece2'].'.php')) {
                    if (is_file('../PLUGINS/USER/'.$GLOBALS['piece2'].'.php')) {
                        unlink('../PLUGINS/USER/'.$GLOBALS['piece2'].'.php');

                        BOT_RESPONSE('Plugin: '.$GLOBALS['piece2'].' removed from: USER dir.');

                        CLI_MSG('[PLUGIN: plugin] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                                $GLOBALS['channel'].' | deleted: '.$GLOBALS['piece2'], '1');
                    } elseif (is_file('../PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php')) {
                              unlink('../PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php');

                              BOT_RESPONSE('Plugin: '.$GLOBALS['piece2'].' removed from OWNER dir.');

                              CLI_MSG('[PLUGIN: plugin] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                                      $GLOBALS['channel'].' | deleted: '.$GLOBALS['piece2'], '1');
                    } elseif (is_file('../PLUGINS/ADMIN/'.$GLOBALS['piece2'].'.php')) {
                              unlink('../PLUGINS/ADMIN/'.$GLOBALS['piece2'].'.php');

                              BOT_RESPONSE('Plugin: '.$GLOBALS['piece2'].' removed from: ADMIN dir.');

                              CLI_MSG('[PLUGIN: plugin] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                                      $GLOBALS['channel'].' | deleted: '.$GLOBALS['piece2'], '1');
                    }
                } else {
                         BOT_RESPONSE('No such plugin.');
                }
                break;
//---------------------------------------------------------------------------------------------------------
            case 'move':
                if (is_dir('../PLUGINS')) {
                    if (!empty($GLOBALS['piece2']) && !empty($GLOBALS['piece3']) && !empty($GLOBALS['piece4'])) {
                        /* scan directory for groups */
                        $directory = '../PLUGINS';
                        $groups = array_diff(scandir($directory), array('..', '.'));
                        $GLOBALS['piece2'] = strtolower($GLOBALS['piece2']);
                        $GLOBALS['piece3'] = strtoupper($GLOBALS['piece3']);
                        $GLOBALS['piece4'] = strtoupper($GLOBALS['piece4']);

                        /* do we have group from input? */
                        if (in_array($GLOBALS['piece3'], $groups)) {
                            if (in_array($GLOBALS['piece4'], $groups)) {
                                /* try to move it */
                                if (is_file('../PLUGINS/'.$GLOBALS['piece3'].'/'.$GLOBALS['piece2'].'.php')) {
                                    rename(
                                        '../PLUGINS/'.$GLOBALS['piece3'].'/'.$GLOBALS['piece2'].'.php',
                                        '../PLUGINS/'.$GLOBALS['piece4'].'/'.$GLOBALS['piece2'].'.php'
                                    );

                                    BOT_RESPONSE('Plugin: '.$GLOBALS['piece2'].' moved to \''
                                                 .$GLOBALS['piece4'].'\' group.');

                                    CLI_MSG('[PLUGIN: plugin] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST']
                                            .') | chan: '.$GLOBALS['channel'].' | moved: '.$GLOBALS['piece2'], '1');
                                } elseif (!is_file('../PLUGINS/'.$GLOBALS['piece3'].'/'.$GLOBALS['piece2'].'.php')) {
                                          BOT_RESPONSE('No such plugin in \''.$GLOBALS['piece3'].'\' group!');
                                }
                            } else {
                                     BOT_RESPONSE('Group \''.$GLOBALS['piece4'].'\' does not exist.');
                            }
                        } else {
                                 BOT_RESPONSE('Group \''.$GLOBALS['piece3'].'\' does not exist.');
                        }
                    } else {
                             BOT_RESPONSE('use: <plugin_name> <from> <to>');
                    }
                } else {
                         BOT_RESPONE('Cannot find PLUGINS directory, deleted?');
                }
                break;
//---------------------------------------------------------------------------------------------------------
            case 'unload':
                if (!empty($GLOBALS['piece2'])) {
                    UnloadPlugin($GLOBALS['piece2']);
                }
                break;
//---------------------------------------------------------------------------------------------------------
            case 'load':
                if (!empty($GLOBALS['piece2'])) {
                    LoadPlugin($GLOBALS['piece2']);
                }
                break;
//---------------------------------------------------------------------------------------------------------
        }
    }
}
