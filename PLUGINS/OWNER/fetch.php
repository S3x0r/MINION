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

    $VERIFY             = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Downloads plugins from repository: '.commandPrefix().'fetch for help';
    $plugin_command     = 'fetch';

function plugin_fetch()
{
    /*
    if (OnEmptyArg('fetch list / fetch get <plugin> <permissions>')) {
    } else {
        if (extension_loaded('openssl')) {

            if (commandFromUser() == 'list') {
                $addr_list = 'https://raw.githubusercontent.com/S3x0r/minion_repository_plugins/master/plugin_list.db';
                $list = @file_get_contents($addr_list);

                if (!empty($list)) {
                    response('Repository list:');
                    response($list);
                    response('End list.');
                } else {
                         response('Cannot connect to fetch server, aborting.');
                }
            }

            if (msgPieces()[0] == 'get') {
                if (!empty(msgPieces()[1])) {
                    if (!empty(msgPieces()[2])) {
                        $dirs = array_diff(scandir(PLUGINSDIR), array('..', '.'));
                        if (in_array(msgPieces()[2], ['USER', 'ADMIN', 'OWNER'])) {
                            $check_file = PLUGINSDIR."/".msgPieces()[2]."/".msgPieces()[1].".php";
                           
                            $dir_user = array_diff(scandir(PLUGINSDIR.'/USER/'), array('..', '.'));
                            $dir_admin = array_diff(scandir(PLUGINSDIR.'/ADMIN/'), array('..', '.'));
                            $dir_owner = array_diff(scandir(PLUGINSDIR.'/OWNER/'), array('..', '.'));
                            $all_dirs = array_merge($dir_user, $dir_admin, $dir_owner);
                            
                            if (in_array(msgPieces()[1].'.php', $all_dirs)) {
                                response('I already have this plugin, aborting.');
                            } else {
                                     $address = loadValueFromConfigFile('FETCH', 'fetch server')."/".msgPieces()[1].".php";
                                if (@file_get_contents($address)) {
                                    response("Downloading plugin: '".msgPieces()[1]."' from repository to: '".msgPieces()[2]."'");

                                    $file = file_get_contents($address);
                                    $a = fopen(PLUGINSDIR."/".msgPieces()[2]."/".msgPieces()[2].".php", 'w');
                                    fwrite($a, $file);
                                    fclose($a);

                                    LoadPlugin(msgPieces()[1]);

                                    response('Plugin added.');
                                } else {
                                         response('No such plugin in repository.');
                                }
                            }
                        } else {
                                 response('I Dont have that permissions.');
                        }
                    } else {
                             response('I need permissions specified.');
                    }
                } else {
                         response('I need plugin name specified.');
                }
            }
        } else {
                 response('I cannot use this plugin, i need php_openssl extension to work!');
        }
    }
    */
}
