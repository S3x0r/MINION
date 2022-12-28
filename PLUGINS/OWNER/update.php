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

    $VERIFY             = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = "Updates the BOT if new version is available: {$GLOBALS['CONFIG.CMD.PREFIX']}update";
    $plugin_command     = 'update';

//------------------------------------------------------------------------------------------------
function plugin_update()
{
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
              cliLog('[bot] Cannot connect to update server');
    }
}
//------------------------------------------------------------------------------------------------
function v_checkVersion()
{

    $version = explode("\n", $GLOBALS['CheckVersion']);

    if ($version[0] > VER) {
        response('My version: '.VER.', version on server: '.$version[0].'');

        cliLog('[bot] New bot update on server: '.$version[0]);
        
        v_tryDownload();
    } else {
              response('No new update, you have the latest version.');
              cliLog('[bot] There is no new update');
    }
}
//------------------------------------------------------------------------------------------------
function v_tryDownload()
{
    response('Downloading update...');

    cliLog('[bot] Downloading update...');

    $newUpdate = file_get_contents($GLOBALS['v_source']);
    $dlHandler = fopen('update.zip', 'w');
      
    if (!fwrite($dlHandler, $newUpdate)) {
        response('Could not save new update, operation aborted');

        cliLog('[bot] Could not save new update, operation aborted');
    }

    fclose($dlHandler);
    response('Update Downloaded');
    cliLog('[bot] Update Downloaded');
    
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
    cliLog('[bot] Extracting update');

    /* Extracting update */
    $zip = new ZipArchive;
    if ($zip->open('update.zip') === true) {
        $zip->extractTo('.');
        $zip->close();
  
        response('Extracted.');
        cliLog('[bot] Extracted.');

        unlink('MINION-master/.gitattributes');

        /* copy from extracted dir to -> new dir */
        recurse_copy("MINION-master/", $GLOBALS['newdir']);

        /* delete downloaded zip */
        unlink('update.zip');

        /* delete extracted dir */
        delete_files('MINION-master/');

        //read config and put to new version conf
        $cfg = new IniParser($GLOBALS['configFile']);
        $GLOBALS['CONFIG.NICKNAME']       = $cfg->get("BOT", "nickname");
        $GLOBALS['CONFIG.NAME']           = $cfg->get("BOT", "name");
        $GLOBALS['CONFIG.IDENT']          = $cfg->get("BOT", "ident");
        $GLOBALS['CONFIG.SERVER']         = $cfg->get("SERVER", "server");
        $GLOBALS['CONFIG.PORT']           = $cfg->get("SERVER", "port");
        $GLOBALS['CONFIG.SERVER.PASSWD']  = $cfg->get("SERVER", "server.password");
        $GLOBALS['CONFIG.TRY.CONNECT']    = $cfg->get("SERVER", "try.connect");
        $GLOBALS['CONFIG.CONNECT.DELAY']  = $cfg->get("SERVER", "connect.delay");
        $GLOBALS['CONFIG.BOT.ADMIN']      = $cfg->get("OWNER", "bot.admin");
        $GLOBALS['CONFIG.AUTO.OP.LIST']   = $cfg->get("OWNER", "auto.op.list");
        $GLOBALS['CONFIG.OWNERS']         = $cfg->get("OWNER", "bot.owners");
        $GLOBALS['CONFIG.OWNER.PASSWD']   = $cfg->get("OWNER", "owner.password");
        $GLOBALS['CONFIG.ADMIN.LIST']     = $cfg->get("ADMIN", "admin.list");
        $GLOBALS['CONFIG.BOT.RESPONSE']   = $cfg->get("RESPONSE", "response");
        $GLOBALS['CONFIG.AUTO.OP']        = $cfg->get("AUTOMATIC", "auto.op");
        $GLOBALS['CONFIG.AUTO.REJOIN']    = $cfg->get("AUTOMATIC", "auto.rejoin");
        $GLOBALS['CONFIG.KEEPCHAN.MODES'] = $cfg->get("AUTOMATIC", "keep.chan.modes");
        $GLOBALS['CONFIG.KEEP.NICK']      = $cfg->get("AUTOMATIC", "keep.nick");
        $GLOBALS['CONFIG.CHANNEL']        = $cfg->get("CHANNEL", "channel");
        $GLOBALS['CONFIG.AUTO.JOIN']      = $cfg->get("CHANNEL", "auto.join");
        $GLOBALS['CONFIG.CHANNEL.MODES']  = $cfg->get("CHANNEL", "channel.modes");
        $GLOBALS['CONFIG.CHANNEL.KEY']    = $cfg->get("CHANNEL", "channel.key");
        $GLOBALS['CONFIG.BAN.LIST']       = $cfg->get("BANS", "ban.list");
        $GLOBALS['CONFIG.CMD.PREFIX']     = $cfg->get("COMMAND", "command.prefix");
        $GLOBALS['CONFIG.CTCP.RESPONSE']  = $cfg->get("CTCP", "ctcp.response");
        $GLOBALS['CONFIG.CTCP.FINGER']    = $cfg->get("CTCP", "ctcp.finger");
        $GLOBALS['CONFIG.CHANNEL.DELAY']  = $cfg->get("DELAYS", "channel.delay");
        $GLOBALS['CONFIG.PRIVATE.DELAY']  = $cfg->get("DELAYS", "private.delay");
        $GLOBALS['CONFIG.NOTICE.DELAY']   = $cfg->get("DELAYS", "notice.delay");
        $GLOBALS['CONFIG.LOGGING']        = $cfg->get("LOGS", "logging");
        $GLOBALS['CONFIG.WEB.LOGIN']      = $cfg->get("PANEL", "web.login");
        $GLOBALS['CONFIG.WEB.PASSWORD']   = $cfg->get("PANEL", "web.password");
        $GLOBALS['CONFIG.TIMEZONE']       = $cfg->get("TIME", "time.zone");
        $GLOBALS['CONFIG.FETCH.SERVER']   = $cfg->get("FETCH", "fetch.server");
        $GLOBALS['CONFIG.PLAY.SOUNDS']    = $cfg->get("PROGRAM", "play.sounds");
        $GLOBALS['CONFIG.SHOW.RAW']       = $cfg->get("DEBUG", "show.raw");

        // save to new config
        $new_cf = $GLOBALS['newdir'].'/CONFIG.INI';

        SaveData($new_cf, 'BOT', 'nickname', $GLOBALS['CONFIG.NICKNAME']);
        SaveData($new_cf, 'BOT', 'name', $GLOBALS['CONFIG.NAME']);
        SaveData($new_cf, 'BOT', 'ident', $GLOBALS['CONFIG.IDENT']);
        SaveData($new_cf, 'SERVER', 'server', $GLOBALS['CONFIG.SERVER']);
        SaveData($new_cf, 'SERVER', 'port', $GLOBALS['CONFIG.PORT']);
        SaveData($new_cf, 'SERVER', 'server.password', $GLOBALS['CONFIG.SERVER.PASSWD']);
        SaveData($new_cf, 'SERVER', 'try.connect', $GLOBALS['CONFIG.TRY.CONNECT']);
        SaveData($new_cf, 'SERVER', 'connect.delay', $GLOBALS['CONFIG.CONNECT.DELAY']);
        SaveData($new_cf, 'OWNER', 'bot.admin', $GLOBALS['CONFIG.BOT.ADMIN']);
        SaveData($new_cf, 'OWNER', 'auto.op.list', $GLOBALS['CONFIG.AUTO.OP.LIST']);
        SaveData($new_cf, 'OWNER', 'bot.owners', $GLOBALS['CONFIG.OWNERS']);
        SaveData($new_cf, 'OWNER', 'owner.password', $GLOBALS['CONFIG.OWNER.PASSWD']);
        SaveData($new_cf, 'ADMIN', 'admin.list', $GLOBALS['CONFIG.ADMIN.LIST']);
        SaveData($new_cf, 'RESPONSE', 'response', $GLOBALS['CONFIG.BOT.RESPONSE']);
        SaveData($new_cf, 'AUTOMATIC', 'auto.op', $GLOBALS['CONFIG.AUTO.OP']);
        SaveData($new_cf, 'AUTOMATIC', 'auto.rejoin', $GLOBALS['CONFIG.AUTO.REJOIN']);
        SaveData($new_cf, 'AUTOMATIC', 'keep.chan.modes', $GLOBALS['CONFIG.KEEPCHAN.MODES']);
        SaveData($new_cf, 'AUTOMATIC', 'keep.nick', $GLOBALS['CONFIG.KEEP.NICK']);
        SaveData($new_cf, 'CHANNEL', 'channel', $GLOBALS['CONFIG.CHANNEL']);
        SaveData($new_cf, 'CHANNEL', 'auto.join', $GLOBALS['CONFIG.AUTO.JOIN']);
        SaveData($new_cf, 'CHANNEL', 'channel.modes', $GLOBALS['CONFIG.CHANNEL.MODES']);
        SaveData($new_cf, 'CHANNEL', 'channel.key', $GLOBALS['CONFIG.CHANNEL.KEY']);
        SaveData($new_cf, 'BANS', 'ban.list', $GLOBALS['CONFIG.BAN.LIST']);
        SaveData($new_cf, 'COMMAND', 'command.prefix', $GLOBALS['CONFIG.CMD.PREFIX']);
        SaveData($new_cf, 'CTCP', 'ctcp.response', $GLOBALS['CONFIG.CTCP.RESPONSE']);
        SaveData($new_cf, 'CTCP', 'ctcp.finger', $GLOBALS['CONFIG.CTCP.FINGER']);
        SaveData($new_cf, 'DELAYS', 'channel.delay', $GLOBALS['CONFIG.CHANNEL.DELAY']);
        SaveData($new_cf, 'DELAYS', 'private.delay', $GLOBALS['CONFIG.PRIVATE.DELAY']);
        SaveData($new_cf, 'DELAYS', 'notice.delay', $GLOBALS['CONFIG.NOTICE.DELAY']);
        SaveData($new_cf, 'LOGS', 'logging', $GLOBALS['CONFIG.LOGGING']);
        SaveData($new_cf, 'PANEL', 'web.login', $GLOBALS['CONFIG.WEB.LOGIN']);
        SaveData($new_cf, 'PANEL', 'web.password', $GLOBALS['CONFIG.WEB.PASSWORD']);
        SaveData($new_cf, 'TIME', 'time.zone', $GLOBALS['CONFIG.TIMEZONE']);
        SaveData($new_cf, 'FETCH', 'fetch.server', $GLOBALS['CONFIG.FETCH.SERVER']);
        SaveData($new_cf, 'PROGRAM', 'play.sounds', $GLOBALS['CONFIG.PLAY.SOUNDS']);
        SaveData($new_cf, 'DEBUG', 'show.raw', $GLOBALS['CONFIG.SHOW.RAW']);

        // copy CONFIG from older version
        copy($GLOBALS['configFile'], $GLOBALS['newdir'].'/OLD_CONFIG.INI');

        // copy DATA folder
        recurse_copy(DATADIR, $GLOBALS['newdir'].'/'.DATADIR);

        // copy LOGS folder
        recurse_copy(LOGSDIR, $GLOBALS['newdir'].'/'.LOGSDIR);

        /* give op */
        if (BotOpped() == true) {
            toServer("MODE ".getBotChannel()." +o {$GLOBALS['USER']}");
        }

        // reconnect to run new version
        toServer("QUIT :Installing update...");
        cliLog('[bot] Restarting bot to new version...');
   
        // if windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cd '.$GLOBALS['newdir'].' & START_BOT.BAT');
        } else {
                  system('cd '.$GLOBALS['newdir'].' & php -f '.$GLOBALS['newdir'].'/BOT.php '
                  .$GLOBALS['newdir'].'/CONFIG.INI');
        }
        exit;
    } else {
              response('Failed to extract, aborting.');
              cliLog('[bot] Failed to extract update, aborting!');
    }
}
