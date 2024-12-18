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
    $plugin_description = 'Checking for updates: '.commandPrefix().'checkupdate';
    $plugin_command     = 'checkupdate';
//------------------------------------------------------------------------------------------------
function plugin_checkupdate()
{
    if (extension_loaded('openssl')) {
        $versionData = file_get_contents(VERSION_URL);

        if (!empty($versionData)) {
            $version = explode("\n", $versionData);

            if ($version[0] > VER) {
                response('New version available!');
                response('My version: '.VER.', version on server: '.$version[0]);
                response('To update BOT, use '.commandPrefix().'update');
            } else {
                     response('No new update, you have the latest version.');
            }
        } else {
                 response('Cannot connect to update server, try next time.');
        }
    } else {
             response('I cannot use this plugin, i need php_openssl extension to work!');
    }
}
