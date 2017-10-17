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
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

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
                 BOT_RESPONSE('plugin move   - Move plugin from OWNER dir to USER directory: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'plugin move <plugin_name>');
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
                if (file_exists('PLUGINS/USER/'.$GLOBALS['piece2'].'.php') xor
                file_exists('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php')) {
                    if (file_exists('PLUGINS/USER/'.$GLOBALS['piece2'].'.php')) {
                        unlink('PLUGINS/USER/'.$GLOBALS['piece2'].'.php');
                        BOT_RESPONSE('Plugin: '.$GLOBALS['piece2'].' removed from: USER dir.');
                        CLI_MSG('[BOT] !plugin delete on: '.$GLOBALS['channel'].', by: '
                        .$GLOBALS['USER'].', deleted: '.$GLOBALS['piece2'], '1');
                    } elseif (file_exists('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php')) {
                               unlink('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php');
                               BOT_RESPONSE('Plugin: '.$GLOBALS['piece2'].' removed from OWNER dir.');
                               CLI_MSG('[BOT] !plugin delete on: '.$GLOBALS['channel'].', by: '
                               .$GLOBALS['USER'].', deleted: '.$GLOBALS['piece2'], '1');
                    }
                } else {
                      BOT_RESPONSE('No such plugin, wrong name?');
                      CLI_MSG('[BOT] !plugin delete on: '.$GLOBALS['channel'].', by: '
                      .$GLOBALS['USER'].', NO SUCH PLUGIN: '.$GLOBALS['piece2'], '1');
                }
                break;
//---------------------------------------------------------------------------------------------------------
            case 'move':
                if (file_exists('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php')) {
                    rename('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php', 'PLUGINS/USER/'.$GLOBALS['piece2'].'.php');
                    BOT_RESPONSE('Plugin: '.$GLOBALS['piece2'].' moved to USER dir.');
                    CLI_MSG('!plugin move on: '.$GLOBALS['channel'].', by: '.$GLOBALS['USER'].
                    ', moved plugin to USER: '.$GLOBALS['piece2'], '1');
                } elseif (!file_exists('PLUGINS/OWNER/'.$GLOBALS['piece2'].'.php')) {
                           BOT_RESPONSE('No such plugin in OWNER dir, wrong name?');
                           CLI_MSG('[BOT] !plugin move on: '.$GLOBALS['channel'].', by: '
                           .$GLOBALS['USER'].', NO SUCH PLUGIN: '.$GLOBALS['piece2'], '1');
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
