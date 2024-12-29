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
    $plugin_description = 'Updates the BOT if new version is available: '.commandPrefix().'update';
    $plugin_command     = 'update';

//------------------------------------------------------------------------------------------------
function plugin_update()
{
    /*
    if (extension_loaded('openssl')) {
        v_connect();
    } else {
             response('I cannot use this plugin, i need php_openssl extension to work!');
    }
}
//------------------------------------------------------------------------------------------------
function v_connect()
{
    $GLOBALS['CheckVersion'] = file_get_contents(VERSION_URL);
    $GLOBALS['newdir']   = "../minion{$GLOBALS['CheckVersion']}";
    $GLOBALS['v_source'] = 'http://github.com/S3x0r/MINION/archive/master.zip';

    if (!empty($GLOBALS['CheckVersion'])) {
        v_checkVersion();
    } else {
              response('Cannot connect to update server, try next time.');
              cliBot('Cannot connect to update server');
    }
}
//------------------------------------------------------------------------------------------------
function v_checkVersion()
{

    $version = explode("\n", $GLOBALS['CheckVersion']);

    if ($version[0] > VER) {
        response('My version: '.VER.', version on server: '.$version[0].'');

        cliBot('New bot update on server: '.$version[0]);
        
        v_tryDownload();
    } else {
              response('No new update, you have the latest version.');
              cliBot('There is no new update');
    }
}
//------------------------------------------------------------------------------------------------
function v_tryDownload()
{
    response('Downloading update...');

    cliBot('Downloading update...');

    $newUpdate = file_get_contents($GLOBALS['v_source']);
    $dlHandler = fopen('update.zip', 'w');
      
    if (!fwrite($dlHandler, $newUpdate)) {
        response('Could not save new update, operation aborted');

        cliBot('Could not save new update, operation aborted');
    }

    fclose($dlHandler);
    response('Update Downloaded');
    cliBot('Update Downloaded');
    
    v_extract();
}
//------------------------------------------------------------------------------------------------
function recurse_copy($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src.'/'.$file)) {
                recurse_copy($src.'/'.$file, $dst.'/'.$file);
            } else {
                      copy($src.'/'.$file, $dst.'/'.$file);
            }
        }
    }
    closedir($dir);
}
//------------------------------------------------------------------------------------------------
function delete_files($target)
{
    if (is_dir($target)) {
        $files = glob($target . '*', GLOB_MARK);
        
        foreach ($files as $file) {
              delete_files($file);
        }
        rmdir($target);
    } elseif (is_file($target)) {
              unlink($target);
    }
}
//------------------------------------------------------------------------------------------------
function v_extract()
{
    response('Extracting update');
    cliBot('Extracting update');

    $zip = new ZipArchive;
    if ($zip->open('update.zip') === true) {
        $zip->extractTo('.');
        $zip->close();
  
        response('Extracted.');
        cliBot('Extracted.');

        unlink('MINION-master/.gitattributes');

        recurse_copy("MINION-master/", $GLOBALS['newdir']);

        unlink('update.zip');

        delete_files('MINION-master/');

        // copy CONFIG from older version
        copy(getConfigFileName(), $GLOBALS['newdir'].'/OLD_CONFIG.json');

        // copy DATA folder
        recurse_copy(DATADIR, $GLOBALS['newdir'].'/'.DATADIR);

        // copy LOGS folder
        recurse_copy(LOGSDIR, $GLOBALS['newdir'].'/'.LOGSDIR);

        opUser(userNickname());

        // reconnect to run new version
        quitFromServer('Installing update...');
        cliBot('Restarting bot to new version...');
   
        // if windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cd '.$GLOBALS['newdir'].' & START_BOT.BAT');
        } else {
                  system('cd '.$GLOBALS['newdir'].' & php -f '.$GLOBALS['newdir'].'/BOT.php '
                  .$GLOBALS['newdir'].'/CONFIG.json');
        }
        exit;
    } else {
              response('Failed to extract, aborting.');
              cliBot('Failed to extract update, aborting!');
    }

    */
}
