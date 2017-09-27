<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Updates the BOT if new version is available: '.$GLOBALS['CONFIG_CMD_PREFIX'].'update';
    $plugin_command = 'update';

//------------------------------------------------------------------------------------------------
function plugin_update()
{

    CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'update on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');

    v_connect();
}
//------------------------------------------------------------------------------------------------
function v_connect()
{

    $GLOBALS['v_addr']   = 'https://raw.githubusercontent.com/S3x0r/version-for-BOT/master/VERSION.TXT';
    $GLOBALS['CheckVersion'] = file_get_contents($GLOBALS['v_addr']);
    $GLOBALS['newdir']   = '../davybot'.$GLOBALS['CheckVersion'];
    $GLOBALS['v_source'] = 'http://github.com/S3x0r/davybot/archive/master.zip';

    if ($GLOBALS['CheckVersion'] !='') {
        v_checkVersion();
    } else {
              BOT_RESPONSE('Cannot connect to update server, try next time.');
              CLI_MSG('[BOT] Cannot connect to update server', '1');
    }
}
//------------------------------------------------------------------------------------------------
function v_checkVersion()
{

    $version = explode("\n", $GLOBALS['CheckVersion']);

    if ($version[0] > VER) {
        BOT_RESPONSE('My version: '.VER.', version on server: '.$version[0].'');
        CLI_MSG('[BOT] New bot update on server: '.$version[0], '1');
        v_tryDownload();
    } else {
              BOT_RESPONSE('No new update, you have the latest version.');
              CLI_MSG('[BOT] There is no new update', '1');
    }
}
//------------------------------------------------------------------------------------------------
function v_tryDownload()
{

    BOT_RESPONSE('Downloading update...');
    CLI_MSG('[BOT] Downloading update...', '1');

    $newUpdate = file_get_contents($GLOBALS['v_source']);
    $dlHandler = fopen('update.zip', 'w');
      
    if (!fwrite($dlHandler, $newUpdate)) {
        BOT_RESPONSE('Could not save new update, operation aborted');
        CLI_MSG('[BOT] Could not save new update, operation aborted', '1');
    }

    fclose($dlHandler);
    BOT_RESPONSE('Update Downloaded');
    CLI_MSG('[BOT] Update Downloaded', '1');
    
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

    BOT_RESPONSE('Extracting update');
    CLI_MSG('[BOT] Extracting update', '1');

    /* Extracting update */
    $zip = new ZipArchive;
    if ($zip->open('update.zip') === true) {
        $zip->extractTo('.');
        $zip->close();
  
        BOT_RESPONSE('Extracted.');
        CLI_MSG('[BOT] Extracted.', '1');

        unlink('davybot-master/.gitattributes');

        /* copy from extracted dir to -> new dir */
        recurse_copy("davybot-master/", $GLOBALS['newdir']);

        /* delete downloaded zip */
        unlink('update.zip');
        unlink($GLOBALS['newdir'].'/.gitattributes');

        /* delete extracted dir */
        delete_files('davybot-master');

        //read config and put to new version conf
        $cfg = new IniParser($GLOBALS['config_file']);
        $GLOBALS['CONFIG_NICKNAME']       = $cfg->get("BOT", "nickname");
        $GLOBALS['CONFIG_NAME']           = $cfg->get("BOT", "name");
        $GLOBALS['CONFIG_IDENT']          = $cfg->get("BOT", "ident");
        $GLOBALS['CONFIG_SERVER']         = $cfg->get("SERVER", "server");
        $GLOBALS['CONFIG_PORT']           = $cfg->get("SERVER", "port");
        $GLOBALS['CONFIG_TRY_CONNECT']    = $cfg->get("SERVER", "try_connect");
        $GLOBALS['CONFIG_CONNECT_DELAY']  = $cfg->get("SERVER", "connect_delay");
        $GLOBALS['CONFIG_AUTO_OP_LIST']   = $cfg->get("ADMIN", "auto_op_list");
        $GLOBALS['CONFIG_OWNERS']         = $cfg->get("ADMIN", "bot_owners");
        $GLOBALS['CONFIG_OWNER_PASSWD']   = $cfg->get("ADMIN", "owner_password");
        $GLOBALS['CONFIG_BOT_RESPONSE']   = $cfg->get("RESPONSE", "bot_response");
        $GLOBALS['CONFIG_AUTO_OP']        = $cfg->get("AUTOMATIC", "auto_op");
        $GLOBALS['CONFIG_AUTO_REJOIN']    = $cfg->get("AUTOMATIC", "auto_rejoin");
        $GLOBALS['CONFIG_KEEP_NICK']      = $cfg->get("AUTOMATIC", "keep_nick");
        $GLOBALS['CONFIG_CNANNEL']        = $cfg->get("CHANNEL", "channel");
        $GLOBALS['CONFIG_AUTO_JOIN']      = $cfg->get("CHANNEL", "auto_join");
        $GLOBALS['CONFIG_CHANNEL_KEY']    = $cfg->get("CHANNEL", "channel_key");
        $GLOBALS['CONFIG_CMD_PREFIX']     = $cfg->get("COMMAND", "command_prefix");
        $GLOBALS['CONFIG_CTCP_RESPONSE']  = $cfg->get("CTCP", "ctcp_response");
        //$GLOBALS['CONFIG_CTCP_VERSION']   = $cfg->get("CTCP", "ctcp_version");
        $GLOBALS['CONFIG_CTCP_FINGER']    = $cfg->get("CTCP", "ctcp_finger");
        $GLOBALS['CONFIG_LOGGING']        = $cfg->get("LOGS", "logging");
        $GLOBALS['CONFIG_LANGUAGE']       = $cfg->get("LANG", "language");
        $GLOBALS['CONFIG_TIMEZONE']       = $cfg->get("TIME", "time_zone");
        $GLOBALS['CONFIG_FETCH_SERVER']   = $cfg->get("FETCH", "fetch_server");
        $GLOBALS['CONFIG_SHOW_RAW']       = $cfg->get("DEBUG", "show_raw");

        // save to new config
        $new_cf = $GLOBALS['newdir'].'/CONFIG.INI';

        SaveData($new_cf, 'BOT', 'nickname', $GLOBALS['CONFIG_NICKNAME']);
        SaveData($new_cf, 'BOT', 'name', $GLOBALS['CONFIG_NAME']);
        SaveData($new_cf, 'BOT', 'ident', $GLOBALS['CONFIG_IDENT']);
        SaveData($new_cf, 'SERVER', 'server', $GLOBALS['CONFIG_SERVER']);
        SaveData($new_cf, 'SERVER', 'port', $GLOBALS['CONFIG_PORT']);
        SaveData($new_cf, 'SERVER', 'try_connect', $GLOBALS['CONFIG_TRY_CONNECT']);
        SaveData($new_cf, 'SERVER', 'connect_delay', $GLOBALS['CONFIG_CONNECT_DELAY']);
        SaveData($new_cf, 'ADMIN', 'auto_op_list', $GLOBALS['CONFIG_AUTO_OP_LIST']);
        SaveData($new_cf, 'ADMIN', 'bot_owners', $GLOBALS['CONFIG_OWNERS']);
        SaveData($new_cf, 'ADMIN', 'owner_password', $GLOBALS['CONFIG_OWNER_PASSWD']);
        SaveData($new_cf, 'RESPONSE', 'bot_response', $GLOBALS['CONFIG_BOT_RESPONSE']);
        SaveData($new_cf, 'AUTOMATIC', 'auto_op', $GLOBALS['CONFIG_AUTO_OP']);
        SaveData($new_cf, 'AUTOMATIC', 'auto_rejoin', $GLOBALS['CONFIG_AUTO_REJOIN']);
        SaveData($new_cf, 'AUTOMATIC', 'keep_nick', $GLOBALS['CONFIG_KEEP_NICK']);
        SaveData($new_cf, 'CHANNEL', 'channel', $GLOBALS['CONFIG_CNANNEL']);
        SaveData($new_cf, 'CHANNEL', 'auto_join', $GLOBALS['CONFIG_AUTO_JOIN']);
        SaveData($new_cf, 'CHANNEL', 'channel_key', $GLOBALS['CONFIG_CHANNEL_KEY']);
        SaveData($new_cf, 'COMMAND', 'command_prefix', $GLOBALS['CONFIG_CMD_PREFIX']);
        SaveData($new_cf, 'CTCP', 'ctcp_response', $GLOBALS['CONFIG_CTCP_RESPONSE']);
        //SaveData($new_cf, 'CTCP', 'ctcp_version', $GLOBALS['CONFIG_CTCP_VERSION']);
        SaveData($new_cf, 'CTCP', 'ctcp_finger', $GLOBALS['CONFIG_CTCP_FINGER']);
        SaveData($new_cf, 'LOGS', 'logging', $GLOBALS['CONFIG_LOGGING']);
        SaveData($new_cf, 'LANG', 'language', $GLOBALS['CONFIG_LANGUAGE']);
        SaveData($new_cf, 'TIME', 'time_zone', $GLOBALS['CONFIG_TIMEZONE']);
        SaveData($new_cf, 'FETCH', 'fetch_server', $GLOBALS['CONFIG_FETCH_SERVER']);
        SaveData($new_cf, 'DEBUG', 'show_raw', $GLOBALS['CONFIG_SHOW_RAW']);

        //copy CONFIG from old ver
        copy($GLOBALS['config_file'], $GLOBALS['newdir'].'/OLD_CONFIG.INI');

        // reconnect to run new version
        fputs($GLOBALS['socket'], "QUIT :Installing update...\n");
        CLI_MSG('[BOT] Restarting bot to new version...', '1');
   
        // if windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cd '.$GLOBALS['newdir'].' & START_BOT.BAT');
        } else {
                  system('cd '.$GLOBALS['newdir'].' & php -f '.$GLOBALS['newdir'].'/BOT.php '
                  .$GLOBALS['newdir'].'/CONFIG.INI');
        }
        die();
    } else {
              BOT_RESPONSE('Failed to extract, aborting.');
              CLI_MSG('[BOT] Failed to extract update, aborting!', '1');
    }
}
